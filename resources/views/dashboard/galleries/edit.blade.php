@extends('adminlte::page')
@section('title', 'Update Gallery')

@section('content')
    <h4 class="page_header">Update Gallery</h4>
    <form method="POST" action="{{ route('dashboard.galleries.update', $gallery->id) }}" enctype="multipart/form-data">
        @method('PUT')
        <div class="ibox-content">
            @include('dashboard.galleries._form')
            <button class="btn btn-primary mt-3">Update</button>
        </div>
    </form>

    <div class="ibox-content mt-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
            <div>
                <h5 class="mb-1">Face groups</h5>
                <p class="text-muted mb-0">
                    Status: <strong>{{ str_replace('_', ' ', ucfirst($gallery->face_processing_status ?? 'disabled')) }}</strong>
                    @if($gallery->faces_clustered_at) · Last grouped {{ $gallery->faces_clustered_at->diffForHumans() }} @endif
                </p>
            </div>
            <form method="POST" action="{{ route('dashboard.galleries.faces.process', $gallery) }}" class="mt-2 mt-sm-0">
                @csrf
                <button class="btn btn-info" type="submit" {{ $gallery->face_processing_enabled ? '' : 'disabled' }}>
                    <i class="fas fa-sync-alt mr-1"></i> Process gallery faces
                </button>
            </form>
        </div>

        @error('faces')<div class="alert alert-warning">{{ $message }}</div>@enderror
        @if($gallery->face_processing_error)<div class="alert alert-warning">{{ $gallery->face_processing_error }}</div>@endif

        @if($gallery->faceClusters->isNotEmpty())
            <div class="d-flex flex-wrap mb-3" style="gap:.5rem">
                <form method="POST" action="{{ route('dashboard.galleries.faces.visibility-all', $gallery) }}">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="visible">
                    <button class="btn btn-sm btn-outline-success" type="submit"><i class="fas fa-eye mr-1"></i> Show all groups</button>
                </form>
                <form method="POST" action="{{ route('dashboard.galleries.faces.visibility-all', $gallery) }}">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="hidden">
                    <button class="btn btn-sm btn-outline-secondary" type="submit"><i class="fas fa-eye-slash mr-1"></i> Hide all groups</button>
                </form>
            </div>
            <div class="row">
                @foreach($gallery->faceClusters as $cluster)
                    <div class="col-6 col-sm-4 col-md-3 col-lg-2 mb-3">
                        <div class="card h-100 text-center {{ $cluster->status === 'visible' ? 'border-success' : 'border-secondary' }}">
                            <img class="card-img-top" src="{{ route('dashboard.galleries.faces.thumbnail', [$gallery, $cluster]) }}" alt="Anonymous face group" style="aspect-ratio:1;object-fit:cover">
                            <div class="card-body p-2">
                                <div class="small mb-2">{{ $cluster->face_count }} {{ Str::plural('face', $cluster->face_count) }}</div>
                                <form method="POST" action="{{ route('dashboard.galleries.faces.visibility', [$gallery, $cluster]) }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="{{ $cluster->status === 'visible' ? 'hidden' : 'visible' }}">
                                    <button class="btn btn-sm {{ $cluster->status === 'visible' ? 'btn-outline-secondary' : 'btn-outline-success' }}" type="submit">
                                        {{ $cluster->status === 'visible' ? 'Hide' : 'Show' }}
                                    </button>
                                </form>
                                <div class="text-muted mt-1" style="font-size:.7rem">{{ ucfirst($cluster->status) }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-light border mb-0">No face groups yet. Enable processing, save, and process the gallery.</div>
        @endif
    </div>
@stop
