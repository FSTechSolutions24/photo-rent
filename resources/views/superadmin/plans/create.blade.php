@extends('adminlte::page')
@section('content')
<h4 class="page_header">Create Plan</h4>



<form method="POST" action="{{ route('superadmin.plans.store') }}" enctype="multipart/form-data">

    <div class="ibox-content">
        @include('superadmin.plans._form')  
        <button class="btn btn-primary mt-3">Submit</button>          
    </div>

</form>
@stop
