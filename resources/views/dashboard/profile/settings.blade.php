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
                        <b>Followers</b> <a class="float-right">1,322</a>
                    </li>
                    <li class="list-group-item">
                        <b>Following</b> <a class="float-right">543</a>
                    </li>
                    <li class="list-group-item">
                        <b>Friends</b> <a class="float-right">13,287</a>
                    </li>
                    </ul>

                    <a href="#" class="btn btn-primary btn-block"><b>Follow</b></a>
                </div>
            </div>
            <br>
            <div class="ibox-content">          
                <div>
                    <strong><i class="fas fa-book mr-1"></i> Education</strong>

                    <p class="text-muted">
                        B.S. in Computer Science from the University of Tennessee at Knoxville
                    </p>

                    <hr>

                    <strong><i class="fas fa-map-marker-alt mr-1"></i> Location</strong>

                    <p class="text-muted">Malibu, California</p>

                    <hr>

                    <strong><i class="fas fa-pencil-alt mr-1"></i> Skills</strong>

                    <p class="text-muted">
                        <span class="tag tag-danger">UI Design</span>
                        <span class="tag tag-success">Coding</span>
                        <span class="tag tag-info">Javascript</span>
                        <span class="tag tag-warning">PHP</span>
                        <span class="tag tag-primary">Node.js</span>
                    </p>

                    <hr>

                    <strong><i class="far fa-file-alt mr-1"></i> Notes</strong>

                    <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam fermentum enim neque.</p>
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
