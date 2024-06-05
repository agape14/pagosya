{{-- Extends layout --}}
@extends('layout.default')



{{-- Content --}}
@section('content')
            <!-- row -->
			<div class="container-fluid">
                <div class="row">
					<div class="col-xl-6 col-xxl-12">
						<div class="row">
							<div class="col-xl-12 col-lg-12">
								<div class="card mb-4 mb-xl-0">
									<div class="card-header border-0  pb-0">
										<div>
											<h4 class="text-black fs-20">My Profile</h4>
											<p class="fs-12 mb-0">Lorem ipsum dolor sit amet, consectetur</p>
										</div>
										<div class="dropdown custom-dropdown mb-0">
											<div class="btn sharp pr-0 tp-btn" data-toggle="dropdown">
												<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
														<path d="M12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13Z" stroke="#2E2E2E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
														<path d="M12 6C12.5523 6 13 5.55228 13 5C13 4.44772 12.5523 4 12 4C11.4477 4 11 4.44772 11 5C11 5.55228 11.4477 6 12 6Z" stroke="#2E2E2E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
														<path d="M12 20C12.5523 20 13 19.5523 13 19C13 18.4477 12.5523 18 12 18C11.4477 18 11 18.4477 11 19C11 19.5523 11.4477 20 12 20Z" stroke="#2E2E2E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
												</svg>
											</div>
											<div class="dropdown-menu dropdown-menu-right">
												<a class="dropdown-item" href="javascript:void(0);">Details</a>
												<a class="dropdown-item text-danger" href="javascript:void(0);">Cancel</a>
											</div>
										</div>
									</div>
									<div class="card-body bg-white border-bottom">
										<div class="media profile-bx">
											<img src="{{ asset('images/profile/16.jpg') }}" alt=""/>
											<div class="media-body align-items-center">
												<h2 class="text-black font-w600">Thomas Aldox</h2>
												<p class="mb-2">@thomasdox</p>
												<p class="text-black">Join on 24 March 2017</p>
												<div class="social-icons">
													<a href="javascript:void(0);" class="iconbx fa fa-phone"></a>
													<a href="javascript:void(0);" class="iconbx fa fa-envelope"></a>
													<a href="javascript:void(0);" class="iconbx fa fa-facebook"></a>
													<a href="javascript:void(0);" class="btn btn-outline-dark">Edit Profile</a>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xl-12 col-lg-6">
								<div class="card">
									<div class="card-header pb-0 border-0">
										<h4 class="mb-0 text-black fs-20">Coin Holding</h4>
									</div>
									<div class="card-body row sp16">
										<div class="col-xl-6 mb-3 col-lg-12 col-md-6">
											<div class="bgl-warning border border-warning media align-items-center p-4">
												<img class="mr-3" src="{{ asset('images/svg/bitcoin-1.svg') }}" alt="">
												<div class="media-body">
													<h2 class="text-black fs-28 mb-2 font-w600">$65,123</h2>
													<span class="fs-14 font-w600">BTC</span>
												</div>
											</div>
										</div>
										<div class="col-xl-6 mb-3 col-lg-12 col-md-6">
											<div class="bgl-secondary border border-secondary media align-items-center p-4">
												<img class="mr-3" src="{{ asset('images/svg/ethereum-1.svg') }}" alt="">
												<div class="media-body">
													<h2 class="text-black fs-28 mb-2 font-w600">$2,551</h2>
													<span class="fs-14 font-w600">ETH</span>
												</div>
											</div>
										</div>
										<div class="col-xl-6 mb-3 col-lg-12 col-md-6">
											<div class="bgl-info border border-info media align-items-center p-4">
												<img class="mr-3" src="{{ asset('images/svg/ripple-1.svg') }}" alt="">
												<div class="media-body">
													<h2 class="text-black fs-28 mb-2 font-w600">$0,55</h2>
													<span class="fs-14 font-w600">RPL</span>
												</div>
											</div>
										</div>
										<div class="col-xl-6 mb-3 col-lg-12 col-md-6">
											<div class="bgl-dark border-info border border-dark media align-items-center p-4">
												<img class="mr-3" src="{{ asset('images/svg/litecoin-1.svg') }}" alt="">
												<div class="media-body">
													<h2 class="text-black fs-28 mb-2 font-w600">$65,123</h2>
													<span class="fs-14 font-w600">LTC</span>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xl-12 col-lg-6">
								<div class="card">
									<div class="card-header border-0 d-xl-flex d-lg-block d-md-flex d-sm-flex d-block">
										<div class="mr-2">
											<h4 class="fs-20 text-black">Wallet Activity</h4>
											<p class="fs-13">Lorem ipsum dolor sit amet, consectetur</p>
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
					<div class="col-xl-6 col-xxl-12">
						<div class="row">
							<div class="col-xl-12">
								<div class="card">
									<div class="card-header border-0 pb-0 d-sm-flex d-block">
										<div>
											<h4 class="text-black fs-20">Contacts</h4>
											<p class="fs-12 mb-0">Lorem ipsum dolor sit amet, consectetur</p>
										</div>
										<a href="javascript:void(0);" class="btn btn-primary rounded-0 mt-3 mt-sm-0">Invite Friends</a>
									</div>
									<div class="card-body contacts-list">
										<div class="media mb-2 align-items-center">
											<img class="mr-3 rounded-0 width70 height70" src="{{ asset('images/contact/1.jpg') }}" alt="">
											<div class="media-body">
												<h6 class="text-black fs-18 mb-0">Alexandro Queque</h6>
												<span class="fs-14">@alexandro</span>
											</div>
											<a class="btn btn-md btn-outline-primary rounded-0" href="javascript:void(0);">TRANSFER</a>
											<a class="btn btn-md btn-dark rounded-0 ml-2" href="javascript:void(0);">UNFOLLOW</a>
											<div class="dropdown custom-dropdown mb-0">
												<div class="btn sharp pr-0 tp-btn" data-toggle="dropdown">
													<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
															<path d="M12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13Z" stroke="#2E2E2E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
															<path d="M12 6C12.5523 6 13 5.55228 13 5C13 4.44772 12.5523 4 12 4C11.4477 4 11 4.44772 11 5C11 5.55228 11.4477 6 12 6Z" stroke="#2E2E2E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
															<path d="M12 20C12.5523 20 13 19.5523 13 19C13 18.4477 12.5523 18 12 18C11.4477 18 11 18.4477 11 19C11 19.5523 11.4477 20 12 20Z" stroke="#2E2E2E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
													</svg>
												</div>
												<div class="dropdown-menu dropdown-menu-right">
													<a class="dropdown-item" href="javascript:void(0);">Details</a>
													<a class="dropdown-item text-danger" href="javascript:void(0);">Cancel</a>
												</div>
											</div>
										</div>
										<div class="media mb-2 align-items-center">
											<img class="mr-3 rounded-0 width70 height70" src="{{ asset('images/contact/2.jpg') }}" alt="">
											<div class="media-body">
												<h6 class="text-black fs-18 mb-0">Benny Cagur</h6>
												<span class="fs-14">@bennycag</span>
											</div>
											<a class="btn btn-md btn-outline-primary rounded-0" href="#">TRANSFER</a>
											<a class="btn btn-md btn-dark rounded-0 ml-2" href="#">UNFOLLOW</a>
											<div class="dropdown custom-dropdown mb-0">
												<div class="btn sharp pr-0 tp-btn" data-toggle="dropdown">
													<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
															<path d="M12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13Z" stroke="#2E2E2E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
															<path d="M12 6C12.5523 6 13 5.55228 13 5C13 4.44772 12.5523 4 12 4C11.4477 4 11 4.44772 11 5C11 5.55228 11.4477 6 12 6Z" stroke="#2E2E2E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
															<path d="M12 20C12.5523 20 13 19.5523 13 19C13 18.4477 12.5523 18 12 18C11.4477 18 11 18.4477 11 19C11 19.5523 11.4477 20 12 20Z" stroke="#2E2E2E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
													</svg>
												</div>
												<div class="dropdown-menu dropdown-menu-right">
													<a class="dropdown-item" href="javascript:void(0);">Details</a>
													<a class="dropdown-item text-danger" href="javascript:void(0);">Cancel</a>
												</div>
											</div>
										</div>
										<div class="media mb-2 align-items-center">
											<img class="mr-3 rounded-0 width70 height70" src="{{ asset('images/contact/3.jpg') }}" alt="">
											<div class="media-body">
												<h6 class="text-black fs-18 mb-0">Chloe Sidabutar</h6>
												<span class="fs-14">@chloesi</span>
											</div>
											<a class="btn btn-md btn-outline-primary rounded-0" href="#">TRANSFER</a>
											<a class="btn btn-md btn-dark rounded-0 ml-2" href="#">UNFOLLOW</a>
											<div class="dropdown custom-dropdown mb-0">
												<div class="btn sharp pr-0 tp-btn" data-toggle="dropdown">
													<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
															<path d="M12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13Z" stroke="#2E2E2E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
															<path d="M12 6C12.5523 6 13 5.55228 13 5C13 4.44772 12.5523 4 12 4C11.4477 4 11 4.44772 11 5C11 5.55228 11.4477 6 12 6Z" stroke="#2E2E2E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
															<path d="M12 20C12.5523 20 13 19.5523 13 19C13 18.4477 12.5523 18 12 18C11.4477 18 11 18.4477 11 19C11 19.5523 11.4477 20 12 20Z" stroke="#2E2E2E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
													</svg>
												</div>
												<div class="dropdown-menu dropdown-menu-right">
													<a class="dropdown-item" href="javascript:void(0);">Details</a>
													<a class="dropdown-item text-danger" href="javascript:void(0);">Cancel</a>
												</div>
											</div>
										</div>
										<div class="media mb-2 align-items-center">
											<img class="mr-3 rounded-0 width70 height70" src="{{ asset('images/contact/4.jpg') }}" alt="">
											<div class="media-body">
												<h6 class="text-black fs-18 mb-0">Cindy Elisabeth</h6>
												<span class="fs-14">@cindy</span>
											</div>
											<a class="btn btn-md btn-outline-primary rounded-0" href="#">TRANSFER</a>
											<a class="btn btn-md btn-dark rounded-0 ml-2" href="#">UNFOLLOW</a>
											<div class="dropdown custom-dropdown mb-0">
												<div class="btn sharp pr-0 tp-btn" data-toggle="dropdown">
													<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
															<path d="M12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13Z" stroke="#2E2E2E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
															<path d="M12 6C12.5523 6 13 5.55228 13 5C13 4.44772 12.5523 4 12 4C11.4477 4 11 4.44772 11 5C11 5.55228 11.4477 6 12 6Z" stroke="#2E2E2E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
															<path d="M12 20C12.5523 20 13 19.5523 13 19C13 18.4477 12.5523 18 12 18C11.4477 18 11 18.4477 11 19C11 19.5523 11.4477 20 12 20Z" stroke="#2E2E2E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
													</svg>
												</div>
												<div class="dropdown-menu dropdown-menu-right">
													<a class="dropdown-item" href="javascript:void(0);">Details</a>
													<a class="dropdown-item text-danger" href="javascript:void(0);">Cancel</a>
												</div>
											</div>
										</div>
										<div class="media mb-2 align-items-center">
											<img class="mr-3 rounded-0 width70 height70" src="{{ asset('images/contact/5.jpg') }}" alt="">
											<div class="media-body">
												<h6 class="text-black fs-18 mb-0">Erica Silalahi</h6>
												<span class="fs-14">@ericasil</span>
											</div>
											<a class="btn btn-md btn-outline-primary rounded-0" href="#">TRANSFER</a>
											<a class="btn btn-md btn-dark rounded-0 ml-2" href="#">UNFOLLOW</a>
											<div class="dropdown custom-dropdown mb-0">
												<div class="btn sharp pr-0 tp-btn" data-toggle="dropdown">
													<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
															<path d="M12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13Z" stroke="#2E2E2E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
															<path d="M12 6C12.5523 6 13 5.55228 13 5C13 4.44772 12.5523 4 12 4C11.4477 4 11 4.44772 11 5C11 5.55228 11.4477 6 12 6Z" stroke="#2E2E2E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
															<path d="M12 20C12.5523 20 13 19.5523 13 19C13 18.4477 12.5523 18 12 18C11.4477 18 11 18.4477 11 19C11 19.5523 11.4477 20 12 20Z" stroke="#2E2E2E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
													</svg>
												</div>
												<div class="dropdown-menu dropdown-menu-right">
													<a class="dropdown-item" href="javascript:void(0);">Details</a>
													<a class="dropdown-item text-danger" href="javascript:void(0);">Cancel</a>
												</div>
											</div>
										</div>
										<div class="media mb-2 align-items-center">
											<img class="mr-3 rounded-0 width70 height70" src="{{ asset('images/contact/6.jpg') }}" alt="">
											<div class="media-body">
												<h6 class="text-black fs-18 mb-0">Eddyson Brownlee</h6>
												<span class="fs-14">@eddyson</span>
											</div>
											<a class="btn btn-md btn-outline-primary rounded-0" href="#">TRANSFER</a>
											<a class="btn btn-md btn-dark rounded-0 ml-2" href="#">UNFOLLOW</a>
											<div class="dropdown custom-dropdown mb-0">
												<div class="btn sharp pr-0 tp-btn" data-toggle="dropdown">
													<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
															<path d="M12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13Z" stroke="#2E2E2E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
															<path d="M12 6C12.5523 6 13 5.55228 13 5C13 4.44772 12.5523 4 12 4C11.4477 4 11 4.44772 11 5C11 5.55228 11.4477 6 12 6Z" stroke="#2E2E2E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
															<path d="M12 20C12.5523 20 13 19.5523 13 19C13 18.4477 12.5523 18 12 18C11.4477 18 11 18.4477 11 19C11 19.5523 11.4477 20 12 20Z" stroke="#2E2E2E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
													</svg>
												</div>
												<div class="dropdown-menu dropdown-menu-right">
													<a class="dropdown-item" href="javascript:void(0);">Details</a>
													<a class="dropdown-item text-danger" href="javascript:void(0);">Cancel</a>
												</div>
											</div>
										</div>
										<div class="media mb-2 align-items-center">
											<img class="mr-3 rounded-0 width70 height70" src="{{ asset('images/contact/7.jpg') }}" alt="">
											<div class="media-body">
												<h6 class="text-black fs-18 mb-0">Franklin Jr</h6>
												<span class="fs-14">@franklinjr</span>
											</div>
											<a class="btn btn-md btn-outline-primary rounded-0" href="#">TRANSFER</a>
											<a class="btn btn-md btn-dark rounded-0 ml-2" href="#">UNFOLLOW</a>
											<div class="dropdown custom-dropdown mb-0">
												<div class="btn sharp pr-0 tp-btn" data-toggle="dropdown">
													<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
															<path d="M12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13Z" stroke="#2E2E2E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
															<path d="M12 6C12.5523 6 13 5.55228 13 5C13 4.44772 12.5523 4 12 4C11.4477 4 11 4.44772 11 5C11 5.55228 11.4477 6 12 6Z" stroke="#2E2E2E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
															<path d="M12 20C12.5523 20 13 19.5523 13 19C13 18.4477 12.5523 18 12 18C11.4477 18 11 18.4477 11 19C11 19.5523 11.4477 20 12 20Z" stroke="#2E2E2E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
													</svg>
												</div>
												<div class="dropdown-menu dropdown-menu-right">
													<a class="dropdown-item" href="javascript:void(0);">Details</a>
													<a class="dropdown-item text-danger" href="javascript:void(0);">Cancel</a>
												</div>
											</div>
										</div>
										<div class="media mb-2 align-items-center">
											<img class="mr-3 rounded-0 width70 height70" src="{{ asset('images/contact/8.jpg') }}" alt="">
											<div class="media-body">
												<h6 class="text-black fs-18 mb-0">Geovanny</h6>
												<span class="fs-14">@rendyor</span>
											</div>
											<a class="btn btn-md btn-outline-primary rounded-0" href="#">TRANSFER</a>
											<a class="btn btn-md btn-dark rounded-0 ml-2" href="#">UNFOLLOW</a>
											<div class="dropdown custom-dropdown mb-0">
												<div class="btn sharp pr-0 tp-btn" data-toggle="dropdown">
													<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
															<path d="M12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13Z" stroke="#2E2E2E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
															<path d="M12 6C12.5523 6 13 5.55228 13 5C13 4.44772 12.5523 4 12 4C11.4477 4 11 4.44772 11 5C11 5.55228 11.4477 6 12 6Z" stroke="#2E2E2E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
															<path d="M12 20C12.5523 20 13 19.5523 13 19C13 18.4477 12.5523 18 12 18C11.4477 18 11 18.4477 11 19C11 19.5523 11.4477 20 12 20Z" stroke="#2E2E2E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
													</svg>
												</div>
												<div class="dropdown-menu dropdown-menu-right">
													<a class="dropdown-item" href="javascript:void(0);">Details</a>
													<a class="dropdown-item text-danger" href="javascript:void(0);">Cancel</a>
												</div>
											</div>
										</div>
										<div class="media mb-2 align-items-center">
											<img class="mr-3 rounded-0 width70 height70" src="{{ asset('images/contact/9.jpg') }}" alt="">
											<div class="media-body">
												<h6 class="text-black fs-18 mb-0">Samantha Eri</h6>
												<span class="fs-14">@samantha</span>
											</div>
											<a class="btn btn-md btn-outline-primary rounded-0" href="#">TRANSFER</a>
											<a class="btn btn-md btn-dark rounded-0 ml-2" href="#">UNFOLLOW</a>
											<div class="dropdown custom-dropdown mb-0">
												<div class="btn sharp pr-0 tp-btn" data-toggle="dropdown">
													<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
															<path d="M12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13Z" stroke="#2E2E2E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
															<path d="M12 6C12.5523 6 13 5.55228 13 5C13 4.44772 12.5523 4 12 4C11.4477 4 11 4.44772 11 5C11 5.55228 11.4477 6 12 6Z" stroke="#2E2E2E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
															<path d="M12 20C12.5523 20 13 19.5523 13 19C13 18.4477 12.5523 18 12 18C11.4477 18 11 18.4477 11 19C11 19.5523 11.4477 20 12 20Z" stroke="#2E2E2E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
													</svg>
												</div>
												<div class="dropdown-menu dropdown-menu-right">
													<a class="dropdown-item" href="javascript:void(0);">Details</a>
													<a class="dropdown-item text-danger" href="javascript:void(0);">Cancel</a>
												</div>
											</div>
										</div>
										<div class="media mb-2 align-items-center">
											<img class="mr-3 rounded-0 width70 height70" src="{{ asset('images/contact/10.jpg') }}" alt="">
											<div class="media-body">
												<h6 class="text-black fs-18 mb-0">Samuel Handerson</h6>
												<span class="fs-14">@sam224</span>
											</div>
											<a class="btn btn-md btn-outline-primary rounded-0" href="#">TRANSFER</a>
											<a class="btn btn-md btn-dark rounded-0 ml-2" href="#">UNFOLLOW</a>
											<div class="dropdown custom-dropdown mb-0">
												<div class="btn sharp pr-0 tp-btn" data-toggle="dropdown">
													<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
															<path d="M12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13Z" stroke="#2E2E2E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
															<path d="M12 6C12.5523 6 13 5.55228 13 5C13 4.44772 12.5523 4 12 4C11.4477 4 11 4.44772 11 5C11 5.55228 11.4477 6 12 6Z" stroke="#2E2E2E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
															<path d="M12 20C12.5523 20 13 19.5523 13 19C13 18.4477 12.5523 18 12 18C11.4477 18 11 18.4477 11 19C11 19.5523 11.4477 20 12 20Z" stroke="#2E2E2E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
													</svg>
												</div>
												<div class="dropdown-menu dropdown-menu-right">
													<a class="dropdown-item" href="javascript:void(0);">Details</a>
													<a class="dropdown-item text-danger" href="javascript:void(0);">Cancel</a>
												</div>
											</div>
										</div>
										<div class="media align-items-center">
											<img class="mr-3 rounded-0 width70 height70" src="{{ asset('images/contact/11.jpg') }}" alt="">
											<div class="media-body">
												<h6 class="text-black fs-18 mb-0">Zain Edwards</h6>
												<span class="fs-14">@zain</span>
											</div>
											<a class="btn btn-md btn-outline-primary rounded-0" href="#">TRANSFER</a>
											<a class="btn btn-md btn-dark rounded-0 ml-2" href="#">UNFOLLOW</a>
											<div class="dropdown custom-dropdown mb-0">
												<div class="btn sharp pr-0 tp-btn" data-toggle="dropdown">
													<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
															<path d="M12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13Z" stroke="#2E2E2E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
															<path d="M12 6C12.5523 6 13 5.55228 13 5C13 4.44772 12.5523 4 12 4C11.4477 4 11 4.44772 11 5C11 5.55228 11.4477 6 12 6Z" stroke="#2E2E2E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
															<path d="M12 20C12.5523 20 13 19.5523 13 19C13 18.4477 12.5523 18 12 18C11.4477 18 11 18.4477 11 19C11 19.5523 11.4477 20 12 20Z" stroke="#2E2E2E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
													</svg>
												</div>
												<div class="dropdown-menu dropdown-menu-right">
													<a class="dropdown-item" href="javascript:void(0);">Details</a>
													<a class="dropdown-item text-danger" href="javascript:void(0);">Cancel</a>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xl-12">
								<div class="card">
									<div class="card-header border-0 pb-0 d-sm-flex d-block">
										<div>
											<h4 class="fs-20 text-black">Portofolio Chart</h4>
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
										<div class="row mx-0 align-items-center">
											<div class="col-sm-7 col-md-7  px-0">
												<div id="chartCircle"></div>
											</div>
											<div class="col-sm-5 col-md-5 px-0">
												<div class="portfolio-chart-deta">
													<div class="col px-0">
														<span></span>	
														<div>
															<p class="mb-1">Income (50%)</p>
															<h3 class="fs-20 font-w600 text-black">$632,662,662</h3>
														</div>
													</div>
													<div class="col px-0">
														<span></span>	
														<div>
															<p class="mb-1">Spends (30%)</p>
															<h3 class="fs-20 font-w600 text-black">$53,234,662</h3>
														</div>
													</div>
													<div class="col px-0">
														<span></span>	
														<div>
															<p class="mb-1">Safe (20%)</p>
															<h3 class="fs-20 font-w600 text-black">$21,412,556</h3>
														</div>
													</div>
												</div>
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