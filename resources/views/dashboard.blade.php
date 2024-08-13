<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">

    <!-- Header -->
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900">
                Dashboard
            </h1>
        </div>
    </header>

    <!-- Sidebar and Main Content -->
    <div class="flex">
        <!-- Sidebar -->
        <nav class="w-1/5 bg-gray-800 text-white h-screen">
            <div class="p-4">
                <ul>
                    <li class="mb-4"><a href="#" class="text-white">Home</a></li>
                    <li class="mb-4"><a href="#" class="text-white">Create Post</a></li>
                    <li class="mb-4"><a href="#" class="text-white">Manage Posts</a></li>
                    <li class="mb-4"><a href="#" class="text-white">Social Media Integrations</a></li>
                    <li class="mb-4"><a href="#" class="text-white">Logout</a></li>
                </ul>
            </div>
        </nav>

        <!-- Main Content Area -->
        <main class="w-4/5 p-8">
            <h2 class="text-2xl font-semibold mb-6">Create a New Post</h2>

            <!-- Post Form -->
            <form action="{{ route('posts.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="title" class="block text-gray-700">Title</label>
                    <input type="text" id="title" name="title" class="w-full p-2 border border-gray-300 rounded mt-1" required>
                </div>

                <div class="mb-4">
                    <label for="content" class="block text-gray-700">Content</label>
                    <textarea id="content" name="content" rows="5" class="w-full p-2 border border-gray-300 rounded mt-1" required></textarea>
                </div>

                <div class="mb-4">
                    <label for="image_url" class="block text-gray-700">Image URL</label>
                    <input type="url" id="image_url" name="image_url" class="w-full p-2 border border-gray-300 rounded mt-1">
                </div>

                <div class="mb-4">
                    <label for="article_url" class="block text-gray-700">Article URL</label>
                    <input type="url" id="article_url" name="article_url" class="w-full p-2 border border-gray-300 rounded mt-1">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700">Choose Social Media Platforms</label>
                    <div class="flex items-center">
                        <input type="checkbox" id="facebook" name="platforms[]" value="facebook" class="mr-2">
                        <label for="facebook" class="mr-4">Facebook</label>
                        <input type="checkbox" id="twitter" name="platforms[]" value="twitter" class="mr-2">
                        <label for="twitter" class="mr-4">Twitter</label>
                        <input type="checkbox" id="instagram" name="platforms[]" value="instagram" class="mr-2">
                        <label for="instagram" class="mr-4">Instagram</label>
                        <input type="checkbox" id="linkedin" name="platforms[]" value="linkedin" class="mr-2">
                        <label for="linkedin">LinkedIn</label>
                    </div>
                </div>

                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Post
                </button>
            </form>
        </main>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white p-4 text-center">
        &copy; 2024 Your Company
    </footer>

</body>

</html>