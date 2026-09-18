@extends('adminlte::page')

@section('title', 'WhatsApp Template')

@section('content')
<div class="container-fluid whatsapp-template-page">
  <x-page-header title="WhatsApp Message Template" description="Customize the message used when sharing gallery access with clients."
    :breadcrumbs="[['label' => 'Galleries', 'url' => route('dashboard.galleries.index')], ['label' => 'WhatsApp Template']]"
    :action-url="route('dashboard.galleries.index')" action-label="View galleries" action-icon="fas fa-images" />

  <div class="template-workspace">
    <section class="template-editor-card">
      <div class="template-card-header">
        <div class="template-card-icon"><i class="fab fa-whatsapp"></i></div>
        <div>
          <span class="template-eyebrow">Message editor</span>
          <h2>Build your gallery message</h2>
          <p>This message is prepared when you choose <strong>Send via WhatsApp</strong> from a gallery.</p>
        </div>
      </div>

      <form method="POST" action="{{ route('dashboard.whatsapp-template.update') }}">
        @csrf
        @method('PUT')

        <div class="template-placeholders">
          <div class="template-placeholders__heading">
            <div>
              <span class="template-eyebrow">Dynamic details</span>
              <h3>Insert a placeholder</h3>
            </div>
            <small>Click to add at your cursor</small>
          </div>
          <div class="template-placeholder-list" aria-label="Available placeholders">
            <button type="button" class="template-placeholder" data-token="@verbatim{{client_name}}@endverbatim">
              <span class="template-placeholder__icon"><i class="fas fa-user"></i></span>
              <span><strong>Client name</strong><code>@verbatim{{client_name}}@endverbatim</code></span>
            </button>
            <button type="button" class="template-placeholder" data-token="@verbatim{{url}}@endverbatim">
              <span class="template-placeholder__icon"><i class="fas fa-link"></i></span>
              <span><strong>Gallery link</strong><code>@verbatim{{url}}@endverbatim</code></span>
            </button>
            <button type="button" class="template-placeholder" data-token="@verbatim{{client_password}}@endverbatim">
              <span class="template-placeholder__icon"><i class="fas fa-key"></i></span>
              <span><strong>Client password</strong><code>@verbatim{{client_password}}@endverbatim</code></span>
            </button>
            <button type="button" class="template-placeholder" data-token="@verbatim{{guest_password}}@endverbatim">
              <span class="template-placeholder__icon"><i class="fas fa-user-friends"></i></span>
              <span><strong>Guest password</strong><code>@verbatim{{guest_password}}@endverbatim</code></span>
            </button>
          </div>
        </div>

        <div class="template-message-field">
          <div class="template-message-field__label">
            <label for="message">Message content</label>
            <span id="messageCount">0 / 4,000</span>
          </div>
          <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="11" maxlength="4000" required>{{ old('message', $template->message) }}</textarea>
          @error('message')<span class="invalid-feedback">{{ $message }}</span>@enderror
          <small class="template-field-help"><i class="fas fa-info-circle"></i> Placeholders are replaced automatically with each gallery's details.</small>
        </div>

        <div class="template-form-footer">
          <span><i class="fas fa-shield-alt"></i> Only your message template is updated.</span>
          <button class="btn template-save-button" type="submit"><i class="fab fa-whatsapp"></i> Save template</button>
        </div>
      </form>
    </section>

    <aside class="template-preview-card" aria-label="Message preview">
      <div class="template-preview-heading">
        <div>
          <span class="template-eyebrow">Live preview</span>
          <h2>Client view</h2>
        </div>
        <span class="template-live-badge"><i></i> Preview</span>
      </div>

      <div class="whatsapp-phone">
        <div class="whatsapp-phone__bar">
          <span class="whatsapp-phone__back"><i class="fas fa-chevron-left"></i></span>
          <span class="whatsapp-phone__avatar"><i class="fas fa-camera"></i></span>
          <span class="whatsapp-phone__contact"><strong>PhotoRent Studio</strong><small>online</small></span>
          <i class="fas fa-video"></i>
          <i class="fas fa-phone-alt"></i>
        </div>
        <div class="whatsapp-phone__chat">
          <span class="whatsapp-phone__date">Today</span>
          <div class="whatsapp-message">
            <span id="messagePreview"></span>
            <small>10:42 <i class="fas fa-check-double"></i></small>
          </div>
        </div>
        <div class="whatsapp-phone__composer">
          <span><i class="far fa-smile"></i> Message</span>
          <i class="fas fa-microphone"></i>
        </div>
      </div>

      <p class="template-preview-note"><i class="fas fa-magic"></i> Sample details are shown in the preview. Real gallery information is inserted when you share.</p>
    </aside>
  </div>
</div>
@endsection

@section('css')
<style>
  .whatsapp-template-page { padding-bottom: 2rem; }
  .template-workspace { display: grid; grid-template-columns: minmax(0, 1.55fr) minmax(320px, .85fr); align-items: start; gap: 1.25rem; }
  .template-editor-card, .template-preview-card { border: 1px solid #dfe8e6; border-radius: 1rem; background: #fff; box-shadow: 0 10px 28px rgba(31, 68, 74, .07); }
  .template-card-header { display: flex; align-items: flex-start; gap: .9rem; padding: 1.35rem 1.4rem 1.2rem; border-bottom: 1px solid #e6eeec; }
  .template-card-icon { display: grid; width: 44px; height: 44px; flex: 0 0 44px; place-items: center; border-radius: .8rem; background: linear-gradient(135deg, #25d366, #159447); box-shadow: 0 7px 16px rgba(28, 174, 88, .22); color: #fff; font-size: 1.25rem; }
  .template-eyebrow { display: block; margin-bottom: .18rem; color: #4b8c75; font-size: .65rem; font-weight: 800; letter-spacing: .12em; text-transform: uppercase; }
  .template-card-header h2, .template-preview-heading h2 { margin: 0; color: #173b3c; font-size: 1.1rem; font-weight: 750; }
  .template-card-header p { margin: .3rem 0 0; color: #778989; font-size: .78rem; }
  .template-editor-card form { padding: 1.35rem 1.4rem 1.4rem; }
  .template-placeholders { padding: 1rem; border: 1px solid #e1ebe8; border-radius: .8rem; background: #f8fbfa; }
  .template-placeholders__heading { display: flex; align-items: flex-end; justify-content: space-between; gap: 1rem; margin-bottom: .8rem; }
  .template-placeholders__heading h3 { margin: 0; color: #274849; font-size: .9rem; font-weight: 750; }
  .template-placeholders__heading small { color: #879796; font-size: .68rem; }
  .template-placeholder-list { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: .55rem; }
  .template-placeholder { display: flex; min-width: 0; align-items: center; gap: .65rem; padding: .68rem .75rem; border: 1px solid #dce8e5; border-radius: .65rem; background: #fff; color: #2e4d4e; text-align: left; cursor: pointer; transition: border-color .18s ease, box-shadow .18s ease, transform .18s ease; }
  .template-placeholder:hover, .template-placeholder:focus { border-color: #71c493; outline: 0; box-shadow: 0 6px 15px rgba(33, 107, 73, .09); transform: translateY(-1px); }
  .template-placeholder__icon { display: grid; width: 31px; height: 31px; flex: 0 0 31px; place-items: center; border-radius: .55rem; background: #eaf8f0; color: #1c9c56; font-size: .7rem; }
  .template-placeholder strong, .template-placeholder code { display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
  .template-placeholder strong { font-size: .75rem; }
  .template-placeholder code { margin-top: .12rem; color: #da4771; font-size: .64rem; }
  .template-message-field { margin-top: 1.15rem; }
  .template-message-field__label { display: flex; align-items: center; justify-content: space-between; margin-bottom: .45rem; }
  .template-message-field__label label { margin: 0; color: #294748; font-size: .8rem; font-weight: 750; }
  .template-message-field__label span { color: #889998; font-size: .68rem; font-weight: 650; }
  .template-message-field textarea { min-height: 245px; resize: vertical; border-color: #d6e2df; border-radius: .7rem; padding: .9rem 1rem; background: #fbfdfc; color: #273e40; font-size: .87rem; line-height: 1.65; transition: border-color .2s ease, box-shadow .2s ease, background .2s ease; }
  .template-message-field textarea:focus { border-color: #52b97a; background: #fff; box-shadow: 0 0 0 3px rgba(37, 211, 102, .11); }
  .template-field-help { display: block; margin-top: .5rem; color: #849493; font-size: .69rem; }
  .template-field-help i { margin-right: .25rem; color: #52a978; }
  .template-form-footer { display: flex; align-items: center; justify-content: space-between; gap: 1rem; margin-top: 1.15rem; padding-top: 1rem; border-top: 1px solid #e8efed; }
  .template-form-footer > span { color: #8a9998; font-size: .68rem; }
  .template-form-footer > span i { margin-right: .25rem; color: #57a77b; }
  .template-save-button { padding: .65rem 1rem; border: 0; border-radius: .6rem; background: linear-gradient(135deg, #21b95a, #138a43); box-shadow: 0 7px 16px rgba(25, 153, 75, .2); color: #fff; font-size: .78rem; font-weight: 750; }
  .template-save-button:hover, .template-save-button:focus { color: #fff; box-shadow: 0 9px 20px rgba(25, 153, 75, .28); transform: translateY(-1px); }
  .template-save-button i { margin-right: .35rem; }
  .template-preview-card { position: sticky; top: 1rem; padding: 1.25rem; }
  .template-preview-heading { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem; }
  .template-live-badge { padding: .35rem .55rem; border-radius: 999px; background: #edf7f2; color: #39805e; font-size: .64rem; font-weight: 750; }
  .template-live-badge i { display: inline-block; width: 6px; height: 6px; margin-right: .3rem; border-radius: 50%; background: #25b961; box-shadow: 0 0 0 3px rgba(37, 185, 97, .13); }
  .whatsapp-phone { overflow: hidden; border: 5px solid #243637; border-radius: 1.15rem; background: #efeae2; box-shadow: 0 16px 30px rgba(29, 52, 50, .14); }
  .whatsapp-phone__bar { display: flex; min-height: 58px; align-items: center; gap: .55rem; padding: .55rem .65rem; background: #075e54; color: #fff; }
  .whatsapp-phone__back { font-size: .68rem; }
  .whatsapp-phone__avatar { display: grid; width: 34px; height: 34px; flex: 0 0 34px; place-items: center; border-radius: 50%; background: #dcebe7; color: #39756d; font-size: .72rem; }
  .whatsapp-phone__contact { min-width: 0; flex: 1; }
  .whatsapp-phone__contact strong, .whatsapp-phone__contact small { display: block; }
  .whatsapp-phone__contact strong { overflow: hidden; font-size: .73rem; text-overflow: ellipsis; white-space: nowrap; }
  .whatsapp-phone__contact small { color: rgba(255,255,255,.72); font-size: .58rem; }
  .whatsapp-phone__bar > i { margin-left: .35rem; font-size: .72rem; }
  .whatsapp-phone__chat { display: flex; min-height: 350px; flex-direction: column; align-items: flex-end; padding: .8rem; background-color: #efeae2; background-image: radial-gradient(rgba(76, 104, 93, .08) .7px, transparent .7px); background-size: 11px 11px; }
  .whatsapp-phone__date { align-self: center; margin-bottom: .8rem; padding: .25rem .55rem; border-radius: .35rem; background: #dce9e6; color: #657b76; font-size: .56rem; box-shadow: 0 1px 2px rgba(0,0,0,.07); }
  .whatsapp-message { position: relative; width: 88%; padding: .65rem .7rem 1.1rem; border-radius: .55rem 0 .55rem .55rem; background: #d9fdd3; box-shadow: 0 1px 2px rgba(0,0,0,.09); color: #283937; font-size: .72rem; line-height: 1.55; }
  .whatsapp-message::after { position: absolute; top: 0; right: -7px; border-width: 0 0 8px 8px; border-style: solid; border-color: transparent transparent transparent #d9fdd3; content: ''; }
  #messagePreview { display: block; overflow-wrap: anywhere; white-space: pre-wrap; }
  .whatsapp-message > small { position: absolute; right: .55rem; bottom: .3rem; color: #758681; font-size: .53rem; }
  .whatsapp-message > small i { color: #55a7c8; }
  .whatsapp-phone__composer { display: flex; align-items: center; gap: .55rem; padding: .5rem; background: #f1f3f2; color: #82908d; font-size: .7rem; }
  .whatsapp-phone__composer span { flex: 1; padding: .5rem .65rem; border-radius: 999px; background: #fff; }
  .whatsapp-phone__composer span i { margin-right: .35rem; }
  .whatsapp-phone__composer > i { display: grid; width: 30px; height: 30px; place-items: center; border-radius: 50%; background: #128c7e; color: #fff; }
  .template-preview-note { margin: .9rem .15rem 0; color: #7f908e; font-size: .68rem; line-height: 1.5; }
  .template-preview-note i { margin-right: .3rem; color: #3ca66a; }
  @media (max-width: 991.98px) {
    .template-workspace { grid-template-columns: 1fr; }
    .template-preview-card { position: static; }
    .whatsapp-phone { max-width: 440px; margin: 0 auto; }
  }
  @media (max-width: 575.98px) {
    .template-card-header, .template-editor-card form, .template-preview-card { padding: 1rem; }
    .template-placeholder-list { grid-template-columns: 1fr; }
    .template-placeholders__heading, .template-form-footer { align-items: flex-start; flex-direction: column; }
    .template-save-button { width: 100%; }
    .whatsapp-phone__chat { min-height: 300px; }
  }
</style>
@endsection

@section('js')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    var message = document.getElementById('message');
    var preview = document.getElementById('messagePreview');
    var count = document.getElementById('messageCount');
    var sampleValues = {
      '@verbatim{{client_name}}@endverbatim': 'Alex Morgan',
      '@verbatim{{url}}@endverbatim': 'photorent.com/gallery/summer-day',
      '@verbatim{{client_password}}@endverbatim': 'alex2026',
      '@verbatim{{guest_password}}@endverbatim': 'guest2026'
    };

    function refreshPreview() {
      var value = message.value;
      Object.keys(sampleValues).forEach(function (token) {
        value = value.split(token).join(sampleValues[token]);
      });
      preview.textContent = value || 'Your WhatsApp message will appear here.';
      count.textContent = message.value.length.toLocaleString() + ' / 4,000';
    }

    document.querySelectorAll('.template-placeholder').forEach(function (button) {
      button.addEventListener('click', function () {
        var token = button.dataset.token;
        var start = message.selectionStart;
        var end = message.selectionEnd;
        var prefix = start > 0 && !/\s/.test(message.value.charAt(start - 1)) ? ' ' : '';
        message.setRangeText(prefix + token, start, end, 'end');
        message.focus();
        refreshPreview();
      });
    });

    message.addEventListener('input', refreshPreview);
    refreshPreview();
  });
</script>
@endsection
