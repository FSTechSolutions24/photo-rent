@extends('adminlte::page')
@section('content')
<h4 class="page_header" style="padding-left: 7.5px;">Profile</h4>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">
            <div class="ibox-content">
                <div class="">
                    <div class="text-center">
                    <img class="profile-user-img img-fluid img-circle"
                        src="{{ Auth::user()->avatar ?? asset('vendor/adminlte/dist/img/AdminLTELogo.png') }}"
                        alt="User profile picture">
                    </div>

                    <h3 class="profile-username text-center">{{ $user->name ?? '' }}</h3>

                    <p class="text-muted text-center">Software Engineer</p>

                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Clients</b> <a class="float-right">{{ $data['client_count'] }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Galleries</b> <a class="float-right">{{ $data['gallery_count'] }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Sessions</b> <a class="float-right">{{ $data['session_count'] }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Storage</b> <a class="float-right">{{ $data['available_storage'] }} / {{ $data['plan_storage'] }}</a>
                        </li>
                    </ul>

                    <a href="#" class="btn btn-primary btn-block"><b>Follow</b></a>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="ibox-content">

                <form method="POST" action="{{ route('photographer.profile.update') }}" enctype="multipart/form-data" autocomplete="off">
                    @method('PUT')
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Name: <span class="required_start">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" class="input form-control" autocomplete="username">
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email: </label>
                        <input type="text" name="email" value="{{ old('email', $user->email ?? '') }}" class="input form-control" required autocomplete="false">
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password:</label>
                        <input type="password" name="password" class="input form-control" autocomplete="new-password">                       
                        @error('password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password Confirm:</label>
                        <input type="password" name="password_confirmation" class="input form-control" autocomplete="new-password">                        
                        @error('password_confirmation')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
    
                    <button class="btn btn-primary mt-3">Update</button>
                </form>

            </div>
        </div>
    </div>
</div>
</form>
@stop
