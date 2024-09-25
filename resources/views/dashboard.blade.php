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

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg divide-y divide-gray-200">
                    <div class="px-4 py-5 sm:p-6">
                        <h2 class="text-lg leading-6 font-medium text-accent mb-4">Create New Article</h2>

                        <!-- Form to create a post -->
                        <form action="{{ url('/facebook/post') }}" method="POST" id="article-form" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-4">
                                <label for="content" class="block text-sm font-medium text-gray-700">Content</label>
                                <textarea name="content" id="content" rows="5" class="mt-1 focus:ring-secondary focus:border-secondary block w-full shadow-sm sm:text-sm border-gray-400 rounded-md px-3 py-2" placeholder="What's on your mind?"></textarea>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Media Upload</label>
                                <div id="media-dropzone" class="dropzone mt-1">
                                    <div class="dz-message">
                                        <i class="fas fa-cloud-upload-alt text-4xl text-secondary mb-2"></i>
                                        <p>Drag and drop files here or click to upload</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Platform options -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Platforms</label>
                                <div class="mt-2 space-y-2">
                                    <div class="flex items-center">
                                        <input id="facebook" name="platforms[]" type="checkbox" class="h-4 w-4 text-secondary focus:ring-secondary border-gray-300 rounded" value="facebook">
                                        <label for="facebook" class="ml-3 text-sm text-gray-700 flex items-center">
                                            <i class="fab fa-facebook-square mr-2" style="color: #3b5998;"></i> Facebook
                                        </label>
                                    </div>

                                    <div class="flex items-center">
                                        <input id="twitter" name="platforms[]" type="checkbox" class="h-4 w-4 text-secondary focus:ring-secondary border-gray-300 rounded">
                                        <label for="twitter" class="ml-3 text-sm text-gray-700 flex items-center">
                                            <i class="fab fa-twitter mr-2" style="color: #1DA1F2;"></i> Twitter
                                        </label>
                                    </div>

                                    <div class="flex items-center">
                                        <input id="instagram" name="platforms[]" type="checkbox" class="h-4 w-4 text-secondary focus:ring-secondary border-gray-300 rounded">
                                        <label for="instagram" class="ml-3 text-sm text-gray-700 flex items-center">
                                            <i class="fab fa-instagram mr-2" style="color: #C13584;"></i> Instagram
                                        </label>
                                    </div>

                                    <div class="flex items-center">
                                        <input id="linkedin" name="platforms[]" type="checkbox" class="h-4 w-4 text-secondary focus:ring-secondary border-gray-300 rounded">
                                        <label for="linkedin" class="ml-3 text-sm text-gray-700 flex items-center">
                                            <i class="fab fa-linkedin mr-2" style="color: #0A66C2;"></i> LinkedIn
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="schedule" class="block text-sm font-medium text-gray-700">Scheduled Post</label>
                                <input type="text" name="schedule" id="schedule" class="sm:text-sm border-gray-300 rounded-md flatpickr-input" placeholder="yyyy-mm-dd / 00:00" data-input>
                            </div>

                            <div class="flex justify-between">
                                <button type="button" id="schedule-post" class="bg-accent text-white px-4 py-2 rounded-md shadow-sm hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary">
                                    Schedule Post
                                </button>
                                <button type="submit" class="bg-primary text-white px-4 py-2 rounded-md shadow-sm hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary">
                                    Create Post
                                </button>
                            </div>

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

        const dropzone = new Dropzone("#media-dropzone", {
            url: "/file/post", // Not actually used, we handle form submission manually
            autoProcessQueue: false, // Disable automatic uploads
            maxFiles: 1, // Allow only one file
            acceptedFiles: "image/*", // Accept only image files
            clickable: true, // Allow clicking on the dropzone to open file explorer
            init: function() {
                const dz = this; // Reference to the Dropzone instance

                // Event listener for when a file is added
                dz.on("addedfile", function(file) {
                    console.log("File added:", file);
                    // You may want to show a preview or some feedback to the user here
                });

                // Handle form submission
                document.querySelector("#your-form-id").addEventListener("submit", function(e) {
                    e.preventDefault(); // Prevent the default form submission

                    // Check if there's a file in the dropzone
                    if (dz.files.length > 0) {
                        dz.processQueue(); // Manually process the queue
                    } else {
                        // Handle the case when no file is uploaded
                        console.log("No file uploaded.");
                        // Optionally, you can submit the form data without an image
                    }
                });

                // Event listener for processing the queue
                dz.on("queuecomplete", function() {
                    console.log("Upload complete!");
                    // Optionally, you can reset the dropzone after the upload
                    dz.removeAllFiles(); // Clear files from Dropzone
                });

                // Event listener for upload errors
                dz.on("error", function(file, errorMessage) {
                    console.error("Error uploading file:", errorMessage);
                });
            }
        });


        //  Handle form submission
        $('#article-form').on('submit', function(e) {
            e.preventDefault();

            if (dropzone.getQueuedFiles().length > 0) {
                dropzone.processQueue(); // Upload the file manually
            } else {
                $(this).off('submit').submit(); // No files to upload, proceed with form submission
            }
        });

        // Initialize flatpickr for scheduled posts
        flatpickr("#schedule", {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            minDate: "today"
        });
    </script>

</body>

</html>