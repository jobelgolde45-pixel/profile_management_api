<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Profile Management API</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        
        <!-- Font Awesome for icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        
        <style>
            :root {
                --primary: #4361ee;
                --primary-dark: #3a56d4;
                --secondary: #7209b7;
                --success: #4cc9f0;
                --danger: #f72585;
                --warning: #f8961e;
                --light: #f8f9fa;
                --dark: #212529;
                --gray: #6c757d;
                --gray-light: #e9ecef;
                --border-radius: 12px;
                --shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
                --shadow-hover: 0 15px 40px rgba(0, 0, 0, 0.12);
                --transition: all 0.3s ease;
            }

            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: 'Instrument Sans', sans-serif;
                background: linear-gradient(135deg, #f5f7fa 0%, #e4edf5 100%);
                color: var(--dark);
                line-height: 1.6;
                min-height: 100vh;
                padding: 20px;
            }

            .container {
                max-width: 1400px;
                margin: 0 auto;
            }

            header {
                text-align: center;
                margin-bottom: 40px;
                padding: 30px 20px;
                background: white;
                border-radius: var(--border-radius);
                box-shadow: var(--shadow);
                position: relative;
                overflow: hidden;
            }

            header::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 5px;
                background: linear-gradient(90deg, var(--primary), var(--secondary));
            }

            h1 {
                font-size: 2.8rem;
                font-weight: 600;
                margin-bottom: 10px;
                background: linear-gradient(90deg, var(--primary), var(--secondary));
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
            }

            .subtitle {
                font-size: 1.1rem;
                color: var(--gray);
                max-width: 700px;
                margin: 0 auto;
            }

            .api-info {
                display: flex;
                justify-content: center;
                gap: 15px;
                margin-top: 20px;
                flex-wrap: wrap;
            }

            .endpoint-badge {
                background: var(--light);
                padding: 8px 15px;
                border-radius: 50px;
                font-size: 0.9rem;
                font-weight: 500;
                display: flex;
                align-items: center;
                gap: 8px;
                border: 1px solid var(--gray-light);
            }

            .method {
                font-weight: 600;
                padding: 3px 10px;
                border-radius: 5px;
                font-size: 0.85rem;
            }

            .method.get { background: #e3f2fd; color: #1565c0; }
            .method.post { background: #e8f5e9; color: #2e7d32; }
            .method.put { background: #fff3e0; color: #ef6c00; }
            .method.patch { background: #fce4ec; color: #c2185b; }
            .method.delete { background: #ffebee; color: #c62828; }

            .main-content {
                display: grid;
                grid-template-columns: 1fr;
                gap: 30px;
            }

            @media (min-width: 992px) {
                .main-content {
                    grid-template-columns: 1fr 1fr;
                }
                
                .forms-section {
                    grid-column: 1;
                }
                
                .results-section {
                    grid-column: 2;
                }
                
                .wide-form {
                    grid-column: 1 / -1;
                }
            }

            .section-title {
                font-size: 1.5rem;
                font-weight: 600;
                margin-bottom: 20px;
                color: var(--dark);
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .section-title i {
                background: linear-gradient(90deg, var(--primary), var(--secondary));
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
            }

            .card {
                background: white;
                border-radius: var(--border-radius);
                box-shadow: var(--shadow);
                padding: 30px;
                margin-bottom: 30px;
                transition: var(--transition);
                border: 1px solid rgba(255, 255, 255, 0.2);
            }

            .card:hover {
                box-shadow: var(--shadow-hover);
                transform: translateY(-5px);
            }

            .form-group {
                margin-bottom: 20px;
            }

            label {
                display: block;
                margin-bottom: 8px;
                font-weight: 500;
                color: var(--dark);
                font-size: 0.95rem;
            }

            .form-control {
                width: 100%;
                padding: 14px 18px;
                border: 1px solid var(--gray-light);
                border-radius: 10px;
                font-family: 'Instrument Sans', sans-serif;
                font-size: 1rem;
                transition: var(--transition);
                background-color: white;
            }

            .form-control:focus {
                outline: none;
                border-color: var(--primary);
                box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
            }

            .form-row {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 20px;
            }

            @media (max-width: 768px) {
                .form-row {
                    grid-template-columns: 1fr;
                }
            }

            .btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
                padding: 14px 28px;
                border: none;
                border-radius: 10px;
                font-family: 'Instrument Sans', sans-serif;
                font-size: 1rem;
                font-weight: 500;
                cursor: pointer;
                transition: var(--transition);
            }

            .btn-primary {
                background: linear-gradient(90deg, var(--primary), var(--primary-dark));
                color: white;
            }

            .btn-primary:hover {
                transform: translateY(-3px);
                box-shadow: 0 10px 20px rgba(67, 97, 238, 0.3);
            }

            .btn-secondary {
                background: var(--gray-light);
                color: var(--dark);
            }

            .btn-secondary:hover {
                background: var(--gray);
                color: white;
            }

            .btn-danger {
                background: linear-gradient(90deg, var(--danger), #e1156c);
                color: white;
            }

            .btn-danger:hover {
                transform: translateY(-3px);
                box-shadow: 0 10px 20px rgba(247, 37, 133, 0.3);
            }

            .btn-success {
                background: linear-gradient(90deg, var(--success), #3ab5d9);
                color: white;
            }

            .form-actions {
                display: flex;
                gap: 15px;
                margin-top: 25px;
            }

            .response-container {
                background: #f8f9fa;
                border-radius: 10px;
                padding: 20px;
                margin-top: 25px;
                max-height: 400px;
                overflow-y: auto;
                border: 1px solid var(--gray-light);
            }

            .response-title {
                font-weight: 600;
                margin-bottom: 10px;
                color: var(--dark);
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .response-title i {
                color: var(--primary);
            }

            pre {
                background: white;
                padding: 15px;
                border-radius: 8px;
                overflow-x: auto;
                font-size: 0.9rem;
                line-height: 1.5;
                border: 1px solid var(--gray-light);
            }

            .json-key {
                color: #d32f2f;
            }
            
            .json-string {
                color: #689f38;
            }
            
            .json-number {
                color: #1976d2;
            }
            
            .json-boolean {
                color: #7b1fa2;
            }

            .status-indicator {
                display: inline-block;
                width: 10px;
                height: 10px;
                border-radius: 50%;
                margin-right: 8px;
            }

            .status-active { background-color: #4caf50; }
            .status-inactive { background-color: #ff9800; }
            .status-suspended { background-color: #f44336; }

            .avatar-preview {
                width: 100px;
                height: 100px;
                border-radius: 50%;
                object-fit: cover;
                border: 3px solid var(--gray-light);
                margin: 10px 0;
            }

            .hidden {
                display: none;
            }

            .loader {
                display: inline-block;
                width: 20px;
                height: 20px;
                border: 3px solid rgba(255, 255, 255, 0.3);
                border-radius: 50%;
                border-top-color: white;
                animation: spin 1s ease-in-out infinite;
            }

            @keyframes spin {
                to { transform: rotate(360deg); }
            }

            footer {
                text-align: center;
                margin-top: 40px;
                padding: 20px;
                color: var(--gray);
                font-size: 0.9rem;
                border-top: 1px solid var(--gray-light);
            }

            .tabs {
                display: flex;
                border-bottom: 1px solid var(--gray-light);
                margin-bottom: 25px;
                overflow-x: auto;
            }

            .tab {
                padding: 12px 20px;
                font-weight: 500;
                cursor: pointer;
                border-bottom: 3px solid transparent;
                white-space: nowrap;
                transition: var(--transition);
            }

            .tab:hover {
                color: var(--primary);
            }

            .tab.active {
                color: var(--primary);
                border-bottom-color: var(--primary);
            }

            .tab-content {
                display: none;
            }

            .tab-content.active {
                display: block;
            }

            .search-filters {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 15px;
                margin-bottom: 20px;
            }

            .notification {
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 15px 25px;
                border-radius: 10px;
                color: white;
                font-weight: 500;
                box-shadow: var(--shadow);
                z-index: 1000;
                transform: translateX(400px);
                transition: transform 0.4s ease;
                max-width: 350px;
            }

            .notification.show {
                transform: translateX(0);
            }

            .notification.success {
                background: linear-gradient(90deg, #4caf50, #2e7d32);
            }

            .notification.error {
                background: linear-gradient(90deg, #f44336, #c62828);
            }

            .notification.info {
                background: linear-gradient(90deg, #2196f3, #1565c0);
            }

            .pagination {
                display: flex;
                justify-content: center;
                gap: 10px;
                margin-top: 20px;
            }

            .page-btn {
                width: 40px;
                height: 40px;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 8px;
                background: white;
                border: 1px solid var(--gray-light);
                cursor: pointer;
                transition: var(--transition);
            }

            .page-btn:hover {
                background: var(--gray-light);
            }

            .page-btn.active {
                background: var(--primary);
                color: white;
                border-color: var(--primary);
            }
            .xml-container{
                width: 100%;
                display:flex;
                flex-direction: row;
                justify-content: center;
                align-self: center;
                margin-bottom: 3rem;
            }
            .xml-page{
                background: #4361ee;
                padding: .5rem 1rem;
                border-radius: 10px;
                text-decoration: none;
                color: #fff;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <header>
                <h1><i class="fas fa-user-circle"></i> Profile Management API</h1>
                <p class="subtitle">SIA 2 - PROJECT</p>
                
                <div class="api-info">
                    <div class="endpoint-badge">
                        <span class="method get">GET</span>
                        <span>/api/profiles</span>
                    </div>
                    <div class="endpoint-badge">
                        <span class="method post">POST</span>
                        <span>/api/profiles</span>
                    </div>
                    <div class="endpoint-badge">
                        <span class="method get">GET</span>
                        <span>/api/profiles/{id}</span>
                    </div>
                    <div class="endpoint-badge">
                        <span class="method put">PUT</span>
                        <span>/api/profiles/{id}</span>
                    </div>
                    <div class="endpoint-badge">
                        <span class="method delete">DELETE</span>
                        <span>/api/profiles/{id}</span>
                    </div>
                </div>

            </header>
                <div class="xml-container">
                    <a href="{{ route('xml.page') }}" class="xml-page">XML page</a>
                </div>

            <div class="main-content">
                <div class="forms-section">
                    <div class="card">
                        <h2 class="section-title"><i class="fas fa-list"></i> Get All Profiles</h2>
                        <p>Retrieve all profiles with optional search, filters, and pagination.</p>
                        
                        <div class="search-filters">
                            <div class="form-group">
                                <label for="search">Search</label>
                                <input type="text" id="search" class="form-control" placeholder="Search by name, email, bio...">
                            </div>
                            
                            <div class="form-group">
                                <label for="status-filter">Status</label>
                                <select id="status-filter" class="form-control">
                                    <option value="">All Status</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="suspended">Suspended</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="gender-filter">Gender</label>
                                <select id="gender-filter" class="form-control">
                                    <option value="">All Genders</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                    <option value="prefer-not-to-say">Prefer not to say</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="per-page">Items per page</label>
                                <select id="per-page" class="form-control">
                                    <option value="5">5</option>
                                    <option value="15" selected>15</option>
                                    <option value="30">30</option>
                                    <option value="50">50</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-actions">
                            <button type="button" class="btn btn-primary" id="fetch-profiles">
                                <i class="fas fa-sync-alt"></i> Fetch Profiles
                            </button>
                        </div>
                        
                        <div id="all-profiles-response" class="response-container hidden">
                            <div class="response-title">
                                <i class="fas fa-code"></i> Response
                            </div>
                            <pre id="all-profiles-data">Response will appear here...</pre>
                        </div>
                    </div>

                    <div class="card">
                        <h2 class="section-title"><i class="fas fa-plus-circle"></i> Create Profile</h2>
                        <p>Create a new user profile with the form below.</p>
                        
                        <form id="create-profile-form">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="user_id">User ID *</label>
                                    <input type="number" id="user_id" class="form-control" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="date_of_birth">Date of Birth</label>
                                    <input type="date" id="date_of_birth" class="form-control">
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="gender">Gender</label>
                                    <select id="gender" class="form-control">
                                        <option value="">Select gender</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                        <option value="other">Other</option>
                                        <option value="prefer-not-to-say">Prefer not to say</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="phone">Phone Number</label>
                                    <input type="tel" id="phone" class="form-control">
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="country">Country</label>
                                    <input type="text" id="country" class="form-control">
                                </div>
                                
                                <div class="form-group">
                                    <label for="city">City</label>
                                    <input type="text" id="city" class="form-control">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="address">Address</label>
                                <textarea id="address" class="form-control" rows="2"></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label for="bio">Bio</label>
                                <textarea id="bio" class="form-control" rows="3" placeholder="Tell us about yourself..."></textarea>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select id="status" class="form-control">
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                        <option value="suspended">Suspended</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="avatar">Avatar</label>
                                    <input type="file" id="avatar" class="form-control" accept="image/*">
                                    <small>JPEG, PNG, or GIF up to 5MB</small>
                                    <img id="avatar-preview" class="avatar-preview hidden" src="#" alt="Avatar preview">
                                </div>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Create Profile
                                </button>
                                <button type="reset" class="btn btn-secondary">
                                    <i class="fas fa-redo"></i> Reset Form
                                </button>
                            </div>
                        </form>
                        
                        <div id="create-profile-response" class="response-container hidden">
                            <div class="response-title">
                                <i class="fas fa-code"></i> Response
                            </div>
                            <pre id="create-profile-data">Response will appear here...</pre>
                        </div>
                    </div>
                </div>

                <div class="results-section">
                    <div class="card">
                        <h2 class="section-title"><i class="fas fa-user"></i> Get Profile by ID</h2>
                        <p>Retrieve a specific profile by its ID.</p>
                        
                        <div class="form-group">
                            <label for="profile-id">Profile ID *</label>
                            <input type="number" id="profile-id" class="form-control" placeholder="Enter profile ID">
                        </div>
                        
                        <div class="form-actions">
                            <button type="button" class="btn btn-primary" id="fetch-profile">
                                <i class="fas fa-search"></i> Fetch Profile
                            </button>
                        </div>
                        
                        <div id="single-profile-response" class="response-container hidden">
                            <div class="response-title">
                                <i class="fas fa-code"></i> Response
                            </div>
                            <pre id="single-profile-data">Response will appear here...</pre>
                        </div>
                    </div>

                    <div class="card">
                        <h2 class="section-title"><i class="fas fa-edit"></i> Update Profile</h2>
                        <p>Update an existing profile by ID.</p>
                        
                        <form id="update-profile-form">
                            <div class="form-group">
                                <label for="update-id">Profile ID *</label>
                                <input type="number" id="update-id" class="form-control" required>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="update-date_of_birth">Date of Birth</label>
                                    <input type="date" id="update-date_of_birth" class="form-control">
                                </div>
                                
                                <div class="form-group">
                                    <label for="update-gender">Gender</label>
                                    <select id="update-gender" class="form-control">
                                        <option value="">Select gender</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                        <option value="other">Other</option>
                                        <option value="prefer-not-to-say">Prefer not to say</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="update-phone">Phone Number</label>
                                    <input type="tel" id="update-phone" class="form-control">
                                </div>
                                
                                <div class="form-group">
                                    <label for="update-country">Country</label>
                                    <input type="text" id="update-country" class="form-control">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="update-bio">Bio</label>
                                <textarea id="update-bio" class="form-control" rows="3"></textarea>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="update-status">Status</label>
                                    <select id="update-status" class="form-control">
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                        <option value="suspended">Suspended</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="update-avatar">New Avatar (optional)</label>
                                    <input type="file" id="update-avatar" class="form-control" accept="image/*">
                                </div>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-sync-alt"></i> Update Profile
                                </button>
                            </div>
                        </form>
                        
                        <div id="update-profile-response" class="response-container hidden">
                            <div class="response-title">
                                <i class="fas fa-code"></i> Response
                            </div>
                            <pre id="update-profile-data">Response will appear here...</pre>
                        </div>
                    </div>

                    <div class="card wide-form">
                        <div class="tabs">
                            <div class="tab active" data-tab="delete">Delete Profile</div>
                            <div class="tab" data-tab="get-by-user">Get by User ID</div>
                            <div class="tab" data-tab="update-status">Update Status</div>
                        </div>
                        
                        <!-- Delete Profile Tab -->
                        <div id="delete-tab" class="tab-content active">
                            <h2 class="section-title"><i class="fas fa-trash-alt"></i> Delete Profile</h2>
                            <p>Delete a profile by ID. This action cannot be undone.</p>
                            
                            <div class="form-group">
                                <label for="delete-id">Profile ID *</label>
                                <input type="number" id="delete-id" class="form-control" placeholder="Enter profile ID to delete">
                            </div>
                            
                            <div class="form-actions">
                                <button type="button" class="btn btn-danger" id="delete-profile">
                                    <i class="fas fa-trash"></i> Delete Profile
                                </button>
                            </div>
                            
                            <div id="delete-profile-response" class="response-container hidden">
                                <div class="response-title">
                                    <i class="fas fa-code"></i> Response
                                </div>
                                <pre id="delete-profile-data">Response will appear here...</pre>
                            </div>
                        </div>
                        
                        <!-- Get by User ID Tab -->
                        <div id="get-by-user-tab" class="tab-content">
                            <h2 class="section-title"><i class="fas fa-user-friends"></i> Get Profile by User ID</h2>
                            <p>Retrieve a profile using the associated user ID.</p>
                            
                            <div class="form-group">
                                <label for="user-id">User ID *</label>
                                <input type="number" id="user-id" class="form-control" placeholder="Enter user ID">
                            </div>
                            
                            <div class="form-actions">
                                <button type="button" class="btn btn-primary" id="fetch-by-user">
                                    <i class="fas fa-search"></i> Get Profile
                                </button>
                            </div>
                            
                            <div id="user-profile-response" class="response-container hidden">
                                <div class="response-title">
                                    <i class="fas fa-code"></i> Response
                                </div>
                                <pre id="user-profile-data">Response will appear here...</pre>
                            </div>
                        </div>
                        
                        <!-- Update Status Tab -->
                        <div id="update-status-tab" class="tab-content">
                            <h2 class="section-title"><i class="fas fa-toggle-on"></i> Update Profile Status</h2>
                            <p>Update only the status of a profile.</p>
                            
                            <div class="form-group">
                                <label for="status-profile-id">Profile ID *</label>
                                <input type="number" id="status-profile-id" class="form-control" placeholder="Enter profile ID">
                            </div>
                            
                            <div class="form-group">
                                <label for="new-status">New Status *</label>
                                <select id="new-status" class="form-control">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="suspended">Suspended</option>
                                </select>
                            </div>
                            
                            <div class="form-actions">
                                <button type="button" class="btn btn-primary" id="update-status-btn">
                                    <i class="fas fa-check-circle"></i> Update Status
                                </button>
                            </div>
                            
                            <div id="status-update-response" class="response-container hidden">
                                <div class="response-title">
                                    <i class="fas fa-code"></i> Response
                                </div>
                                <pre id="status-update-data">Response will appear here...</pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
        </div>

        <div id="notification" class="notification"></div>

        <script>
            // Base API URL - Change this to your actual API URL
            // For Laravel, you can use relative URL if frontend is served from same domain
            const API_BASE_URL = '/api/profiles';
            
            // Add CSRF token for Laravel
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            
            // DOM Elements
            const notification = document.getElementById('notification');
            const avatarPreview = document.getElementById('avatar-preview');
            const avatarInput = document.getElementById('avatar');
            
            // Tab functionality
            document.querySelectorAll('.tab').forEach(tab => {
                tab.addEventListener('click', function() {
                    const tabId = this.getAttribute('data-tab');
                    
                    // Update active tab
                    document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    
                    // Show active content
                    document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
                    document.getElementById(`${tabId}-tab`).classList.add('active');
                });
            });
            
            // Avatar preview
            if (avatarInput) {
                avatarInput.addEventListener('change', function() {
                    if (this.files && this.files[0]) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            avatarPreview.src = e.target.result;
                            avatarPreview.classList.remove('hidden');
                        };
                        reader.readAsDataURL(this.files[0]);
                    }
                });
            }
            
            // Show notification
            function showNotification(message, type = 'info') {
                notification.textContent = message;
                notification.className = `notification ${type} show`;
                
                setTimeout(() => {
                    notification.classList.remove('show');
                }, 5000);
            }
            
            // Format JSON for display
            function formatJSON(json) {
                if (typeof json === 'string') {
                    try {
                        json = JSON.parse(json);
                    } catch (e) {
                        return json;
                    }
                }
                
                return JSON.stringify(json, null, 2);
            }
            
            // Syntax highlight for JSON
            function syntaxHighlight(json) {
                if (typeof json === 'string') {
                    try {
                        json = JSON.parse(json);
                        json = JSON.stringify(json, null, 2);
                    } catch (e) {
                        return json;
                    }
                } else {
                    json = JSON.stringify(json, null, 2);
                }
                
                json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
                return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function (match) {
                    let cls = 'json-number';
                    if (/^"/.test(match)) {
                        if (/:$/.test(match)) {
                            cls = 'json-key';
                        } else {
                            cls = 'json-string';
                        }
                    } else if (/true|false/.test(match)) {
                        cls = 'json-boolean';
                    } else if (/null/.test(match)) {
                        cls = 'json-null';
                    }
                    return '<span class="' + cls + '">' + match + '</span>';
                });
            }
            
            // Display response in a container
            function displayResponse(containerId, data, show = true) {
                const container = document.getElementById(containerId);
                const dataElement = document.getElementById(`${containerId.replace('-response', '-data')}`);
                
                if (dataElement) {
                    dataElement.innerHTML = syntaxHighlight(data);
                }
                
                if (show) {
                    container.classList.remove('hidden');
                }
            }
            
            // Real API calls - REPLACED THE SIMULATION WITH REAL FETCH CALLS
            
            // Event Listeners for API calls
            
            // Fetch all profiles
            document.getElementById('fetch-profiles').addEventListener('click', async function() {
                this.innerHTML = '<span class="loader"></span> Loading...';
                this.disabled = true;
                
                const search = document.getElementById('search').value;
                const status = document.getElementById('status-filter').value;
                const gender = document.getElementById('gender-filter').value;
                const perPage = document.getElementById('per-page').value;
                
                // Build query string
                let queryParams = [];
                if (search) queryParams.push(`search=${encodeURIComponent(search)}`);
                if (status) queryParams.push(`status=${status}`);
                if (gender) queryParams.push(`gender=${gender}`);
                if (perPage) queryParams.push(`per_page=${perPage}`);
                
                const queryString = queryParams.length ? `?${queryParams.join('&')}` : '';
                
                try {
                    const response = await fetch(`${API_BASE_URL}${queryString}`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    });
                    
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    
                    const data = await response.json();
                    displayResponse('all-profiles-response', data);
                    showNotification(`Fetched ${data.data?.length || 0} profiles successfully`, 'success');
                } catch (error) {
                    showNotification('Failed to fetch profiles: ' + error.message, 'error');
                    displayResponse('all-profiles-response', { success: false, error: error.message });
                } finally {
                    this.innerHTML = '<i class="fas fa-sync-alt"></i> Fetch Profiles';
                    this.disabled = false;
                }
            });
            
            // Create profile form
            document.getElementById('create-profile-form').addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const submitBtn = this.querySelector('button[type="submit"]');
                submitBtn.innerHTML = '<span class="loader"></span> Creating...';
                submitBtn.disabled = true;
                
                // Create FormData object for file upload
                const formData = new FormData();
                
                // Get form values and append to FormData
                const formFields = [
                    'user_id', 'date_of_birth', 'gender', 'phone', 
                    'country', 'city', 'address', 'bio', 'status'
                ];
                
                formFields.forEach(field => {
                    const value = document.getElementById(field).value;
                    if (value) {
                        formData.append(field, value);
                    }
                });
                
                // Handle file upload
                const avatarFile = document.getElementById('avatar').files[0];
                if (avatarFile) {
                    formData.append('avatar', avatarFile);
                }
                
                try {
                    const response = await fetch(API_BASE_URL, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    });
                    
                    if (!response.ok) {
                        const errorData = await response.json();
                        throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
                    }
                    
                    const data = await response.json();
                    displayResponse('create-profile-response', data);
                    showNotification(data.message || 'Profile created successfully', 'success');
                    
                    // Reset form
                    this.reset();
                    avatarPreview.classList.add('hidden');
                    avatarPreview.src = '#';
                    
                } catch (error) {
                    showNotification('Failed to create profile: ' + error.message, 'error');
                    displayResponse('create-profile-response', { success: false, error: error.message });
                } finally {
                    submitBtn.innerHTML = '<i class="fas fa-save"></i> Create Profile';
                    submitBtn.disabled = false;
                }
            });
            
            // Fetch single profile by ID
            document.getElementById('fetch-profile').addEventListener('click', async function() {
                const profileId = document.getElementById('profile-id').value;
                
                if (!profileId) {
                    showNotification('Please enter a profile ID', 'error');
                    return;
                }
                
                this.innerHTML = '<span class="loader"></span> Loading...';
                this.disabled = true;
                
                try {
                    const response = await fetch(`${API_BASE_URL}/${profileId}`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    });
                    
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    
                    const data = await response.json();
                    displayResponse('single-profile-response', data);
                    showNotification('Profile fetched successfully', 'success');
                } catch (error) {
                    showNotification('Failed to fetch profile: ' + error.message, 'error');
                    displayResponse('single-profile-response', { success: false, error: error.message });
                } finally {
                    this.innerHTML = '<i class="fas fa-search"></i> Fetch Profile';
                    this.disabled = false;
                }
            });
            
            // Update profile form
            document.getElementById('update-profile-form').addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const profileId = document.getElementById('update-id').value;
                
                if (!profileId) {
                    showNotification('Please enter a profile ID', 'error');
                    return;
                }
                
                const submitBtn = this.querySelector('button[type="submit"]');
                submitBtn.innerHTML = '<span class="loader"></span> Updating...';
                submitBtn.disabled = true;
                
                // Create FormData object for file upload
                const formData = new FormData();
                
                // Get form values and append to FormData
                const formFields = [
                    'date_of_birth', 'gender', 'phone', 
                    'country', 'bio', 'status'
                ];
                
                formFields.forEach(field => {
                    const value = document.getElementById(`update-${field}`).value;
                    if (value) {
                        formData.append(field, value);
                    }
                });
                
                // Handle file upload
                const avatarFile = document.getElementById('update-avatar').files[0];
                if (avatarFile) {
                    formData.append('avatar', avatarFile);
                }
                
                // Use PUT method for update
                formData.append('_method', 'PUT');
                
                try {
                    const response = await fetch(`${API_BASE_URL}/${profileId}`, {
                        method: 'POST', // Laravel expects POST with _method=PUT
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    });
                    
                    if (!response.ok) {
                        const errorData = await response.json();
                        throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
                    }
                    
                    const data = await response.json();
                    displayResponse('update-profile-response', data);
                    showNotification(data.message || 'Profile updated successfully', 'success');
                } catch (error) {
                    showNotification('Failed to update profile: ' + error.message, 'error');
                    displayResponse('update-profile-response', { success: false, error: error.message });
                } finally {
                    submitBtn.innerHTML = '<i class="fas fa-sync-alt"></i> Update Profile';
                    submitBtn.disabled = false;
                }
            });
            
            // Delete profile
            document.getElementById('delete-profile').addEventListener('click', async function() {
                const profileId = document.getElementById('delete-id').value;
                
                if (!profileId) {
                    showNotification('Please enter a profile ID', 'error');
                    return;
                }
                
                if (!confirm(`Are you sure you want to delete profile with ID ${profileId}? This action cannot be undone.`)) {
                    return;
                }
                
                this.innerHTML = '<span class="loader"></span> Deleting...';
                this.disabled = true;
                
                try {
                    const response = await fetch(`${API_BASE_URL}/${profileId}`, {
                        method: 'DELETE',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    });
                    
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    
                    const data = await response.json();
                    displayResponse('delete-profile-response', data);
                    showNotification(data.message || 'Profile deleted successfully', 'success');
                    document.getElementById('delete-id').value = '';
                } catch (error) {
                    showNotification('Failed to delete profile: ' + error.message, 'error');
                    displayResponse('delete-profile-response', { success: false, error: error.message });
                } finally {
                    this.innerHTML = '<i class="fas fa-trash"></i> Delete Profile';
                    this.disabled = false;
                }
            });
            
            // Fetch profile by user ID
            document.getElementById('fetch-by-user').addEventListener('click', async function() {
                const userId = document.getElementById('user-id').value;
                
                if (!userId) {
                    showNotification('Please enter a user ID', 'error');
                    return;
                }
                
                this.innerHTML = '<span class="loader"></span> Loading...';
                this.disabled = true;
                
                try {
                    const response = await fetch(`${API_BASE_URL}/user/${userId}`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    });
                    
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    
                    const data = await response.json();
                    displayResponse('user-profile-response', data);
                    showNotification('Profile fetched successfully', 'success');
                } catch (error) {
                    showNotification('Failed to fetch profile: ' + error.message, 'error');
                    displayResponse('user-profile-response', { success: false, error: error.message });
                } finally {
                    this.innerHTML = '<i class="fas fa-search"></i> Get Profile';
                    this.disabled = false;
                }
            });
            
            // Update profile status
            document.getElementById('update-status-btn').addEventListener('click', async function() {
                const profileId = document.getElementById('status-profile-id').value;
                const newStatus = document.getElementById('new-status').value;
                
                if (!profileId) {
                    showNotification('Please enter a profile ID', 'error');
                    return;
                }
                
                this.innerHTML = '<span class="loader"></span> Updating...';
                this.disabled = true;
                
                try {
                    const response = await fetch(`${API_BASE_URL}/${profileId}/status`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({ status: newStatus })
                    });
                    
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    
                    const data = await response.json();
                    displayResponse('status-update-response', data);
                    showNotification(data.message || 'Status updated successfully', 'success');
                } catch (error) {
                    showNotification('Failed to update status: ' + error.message, 'error');
                    displayResponse('status-update-response', { success: false, error: error.message });
                } finally {
                    this.innerHTML = '<i class="fas fa-check-circle"></i> Update Status';
                    this.disabled = false;
                }
            });
            
            // Initialize with some sample data
            document.addEventListener('DOMContentLoaded', function() {
                // Auto-populate some fields for demo purposes
                document.getElementById('user_id').value = Math.floor(Math.random() * 1000) + 100;
                document.getElementById('profile-id').value = 1;
                document.getElementById('update-id').value = 1;
                document.getElementById('delete-id').value = 1;
                document.getElementById('user-id').value = 1;
                document.getElementById('status-profile-id').value = 1;
                
                // Show a welcome notification
                setTimeout(() => {
                    showNotification('Welcome to Profile Management API Interface! Use the forms to interact with the API.', 'info');
                }, 1000);
            });
        </script>
    </body>
</html>