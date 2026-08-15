@extends('cms.parent')

@section('title', 'Issues List')

@section('main-title', 'Issues')

@section('sub-title', 'Index Issues')

@section('styles')
@endsection

@section('content')

    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Issues Table</h3>
                    @can('create', App\Models\Issue::class)
                        <a href="{{ route('admin.issues.create') }}" class="btn btn-info">Create Issue</a>
                    @endcan
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0 table-responsive w-100">
                <table class="table table-striped align-middle m-0 text-nowrap">
                    <thead>
                        <tr class="text-center">
                            <th style="width: 10px">#</th>
                            <th>Requester</th>
                            <th>Category</th>
                            <th>Major</th>
                            @if (Auth::user()->user_type_id != 2)
                                <th>Assigned To</th>
                            @endif
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($issues as $issue)
                            <tr class="align-middle text-center">
                                <td>{{ $issue->id }}</td>
                                <td>{{ $issue->user->name ?? 'N/A' }}</td>
                                <td><span class="badge bg-secondary">{{ $issue->category->title ?? 'N/A' }}</span></td>
                                <td>{{ $issue->major->trans_name ?? '-' }}</td>
                               @if (Auth::user()->user_type_id != 2)
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            {{ $issue->assignedTo->name ?? 'Unassigned' }}
                                        </span>
                                    </td>
                                @endif
                          
                                <td>
                                    @switch($issue->status)
                                        @case('approved')
                                            <span class="badge bg-success">Approved</span>
                                        @break

                                        @case('rejected')
                                            <span class="badge bg-danger">Rejected</span>
                                        @break

                                        @case('under_review')
                                            <span class="badge bg-warning text-dark">Under Review</span>
                                        @break

                                        @case('closed')
                                            <span class="badge bg-dark">Closed</span>
                                        @break

                                        @default
                                            <span class="badge bg-info">Pending</span>
                                    @endswitch
                                </td>
                                <td>{{ $issue->created_at->format('Y-m-d') }}</td>
                                <td>
                                   @can('view', $issue)
                                    {{-- زر العرض والتفاصيل --}}
                                    <a href="{{ route('admin.issues.show', $issue->id) }}"
                                        class="btn btn-sm btn-primary">Show</a>
                                    @endcan
                                    {{-- زر التعديل إن وجد --}}
                                    @can('update', $issue)
                                        <a href="{{ route('admin.issues.edit', $issue->id) }}"
                                            class="btn btn-sm btn-info">Edit</a>
                                    @endcan

                                    {{-- زر الحذف --}}
                                    @can('delete', $issue)
                                        <form action="{{ route('admin.issues.destroy', $issue->id) }}" method="POST"
                                            style="display: inline-block;" class="form-delete delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">No issues found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
                @if ($issues->hasPages())
                    <div class="card-footer clearfix">
                        {{ $issues->links() }}
                    </div>
                @endif
            </div>
            <!-- /.card -->
        </div>

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
    @endsection
