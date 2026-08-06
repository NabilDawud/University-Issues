@extends('cms.parent')

@section('title', 'Index Users')

@section('main-title', 'Index Users')

@section('sub-title', 'index users')

@section('styles')
@endsection

@section('content')

    <!-- /.col -->
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header">
                <div class=" d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Users Table</h3>
                    <a href="{{ route('admin.users.create') }}" class="btn btn-info">Create User</a>
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
                            <th>User Type</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr class="align-middle text-center">
                                <td>{{ $user->id }}</td>
                                <td>
                                    <img src="{{ $user->profile_image ? asset($user->profile_image) : asset('cms/assets/img/gray-user-profile-icon-png-fP8Q1P.png') }}"
                                        alt="User Image" class="mr-2 mx-auto"
                                        style="border: 2px solid #dee2e6; border-radius: 50%; width: 53px; aspect-ratio: 1/1; object-fit: cover;">
                                </td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td><span class="badge text-bg-{{ $user->gender == 'male' ? 'primary' : 'danger' }}">{{ $user->gender }}</span></td>
                                <td>{{ $user->city == 'gaza' ? 'Gaza' : ($user->city == 'khan_younis' ? 'Khan Younis' : ($user->city == 'rafah' ? 'Rafah' : ($user->city == 'jabalia' ? 'Jabalia' : ($user->city == 'beit_hanun' ? 'Beit Hanun' : ($user->city == 'beit_lahya' ? 'Beit Lahya' : ($user->city == 'deir_al_balah' ? 'Deir al-Balah' : ($user->city == 'al_zawaid' ? 'Al Zawaid' : ($user->city == 'al_nasirat' ? 'Al Nasirat' : ($user->city == 'al_brij' ? 'Al Brij' : 'Al Mughazi'))))))))) }}
                                </td>
                                <td><span
                                        class="badge text-bg-{{ $user->userType->id == 1 ? 'warning' : ($user->userType->id == 2 ? 'secondary' : 'light') }}">{{ $user->userType->name ?? 'N/A' }}</span>
                                </td>
                                <td><span
                                        class="badge text-bg-{{ $user->is_active ? 'success' : 'danger' }}">{{ $user->is_active ? 'Active' : 'Inactive' }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.users.show', $user->id) }}"
                                        class="btn btn-sm btn-primary">Show</a>
                                    <a href="{{ route('admin.users.edit', $user->id) }}"
                                        class="btn btn-sm btn-info">Edit</a>
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
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
            @if ($users->hasPages())
                <span class="p-2">{{ $users->links() }}</span>
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
