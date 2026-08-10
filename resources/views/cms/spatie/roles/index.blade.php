@extends('cms.parent')

@section('title', 'Index Roles')

@section('main-title', 'Index Roles')

@section('sub-title', 'index roles')

@section('styles')
@endsection

@section('content')

    <!-- /.col -->
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header">
                <div class=" d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Roles Table</h3>
                    @can('Create Role')
                        <a href="{{ route('admin.roles.create') }}" class="btn btn-info">Create Role</a>
                    @endcan
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0 table-responsive w-100">
                <table class="table table-striped align-middle m-0 text-nowrap">
                    <thead>
                        <tr class="text-center">
                            <th style="width: 10px">#</th>
                            <th>Role Name</th>
                            <th>User Types</th>
                            @can('Index Role-Permissions')
                                <th>Permissions</th>
                            @endcan
                            <th>Users</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $role)
                            <tr class="align-middle text-center">
                                <td>{{ $role->id }}</td>
                                <td>{{ $role->name }}</td>
                                <td>
                                    @foreach ($role->userTypes as $userType)
                                        <span
                                            class="badge {{ $userType->id == 1 ? 'bg-secondary' : ($userType->id == 2 ? 'bg-success' : 'bg-info') }}">{{ $userType->name }}</span>
                                    @endforeach
                                </td>
                                @can('Index Role-Permissions')
                                    <td>
                                        <a href="{{ route('admin.roles.permissions', $role->id) }}"
                                            class="text-decoration-none btn btn-primary">Permissions
                                            ({{ $role->permissions_count }})</a>
                                    </td>
                                @endcan
                                <td>
                                    {{ $role->users_count }}
                                </td>
                                <td>
                                    {{-- <a href="{{ route('admin.roles.show', $role->id) }}"
                                        class="btn btn-sm btn-primary">Show</a>
                                        @can('Edit Role')
                                        <a href="{{ route('admin.roles.edit', $role->id) }}"
                                            class="btn btn-sm btn-info">Edit</a> @endcan --}}

                                    @can('Delete Role')
                                        <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST"
                                            style="display: inline-block;" class="form-delete delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach


                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
            @if ($roles->hasPages())
                <span class="p-2">{{ $roles->links() }}</span>
            @endif
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col -->
@endsection

@section('scripts')
    @include('components.alerts')
    <script>
        document.querySelectorAll('.delete-form').forEach(form => {

            form.addEventListener('submit', async function(e) {

                e.preventDefault();

                const result = await Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                });

                if (!result.isConfirmed) return;

                try {

                    const response = await ajaxRequest(
                        this.action,
                        'DELETE'
                    );

                    this.closest('tr').remove();

                    Swal.fire({
                        icon: response.icon,
                        title: response.message,
                        showConfirmButton: false,
                        timer: 1200
                    });

                } catch (error) {

                    if (error.errors) {

                        showErrors(error.errors);

                    } else {

                        Swal.fire({
                            icon: error.icon ?? 'error',
                            title: error.message ?? 'Delete failed'
                        });

                    }

                }

            });

        });
    </script>
    {{-- with onclick="deleteColumn(event,this)" on the button --}}
    {{-- <script>
        function deleteColumn(event, element) {
            event.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    element.closest('form').submit();
                    // event.target.closest('form').submit();
                }
            })
        }
    </script> --}}

@endsection
