<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserProfile;
use App\Http\Requests\StoreUserProfileRequest;
use App\Http\Requests\UpdateUserProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
    private function ModifyResponse($data, $status = 200)
    {
        $acceptHeader = strtolower(request()->header('Accept', ''));
        $formatParam = strtolower(request()->get('format', ''));

        if ($formatParam === 'xml') {
            return $this->convertToXml($data, $status);
        }

        if ($formatParam === 'json') {
            return response()->json($data, $status);
        }

        if (str_contains($acceptHeader, 'application/xml') ||
            str_contains($acceptHeader, 'text/xml') ||
            str_contains($acceptHeader, 'xml')) {
            return $this->convertToXml($data, $status);
        }

        return response()->json($data, $status);
    }

    private function convertToXml($data, $status = 200)
    {
        $cleanData = [
            'success' => $data['success'] ?? false,
            'message' => $data['message'] ?? '',
        ];

        if (isset($data['data'])) {
            if ($data['data'] instanceof \Illuminate\Database\Eloquent\Model) {
                $cleanData['profile'] = $this->extractModelData($data['data']);
            } elseif ($data['data'] instanceof \Illuminate\Support\Collection) {
                $cleanData['profiles'] = $data['data']->map(function ($item) {
                    return $this->extractModelData($item);
                })->toArray();
            } elseif (is_array($data['data'])) {
                $cleanData['data'] = $this->cleanArray($data['data']);
            }
        }

        if (isset($data['meta'])) {
            $cleanData['meta'] = $this->cleanArray($data['meta']);
        }

        if (isset($data['count'])) {
            $cleanData['count'] = $data['count'];
        }

        if (isset($data['error'])) {
            $cleanData['error'] = $data['error'];
        }

        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><response/>');
        $this->simpleArrayToXml($cleanData, $xml);

        return response($xml->asXML(), $status)
            ->header('Content-Type', 'application/xml');
    }

    /**
     * FIXED: Only return model attributes (attributesToArray())
     * NOT: relations, metadata, internal properties
     */
    private function extractModelData($model)
    {
        return $model->attributesToArray();
    }

    /**
     * FIXED: Remove internal "__" and "___" keys
     */
    private function cleanArray($array)
    {
        $result = [];

        foreach ($array as $key => $value) {
            if (is_string($key) && (str_starts_with($key, '__') || str_starts_with($key, '___'))) {
                continue;
            }

            if (is_array($value)) {
                $result[$key] = $this->cleanArray($value);
            } else {
                $result[$key] = $value;
            }
        }

        return $result;
    }

    private function simpleArrayToXml($data, &$xml)
    {
        foreach ($data as $key => $value) {
            if (is_numeric($key)) {
                $key = 'item';
            }

            $key = preg_replace('/[^a-zA-Z0-9_\-]/', '_', (string)$key);

            if (is_array($value)) {
                $sub = $xml->addChild($key);
                $this->simpleArrayToXml($value, $sub);
            } else {
                $xml->addChild($key, htmlspecialchars((string)$value));
            }
        }
    }

    public function index(Request $request)
    {
        try {
            $query = UserProfile::with(['user:id,name,email']);

            if ($request->has('search')) {
                $query->search($request->input('search'));
            }

            if ($request->has('status')) {
                $query->where('status', $request->input('status'));
            }

            if ($request->has('gender')) {
                $query->where('gender', $request->input('gender'));
            }

            if ($request->has('country')) {
                $query->where('country', $request->input('country'));
            }

            $perPage = $request->input('per_page', 15);
            $profiles = $query->paginate($perPage);

            return $this->ModifyResponse([
                'success' => true,
                'data' =>array_map(function ($item) {
                    return $this->extractModelData($item); // convert each model to clean array
                }, $profiles->items()),
                'meta' => [
                    'current_page' => $profiles->currentPage(),
                    'last_page' => $profiles->lastPage(),
                    'per_page' => $profiles->perPage(),
                    'total' => $profiles->total(),
                    'from' => $profiles->firstItem(),
                    'to' => $profiles->lastItem(),
                ]
            ], 200);

        } catch (\Exception $e) {
            return $this->ModifyResponse([
                'success' => false,
                'message' => 'Failed to fetch profiles',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(StoreUserProfileRequest $request)
    {
        DB::beginTransaction();

        try {
            $validated = $request->validated();

            if ($request->hasFile('avatar')) {
                $validated['avatar_url'] = $this->handleAvatarUpload($request->file('avatar'));
            }

            $profile = UserProfile::create($validated);

            DB::commit();

            return $this->ModifyResponse([
                'success' => true,
                'message' => 'Profile created successfully',
                'data' => $profile
            ], 201);

        } catch (\Exception $e) {
            DB::rollback();

            return $this->ModifyResponse([
                'success' => false,
                'message' => 'Failed to create profile',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(int $id)
    {
        try {
            $profile = UserProfile::findOrFail($id);

            return $this->ModifyResponse([
                'success' => true,
                'data' => $profile
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->ModifyResponse([
                'success' => false,
                'message' => 'Profile not found'
            ], 404);

        } catch (\Exception $e) {
            return $this->ModifyResponse([
                'success' => false,
                'message' => 'Failed to fetch profile',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(UpdateUserProfileRequest $request, int $id)
    {
        DB::beginTransaction();

        try {
            $profile = UserProfile::findOrFail($id);
            $validated = $request->validated();

            if ($request->hasFile('avatar')) {
                if ($profile->avatar_url) {
                    $this->deleteOldAvatar($profile->avatar_url);
                }
                $validated['avatar_url'] = $this->handleAvatarUpload($request->file('avatar'));
            }

            $profile->update($validated);

            DB::commit();

            return $this->ModifyResponse([
                'success' => true,
                'message' => 'Profile updated successfully',
                'data' => $profile
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollback();

            return $this->ModifyResponse([
                'success' => false,
                'message' => 'Profile not found'
            ], 404);

        } catch (\Exception $e) {
            DB::rollback();

            return $this->ModifyResponse([
                'success' => false,
                'message' => 'Failed to update profile',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(int $id)
    {
        DB::beginTransaction();

        try {
            $profile = UserProfile::findOrFail($id);

            if ($profile->avatar_url) {
                $this->deleteOldAvatar($profile->avatar_url);
            }

            $profile->delete();

            DB::commit();

            return $this->ModifyResponse([
                'success' => true,
                'message' => 'Profile deleted successfully'
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollback();

            return $this->ModifyResponse([
                'success' => false,
                'message' => 'Profile not found'
            ], 404);

        } catch (\Exception $e) {
            DB::rollback();

            return $this->ModifyResponse([
                'success' => false,
                'message' => 'Failed to delete profile',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getByUserId(int $userId)
    {
        try {
            $profile = UserProfile::where('user_id', $userId)->firstOrFail();

            return $this->ModifyResponse([
                'success' => true,
                'data' => $profile
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->ModifyResponse([
                'success' => false,
                'message' => 'Profile not found for this user'
            ], 404);

        } catch (\Exception $e) {
            return $this->ModifyResponse([
                'success' => false,
                'message' => 'Failed to fetch profile',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateStatus(Request $request, int $id)
    {
        $request->validate([
            'status' => 'required|in:active,inactive,suspended'
        ]);

        try {
            $profile = UserProfile::findOrFail($id);
            $profile->update(['status' => $request->input('status')]);

            return $this->ModifyResponse([
                'success' => true,
                'message' => 'Profile status updated successfully',
                'data' => $profile
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->ModifyResponse([
                'success' => false,
                'message' => 'Profile not found'
            ], 404);

        } catch (\Exception $e) {
            return $this->ModifyResponse([
                'success' => false,
                'message' => 'Failed to update status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function handleAvatarUpload($file): string
    {
        $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('avatars', $filename, 'public');

        return Storage::url($path);
    }

    private function deleteOldAvatar(string $avatarUrl): void
    {
        $path = str_replace('/storage/', '', parse_url($avatarUrl, PHP_URL_PATH));

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
