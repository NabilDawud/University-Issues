@extends('cms.parent')

@section('title', 'Index Permissions')

@section('main-title', 'Index Permissions')

@section('sub-title', 'index permissions')

@section('styles')
@endsection

@section('content')

    <!-- /.col -->
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header">
                <div class=" d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Permissions Table</h3>
                    <a href="{{ route('admin.permissions.create') }}" class="btn btn-info">Create Permission</a>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0 table-responsive w-100">
                <table class="table table-striped align-middle m-0 text-nowrap">
                    <thead>
                        <tr class="text-center">
                            <th style="width: 10px">#</th>
                            <th>Permission Name</th>
                            <th>roles</th>
                            <th>Users</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($permissions as $permission)
                            <tr class="align-middle text-center">
                                <td>{{ $permission->id }}</td>
                                <td>{{ $permission->name }}</td>

                                <td>
                                    {{ $permission->roles_count }}
                                </td>
                                <td>
                                    {{ $permission->users_count }}
                                </td>
                                <td>
                                    {{-- <a href="{{ route('admin.permissions.show', $permission->id) }}"
                                        class="btn btn-sm btn-primary">Show</a>
                                    <a href="{{ route('admin.permissions.edit', $permission->id) }}"
                                        class="btn btn-sm btn-info">Edit</a> --}}
                                    <form action="{{ route('admin.permissions.destroy', $permission->id) }}" method="POST"
                                        style="display: inline-block;" class="form-delete delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach


                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
            @if ($permissions->hasPages())
                <span class="p-2">{{ $permissions->links() }}</span>
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
