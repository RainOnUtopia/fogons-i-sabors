<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h5 mb-0 text-dark">{{ __('admin.users_list') }}</h2>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-sm">{{ __('admin.users.back') }}</a>
        </div>
    </x-slot>

            @if (session('status') === 'user-updated')
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ __('admin.users.updated_successfully') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form method="GET" action="{{ route('admin.users.index') }}" class="mb-4">
                <div class="row g-2">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="{{ __('admin.users.search_placeholder') }}" value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="role" class="form-select">
                            <option value="">{{ __('admin.users.filter_all_roles') }}</option>
                            <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>User</option>
                            <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">{{ __('admin.users.filter_all_statuses') }}</option>
                            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>{{ __('admin.users.active') }}</option>
                            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>{{ __('admin.users.inactive') }}</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex gap-2">
                        <input type="hidden" name="sort" value="{{ request('sort', 'name') }}">
                        <input type="hidden" name="direction" value="{{ request('direction', 'asc') }}">
                        <button type="submit" class="btn btn-primary w-100">{{ __('admin.users.filter_button') }}</button>
                        @if(request()->anyFilled(['search', 'role', 'status']))
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">{{ __('admin.users.reset_button') }}</a>
                        @endif
                    </div>
                </div>
            </form>

            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    @php
                                        // Helper func for sorting
                                        $buildSortUrl = function($column) {
                                            $currentSort = request('sort', 'name');
                                            $currentDirection = request('direction', 'asc');
                                            $newDirection = ($currentSort === $column && $currentDirection === 'asc') ? 'desc' : 'asc';
                                            return request()->fullUrlWithQuery(['sort' => $column, 'direction' => $newDirection]);
                                        };
                                        $sortIndicator = function($column) {
                                            if (request('sort', 'name') === $column) {
                                                return request('direction', 'asc') === 'asc' ? '↑' : '↓';
                                            }
                                            return '';
                                        };
                                    @endphp
                                    <th>
                                        <a href="{{ $buildSortUrl('name') }}" class="text-decoration-none text-dark">
                                            {{ __('auth.name') }} {{ $sortIndicator('name') }}
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ $buildSortUrl('email') }}" class="text-decoration-none text-dark">
                                            {{ __('auth.email') }} {{ $sortIndicator('email') }}
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ $buildSortUrl('role') }}" class="text-decoration-none text-dark">
                                            {{ __('admin.users.role') }} {{ $sortIndicator('role') }}
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ $buildSortUrl('is_active') }}" class="text-decoration-none text-dark">
                                            {{ __('admin.users.status') }} {{ $sortIndicator('is_active') }}
                                        </a>
                                    </th>
                                    <th class="text-end">{{ __('admin.users.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td class="align-middle">{{ $user->name }}</td>
                                        <td class="align-middle">{{ $user->email }}</td>
                                        <td class="align-middle">
                                            <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : 'primary' }}">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </td>
                                        <td class="align-middle">
                                            @if($user->is_active)
                                                <span class="badge bg-success">{{ __('admin.users.active') }}</span>
                                            @else
                                                <span class="badge bg-secondary">{{ __('admin.users.inactive') }}</span>
                                            @endif
                                        </td>
                                        <td class="align-middle text-end">
                                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-primary">
                                                {{ __('admin.users.edit_title') }}
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                {{ $users->links() }}
            </div>
</x-app-layout>
