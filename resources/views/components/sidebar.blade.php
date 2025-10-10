<aside class="w-64 min-h-screen bg-base-200 dark:bg-base-300 p-4 flex flex-col">
    <div class="mb-6">
        <img src="{{ Vite::image('logo.svg') }}" alt="Logo">
    </div>
    <ul class="menu menu-vertical p-0 w-full grow">
        <li><a href="{{ route('dashboard.index') }}"><x-lucide-layout-dashboard /> Dashboard</a></li>
        <li><a href="{{ route('users.index') }}"><x-lucide-users-round /> Users</a></li>
        <li><a href="#"><x-lucide-settings /> Settings</a></li>
        <li class="mt-auto">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="cursor-pointer">
                    <x-lucide-log-out class="inline-block me-1 mb-1" /> <span>Logout</span>
                </button>
            </form>
        </li>
    </ul>
</aside>