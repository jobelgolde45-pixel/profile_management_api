<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserProfile;
use App\Http\Requests\StoreUserProfileRequest;
use App\Http\Requests\UpdateUserProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
    /**
     * Display a listing of user profiles.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = UserProfile::with(['user:id,name,email']);
            
            // Search functionality
            if ($request->has('search')) {
                $query->search($request->input('search'));
            }
            
            // Filter by status
            if ($request->has('status')) {
                $query->where('status', $request->input('status'));
            }
            
            // Filter by gender
            if ($request->has('gender')) {
                $query->where('gender', $request->input('gender'));
            }
            
            // Filter by country
            if ($request->has('country')) {
                $query->where('country', $request->input('country'));
            }
            
            // Pagination
            $perPage = $request->input('per_page', 15);
            $profiles = $query->paginate($perPage);
            
            return response()->json([
                'success' => true,
                'data' => $profiles->items(),
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
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch profiles',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created user profile.
     */
    public function store(StoreUserProfileRequest $request): JsonResponse
    {
        DB::beginTransaction();
        
        try {
            $validated = $request->validated();
            
            // Handle avatar upload if provided
            if ($request->hasFile('avatar')) {
                $validated['avatar_url'] = $this->handleAvatarUpload($request->file('avatar'));
            }
            
            $profile = UserProfile::create($validated);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Profile created successfully',
                'data' => $profile->load(['user:id,name,email'])
            ], 201);
            
        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create profile',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified user profile.
     */
    public function show(int $id): JsonResponse
    {
        try {
            $profile = UserProfile::with(['user:id,name,email'])->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => $profile
            ], 200);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Profile not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch profile',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified user profile.
     */
    public function update(UpdateUserProfileRequest $request, int $id): JsonResponse
    {
        DB::beginTransaction();
        
        try {
            $profile = UserProfile::findOrFail($id);
            $validated = $request->validated();
            
            // Handle avatar upload if provided
            if ($request->hasFile('avatar')) {
                // Delete old avatar if exists
                if ($profile->avatar_url) {
                    $this->deleteOldAvatar($profile->avatar_url);
                }
                $validated['avatar_url'] = $this->handleAvatarUpload($request->file('avatar'));
            }
            
            $profile->update($validated);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'data' => $profile->fresh(['user:id,name,email'])
            ], 200);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Profile not found'
            ], 404);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update profile',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified user profile.
     */
    public function destroy(int $id): JsonResponse
    {
        DB::beginTransaction();
        
        try {
            $profile = UserProfile::findOrFail($id);
            
            // Delete avatar if exists
            if ($profile->avatar_url) {
                $this->deleteOldAvatar($profile->avatar_url);
            }
            
            $profile->delete();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Profile deleted successfully'
            ], 200);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Profile not found'
            ], 404);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete profile',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get profile by user ID.
     */
    public function getByUserId(int $userId): JsonResponse
    {
        try {
            $profile = UserProfile::with(['user:id,name,email'])
                ->where('user_id', $userId)
                ->firstOrFail();
            
            return response()->json([
                'success' => true,
                'data' => $profile
            ], 200);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Profile not found for this user'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch profile',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update profile status.
     */
    public function updateStatus(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'status' => 'required|in:active,inactive,suspended'
        ]);

        try {
            $profile = UserProfile::findOrFail($id);
            $profile->update(['status' => $request->input('status')]);
            
            return response()->json([
                'success' => true,
                'message' => 'Profile status updated successfully',
                'data' => $profile
            ], 200);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Profile not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle avatar file upload.
     */
    private function handleAvatarUpload($file): string
    {
        $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('avatars', $filename, 'public');
        
        return Storage::url($path);
    }

    /**
     * Delete old avatar file.
     */
    private function deleteOldAvatar(string $avatarUrl): void
    {
        $path = str_replace('/storage/', '', parse_url($avatarUrl, PHP_URL_PATH));
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}