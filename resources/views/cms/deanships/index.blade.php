@extends('cms.parent')

@section('title', 'Deanships')

@section('main-title', 'Index Deanships')

@section('sub-title', 'index deanships')

@section('styles')
@endsection

@section('content')

    <!-- /.col -->
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header">
                <div class=" d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Deanships Table</h3>
                    @can('Create Deanship')
                        <a href="{{ route('admin.deanships.create') }}" class="btn btn-info">Create Deanship</a>
                    @endcan
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0 table-responsive w-100">
                <table class="table table-striped align-middle m-0 text-nowrap">
                    <thead>
                        <tr class="text-center">
                            <th style="width: 10px">#</th>
                            <th>Deanship Name</th>
                            <th>Extension Number</th>
                            <th>Office Number</th>
                            <th>Status</th>
                            <th>Number of Departments</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($deanships as $deanship)
                            <tr class="align-middle text-center">
                                <td>{{ $deanship->id }}</td>
                                <td>{{ $deanship->trans_name }}</td>
                                <td>{{ $deanship->extension_number }}</td>
                                <td>{{ $deanship->office_number }}</td>
                                <td><span
                                        class="badge text-bg-{{ $deanship->is_active ? 'success' : 'danger' }}">{{ $deanship->is_active ? 'Active' : 'Inactive' }}</span>
                                </td>
                                <td>{{ $deanship->departments_count }}</td>
                                <td>
                                    <a href="{{ route('admin.deanships.show', $deanship->id) }}"
                                        class="btn btn-sm btn-primary">Show</a>
                                    @can('Edit Deanship')
                                        <a href="{{ route('admin.deanships.edit', $deanship->id) }}"
                                            class="btn btn-sm btn-info">Edit</a>
                                    @endcan
                                    @can('Delete Deanship')
                                        <form action="{{ route('admin.deanships.destroy', $deanship->id) }}" method="POST"
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
            @if ($deanships->hasPages())
                <span class="p-2">{{ $deanships->links() }}</span>
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
