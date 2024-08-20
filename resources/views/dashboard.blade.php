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

                            <!-- Platform options -->
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
                                <label for="schedule" class="block text-sm font-medium text-gray-700">Schedule Post</
                                        </label>
                                    <input type="text" name="schedule" id="schedule" class="mt-1 focus:ring-secondary focus:border-secondary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md flatpickr-input" placeholder="Select Date and Time" data-input>
                            </div>

                            <!-- Buttons for schedule and create post -->
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
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg divide-y divide-gray-200">
                        <div class="px-4 py-5 sm:p-6">
                            <div class="text-sm text-gray-500">No posts scheduled.</div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            flatpickr("#schedule", {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
            });
        });
    </script>
    <!-- Added alpine for profile dropdown functionality -->
    <script src="//unpkg.com/alpinejs" defer></script>

</body>

</html>