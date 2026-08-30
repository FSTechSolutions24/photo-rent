@extends('adminlte::page')

@section('content')

<div class="container setup-step" id="plan-step">
  <div class="pricing-section">
    <div class="setup-progress"><span class="is-active">1</span><i></i><span>2</span></div>
    <p class="setup-step-label">Step 1 of 2</p>
    <h1>Choose Your Plan</h1>
    <p>Simple, transparent pricing to help you grow your portfolio.</p>
  </div>

  <div class="row plan-grid g-4">

    @if ($canStartTrial)
      <div class="col-12 col-sm-6 col-lg-3 d-flex">
        <div class="card pricing-card trial-plan">
          <div class="badge-trial">14-Day Free Trial</div>
          <div class="plan-header">
            <div class="plan-name">Free Trial</div>
            <div class="plan-price">$0<span class="plan-duration">/14 days</span></div>
          </div>
          <ul class="plan-features">
            <li>8 GB secure storage</li>
            <li>Full photographer workspace</li>
            <li>No payment details required</li>
            <li>Automatically ends after 14 days</li>
          </ul>
          <button class="btn plan-btn select_plan" data-id="trial">Start Free Trial</button>
        </div>
      </div>
    @endif

    <!-- Recommended Plan -->
    @foreach ($plans as $plan)
      <div class="col-12 col-sm-6 col-lg-3 d-flex">
        <div class="card pricing-card {{ $plan->most_popular ? 'recommended' : '' }}">
          @if($plan->most_popular)
            <div class="badge-recommended">Most Popular</div>
          @endif
          <div class="plan-header">
            <div class="plan-name">{{ $plan->name }}</div>
            <div class="plan-price">${{ $plan->price }}<span class="plan-duration">/mo</span></div>
          </div>
          <ul class="plan-features">
            @foreach ($plan->lines as $line)
              <li>{{ $line->feature_name }}</li>
            @endforeach
          </ul>
          <button class="btn plan-btn select_plan" data-id="{{ $plan->id }}">Select Plan</button>
        </div>
      </div>
    @endforeach

  </div>

  <div class="setup-action">
    <button id="next-btn" class="btn btn-checker" type="button" disabled>
      Next: Choose your domain <i class="fas fa-arrow-right ms-2"></i>
    </button>
  </div>
</div>
  
<div class="container setup-step" id="domain-step" hidden>
  <div class="setup-step-heading">
    <div class="domain-navigation">
      <button id="back-to-plans" class="btn" type="button"><i class="fas fa-arrow-left"></i><span>Back to plans</span></button>
      <span><i class="fas fa-shield-alt"></i> Secure setup</span>
    </div>
    <div class="setup-progress"><span class="is-complete"><i class="fas fa-check"></i></span><i></i><span class="is-active">2</span></div>
    <p class="setup-step-label">Step 2 of 2</p>
    <h2>Claim your portfolio link</h2>
    <p>Your clients will use this address to view their galleries.</p>
  </div>
  <div class="domain-panel">
    <div class="browser-wrapper">
      <div class="browser-header">
        <div class="browser-dots">
        <span class="red"></span>
        <span class="yellow"></span>
        <span class="green"></span>
        </div>
        <div class="browser-tab" id="tabTitle">yourdomain.com</div>
      </div>

      <div class="browser-body">
        <label for="subdomain-input">Your portfolio address</label>
        <div class="domain-input">
          <input id="subdomain-input" type="text" placeholder="yourname" />
          <span>.yourdomain.com</span>
        </div>

        <div id="status"></div>
      </div>
    </div>
    <button id="create-btn" class="btn btn-checker" disabled>
      Create Portfolio <i class="fas fa-check ms-2"></i>
    </button>
    <p class="domain-help"><i class="fas fa-info-circle"></i> You can update your portfolio settings later from your dashboard.</p>
  </div>
</div>

<br><br><br><br>
  
@section('js')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>

  window.selectedPlan = '';
  const nextButton = document.getElementById('next-btn');
  const planStep = document.getElementById('plan-step');
  const domainStep = document.getElementById('domain-step');
  $('.select_plan').click(function(){
    window.selectedPlan = $(this).data('id');
    $('.pricing-card').removeClass('selected-plan');
    $(this).parent('.pricing-card').addClass('selected-plan');
    $('.pricing-card').find('.select_plan').prop('disabled', false);
    $(this).parent('.pricing-card').find('.select_plan').prop('disabled', true);
    check_profile_creation();
  })

  const input = document.getElementById("subdomain-input");
  const status = document.getElementById("status");
  const btn = document.getElementById("create-btn");
  const tabTitle = document.getElementById("tabTitle");
  let timeout = null;

  input.addEventListener("input", () => {
    const value = input.value.trim();
    btn.disabled = true;
    status.textContent = "";

    // Update "tab title" dynamically
    tabTitle.textContent = value ? `${value}.yourdomain.com` : "yourdomain.com";

    status.innerHTML = `
      <div class="spinner-border text-primary" role="status"></div>
      <span>Checking availability...</span>
    `;
    
    clearTimeout(timeout);
    timeout = setTimeout(() => {
      check_subdomain_existance(value);
    }, 300);

  });

  window.validSubdomain = false;
  function check_subdomain_existance(value){
    axios.get(`/dashboard/api/profile/checksubdomain`, {
      params: { subdomain: value }
    })
    .then(response => {
      if (response.data.subdomain_empty) {
        status.innerHTML = `<span class="text-danger fw-semibold"> ${response.data.message}</span>`;
        window.validSubdomain = false;
        check_profile_creation();
      }
      if (response.data.available) {
        status.innerHTML = `<span class="text-success fw-semibold"> ${response.data.message}</span>`;
        window.validSubdomain = true;
        check_profile_creation();
      } else {
        status.innerHTML = `<span class="text-danger fw-semibold"> ${response.data.message}</span>`;
        window.validSubdomain = false;
        check_profile_creation();
      }
    })
    .catch(error => {
      window.toastr.error('Failed to check subdomain. ' + error);
      window.validSubdomain = false;
      check_profile_creation();
    });
  }

  function check_profile_creation(){
    $('#next-btn').prop('disabled', window.selectedPlan === '');
    $('#create-btn').prop('disabled', !(window.validSubdomain === true && window.selectedPlan !== ''));
  }

  nextButton.addEventListener('click', () => {
    if (!window.selectedPlan) return;
    planStep.hidden = true;
    domainStep.hidden = false;
    document.getElementById('subdomain-input').focus();
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });

  document.getElementById('back-to-plans').addEventListener('click', () => {
    domainStep.hidden = true;
    planStep.hidden = false;
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });

  $(document).on('click','#create-btn',function(){

    // axios.get(`/dashboard/api/profile/createphotographerprofile`, {
    //   params: { 
    //     selectedPlan: window.selectedPlan, 
    //     subdomain: $('#subdomain-input').val(), 
    //   }
    // })
    // .then(response => {
    //   if(response.data.success) {
    //     window.location.href = '/dashboard';
    //   }
    // })
    // .catch(error => {
    // });

    var plan = window.selectedPlan;
    var subdomain = $('#subdomain-input').val(); 
    axios.get(`/dashboard/api/profile/createphotographerprofile`, {
      params: { 
        selectedPlan: window.selectedPlan, 
        subdomain: $('#subdomain-input').val(), 
      }
    })
    .then(response => {
      if (response.data.trial) {
        window.location.href = '/dashboard';
        return;
      }

      paymob_payment(response.data.url);
    })
    .catch(error => {
      const message = error.response?.data?.message || 'We could not create your portfolio. Please try again.';
      status.innerHTML = `<span class="text-danger fw-semibold">${message}</span>`;
    });

  })

  function paymob_payment(url){
    console.log(url);
    window.open(url, '_self');
  }

</script>
@endsection

<style>

  body {
    background: #f4f7ff !important;
  }

  .content-wrapper {
    position: relative;
    min-height: 100vh;
    overflow: hidden;
    background:
      radial-gradient(circle at 12% 13%, rgba(129, 140, 248, .18), transparent 24rem),
      radial-gradient(circle at 88% 18%, rgba(45, 212, 191, .16), transparent 22rem),
      radial-gradient(circle at 50% 90%, rgba(244, 114, 182, .12), transparent 27rem),
      linear-gradient(145deg, #f8faff 0%, #f3f6ff 48%, #f8fbff 100%) !important;
  }

  .content-wrapper::before,
  .content-wrapper::after {
    content: '';
    position: absolute;
    z-index: 0;
    border-radius: 50%;
    pointer-events: none;
    filter: blur(1px);
  }

  .content-wrapper::before {
    width: 310px;
    height: 310px;
    top: 70px;
    right: -120px;
    border: 1px solid rgba(99, 102, 241, .16);
    background: radial-gradient(circle at 30% 30%, rgba(255,255,255,.72), rgba(196,181,253,.12) 45%, transparent 71%);
    box-shadow: inset 0 0 58px rgba(129, 140, 248, .12);
  }

  .content-wrapper::after {
    width: 220px;
    height: 220px;
    bottom: -75px;
    left: -65px;
    border: 1px solid rgba(20, 184, 166, .17);
    background: radial-gradient(circle at 65% 35%, rgba(255,255,255,.7), rgba(94,234,212,.14) 44%, transparent 72%);
    box-shadow: inset 0 0 45px rgba(20, 184, 166, .11);
  }

  .setup-step {
    position: relative;
    z-index: 1;
  }

  .browser-wrapper {
    margin: auto;
    border-radius: 16px;
    background-color: #fff;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    transition: all 0.3s ease;
  }

  .domain-panel {
    max-width: 720px;
    margin: 0 auto;
    padding: 1.15rem;
    border: 1px solid #e8ebf0;
    border-radius: 22px;
    background: linear-gradient(145deg, #fff, #f8faff);
    box-shadow: 0 18px 45px rgba(15, 23, 42, .08);
  }

  .domain-panel .btn-checker { margin: 1.15rem 0 0; }
  .domain-help { margin: .9rem 0 .1rem; color: #64748b; font-size: .78rem; text-align: center; }
  .domain-help i { margin-right: .35rem; color: #6366f1; }

  /* ----- Browser Header ----- */
  .browser-header {
    background-color: #e5e7eb;
    padding: 8px 14px;
    display: flex;
    align-items: center;
    justify-content: flex-start;
    gap: 8px;
    border-bottom: 1px solid #d1d5db;
    position: relative;
  }

  .browser-header::before {
    content: "";
    position: absolute;
    inset: 0;
    background: linear-gradient(to bottom, #f3f4f6, #e5e7eb);
    z-index: 0;
  }

  .browser-dots {
    display: flex;
    align-items: center;
    gap: 6px;
    z-index: 1;
  }

  .browser-dots span {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    display: inline-block;
  }

  .browser-dots .red { background-color: #ef4444; }
  .browser-dots .yellow { background-color: #f59e0b; }
  .browser-dots .green { background-color: #22c55e; }

  /* ----- Tab Area ----- */
  .browser-tab {
    flex: 1;
    background-color: #fff;
    border-radius: 8px 8px 0 0;
    margin-left: 20px;
    padding: 4px 12px;
    font-size: 0.9rem;
    color: #374151;
    font-weight: 500;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    z-index: 1;
    position: relative;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
  }

  /* ----- Main Content ----- */
  .browser-body {
    padding: 24px;
  }

  .browser-body > label {
    display: block;
    margin-bottom: .7rem;
    color: #334155;
    font-size: .82rem;
    font-weight: 700;
  }

  .domain-input {
    display: flex;
    align-items: center;
    border: 1px solid #d1d5db;
    border-radius: 12px;
    padding: 0.5rem 0.75rem;
    transition: border-color 0.2s, box-shadow 0.2s;
  }

  .domain-input:focus-within {
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
  }

  .domain-input input {
    flex: 1;
    border: none;
    outline: none;
    font-size: 1rem;
    color: #111827;
    background: transparent;
  }

  .domain-input span {
    color: #6b7280;
    font-size: 0.95rem;
    white-space: nowrap;
  }

  #status {
    min-height: 22px;
    margin-top: 10px;
    font-size: 0.9rem;
  }

  .spinner-border {
    width: 1rem;
    height: 1rem;
    border-width: 0.15em;
    vertical-align: text-bottom;
    margin-right: 6px;
  }

  .btn-checker {
    width: 100%;
    margin-top: 1rem;
    border-radius: 12px;
    background-color: #4f46e5;
    border: none;
    color: #fff;
    font-weight: 500;
    padding: 0.75rem;
    transition: background-color 0.2s;
  }

  .btn-checker:hover:not(:disabled) {
    background-color: #4338ca;
    color: #fff;
  }

  .btn-checker:disabled {
    background-color: #d1d5db;
    cursor: not-allowed;
  }
</style>

<style>
  .pricing-section {
    text-align: center;
    max-width: 680px;
    margin: 0 auto 2.5rem;
  }

  .setup-step { padding-top: 1.5rem; }

  .setup-step-heading {
    max-width: 720px;
    margin: 0 auto 1.5rem;
    text-align: center;
  }

  .setup-step-heading h2 {
    margin: .35rem 0;
    color: #1e293b;
    font-size: clamp(1.8rem, 4vw, 2.35rem);
    font-weight: 700;
    letter-spacing: -.04em;
  }

  .setup-step-heading > p:last-child { color: #64748b; }

  .domain-navigation {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1.5rem;
    padding: .65rem .75rem;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    background: #fff;
    box-shadow: 0 4px 12px rgba(15, 23, 42, .04);
  }

  .domain-navigation #back-to-plans {
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    padding: .35rem .55rem;
    border: 0;
    border-radius: 8px;
    color: #4338ca;
    font-size: .86rem;
    font-weight: 700;
  }

  .domain-navigation #back-to-plans:hover { background: #eef2ff; }

  .domain-navigation > span {
    color: #64748b;
    font-size: .75rem;
    font-weight: 600;
  }

  .domain-navigation > span i { margin-right: .3rem; color: #10b981; }

  .setup-progress {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin-bottom: .65rem;
  }

  .setup-progress > span {
    display: grid;
    width: 25px;
    height: 25px;
    place-items: center;
    border: 1px solid #cbd5e1;
    border-radius: 50%;
    color: #94a3b8;
    font-size: .72rem;
    font-weight: 700;
  }

  .setup-progress > span.is-active, .setup-progress > span.is-complete {
    border-color: #4f46e5;
    background: #4f46e5;
    color: #fff;
  }

  .setup-progress > i { width: 32px; height: 1px; background: #cbd5e1; }
  .setup-step-label { margin: 0 0 .4rem; color: #6366f1; font-size: .72rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase; }

  .setup-action { display: flex; justify-content: center; margin: 2rem 0 .5rem; }

  .setup-action .btn-checker { width: auto; min-width: 265px; margin: 0; }

  .pricing-section h1 {
    font-weight: 700;
    font-size: clamp(2rem, 4vw, 2.7rem);
    letter-spacing: -0.04em;
    margin-bottom: 0.5rem;
  }

  .pricing-section p {
    color: #64748b;
    font-size: 1rem;
    margin-bottom: 2rem;
  }

  .pricing-card {
    width: 100%;
    min-height: 100%;
    display: flex;
    flex-direction: column;
    border: none;
    border-radius: 18px;
    box-shadow: 0 10px 28px rgba(15, 23, 42, 0.07);
    transition: all 0.3s ease;
    background-color: #fff;
    overflow: hidden;
    position: relative;
    border: 1px solid #edf0f5;
  }

  .pricing-card:hover {
    transform: translateY(-7px);
    box-shadow: 0 18px 38px rgba(15, 23, 42, 0.12);
  }

  .plan-header {
    padding: 1.7rem 1.35rem .8rem;
    min-height: 132px;
  }

  .plan-name {
    padding-right: 68px;
    font-size: 1.22rem;
    font-weight: 600;
    color: #1e293b;
  }

  .plan-price {
    font-size: 2.25rem;
    font-weight: 700;
    color: #4f46e5;
    line-height: 1;
    margin-top: .65rem;
  }

  .plan-duration {
    font-size: 0.9rem;
    color: #64748b;
  }

  .plan-features {
    list-style: none;
    padding: 0;
    margin: .6rem 0 1.25rem;
    text-align: left;
    font-size: .88rem;
    color: #334155;
    flex: 1;
  }

  .plan-features li {
    padding: .43rem 1.35rem;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .plan-features li::before, .text-success.fw-semibold::before {
    content: "✓";
    color: #22c55e;
    font-weight: bold;
  }
  .text-danger.fw-semibold::before {
      content: "✕";
  }
  .text-danger.fw-semibold {
      color: #dc3545
  }
  .text-success.fw-semibold {
      color: #22c55e
  }

  .plan-btn {
    background-color: #4f46e5;
    border: none;
    color: white;
    border-radius: 10px;
    font-weight: 500;
    padding: .72rem 1rem;
    width: calc(100% - 3rem);
    margin: auto auto 1.35rem;
    transition: background-color 0.3s ease;
  }

  .plan-btn:hover {
    background-color: #4338ca;
    color: white;
  }

  .recommended {
    border: 2px solid #6366f1;
    box-shadow: 0 14px 32px rgba(99, 102, 241, .15);
  }

  .badge-recommended {
    position: absolute;
    top: 15px;
    right: 15px;
    background: #4f46e5;
    color: #fff;
    font-size: 0.75rem;
    font-weight: 600;
    padding: 6px 12px;
    border-radius: 30px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
  }

  .trial-plan {
    border: 2px solid #10b981;
    background: linear-gradient(180deg, #f0fdf4 0%, #ffffff 55%);
  }

  .trial-plan .plan-price {
    color: #059669;
  }

  .trial-plan .plan-btn {
    background-color: #059669;
  }

  .trial-plan .plan-btn:hover {
    background-color: #047857;
  }

  .badge-trial {
    position: absolute;
    top: 15px;
    right: 15px;
    background: #059669;
    color: #fff;
    font-size: 0.75rem;
    font-weight: 600;
    padding: 6px 12px;
    border-radius: 30px;
    box-shadow: 0 2px 6px rgba(5, 150, 105, 0.2);
  }

  @media (max-width: 992px) {
    .pricing-card {
      margin-bottom: 2rem;
    }
  }

  .selected-plan {
    background: #eef2ff;
    border-color: #4f46e5;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, .13), 0 16px 38px rgba(15, 23, 42, .13);
  }

  .selected-plan::after {
    content: 'Selected';
    position: absolute;
    left: 50%;
    bottom: 10px;
    transform: translateX(-50%);
    color: #4f46e5;
    font-size: .68rem;
    font-weight: 700;
  }

  .selected-plan .plan-btn {
    margin-bottom: 2.15rem;
  }

  @media (min-width: 1200px) {
    .plan-grid { margin-left: -10px; margin-right: -10px; }
    .plan-grid > [class*='col-'] { padding-left: 10px; padding-right: 10px; }
  }

  @media (max-width: 575.98px) {
    .plan-header { min-height: auto; }
    .domain-panel { padding: .65rem; border-radius: 16px; }
    .domain-navigation > span { display: none; }
    .content-wrapper::before { width: 220px; height: 220px; top: 35px; right: -105px; }
    .content-wrapper::after { width: 160px; height: 160px; bottom: -55px; left: -65px; }
  }

</style>

@stop
