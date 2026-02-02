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
										<h3 class="text-white">{{$studentCount}}</h3>
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
										<p class="mb-1">Registrations</p>
										<h3 class="text-white">{{$registrationCount}}</h3>
										<div class="progress mb-2 bg-white">
                                            <div class="progress-bar progress-animated bg-white" style="width: 50%"></div>
                                        </div>
										<small>50% Payments completed</small>
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
										<i class="la la-graduation-cap"></i>
									</span>
									<div class="media-body text-white">
										<p class="mb-1">Total Course</p>
										<h3 class="text-white">28</h3>
										<div class="progress mb-2 bg-white">
                                            <div class="progress-bar progress-animated bg-white" style="width: 76%"></div>
                                        </div>
										<small>76% Increase in 20 Days</small>
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
										<p class="mb-1">Completed Payments</p>
										<h3 class="text-white">{{$completedPaymentsCount}}</h3>
										<div class="progress mb-2 bg-white">
                                            <div class="progress-bar progress-animated bg-white" style="width: {{$completedPaymentsPercentage}}%"></div>
                                        </div>
										<small>{{number_format($completedPaymentsPercentage,2)}}% paid from {{$allPaymentsCount}}</small>
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
								<div id="morris_donught_2" class="morris_chart_height ltr" style="height: 300px !important;"></div>
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