@extends('adminlte::page')

@section('content')

<h4 class="page_header">Edit Plan</h4>

<form method="POST" action="{{ route('superadmin.plans.update', $plan->id) }}" enctype="multipart/form-data">

    @method('PUT')
    <div class="ibox-content">
        @include('superadmin.plans._form')
        <button class="btn btn-primary mt-3">Update</button>
    </div>
</form>

@stop