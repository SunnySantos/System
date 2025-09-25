<aside class="w-64 min-h-screen bg-base-200 dark:bg-base-300 p-4">
    <div class="mb-6">
        <img src="{{ Vite::image('logo.svg') }}" alt="Logo">
    </div>
    <ul class="menu menu-vertical p-0 w-full">
        <li><a href="{{ route('dashboard.index') }}"><x-lucide-layout-dashboard /> Dashboard</a></li>
        <li><a href="{{ route('users.index') }}"><x-lucide-users-round /> Users</a></li>
        <li><a href="#"><x-lucide-settings /> Settings</a></li>
        <li><a href="#"><x-lucide-log-out /> Logout</a></li>
    </ul>
</aside>