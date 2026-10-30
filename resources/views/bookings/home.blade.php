@extends('layouts.public')

@section('title', 'Automotive paint services')
@section('meta_description', 'Book an automotive paint service or track your Cat Kereta booking.')

@section('content')
  <section class="ck-hero">
    <div class="ck-hero-copy">
      <span class="ck-eyebrow">Automotive paint services</span>
      <h1>Bring your paintwork back to life.</h1>
      <p>From scratches to a full repaint, tell us what your car needs. We’ll review your request and contact you to arrange a visit.</p>
      <div class="ck-hero-actions">
        <a href="{{ route('bookings.create') }}" class="btn btn-lg btn-primary">Book a service</a>
        <a href="{{ route('bookings.track.create') }}" class="btn btn-lg btn-outline-primary">Track a service</a>
      </div>
    </div>
    <aside class="ck-next-steps" aria-label="What happens next">
      <span class="ck-eyebrow">Simple from start to finish</span>
      <ol>
        <li><span>1</span><div><strong>Tell us about your car</strong><p>Choose a service and share a few details.</p></div></li>
        <li><span>2</span><div><strong>We’ll get in touch</strong><p>Our team will review your request and confirm a date.</p></div></li>
        <li><span>3</span><div><strong>Follow your service</strong><p>Use your service number to check updates and photos.</p></div></li>
      </ol>
    </aside>
  </section>

  <section class="ck-action-section" aria-labelledby="get-started-title">
    <div class="ck-section-heading">
      <span class="ck-eyebrow">How can we help?</span>
      <h2 id="get-started-title">Choose what you’d like to do</h2>
    </div>
    <div class="row g-4">
      <div class="col-md-6">
        <article class="card card-bordered ck-action-card h-100">
          <div class="card-inner">
            <span class="ck-card-number" aria-hidden="true">01</span>
            <h3>Book a paint service</h3>
            <p>Describe the paintwork you need. We’ll review your request and contact you to arrange a visit.</p>
            <a href="{{ route('bookings.create') }}" class="btn btn-primary">Start a booking</a>
          </div>
        </article>
      </div>
      <div class="col-md-6">
        <article class="card card-bordered ck-action-card h-100">
          <div class="card-inner">
            <span class="ck-card-number" aria-hidden="true">02</span>
            <h3>Track a service</h3>
            <p>Already booked with us? Enter the service number we gave you to see the latest status and photos.</p>
            <a href="{{ route('bookings.track.create') }}" class="btn btn-outline-primary">Enter service number</a>
          </div>
        </article>
      </div>
    </div>
  </section>

  <section class="ck-services" aria-labelledby="services-title">
    <div class="ck-section-heading">
      <span class="ck-eyebrow">Our services</span>
      <h2 id="services-title">Paint care for your car</h2>
    </div>
    <ul class="ck-service-list">
      @foreach($services as $service)
        <li>{{ $service }}</li>
      @endforeach
    </ul>
  </section>
@endsection
