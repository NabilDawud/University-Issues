@extends('cms.parent')

@section('title', 'Reassign Issue #' . $issue->id)
@section('main-title', 'Reassign Issue')
@section('sub-title', 'Issue #' . $issue->id)

@section('content')
    <div class="row">
        <!-- كارت ملخص القضية -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Issue Summary</h3>
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>Issue ID:</strong> #{{ $issue->id }}</p>
                    <p class="mb-2"><strong>Requester:</strong> {{ $issue->user->name ?? 'N/A' }}</p>
                    <p class="mb-2"><strong>Category:</strong> <span
                            class="badge bg-secondary">{{ $issue->category->title ?? 'N/A' }}</span></p>
                    <p class="mb-2"><strong>Currently Assigned To:</strong> <span
                            class="badge bg-info text-dark">{{ $issue->assignedTo->name ?? 'Unassigned' }}</span></p>
                    <hr>
                    <p class="mb-1"><strong>Description:</strong></p>
                    <p class="text-muted small">{{ Str::limit($issue->description, 150) }}</p>
                </div>
            </div>
        </div>

        <!-- نموذج إعادة التعيين -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Reassign Target & Notes</h3>
                </div>
                <form id="reassignForm" novalidate action="{{ route('admin.issues.reassign', $issue->id) }}" method="POST">
                    @csrf
                    <div class="card-body">

                        {{-- اختيار المسؤول الجديد --}}
                        <div class="mb-3 fieldsDiv">
                            <label for="assigned_to" class="form-label">Assign To <span class="text-danger">*</span></label>
                            <select class="form-select" id="assigned_to" name="assigned_to" required>
                                <option value="" selected disabled>-- Select Employee / Officer --</option>
                                @foreach ($assignableUsers as $user)
                                    <option value="{{ $user->id }}">
                                        {{ $user->name }}
                                        @if ($user->roles->isNotEmpty())
                                            ({{ $user->roles->pluck('name')->implode(', ') }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>

                        {{-- ملاحظات التعيين --}}
                        <div class="mb-3">
                            <label for="admin_notes" class="form-label">Notes / Reason for Reassignment</label>
                            <textarea class="form-control" id="admin_notes" name="admin_notes" rows="4"
                                placeholder="Enter any notes or reasons for transferring this issue..."></textarea>
                        </div>

                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <a href="{{ route('admin.issues.show', $issue->id) }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-paper-plane me-1"></i> Confirm Reassignment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @include('components.alerts')
    <script>
        document.getElementById('reassignForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            try {
                const response = await ajaxRequest(this.action, 'POST', formData);

                Swal.fire({
                    icon: response.icon ?? 'success',
                    title: response.message,
                    showConfirmButton: false,
                    timer: 1200
                }).then(() => {
                    if (response.redirect) {
                        window.location.href = response.redirect;
                    } else {
                        window.location.reload();
                    }
                });

            } catch (error) {
                if (error.errors) {
                    showErrors(error.errors);
                } else {
                    Swal.fire({
                        icon: error.icon ?? 'error',
                        title: error.message ?? 'Reassignment failed'
                    });
                }
            }
        });
    </script>
@endsection
