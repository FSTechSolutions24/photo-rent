@csrf


<div class="mb-3">
    <label class="form-label">Appointment Name: <span class="required_start">*</span></label>
    <input type="text" name="name" value="{{ old('name', $appointment->name ?? '') }}" class="input form-control" required autocomplete="false">
    @error('name')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Appointment Description:</label>
    <textarea name="description" class="input form-control" autocomplete="false">{{ old('description', $appointment->description ?? '') }}</textarea>
    @error('description')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Session:</label>
    <input type="text" name="session_id" value="{{ old('session_id', $appointment->session_id ?? '') }}" class="input form-control" autocomplete="false">
    @error('session_id')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Date: <span class="required_start">*</span></label>
    <input type="text" name="date" value="{{ old('date', $appointment->date ?? '') }}" class="input form-control" autocomplete="false">
    @error('date')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>


<div class="row">
    <div class="mb-3 col-md-6">
        <label class="form-label">Start Time: <span class="required_start">*</span></label>
        <input type="text" name="start_time" value="{{ old('start_time', $appointment->start_time ?? '') }}" class="input form-control" autocomplete="false">
        @error('start_time')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    
    <div class="mb-3 col-md-6">
        <label class="form-label">End Time:</label>
        <input type="text" name="end_time" value="{{ old('end_time', $appointment->end_time ?? '') }}" class="input form-control" autocomplete="false">
        @error('end_time')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>
    
