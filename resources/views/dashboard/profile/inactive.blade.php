@extends('adminlte::page')

@section('content')

<div class="renewal-page container py-4">
  <section class="renewal-hero text-center">
    <span class="renewal-icon"><i class="fas fa-hourglass-end"></i></span>
    <p class="eyebrow">FREE TRIAL ENDED</p>
    <h1>Your free trial has come to an end.</h1>
    <p>Your portfolio and account are safe. Choose a paid plan to reactivate your workspace and continue sharing your work.</p>
  </section>

  <div class="row justify-content-center g-4">
    @forelse ($plans as $plan)
      <div class="col-12 col-md-6 col-xl-4 d-flex">
        <article class="plan-card {{ $plan->most_popular ? 'is-popular' : '' }}">
          @if ($plan->most_popular)<span class="popular-badge">Most popular</span>@endif
          <h2>{{ $plan->name }}</h2>
          <p class="price">${{ $plan->price }}<small>/ {{ $plan->billing_cycle ?? 'month' }}</small></p>
          <ul>
            @foreach ($plan->lines as $line)<li>{{ $line->feature_name }}</li>@endforeach
          </ul>
          <button type="button" class="btn renew-btn" data-plan-id="{{ $plan->id }}">Choose {{ $plan->name }}</button>
        </article>
      </div>
    @empty
      <div class="col-12"><div class="alert alert-info text-center">Paid plans are not available yet. Please contact support.</div></div>
    @endforelse
  </div>
</div>

@section('js')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
document.querySelectorAll('.renew-btn').forEach((button) => {
  button.addEventListener('click', () => {
    const original = button.innerHTML;
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Redirecting…';
    axios.post('{{ route('dashboard.profile.renew-subscription') }}', {
      selectedPlan: button.dataset.planId,
      _token: '{{ csrf_token() }}'
    })
      .then(({ data }) => window.location.href = data.url)
      .catch((error) => {
        button.disabled = false;
        button.innerHTML = original;
        window.toastr?.error(error.response?.data?.message || 'We could not start your subscription. Please try again.');
      });
  });
});
</script>
@endsection

@section('css')
<style>
.renewal-page { max-width: 1100px; }.renewal-hero { max-width:700px; margin:1rem auto 3rem; }.renewal-icon { display:inline-grid; place-items:center; width:58px; height:58px; margin-bottom:1rem; border-radius:50%; color:#fff; background:linear-gradient(135deg,#f59e0b,#ea580c); box-shadow:0 12px 26px rgba(234,88,12,.24); font-size:1.45rem; }.eyebrow { margin:0 0 .4rem; color:#ea580c; font-size:.75rem; font-weight:800; letter-spacing:.12em; }.renewal-hero h1 { color:#1e293b; font-weight:750; letter-spacing:-.035em; }.renewal-hero > p:last-child { color:#64748b; font-size:1.04rem; }.plan-card { position:relative; display:flex; flex-direction:column; width:100%; padding:2rem; border:1px solid #e2e8f0; border-radius:18px; background:#fff; box-shadow:0 10px 28px rgba(15,23,42,.07); }.plan-card.is-popular { border:2px solid #4f46e5; box-shadow:0 14px 32px rgba(79,70,229,.16); }.popular-badge { position:absolute; top:-13px; right:22px; padding:5px 11px; border-radius:999px; color:#fff; background:#4f46e5; font-size:.72rem; font-weight:700; }.plan-card h2 { color:#1e293b; font-size:1.35rem; font-weight:700; }.price { color:#4f46e5; font-size:2.3rem; font-weight:750; line-height:1; }.price small { color:#64748b; font-size:.85rem; font-weight:500; }.plan-card ul { flex:1; padding:0; margin:1.4rem 0; list-style:none; color:#475569; }.plan-card li { margin:.7rem 0; }.plan-card li::before { content:'✓'; margin-right:.6rem; color:#10b981; font-weight:800; }.renew-btn { width:100%; padding:.75rem 1rem; border:0; border-radius:10px; color:#fff; background:#4f46e5; font-weight:700; }.renew-btn:hover { color:#fff; background:#4338ca; }
</style>
@endsection

@stop
