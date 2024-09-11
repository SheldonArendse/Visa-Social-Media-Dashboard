<aside class="sidebar bg-accent w-64 min-h-screen flex flex-col shadow-lg">
    <div class="p-4 bg-primary">
        <h1 class="text-accent text-xl font-bold">SA Migration</h1>
    </div>
    <div class="flex-1 flex flex-col justify-between">
        <nav class="flex-1 pt-5 pb-4 overflow-y-auto">
            <a href="{{ route('dashboard') }}" id="side-nav" class="flex items-center px-4 py-2 text-gray-300 hover:bg-secondary hover:text-accent">
                <i class="fas fa-tachometer-alt mr-3"></i>
                Dashboard
            </a>
            <a href="{{ route('articles') }}" class="flex items-center px-4 py-2 text-gray-300 hover:bg-secondary hover:text-accent">
                <i class="fas fa-newspaper mr-3"></i>
                Articles
            </a>
            <a href="{{ route('analytics') }}" class="flex items-center px-4 py-2 text-gray-300 hover:bg-secondary hover:text-accent">
                <i class="fas fa-chart-line mr-3"></i>
                Analytics
            </a>
            <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-gray-300 hover:bg-secondary hover:text-accent">
                <i class="fas fa-user mr-3"></i>
                Profile
            </a>
            <a href="#" class="flex items-center px-4 py-2 text-gray-300 hover:bg-secondary hover:text-accent">
                <i class="fas fa-sign-out-alt mr-3"></i>
                Logout
            </a>
        </nav>
    </div>
</aside>