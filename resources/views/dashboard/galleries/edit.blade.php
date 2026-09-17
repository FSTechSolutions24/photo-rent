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

    @php
        $faceStatus = $gallery->face_processing_status ?? 'disabled';
        $faceStatusLabel = str_replace('_', ' ', ucfirst($faceStatus));
        $faceStatusTone = in_array($faceStatus, ['ready', 'completed'], true)
            ? 'success'
            : (in_array($faceStatus, ['failed', 'partial_failure', 'unavailable'], true) ? 'danger' : 'neutral');
        $visibleFaceGroups = $gallery->faceClusters->where('status', 'visible')->count();
    @endphp

    <div class="ibox-content mt-4 face-groups-panel">
        <div class="face-groups-header">
            <div class="face-groups-heading">
                <span class="face-groups-heading__icon"><i class="fas fa-user-friends"></i></span>
                <div>
                    <div class="face-groups-heading__title">
                        <h5>Face groups</h5>
                        <span class="face-status face-status--{{ $faceStatusTone }}">
                            <i class="fas fa-circle"></i>{{ $faceStatusLabel }}
                        </span>
                    </div>
                    <p>
                        Manage which people appear in the public gallery filter.
                        @if($gallery->faces_clustered_at)<span>Last grouped {{ $gallery->faces_clustered_at->diffForHumans() }}.</span>@endif
                    </p>
                </div>
            </div>
            <form method="POST" action="{{ route('dashboard.galleries.faces.process', $gallery) }}">
                @csrf
                <button class="face-process-button" type="submit" {{ $gallery->face_processing_enabled ? '' : 'disabled' }}>
                    <i class="fas fa-sync-alt"></i>
                    <span>Process gallery faces</span>
                </button>
            </form>
        </div>

        @error('faces')<div class="alert alert-warning">{{ $message }}</div>@enderror
        @if($gallery->face_processing_error)<div class="alert alert-warning">{{ $gallery->face_processing_error }}</div>@endif

        @if($gallery->faceClusters->isNotEmpty())
            <div class="face-groups-toolbar">
                <div class="face-groups-summary">
                    <strong>{{ $gallery->faceClusters->count() }}</strong>
                    <span>{{ Str::plural('person', $gallery->faceClusters->count()) }} detected</span>
                    <i></i>
                    <strong>{{ $visibleFaceGroups }}</strong>
                    <span>shown publicly</span>
                </div>
                <div class="face-groups-bulk-actions">
                    <form method="POST" action="{{ route('dashboard.galleries.faces.visibility-all', $gallery) }}">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="visible">
                        <button class="face-bulk-button face-bulk-button--show" type="submit">
                            <i class="fas fa-eye"></i><span>Show all</span>
                        </button>
                    </form>
                    <form method="POST" action="{{ route('dashboard.galleries.faces.visibility-all', $gallery) }}">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="hidden">
                        <button class="face-bulk-button face-bulk-button--hide" type="submit">
                            <i class="fas fa-eye-slash"></i><span>Hide all</span>
                        </button>
                    </form>
                </div>
            </div>

            <div class="face-groups-grid">
                @foreach($gallery->faceClusters as $cluster)
                    @php($clusterIsVisible = $cluster->status === 'visible')
                    <article class="face-group-card {{ $clusterIsVisible ? 'is-visible' : 'is-hidden' }}">
                        <div class="face-group-card__image">
                            <img src="{{ route('dashboard.galleries.faces.thumbnail', [$gallery, $cluster]) }}" alt="Anonymous face group" loading="lazy">
                            <span class="face-group-card__badge">
                                <i class="fas {{ $clusterIsVisible ? 'fa-eye' : 'fa-eye-slash' }}"></i>
                                {{ $clusterIsVisible ? 'Shown' : 'Hidden' }}
                            </span>
                        </div>
                        <div class="face-group-card__body">
                            <div class="face-group-card__meta">
                                <strong>{{ $cluster->face_count }}</strong>
                                <span>{{ Str::plural('photo match', $cluster->face_count) }}</span>
                            </div>
                            <form method="POST" action="{{ route('dashboard.galleries.faces.visibility', [$gallery, $cluster]) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="{{ $clusterIsVisible ? 'hidden' : 'visible' }}">
                                <button class="face-visibility-button {{ $clusterIsVisible ? 'face-visibility-button--hide' : 'face-visibility-button--show' }}" type="submit">
                                    <i class="fas {{ $clusterIsVisible ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                                    <span>{{ $clusterIsVisible ? 'Hide from gallery' : 'Show in gallery' }}</span>
                                </button>
                            </form>
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <div class="face-groups-empty">
                <span><i class="far fa-user-circle"></i></span>
                <h6>No face groups yet</h6>
                <p>Enable face processing, save the gallery, then process its photos to find people.</p>
            </div>
        @endif
    </div>
@stop

@section('css')
    <style>
        .face-groups-panel { overflow:hidden; padding:0 !important; }
        .face-groups-header { display:flex; align-items:center; justify-content:space-between; gap:1.25rem; padding:1.5rem 1.65rem; border-bottom:1px solid #e8edf3; background:linear-gradient(135deg,#fff 45%,#f4f9ff); }
        .face-groups-heading { display:flex; min-width:0; align-items:center; gap:1rem; }
        .face-groups-heading__icon { display:grid; width:46px; height:46px; flex:0 0 46px; place-items:center; border-radius:14px; background:linear-gradient(135deg,#1476d4,#15a8b8); color:#fff; font-size:1.05rem; box-shadow:0 8px 20px rgba(20,118,212,.2); }
        .face-groups-heading__title { display:flex; align-items:center; flex-wrap:wrap; gap:.65rem; }
        .face-groups-heading h5 { margin:0; color:#18324d; font-size:1.08rem; font-weight:700; }
        .face-groups-heading p { margin:.3rem 0 0; color:#748396; font-size:.82rem; }
        .face-groups-heading p span { margin-left:.25rem; }
        .face-status { display:inline-flex; align-items:center; gap:.38rem; padding:.28rem .58rem; border-radius:999px; font-size:.67rem; font-weight:700; line-height:1; text-transform:capitalize; }
        .face-status i { font-size:.38rem; }
        .face-status--success { background:#e9f9f0; color:#16864b; }
        .face-status--danger { background:#fff0f1; color:#c83d4e; }
        .face-status--neutral { background:#eef3f8; color:#607187; }
        .face-process-button { display:inline-flex; min-height:42px; align-items:center; justify-content:center; gap:.55rem; padding:.65rem 1rem; border:0; border-radius:11px; background:linear-gradient(135deg,#148ac1,#16a6b4); color:#fff; font-size:.8rem; font-weight:700; cursor:pointer; box-shadow:0 7px 18px rgba(20,138,193,.22); transition:.2s ease; }
        .face-process-button:hover { color:#fff; filter:brightness(1.04); transform:translateY(-1px); box-shadow:0 10px 22px rgba(20,138,193,.28); }
        .face-process-button:disabled { cursor:not-allowed; opacity:.48; box-shadow:none; transform:none; }
        .face-groups-panel>.alert { margin:1.25rem 1.65rem 0; }
        .face-groups-toolbar { display:flex; align-items:center; justify-content:space-between; gap:1rem; padding:1rem 1.65rem; border-bottom:1px solid #edf1f5; background:#fbfcfe; }
        .face-groups-summary { display:flex; align-items:baseline; flex-wrap:wrap; gap:.32rem; color:#78879a; font-size:.75rem; }
        .face-groups-summary strong { color:#253b53; font-size:.95rem; }
        .face-groups-summary i { width:3px; height:3px; margin:0 .32rem; border-radius:50%; background:#aeb8c4; }
        .face-groups-bulk-actions { display:flex; align-items:center; gap:.5rem; }
        .face-groups-bulk-actions form { margin:0; }
        .face-bulk-button { display:inline-flex; height:35px; align-items:center; gap:.42rem; padding:0 .78rem; border:1px solid; border-radius:9px; background:#fff; font-size:.72rem; font-weight:700; cursor:pointer; transition:.18s ease; }
        .face-bulk-button--show { border-color:#b9e8cc; color:#17844b; }
        .face-bulk-button--show:hover { border-color:#32ad6b; background:#eaf9f1; }
        .face-bulk-button--hide { border-color:#d8e0e8; color:#667587; }
        .face-bulk-button--hide:hover { border-color:#9da9b7; background:#f0f3f6; color:#3f4d5e; }
        .face-groups-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(170px,1fr)); gap:1rem; padding:1.35rem 1.65rem 1.65rem; }
        .face-group-card { min-width:0; overflow:hidden; border:1px solid #e1e7ed; border-radius:14px; background:#fff; box-shadow:0 4px 14px rgba(31,53,78,.07); transition:border-color .2s ease,box-shadow .2s ease,transform .2s ease; }
        .face-group-card:hover { border-color:#c5d5e5; transform:translateY(-2px); box-shadow:0 10px 24px rgba(31,53,78,.12); }
        .face-group-card__image { position:relative; overflow:hidden; aspect-ratio:1/1; background:#e9eef3; }
        .face-group-card__image:after { position:absolute; inset:auto 0 0; height:38%; content:''; background:linear-gradient(transparent,rgba(11,22,34,.45)); pointer-events:none; }
        .face-group-card__image img { width:100%; height:100%; display:block; object-fit:cover; transition:transform .35s ease; }
        .face-group-card:hover .face-group-card__image img { transform:scale(1.025); }
        .face-group-card.is-hidden .face-group-card__image img { filter:saturate(.55); opacity:.82; }
        .face-group-card__badge { position:absolute; right:.65rem; bottom:.62rem; z-index:1; display:inline-flex; align-items:center; gap:.32rem; padding:.28rem .5rem; border:1px solid rgba(255,255,255,.28); border-radius:999px; background:rgba(17,30,43,.72); color:#fff; font-size:.64rem; font-weight:700; backdrop-filter:blur(7px); }
        .face-group-card.is-visible .face-group-card__badge i { color:#75e6a6; }
        .face-group-card.is-hidden .face-group-card__badge i { color:#cbd3dc; }
        .face-group-card__body { padding:.8rem; }
        .face-group-card__meta { display:flex; align-items:baseline; gap:.3rem; margin-bottom:.68rem; }
        .face-group-card__meta strong { color:#20374f; font-size:.92rem; }
        .face-group-card__meta span { color:#7d8b9b; font-size:.7rem; }
        .face-group-card__body form { margin:0; }
        .face-visibility-button { display:flex; width:100%; min-height:36px; align-items:center; justify-content:center; gap:.45rem; padding:.45rem .6rem; border:1px solid; border-radius:9px; font-size:.7rem; font-weight:700; cursor:pointer; transition:.18s ease; }
        .face-visibility-button--hide { border-color:#dce3ea; background:#f7f9fb; color:#5e6e80; }
        .face-visibility-button--hide:hover { border-color:#bcc8d4; background:#eef2f6; color:#35475a; }
        .face-visibility-button--show { border-color:#b9e8cc; background:#edfaf3; color:#16824a; }
        .face-visibility-button--show:hover { border-color:#3db475; background:#dff6e9; color:#106b3c; }
        .face-groups-empty { margin:1.4rem 1.65rem 1.65rem; padding:2.25rem 1rem; border:1px dashed #cad6e2; border-radius:14px; background:#f9fbfd; text-align:center; }
        .face-groups-empty>span { display:grid; width:52px; height:52px; margin:0 auto .8rem; place-items:center; border-radius:50%; background:#eaf2fa; color:#5782ad; font-size:1.35rem; }
        .face-groups-empty h6 { margin:0 0 .35rem; color:#29425b; font-weight:700; }
        .face-groups-empty p { margin:0; color:#7b8998; font-size:.8rem; }
        @media (max-width:767.98px) { .face-groups-header { align-items:flex-start; flex-direction:column; }.face-groups-header form,.face-process-button { width:100%; }.face-groups-toolbar { align-items:flex-start; flex-direction:column; }.face-groups-bulk-actions { width:100%; }.face-groups-bulk-actions form { flex:1; }.face-bulk-button { width:100%; justify-content:center; }.face-groups-grid { grid-template-columns:repeat(2,minmax(0,1fr)); gap:.75rem; padding:1rem; } }
        @media (max-width:380px) { .face-groups-grid { grid-template-columns:1fr; }.face-groups-heading__icon { display:none; } }
    </style>
@stop
