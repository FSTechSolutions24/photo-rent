@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content')
    @php
        $netClass = $summary['net'] > 0 ? 'profit' : ($summary['net'] < 0 ? 'loss' : 'zero');
        $netIcon = $summary['net'] > 0 ? 'fa-arrow-up' : ($summary['net'] < 0 ? 'fa-arrow-down' : 'fa-minus');
        $firstName = explode(' ', trim(auth()->user()->name ?? 'Photographer'))[0];
    @endphp

    <main class="dashboard-shell">
    <section class="dashboard-hero">
        <div class="hero-copy">
            <span class="hero-eyebrow"><i></i> PhotoRent workspace</span>
            <h1>Welcome back, {{ $firstName }}</h1>
            <p>Keep an eye on your photography business, manage your bookings, and deliver beautiful galleries—all from one place.</p>
            <div class="hero-actions">
                <a href="{{ route('dashboard.sessions.create') }}" class="btn hero-primary"><i class="fas fa-plus mr-2"></i>New session</a>
                <a href="{{ route('dashboard.galleries.index') }}" class="btn hero-secondary">Explore galleries</a>
            </div>
        </div>
        <div class="hero-art" aria-hidden="true">
            <span class="orbit orbit-one"></span><span class="orbit orbit-two"></span>
            <span class="hero-icon"><i class="fas fa-camera-retro"></i></span>
            <i class="spark spark-one"></i><i class="spark spark-two"></i>
        </div>
    </section>

    <div class="row dashboard-stats">
        <div class="col-sm-6 col-xl-3"><a href="{{ route('dashboard.clients.index') }}" class="dash-stat stat-teal">
            <span class="stat-icon"><i class="fas fa-users"></i></span><strong>{{ number_format($counts['clients']) }}</strong>
            <span class="stat-label">Customers</span><small>People in your workspace</small><i class="fas fa-arrow-right stat-arrow"></i>
        </a></div>
        <div class="col-sm-6 col-xl-3"><a href="{{ route('dashboard.sessions.index') }}" class="dash-stat stat-lime">
            <span class="stat-icon"><i class="fas fa-camera"></i></span><strong>{{ number_format($counts['sessions']) }}</strong>
            <span class="stat-label">Sessions</span><small>All photography sessions</small><i class="fas fa-arrow-right stat-arrow"></i>
        </a></div>
        <div class="col-sm-6 col-xl-3"><a href="{{ route('dashboard.galleries.index') }}" class="dash-stat stat-amber">
            <span class="stat-icon"><i class="fas fa-images"></i></span><strong>{{ number_format($counts['galleries']) }}</strong>
            <span class="stat-label">Galleries</span><small>Client galleries created</small><i class="fas fa-arrow-right stat-arrow"></i>
        </a></div>
        <div class="col-sm-6 col-xl-3"><a href="{{ route('photographer.appointments.index') }}" class="dash-stat stat-rose">
            <span class="stat-icon"><i class="far fa-calendar-alt"></i></span>
            <strong class="meeting">{{ $nextMeeting ? $nextMeeting['starts_at']->format('d M · H:i') : 'No meeting' }}</strong>
            <span class="stat-label">Next meeting</span><small>{{ $nextMeeting ? 'Your next scheduled appointment' : 'Your calendar is clear' }}</small><i class="fas fa-arrow-right stat-arrow"></i>
        </a></div>
    </div>

    <div class="dash-section"><div><span class="section-kicker">BUSINESS PULSE</span><h2>Finance overview</h2></div><a href="{{ route('dashboard.finance-report.index') }}">View full report <i class="fas fa-arrow-right ml-1"></i></a></div>
    <div class="dash-finance">
        <div class="received"><span class="finance-icon"><i class="fas fa-hand-holding-usd"></i></span><small>Received</small><strong>{{ number_format($summary['received'], 2) }}</strong></div>
        <div class="spent"><span class="finance-icon"><i class="fas fa-receipt"></i></span><small>Spent</small><strong>{{ number_format($summary['spent'], 2) }}</strong></div>
        <div class="outstanding"><span class="finance-icon"><i class="fas fa-clock"></i></span><small>Still to collect</small><strong>{{ number_format($summary['outstanding'], 2) }}</strong></div>
        <div class="{{ $netClass }}"><span class="finance-icon"><i class="fas fa-chart-line"></i></span><small>Profit / Loss</small><strong><i class="fas {{ $netIcon }} mr-1"></i>{{ number_format(abs($summary['net']), 2) }}</strong></div>
    </div>

    <div class="row lower-dashboard">
        <div class="col-lg-7 mb-4"><section class="dash-panel">
            <div class="dash-panel-head"><div><span class="section-kicker">SCHEDULE</span><h2>Upcoming meetings</h2></div><a href="{{ route('photographer.appointments.index') }}">Open calendar</a></div>
            @forelse ($upcomingMeetings as $meeting)
                <div class="next-meeting"><div class="date"><strong>{{ $meeting['starts_at']->format('d') }}</strong><span>{{ $meeting['starts_at']->format('M') }}</span></div><div class="meeting-copy"><a class="appointment-link" href="{{ $meeting['edit_url'] }}">{{ $meeting['name'] }}</a><small><i class="far fa-clock mr-1"></i>{{ $meeting['time_label'] }}</small><small>{{ $meeting['subtitle'] }} · {{ ucfirst($meeting['type']) }}</small></div><i class="fas fa-chevron-right meeting-arrow"></i></div>
            @empty
                <div class="empty"><span><i class="far fa-calendar-plus"></i></span><div><strong>Your schedule is open</strong><p>No upcoming meeting. <a href="{{ route('photographer.appointments.create') }}">Schedule one</a></p></div></div>
            @endforelse
        </section></div>
        <div class="col-lg-5 mb-4"><section class="dash-panel session-panel">
            <div class="dash-panel-head"><div><span class="section-kicker">REVENUE</span><h2>Session value</h2></div><a href="{{ route('dashboard.sessions.index') }}">View sessions</a></div>
            <div class="session-value"><span>Total amount across all sessions</span><strong>{{ number_format($summary['total_amount'], 2) }}</strong><div class="value-progress"><i style="width: {{ $summary['total_amount'] > 0 ? min(100, max(5, (($summary['total_amount'] - $summary['outstanding']) / $summary['total_amount']) * 100)) : 0 }}%"></i></div><small><b>{{ number_format($summary['outstanding'], 2) }}</b> still to collect</small></div>
        </section></div>
    </div>
    </main>
@stop

@section('css')
<style>
.content-wrapper>.content{padding:0!important}
.dashboard-shell{display:block;width:100%;box-sizing:border-box;margin:0;padding:38px var(--pr-dashboard-gutter) 0}.dashboard-hero{position:relative;display:flex;min-height:270px;overflow:hidden;margin-bottom:22px;padding:46px 48px;border-radius:20px;background:linear-gradient(120deg,#073b74 0%,#1455ac 58%,#2576cd 100%);box-shadow:0 18px 38px rgba(7,59,116,.18);color:#fff}.hero-copy{position:relative;z-index:2;max-width:730px}.hero-eyebrow,.section-kicker{display:block;font-size:.68rem;font-weight:800;letter-spacing:.13em;text-transform:uppercase}.hero-eyebrow{margin-bottom:18px;color:#d5e9ff}.hero-eyebrow i{display:inline-block;width:23px;height:2px;margin-right:8px;vertical-align:middle;background:#70aef0}.dashboard-hero h1{margin:0 0 12px;font-size:2.25rem;font-weight:800;letter-spacing:-.035em}.dashboard-hero p{max-width:680px;margin:0;color:rgba(238,246,255,.82);font-size:.96rem;line-height:1.75}.hero-actions{display:flex;gap:10px;margin-top:24px}.hero-actions .btn{padding:10px 18px;border-radius:8px;font-weight:700}.hero-primary{background:#fff;color:#1455ac!important;box-shadow:0 7px 20px rgba(0,0,0,.13)}.hero-secondary{border:1px solid rgba(255,255,255,.3);background:rgba(255,255,255,.08);color:#fff!important}.hero-art{position:absolute;top:0;right:0;width:34%;height:100%}.hero-icon{position:absolute;top:50%;left:52%;display:grid;width:96px;height:96px;place-items:center;transform:translate(-50%,-50%);border:1px solid rgba(255,255,255,.14);border-radius:50%;background:rgba(255,255,255,.1);box-shadow:0 0 0 18px rgba(255,255,255,.035);font-size:2rem;color:rgba(255,255,255,.72)}.orbit{position:absolute;border:1px solid rgba(255,255,255,.1);border-radius:50%}.orbit-one{width:270px;height:270px;top:-125px;right:25px}.orbit-two{width:230px;height:230px;right:-95px;bottom:-110px}.spark{position:absolute;width:13px;height:13px;border:4px solid #fff;border-radius:50%;background:#62a7f1}.spark-one{top:62px;left:48%}.spark-two{right:24%;bottom:55px;background:#a9d2ff}
.dashboard-stats{margin-right:-9px;margin-left:-9px}.dashboard-stats>[class*=col-]{padding-right:9px;padding-left:9px}.dash-stat{position:relative;display:block;min-height:178px;overflow:hidden;margin-bottom:18px;padding:20px;border:1px solid #e0e9e7;border-radius:15px;background:#fff;box-shadow:0 8px 24px rgba(31,68,74,.055);color:#18363e;transition:transform .2s ease,box-shadow .2s ease}.dash-stat:after{position:absolute;right:-27px;bottom:-34px;width:88px;height:88px;border-radius:50%;background:var(--stat-soft);content:''}.dash-stat:hover{color:#18363e;text-decoration:none;transform:translateY(-3px);box-shadow:0 14px 32px rgba(31,68,74,.1)}.stat-icon{display:grid;width:43px;height:43px;margin-bottom:15px;place-items:center;border-radius:11px;background:var(--stat-soft);color:var(--stat-color);font-size:1rem}.dash-stat strong{display:block;font-size:1.65rem;line-height:1.1}.dash-stat strong.meeting{min-height:29px;padding-top:5px;font-size:1.02rem}.stat-label{display:block;margin-top:4px;font-size:.82rem;font-weight:800}.dash-stat small{display:block;margin-top:5px;color:#849397;font-size:.72rem}.stat-arrow{position:absolute;top:24px;right:21px;color:#bcc9c8;font-size:.75rem}.stat-teal{--stat-color:#1769c2;--stat-soft:#e7f1ff}.stat-lime{--stat-color:#4c73ba;--stat-soft:#edf2ff}.stat-amber{--stat-color:#387fc8;--stat-soft:#e9f3ff}.stat-rose{--stat-color:#315f9e;--stat-soft:#eaf0f8}
.dash-section,.dash-panel-head{display:flex;align-items:center;justify-content:space-between;gap:1rem}.dash-section{margin:10px 0 13px}.section-kicker{margin-bottom:3px;color:#88a09f}.dash-section h2,.dash-panel-head h2{margin:0;color:#18363e;font-size:1.15rem;font-weight:800}.dash-section>a,.dash-panel-head>a{color:#1455ac;font-size:.79rem;font-weight:700}.dash-finance{display:grid;grid-template-columns:repeat(4,1fr);overflow:hidden;border:1px solid #e0e9e7;border-radius:15px;background:#fff;box-shadow:0 8px 24px rgba(31,68,74,.055)}.dash-finance>div{position:relative;padding:20px 22px;border-right:1px solid #e6eeec}.dash-finance>div:last-child{border:0}.finance-icon{float:right;display:grid;width:36px;height:36px;place-items:center;border-radius:10px;background:#edf3fa;color:#52759a}.dash-finance small{display:block;color:#80918f;font-size:.68rem;font-weight:800;letter-spacing:.05em;text-transform:uppercase}.dash-finance strong{display:block;margin-top:6px;color:#17343d;font-size:1.3rem}.received .finance-icon,.received strong,.profit .finance-icon,.profit strong{color:#1769c2}.spent .finance-icon,.spent strong,.loss .finance-icon,.loss strong{color:#d05e67}.outstanding .finance-icon,.outstanding strong{color:#d29110}.zero .finance-icon,.zero strong{color:#71848a}
.lower-dashboard{margin-top:22px}.dash-panel{height:100%;padding:23px;border:1px solid #e0e9e7;border-radius:15px;background:#fff;box-shadow:0 8px 24px rgba(31,68,74,.055)}.next-meeting{display:flex;align-items:center;gap:14px;margin-top:15px;padding:0 0 15px;border-bottom:1px solid #edf2f1}.next-meeting:last-child{padding-bottom:0;border-bottom:0}.next-meeting .date{display:flex;flex-direction:column;align-items:center;min-width:53px;padding:7px;border-radius:11px;background:#e7f1ff;color:#1769c2}.date strong{font-size:1.15rem}.date span{font-size:.65rem;font-weight:800;text-transform:uppercase}.meeting-copy{min-width:0}.next-meeting small{display:block;margin-top:2px;color:#829290;font-size:.73rem}.appointment-link{display:block;overflow:hidden;color:#18363e;font-size:.88rem;font-weight:800;text-overflow:ellipsis;white-space:nowrap}.appointment-link:hover{color:#1455ac;text-decoration:none}.meeting-arrow{margin-left:auto;color:#bdc9c8;font-size:.7rem}.empty{display:flex;align-items:center;gap:14px;padding:34px 4px 22px;color:#7b8e8c}.empty>span{display:grid;width:48px;height:48px;place-items:center;border-radius:12px;background:#e7f1ff;color:#1769c2}.empty strong{display:block;color:#28464d}.empty p{margin:2px 0 0;font-size:.8rem}.session-value{padding:28px 2px 10px}.session-value>span,.session-value>small{display:block;color:#7b8e8c;font-size:.78rem}.session-value>strong{display:block;margin:5px 0 18px;color:#17343d;font-size:2.2rem;letter-spacing:-.04em}.value-progress{height:7px;overflow:hidden;margin-bottom:12px;border-radius:10px;background:#edf2f1}.value-progress i{display:block;height:100%;border-radius:10px;background:linear-gradient(90deg,#1769c2,#62a7f1)}.session-value small b{color:#d29110}
@media(max-width:991.98px){.dashboard-shell{margin:0;padding:24px 22px 0}.dashboard-hero{padding:38px 36px}.hero-art{opacity:.55;width:45%}.dash-finance{grid-template-columns:repeat(2,1fr)}.dash-finance>div:nth-child(2){border-right:0}.dash-finance>div:nth-child(-n+2){border-bottom:1px solid #e6eeec}}
@media(max-width:575.98px){.dashboard-shell{margin:0;padding:18px 14px 0}.dashboard-hero{min-height:300px;padding:30px 25px}.dashboard-hero h1{margin-bottom:10px;font-size:1.8rem}.hero-copy{max-width:100%}.hero-art{display:none}.hero-actions{align-items:stretch;flex-direction:column;max-width:190px}.dash-finance{grid-template-columns:1fr}.dash-finance>div{border-right:0;border-bottom:1px solid #e6eeec!important}.dash-section{align-items:flex-end}.dash-section>a{white-space:nowrap}.dash-stat{min-height:160px}}
</style>
@stop
