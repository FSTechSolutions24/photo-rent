@extends('adminlte::page')
@section('title', 'Create Plan')
@section('content')
<x-page-header title="Create Plan" description="Define a new subscription plan, its pricing, storage, and included features."
    :breadcrumbs="[['label' => 'Administration'], ['label' => 'Plans', 'url' => route('superadmin.plans.index')], ['label' => 'Create Plan']]"
    :action-url="route('superadmin.plans.index')" action-label="View plans" action-icon="fas fa-list" />
<form method="POST" action="{{ route('superadmin.plans.store') }}" enctype="multipart/form-data">

    <div class="ibox-content">
        @include('superadmin.plans._form')  
        <button class="btn btn-primary mt-3">Submit</button>          
    </div>

</form>
@stop
