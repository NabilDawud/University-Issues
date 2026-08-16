@extends('cms.parent')

@section('title', 'Issue Details #' . $issue->id)
@section('main-title', 'Issue Details')
@section('sub-title', 'Issue #' . $issue->id)

@section('content')
    <!-- 1. Issue Details & History (Left Column) -->
    <div class="col-md-8">
        <!-- Details Card -->
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header border-bottom py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="card-title mb-1 fw-bold">Issue #{{ $issue->id }} -
                            {{ $issue->category->title ?? 'N/A' }}</h4>
                        <div class="mt-1">
                            @switch($issue->status)
                                @case('approved')
                                    <span
                                        class="badge bg-success-subtle text-success border border-success px-2 py-1 fw-medium">Approved</span>
                                @break

                                @case('rejected')
                                    <span
                                        class="badge bg-danger-subtle text-danger border border-danger px-2 py-1 fw-medium">Rejected</span>
                                @break

                                @case('under_review')
                                    <span
                                        class="badge bg-warning-subtle text-warning border border-warning px-2 py-1 fw-medium">Under
                                        Review</span>
                                @break

                                @default
                                    <span
                                        class="badge bg-info-subtle text-info border border-info px-2 py-1 fw-medium">Pending</span>
                            @endswitch
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        @can('update', $issue)
                            <a href="{{ route('admin.issues.edit', $issue->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit me-1"></i> Edit Issue
                            </a>
                        @endcan
                        @can('viewAny', $issue)
                            <a href="{{ route('admin.issues.index') }}" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-list me-1"></i> Index Issues
                            </a>
                        @endcan
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Requester:</strong> {{ $issue->user->name ?? 'N/A' }}
                            ({{ $issue->requester_number }})</p>
                        <p class="mb-1"><strong>Major:</strong> {{ $issue->major->trans_name ?? 'General / None' }}</p>
                    </div>
                    <div class="col-md-6">
                        @if (Auth::user()->user_type_id != 2)
                            <p class="mb-1"><strong>Assigned To:</strong>
                                <span
                                    class="badge bg-light text-dark border">{{ $issue->assignedTo->name ?? 'Unassigned' }}</span>
                            </p>
                        @endif
                        @if ($issue->status == 'rejected')
                            <p class="mb-1 text-danger"><strong>Rejection Reason:</strong>
                                {{ $issue->rejection_reason ?? 'N/A' }}</p>
                        @endif
                    </div>
                </div>

                <hr>
                <h5 class="fw-bold fs-6 text-muted mb-2">Description</h5>
                <p class="text-secondary">{{ $issue->description }}</p>

                @if (!empty($issue->form_data))
                    <hr>
                    <h5 class="fw-bold fs-6 text-muted mb-2">Form Additional Data</h5>
                    <ul class="list-group list-group-flush">
                        @foreach ($issue->form_data as $key => $value)
                            <li class="list-group-item px-0 py-1 border-0 bg-transparent">
                                <strong class="text-capitalize">{{ str_replace('_', ' ', $key) }}:</strong>
                                {{ is_array($value) ? implode(', ', $value) : $value }}
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        @if (Auth::user()->user_type_id != 2)
            <!-- Assignments History Timeline -->
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header border-bottom py-3">
                    <h5 class="card-title mb-0 fw-bold">Assignments History</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm text-center align-middle mb-0">
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
                                            <small
                                                class="text-muted d-block">({{ $assignment->created_at->diffForHumans() }})</small>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-3 text-muted">No history recorded yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- 2. Actions & Discussion Section (Right Column) -->
    <div class="col-md-4">
        <!-- Management Actions Card -->
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header border-bottom py-3">
                <h5 class="card-title mb-0 fw-bold">Management Actions</h5>
            </div>
            <div class="card-body">
                @if (in_array($issue->status, ['pending', 'under_review']))
                    <div class="d-grid gap-2">
                        {{-- Approve Button --}}
                        @can('approve', $issue)
                            <button type="button"
                                onclick="performAction('{{ route('admin.issues.approve', $issue->id) }}', 'POST', 'Approve this issue?')"
                                class="btn btn-success">
                                <i class="fas fa-check-circle me-1"></i> Approve Issue
                            </button>
                        @endcan

                        <div class="row g-2">
                            {{-- Reject Button --}}
                            @can('reject', $issue)
                                <div class="col">
                                    <button type="button" class="btn btn-outline-danger w-100" data-bs-toggle="modal"
                                        data-bs-target="#rejectModal">
                                        <i class="fas fa-times-circle me-1"></i> Reject
                                    </button>
                                </div>
                            @endcan

                            {{-- Close Button --}}
                            @can('close', $issue)
                                <div class="col">
                                    <button type="button"
                                        onclick="performAction('{{ route('admin.issues.close', $issue->id) }}', 'POST', 'Close this issue?')"
                                        class="btn btn-outline-secondary w-100">
                                        <i class="fas fa-lock me-1"></i> Close
                                    </button>
                                </div>
                            @endcan
                        </div>

                        {{-- Reassign Button --}}
                        @can('reassign', $issue)
                            <a href="{{ route('admin.issues.reassign', $issue->id) }}" class="btn btn-warning">
                                <i class="fas fa-user-edit me-1"></i> Reassign Issue
                            </a>
                        @endcan

                        @if (
                            !Auth::user()->can('approve', $issue) &&
                                !Auth::user()->can('reject', $issue) &&
                                !Auth::user()->can('reassign', $issue))
                            <div class="alert alert-info mb-0 text-center">
                                <i class="fas fa-info-circle me-1"></i> No management permissions.
                            </div>
                        @endif
                    </div>
                @else
                    <div class="alert alert-secondary mb-0 text-center">
                        This issue is <strong>{{ strtoupper($issue->status) }}</strong> and closed for actions.
                    </div>
                @endif
            </div>
        </div>

        <!-- Combined Tabbed Card: Comments & Attachments -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header border-bottom p-2">
                <ul class="nav nav-pills nav-justified" id="issueTabs" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active py-2 fw-medium" id="comments-tab" data-bs-toggle="tab"
                            data-bs-target="#comments-pane" type="button">
                            <i class="fas fa-comments me-1"></i> Discussion
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link py-2 fw-medium" id="attachments-tab" data-bs-toggle="tab"
                            data-bs-target="#attachments-pane" type="button">
                            <i class="fas fa-paperclip me-1"></i> Files
                            <span class="badge bg-secondary ms-1"
                                id="attachment-count">{{ $issue->attachments->count() }}</span>
                        </button>
                    </li>
                </ul>
            </div>
            <div class="card-body tab-content">
                <!-- Tab 1: Comments -->
                <div class="tab-pane fade show active" id="comments-pane">
                    <form id="comment-form" novalidate
                        onsubmit="submitComment(event, '{{ route('admin.issues.comments.store', $issue->id) }}')">
                        @csrf
                        <div class="mb-2 fieldsDiv">
                            <textarea name="comment" id="comment" class="form-control" rows="2" required
                                placeholder="Type a comment or note..."></textarea>
                            <div class="invalid-feedback"></div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm w-100 mb-3">
                            <i class="fas fa-paper-plane me-1"></i> Post Comment
                        </button>
                    </form>

                    <hr class="my-2">

                    <div id="comments-container" class="pe-1" style="max-height: 350px; overflow-y: auto;">
                        <div class="comments-list" id="comments-list">
                            @forelse($issue->comments as $comment)
                                @php $isMe = $comment->user_id === Auth::id(); @endphp
                                <div
                                    class="d-flex flex-column mb-3 {{ $isMe ? 'align-items-end' : 'align-items-start' }}">
                                    <div class="d-flex align-items-center gap-1 mb-1">
                                        <small class="fw-bold text-muted">{{ $comment->user->name }}</small>
                                        <small class="text-secondary-emphasis" style="font-size: 0.75rem;">•
                                            {{ $comment->created_at->diffForHumans() }}</small>
                                    </div>
                                    <div
                                        class="p-2 px-3 rounded-3 max-w-75 {{ $isMe ? 'bg-primary text-white' : 'bg-light text-dark border' }}">
                                        <p class="mb-0 small" dir="auto">{{ $comment->comment }}</p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-muted text-center my-3 small" id="no-comments-msg">No comments recorded
                                    yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Tab 2: Attachments -->
                <div class="tab-pane fade" id="attachments-pane">
                    <form id="attachment-form"
                        onsubmit="submitAttachment(event, '{{ route('admin.issues.attachments.store', $issue->id) }}')"
                        novalidate enctype="multipart/form-data">
                        @csrf
                        <div class="mb-2 fieldsDiv">
                            <input type="file" name="file" id="attachment_file"
                                class="form-control form-control-sm" required>
                            <div class="form-text mt-1" style="font-size: 0.75rem;">Allowed: PDF, PNG, JPG, DOCX (Max:
                                5MB)</div>
                            <div class="invalid-feedback"></div>
                        </div>
                        <button type="submit" class="btn btn-outline-primary btn-sm w-100 mb-3" id="btn-upload">
                            <i class="fas fa-upload me-1"></i> Upload Attachment
                        </button>
                    </form>

                    <hr class="my-2">

                    <div id="attachments-list" class="list-group list-group-flush"
                        style="max-height: 350px; overflow-y: auto;">
                        @forelse($issue->attachments as $attachment)
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-2 border-bottom"
                                id="attachment-item-{{ $attachment->id }}">
                                <div class="d-flex align-items-center text-truncate me-2">
                                    <i class="fas fa-file-alt text-primary me-2"></i>
                                    <div class="text-truncate" style="max-width: 150px;">
                                        <a href="{{ asset($attachment->file_path) }}" target="_blank"
                                            class="text-muted fw-bold text-decoration-none small">
                                            {{ $attachment->file_name }}
                                        </a>
                                        <div class="text-muted" style="font-size: 0.7rem;">
                                            {{ round($attachment->file_size / 1024, 1) }} KB •
                                            {{ $attachment->user->name }}
                                        </div>
                                    </div>
                                </div>
                                <div class="btn-group">
                                    <a href="{{ asset($attachment->file_path) }}" download
                                        class="btn btn-sm btn-light text-primary py-0 px-2" title="Download">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    @if (Auth::id() == $attachment->user_id || Auth::user()->user_type_id != 2)
                                        <button type="button" class="btn btn-sm btn-light text-danger py-0 px-2"
                                            onclick="deleteAttachment('{{ route('admin.issues.attachments.destroy', $attachment->id) }}', {{ $attachment->id }})"
                                            title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-muted text-center my-3 small" id="no-attachments-msg">No attachments uploaded
                                yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog border-0">
            <div class="modal-content">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title fw-bold">Reject Issue</h5>
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
                    <div class="modal-footer border-top">
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

        // 1. دالة التمرير للأسفل
        function scrollToBottomComments() {
            const commentsContainer = document.getElementById('comments-container');
            if (commentsContainer) {
                commentsContainer.scrollTop = commentsContainer.scrollHeight;
            }
        }

        // التمرير تلقائيًا عند تحميل الصفحة
        document.addEventListener('DOMContentLoaded', function() {
            scrollToBottomComments();
        });

        // التمرير عند فتح تبويب التعليقات
        const commentsTab = document.getElementById('comments-tab');
        if (commentsTab) {
            commentsTab.addEventListener('shown.bs.tab', function() {
                scrollToBottomComments();
            });
        }

        // 1. دالة ديناميكية لإنشاء Toast تضمن وجود Swal دائماً عند الاستدعاء
        function getToast() {
            return Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });
        }
        // 2. دالة إرسال التعليق المعدلة عبر AJAX
        async function submitComment(e, url) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);

            try {
                const response = await ajaxRequest(url, 'POST', formData);

                // مسح خانة الإدخال
                form.reset();

                // إظهار إشعار النجاح
                getToast().fire({
                    icon: response.icon ?? 'success',
                    title: response.message ?? 'Comment posted successfully'
                });

                // إزالة رسالة "لا توجد تعليقات"
                const noCommentsMsg = document.getElementById('no-comments-msg');
                if (noCommentsMsg) noCommentsMsg.remove();

                // إنشاء التنسيق الجديد (فقاعة محادثة للمستخدم الحالي)
                const commentsList = document.getElementById('comments-list');
                const newCommentHtml = `
            <div class="d-flex flex-column mb-3 align-items-end">
                <div class="d-flex align-items-center gap-1 mb-1">
                    <small class="fw-bold text-muted">${response.comment.user.name}</small>
                    <small class="text-secondary-emphasis" style="font-size: 0.75rem;">• Just now</small>
                </div>
                <div class="p-2 px-3 rounded-3 bg-primary text-white" style="max-width: 85%;">
                    <p class="mb-0 small" dir="auto">${response.comment.comment}</p>
                </div>
            </div>
        `;

                // إدراج التعليق في آخر القائمة (beforeend) وتمرير السكرول للأسفل
                commentsList.insertAdjacentHTML('beforeend', newCommentHtml);
                scrollToBottomComments();

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

        // Upload Attachment via AJAX
        async function submitAttachment(e, url) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);
            const submitBtn = document.getElementById('btn-upload');

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Uploading...';

            try {
                const response = await ajaxRequest(url, 'POST', formData);

                form.reset();

                getToast().fire({
                    icon: response.icon ?? 'success',
                    title: response.message ?? 'File uploaded successfully'
                });

                // Remove 'no attachments' message if present
                const noMsg = document.getElementById('no-attachments-msg');
                if (noMsg) noMsg.remove();

                // Append new attachment item to list
                const list = document.getElementById('attachments-list');
                const itemHtml = `
            <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-2" id="attachment-item-${response.attachment.id}">
                <div class="d-flex align-items-center text-truncate me-2">
                    <i class="fas fa-file-alt text-primary me-2 fa-lg"></i>
                    <div class="text-truncate">
                        <a href="${response.attachment.file_url}" target="_blank" class="text-muted fw-bold text-decoration-none">
                            ${response.attachment.file_name}
                        </a>
                        <br>
                        <small class="text-muted">${(response.attachment.file_size / 1024).toFixed(1)} KB • Just now</small>
                    </div>
                </div>
                <div class="btn-group">
                    <a href="${response.attachment.file_url}" download class="btn btn-sm btn-light text-primary py-0 px-2" title="Download">
                        <i class="fas fa-download"></i>
                    </a>
                    <button type="button" class="btn btn-sm btn-light text-danger py-0 px-2" title="Delete" onclick="deleteAttachment('${response.delete_url}', ${response.attachment.id})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;
                list.insertAdjacentHTML('afterbegin', itemHtml);

                // Update Counter
                const countBadge = document.getElementById('attachment-count');
                if (countBadge) countBadge.innerText = parseInt(countBadge.innerText) + 1;

            } catch (error) {
                if (error.errors) {
                    showErrors(error.errors);
                } else {
                    Swal.fire({
                        icon: error.icon ?? 'error',
                        title: error.message ?? 'Upload failed'
                    });
                }
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-paperclip me-1"></i> Upload Attachment';
            }
        }

        // Delete Attachment via AJAX
        async function deleteAttachment(url, id) {
            const result = await Swal.fire({
                title: 'Delete this file?',
                text: 'This action cannot be undone!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            });

            if (!result.isConfirmed) return;

            try {
                const response = await ajaxRequest(url, 'DELETE');

                getToast().fire({
                    icon: 'success',
                    title: response.message ?? 'File deleted'
                });

                // Remove element from UI
                const item = document.getElementById(`attachment-item-${id}`);
                if (item) item.remove();

                // Update Counter
                const countBadge = document.getElementById('attachment-count');
                if (countBadge) {
                    const newCount = Math.max(0, parseInt(countBadge.innerText) - 1);
                    countBadge.innerText = newCount;
                    if (newCount === 0) {
                        document.getElementById('attachments-list').innerHTML =
                            '<p class="text-muted text-center my-3" id="no-attachments-msg">No attachments uploaded yet.</p>';
                    }
                }
            } catch (error) {
                Swal.fire({
                    icon: error.icon ?? 'error',
                    title: error.message ?? 'Delete failed'
                });
            }
        }
    </script>

@endsection
