@csrf


<div class="mb-3">
    <label class="form-label">Plan Name: <span class="required_start">*</span></label>
    <input type="text" name="name" value="{{ old('name', $plan->name ?? '') }}" class="input form-control" required autocomplete="false">
    @error('name')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Plan Price: <span class="required_start">*</span></label>
    <input type="text" name="price" value="{{ old('price', $plan->price ?? '') }}" class="input form-control" required autocomplete="false">
    @error('price')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>