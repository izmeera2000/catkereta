@extends('layouts.app')

@section('title', 'Manage booking')
@section('page_title', 'Manage booking')

@section('breadcrumbs')
  <li class="breadcrumb-item"><a href="{{ route('staff.dashboard') }}">Bookings</a></li>
  <li class="breadcrumb-item active" aria-current="page">{{ $booking->customer_name }}</li>
@endsection

@section('content')
  @if(session('success'))
    <div class="alert alert-success alert-icon"><em class="icon ni ni-check-circle"></em>{{ session('success') }}</div>
  @endif
  @if($errors->any())
    <div class="alert alert-danger" role="alert">{{ $errors->first() }}</div>
  @endif

  <div class="nk-block">
    <div class="row g-gs">
      <div class="col-lg-7">
        <div class="card card-bordered">
          <div class="card-inner">
            <h5 class="title">Customer and vehicle</h5>
            <dl class="row gy-2 mt-2">
              <dt class="col-sm-4">Customer</dt><dd class="col-sm-8">{{ $booking->customer_name }}</dd>
              <dt class="col-sm-4">Email</dt><dd class="col-sm-8"><a href="mailto:{{ $booking->customer_email }}">{{ $booking->customer_email }}</a></dd>
              <dt class="col-sm-4">Phone</dt><dd class="col-sm-8"><a href="tel:{{ $booking->customer_phone }}">{{ $booking->customer_phone }}</a></dd>
              <dt class="col-sm-4">Vehicle</dt><dd class="col-sm-8">{{ $booking->vehicle_year ? $booking->vehicle_year.' ' : '' }}{{ $booking->vehicle_make }} {{ $booking->vehicle_model }}</dd>
              <dt class="col-sm-4">Current color</dt><dd class="col-sm-8">{{ $booking->vehicle_color ?: 'Not specified' }}</dd>
              <dt class="col-sm-4">Paint service</dt><dd class="col-sm-8">{{ \App\Models\PaintBooking::SERVICES[$booking->service_type] ?? $booking->service_type }}</dd>
              <dt class="col-sm-4">Preferred date</dt><dd class="col-sm-8">{{ $booking->preferred_date?->format('F j, Y') ?? 'Not specified' }}</dd>
              <dt class="col-sm-4">Description</dt><dd class="col-sm-8">{{ $booking->description ?: 'No additional details.' }}</dd>
            </dl>
            <a href="{{ \Illuminate\Support\Facades\URL::signedRoute('bookings.show', ['booking' => $booking->public_id], null, false) }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-primary">Open customer progress page</a>
          </div>
        </div>
      </div>
      <div class="col-lg-5">
        <div class="card card-bordered">
          <div class="card-inner">
            <h5 class="title">Update status</h5>
            <form method="POST" action="{{ route('staff.bookings.update', $booking) }}" class="mt-3">
              @csrf
              @method('PATCH')
              <div class="form-group">
                <label class="form-label" for="status">Booking status</label>
                <div class="form-control-wrap">
                  <select id="status" name="status" class="form-select" required>
                    @foreach($statuses as $value => $label)
                      <option value="{{ $value }}" @selected(old('status', $booking->status) === $value)>{{ $label }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <button type="submit" class="btn btn-primary">Save status</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="nk-block">
    <div class="nk-block-head nk-block-head-sm">
      <h5 class="nk-block-title">Before, during and after photos</h5>
      <div class="nk-block-des"><p>Images are kept in private storage and shown only on the customer’s private progress page.</p></div>
    </div>
    <div class="row g-gs">
      @foreach($stages as $stage => $label)
        <div class="col-lg-4">
          <div class="card card-bordered h-100">
            <div class="card-inner">
              <h6 class="title">{{ $label }}</h6>
              @foreach($booking->photos->where('stage', $stage) as $photo)
                <div class="mb-3">
                  <img
                    class="img-fluid rounded"
                    src="{{ \Illuminate\Support\Facades\URL::signedRoute('bookings.photos.show', ['booking' => $booking->public_id, 'photo' => $photo->id], null, false) }}"
                    alt="{{ $photo->caption ?: $label.' paint progress photo' }}"
                    loading="lazy"
                  />
                  @if($photo->caption)<p class="text-soft mt-1">{{ $photo->caption }}</p>@endif
                </div>
              @endforeach
              <form method="POST" action="{{ route('staff.bookings.photos.store', $booking) }}" enctype="multipart/form-data" class="mt-3">
                @csrf
                <input type="hidden" name="stage" value="{{ $stage }}" />
                <div class="form-group">
                  <label class="form-label" for="photo-{{ $stage }}">Add {{ strtolower($label) }} photo</label>
                  <input id="photo-{{ $stage }}" name="photo" type="file" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" class="form-control" required />
                  <span class="form-note">JPG, PNG or WebP; maximum 5 MB. Photos are compressed when possible.</span>
                </div>
                <div class="form-group">
                  <label class="form-label" for="caption-{{ $stage }}">Caption <span class="text-soft">(optional)</span></label>
                  <input id="caption-{{ $stage }}" name="caption" maxlength="255" class="form-control" />
                </div>
                <button type="submit" class="btn btn-sm btn-primary">Upload {{ strtolower($label) }} photo</button>
              </form>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
@endsection
