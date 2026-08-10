@extends('cms.parent')

@section('title', 'Index Admins')

@section('main-title', 'Index Admins')

@section('sub-title', 'index Admins')

@section('styles')
@endsection

@section('content')

    <!-- /.col -->
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header">
                <div class=" d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Admins Table</h3>
                    <a href="{{ route('admin.admins.create') }}" class="btn btn-info">Create Admin</a>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0 table-responsive w-100">
                <table class="table table-striped align-middle m-0 text-nowrap">
                    <thead>
                        <tr class="text-center">
                            <th style="width: 10px">#</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>gender</th>
                            <th>City</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($admins as $admin)
                            <tr class="align-middle text-center">
                                <td>{{ $admin->id }}</td>
                                <td>
                                    <img src="{{ $admin->profile_image ? asset($admin->profile_image) : asset('cms/assets/img/gray-user-profile-icon-png-fP8Q1P.png') }}"
                                        alt="Admin Image" class="mr-2 mx-auto"
                                        style="border: 2px solid #dee2e6; border-radius: 50%; width: 53px; aspect-ratio: 1/1; object-fit: cover;">
                                </td>
                                <td>{{ $admin->name }}</td>
                                <td>{{ $admin->email }}</td>
                                <td><span class="badge text-bg-{{ $admin->gender == 'male' ? 'primary' : 'danger' }}">{{ $admin->gender }}</span></td>
                                <td>{{ $admin->city == 'gaza' ? 'Gaza' : ($admin->city == 'khan_younis' ? 'Khan Younis' : ($admin->city == 'rafah' ? 'Rafah' : ($admin->city == 'jabalia' ? 'Jabalia' : ($admin->city == 'beit_hanun' ? 'Beit Hanun' : ($admin->city == 'beit_lahya' ? 'Beit Lahya' : ($admin->city == 'deir_al_balah' ? 'Deir al-Balah' : ($admin->city == 'al_zawaid' ? 'Al Zawaid' : ($admin->city == 'al_nasirat' ? 'Al Nasirat' : ($admin->city == 'al_brij' ? 'Al Brij' : 'Al Mughazi'))))))))) }}
                                </td>
                                <td><span
                                        class="badge text-bg-warning">{{ $admin->roles->first()?->name ?? '-' }}</span>
                                </td>
                                <td><span
                                        class="badge text-bg-{{ $admin->is_active ? 'success' : 'danger' }}">{{ $admin->is_active ? 'Active' : 'Inactive' }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.admins.show', $admin->id) }}"
                                        class="btn btn-sm btn-primary">Show</a>
                                    <a href="{{ route('admin.admins.edit', $admin->id) }}"
                                        class="btn btn-sm btn-info">Edit</a>
                                    <form action="{{ route('admin.admins.destroy', $admin->id) }}" method="POST"
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
            @if ($admins->hasPages())
                <span class="p-2">{{ $admins->links() }}</span>
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
