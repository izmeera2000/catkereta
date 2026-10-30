@extends('layouts.app')

@section('title', 'Paint bookings')
@section('page_title', 'Paint bookings')
@section('page_description', 'Review customer requests and manage paint-work progress.')

@section('breadcrumbs')
  <li class="breadcrumb-item active" aria-current="page">Bookings</li>
@endsection

@section('content')
  <div class="nk-block">
    <div class="row g-gs">
      @foreach($statuses as $status => $label)
        <div class="col-6 col-lg">
          <div class="card card-bordered">
            <div class="card-inner">
              <span class="sub-title">{{ $label }}</span>
              <div class="amount">{{ $counts[$status] ?? 0 }}</div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>

  <div class="nk-block">
    <div class="card card-bordered card-stretch">
      <div class="card-inner-group">
        <div class="card-inner">
          <h5 class="card-title">Booking requests</h5>
        </div>
        <div class="card-inner p-0">
          <div class="table-responsive">
            <table class="table table-tranx">
              <thead>
                <tr>
                  <th>Customer</th>
                  <th>Vehicle</th>
                  <th>Paint service</th>
                  <th>Preferred date</th>
                  <th>Status</th>
                  <th><span class="d-none d-sm-inline">Details</span></th>
                </tr>
              </thead>
              <tbody>
                @forelse($bookings as $booking)
                  <tr>
                    <td>
                      <span class="tb-lead">{{ $booking->customer_name }}</span>
                      <span class="d-block text-soft">{{ $booking->customer_email }}</span>
                    </td>
                    <td>{{ $booking->vehicle_year ? $booking->vehicle_year.' ' : '' }}{{ $booking->vehicle_make }} {{ $booking->vehicle_model }}</td>
                    <td>{{ \App\Models\PaintBooking::SERVICES[$booking->service_type] ?? $booking->service_type }}</td>
                    <td>{{ $booking->preferred_date?->format('M j, Y') ?? 'Not specified' }}</td>
                    <td>{{ $statuses[$booking->status] ?? $booking->status }}</td>
                    <td><a href="{{ route('staff.bookings.show', $booking) }}" class="btn btn-sm btn-outline-primary">Manage</a></td>
                  </tr>
                @empty
                  <tr><td colspan="6" class="text-center p-4">No booking requests yet.</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
        @if($bookings->hasPages())
          <div class="card-inner">{{ $bookings->links() }}</div>
        @endif
      </div>
    </div>
  </div>
@endsection
