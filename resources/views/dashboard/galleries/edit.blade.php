@extends('adminlte::page')
@section('title', 'Update Gallery')

@section('content')
    <x-page-header title="Update Gallery" description="Adjust gallery details, access settings, and presentation options."
        :breadcrumbs="[['label' => 'Galleries', 'url' => route('dashboard.galleries.index')], ['label' => 'Update Gallery']]"
        :action-url="route('dashboard.galleries.index')" action-label="View galleries" action-icon="fas fa-images" />
    <form method="POST" action="{{ route('dashboard.galleries.update', $gallery->id) }}" enctype="multipart/form-data">
        @method('PUT')
        <div class="ibox-content">
            @include('dashboard.galleries._form')
            <button class="btn btn-primary mt-3">Update</button>
        </div>
    </form>
@stop
