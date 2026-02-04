@extends('admin.layouts.admin')

@section('content')
	<div class="row">
		<div class="col-xl-3 col-xxl-3 col-sm-6">
			<div class="widget-stat card bg-primary">
				<div class="card-body">
					<div class="media">
						<span class="me-3">
							<i class="la la-users"></i>
						</span>
						<div class="media-body text-white">
							<p class="mb-1">Total Students</p>
							<h3 class="text-white">{{$registrationCount}} / {{$studentCount}}</h3>
							<div class="progress mb-2 bg-white">
								<div class="progress-bar progress-animated bg-white" style="width: {{$registeredPercentage}}%"></div>
							</div>
							<small>{{number_format($registeredPercentage, 2)}}% Registered</small>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-3 col-xxl-3 col-sm-6">
			<div class="widget-stat card bg-secondary">
				<div class="card-body">
					<div class="media">
						<span class="me-3">
							<i class="la la-user"></i>
						</span>
						<div class="media-body text-white">
							<p class="mb-1">Seats Usage</p>
							<h3 class="text-white">{{$seatUsage['used_seats']}} / {{$seatUsage['max_seats']}}</h3>
							<div class="progress mb-2 bg-white">
								<div class="progress-bar progress-animated bg-white" style="width: {{$seatUsage['percentage']}}%"></div>
							</div>
							<small>{{$seatUsage['percentage']}}% Booked</small>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-3 col-xxl-3 col-sm-6">
			<div class="widget-stat card bg-primary">
				<div class="card-body">
					<div class="media">
						<span class="me-3">
							<i class="la la-chair"></i>
						</span>
						<div class="media-body text-white">
							<p class="mb-1">Additional Seats</p>
							<h3 class="text-white">{{$additionalSeatUsage['used_seats']}} / {{$additionalSeatUsage['max_seats']}}</h3>
							<div class="progress mb-2 bg-white">
								<div class="progress-bar progress-animated bg-white" style="width: {{$additionalSeatUsage['percentage']}}%"></div>
							</div>
							<small>{{$additionalSeatUsage['percentage']}}% Booked</small>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-3 col-xxl-3 col-sm-6">
			<div class="widget-stat card bg-secondary">
				<div class="card-body">
					<div class="media">
						<span class="me-3">
							<i class="la la-dollar"></i>
						</span>
						<div class="media-body text-white">
							<p class="mb-1">Payment Status</p>
							<h3 class="text-white">LKR {{$paymentStats['total']}}</h3>
							<div class="progress mb-2 bg-white">
								<div class="progress-bar progress-animated bg-white" style="width: {{$paymentStats['percentage']}}%"></div>
							</div>
							<small>{{$paymentStats['percentage']}}% ({{$paymentStats['paid']}}) paid [{{$paymentStats['due']}}]</small>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-6 col-xxl-6 col-lg-12 col-sm-12">
			<div class="card">
				<div class="card-header">
					<h3 class="card-title">Registrations</h3>
				</div>
				<div class="card-body">
					<div id="morris_bar_stalked" class="morris_chart_height ltr" style="height: 300px !important;"></div>
				</div>
			</div>
		</div>
		<div class="col-xl-3 col-xxl-3 col-sm-6">
			
			<div class="card">
				<div class="card-header">
					<h3 class="card-title">Course Comparison</h3>
				</div>
				<div class="card-body">
					<div id="morris_donught_x" class="morris_chart_height ltr" style="height: 300px !important;"></div>
				</div>
			</div>
		</div>
		<div class="col-xl-3 col-xxl-3 col-sm-6">
			<div class="card">
				<div class="card-header">
					<h3 class="card-title">University Survey</h3>
				</div>
				<div class="card-body">
					<div id="morris_area" class="morris_chart_height ltr" style="height: 300px !important;"></div>
				</div>
			</div>
		</div>

		<div class="col-xl-12 col-xxl-12 col-lg-12 col-md-12 col-sm-12">
			<div class="card">
				<div class="card-header">
					<h4 class="card-title">Recent Registrations</h4>
				</div>
				<div class="card-body pt-2">
					<div class="table-responsive recentOrderTable">
						<table class="display responsive nowrap w-100">
							<thead>
								<tr>
									<th>ID</th>
									<th>Student ID</th>
									<th>Full Name</th>
									<th>Program(s)</th>
									<th>Batch</th>
									<th>Status</th>
									<th>Phone</th>
									<th>Payments</th>
								</tr>
							</thead>
							<tbody>
								
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@push('scripts')

<script>
    var courseData = [
        @foreach($groups as $programs => $count)
            {
                label: "{{ $programs }}",
                value: {{ $count }}
            },
        @endforeach
    ];
</script>

<script>
    Morris.Donut({
        element: 'morris_donught_x',
        data: courseData,
        resize: true,
        formatter: function (value) {
            return value + " Students";
        }
    });
</script>

@endpush
