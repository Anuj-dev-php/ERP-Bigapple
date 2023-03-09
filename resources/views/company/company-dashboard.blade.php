@extends('layout.layout') @section('content')
<!-- 
        <link rel="shortcut icon" href="{{ url('assets/images/big_apple_erp_favicon.jpeg') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">-->
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"  ></script>  -->
<h4 class="menu-title  mb-5 font-size-18 addeditformheading" style="">&nbsp&nbsp&nbsp&nbsp&nbspDashboard</h4>
<div class="pagecontent">
	<style>
	.overflow-hidden {
		overflow: hidden!important;
	}
	
	.card {
		margin-bottom: 24px;
		-webkit-box-shadow: 0 0.75rem 1.5rem rgb(18 38 63 / 3%);
		box-shadow: 0 0.75rem 1.5rem rgb(18 38 63 / 3%);
	}
	
	.text-truncate {
		overflow: hidden;
		text-overflow: ellipsis;
		white-space: nowrap;
	}
	
	p {
		margin-top: 0;
		margin-bottom: 1rem;
	}
	
	.text-primary {
		--bs-text-opacity: 1;
		color: rgba(var(--bs-primary-rgb), var(--bs-text-opacity))!important;
	}
	
	.bg-primary.bg-soft {
		background-color: rgba(85, 110, 230, .25)!important;
	}
	
	.card {
		position: relative;
		display: -webkit-box;
		display: -ms-flexbox;
		display: flex;
		-webkit-box-orient: vertical;
		-webkit-box-direction: normal;
		-ms-flex-direction: column;
		flex-direction: column;
		min-width: 0;
		word-wrap: break-word;
		background-color: #fff;
		background-clip: border-box;
		border: 0 solid #f6f6f6;
		border-radius: 0.25rem;
	}
	
	.font-size-15 {
		font-size: 15px!important;
	}
	
	:root {
		--bs-blue: #556ee6;
		--bs-indigo: #564ab1;
		--bs-purple: #6f42c1;
		--bs-pink: #e83e8c;
		--bs-red: #f46a6a;
		--bs-orange: #f1734f;
		--bs-yellow: #f1b44c;
		--bs-green: #34c38f;
		--bs-teal: #050505;
		--bs-cyan: #50a5f1;
		--bs-white: #fff;
		--bs-gray: #74788d;
		--bs-gray-dark: #343a40;
		--bs-gray-100: #f8f9fa;
		--bs-gray-200: #eff2f7;
		--bs-gray-300: #f6f6f6;
		--bs-gray-400: #ced4da;
		--bs-gray-500: #adb5bd;
		--bs-gray-600: #74788d;
		--bs-gray-700: #495057;
		--bs-gray-800: #343a40;
		--bs-gray-900: #212529;
		--bs-primary: #556ee6;
		--bs-secondary: #74788d;
		--bs-success: #34c38f;
		--bs-info: #50a5f1;
		--bs-warning: #f1b44c;
		--bs-danger: #f46a6a;
		--bs-pink: #e83e8c;
		--bs-light: #eff2f7;
		--bs-dark: #343a40;
		--bs-primary-rgb: 85, 110, 230;
		--bs-secondary-rgb: 116, 120, 141;
		--bs-success-rgb: 52, 195, 143;
		--bs-info-rgb: 80, 165, 241;
		--bs-warning-rgb: 241, 180, 76;
		--bs-danger-rgb: 244, 106, 106;
		--bs-pink-rgb: 232, 62, 140;
		--bs-light-rgb: 239, 242, 247;
		--bs-dark-rgb: 52, 58, 64;
		--bs-white-rgb: 255, 255, 255;
		--bs-black-rgb: 0, 0, 0;
		--bs-body-color-rgb: 73, 80, 87;
		--bs-body-bg-rgb: 248, 248, 251;
		--bs-font-sans-serif: "Poppins", sans-serif;
		--bs-font-monospace: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
		--bs-gradient: linear-gradient(180deg, rgba(255, 255, 255, 0.15), rgba(255, 255, 255, 0));
		--bs-body-font-family: var(--bs-font-sans-serif);
		--bs-body-font-size: 0.8125rem;
		--bs-body-font-weight: 400;
		--bs-body-line-height: 1.5;
		/* --bs-body-color: #495057; */
		--bs-body-bg: #f8f8fb;
	}
	
	.img-fluid {
		max-width: 100%;
		height: auto;
	}
	
	img,
	svg {
		vertical-align: middle;
	}
	
	element.style {}
	
	.profile-user-wid {
		margin-top: -26px;
	}
	
	.avatar-md {
		height: 4.5rem;
		width: 4.5rem;
	}
	
	.mb-4 {
		margin-bottom: 1.5rem!important;
	}
	
	*,
	::after,
	::before {
		-webkit-box-sizing: border-box;
		box-sizing: border-box;
	}
	
	user agent stylesheet div {
		display: block;
	}
	
	.profile-user-wid {
		margin-top: -26px;
	}
	
	.mb-4 {
		margin-bottom: 1.5rem!important;
	}
	
	.img-thumbnail {
		padding: 0.25rem;
		background-color: #f8f8fb;
		border: 1px solid #f6f6f6;
		border-radius: 0.25rem;
		max-width: 100%;
		height: auto;
	}
	
	img,
	svg {
		vertical-align: middle;
	}
	
	.avatar-md {
		height: 4.5rem;
		width: 4.5rem;
	}
	
	.h5,
	h5 {
		font-size: 1.015625rem;
	}
	
	#divisionalsales_chart_mtd,
	#divisionalsales_chart_qtd,
	#divisionalsales_chart_ytd,
	#individualsales_chart_mtd,
	#individualsales_chart_qtd,
	#individualsales_chart_ytd ,
	#expenses_chart_mtd,
	#expenses_chart_qtd ,
	#expenses_chart_ytd
	 {
		max-width: 650px;
		margin: 35px auto;
	}



/* 
    .modal-backdrop.show {
				opacity: .5;
			}
			
			.modal-backdrop.fade {
				opacity: 0;
			}
			  
			
			.offcanvas-backdrop.fade {
				opacity: 0;
			}
			
			.fade {
				transition: opacity .15s linear;
			}
			
			.btn-check:focus+.btn,
			.btn:focus { 
				box-shadow: 0 0 0 .25remrgba(13, 110, 253, .25);
			}
			
			button:focus:not(:focus-visible) {
				outline: 0;
			}
			
			.btn-check:focus+.btn,
			.btn:focus { 
				box-shadow: 0 0 0 .25remrgba(13, 110, 253, .25);
			}
			
			.btn-check:focus+.btn,
			.btn:focus {
				outline: 0;
				box-shadow: 0 0 0 0.25rem rgb(13 110 253 / 25%);
			}
			
			.form-control:focus {
				color: #212529;
				background-color: #fff;
				border-color: #86b7fe;
				outline: 0;
				box-shadow: 0 0 0 .25remrgba(13, 110, 253, .25);
			}
			
			[type=button]:not(:disabled),
			[type=reset]:not(:disabled),
			[type=submit]:not(:disabled),
			button:not(:disabled) {
				cursor: pointer;
			}
			
			.form-control {
				font-size: 12px;
			}
			
			.offcanvas-backdrop {
				position: fixed;
				top: 0;
				left: 0;
				z-index: 1040;
				width: 100vw;
				height: 100vh;
				background-color: #000;
			}
			
			.fade {
				transition: opacity .15s linear;
			}
			
			.modal-backdrop {
				position: fixed;
				top: 0;
				left: 0;
				z-index: 1050;
				width: 100vw;
				height: 100vh;
			}
			 
			.fade {
				transition: opacity .15s linear;
			} */
            /* off canvas css ends */
	</style>

	
 
	<div class="container-fluid">

	
<!-- Modal Dialog Box To Show Pendings -->
 
<div id="showPendingModal" class="modal fade">
	<div class="modal-dialog " style="max-width:98%;height:2000px;">
		<!-- Modal content-->
		<div class="modal-content"  style="height:500px;;" >
			<div class="modal-header">
				<h4 class="modal-title" id="pendingmodal_heading">Receivable Details</h4>
				<!-- onclick="$('#showPendingModal').modal('hide'); " -->
				<button type="button" class="close" style="outline:none;"  onclick="closePendingModal();">&times;</button>
			</div>
			<div class="modal-body"> 
				<div class="container-fluid "> 
				<div class="card ">
					<div class="card-body">		
					<div class="table-responsive" style="border-left:0px;border-right:0px;max-height:350px;">
									<!-- table-striped -->
									<table class="table align-middle table-nowrap mb-0">
										<thead class="table-light" style='border-bottom:5px solid #eff2f7'>
									<tr id="pendingmodal_column_names" >
										<th>Docno</th><th>DocDate</th><th>Amount</th><th>Balance</th><th>Amount To Be Adjusted</th>
									  </tr>
								</thead>
								<tbody id="pendingmodal_tabledata"  >
									<tr><td>data</td><td>data</td><td>data</td><td>data</td><td>data</td></tr>
									<tr><td>data</td><td>data</td><td>data</td><td>data</td><td>data</td></tr>
									<tr><td>data</td><td>data</td><td>data</td><td>data</td><td>data</td></tr>
									<tr><td>data</td><td>data</td><td>data</td><td>data</td><td>data</td></tr>
									<tr><td>data</td><td>data</td><td>data</td><td>data</td><td>data</td></tr>
											<tr><td>data</td><td>data</td><td>data</td><td>data</td><td>data</td></tr>
													<tr><td>data</td><td>data</td><td>data</td><td>data</td><td>data</td></tr>
															<tr><td>data</td><td>data</td><td>data</td><td>data</td><td>data</td></tr>
																	<tr><td>data</td><td>data</td><td>data</td><td>data</td><td>data</td></tr>
																			<tr><td>data</td><td>data</td><td>data</td><td>data</td><td>data</td></tr>
																					<tr><td>data</td><td>data</td><td>data</td><td>data</td><td>data</td></tr>
																							<tr><td>data</td><td>data</td><td>data</td><td>data</td><td>data</td></tr>
																							<tr><td>data</td><td>data</td><td>data</td><td>data</td><td>data</td></tr>
									 
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

<!-- Modal -->
	@include('configuration.loader')
		<div class="row">
			<div class="col-md-5">
				<!-- first card -->
				<div class="row">
					<div class="col-md-12">
						<div class="card overflow-hidden" style="position:relative; ">
							<div class="bg-primary bg-soft">
								<div class="row">
									<div class="col-7">
										<div class="text-primary p-3">
											<h5 style="font-size:13px">Welcome back!</h5> </div>
									</div>
									<div class="col-5 align-self-end"> <img src="https://themesbrand.com/skote/layouts/assets/images/profile-img.png" class="img-fluid"> </div>
								</div>
							</div>
							<div class="card-body pt-0">
								<div class="row">
									<div class="col-sm-2">
										<div class="avatar-md profile-user-wid mb-4"> <img src="{{ url('assets/images/users/admin.png') }}" class="img-thumbnail rounded-circle" style="height: 3.2rem;margin-top:5px;"> </div>
										<h5 class="font-size-15 text-truncate text-center" style="margin-top:-39px;">{{ substr(ucfirst(\Auth::user()->Nickname),0,5) }}</h5> </div>
									<div class="col-sm-10" style="margin:0px;">
										<div class="pt-2">
											<div class="row">
												<div class="col-md-6">
													<h6>Transaction Shortcut</h6> @foreach ( $tablelinks as $tablelink )
													<a href="{{url('/')}}/{{Session::get('company_name')}}/add-transaction-insert-role-fields/{{$tablelink->tablename}}/{{$tablelink->Id}}"><img src="https://cdn0.iconfinder.com/data/icons/octicons/1024/primitive-dot-512.png" style="height:10px;width:10px" />{{ucwords(strtolower($tablelink->tablelabel))}}</a>
													<br/> @endforeach </div>
												<div class="col-md-6">
													<h6>Report Shortcut</h6> @foreach ( $reportlinks as $reportlink)
													<a href="{{url('/')}}/{{Session::get('company_name')}}/"><img src="https://cdn0.iconfinder.com/data/icons/octicons/1024/primitive-dot-512.png" style="height:10px;width:10px" />{{ucwords(strtolower($reportlink->reportname))}}</a>
													<br/> @endforeach </div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- start code here  -->
 
				<div class="offcanvas offcanvas-top" tabindex="-1" id="offcanvasTop" aria-labelledby="offcanvasTopLabel" style="height:450px;">
					<div class="offcanvas-header">  
						<button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
					</div>
					<div class="offcanvas-body">
                            
                                <div class="col-xl-4">
                                <div class="card" style="margin-left:400px;width:700px">
                                    <div class="card-body">
                                        <h4 class="card-title mb-5">News</h4>
                                        <ul class="verti-timeline list-unstyled">
                                            @php
                                                $newsindex=0;
                                            @endphp

                                        @foreach($companynews as $news)
                                            <li class="event-list">
                                                <div class="event-timeline-dot" style="margin-top:-12px"> <i class="bx bx-right-arrow-circle font-size-18"></i> </div>
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0 me-3">
                                                        <h5 class="font-size-14">{{date('d M',strtotime($news->date))}}<i style="margin-top:-2px" class="bx bx-right-arrow-alt font-size-16 text-primary align-middle ms-2"></i></h5> </div>
                                                    <div class="flex-grow-1">
                                                        <div>
                                                        <p style="line-height:18px">
                                                            {{substr($news->News,0,50)}}  @if(strlen($news->News)>50) <span class="sp_readmore d-none"  data-index="{{$newsindex}}"  > {{substr($news->News,50)}}</span> <a data-index="{{$newsindex}}" href="javascript:void(0);"  class='news_readmoreless'>Read more</a>  @endif
                                                        </p>
                                                    </div>
                                                    </div>
                                                </div>
                                            </li>
                                            @php
                                                $newsindex++;
                                            @endphp
                                            @endforeach
                                          
                                        </ul>
                                    </div>
                                </div>
                            </div>
<!-- 
                        
                        <div class="modal-backdrop fade show"></div>
                        <div class="offcanvas-backdrop fade show"></div> -->

					</div>
				</div>
				@php
                    $firstcompanynews=  $companynews->first();
                @endphp
				 @if(!empty( $firstcompanynews))
				<div class="row">
					<div class="col-md-12">
						<div class="card">
							<div class="card-body">
								<h5 class="mt-3 ms-3">News</h5>
								<button class="btn btn-default form-control" type="button"  data-bs-toggle="offcanvas" data-bs-target="#offcanvasTop" aria-controls="offcanvasTop">
									<ul class="verti-timeline list-unstyled">
										<li class="event-list">
											<div class="event-timeline-dot" style="margin-top:-12px"> <i class="bx bx-right-arrow-circle font-size-18"></i> </div>
											<div class="d-flex">
												<div class="flex-shrink-0 me-3">
                                             
													<h5 class="font-size-14">{{date('d M',strtotime( $firstcompanynews->date))}}<i style="margin-top:-2px" class="bx bx-right-arrow-alt font-size-16 text-primary align-middle ms-2"></i></h5> </div>
												<div class="flex-grow-1" style="margin-left:-50px;margin-top:3px;width:405px;">
													<div> {{substr($firstcompanynews->News,0,40)}} </div>
												</div>
											  
											</div>
											<div class="text-center mt-4"><a  data-bs-toggle="offcanvas"  href="#offcanvasTop" aria-controls="offcanvasTop" class="btn btn-primary waves-effect waves-light btn-sm">View More <i class="mdi mdi-arrow-right ms-1"></i></a></div>
										</li>
							</div>
							</button>
						</div>
					</div>
				</div>
				@endif
				<!-- end code here -->
				<!-- end first card -->
				<!-- start second card -->
				<div class="row">
					<div class="col-md-12">
						<div id="carouselgraphs" class="carousel slide" data-bs-ride="carousel">
							<div class="carousel-inner">
							@if($is_admin==true) 
							<div class="carousel-item   active "   >
									<div class="row mt-3">
										
									</div>
									<div class="card bg text-right" style="margin-left:10%;margin-right:10%;">
										<div class="card-header">
											<ul class="nav nav-pills">
												<li class="nav-item"> <a href="#expenses_mtd" class="nav-link active" data-bs-toggle="tab">MTD</a> </li>
												<li class="nav-item"> <a href="#expenses_qtd" class="nav-link" data-bs-toggle="tab">QTD</a> </li>
												<li class="nav-item"> <a href="#expenses_ytd" class="nav-link" data-bs-toggle="tab">YTD</a> </li>
											</ul>
										</div>
										<div class="card-body">
											<div class="tab-content">
												<div class="tab-pane fade show active" id="expenses_mtd"    >
												<div class="col-md-12">
											<h5>Expenses (Lakhs)</h5> </div>
													<div id="expenses_chart_mtd" style="height:300px"  > </div>
												</div>
												<div class="tab-pane fade" id="expenses_qtd"  style="margin:15px;" >
												<div class="col-md-12">
											<h5>Expenses (Lakhs)</h5> </div>
													<div id="expenses_chart_qtd" style="height:300px"> </div>
												</div>
												<div class="tab-pane fade" id="expenses_ytd"  style="margin:15px;" >
												<div class="col-md-12">
											<h5>Expenses (Lakhs)</h5> </div>
													<div id="expenses_chart_ytd" style="height:300px"> </div>
												</div>
											</div>
										</div>
									</div>
								</div>
								
								@endif

								<div class="carousel-item @if($is_admin==false) active  @endif">
									<div class="row mt-3">
										
									</div>
									<div class="card bg text-right" style="margin-left:10%;margin-right:10%;">
										<div class="card-header">
											<ul class="nav nav-pills">
												<li class="nav-item"> <a href="#individualsales_mtd" class="nav-link active" data-bs-toggle="tab">MTD</a> </li>
												<li class="nav-item"> <a href="#individualsales_qtd" class="nav-link" data-bs-toggle="tab">QTD</a> </li>
												<li class="nav-item"> <a href="#individualsales_ytd" class="nav-link" data-bs-toggle="tab">YTD</a> </li>
											</ul>
										</div>
										<div class="card-body">
											<div class="tab-content">
												<div class="tab-pane fade show active" id="individualsales_mtd">
												<div class="col-md-12">
											<h5>Individual Sales (Lakhs)</h5> </div>
													<div id="individualsales_chart_mtd" style="height:300px"> </div>
												</div>
												<div class="tab-pane fade" id="individualsales_qtd">
												<div class="col-md-12">
											<h5>Individual Sales (Lakhs)</h5> </div>
													<div id="individualsales_chart_qtd" style="height:300px"> </div>
												</div>
												<div class="tab-pane fade" id="individualsales_ytd">
														<div class="col-md-12">
											<h5>Individual Sales (Lakhs)</h5> </div>
													<div id="individualsales_chart_ytd" style="height:300px"> </div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="carousel-item">
									<div class="row mt-3">
										
									</div>
									<div class="card bg text-right" style="margin-left:10%;margin-right:10%;">
										<div class="card-header">
											<ul class="nav nav-pills">
												<li class="nav-item"> <a href="#divisionalsales_mtd" class="nav-link active" data-bs-toggle="tab">MTD</a> </li>
												<li class="nav-item"> <a href="#divisionalsales_qtd" class="nav-link" data-bs-toggle="tab">QTD</a> </li>
												<li class="nav-item"> <a href="#divisionalsales_ytd" class="nav-link" data-bs-toggle="tab">YTD</a> </li>
											</ul>
										</div>
										<div class="card-body">
											<div class="tab-content">
												<div class="tab-pane fade show active" id="divisionalsales_mtd">
												<div class="col-md-12">
											<h5>Divisional Sales</h5> </div>
													<div id="divisionalsales_chart_mtd" style="height:300px"> </div>
												</div>
												<div class="tab-pane fade" id="divisionalsales_qtd">
												<div class="col-md-12">
											<h5>Divisional Sales</h5> </div>
													<div id="divisionalsales_chart_qtd" style="height:300px"> </div>
												</div>
												<div class="tab-pane fade" id="divisionalsales_ytd">
												<div class="col-md-12">
											<h5>Divisional Sales</h5> </div>
													<div id="divisionalsales_chart_ytd" style="height:300px"> </div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<button class="carousel-control-prev" type="button" data-bs-target="#carouselgraphs" data-bs-slide="prev"> <img src="https://icones.pro/wp-content/uploads/2021/06/icone-fleche-gauche-grise.png" style="height:30px;width:30px" alt=""> <span class="visually-hidden">Previous</span> </button>
							<button class="carousel-control-next" type="button" data-bs-target="#carouselgraphs" data-bs-slide="next"> <img src="https://icones.pro/wp-content/uploads/2021/06/icone-fleche-droite-grise.png" style="height:30px;width:30px" alt=""> <span class="visually-hidden">Next</span> </button>
						</div>
					</div>
				</div>
				<style>
			/* .align-self-center {
    -ms-flex-item-align: center!important;
    align-self: center!important;
}
.flex-shrink-0 {
    -ms-flex-negative: 0!important;
    flex-shrink: 0!important;
}
*, ::after, ::before {
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
}
font-size-24 {
    font-size: 24px!important;
}
.bx {
    font-family: boxicons!important;
    font-weight: 400;
    font-style: normal;
    font-variant: normal;
    line-height: 1;
    display: inline-block;
    text-transform: none;
    speak: none;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}
.avatar-title {
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    background-color: #556ee6;
    color: #fff;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    font-weight: 500;
    height: 100%;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
    width: 100%;
}

.bg-primary {
    --bs-bg-opacity: 1;
    background-color: rgba(var(--bs-primary-rgb),var(--bs-bg-opacity))!important;
}
.rounded-circle {
    border-radius: 50%!important;
}
.align-self-center {
    -ms-flex-item-align: center!important;
    align-self: center!important;
}
.fw-medium {
    font-weight: 500;
}
.text-muted {
    --bs-text-opacity: 1;
    color: #74788d!important;
}
p {
    margin-top: 0;
    margin-bottom: 1rem;
}
.mini-stats-wid .mini-stat-icon {
    overflow: hidden;
    /* position: relative; */
			}
			/* .avatar-title {
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    background-color: #556ee6;
    color: #fff;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    font-weight: 500;
    height: 100%;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
    width: 100%;
} */
			/* .flex-shrink-0 {
    -ms-flex-negative: 0!important;
    flex-shrink: 0!important;
}
.bg-primary {
    --bs-bg-opacity: 1;
    background-color: rgba(var(--bs-primary-rgb),var(--bs-bg-opacity))!important;
}
*, ::after, ::before {
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
}
.bg-primary {
    --bs-bg-opacity: 1;
    background-color: rgba(var(--bs-primary-rgb),var(--bs-bg-opacity))!important;
}
*, ::after, ::before {
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
}
*, ::after, ::before {
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
}
.font-size-24 {
    font-size: 24px!important;
}
.bx {
    font-family: boxicons!important;
    font-weight: 400;
    font-style: normal;
    font-variant: normal;
    line-height: 1;
    display: inline-block;
    text-transform: none;
    speak: none;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}
.d-flex {
    display: -webkit-box!important;
    display: -ms-flexbox!important;
    display: flex!important;
} */
			*/ .bx {
				font-family: boxicons!important;
				font-weight: 400;
				font-style: normal;
				font-variant: normal;
				line-height: 1;
				display: inline-block;
				text-transform: none;
				speak: none;
				-webkit-font-smoothing: antialiased;
				-moz-osx-font-smoothing: grayscale;
				margin-top: 15px;
			}
				</style>
				<!-- end second card -->
			</div>
			<div class="col-md-7">
				<div class="row">
					<div class="col-md-3">
						<div class="card mini-stats-wid">
							<div class="card-body">
								<div class="d-flex">
									<div class="flex-grow-1">
										<p class="text-muted fw-medium ms-3"  >Pending Quotes (L)</p>
										<div class="flex-shrink-0 align-self-center me-3">
										
										<h4 class="mb-3 ms-3 me-3"> <i class="fa fa-rupee" style="font-size:20px"></i><span id="sp_pendingquotes">0</span></h4> </div>
										<div class="mini-stat-icon avatar-sm rounded-circle bg-primary mb-3" style="margin-left:100px;margin-top:-46px"> <span class="avatar-title">
                                                               	<i class="bx bx-purchase-tag-alt font-size-24 " style="margin-top:6px"></i>
                                                            </span> </div>
									</div>
										
									
								</div>
								<!-- data-bs-toggle="offcanvas"  href="#offcanvasTop" aria-controls="offcanvasTop"  -->
								<!-- data-toggle="modal" data-target="#showPendingModal" -->
								<div class="text-center"><a class="btn btn-primary waves-effect waves-light btn-sm pending_modal_link"  data-purpose="pendingquotes">View More <i class="mdi mdi-arrow-right ms-1"></i></a></div>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="card mini-stats-wid">
							<div class="card-body">
								<div class="d-flex">
									<div class="flex-grow-1">
										<p class="text-muted fw-medium ms-3">Pending Orders</p>
										<div class="flex-shrink-0 align-self-center me-3">
										
										<h4 class="mb-3 ms-3 me-3"> <i class="fa fa-rupee" style="font-size:20px"></i><span id="sp_pendingquotes">0</span></h4> </div>
										<div class="mini-stat-icon avatar-sm rounded-circle bg-primary mb-3" style="margin-left:100px;margin-top:-46px"> <span class="avatar-title">
                                                               	<i class="bx bx-purchase-tag-alt font-size-24 " style="margin-top:6px"></i>
                                                            </span> </div>
															
									</div>
									
								</div>
								
								<div class="text-center"><a   class="btn btn-primary waves-effect waves-light btn-sm pending_modal_link"  data-purpose="pendingorders">View More <i class="mdi mdi-arrow-right ms-1"></i></a></div>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="card mini-stats-wid">
							<div class="card-body">
								<div class="d-flex">
									<div class="flex-grow-1">
										<p class="text-muted fw-medium ms-3">Pending Invoices</p>
										<div class="flex-shrink-0 align-self-center me-3">
										
										<h4 class="mb-3 ms-3 me-3"> <i class="fa fa-rupee" style="font-size:20px"></i><span id="sp_pendingquotes">0</span></h4> </div>
										<div class="mini-stat-icon avatar-sm rounded-circle bg-primary mb-3" style="margin-left:100px;margin-top:-46px"> <span class="avatar-title">
                                                               	<i class="bx bx-purchase-tag-alt font-size-24 " style="margin-top:6px"></i>
                                                            </span> </div>
									</div>
								</div>
								<div class="text-center"><a  class="btn btn-primary waves-effect waves-light btn-sm  pending_modal_link"  data-purpose="pendinginvoices">View More <i class="mdi mdi-arrow-right ms-1"></i></a></div>
							</div>
						</div>
					</div>
					@if($show_ageing_receivables==true)
					<div class="col-md-3">
						<div class="card mini-stats-wid">
							<div class="card-body">
								<div class="d-flex">
									<div class="flex-grow-1">
										<p class="text-muted fw-medium ms-3">Ageing Rvbl (#/<i class="fa fa-rupee"></i>)</p>
										<div class="flex-shrink-0 align-self-center me-3">
										
										<h4 class="mb-3 ms-3 me-3"> <i class="fa fa-rupee" style="font-size:20px"></i><span id="sp_pendingquotes">0</span></h4> </div>
										<div class="mini-stat-icon avatar-sm rounded-circle bg-primary mb-3" style="margin-left:100px;margin-top:-46px"> <span class="avatar-title">
                                                               	<i class="bx bx-purchase-tag-alt font-size-24 " style="margin-top:6px"></i>
                                                            </span> </div>
									</div>
								</div>
								<div class="text-center"><a    class="btn btn-primary waves-effect waves-light btn-sm  pending_modal_link"  data-purpose="ageingreceivables">View More <i class="mdi mdi-arrow-right ms-1"></i></a></div>
							</div>
						</div>
					</div>
					@else

					<div class="col-md-3">
						<div class="card mini-stats-wid">
							<div class="card-body">
								<div class="d-flex">
									<div class="flex-grow-1">
										<p class="text-muted fw-medium ms-3">No. of RMA Cases</p>
										<h4 class="mb-3 ms-3 me-3"><span id="sp_pending_rma_cases">0</span> </h4> </div>
									<div class="flex-shrink-0 align-self-center me-3">
										<div class="mini-stat-icon avatar-sm rounded-circle bg-primary"> <span class="avatar-title">
                                                                <!-- <i class="bx bx-purchase-tag-alt font-size-24" style="margin-top:6px"></i> -->
																<i class="bx bx-archive-in font-size-24" style="margin-top:6px"></i>
                                                            </span> </div>
									</div>
								</div>
								<div class="text-center"><a   class="btn btn-primary waves-effect waves-light btn-sm   pending_modal_link"  data-purpose="rmacases">View More <i class="mdi mdi-arrow-right ms-1"></i></a></div>
							</div>
						</div>
					</div>

					@endif
				</div>
				<!-- start email sent card  -->
				<div class="row">
					<div class="col-md-12">
						<div class="card">
							<div class="d-sm-flex flex-wrap mt-3 ms-3 me-3 ">
								<h4 class="card-title mb-4">Team Sales (Lakhs)</h4>
							    
							</div>
							<!-- <div class="card-header">
											<ul class="nav nav-pills">
												<li class="nav-item"> <a href="#sales_mtd" class="nav-link active" data-bs-toggle="tab">MTD</a> </li>
												<li class="nav-item"> <a href="#sales_qtd" class="nav-link" data-bs-toggle="tab">QTD</a> </li>
												<li class="nav-item"> <a href="#sales_ytd" class="nav-link" data-bs-toggle="tab">YTD</a> </li>
											</ul>
							 </div> -->
							 <div class="card-body">
								
								<div class="tab-content">
								<div class="tab-pane fade show active" id="sales_mtd">
													<div id="sales_chart_mtd"></div>
												</div>
										 
									
								</div>

  							</div>
							<!-- end email sent card -->
							<!-- start graph -->
						</div>
					</div>
				</div>
				<!-- start 4 cards -->
				<!-- end 4 cards -->
			</div>
		</div>
	</div>
	<div class="container-fluid">
		<style>
		.mini-stats-wid .mini-stat-icon {
			overflow: hidden;
			position: relative;
		}
		
		.card-title {
			font-size: 15px;
			margin: 0 0 7px 0;
			font-weight: 600;
		}
		
		.card {
			position: relative;
			display: -webkit-box;
			display: -ms-flexbox;
			display: flex;
			-webkit-box-orient: vertical;
			-webkit-box-direction: normal;
			-ms-flex-direction: column;
			flex-direction: column;
			min-width: 0;
			word-wrap: break-word;
			background-color: #fff;
			background-clip: border-box;
			border: 0 solid #f6f6f6;
			border-radius: 0.25rem;
		}
		
		.row {
			--bs-gutter-x: 24px;
			--bs-gutter-y: 0;
			display: -webkit-box;
			display: -ms-flexbox;
			display: flex;
			-ms-flex-wrap: wrap;
			flex-wrap: wrap;
			margin-top: calc(-1 * var(--bs-gutter-y));
			margin-right: calc(-.5 * var(--bs-gutter-x));
			margin-left: calc(-.5 * var(--bs-gutter-x));
		}
		
		h4 {
			font-size: 1.21875rem;
		}
		
		.mb-4 {
			margin-bottom: 1.5rem!important;
		}
		
		.card-title {
			margin-bottom: 0.5rem;
		}
		
		.table-responsive {
			overflow-x: auto;
			-webkit-overflow-scrolling: touch;
		}
		
		.mb-0 {
			margin-bottom: 0!important;
		}
		
		.align-middle {
			vertical-align: middle!important;
		}
		
		.table th {
			font-weight: 600;
		}
		
		.table {
			--bs-table-bg: transparent;
			--bs-table-accent-bg: transparent;
			--bs-table-striped-color: #495057;
			--bs-table-striped-bg: #f8f9fa;
			--bs-table-active-color: #495057;
			--bs-table-active-bg: #f8f9fa;
			--bs-table-hover-color: #495057;
			--bs-table-hover-bg: #f8f9fa;
			width: 100%;
			margin-bottom: 1rem;
			color: #495057;
			vertical-align: top;
			border-color: #eff2f7;
		}
		
		.table .table-light {
			color: #495057;
			border-color: #eff2f7;
			background-color: #f8f9fa;
		}
		
		table {
			caption-side: bottom;
			border-collapse: collapse;
		}
		
		table {
			display: table;
			border-collapse: separate;
			box-sizing: border-box;
			text-indent: initial;
			border-spacing: 2px;
			border-color: grey;
		}
		
		tbody,
		td,
		tfoot,
		th,
		thead,
		tr {
			border-color: inherit;
			border-style: solid;
			border-width: 0;
		}
		
		.table-light {
			--bs-table-bg: #eff2f7;
			--bs-table-striped-bg: #e3e6eb;
			--bs-table-striped-color: #000;
			--bs-table-active-bg: #d7dade;
			--bs-table-active-color: #000;
			--bs-table-hover-bg: #dde0e4;
			--bs-table-hover-color: #000;
			color: #000;
			border-color: #d7dade;
		}
		
		thead {
			display: table-header-group;
			vertical-align: middle;
			border-color: inherit;
		}
		
		.table-nowrap td,
		.table-nowrap th {
			white-space: nowrap;
		}
		
		.table th {
			font-weight: 600;
		}
		
		.table>:not(caption)>*>* {
			padding: 0.75rem 0.75rem;
			background-color: var(--bs-table-bg);
			border-bottom-width: 1px;
			-webkit-box-shadow: inset 0 0 0 9999px var(--bs-table-accent-bg);
			box-shadow: inset 0 0 0 9999px var(--bs-table-accent-bg);
		}
		
		.row {
			--bs-gutter-x: 24px;
			--bs-gutter-y: 0;
			display: -webkit-box;
			display: -ms-flexbox;
			display: flex;
			-ms-flex-wrap: wrap;
			flex-wrap: wrap;
			margin-top: calc(-1 * var(--bs-gutter-y));
			margin-right: calc(-.5 * var(--bs-gutter-x));
			margin-left: calc(-.5 * var(--bs-gutter-x));
		}
		
		tr {
			display: table-row;
			vertical-align: inherit;
			border-color: inherit;
		}
		</style>
		<div class="row">
			<div class="col-12 mx-auto">
				<div class="card" style="width:98%;margin-left:12px">
					<div class="container-fluid" style="width:95%;margin-left:2.5%">
						<div class="card  mtb-2">
							<div class="card-body">
								<h4 class="card-title mb-4" style="font-size: 15px;font-weight: 600;margin: 0 0 7px 0;">Latest Transaction</h4>
								<div class="table-responsive" style="border-left:0px;border-right:0px">
									<!-- table-striped -->
									<table class="table align-middle table-nowrap mb-0">
										<thead class="table-light" style='border-bottom:5px solid #eff2f7'>
											<tr> 
												<th>Doc No.</th>
												<th>Doc Date</th>
												<th>Name</th>
												<th>Salesman</th>
												<th>View Details</th>
											</tr>
										</thead>
										<tbody> @foreach ($lastdatas as $lastdata)
											<tr> 
												<td> @if(!empty($lastdata->editurl)) <a href="{{$lastdata->editurl}}">{{ $lastdata->{'Doc No'} }} </a> @else {{ $lastdata->{'Doc No'} }} @endif </td>
												<td> {{ formatDate($lastdata->{'Doc Date'}) }} </td>
												<td>{{$lastdata->Name}}</td>
												<td>{{$lastdata->Salesman}}</td>
												<td> @if(!empty($lastdata->editurl)) <a class="btn btn-primary" href="{{$lastdata->editurl}}">View Details</a> @endif </td>
											</tr> @endforeach </tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
            
            
            @endsection @section('js')
			<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
			<script type="text/javascript">
				
jQuery.ajaxSetup({async:true});
			$(document).ready(function() {
				// showLoader(16); 

 
			});


            $(".news_readmoreless").click(function(){

                var index=$(this).data('index');
 
                var readmorelesshtml= $(this).html().trim();

                if(readmorelesshtml=="Read more"){
                    $(this).html("Read less");
                    $(`.sp_readmore[data-index='${index}']`).removeClass('d-none');
                    $(`.sp_readmore[data-index='${index}']`).addClass('d-inline');
                }
                else{
                    $(this).html("Read more");
                    
                    $(`.sp_readmore[data-index='${index}']`).addClass('d-none');
                    $(`.sp_readmore[data-index='${index}']`).removeClass('d-inline');
                }
 
 
            });


			$(".pending_modal_link").click(function(){
				var purpose=$(this).data('purpose');

				if(purpose=="pendingquotes"){

					$("#pendingmodal_heading").html("Pending Quotes");
					showLoader(10);


				}
				else if(purpose=="pendingorders"){

					$("#pendingmodal_heading").html("Pending Orders");
					showLoader(5);
				}
				else if(purpose=="pendinginvoices"){

					
					$("#pendingmodal_heading").html("Pending Invoices");
					showLoader(5);

				}
				else if(purpose=="ageingreceivables"){
					
					$("#pendingmodal_heading").html("Ageing Receivables");
					showLoader(5);

				}
				else if(purpose=="rmacases"){

					$("#pendingmodal_heading").html("RMA Cases");
					showLoader(5);
				}

				$("#pendingmodal_column_names").empty();

				$("#pendingmodal_tabledata").empty();

				
				$.get("{{url('/')}}/{{$companyname}}/get-dashboard-pending-data/"+purpose,function(data,status){

				
					$("#showPendingModal").modal("show");

					
				
					    var result=JSON.parse(JSON.stringify(data));

						var tablecolumns=result['column_html'];

						var tabledata=result['table_html'];

				

						$("#pendingmodal_column_names").html(tablecolumns);

						$("#pendingmodal_tabledata").html(tabledata);
 
					});
 

			});


			function closePendingModal(){
				
				$("#showPendingModal").modal("hide");
			}


			window.onload = (event) =>{

				setTimeout(function(){

					$.get("{{url('/')}}/{{$companyname}}/get-no-of-pending-quotes", function(data, status) {
					var result = JSON.parse(JSON.stringify(data));
					var noofpendingquotes=result['noofpendingquotes'];
					$("#sp_pendingquotes").html(noofpendingquotes);

				});

				$.get("{{url('/')}}/{{$companyname}}/get-no-of-pending-orders", function(data, status) {
					var result = JSON.parse(JSON.stringify(data));
					var noofpendingorders=result['noofpendingorders'];
					$("#sp_pendingorders").html(noofpendingorders);

				});

				$.get("{{url('/')}}/{{$companyname}}/get-no-of-pending-invoices", function(data, status) {
					var result = JSON.parse(JSON.stringify(data));
					var noofpendinginvoices=result['noofpendinginvoices'];
					$("#sp_pendinginvoices").html(noofpendinginvoices);

				});

			var  ageingreceivables_and_amount=	$("#sp_ageingreceivables_and_amount").length;
			var  pending_rm_cases=	$("#sp_pending_rma_cases").length;


			if(ageingreceivables_and_amount==1){
				$.get("{{url('/')}}/{{$companyname}}/get-no-of-ageing-receivables-and-amount", function(data, status) {
					var result = JSON.parse(JSON.stringify(data));

					var ageingreceivable_and_amount=result['no_of_ageing_receivables_and_amount'];

					$("#sp_ageingreceivables_and_amount").html(ageingreceivable_and_amount);
 
				});


			}
			else{

				$.get("{{url('/')}}/{{$companyname}}/get-no-of-pending-rma-cases", function(data, status) {
					var result = JSON.parse(JSON.stringify(data));

					var no_of_pending_rma_cases=result['no_of_pending_rma_cases'];

					$("#sp_pending_rma_cases").html(no_of_pending_rma_cases);
 
				});

			}



			var expense_chart_mtd_options={	
				
				noData: {
						text: "Loading ....",
						align: "center",
						verticalAlign: "middle",
						style:{
							fontSize:'18px',
							fontWeight:'bold'
						}
					},
					series: [],
												 labels: [], 		
													chart: {									
													type: 'donut',
													height: 400,
													},
													plotOptions: {
													pie: {
														startAngle: -90,
														endAngle: 270
													}
													},
													dataLabels: {
													enabled: false
													},
													fill: {
													type: 'gradient',
													},
													responsive: [{
													breakpoint: 480,
													options: {
														chart: {
														width: 200
														},
														legend: {
														position: 'bottom'
														}
													}
													}]
													};;
																
												var expenses_mtd = new ApexCharts(document.querySelector("#expenses_chart_mtd"), expense_chart_mtd_options);


												expenses_mtd.render();


												var expenses_qtd = new ApexCharts(document.querySelector("#expenses_chart_qtd"), expense_chart_mtd_options);

													expenses_qtd.render();

													var expenses_ytd = new ApexCharts(document.querySelector("#expenses_chart_ytd"), expense_chart_mtd_options);

														expenses_ytd.render();
																											

				$.get("{{url('/')}}/{{$companyname}}/get-expense-charts-data", function(data, status) {
					var result = JSON.parse(JSON.stringify(data));
					
					var expensecharts=result['data'];

					expenses_mtd.updateOptions({
						series: expensecharts['mtd']['values'],
					     labels:expensecharts['mtd']['names'],
						 legend: {
									formatter: function(val, opts) {
										return   expensecharts['mtd']['names'][opts.seriesIndex].substring(0, 8) ;
									}
								},
					});

 
					expenses_qtd.updateOptions({
						series: expensecharts['qtd']['values'],
						 labels:expensecharts['qtd']['names'],
						 legend: {
								formatter: function(val, opts) {
																return   expensecharts['qtd']['names'][opts.seriesIndex].substring(0, 8) ;
										}
								},
					});

					expenses_ytd.updateOptions({
						series: expensecharts['ytd']['values'],
					    labels:expensecharts['ytd']['names'],
						legend: {
								formatter: function(val, opts) {
									return   expensecharts['ytd']['names'][opts.seriesIndex].substring(0, 8) ;
								}
							},
					});											
						
 

				});


					var individualmtd_options = {
									noData: {
									text: "Loading ....",
									align: "center",
									verticalAlign: "middle",
									style:{
										fontSize:'18px',
										fontWeight:'bold'
									}
								},
							chart: {
								height: 280,
								type: "radialBar"
							},
							series: [ ],
							plotOptions: {
								radialBar: {
									hollow: {
										margin: 15,
										size: "70%"
									},
									dataLabels: {
										showOn: "always",
										name: {
											offsetY: -10,
											show: true,
											color: "#888",
											fontSize: "25px"
										},
										value: {
											color: "#111",
											fontSize: "25px",
											show: true
										} ,
									 
									}
								}
							},
							stroke: {
								lineCap: "round",
							},
							labels: ["Progress"]
						};


						var individual_mtdchart = new ApexCharts(document.querySelector("#individualsales_chart_mtd"), individualmtd_options);
						individual_mtdchart.render();

						var individual_qtdchart = new ApexCharts(document.querySelector("#individualsales_chart_qtd"), individualmtd_options);
						individual_qtdchart.render();

						var individual_ytdchart = new ApexCharts(document.querySelector("#individualsales_chart_ytd"), individualmtd_options);
						individual_ytdchart.render();
				
				$.get("{{url('/')}}/{{$companyname}}/get-individual-sales-charts-data", function(data, status) {

					var result=JSON.parse(JSON.stringify(data));
					var individual_charts=result['data'];

					individual_mtdchart.updateOptions({
						series: [individual_charts['MTD_PER']],
						plotOptions:{
							dataLabels: {
							total: {      
								 show: true,
								label: "SALES "+individual_charts['MTD'],
								formatter: function(w) {
									return individual_charts['MTD_PER']+'%';
								}
										
							}

						}

						}
 
					});
 

					individual_qtdchart.updateOptions({
						series: [individual_charts['QTD_PER']],
						plotOptions:{
							dataLabels: {
							total: {      
								 show: true,
								label: "SALES "+individual_charts['QTD'],
								formatter: function(w) {
									return individual_charts['QTD_PER']+'%';
								}
										
							}

						}

						}
					});


					individual_ytdchart.updateOptions({
						series: [individual_charts['YTD_PER']],
						plotOptions:{
							dataLabels: {
							total: {      
								 show: true,
								label: "SALES "+individual_charts['YTD'],
								formatter: function(w) {
									return individual_charts['YTD_PER']+'%';
								}
										
							}

						}

						}
					});
  
 
					


				});

			
				var mtdoptions = {
					noData: {
						text: "Loading ....",
						align: "center",
						verticalAlign: "middle",
						style:{
							fontSize:'18px',
							fontWeight:'bold'
						}
					},
					series: [],
						legend: {
							show: true,
							position: 'right'
						},
						chart: {
							height: 280,
							type: "radialBar"
						},
						plotOptions: {
							radialBar: {
								dataLabels: {
									name: {
										fontSize: "22px"
									},
									value: {
										fontSize: "16px"
									},
								 
								}
							}
						}, 
						labels: []
					};
					var mtdchart = new ApexCharts(document.querySelector("#divisionalsales_chart_mtd"), mtdoptions);
					mtdchart.render();

					var qtdchart = new ApexCharts(document.querySelector("#divisionalsales_chart_qtd"), mtdoptions);
					qtdchart.render();

					var ytdchart = new ApexCharts(document.querySelector("#divisionalsales_chart_ytd"), mtdoptions);
					ytdchart.render();

				$.get("{{url('/')}}/{{$companyname}}/get-divisional-sales-charts-data", function(data, status) {

					var result=JSON.parse(JSON.stringify(data));
					var divisionalcharts = result['data'];
					var totalmtds = divisionalcharts['total_mtds'];
					var divisions = divisionalcharts['divisions'];

					mtdchart.updateOptions({
							series: divisionalcharts['mtds'],
						plotOptions: {
									radialBar: {
										dataLabels: {
											total: {
												show: true,
												label: "Total",
												formatter: function(w) {
													return totalmtds;
												  }
											}

										}
									}
							}
							,
							labels: divisions
					});


					var totalqtds = divisionalcharts['total_qtds'];
					qtdchart.updateOptions({
							series: divisionalcharts['qtds'],
						plotOptions: {
									radialBar: {
										dataLabels: {
											total: {
												show: true,
												label: "Total",
												formatter: function(w) {
													return totalqtds;
												  }
											}

										}
									}
							}
							,
							labels: divisions
					});
  
				
					var totalytds = divisionalcharts['total_ytds'];

					ytdchart.updateOptions({
						series: divisionalcharts['ytds'],
						plotOptions: {
									radialBar: {
										dataLabels: {
											total: {
												show: true,
												label: "Total",
												formatter: function(w) {
													return totalytds;
												  }
											}

										}
									}
							}
							,
							labels: divisions
					}); 
				});



				var saleschart_options_mtd= {
					noData: {
						text: "Loading ....",
						align: "center",
						verticalAlign: "middle",
						style:{
							fontSize:'18px',
							fontWeight:'bold'
						}
					},
							series:[],
							chart: {
							type: 'bar',
							height: 600,
							stacked: true,
							toolbar: {
								show: false
							},
							zoom: {
								enabled: true
							}
							},
							colors:[],
							responsive: [{
							breakpoint: 480,
							options: {
								legend: {
								position: 'bottom',
								offsetX: -10,
								offsetY: 0
								}
							}
							}],
							plotOptions: {
							bar: {
								horizontal: false,
								borderRadius: 10
							},
							},
							dataLabels: {
								enabled: false,
							},
							xaxis: {
							type: 'category',
							categories:[],
							},
							legend: {
								position: 'right',
								offsetY: 40
							},
							fill: {
							opacity: 1
							}
							};

		
							var sales_mtd = new ApexCharts(document.querySelector("#sales_chart_mtd"), saleschart_options_mtd);

							sales_mtd.render();



				

				$.get("{{url('/')}}/{{$companyname}}/get-sales-charts-data", function(data, status) {
					var result = JSON.parse(JSON.stringify(data));
					
					var salescharts=result['data'];  

					sales_mtd.updateOptions({
						series:salescharts['series'],

					 xaxis:	{
							categories:salescharts['categories']
						}	 ,
						colors:salescharts['colors'] 
					} );
				 				

				});

				}, 2000);
				
			
				 

				};


			</script> @endsection