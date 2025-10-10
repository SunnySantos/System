<aside class="w-64 min-h-screen bg-base-200 dark:bg-base-300 p-4 flex flex-col">
    <div class="mb-6">
        <img src="{{ Vite::image('logo.svg') }}" alt="Logo">
    </div>
    <ul class="menu menu-vertical p-0 w-full grow">
        <li><a href="{{ route('dashboard.index') }}"><x-lucide-layout-dashboard /> Dashboard</a></li>
        <li><a href="{{ route('users.index') }}"><x-lucide-users-round /> Users</a></li>
        <li><a href="{{ route('settings.index') }}"><x-lucide-settings /> Settings</a></li>
        <li class="mt-auto">
            <a href="#" onclick='event.preventDefault(); document.getElementById("logout_form").submit();'><x-lucide-log-out /> Logout</a>
            <form action="{{ route('logout') }}" method="POST" class="hidden" id="logout_form">
                @csrf
                <button type="submit" class="cursor-pointer">
                    Logout
                </button>
            </form>
        </li>
    </ul>
</aside>