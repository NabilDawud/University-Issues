<?php

namespace App\Policies;

use App\Models\Issue;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class IssuePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if ($user->can('Index Issue')) {
            return true;
        }
        if ($user->isAdmin()) {
            return true;
        }

        return $user->hasAnyRole(['Department Head', 'Dean', 'Instructor', 'Assistant']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Issue $issue): bool
    {
        if (!$user->can('Show Issue')) {
            return false;
        }
        return $user->isAdmin() || $issue->user_id === $user->id || $issue->assigned_to === $user->id || $issue->action_by === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('Create Issue') || $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Issue $issue): bool
    {
        if ($user->isAdmin()) {
            return true; // الأدمن يمكنه تعديل أي طلب
        }
        if (!$user->can('Edit Issue')) {
            return false;
        }
        if ($issue->status !== 'pending') {
            return false; // لا يمكن التعديل على الطلبات التي تم الموافقة عليها أو رفضها أو إغلاقها
        }
        if ($issue->assigned_to === $user->id || $issue->action_by === $user->id) {
            return false; // لا يمكن تعديل الطلب إذا كان المستخدم هو المحال إليه أو مقتضي الإجراء النهائي
        }
        return $issue->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Issue $issue): bool
    {
        if ($user->isAdmin()) {
            return true; // الأدمن يمكنه حذف أي طلب
        }
        if(!$user->can('Delete Issue')) {
            return false;
        }
        if($issue->status !== 'pending') {
            return false; // لا يمكن حذف الطلبات التي تم الموافقة عليها أو رفضها أو إغلاقها
        }
        if ($issue->assigned_to === $user->id || $issue->action_by === $user->id) {
            return false; // لا يمكن حذف الطلب إذا كان المستخدم هو المحال إليه أو مقتضي الإجراء النهائي
        }
        return $issue->user_id === $user->id; // يمكن للمستخدم حذف طلباته الخاصة فقط
    }

    public function reassign(User $user, Issue $issue, ?int $targetUserId = null): bool
    {
        // 1. يجب أن تكون المشكلة موجهة للمستخدم الحالي (assigned_to) أو أن يكون المستخدم أدمن
        if (!$user->isAdmin() && $issue->assigned_to !== $user->id) {
            return false;
        }

        // 2. إذا كان الفحص مبدئياً فقط لعرض الشاشة (showReassignForm)
        if ($targetUserId === null) {
            return true;
        }

        // 3. الأدمن يستطيع تحويل المشكلة لأي مستخدم في النظام
        if ($user->isAdmin()) {
            return true;
        }

        // ----------------------------------------------------
        // منطق رئيس القسم (Department Head)
        // ----------------------------------------------------
        if ($user->hasRole('Department Head')) {
            $deanshipId = $issue->major?->department?->deanship_id
                ?? $issue->user?->studentProfile?->department?->deanship_id;

            if ($deanshipId) {
                $deanId = User::role('Dean')
                    ->whereHas('employee.department', function ($q) use ($deanshipId) {
                        $q->where('deanship_id', $deanshipId);
                    })->value('id');

                if ($targetUserId === $deanId) {
                    return true;
                }
            }

            return User::where('id', $targetUserId)->whereHas('roles', function ($q) {
                $q->whereIn('name', ['Admin', 'Sub Admin', 'Super Admin', 'Dean']);
            })->exists();
        }

        // ----------------------------------------------------
        // منطق العميد (Dean)
        // ----------------------------------------------------
        if ($user->hasRole('Dean')) {
            return User::where('id', $targetUserId)->whereHas('roles', function ($q) {
                $q->whereIn('name', ['Admin', 'Sub Admin', 'Super Admin', 'Department Head']);
            })->exists();
        }

        // ----------------------------------------------------
        // منطق المحاضر (Instructor)
        // ----------------------------------------------------
        if ($user->hasRole('Instructor')) {
            $instructorDepartmentId = $user->employee?->department_id;

            return User::where('id', $targetUserId)
                ->where(function ($query) use ($instructorDepartmentId) {
                    // تحويل للأدمنز
                    $query->whereHas('roles', function ($q) {
                        $q->whereIn('name', ['Admin', 'Sub Admin', 'Super Admin']);
                    });

                    // أو تحويل لرئيس قسم المحاضر نفسه
                    if ($instructorDepartmentId) {
                        $query->orWhere(function ($deptHeadQuery) use ($instructorDepartmentId) {
                            $deptHeadQuery->whereHas('roles', function ($q) {
                                $q->where('name', 'Department Head');
                            })->whereHas('employee', function ($empQuery) use ($instructorDepartmentId) {
                                $empQuery->where('department_id', $instructorDepartmentId);
                            });
                        });
                    }
                })->exists();
        }

        // ----------------------------------------------------
        // أي دور آخر (لا ينتمي للأدوار أعلاه): يسمح بالتحويل للأدمن فقط
        // ----------------------------------------------------
        return User::where('id', $targetUserId)
            ->whereHas('roles', function ($q) {
                $q->whereIn('name', ['Admin', 'Sub Admin']);
            })->exists();
    }
    public function approve(User $user, Issue $issue): bool
    {
        // لا يمكن الموافقة على طلب مغلق أو مبتوت فيه مسبقاً
        if (in_array($issue->status, ['approved', 'rejected', 'closed'])) {
            return false;
        }

        // الصلاحية للمسؤول الحالي المُحال إليه الطلب أو الأدمن
        return $user->isAdmin() || $issue->assigned_to === $user->id;
    }
    public function reject(User $user, Issue $issue): bool
    {
        if (in_array($issue->status, ['approved', 'rejected', 'closed'])) {
            return false;
        }

        return $user->isAdmin() || $issue->assigned_to === $user->id;
    }
    public function close(User $user, Issue $issue): bool
    {
        // الإغلاق غالباً متاح للأدمن أو الشخص المحال إليه الطلب
        return $user->isAdmin() || $issue->assigned_to === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Issue $issue): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Issue $issue): bool
    {
        return false;
    }
}
