@extends('layouts.public')

@section('title', 'Request a paint booking')

@section('content')
  <div class="nk-block-head wide-md mx-auto text-center">
    <div class="nk-block-head-content">
      <a href="{{ route('bookings.home') }}" class="ck-back-link">← Back to Cat Kereta</a>
      <h1 class="nk-block-title page-title">Book a paint service</h1>
      <div class="nk-block-des">
        <p>Tell us about your car and the paint work you need. Our team will review your request and contact you to confirm a date.</p>
      </div>
    </div>
  </div>

  <div class="nk-block">
    <div class="card card-bordered">
      <div class="card-inner card-inner-lg">
        <form method="POST" action="{{ route('bookings.store') }}" class="gy-3">
          @csrf
          <h5 class="title">Your contact details</h5>
          <div class="row g-3">
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label" for="customer_name">Name</label>
                <div class="form-control-wrap">
                  <input id="customer_name" name="customer_name" class="form-control @error('customer_name') is-invalid @enderror" value="{{ old('customer_name') }}" autocomplete="name" required />
                  @error('customer_name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label" for="customer_email">Email</label>
                <div class="form-control-wrap">
                  <input id="customer_email" name="customer_email" type="email" class="form-control @error('customer_email') is-invalid @enderror" value="{{ old('customer_email') }}" autocomplete="email" required />
                  @error('customer_email')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label" for="customer_phone">Phone</label>
                <div class="form-control-wrap">
                  <input id="customer_phone" name="customer_phone" type="tel" class="form-control @error('customer_phone') is-invalid @enderror" value="{{ old('customer_phone') }}" autocomplete="tel" required />
                  @error('customer_phone')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
              </div>
            </div>
          </div>

          <hr class="preview-hr" />
          <h5 class="title">Your vehicle</h5>
          <div class="row g-3">
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label" for="vehicle_make">Make</label>
                <div class="form-control-wrap">
                  <input id="vehicle_make" name="vehicle_make" list="vehicle-makes" class="form-control @error('vehicle_make') is-invalid @enderror" value="{{ old('vehicle_make') }}" placeholder="Start typing, e.g. Toyota" autocomplete="off" aria-describedby="vehicle-make-help" required />
                  <datalist id="vehicle-makes">
                    <option value="Perodua"></option>
                    <option value="Proton"></option>
                    <option value="Toyota"></option>
                    <option value="Honda"></option>
                    <option value="Nissan"></option>
                    <option value="Mazda"></option>
                    <option value="Mitsubishi"></option>
                    <option value="Suzuki"></option>
                    <option value="Isuzu"></option>
                    <option value="Hyundai"></option>
                    <option value="Kia"></option>
                    <option value="Mercedes-Benz"></option>
                    <option value="BMW"></option>
                    <option value="Other"></option>
                  </datalist>
                  @error('vehicle_make')<span class="invalid-feedback">{{ $message }}</span>@enderror
                  <small id="vehicle-make-help" class="form-text text-soft">Choose a suggestion or type another make.</small>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label" for="vehicle_model">Model</label>
                <div class="form-control-wrap">
                  <input id="vehicle_model" name="vehicle_model" list="vehicle-models" class="form-control @error('vehicle_model') is-invalid @enderror" value="{{ old('vehicle_model') }}" placeholder="Choose a make first" autocomplete="off" aria-describedby="vehicle-model-help" required />
                  <datalist id="vehicle-models"></datalist>
                  @error('vehicle_model')<span class="invalid-feedback">{{ $message }}</span>@enderror
                  <small id="vehicle-model-help" class="form-text text-soft">Suggestions update based on the make. You can also type a model.</small>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label" for="vehicle_year">Year <span class="text-soft">(optional)</span></label>
                <div class="form-control-wrap">
                  <input id="vehicle_year" name="vehicle_year" type="number" min="1900" max="{{ now()->year + 1 }}" class="form-control @error('vehicle_year') is-invalid @enderror" value="{{ old('vehicle_year') }}" />
                  @error('vehicle_year')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label" for="vehicle_color">Current color <span class="text-soft">(optional)</span></label>
                <div class="form-control-wrap">
                  <input id="vehicle_color" name="vehicle_color" class="form-control @error('vehicle_color') is-invalid @enderror" value="{{ old('vehicle_color') }}" />
                  @error('vehicle_color')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label" for="service_type">Paint service</label>
                <div class="form-control-wrap">
                  <select id="service_type" name="service_type" class="form-select @error('service_type') is-invalid @enderror" required>
                    <option value="">Choose a service</option>
                    @foreach($services as $value => $label)
                      <option value="{{ $value }}" @selected(old('service_type') === $value)>{{ $label }}</option>
                    @endforeach
                  </select>
                  @error('service_type')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label" for="preferred_date">Preferred date <span class="text-soft">(optional)</span></label>
                <div class="form-control-wrap">
                  <input id="preferred_date" name="preferred_date" type="date" min="{{ now()->toDateString() }}" class="form-control @error('preferred_date') is-invalid @enderror" value="{{ old('preferred_date') }}" />
                  @error('preferred_date')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label" for="description">Describe the work</label>
                <div class="form-control-wrap">
                  <textarea id="description" name="description" rows="3" maxlength="3000" class="form-control @error('description') is-invalid @enderror" placeholder="Tell us what needs painting or repairing.">{{ old('description') }}</textarea>
                  @error('description')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
              </div>
            </div>
          </div>
          <div class="form-group mt-4">
            <button type="submit" class="btn btn-lg btn-primary">Request a booking</button>
          </div>
          <p class="text-soft">This is a booking request, not a confirmed appointment. We’ll contact you to arrange the visit.</p>
        </form>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script src="{{ asset('assets/js/vehicle-suggestions.js') }}" defer></script>
@endpush
