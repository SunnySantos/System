<aside class="w-64 min-h-screen bg-base-200 dark:bg-base-300 p-4 flex flex-col">
    <div class="mb-6">
        <img src="{{ Vite::image('logo.svg') }}" alt="Logo">
    </div>
    <ul class="menu menu-vertical p-0 w-full grow">
        @if(Auth::user()->canAccess('dashboard.index'))
            <li><a href="{{ route('dashboard.index') }}"><x-lucide-layout-dashboard /> Dashboard</a></li>
        @endif
        @if(Auth::user()->canAccess('users.index'))
            <li><a href="{{ route('users.index') }}"><x-lucide-users-round /> Users</a></li>
        @endif
        @if(Auth::user()->canAccess('roles.index'))
            <li><a href="{{ route('roles.index') }}"><x-lucide-user-round-cog /> Roles</a></li>
        @endif
        @if(Auth::user()->canAccess('settings.index'))
            <li><a href="{{ route('settings.index') }}"><x-lucide-settings /> Settings</a></li>
        @endif
    </ul>
</aside>