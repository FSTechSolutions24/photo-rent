@extends('adminlte::page')
@section('title', 'Edit Plan')

@section('content')

<x-page-header title="Edit Plan" description="Update this subscription plan’s pricing, storage, and included features."
    :breadcrumbs="[['label' => 'Administration'], ['label' => 'Plans', 'url' => route('superadmin.plans.index')], ['label' => 'Edit Plan']]"
    :action-url="route('superadmin.plans.index')" action-label="View plans" action-icon="fas fa-list" />

<form method="POST" action="{{ route('superadmin.plans.update', $plan->id) }}" enctype="multipart/form-data">

    @method('PUT')
    <div class="ibox-content">
        @include('superadmin.plans._form')
        <button class="btn btn-primary mt-3">Update</button>
    </div>
</form>

@stop
