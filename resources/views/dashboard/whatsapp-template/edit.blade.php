@extends('adminlte::page')

@section('title', 'WhatsApp Template')

@section('content')
<div class="container-fluid">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="page_header mb-0">WhatsApp Message Template</h4>
  </div>

  <div class="card card-outline card-success" style="max-width: 850px;">
    <div class="card-body">
      <p class="text-muted">This message is prepared when you choose <strong>Send via WhatsApp</strong> from a gallery. Use any of these placeholders:</p>
      <div class="mb-3">
        <code>@verbatim{{client_name}}@endverbatim</code> client name &nbsp;
        <code>@verbatim{{url}}@endverbatim</code> gallery link &nbsp;
        <code>@verbatim{{client_password}}@endverbatim</code> client password &nbsp;
        <code>@verbatim{{guest_password}}@endverbatim</code> guest password
      </div>
      <form method="POST" action="{{ route('dashboard.whatsapp-template.update') }}">
        @csrf
        @method('PUT')
        <div class="form-group">
          <label for="message">Message</label>
          <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="10" required>{{ old('message', $template->message) }}</textarea>
          @error('message')<span class="invalid-feedback">{{ $message }}</span>@enderror
        </div>
        <button class="btn btn-success" type="submit"><i class="fab fa-whatsapp"></i> Save template</button>
      </form>
    </div>
  </div>
</div>
@endsection
