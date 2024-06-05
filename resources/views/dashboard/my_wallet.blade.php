{{-- Extends layout --}}
@extends('layout.default')



{{-- Content --}}
@section('content')
            <!-- row -->
			<div class="container-fluid">
                <div class="row">
					<div class="col-xl-6">
						<div class="row">
							<div class="col-xl-12">
								<div class="card">
									<div class="card-header d-block d-sm-flex border-0 pb-0">
										<div>
											<h4 class="fs-20 text-black">My Cards</h4>
											<p class="mb-0 fs-13">Lorem ipsum dolor sit amet, consectetur</p>
										</div>
										<div class="card-action card-tabs mt-3 mt-sm-0">
											<ul class="nav nav-tabs tabs-lg" role="tablist">
												<li class="nav-item">
													<a class="nav-link active px-3 wspace-no" data-toggle="tab" href="#Crypto-Wallet" role="tab">
														Crypto Wallet
													</a>
												</li>
												<li class="nav-item">
													<a class="nav-link px-3 wspace-no" data-toggle="tab" href="#Bank-Wallet" role="tab">
														Bank Wallet
													</a>
												</li>
											</ul>
										</div>
									</div>
									<div class="card-body border-bottom tab-content">
										<div class="tab-pane active show fade" id="Bank-Wallet" role="tabpanel">
											<div class="owl-bank-wallet owl-carousel owl-loaded owl-drag mb-4">
												<div class="items">
													<img class="mw-100" src="{{ asset('images/card/card1.jpg') }}" alt="">
												</div>
												<div class="items">
													<img  class="mw-100" src="{{ asset('images/card/card2.jpg') }}" alt="">
												</div>
												<div class="items">
													<img  class="mw-100" src="{{ asset('images/card/card3.jpg') }}" alt="">
												</div>
											</div>
											<div class="d-flex align-items-center">
												<p class="text-black">Monthly Limits</p>
												<h3 class="fs-20 font-w600 text-black ml-auto">$2000 of $10000</h3>
											</div>
											<div class="progress rounded-0">
												<div class="progress-bar rounded-0 bg-primary progress-animated" style="width: 75%; height:12px;" role="progressbar">
													<span class="sr-only">60% Complete</span>
												</div>
											</div>
										</div>
										<div class="tab-pane" id="Crypto-Wallet" role="tabpanel">
											<div class="owl-bank-wallet owl-carousel owl-loaded owl-drag mb-4">
												<div class="items">
													<img class="mw-100" src="{{ asset('images/card/card1.jpg') }}" alt="">
												</div>
												<div class="items">
													<img  class="mw-100" src="{{ asset('images/card/card2.jpg') }}" alt="">
												</div>
												<div class="items">
													<img  class="mw-100" src="{{ asset('images/card/card3.jpg') }}" alt="">
												</div>
											</div>
											<div class="d-flex align-items-center">
												<p class="text-black">Monthly Limits</p>
												<h3 class="fs-20 font-w600 text-black ml-auto">$2000 of $10000</h3>
											</div>
											<div class="progress rounded-0">
												<div class="progress-bar rounded-0 bg-primary progress-animated" style="width: 75%; height:12px;" role="progressbar">
													<span class="sr-only">60% Complete</span>
												</div>
											</div>
										</div>
									</div>
									<div class="card-body">
										<div class="row">
											<div class="col-sm-5 mb-4">
												<p class="mb-2">Card Name</p>
												<h4 class="text-black">Main Balance</h4>
											</div>
											<div class="col-sm-7 mb-4">
												<p class="mb-2">Bank Name</p>
												<h4 class="text-black">ABC Center Bank</h4>
											</div>
											<div class="col-sm-5 mb-4">
												<p class="mb-2">Valid Date</p>
												<h4 class="text-black">08/21</h4>
											</div>
											<div class="col-sm-7 mb-4">
												<p class="mb-2">Card Holder</p>
												<h4 class="text-black">Thomas Aldox</h4>
											</div>
											<div class="col-sm-5">
												<p class="mb-2">Card Number</p>
												<h4 class="text-black">**** **** **** 1234</h4>
											</div>
											<div class="col-sm-7">
												<a class="btn btn-outline-primary rounded-0" href="javascript:void(0);">Generate Number</a>
												<a class="btn btn-outline-primary rounded-0" href="javascript:void(0);">Edit</a>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xl-12">
								<div class="card">
									<div class="card-header border-0 pb-0">
										<div>
											<h4 class="fs-20 text-black">Transfer</h4>
											<p class="mb-0 fs-13">Lorem ipsum dolor sit amet, consectetur</p>
										</div>
									</div>
									<div class="card-body">
										<div class="basic-form">
											<form>
												<div class="form-group">
													<div class="input-group input-group-lg">
														<div class="input-group-prepend">
															<span class="input-group-text bg-white border rounded-0">Amount</span>
														</div>
														<input type="number" class="form-control rounded-0" placeholder="0,000000">
													</div>
												</div>
											</form>
										</div>
										<div class="d-flex justify-content-between align-items-center">
											<h4 class="fs-20 text-black mb-0">Recent Contacts</h4>
											<a href="javascript:void(0);" class="btn btn-link">View more</a>
										</div>
										<div class="testimonial-one-1 owl-right-nav owl-carousel owl-loaded owl-drag">
											<div class="items">
												<div>
													<img class="mb-3" src="{{ asset('images/profile/10.jpg') }}" alt="">
													<h5 class="text-black mb-0">Samuel</h5>
													<span class="fs-12">@sam224</span>
												</div>
											</div>
											<div class="items">
												<div>
													<img class="mb-3" src="{{ asset('images/profile/11.jpg') }}" alt="">
													<h5 class="text-black mb-0">Cindy</h5>
													<span class="fs-12">@cindyss</span>
												</div>
											</div>
											<div class="items">
												<div>
													<img class="mb-3" src="{{ asset('images/profile/12.jpg') }}" alt="">
													<h5 class="text-black mb-0">David</h5>
													<span class="fs-12">@davidxc</span>
												</div>
											</div>
											<div class="items">
												<div>
													<img class="mb-3" src="{{ asset('images/profile/13.jpg') }}" alt="">
													<h5 class="text-black mb-0">Martha </h5>
													<span class="fs-12">@marthaa</span>
												</div>
											</div>
											<div class="items">
												<div>
													<img class="mb-3" src="{{ asset('images/profile/14.jpg') }}" alt="">
													<h5 class="text-black mb-0">Olivia </h5>
													<span class="fs-12">@oliv62</span>
												</div>
											</div>
											<div class="items">
												<div>
													<img class="mb-3" src="{{ asset('images/profile/15.jpg') }}" alt="">
													<h5 class="text-black mb-0">Bella</h5>
													<span class="fs-12">@bellazz</span>
												</div>
											</div>
										</div>
									</div>
									<div class="card-footer border-0 pt-0">
										<div class="row align-items-center">
											<div class="col-md-7">
												<p class="mb-0 fs-14">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut</p>
											</div>
											<div class="col-md-5 text-right">
												<a href="javascript:void(0);" class="btn btn-primary d-block rounded-0 mt-3 mt-md-0">
												TRANSFER NOW
												</a>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-6">
						<div class="row">
							<div class="col-xl-12">
								<div class="card">
									<div class="card-body p-0">
										<div class="media p-4 top-up-bx col-md-6 align-items-center">
											<div class="media-body">
												<h3 class="fs-20 text-white">TOP UP</h3>
												<p class="text-white font-w200 mb-0 fs-14">Lorem ipsum dolor sit amet, consectetur adipis</p>
											</div>
											<a href="javascript:void(0);" class="icon-btn ml-3">
												<svg width="15" height="27" viewBox="0 0 15 27" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M5.9375 6.23198L5.9375 24.875C5.9375 25.6689 6.58107 26.3125 7.375 26.3125C8.16892 26.3125 8.8125 25.6689 8.8125 24.875L8.8125 6.23201L11.2311 8.66231L11.2311 8.66232C11.7911 9.22502 12.7013 9.22719 13.264 8.66716C13.8269 8.107 13.8288 7.1968 13.2689 6.6342L12.9145 6.98689L13.2689 6.63419L8.3939 1.73557L8.38872 1.73036L8.38704 1.72877C7.82626 1.17279 6.92186 1.17467 6.36301 1.72875L6.36136 1.73031L6.35609 1.73561L1.48109 6.63424L1.48108 6.63425C0.921124 7.19694 0.9232 8.10708 1.48597 8.66719C2.04868 9.22724 2.95884 9.22508 3.51889 8.66237L3.51891 8.66235L5.9375 6.23198Z" fill="#6418C3" stroke="#6418C3"/>
												</svg>
											</a>
										</div>
										<div class="media p-4 withdraw-bx col-md-6 align-items-center">
											<div class="media-body">
												<h3 class="fs-20 text-white">WITHDRAW</h3>
												<p class="text-white font-w200 mb-0 fs-14">Lorem ipsum dolor sit amet, consectetur adipis</p>
											</div>
											<a href="javascript:void(0);" class="icon-btn ml-3">
												<svg width="27" height="27" viewBox="0 0 15 27" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M5.9375 20.768L5.9375 2.125C5.9375 1.33108 6.58107 0.6875 7.375 0.6875C8.16892 0.6875 8.8125 1.33108 8.8125 2.125L8.8125 20.768L11.2311 18.3377L11.2311 18.3377C11.7911 17.775 12.7013 17.7728 13.264 18.3328C13.8269 18.893 13.8288 19.8032 13.2689 20.3658L12.9145 20.0131L13.2689 20.3658L8.3939 25.2644L8.38872 25.2696L8.38704 25.2712C7.82626 25.8272 6.92186 25.8253 6.36301 25.2712L6.36136 25.2697L6.35609 25.2644L1.48109 20.3658L1.48108 20.3658C0.921124 19.8031 0.9232 18.8929 1.48597 18.3328C2.04868 17.7728 2.95884 17.7749 3.51889 18.3376L3.51891 18.3377L5.9375 20.768Z" fill="#6418C3" stroke="#6418C3"/>
												</svg>
											</a>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xl-12">
								<div class="card">
									<div class="card-header border-0 pb-0 d-block d-sm-flex">
										<div>
											<h4 class="fs-20 text-black">Overview Balance</h4>
											<p class="mb-0 fs-13">Lorem ipsum dolor sit amet, consectetur</p>
										</div>
										<div class="dropdown custom-dropdown d-block mt-3 mt-sm-0">
											<div class="btn bgl-light d-flex align-items-center rounded-0 svg-btn " data-toggle="dropdown">
												<div class="text-left">
													<span class="d-block text-black">Weekly (2020)</span>
												</div>
												<i class="fa fa-angle-down scale5 text-primary ml-3"></i>
											</div>
											<div class="dropdown-menu dropdown-menu-right">
												<a class="dropdown-item" href="javascript:void(0);">4 June 2020 - 4 July 2020</a>
												<a class="dropdown-item" href="javascript:void(0);">5 july 2020 - 4 Aug 2020</a>
											</div>
										</div>
									</div>
									<div class="card-body">
										<canvas id="activity" class="chartjs"></canvas>
									</div>
								</div>
							</div>
							<div class="col-xl-12">
								<div class="card">
									<div class="card-header border-0 d-sm-flex d-block">
										<div>
											<h4 class="fs-20 text-black">Wallet Activity</h4>
											<p class="mb-0 fs-13">Lorem ipsum dolor sit amet, consectetur</p>
										</div>
										<div class="card-action card-tabs mt-3 mt-sm-0">
											<ul class="nav nav-tabs" role="tablist">
												<li class="nav-item">
													<a class="nav-link active" data-toggle="tab" href="#monthly" role="tab" aria-selected="true">
														Monthly	
													</a>
												</li>
												<li class="nav-item">
													<a class="nav-link" data-toggle="tab" href="#Weekly" role="tab" aria-selected="false">
														Weekly
													</a>
												</li>
												<li class="nav-item">
													<a class="nav-link" data-toggle="tab" href="#Today" role="tab" aria-selected="false">
														Today
													</a>
												</li>
											</ul>
										</div>
									</div>
									<div class="card-body p-0 tab-content card-table">
										<div class="tab-pane fade active show" id="monthly">
											<div class="table-responsive">
												<table class="table">
													<tbody>
														<tr>
															<td>
																<div class="media">
																	<span class="bgl-primary p-3 mr-3">
																		<svg width="27" height="27" viewBox="0 0 15 27" fill="none" xmlns="http://www.w3.org/2000/svg">
																			<path d="M5.9375 6.23199L5.9375 24.875C5.9375 25.6689 6.58107 26.3125 7.375 26.3125C8.16892 26.3125 8.8125 25.6689 8.8125 24.875L8.8125 6.23202L11.2311 8.66232L11.2311 8.66234C11.7911 9.22504 12.7013 9.2272 13.264 8.66717C13.8269 8.10701 13.8288 7.19681 13.2689 6.63421L12.9145 6.9869L13.2689 6.6342L8.3939 1.73558L8.38872 1.73037L8.38704 1.72878C7.82626 1.1728 6.92186 1.17468 6.36301 1.72877L6.36136 1.73033L6.35609 1.73563L1.48109 6.63425L1.48108 6.63426C0.921124 7.19695 0.9232 8.10709 1.48597 8.6672C2.04868 9.22725 2.95884 9.22509 3.51889 8.66238L3.51891 8.66236L5.9375 6.23199Z" fill="#6418C3" stroke="#6418C3"></path>
																		</svg>
																	</span>
																	<div class="media-body align-self-center">
																		<h5 class="font-w600 text-black">Topup</h5>
																		<p class="mb-0 fs-15">4/5/2020 06:24 AM</p>
																	</div>
																</div>
															</td>
															<td class="font-w600 text-center">+$5,553</td>
															<td><a class="btn-link text-success float-right" href="javascript:void(0);">COMPLETED</a></td>
														</tr>
														<tr>
															<td>
																<div class="media">
																	<span class="bgl-primary p-3 mr-3">
																		<svg width="27" height="27" viewBox="0 0 15 27" fill="none" xmlns="http://www.w3.org/2000/svg">
																			<path d="M5.9375 6.23199L5.9375 24.875C5.9375 25.6689 6.58107 26.3125 7.375 26.3125C8.16892 26.3125 8.8125 25.6689 8.8125 24.875L8.8125 6.23202L11.2311 8.66232L11.2311 8.66234C11.7911 9.22504 12.7013 9.2272 13.264 8.66717C13.8269 8.10701 13.8288 7.19681 13.2689 6.63421L12.9145 6.9869L13.2689 6.6342L8.3939 1.73558L8.38872 1.73037L8.38704 1.72878C7.82626 1.1728 6.92186 1.17468 6.36301 1.72877L6.36136 1.73033L6.35609 1.73563L1.48109 6.63425L1.48108 6.63426C0.921124 7.19695 0.9232 8.10709 1.48597 8.6672C2.04868 9.22725 2.95884 9.22509 3.51889 8.66238L3.51891 8.66236L5.9375 6.23199Z" fill="#6418C3" stroke="#6418C3"></path>
																		</svg>
																	</span>
																	<div class="media-body align-self-center">
																		<h5 class="font-w600 text-black">Topup</h5>
																		<p class="mb-0 fs-15">4/5/2020 06:24 AM</p>
																	</div>
																</div>
															</td>
															<td class="font-w600 text-center">+$5,553</td>
															<td><a class="btn-link text-dark float-right" href="javascript:void(0);">PENDING</a></td>
														</tr>
														<tr>
															<td>
																<div class="media">
																	<span class="bgl-primary p-3 mr-3">
																		<svg width="27" height="27" viewBox="0 0 15 27" fill="none" xmlns="http://www.w3.org/2000/svg">
																		<path d="M5.9375 20.768L5.9375 2.12498C5.9375 1.33106 6.58107 0.687485 7.375 0.687485C8.16892 0.687485 8.8125 1.33106 8.8125 2.12499L8.8125 20.768L11.2311 18.3377L11.2311 18.3377C11.7911 17.775 12.7013 17.7728 13.264 18.3328C13.8269 18.893 13.8288 19.8032 13.2689 20.3658L12.9145 20.0131L13.2689 20.3658L8.3939 25.2644L8.38872 25.2696L8.38704 25.2712C7.82626 25.8272 6.92186 25.8253 6.36301 25.2712L6.36136 25.2697L6.35609 25.2644L1.48109 20.3657L1.48108 20.3657C0.921124 19.803 0.9232 18.8929 1.48597 18.3328C2.04868 17.7727 2.95884 17.7749 3.51889 18.3376L3.51891 18.3376L5.9375 20.768Z" fill="#6418C3" stroke="#6418C3"></path></svg>
																	</span>
																	<div class="media-body align-self-center">
																		<h5 class="font-w600 text-black">Withdraw</h5>
																		<p class="mb-0 fs-15">4/5/2020 06:24 AM</p>
																	</div>
																</div>
															</td>
															<td class="font-w600 text-center">-$2,000</td>
															<td><a class="btn-link text-success float-right" href="javascript:void(0);">COMPLETED</a></td>
														</tr>
														<tr>
															<td>
																<div class="media">
																	<span class="bgl-primary p-3 mr-3">
																		<svg width="27" height="27" viewBox="0 0 15 27" fill="none" xmlns="http://www.w3.org/2000/svg">
																		<path d="M5.9375 20.768L5.9375 2.12498C5.9375 1.33106 6.58107 0.687485 7.375 0.687485C8.16892 0.687485 8.8125 1.33106 8.8125 2.12499L8.8125 20.768L11.2311 18.3377L11.2311 18.3377C11.7911 17.775 12.7013 17.7728 13.264 18.3328C13.8269 18.893 13.8288 19.8032 13.2689 20.3658L12.9145 20.0131L13.2689 20.3658L8.3939 25.2644L8.38872 25.2696L8.38704 25.2712C7.82626 25.8272 6.92186 25.8253 6.36301 25.2712L6.36136 25.2697L6.35609 25.2644L1.48109 20.3657L1.48108 20.3657C0.921124 19.803 0.9232 18.8929 1.48597 18.3328C2.04868 17.7727 2.95884 17.7749 3.51889 18.3376L3.51891 18.3376L5.9375 20.768Z" fill="#6418C3" stroke="#6418C3"></path></svg>
																	</span>
																	<div class="media-body align-self-center">
																		<h5 class="font-w600 text-black">Withdraw</h5>
																		<p class="mb-0 fs-15">4/5/2020 06:24 AM</p>
																	</div>
																</div>
															</td>
															<td class="font-w600 text-center">-$2,000</td>
															<td><a class="btn-link text-dark float-right" href="javascript:void(0);">PENDING</a></td>
														</tr>
														<tr>
															<td>
																<div class="media">
																	<span class="bgl-primary p-3 mr-3">
																		<svg width="27" height="27" viewBox="0 0 15 27" fill="none" xmlns="http://www.w3.org/2000/svg">
																			<path d="M5.9375 6.23199L5.9375 24.875C5.9375 25.6689 6.58107 26.3125 7.375 26.3125C8.16892 26.3125 8.8125 25.6689 8.8125 24.875L8.8125 6.23202L11.2311 8.66232L11.2311 8.66234C11.7911 9.22504 12.7013 9.2272 13.264 8.66717C13.8269 8.10701 13.8288 7.19681 13.2689 6.63421L12.9145 6.9869L13.2689 6.6342L8.3939 1.73558L8.38872 1.73037L8.38704 1.72878C7.82626 1.1728 6.92186 1.17468 6.36301 1.72877L6.36136 1.73033L6.35609 1.73563L1.48109 6.63425L1.48108 6.63426C0.921124 7.19695 0.9232 8.10709 1.48597 8.6672C2.04868 9.22725 2.95884 9.22509 3.51889 8.66238L3.51891 8.66236L5.9375 6.23199Z" fill="#6418C3" stroke="#6418C3"></path>
																		</svg>
																	</span>
																	<div class="media-body align-self-center">
																		<h5 class="font-w600 text-black">Topup</h5>
																		<p class="mb-0 fs-15">4/5/2020 06:24 AM</p>
																	</div>
																</div>
															</td>
															<td class="font-w600 text-center">+$5,553</td>
															<td><a class="btn-link text-success float-right" href="javascript:void(0);">COMPLETED</a></td>
														</tr>
														<tr>
															<td>
																<div class="media">
																	<span class="bgl-primary p-3 mr-3">
																		<svg width="27" height="27" viewBox="0 0 15 27" fill="none" xmlns="http://www.w3.org/2000/svg">
																		<path d="M5.9375 20.768L5.9375 2.12498C5.9375 1.33106 6.58107 0.687485 7.375 0.687485C8.16892 0.687485 8.8125 1.33106 8.8125 2.12499L8.8125 20.768L11.2311 18.3377L11.2311 18.3377C11.7911 17.775 12.7013 17.7728 13.264 18.3328C13.8269 18.893 13.8288 19.8032 13.2689 20.3658L12.9145 20.0131L13.2689 20.3658L8.3939 25.2644L8.38872 25.2696L8.38704 25.2712C7.82626 25.8272 6.92186 25.8253 6.36301 25.2712L6.36136 25.2697L6.35609 25.2644L1.48109 20.3657L1.48108 20.3657C0.921124 19.803 0.9232 18.8929 1.48597 18.3328C2.04868 17.7727 2.95884 17.7749 3.51889 18.3376L3.51891 18.3376L5.9375 20.768Z" fill="#6418C3" stroke="#6418C3"></path></svg>
																	</span>
																	<div class="media-body align-self-center">
																		<h5 class="font-w600 text-black">Withdraw</h5>
																		<p class="mb-0 fs-15">4/5/2020 06:24 AM</p>
																	</div>
																</div>
															</td>
															<td class="font-w600 text-center">-$2,000</td>
															<td><a class="btn-link text-danger float-right" href="javascript:void(0);">CANCELED</a></td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
										<div class="tab-pane" id="Weekly">
											<div class="table-responsive">
												<table class="table">
													<tbody>
														<tr>
															<td>
																<div class="media">
																	<span class="bgl-primary p-3 mr-3">
																		<svg width="27" height="27" viewBox="0 0 15 27" fill="none" xmlns="http://www.w3.org/2000/svg">
																		<path d="M5.9375 20.768L5.9375 2.12498C5.9375 1.33106 6.58107 0.687485 7.375 0.687485C8.16892 0.687485 8.8125 1.33106 8.8125 2.12499L8.8125 20.768L11.2311 18.3377L11.2311 18.3377C11.7911 17.775 12.7013 17.7728 13.264 18.3328C13.8269 18.893 13.8288 19.8032 13.2689 20.3658L12.9145 20.0131L13.2689 20.3658L8.3939 25.2644L8.38872 25.2696L8.38704 25.2712C7.82626 25.8272 6.92186 25.8253 6.36301 25.2712L6.36136 25.2697L6.35609 25.2644L1.48109 20.3657L1.48108 20.3657C0.921124 19.803 0.9232 18.8929 1.48597 18.3328C2.04868 17.7727 2.95884 17.7749 3.51889 18.3376L3.51891 18.3376L5.9375 20.768Z" fill="#6418C3" stroke="#6418C3"></path></svg>
																	</span>
																	<div class="media-body align-self-center">
																		<h5 class="font-w600 text-black">Withdraw</h5>
																		<p class="mb-0 fs-15">4/5/2020 06:24 AM</p>
																	</div>
																</div>
															</td>
															<td class="font-w600 text-center">-$2,000</td>
															<td><a class="btn-link text-success float-right" href="javascript:void(0);">COMPLETED</a></td>
														</tr>
														<tr>
															<td>
																<div class="media">
																	<span class="bgl-primary p-3 mr-3">
																		<svg width="27" height="27" viewBox="0 0 15 27" fill="none" xmlns="http://www.w3.org/2000/svg">
																		<path d="M5.9375 20.768L5.9375 2.12498C5.9375 1.33106 6.58107 0.687485 7.375 0.687485C8.16892 0.687485 8.8125 1.33106 8.8125 2.12499L8.8125 20.768L11.2311 18.3377L11.2311 18.3377C11.7911 17.775 12.7013 17.7728 13.264 18.3328C13.8269 18.893 13.8288 19.8032 13.2689 20.3658L12.9145 20.0131L13.2689 20.3658L8.3939 25.2644L8.38872 25.2696L8.38704 25.2712C7.82626 25.8272 6.92186 25.8253 6.36301 25.2712L6.36136 25.2697L6.35609 25.2644L1.48109 20.3657L1.48108 20.3657C0.921124 19.803 0.9232 18.8929 1.48597 18.3328C2.04868 17.7727 2.95884 17.7749 3.51889 18.3376L3.51891 18.3376L5.9375 20.768Z" fill="#6418C3" stroke="#6418C3"></path></svg>
																	</span>
																	<div class="media-body align-self-center">
																		<h5 class="font-w600 text-black">Withdraw</h5>
																		<p class="mb-0 fs-15">4/5/2020 06:24 AM</p>
																	</div>
																</div>
															</td>
															<td class="font-w600 text-center">-$2,000</td>
															<td><a class="btn-link text-dark float-right" href="javascript:void(0);">PENDING</a></td>
														</tr>
														<tr>
															<td>
																<div class="media">
																	<span class="bgl-primary p-3 mr-3">
																		<svg width="27" height="27" viewBox="0 0 15 27" fill="none" xmlns="http://www.w3.org/2000/svg">
																			<path d="M5.9375 6.23199L5.9375 24.875C5.9375 25.6689 6.58107 26.3125 7.375 26.3125C8.16892 26.3125 8.8125 25.6689 8.8125 24.875L8.8125 6.23202L11.2311 8.66232L11.2311 8.66234C11.7911 9.22504 12.7013 9.2272 13.264 8.66717C13.8269 8.10701 13.8288 7.19681 13.2689 6.63421L12.9145 6.9869L13.2689 6.6342L8.3939 1.73558L8.38872 1.73037L8.38704 1.72878C7.82626 1.1728 6.92186 1.17468 6.36301 1.72877L6.36136 1.73033L6.35609 1.73563L1.48109 6.63425L1.48108 6.63426C0.921124 7.19695 0.9232 8.10709 1.48597 8.6672C2.04868 9.22725 2.95884 9.22509 3.51889 8.66238L3.51891 8.66236L5.9375 6.23199Z" fill="#6418C3" stroke="#6418C3"></path>
																		</svg>
																	</span>
																	<div class="media-body align-self-center">
																		<h5 class="font-w600 text-black">Topup</h5>
																		<p class="mb-0 fs-15">4/5/2020 06:24 AM</p>
																	</div>
																</div>
															</td>
															<td class="font-w600 text-center">+$5,553</td>
															<td><a class="btn-link text-success float-right" href="javascript:void(0);">COMPLETED</a></td>
														</tr>
														<tr>
															<td>
																<div class="media">
																	<span class="bgl-primary p-3 mr-3">
																		<svg width="27" height="27" viewBox="0 0 15 27" fill="none" xmlns="http://www.w3.org/2000/svg">
																		<path d="M5.9375 20.768L5.9375 2.12498C5.9375 1.33106 6.58107 0.687485 7.375 0.687485C8.16892 0.687485 8.8125 1.33106 8.8125 2.12499L8.8125 20.768L11.2311 18.3377L11.2311 18.3377C11.7911 17.775 12.7013 17.7728 13.264 18.3328C13.8269 18.893 13.8288 19.8032 13.2689 20.3658L12.9145 20.0131L13.2689 20.3658L8.3939 25.2644L8.38872 25.2696L8.38704 25.2712C7.82626 25.8272 6.92186 25.8253 6.36301 25.2712L6.36136 25.2697L6.35609 25.2644L1.48109 20.3657L1.48108 20.3657C0.921124 19.803 0.9232 18.8929 1.48597 18.3328C2.04868 17.7727 2.95884 17.7749 3.51889 18.3376L3.51891 18.3376L5.9375 20.768Z" fill="#6418C3" stroke="#6418C3"></path></svg>
																	</span>
																	<div class="media-body align-self-center">
																		<h5 class="font-w600 text-black">Withdraw</h5>
																		<p class="mb-0 fs-15">4/5/2020 06:24 AM</p>
																	</div>
																</div>
															</td>
															<td class="font-w600 text-center">-$2,000</td>
															<td><a class="btn-link text-danger float-right" href="javascript:void(0);">CANCELED</a></td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
										<div class="tab-pane" id="Today">
											<div class="table-responsive">
												<table class="table">
													<tbody>
														<tr>
															<td>
																<div class="media">
																	<span class="bgl-primary p-3 mr-3">
																		<svg width="27" height="27" viewBox="0 0 15 27" fill="none" xmlns="http://www.w3.org/2000/svg">
																			<path d="M5.9375 6.23199L5.9375 24.875C5.9375 25.6689 6.58107 26.3125 7.375 26.3125C8.16892 26.3125 8.8125 25.6689 8.8125 24.875L8.8125 6.23202L11.2311 8.66232L11.2311 8.66234C11.7911 9.22504 12.7013 9.2272 13.264 8.66717C13.8269 8.10701 13.8288 7.19681 13.2689 6.63421L12.9145 6.9869L13.2689 6.6342L8.3939 1.73558L8.38872 1.73037L8.38704 1.72878C7.82626 1.1728 6.92186 1.17468 6.36301 1.72877L6.36136 1.73033L6.35609 1.73563L1.48109 6.63425L1.48108 6.63426C0.921124 7.19695 0.9232 8.10709 1.48597 8.6672C2.04868 9.22725 2.95884 9.22509 3.51889 8.66238L3.51891 8.66236L5.9375 6.23199Z" fill="#6418C3" stroke="#6418C3"></path>
																		</svg>
																	</span>
																	<div class="media-body align-self-center">
																		<h5 class="font-w600 text-black">Topup</h5>
																		<p class="mb-0 fs-15">4/5/2020 06:24 AM</p>
																	</div>
																</div>
															</td>
															<td class="font-w600 text-center">+$5,553</td>
															<td><a class="btn-link text-success float-right" href="javascript:void(0);">COMPLETED</a></td>
														</tr>
														<tr>
															<td>
																<div class="media">
																	<span class="bgl-primary p-3 mr-3">
																		<svg width="27" height="27" viewBox="0 0 15 27" fill="none" xmlns="http://www.w3.org/2000/svg">
																		<path d="M5.9375 20.768L5.9375 2.12498C5.9375 1.33106 6.58107 0.687485 7.375 0.687485C8.16892 0.687485 8.8125 1.33106 8.8125 2.12499L8.8125 20.768L11.2311 18.3377L11.2311 18.3377C11.7911 17.775 12.7013 17.7728 13.264 18.3328C13.8269 18.893 13.8288 19.8032 13.2689 20.3658L12.9145 20.0131L13.2689 20.3658L8.3939 25.2644L8.38872 25.2696L8.38704 25.2712C7.82626 25.8272 6.92186 25.8253 6.36301 25.2712L6.36136 25.2697L6.35609 25.2644L1.48109 20.3657L1.48108 20.3657C0.921124 19.803 0.9232 18.8929 1.48597 18.3328C2.04868 17.7727 2.95884 17.7749 3.51889 18.3376L3.51891 18.3376L5.9375 20.768Z" fill="#6418C3" stroke="#6418C3"></path></svg>
																	</span>
																	<div class="media-body align-self-center">
																		<h5 class="font-w600 text-black">Withdraw</h5>
																		<p class="mb-0 fs-15">4/5/2020 06:24 AM</p>
																	</div>
																</div>
															</td>
															<td class="font-w600 text-center">-$2,000</td>
															<td><a class="btn-link text-danger float-right" href="javascript:void(0);">CANCELED</a></td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
            </div>
			
@endsection			