<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use App\Models\Major;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class IssueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        $issues = Issue::with(['category', 'major', 'user', 'assignedTo', 'actionBy'])
            // إذا لم يكن أدمن، يرى فقط القضايا التي أنشأها أو الموجهة إليه أو التي هو مسؤول عن اتخاذ إجراء بشأنها
            ->when(!$user->isAdmin(), function ($query) use ($user) {
                $query->where(function ($q) use ($user) {
                    $q->where('user_id', $user->id)
                        ->orWhere('assigned_to', $user->id)->orWhere('action_by', $user->id);
                });
            })
            ->latest()
            ->paginate(config('app.pagination_limit', 10));

        return view('cms.issues.index', compact('issues'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = \App\Models\Category::select('id', 'title', 'form_fields')->get();
        $majors = \App\Models\Major::select('id', 'name')->get();
        return view('cms.issues.create', compact('categories', 'majors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'description' => 'required|string',
            'form_data' => 'nullable|array',
            'form_data.*' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'major_id' => 'nullable|exists:majors,id',
        ]);

        $category = \App\Models\Category::find($validatedData['category_id']);
        $allowedKeys = $category->form_fields ?? [];
        $filteredFormData = array_intersect_key(
            $request->input('form_data', []),
            array_flip($allowedKeys)
        );
        if (count($filteredFormData) !== count($request->input('form_data', []))) {
            return response()->json([
                'message' => 'Invalid form data submitted.',
                'icon' => 'error',
            ], 422);
        }

        $assignedToId = null;
        // 1. التوجيه بناءً على التخصص المختار
        if (!empty($validatedData['major_id'])) {
            $major = Major::with('department')->find($validatedData['major_id']);
            if ($major?->department_id) {
                $assignedToId = User::role('Department Head')
                    ->whereHas('employee', function ($q) use ($major) {
                        $q->where('department_id', $major->department_id);
                    })->value('id');
            }
        }
        // 2. إذا لم يكن هناك تخصص، نفحص قسم الطالب المباشر (إن وجد)
        if (!$assignedToId && Auth::user()->student?->major?->department_id) {
            $assignedToId = User::role('Department Head')
                ->whereHas('employee', function ($q) {
                    $q->where('department_id', Auth::user()->student->major->department_id);
                })->value('id');
        }
        // 3. Fallback: التوجيه للأدمن إذا لم يتم تحديد رئيس قسم او ممكن مستقبلا لو كان في مسؤول لشؤون الطلبة نوجه عليه
        if (!$assignedToId) {
            $assignedToId = User::role('Admin')->value('id') ?? User::role('Sub Admin')->value('id');
        }
        return DB::transaction(function () use ($validatedData, $assignedToId, $filteredFormData) {
            $issue = Issue::create([
                'user_id' => Auth::id(),
                'category_id' => $validatedData['category_id'],
                'major_id' => $validatedData['major_id'] ?? null,
                'requester_number' => 'ISSUE-' . date('Y') . '-' . str_pad(Issue::count() + 1, 5, '0', STR_PAD_LEFT),
                'description' => $validatedData['description'],
                'form_data' => $filteredFormData,
                'assigned_to' => $assignedToId,
                'status' => 'pending',
            ]);
            if ($issue->assigned_to) {
                $issue->assignments()->create([
                    'assigned_to' => $issue->assigned_to,
                    'action_by' => Auth::id(),
                    'status' => 'pending',
                ]);
            }
            return response()->json([
                'message' => 'Issue created successfully',
                'icon' => 'success',
            ], 201);
        });

    }

    /**
     * Display the specified resource.
     */
    public function show(Issue $issue)
    {
        Gate::authorize('view', $issue);
        $issue->load([
            'user.student',
            'category',
            'major.department',
            'assignedTo',
            'actionBy',
            'assignments.assignedTo',
            'assignments.actionBy',
            'comments.user'
        ]);
        return view('cms.issues.show', compact('issue'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Issue $issue)
    {
        Gate::authorize('update', $issue);
        $majors = Major::select('id', 'name')->get();
        $categories = \App\Models\Category::select('id', 'title', 'form_fields')->get();
        $issue->load(['category', 'major']);
        return view('cms.issues.edit', compact('issue', 'majors', 'categories'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Issue $issue)
    {
        Gate::authorize('update', $issue);
        $validatedData = $request->validate([
            'description' => 'required|string',
            'form_data' => 'nullable|array',
            'form_data.*' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'major_id' => 'nullable|exists:majors,id',
        ]);

        $category = \App\Models\Category::find($validatedData['category_id']);
        $allowedKeys = $category->form_fields ?? [];
        $filteredFormData = array_intersect_key(
            $request->input('form_data', []),
            array_flip($allowedKeys)
        );
        if (count($filteredFormData) !== count($request->input('form_data', []))) {
            return response()->json([
                'message' => 'Invalid form data submitted.',
                'icon' => 'error',
            ], 422);
        }

        return DB::transaction(function () use ($issue, $validatedData, $filteredFormData) {
            $issue->update([
                'description' => $validatedData['description'],
                'form_data' => $filteredFormData,
                'category_id' => $validatedData['category_id'],
                'major_id' => $validatedData['major_id'] ?? null,
            ]);
            return response()->json([
                'message' => 'Issue updated successfully',
                'icon' => 'success',
                'redirect' => route('admin.issues.show', $issue->id)
            ], 200);
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Issue $issue)
    {
        //
    }
    public function showReassignForm(Issue $issue)
    {
        // فحص الصلاحية عبر الـ Policy
        Gate::authorize('reassign', $issue);

        $user = Auth::user();

        // جلب قائمة المستلمين المتاحين بناءً على دور المستخدم الحالي
        $assignableUsers = User::query()
            ->where('id', '!=', $user->id) // استثناء المستخدم الحالي
            ->when($user->hasRole('Department Head'), function ($q) use ($issue) {
                // رئيس القسم يحول للعميد الخاص بالكلية/العمادة أو الأدمن
                $deanshipId = $issue->major?->department?->deanship_id
                    ?? $issue->user?->student?->major?->department?->deanship_id;

                $q->whereHas('roles', function ($roleQuery) use ($deanshipId) {
                    if ($deanshipId) {
                        $roleQuery->whereIn('name', ['Admin', 'Sub Admin', 'Super Admin', 'Dean'])
                            ->whereHas('users.employee.department', function ($deptQuery) use ($deanshipId) {
                                $deptQuery->where('deanship_id', $deanshipId);
                            });
                    } else {
                        $roleQuery->whereIn('name', ['Admin', 'Sub Admin', 'Super Admin', 'Dean']);
                    }
                });
            })
            ->when($user->hasRole('Dean'), function ($q) {
                // العميد يحول لرؤساء الأقسام أو الأدمن
                $q->whereHas('roles', function ($roleQuery) {
                    $roleQuery->whereIn('name', ['Admin', 'Sub Admin', 'Super Admin', 'Department Head']);
                });
            })
            ->when($user->hasRole('Instructor'), function ($q) use ($user) {
                // جلب القسم الخاص بالمحاضر الحالي
                $instructorDepartmentId = $user->employee?->department_id;

                $q->where(function ($subQuery) use ($instructorDepartmentId) {
                    // 1. جلب الأدمنز (بدون تقييد بالقسم)
                    $subQuery->whereHas('roles', function ($roleQuery) {
                        $roleQuery->whereIn('name', ['Admin', 'Sub Admin', 'Super Admin']);
                    });

                    // 2. جلب رئيس القسم الخاص بقسم المحاضر فقط
                    if ($instructorDepartmentId) {
                        $subQuery->orWhere(function ($deptHeadQuery) use ($instructorDepartmentId) {
                            $deptHeadQuery->whereHas('roles', function ($roleQuery) {
                                $roleQuery->where('name', 'Department Head');
                            })->whereHas('users.employee', function ($empQuery) use ($instructorDepartmentId) {
                                $empQuery->where('department_id', $instructorDepartmentId);
                            });
                        });
                    }
                });
            })
            ->when(!$user->hasAnyRole(['Department Head', 'Dean', 'Instructor', 'Admin', 'Sub Admin', 'Super Admin']), function ($q) {
                // المستخدمون الآخرون يمكنهم تحويل لأي مستخدم ادمن او ادمن فرعي وهم يقوموا بالتصرف
                $q->whereHas('roles', function ($roleQuery) {
                    $roleQuery->whereIn('name', ['Admin', 'Sub Admin']);
                });
            }) // الadmin, Sub Admin, Super Admin يمكنهم تحويل لأي مستخدم
            ->with('roles')
            ->get();

        return view('cms.issues.reassign', compact('issue', 'assignableUsers'));
    }
    public function reassign(Request $request, Issue $issue)
    {
        $validatedData = $request->validate([
            'assigned_to' => 'required|exists:users,id',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        // فحص الصلاحيات مع تمرير الشخص المستهدف
        Gate::authorize('reassign', [$issue, (int) $validatedData['assigned_to']]);

        // 1. تحديث بيانات القضية
        return DB::transaction(function () use ($issue, $validatedData) {
            $issue->update([
                'assigned_to' => $validatedData['assigned_to'],
                'action_by' => Auth::id(),
                'admin_notes' => $validatedData['admin_notes'] ?? null,
                'status' => 'under_review',
            ]);

            // تسجيل العملية في جدول الـ assignments التاريخي
            $issue->assignments()->create([
                'assigned_to' => $validatedData['assigned_to'],
                'action_by' => Auth::id(),
                'status' => 'under_review',
                'admin_notes' => $validatedData['admin_notes'] ?? null,
            ]);
            return response()->json([
                'message' => 'Issue reassigned successfully',
                'icon' => 'success',
                'redirect' => route('admin.issues.show', $issue->id),
            ], 200);
        });
    }

    public function approve(Issue $issue)
    {
        Gate::authorize('approve', $issue);
        return DB::transaction(function () use ($issue) {
            $issue->update(['status' => 'approved', 'action_by' => Auth::id()]);
            $issue->assignments()->create([
                'assigned_to' => $issue->assigned_to,
                'action_by' => Auth::id(),
                'status' => 'approved',
            ]);
            return response()->json([
                'message' => 'Issue approved successfully',
                'icon' => 'success',
            ], 200);
        });
    }
    public function reject(Request $request, Issue $issue)
    {
        Gate::authorize('reject', $issue);
        $validatedData = $request->validate([
            'rejection_reason' => 'required|string',
        ]);
        return DB::transaction(function () use ($issue, $validatedData) {
            $issue->update(['status' => 'rejected', 'rejection_reason' => $validatedData['rejection_reason'], 'action_by' => Auth::id()]);
            $issue->assignments()->create([
                'assigned_to' => $issue->assigned_to,
                'action_by' => Auth::id(),
                'status' => 'rejected',
                'rejection_reason' => $validatedData['rejection_reason'] ?? null,
            ]);
            return response()->json([
                'message' => 'Issue rejected successfully',
                'icon' => 'success',
            ], 200);
        });
    }
    public function close(Issue $issue)
    {
        Gate::authorize('close', $issue);
        return DB::transaction(function () use ($issue) {
            $issue->update(['status' => 'closed', 'action_by' => Auth::id()]);
            $issue->assignments()->create([
                'assigned_to' => $issue->assigned_to,
                'action_by' => Auth::id(),
                'status' => 'closed',
            ]);
            return response()->json([
                'message' => 'Issue closed successfully',
                'icon' => 'success',
            ], 200);
        });
    }
    public function storeComment(Request $request, Issue $issue)
    {
        // التحقق من أن المستخدم يمتلك صلاحية رؤية الطلب للتعليق عليه
        Gate::authorize('view', $issue);

        $validated = $request->validate([
            'comment' => 'required|string|max:2000',
        ]);

        $comment = $issue->comments()->create([
            'user_id' => Auth::id(),
            'comment' => $validated['comment'],
        ]);

        // تحميل بيانات المستخدم للرد الفوري مع AJAX
        $comment->load('user');

        return response()->json([
            'message' => 'Comment added successfully',
            'icon' => 'success',
            'comment' => $comment,
        ], 201);
    }
}