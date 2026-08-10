<div class="col-md-4">
    <label for="employee_number" class="form-label">Employee Number</label>
    <input type="text" class="form-control" id="employee_number" name="employee_number"
        value="{{ $employee->employee->employee_number ?? '' }}" required />
    <div class="invalid-feedback"></div>
</div>
<div class="col-md-4">
    <label for="office_number" class="form-label">Office Number</label>
    <input type="text" class="form-control" id="office_number" name="office_number"
        value="{{ $employee->employee->office_number ?? '' }}" required />
    <div class="invalid-feedback"></div>
</div>
<div class="col-md-4">
    <label for="employee_type" class="form-label">Employee Type</label>
    <select class="form-select" id="employee_type" name="employee_type">
        <option selected disabled value="">Choose&hellip;</option>
        <option value="instructor" {{ ($employee->employee->employee_type ?? '') === 'instructor' ? 'selected' : '' }}>
            Instructor
        </option>
        <option value="dean_head" {{ ($employee->employee->employee_type ?? '') === 'dean_head' ? 'selected' : '' }}>
            Dean/Head
        </option>
        <option value="department_head"
            {{ ($employee->employee->employee_type ?? '') === 'department_head' ? 'selected' : '' }}>
            Department Head
        </option>
        <option value="assistant" {{ ($employee->employee->employee_type ?? '') === 'assistant' ? 'selected' : '' }}>
            Assistant
        </option>
    </select>
    <div class="invalid-feedback"></div>
</div>
<div class="col-md-4">
    <label for="department_id" class="form-label">Departments</label>
    <select class="form-select" id="department_id" name="department_id">
        <option selected disabled value="">Choose&hellip;</option>
        @foreach ($departments as $department)
            <option value="{{ $department->id }}"
                {{ ($employee->employee->department_id ?? '') === $department->id ? 'selected' : '' }}>
                {{ $department->trans_name }}
            </option>
        @endforeach
    </select>
    <div class="invalid-feedback"></div>
</div>
