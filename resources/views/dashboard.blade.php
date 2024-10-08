<!DOCTYPE html>
<html lang="en">

<head>
    <base href="{{ url('/') }}/">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SA Migration | Dashboard</title>

    <link rel="icon" href="{{ asset('images/sami-logo(1).png') }}" type="image/png">

    <!-- CSS link -->
    <link href="{{ asset('css/dashboard-style.css') }}" rel="stylesheet">

    <!-- font links -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/dropzone@5.9.3/dist/min/dropzone.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
</head>

<body class="bg-background-color min-h-screen flex">
    @include('layouts.sidebar')

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Top Navigation -->
        <header class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
                <button id="sidebar-toggle" class="text-accent focus:outline-none focus:text-secondary lg:hidden">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                <h1 class="text-lg font-semibold text-accent hidden lg:block">Visa Dashboard</h1>

                <!-- User Settings Dropdown -->
                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }}</div>
                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>
        </header>

        <!-- Notifications -->
        @if(session('success'))
        <div class="notification success" id="success-message" style="display: none;">
            {{ session('success') }}
            <button class="close-btn" onclick="closeNotification('success-message')">&times;</button>
        </div>
        @endif

        @if(session('error'))
        <div class="notification error" id="error-message" style="display: none;">
            {{ session('error') }}
            <button class="close-btn" onclick="closeNotification('error-message')">&times;</button>
        </div>
        @endif

        <!-- Main Content Area -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

                <div class="bg-white overflow-hidden shadow-sm rounded-lg divide-y divide-gray-200">
                    <div class="px-4 py-5 sm:p-6">
                        <h2 class="text-lg leading-6 font-medium text-accent mb-4">Create New Article</h2>

                        <!-- Form to create a post -->
                        <form action="{{ url('/facebook/post') }}" method="POST" enctype="multipart/form-data" id="article-form">
                            @csrf
                            <textarea name="content" placeholder="Your post content" required class="border rounded p-2 w-full mb-4 resize-y h-24" id="content-section"></textarea>

                            <!-- New Links/Website section -->
                            <label for="links" class="block text-lg font-medium text-gray-700 mb-2">Links/Website</label>
                            <input type="url" name="links" placeholder="Paste your clickable URL here" class="border rounded p-2 w-full mb-4" id="links" />

                            <!-- Dropzone for file upload -->
                            <div class="dropzone" id="file-dropzone">
                                <div class="dz-message">
                                    <i class="fas fa-cloud-upload-alt text-4xl text-secondary mb-2"></i>
                                    <p>Drag and drop files here or click to upload</p>
                                </div>
                            </div>

                            <!-- Social Media Platforms Checkbox Options -->
                            <div class="mt-4">
                                <h3 class="text-lg leading-6 font-medium text-accent mb-2">Select Platforms</h3>
                                <div class="flex flex-col">
                                    <div class="flex items-center mb-2">
                                        <input id="facebook" name="platforms[]" type="checkbox" value="facebook" class="h-4 w-4 text-secondary focus:ring-secondary border-gray-300 rounded">
                                        <label for="facebook" class="ml-3 text-sm text-gray-700 flex items-center">
                                            <i class="fab fa-facebook mr-2" style="color: #4267B2;"></i> Facebook
                                        </label>
                                    </div>
                                    <div class="flex items-center mb-2">
                                        <input id="instagram" name="platforms[]" type="checkbox" value="instagram" class="h-4 w-4 text-secondary focus:ring-secondary border-gray-300 rounded">
                                        <label for="instagram" class="ml-3 text-sm text-gray-700 flex items-center">
                                            <i class="fab fa-instagram mr-2" style="color: #C13584;"></i> Instagram
                                        </label>
                                    </div>
                                    <div class="flex items-center mb-2">
                                        <input id="twitter" name="platforms[]" type="checkbox" value="twitter" class="h-4 w-4 text-secondary focus:ring-secondary border-gray-300 rounded">
                                        <label for="twitter" class="ml-3 text-sm text-gray-700 flex items-center">
                                            <i class="fab fa-twitter mr-2" style="color: #1DA1F2;"></i> Twitter
                                        </label>
                                    </div>
                                    <div class="flex items-center mb-2">
                                        <input id="tiktok" name="platforms[]" type="checkbox" value="tiktok" class="h-4 w-4 text-secondary focus:ring-secondary border-gray-300 rounded">
                                        <label for="tiktok" class="ml-3 text-sm text-gray-700 flex items-center">
                                            <i class="fab fa-tiktok mr-2" style="color: #69C9D0;"></i> TikTok
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="bg-blue-500 text-white p-2 rounded" id="btn-post">Create Post</button>
                        </form>


                    </div>
                </div>

                <!-- Display Scheduled Posts -->
                <div class="mt-8">
                    <h2 class="text-lg leading-6 font-medium text-accent mb-4">Scheduled Posts</h2>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Content</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Platforms</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Scheduled Time</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <!-- Example post data -->
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Example content for scheduled post</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Facebook, Twitter</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024-09-25 / 12:00</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button class="text-secondary hover:text-primary">Edit</button>
                                    <button class="text-red-600 hover:text-red-900 ml-4">Delete</button>
                                </td>
                            </tr>
                            <!-- Add your scheduled posts here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        // Initialize Dropzone
        Dropzone.autoDiscover = false;

        const dropzone = new Dropzone("#file-dropzone", {
            url: "/facebook/post", // URL for posting
            autoProcessQueue: false,
            maxFiles: 1,
            acceptedFiles: "image/*",
            clickable: true,
            init: function() {
                const dz = this;

                // Handle form submission
                document.querySelector("#article-form").addEventListener("submit", function(e) {
                    e.preventDefault(); // Prevent the default form submission

                    // Check if there's a file in the dropzone
                    if (dz.files.length > 0) {
                        // Submit form data
                        const formData = new FormData(this);
                        dz.files.forEach((file) => {
                            formData.append('file', file); // Append the file from Dropzone
                        });

                        // Send the FormData containing the form inputs and file
                        $.ajax({
                            url: '/facebook/post', // URL for posting
                            type: 'POST',
                            data: formData,
                            contentType: false,
                            processData: false,
                            success: function(response) {
                                console.log("Post successful:", response);
                                dz.removeAllFiles(); // Clear files from Dropzone after successful upload
                                $('#article-form')[0].reset(); // Reset the form

                                // Display success notification
                                $('#success-message').text(response.message).show();
                            },
                            error: function(xhr, status, error) {
                                console.error("Error posting:", error);

                                // Display error notification
                                $('#error-message').text(xhr.responseJSON.message || "An error occurred.").show();
                            }
                        });
                    } else {
                        // Submit form without files
                        this.submit();
                    }
                });

                // Event listener for upload errors
                dz.on("error", function(file, errorMessage) {
                    console.error("Error uploading file:", errorMessage);
                });
            }
        });

        // Function to close notification manually
        function closeNotification(id) {
            const notification = document.getElementById(id);
            if (notification) {
                notification.style.display = 'none';
            }
        }

        // Automatically hide notifications after 5 seconds
        window.onload = function() {
            setTimeout(function() {
                const successMessage = document.getElementById('success-message');
                const errorMessage = document.getElementById('error-message');

                if (successMessage) {
                    successMessage.style.display = 'none';
                }
                if (errorMessage) {
                    errorMessage.style.display = 'none';
                }
            }, 5000);
        }
    </script>


</body>

</html>