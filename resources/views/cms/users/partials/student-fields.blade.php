<div class="col-md-4">
    <label for="id_number" class="form-label">ID Number</label>
    <input type="text" class="form-control" id="id_number" name="id_number"
        value="{{ $user->student->id_number ?? '' }}" required />
    <div class="invalid-feedback"></div>
</div>
<div class="col-md-4">
    <label for="student_number" class="form-label">Student Number</label>
    <input type="text" class="form-control" id="student_number" name="student_number"
        value="{{ $user->student->student_number ?? '' }}" required />
    <div class="invalid-feedback"></div>
</div>
<div class="col-md-4">
    <label for="major_id" class="form-label">Departments</label>
    <select class="form-select" id="major_id" name="major_id">
        <option selected disabled value="">Choose&hellip;</option>
        @foreach ($majors as $major)
            <option value="{{ $major->id }}"
                {{ ($user->student->major_id ?? '') === $major->id ? 'selected' : '' }}>
                {{ $major->trans_name }}
            </option>
        @endforeach
    </select>
    <div class="invalid-feedback"></div>
</div>
