@extends('adminlte::page')

@section('content')

<form method="POST" action="{{ route('dashboard.clients.store') }}" enctype="multipart/form-data">
    @csrf

    <h4 class="page_header">Add Client</h4>
    <div class="card">

        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">Client Name: <span class="required_start">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" class="input form-control" required autocomplete="false">
                @error('name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
        
            <div class="mb-3">
                <label class="form-label">Client Phone: <span class="required_start">*</span></label>
                <input type="text" name="phone" value="{{ old('phone') }}" class="input form-control" required autocomplete="false">
                @error('phone')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
        
            <div class="mb-3">
                <label class="form-label">Client Phone2:</label>
                <input type="text" name="phone2" value="{{ old('phone2') }}" class="input form-control" autocomplete="false">
                @error('phone2')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
        
            <div class="mb-3">
                <label class="form-label">Client Email:</label>
                <input type="text" name="email" value="{{ old('email') }}" class="input form-control" autocomplete="false">
                @error('email')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
        
            <button class="btn btn-primary mt-3">Create Client</button>
        </div>
    </div>
</form>

@stop