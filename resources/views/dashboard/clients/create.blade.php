@extends('adminlte::page')

@section('content')

<form method="POST" action="{{ route('dashboard.clients.store') }}" enctype="multipart/form-data">
    @csrf

    <div class="card">

        <div class="card-body">
            <div>
                <label>Client Name: <span class="required_start">*</span></label>
                <input type="text" name="name" class="input form-control" required autocomplete="false">
            </div>
        
            <div>
                <label>Client Phone: <span class="required_start">*</span></label>
                <input type="text" name="phone" class="input form-control" required autocomplete="false">
            </div>
        
            <div>
                <label>Client Phone2:</label>
                <input type="text" name="phone2" class="input form-control" autocomplete="false">
            </div>
        
            <div>
                <label>Client Email:</label>
                <input type="text" name="email" class="input form-control" autocomplete="false">
            </div>
        
            <button class="btn btn-primary mt-3">Create Client</button>
        </div>
    </div>
</form>

@stop