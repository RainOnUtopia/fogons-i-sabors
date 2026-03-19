<x-app-layout>
    <x-slot name="header">
        <h2 class="h5 mb-0 text-dark">
            {{ __('admin.dashboard_title') }}
        </h2>
    </x-slot>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body py-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.users.index') }}">
                                {{ __('admin.users_list') }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-white">{{ __('admin.welcome') }}</div>
                <div class="card-body">
                    {{ __('dashboard.logged_in') }} (Rol: {{ Auth::user()->role }})
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
