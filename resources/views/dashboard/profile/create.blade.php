@extends('adminlte::page')

@section('content')

<div class="container">
    <div class="pricing-section">
      <h1>Choose Your Plan</h1>
      <p>Simple, transparent pricing to help you grow your portfolio.</p>
    </div>

    <div class="row justify-content-center g-4">
      <!-- Basic Plan -->
      <div class="col-md-4">
        <div class="card pricing-card">
          <div class="plan-header">
            <div class="plan-name">Starter</div>
            <div class="plan-price">$9<span class="plan-duration">/mo</span></div>
          </div>
          <ul class="plan-features">
            <li>1 Portfolio Website</li>
            <li>5GB Storage</li>
            <li>Basic Analytics</li>
            <li>Email Support</li>
          </ul>
          <button class="btn plan-btn">Select Plan</button>
        </div>
      </div>

      <!-- Recommended Plan -->
      <div class="col-md-4">
        <div class="card pricing-card recommended">
          <div class="badge-recommended">Most Popular</div>
          <div class="plan-header">
            <div class="plan-name">Pro</div>
            <div class="plan-price">$19<span class="plan-duration">/mo</span></div>
          </div>
          <ul class="plan-features">
            <li>5 Portfolio Websites</li>
            <li>25GB Storage</li>
            <li>Advanced Analytics</li>
            <li>Priority Support</li>
            <li>Custom Domain</li>
          </ul>
          <button class="btn plan-btn">Select Plan</button>
        </div>
      </div>

      <!-- Premium Plan -->
      <div class="col-md-4">
        <div class="card pricing-card">
          <div class="plan-header">
            <div class="plan-name">Enterprise</div>
            <div class="plan-price">$49<span class="plan-duration">/mo</span></div>
          </div>
          <ul class="plan-features">
            <li>Unlimited Portfolios</li>
            <li>100GB Storage</li>
            <li>Team Collaboration</li>
            <li>Custom Integrations</li>
            <li>Dedicated Support</li>
          </ul>
          <button class="btn plan-btn">Select Plan</button>
        </div>
      </div>
    </div>
</div>
  
<br><br>
<div class="container">
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
        <h5 class="mb-3 fw-semibold text-secondary">
            Claim your portfolio link
        </h5>
    
        <div class="domain-input">
            <input id="subdomain-input" type="text" placeholder="yourname" />
            <span>.yourdomain.com</span>
        </div>
    
        <div id="status"></div>
    
        <button id="create-btn" class="btn btn-checker" disabled>
            Create Portfolio
        </button>
    </div>
    </div>
</div>
 
<br><br><br><br>
  
@section('js')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>

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

    if (!value) return;

    status.innerHTML = `
      <div class="spinner-border text-primary" role="status"></div>
      <span>Checking availability...</span>
    `;
    
    clearTimeout(timeout);
    timeout = setTimeout(() => {
      check_subdomain_existance(value);
    }, 300);

  });

  function check_subdomain_existance(value){
    axios.get(`/dashboard/api/profile/checksubdomain/${value}`)
    .then(response => {
      if (response.data.available) {
        status.innerHTML = `<span class="text-success fw-semibold"> ${response.data.message}</span>`;
      } else {
        status.innerHTML = `<span class="text-danger fw-semibold"> ${response.data.message}</span>`;
      }
    })
    .catch(error => {
      window.toastr.error('Failed to check subdomain. ' + error);
    });
  }

</script>
@endsection

<style>

  .browser-wrapper {
    /* max-width: 480px; */
    margin: auto;
    border-radius: 16px;
    background-color: #fff;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    transition: all 0.3s ease;
  }

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
    margin-bottom: 3rem;
  }

  .pricing-section h1 {
    font-weight: 700;
    font-size: 2.5rem;
    margin-bottom: 0.5rem;
  }

  .pricing-section p {
    color: #64748b;
    font-size: 1rem;
    margin-bottom: 2rem;
  }

  .pricing-card {
    border: none;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
    transition: all 0.3s ease;
    background-color: #fff;
    overflow: hidden;
    position: relative;
  }

  .pricing-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 32px rgba(0, 0, 0, 0.08);
  }

  .plan-header {
    padding: 2rem 1.5rem 1rem;
  }

  .plan-name {
    font-size: 1.4rem;
    font-weight: 600;
    color: #1e293b;
  }

  .plan-price {
    font-size: 2.5rem;
    font-weight: 700;
    color: #4f46e5;
    margin-top: 0.5rem;
  }

  .plan-duration {
    font-size: 0.9rem;
    color: #64748b;
  }

  .plan-features {
    list-style: none;
    padding: 0;
    margin: 1.5rem 0;
    text-align: left;
    font-size: 0.95rem;
    color: #334155;
  }

  .plan-features li {
    padding: 0.5rem 1.5rem;
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
    padding: 0.75rem 1rem;
    width: calc(100% - 3rem);
    margin: 0 auto 1.5rem;
    transition: background-color 0.3s ease;
  }

  .plan-btn:hover {
    background-color: #4338ca;
    color: white;
  }

  .recommended {
    border: 2px solid #6366f1;
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

  @media (max-width: 992px) {
    .pricing-card {
      margin-bottom: 2rem;
    }
  }
</style>

@stop