<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XML API Tester - User Profiles</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Highlight.js for XML syntax highlighting -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/styles/atom-one-dark.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/highlight.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/languages/xml.min.js"></script>
    
    <style>
        .tab-active {
            @apply border-blue-500 text-blue-600;
        }
        .response-xml {
            white-space: pre-wrap;
            word-break: break-all;
            font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
            font-size: 13px;
        }
        .hljs {
            background: transparent !important;
        }
        .fade-in {
            animation: fadeIn 0.3s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .form-section {
            transition: all 0.3s ease;
        }
        .form-section:hover {
            @apply shadow-lg;
            transform: translateY(-2px);
        }
        .welcome-container{
                width: 100%;
                display:flex;
                flex-direction: row;
                justify-content: end;
                align-self: center;
                margin-bottom: 3rem;
            }
         .welcome-page{
                background: #4361ee;
                padding: .5rem 1rem;
                border-radius: 10px;
                text-decoration: none;
                color: #fff;
                margin-right: 2rem;
                margin-top: 2rem;
            }
    </style>
</head>
<body class="bg-gray-50">

     <div class="welcome-container">
                    <a href="{{ route('xml.page') }}" class="welcome-page">JSON page</a>
    </div>

    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="text-center mb-10">
            <h1 class="text-4xl font-bold text-gray-800 mb-3">
                <i class="fas fa-code text-blue-500 mr-3"></i>
                User Profiles XML API Tester
            </h1>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                Test all user profile endpoints with XML responses. Each form will automatically append <code class="bg-blue-100 text-blue-800 px-2 py-1 rounded">?format=xml</code> to requests.
            </p>
        </div>

        <!-- API Base URL Display -->
        {{-- <div class="bg-white rounded-xl shadow-md p-6 mb-8 border-l-4 border-blue-500">
            <div class="flex items-center">
                <i class="fas fa-link text-blue-500 text-xl mr-3"></i>
                <div>
                    <h3 class="font-semibold text-gray-700">API Base URL</h3>
                    <code class="text-gray-800 bg-gray-100 px-3 py-2 rounded-lg text-sm mt-1 inline-block">
                        {{ url('/api/profiles') }}
                    </code>
                </div>
            </div>
        </div> --}}

        <!-- Tabs for Different Endpoints -->
        <div class="mb-8">
            <div class="border-b border-gray-200">
                <nav class="flex flex-wrap -mb-px">
                    <button data-tab="get-all" class="tab-button inline-flex items-center py-3 px-4 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 transition-colors duration-200">
                        <i class="fas fa-list mr-2"></i> GET All Profiles
                    </button>
                    <button data-tab="get-single" class="tab-button inline-flex items-center py-3 px-4 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 transition-colors duration-200">
                        <i class="fas fa-user mr-2"></i> GET Single Profile
                    </button>
                    <button data-tab="create" class="tab-button inline-flex items-center py-3 px-4 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 transition-colors duration-200">
                        <i class="fas fa-plus-circle mr-2"></i> CREATE Profile
                    </button>
                    <button data-tab="update" class="tab-button inline-flex items-center py-3 px-4 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 transition-colors duration-200">
                        <i class="fas fa-edit mr-2"></i> UPDATE Profile
                    </button>
                    <button data-tab="delete" class="tab-button inline-flex items-center py-3 px-4 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 transition-colors duration-200">
                        <i class="fas fa-trash mr-2"></i> DELETE Profile
                    </button>
                    <button data-tab="by-user" class="tab-button inline-flex items-center py-3 px-4 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 transition-colors duration-200">
                        <i class="fas fa-users mr-2"></i> GET By User ID
                    </button>
                    <button data-tab="status" class="tab-button inline-flex items-center py-3 px-4 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 transition-colors duration-200">
                        <i class="fas fa-toggle-on mr-2"></i> Update Status
                    </button>
                </nav>
            </div>
        </div>

        <!-- Response Display Area -->
        <div class="bg-gray-900 rounded-xl shadow-lg mb-10 overflow-hidden fade-in" id="response-container" style="display: none;">
            <div class="bg-gray-800 px-6 py-4 flex justify-between items-center">
                <div class="flex items-center">
                    <i class="fas fa-code text-green-400 mr-3"></i>
                    <h3 class="text-white font-semibold">XML Response</h3>
                </div>
                <button onclick="copyResponse()" class="text-gray-300 hover:text-white transition-colors duration-200" title="Copy to clipboard">
                    <i class="fas fa-copy mr-1"></i> Copy
                </button>
            </div>
            <div class="p-6">
                <pre class="response-xml text-green-400" id="response-content"></pre>
            </div>
            <div class="bg-gray-800 px-6 py-3 text-sm text-gray-400 flex justify-between">
                <div id="response-status"></div>
                <div id="response-time"></div>
            </div>
        </div>

        <!-- Forms Container -->
        <div class="space-y-8">
            <!-- GET All Profiles Form -->
            <div id="get-all-form" class="form-section bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center mb-6">
                    <div class="bg-blue-100 p-3 rounded-lg mr-4">
                        <i class="fas fa-list text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">GET All User Profiles</h3>
                        <p class="text-gray-600">Retrieve all user profiles with optional filtering and pagination</p>
                    </div>
                </div>
                
                <form id="form-get-all" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Search Term</label>
                            <input type="text" name="search" placeholder="Search by name..." 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                                <option value="">All Statuses</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="suspended">Suspended</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                            <select name="gender" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                                <option value="">All Genders</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Per Page</label>
                            <input type="number" name="per_page" value="15" min="1" max="100" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                        </div>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 flex items-center">
                            <i class="fas fa-paper-plane mr-2"></i> Send GET Request
                        </button>
                    </div>
                </form>
            </div>

            <!-- GET Single Profile Form -->
            <div id="get-single-form" class="form-section bg-white rounded-xl shadow-md p-6" style="display: none;">
                <div class="flex items-center mb-6">
                    <div class="bg-green-100 p-3 rounded-lg mr-4">
                        <i class="fas fa-user text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">GET Single Profile</h3>
                        <p class="text-gray-600">Retrieve a specific user profile by ID</p>
                    </div>
                </div>
                
                <form id="form-get-single" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Profile ID</label>
                        <input type="number" name="id" required 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" 
                               placeholder="Enter profile ID">
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-200 flex items-center">
                            <i class="fas fa-paper-plane mr-2"></i> Send GET Request
                        </button>
                    </div>
                </form>
            </div>

            <!-- CREATE Profile Form -->
            <div id="create-form" class="form-section bg-white rounded-xl shadow-md p-6" style="display: none;">
                <div class="flex items-center mb-6">
                    <div class="bg-purple-100 p-3 rounded-lg mr-4">
                        <i class="fas fa-plus-circle text-purple-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">CREATE New Profile</h3>
                        <p class="text-gray-600">Create a new user profile</p>
                    </div>
                </div>
                
                <form id="form-create" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">User ID *</label>
                            <input type="number" name="user_id" required 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                            <input type="date" name="date_of_birth" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                            <input type="tel" name="phone_number" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                            <select name="gender" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                                <option value="">Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                            <textarea name="address" rows="2" 
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                            <input type="text" name="country" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="suspended">Suspended</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Bio</label>
                            <textarea name="bio" rows="3" 
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"></textarea>
                        </div>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-3 bg-purple-600 text-white font-medium rounded-lg hover:bg-purple-700 focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-all duration-200 flex items-center">
                            <i class="fas fa-paper-plane mr-2"></i> Send POST Request
                        </button>
                    </div>
                </form>
            </div>

            <!-- UPDATE Profile Form -->
            <div id="update-form" class="form-section bg-white rounded-xl shadow-md p-6" style="display: none;">
                <div class="flex items-center mb-6">
                    <div class="bg-yellow-100 p-3 rounded-lg mr-4">
                        <i class="fas fa-edit text-yellow-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">UPDATE Profile</h3>
                        <p class="text-gray-600">Update an existing user profile</p>
                    </div>
                </div>
                
                <form id="form-update" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Profile ID *</label>
                            <input type="number" name="id" required 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                            <input type="date" name="date_of_birth" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                            <input type="tel" name="phone_number" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                            <select name="gender" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                                <option value="">Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                            <textarea name="address" rows="2" 
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                            <input type="text" name="country" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="suspended">Suspended</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Bio</label>
                            <textarea name="bio" rows="3" 
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"></textarea>
                        </div>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-3 bg-yellow-600 text-white font-medium rounded-lg hover:bg-yellow-700 focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition-all duration-200 flex items-center">
                            <i class="fas fa-paper-plane mr-2"></i> Send PUT Request
                        </button>
                    </div>
                </form>
            </div>

            <!-- DELETE Profile Form -->
            <div id="delete-form" class="form-section bg-white rounded-xl shadow-md p-6" style="display: none;">
                <div class="flex items-center mb-6">
                    <div class="bg-red-100 p-3 rounded-lg mr-4">
                        <i class="fas fa-trash text-red-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">DELETE Profile</h3>
                        <p class="text-gray-600">Delete a user profile by ID</p>
                    </div>
                </div>
                
                <form id="form-delete" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Profile ID *</label>
                        <input type="number" name="id" required 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" 
                               placeholder="Enter profile ID to delete">
                    </div>
                    
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-red-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700">
                                    <strong>Warning:</strong> This action cannot be undone. The profile will be permanently deleted.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-3 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all duration-200 flex items-center">
                            <i class="fas fa-paper-plane mr-2"></i> Send DELETE Request
                        </button>
                    </div>
                </form>
            </div>

            <!-- GET Profile by User ID Form -->
            <div id="by-user-form" class="form-section bg-white rounded-xl shadow-md p-6" style="display: none;">
                <div class="flex items-center mb-6">
                    <div class="bg-indigo-100 p-3 rounded-lg mr-4">
                        <i class="fas fa-users text-indigo-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">GET Profile by User ID</h3>
                        <p class="text-gray-600">Retrieve profile associated with a specific user</p>
                    </div>
                </div>
                
                <form id="form-by-user" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">User ID *</label>
                        <input type="number" name="user_id" required 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" 
                               placeholder="Enter user ID">
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-3 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200 flex items-center">
                            <i class="fas fa-paper-plane mr-2"></i> Send GET Request
                        </button>
                    </div>
                </form>
            </div>

            <!-- Update Status Form -->
            <div id="status-form" class="form-section bg-white rounded-xl shadow-md p-6" style="display: none;">
                <div class="flex items-center mb-6">
                    <div class="bg-teal-100 p-3 rounded-lg mr-4">
                        <i class="fas fa-toggle-on text-teal-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">Update Profile Status</h3>
                        <p class="text-gray-600">Update the status of a user profile</p>
                    </div>
                </div>
                
                <form id="form-status" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Profile ID *</label>
                            <input type="number" name="id" required 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                            <select name="status" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="suspended">Suspended</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-3 bg-teal-600 text-white font-medium rounded-lg hover:bg-teal-700 focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 transition-all duration-200 flex items-center">
                            <i class="fas fa-paper-plane mr-2"></i> Send PATCH Request
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <script>
        // Tab switching functionality
        document.querySelectorAll('.tab-button').forEach(button => {
            button.addEventListener('click', function() {
                const tabId = this.getAttribute('data-tab');
                
                // Update active tab
                document.querySelectorAll('.tab-button').forEach(btn => {
                    btn.classList.remove('tab-active', 'border-blue-500', 'text-blue-600');
                    btn.classList.add('border-transparent', 'text-gray-500');
                });
                this.classList.add('tab-active', 'border-blue-500', 'text-blue-600');
                this.classList.remove('border-transparent', 'text-gray-500');
                
                // Show selected form, hide others
                document.querySelectorAll('[id$="-form"]').forEach(form => {
                    form.style.display = 'none';
                });
                document.getElementById(`${tabId}-form`).style.display = 'block';
                
                // Hide response container when switching tabs
                document.getElementById('response-container').style.display = 'none';
            });
        });

        // Set first tab as active
        document.querySelector('.tab-button').click();

        // Form submission handlers
        document.getElementById('form-get-all').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const params = new URLSearchParams();
            
            // Add format=xml parameter
            params.append('format', 'xml');
            
            // Add form parameters
            formData.forEach((value, key) => {
                if (value) params.append(key, value);
            });
            
            sendRequest('GET', `{{ url('/api/profiles') }}?${params.toString()}`);
        });

        document.getElementById('form-get-single').addEventListener('submit', function(e) {
            e.preventDefault();
            const id = this.querySelector('[name="id"]').value;
            sendRequest('GET', `{{ url('/api/profiles') }}/${id}?format=xml`);
        });

        document.getElementById('form-create').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());
            
            // Remove empty values
            Object.keys(data).forEach(key => {
                if (!data[key]) delete data[key];
            });
            
            sendRequest('POST', `{{ url('/api/profiles') }}?format=xml`, data);
        });

        document.getElementById('form-update').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const id = formData.get('id');
            formData.delete('id');
            
            const data = Object.fromEntries(formData.entries());
            
            // Remove empty values
            Object.keys(data).forEach(key => {
                if (!data[key]) delete data[key];
            });
            
            sendRequest('PUT', `{{ url('/api/profiles') }}/${id}?format=xml`, data);
        });

        document.getElementById('form-delete').addEventListener('submit', function(e) {
            e.preventDefault();
            const id = this.querySelector('[name="id"]').value;
            
            if (confirm('Are you sure you want to delete this profile?')) {
                sendRequest('DELETE', `{{ url('/api/profiles') }}/${id}?format=xml`);
            }
        });

        document.getElementById('form-by-user').addEventListener('submit', function(e) {
            e.preventDefault();
            const userId = this.querySelector('[name="user_id"]').value;
            sendRequest('GET', `{{ url('/api/profiles') }}/user/${userId}?format=xml`);
        });

        document.getElementById('form-status').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const id = formData.get('id');
            const status = formData.get('status');
            
            sendRequest('PATCH', `{{ url('/api/profiles') }}/${id}/status?format=xml`, { status });
        });

        // Send API request
        async function sendRequest(method, url, data = null) {
            const startTime = Date.now();
            const responseContainer = document.getElementById('response-container');
            const responseContent = document.getElementById('response-content');
            const responseStatus = document.getElementById('response-status');
            const responseTime = document.getElementById('response-time');
            
            // Show loading state
            responseContent.textContent = 'Loading...';
            responseContainer.style.display = 'block';
            
            try {
                const options = {
                    method: method,
                    headers: {
                        'Accept': 'application/xml',
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                };
                
                if (data && (method === 'POST' || method === 'PUT' || method === 'PATCH')) {
                    options.body = JSON.stringify(data);
                }
                
                const response = await fetch(url, options);
                const endTime = Date.now();
                const duration = endTime - startTime;
                
                const responseText = await response.text();
                
                // Try to parse as XML, if not valid XML, show raw response
                let formattedXml;
                try {
                    const parser = new DOMParser();
                    const xmlDoc = parser.parseFromString(responseText, 'text/xml');
                    
                    // Check if parsing produced an error
                    const parserError = xmlDoc.querySelector('parsererror');
                    if (parserError) {
                        throw new Error('Invalid XML');
                    }
                    
                    // Format XML with indentation
                    formattedXml = formatXml(responseText);
                } catch (e) {
                    formattedXml = responseText;
                }
                
                // Display response
                responseContent.innerHTML = hljs.highlight(formattedXml, { language: 'xml' }).value;
                responseStatus.innerHTML = `<span class="font-semibold">Status:</span> ${response.status} ${response.statusText}`;
                responseTime.innerHTML = `<span class="font-semibold">Time:</span> ${duration}ms`;
                
                // Scroll to response
                responseContainer.scrollIntoView({ behavior: 'smooth' });
                
            } catch (error) {
                responseContent.textContent = `Error: ${error.message}`;
                responseStatus.innerHTML = `<span class="font-semibold">Status:</span> Error`;
                responseTime.innerHTML = '';
            }
        }

        // Format XML with indentation
        function formatXml(xml) {
            let formatted = '';
            let indent = '';
            const tab = '  ';
            
            xml.split(/>\s*</).forEach(node => {
                if (node.match(/^\/\w/)) {
                    indent = indent.substring(tab.length);
                }
                
                formatted += indent + '<' + node + '>\r\n';
                
                if (node.match(/^<?\w[^>]*[^\/]$/)) {
                    indent += tab;
                }
            });
            
            return formatted.substring(1, formatted.length - 3);
        }

        // Copy response to clipboard
        function copyResponse() {
            const responseText = document.getElementById('response-content').textContent;
            navigator.clipboard.writeText(responseText).then(() => {
                // Show temporary success message
                const copyBtn = document.querySelector('#response-container button[onclick="copyResponse()"]');
                const originalHtml = copyBtn.innerHTML;
                copyBtn.innerHTML = '<i class="fas fa-check mr-1"></i> Copied!';
                copyBtn.classList.add('text-green-400');
                
                setTimeout(() => {
                    copyBtn.innerHTML = originalHtml;
                    copyBtn.classList.remove('text-green-400');
                }, 2000);
            });
        }

        // Initialize highlight.js
        hljs.highlightAll();
    </script>
</body>
</html>