<!-- resources/views/layouts/sidebar.blade.php -->
<aside class="w-64 bg-white border-r border-gray-100 h-full">
    <div class="p-4">
        <nav class="space-y-1">
            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-nav-link>
            @can(\App\Constants\PermissionSlug::MICROSITES_VIEW_ANY)
                <x-nav-link :href="route('microsites.index')" :active="request()->routeIs('microsites.index')">
                    {{ __('Sitios') }}
                </x-nav-link>
            @endcan
            @can(\App\Constants\PermissionSlug::USERS_VIEW_ANY)
                <x-nav-link :href="route('microsites.index')" :active="request()->routeIs('users.index')">
                    {{ __('Usuarios') }}
                </x-nav-link>
            @endcan
            @can(\App\Constants\PermissionSlug::ROLE_PERMISSION_VIEW)
                <x-nav-link :href="route('rolePermission.permissions')" :active="request()->routeIs('rolePermission.permissions')">
                    {{ __('Permisos') }}
                </x-nav-link>
            @endcan
        </nav>
    </div>
</aside>
