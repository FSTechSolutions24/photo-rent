@props([
    'title',
    'description',
    'breadcrumbs' => [],
    'actionUrl' => null,
    'actionLabel' => null,
    'actionIcon' => 'fas fa-plus',
    'actionTarget' => null,
])

<header {{ $attributes->merge(['class' => 'page-intro-card']) }}>
    <nav class="page-breadcrumb" aria-label="Breadcrumb">
        <a href="{{ route('dashboard') }}"><i class="fas fa-home"></i><span>Home</span></a>
        @foreach($breadcrumbs as $breadcrumb)
            <i class="fas fa-chevron-right" aria-hidden="true"></i>
            @if(!empty($breadcrumb['url']))
                <a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['label'] }}</a>
            @else
                <span aria-current="page">{{ $breadcrumb['label'] }}</span>
            @endif
        @endforeach
    </nav>

    <div class="page-intro-body">
        <div class="page-intro-copy">
            <h1>{{ $title }}</h1>
            <p>{{ $description }}</p>
        </div>
        @if($actionUrl && $actionLabel)
            <a href="{{ $actionUrl }}" class="btn page-intro-action" @if($actionTarget) target="{{ $actionTarget }}" @endif>
                <i class="{{ $actionIcon }} mr-2"></i>{{ $actionLabel }}
            </a>
        @endif
    </div>
</header>
