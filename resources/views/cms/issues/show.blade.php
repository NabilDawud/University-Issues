@extends('cms.parent')

@section('title', 'Issue Details #' . $issue->id)
@section('main-title', 'Issue Details')
@section('sub-title', 'Issue #' . $issue->id)

@section('content')
    <div class="row">
        <!-- 1. Issue Details -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="card-title mb-1">Issue #{{ $issue->id }} - {{ $issue->category->title ?? 'N/A' }}</h3>
                            <div class="mt-1">
                                @switch($issue->status)
                                    @case('approved')
                                        <span class="badge bg-success fs-5 px-2 py-1 fw-medium">Approved</span>
                                    @break

                                    @case('rejected')
                                        <span class="badge bg-danger fs-5 px-2 py-1 fw-medium">Rejected</span>
                                    @break

                                    @case('under_review')
                                        <span class="badge bg-warning text-dark fs-5 px-2 py-1 fw-medium">Under Review</span>
                                    @break

                                    @default
                                        <span class="badge bg-info fs-5 px-2 py-1 fw-medium">Pending</span>
                                @endswitch
                            </div>
                        </div>
                        <div>
                            @can('update', $issue)
                                <a href="{{ route('admin.issues.edit', $issue->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i> Edit Issue
                                </a>
                            @endcan
                            @can('viewAny', $issue)
                                <a href="{{ route('admin.issues.index') }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-list"></i> Index Issues
                                </a>
                            @endcan
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <p><strong>Requester:</strong> {{ $issue->user->name ?? 'N/A' }} ({{ $issue->requester_number }})</p>
                    <p><strong>Major:</strong> {{ $issue->major->trans_name ?? 'General / None' }}</p>
                    @if (Auth::user()->user_type_id != 2)
                        <p><strong>Assigned To:</strong> <span
                                class="badge bg-light text-dark border">{{ $issue->assignedTo->name ?? 'Unassigned' }}</span>
                        </p>
                    @endif
                    @if ($issue->status == 'rejected')
                        <p><strong>Rejection Reason:</strong> {{ $issue->rejection_reason ?? 'N/A' }}</p>
                    @endif
                    <hr>
                    <h5>Description</h5>
                    <p class="text-muted">{{ $issue->description }}</p>

                    @if (!empty($issue->form_data))
                        <hr>
                        <h5>Form Additional Data</h5>
                        <ul>
                            @foreach ($issue->form_data as $key => $value)
                                <li><strong>{{ ucfirst($key) }}:</strong>
                                    {{ is_array($value) ? implode(', ', $value) : $value }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

            @if (Auth::user()->user_type_id != 2)
                <!-- Assignments History Timeline -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="card-title">Assignments History</h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-sm text-center align-middle">
                            <thead>
                                <tr>
                                    <th>Action By</th>
                                    <th>Assigned To</th>
                                    <th>Status</th>
                                    <th>Notes / Reason</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($issue->assignments as $assignment)
                                    <tr>
                                        <td>{{ $assignment->actionBy->name ?? 'System' }}</td>
                                        <td>{{ $assignment->assignedTo->name ?? 'N/A' }}</td>
                                        <td><span class="badge bg-secondary">{{ $assignment->status }}</span></td>
                                        <td>{{ $assignment->admin_notes ?? ($assignment->rejection_reason ?? '-') }}</td>
                                        <td>{{ $assignment->created_at->format('Y-m-d H:i') }}
                                            ({{ $assignment->created_at->diffForHumans() }})
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5">No history recorded yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>

        <!-- 2. Actions & Discussion Section -->
        <div class="col-md-4">
            <!-- Management Actions Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Management Actions</h3>
                </div>
                <div class="card-body">
                    @if (in_array($issue->status, ['pending', 'under_review']))

                        {{-- Approve Button --}}
                        @can('approve', $issue)
                            <button type="button"
                                onclick="performAction('{{ route('admin.issues.approve', $issue->id) }}', 'POST', 'Approve this issue?')"
                                class="btn btn-success w-100 mb-2">
                                <i class="fas me-1"></i> Approve
                            </button>
                        @endcan

                        {{-- Reject Button --}}
                        @can('reject', $issue)
                            <button type="button" class="btn btn-danger w-100 mb-2" data-bs-toggle="modal"
                                data-bs-target="#rejectModal">
                                <i class="fas me-1"></i> Reject
                            </button>
                        @endcan

                        {{-- Close Button --}}
                        @can('close', $issue)
                            <button type="button"
                                onclick="performAction('{{ route('admin.issues.close', $issue->id) }}', 'POST', 'Close this issue?')"
                                class="btn btn-secondary w-100 mb-2">
                                <i class="fas me-1"></i> Close
                            </button>
                        @endcan

                        {{-- Reassign Button --}}
                        @can('reassign', $issue)
                            <a href="{{ route('admin.issues.reassign', $issue->id) }}" class="btn btn-warning w-100 mb-2">
                                <i class="fas me-1"></i> Reassign Issue
                            </a>
                        @endcan

                        @if (
                            !Auth::user()->can('approve', $issue) &&
                                !Auth::user()->can('reject', $issue) &&
                                !Auth::user()->can('reassign', $issue))
                            <div class="alert alert-info mb-0">
                                You don't have active permissions to manage this issue.
                            </div>
                        @endif
                    @else
                        <div class="alert alert-secondary mb-0 text-center">
                            This issue is <strong>{{ strtoupper($issue->status) }}</strong> and closed for actions.
                        </div>
                    @endif
                </div>
            </div>

            <!-- Comments & Discussion Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Discussion & Comments</h3>
                </div>
                <div class="card-body">
                    <!-- Add Comment Form -->
                    <form id="comment-form" novalidate
                        onsubmit="submitComment(event, '{{ route('admin.issues.comments.store', $issue->id) }}')">
                        @csrf
                        <div class="mb-3 fieldsDiv">
                            <label for="comment" class="form-label">Add Comment / Inquiry</label>
                            <textarea name="comment" id="comment" class="form-control" rows="3" required
                                placeholder="Type your comment or note here..."></textarea>
                            <div class="invalid-feedback"></div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 mb-3">
                            <i class="fas fa-paper-plane me-1"></i> Post Comment
                        </button>
                    </form>

                    <hr>

                    <!-- Comments List -->
                    <div id="comments-container">
                        <h5 class="mb-3">Discussion History</h5>
                        <div class="comments-list" id="comments-list" style="max-height: 380px; overflow-y: auto;">
                            @forelse($issue->comments as $comment)
                                <div
                                    class="card mb-2 p-3 {{ $comment->user_id === Auth::id() ? 'bg-light border-primary' : '' }}">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <strong
                                            class="{{ $comment->user_id === Auth::id() ? 'text-dark' : 'text-light' }}">{{ $comment->user->name }}</strong>
                                        <small
                                            class="{{ $comment->user_id === Auth::id() ? 'text-dark' : 'text-white' }} p-1 rounded">{{ $comment->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-0 text-secondary">{{ $comment->comment }}</p>
                                </div>
                            @empty
                                <p class="text-muted text-center my-3" id="no-comments-msg">No comments recorded yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reject Issue</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="rejectForm" novalidate
                    onsubmit="submitReject(event, '{{ route('admin.issues.reject', $issue->id) }}')">
                    <div class="modal-body">
                        <div class="mb-3 fieldsDiv">
                            <label for="rejection_reason" class="form-label">Rejection Reason <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="3" required></textarea>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Confirm Rejection</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @include('components.alerts')
    <script>
        // Direct Action Handler (e.g. Approve / Close)
        async function performAction(url, method, confirmTitle) {
            const result = await Swal.fire({
                title: confirmTitle,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, proceed!',
                cancelButtonText: 'Cancel'
            });

            if (!result.isConfirmed) return;

            try {
                const response = await ajaxRequest(url, method);
                Swal.fire({
                    icon: response.icon ?? 'success',
                    title: response.message,
                    showConfirmButton: false,
                    timer: 1200
                }).then(() => {
                    window.location.reload();
                });
            } catch (error) {
                Swal.fire({
                    icon: error.icon ?? 'error',
                    title: error.message ?? 'Action failed'
                });
            }
        }

        // Rejection Form Submission
        async function submitReject(e, url) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);

            try {
                const response = await ajaxRequest(url, 'POST', formData);

                const modalEl = document.getElementById('rejectModal');
                const modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) modal.hide();

                Swal.fire({
                    icon: response.icon ?? 'success',
                    title: response.message,
                    showConfirmButton: false,
                    timer: 1200
                }).then(() => {
                    window.location.reload();
                });
            } catch (error) {
                if (error.errors) {
                    showErrors(error.errors);
                } else {
                    Swal.fire({
                        icon: error.icon ?? 'error',
                        title: error.message ?? 'Rejection failed'
                    });
                }
            }
        }

        // New: Submit Comment via AJAX
        async function submitComment(e, url) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end', // أو 'top-start' إذا كانت الواجهة عربية RTL
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });
            try {
                const response = await ajaxRequest(url, 'POST', formData);

                // Clear textarea
                form.reset();

                // Show success toast
                Toast.fire({
                    icon: response.icon ?? 'success',
                    title: response.message ?? 'Comment posted successfully'
                });

                // Remove 'no comments' message if exists
                const noCommentsMsg = document.getElementById('no-comments-msg');
                if (noCommentsMsg) noCommentsMsg.remove();

                // Append new comment to the top of list dynamically
                const commentsList = document.getElementById('comments-list');
                const newCommentHtml = `
                    <div class="card mb-2 p-3 bg-light border-primary">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <strong class="text-dark">${response.comment.user.name}</strong>
                            <small class="text-muted">Just now</small>
                        </div>
                        <p class="mb-0 text-dark">${response.comment.comment}</p>
                    </div>
                `;
                commentsList.insertAdjacentHTML('afterbegin', newCommentHtml);

            } catch (error) {
                if (error.errors) {
                    showErrors(error.errors);
                } else {
                    Swal.fire({
                        icon: error.icon ?? 'error',
                        title: error.message ?? 'Failed to post comment'
                    });
                }
            }
        }
    </script>
@endsection
