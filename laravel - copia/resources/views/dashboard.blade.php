<x-app-layout>
    <x-slot name="header">
        <h2 class="h5 mb-0 text-dark">
            {{ __('common.dashboard') }}
        </h2>
    </x-slot>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    {{ __('dashboard.logged_in') }} (Rol: {{ Auth::user()->role }})
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
