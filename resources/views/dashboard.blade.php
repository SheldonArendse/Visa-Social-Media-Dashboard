<!DOCTYPE html>
<html lang="en">

<head>
    <base href="{{ url('/') }}/">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SA Migration | Dashboard</title>

    <!-- CSS link -->
    <link href="{{ asset('css/dashboard-style.css') }}" rel="stylesheet">

    <!-- font links -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/dropzone@5.9.3/dist/min/dropzone.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/dropzone@5.9.3/dist/min/dropzone.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
</head>

<body class="bg-background-color min-h-screen flex">
    <!-- Sidebar Navigation -->
    <aside class="sidebar bg-accent w-64 min-h-screen flex flex-col shadow-lg">
        <div class="p-4 bg-primary">
            <h1 class="text-accent text-xl font-bold">SA Migration</h1>
        </div>
        <div class="flex-1 flex flex-col justify-between">
            <nav class="flex-1 pt-5 pb-4 overflow-y-auto">
                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2 text-gray-300 hover:bg-secondary hover:text-accent">
                    <i class="fas fa-tachometer-alt mr-3"></i>
                    Dashboard
                </a>
                <a href="#" class="flex items-center px-4 py-2 text-gray-300 hover:bg-secondary hover:text-accent">
                    <i class="fas fa-newspaper mr-3"></i>
                    Articles
                </a>
                <a href="#" class="flex items-center px-4 py-2 text-gray-300 hover:bg-secondary hover:text-accent">
                    <i class="fas fa-chart-line mr-3"></i>
                    Analytics
                </a>
                <a href="#" class="flex items-center px-4 py-2 text-gray-300 hover:bg-secondary hover:text-accent">
                    <i class="fas fa-cog mr-3"></i>
                    Settings
                </a>
                <a href="#" class="flex items-center px-4 py-2 text-gray-300 hover:bg-secondary hover:text-accent">
                    <i class="fas fa-sign-out-alt mr-3"></i>
                    Logout
                </a>
            </nav>
        </div>
    </aside>

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
                <h1 class="text-lg font-semibold text-accent hidden lg:block">Dashboard</h1>
                <div class="flex items-center">
                    <div class="ml-3 relative">
                        <div>
                            <button type="button" class="flex items-center max-w-xs bg-white rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                <img class="h-8 w-8 rounded-full" src="https://sa-migration-social-media.example.com/user-avatar.jpg" alt="User avatar">
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg divide-y divide-gray-200">
                    <div class="px-4 py-5 sm:p-6">
                        <h2 class="text-lg leading-6 font-medium text-accent mb-4">Create New Article</h2>
                        <form action="#" method="POST" id="article-form" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-4">
                                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                                <input type="text" name="title" id="title" class="mt-1 focus:ring-secondary focus:border-secondary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>

                            <div class="mb-4">
                                <label for="content" class="block text-sm font-medium text-gray-700">Content</label>
                                <textarea name="content" id="content" rows="5" class="mt-1 focus:ring-secondary focus:border-secondary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
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

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Platforms</label>
                                <div class="mt-2 space-y-2">
                                    <div class="flex items-center">
                                        <input id="facebook" name="platforms[]" type="checkbox" class="h-4 w-4 text-secondary focus:ring-secondary border-gray-300 rounded">
                                        <label for="facebook" class="ml-3 text-sm text-gray-700">Facebook</label>
                                    </div>

                                    <div class="flex items-center">
                                        <input id="twitter" name="platforms[]" type="checkbox" class="h-4 w-4 text-secondary focus:ring-secondary border-gray-300 rounded">
                                        <label for="twitter" class="ml-3 text-sm text-gray-700">Twitter</label>
                                    </div>

                                    <div class="flex items-center">
                                        <input id="instagram" name="platforms[]" type="checkbox" class="h-4 w-4 text-secondary focus:ring-secondary border-gray-300 rounded">
                                        <label for="instagram" class="ml-3 text-sm text-gray-700">Instagram</label>
                                    </div>

                                    <div class="flex items-center">
                                        <input id="linkedin" name="platforms[]" type="checkbox" class="h-4 w-4 text-secondary focus:ring-secondary border-gray-300 rounded">
                                        <label for="linkedin" class="ml-3 text-sm text-gray-700">LinkedIn</label>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label for="schedule" class="block text-sm font-medium text-gray-700">Schedule Post</label>
                                <input id="schedule" name="schedule" type="text" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" placeholder="Select date and time">
                            </div>
                            <div class="flex items-center justify-between">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-secondary text-white border border-transparent rounded-md shadow-sm text-sm font-medium hover:bg-secondary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary">
                                    <i class="fas fa-save mr-2"></i> Save
                                </button>
                                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 text-gray-700 border border-transparent rounded-md shadow-sm text-sm font-medium hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary">
                                    <i class="fas fa-arrow-left mr-2"></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        document.getElementById('sidebar-toggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('open');
        });

        Dropzone.autoDiscover = false;
        const dropzone = new Dropzone("#media-dropzone", {
            url: "#",
            acceptedFiles: "image/*,video/*",
            addRemoveLinks: true,
            maxFilesize: 10, // MB
            init: function() {
                this.on("error", function(file, response) {
                    console.log(response);
                });
            }
        });

        flatpickr("#schedule", {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
        });
    </script>
</body>

</html>