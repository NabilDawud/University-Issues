@extends('cms.parent')

@section('title', 'Index Students')

@section('main-title', 'Index Students')

@section('sub-title', 'index students')

@section('styles')
@endsection

@section('content')

    <!-- /.col -->
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header">
                <div class=" d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Students Table</h3>
                    @can('Create Student')
                        <a href="{{ route('admin.students.create') }}" class="btn btn-info">Create Student</a>
                    @endcan
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
                            <th>ID Number</th>
                            <th>Student Number</th>
                            <th>Email</th>
                            <th>gender</th>
                            <th>City</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($students as $student)
                            <tr class="align-middle text-center">
                                <td>{{ $student->id }}</td>
                                <td>
                                    <img src="{{ $student->profile_image ? asset($student->profile_image) : asset('cms/assets/img/gray-user-profile-icon-png-fP8Q1P.png') }}"
                                        alt="User Image" class="mr-2 mx-auto"
                                        style="border: 2px solid #dee2e6; border-radius: 50%; width: 53px; aspect-ratio: 1/1; object-fit: cover;">
                                </td>
                                <td>{{ $student->name }}</td>
                                <td>{{ $student->student->id_number ?? '' }}</td>
                                <td>{{ $student->student->student_number ?? '' }}</td>
                                <td>{{ $student->email }}</td>
                                <td><span
                                        class="badge text-bg-{{ $student->gender == 'male' ? 'primary' : 'danger' }}">{{ $student->gender }}</span>
                                </td>
                                <td>{{ $student->city == 'gaza' ? 'Gaza' : ($student->city == 'khan_younis' ? 'Khan Younis' : ($student->city == 'rafah' ? 'Rafah' : ($student->city == 'jabalia' ? 'Jabalia' : ($student->city == 'beit_hanun' ? 'Beit Hanun' : ($student->city == 'beit_lahya' ? 'Beit Lahya' : ($student->city == 'deir_al_balah' ? 'Deir al-Balah' : ($student->city == 'al_zawaid' ? 'Al Zawaid' : ($student->city == 'al_nasirat' ? 'Al Nasirat' : ($student->city == 'al_brij' ? 'Al Brij' : 'Al Mughazi'))))))))) }}
                                </td>
                                <td><span
                                        class="badge text-bg-secondary">{{ $student->roles->first()?->name ?? '' }}</span>
                                </td>
                                <td><span
                                        class="badge text-bg-{{ $student->is_active ? 'success' : 'danger' }}">{{ $student->is_active ? 'Active' : 'Inactive' }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.students.show', $student->id) }}"
                                        class="btn btn-sm btn-primary">Show</a>
                                    @can('Edit Student')
                                        <a href="{{ route('admin.students.edit', $student->id) }}"
                                            class="btn btn-sm btn-info">Edit</a>
                                    @endcan
                                    @can('Delete Student')
                                        <form action="{{ route('admin.students.destroy', $student->id) }}" method="POST"
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
            @if ($students->hasPages())
                <span class="p-2">{{ $students->links() }}</span>
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
