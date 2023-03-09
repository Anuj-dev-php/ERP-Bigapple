@php
use App\Models\MainMenu;
use App\Models\RolesMenu;
use App\Models\TableModule;
use App\Models\TableModuleDet;
use App\Models\RolesMap;
use App\Models\TableMaster;
use App\Models\TblRoleModule;
use App\Helper\Helper;
$companyRoleId = Helper::getUserCompRole();

$roleMenusIds = RolesMenu::whereRoleId($companyRoleId)
    ->pluck('menu_name')
    ->toArray();
   
$mainMenuDatas = MainMenu::whereParent(0)->orderby('sequence','asc')->get();
$transactionMenuRole = TblRoleModule::where('role_id', $companyRoleId)
    ->pluck('module_id')
    ->toArray();

$transactionMenuDatas = TableModule::whereHas('tblRoleModule', function ($q) use ($companyRoleId) {
    $q->where('role_id', $companyRoleId);
})
    ->whereHas('tableModuleDets.rolesMap')
    ->orderBy('sequence','asc')
    ->orderBy('mname','asc')
    ->get();

$moduleids = $transactionMenuDatas->pluck('id');
 
$tabledetails = TableMaster::join('roles_map', 'table_master.id', '=', 'roles_map.Tran_Id')
    ->join('tbl_module_det', 'table_master.id', '=', 'tbl_module_det.txn_id')
    ->whereIn('tbl_module_det.mid', $moduleids)
    ->where('roles_map.RoleName', $companyRoleId)
    ->where(function ($query) {
        $query
            ->where('Insert_Roles', 'yes')
            ->orwhere('Edit_Roles', 'yes')
            ->orwhere('Delete_Roles', 'yes')
            ->orwhere('View_Roles', 'yes')
            ->orwhere('Print_Roles', 'yes')
            ->orwhere('masters', 'yes')
            ->orwhere('history', 'yes')
            ->orwhere('amend', 'yes')
            ->orwhere('copy', 'yes');
    })
    ->select('table_master.Table_Name', 'table_master.table_label', 'table_master.id', 'tbl_module_det.mid', 'roles_map.Insert_Roles', 'roles_map.Edit_Roles', 'roles_map.Delete_Roles', 'roles_map.View_Roles')
    ->orderby('tbl_module_det.sequence','ASC')
    ->orderby('table_master.table_label', 'ASC')
    ->get()
    ->toArray();

$transaction_menu_pages = [];

foreach($moduleids as $moduleid){

    $transaction_menu_pages[$moduleid]=[];

}

foreach ($tabledetails as $tabledetail) {
    
    $mid = $tabledetail['mid']; 
    array_push($transaction_menu_pages[$mid], $tabledetail);
}

 

@endphp

<!doctype html>
<html lang="en">

<style>
    .fnt-sz {
        font-size: 14px;
        margin-bottom: -10px;
    }

    .dropdown-last {
        position: relative;
        display: inline-block;
    }

    .dropdown-last-content {
        display: none;
        position: absolute;
        left: 180px;
        margin-top: -24px;
        background-color: #fefefe;
        min-width: 20px;
        border: 1px solid rgba(0, 0, 0, .15);
        z-index: 1;
    }

    .dropdown-last-content a {
        color: black;
        padding: 5px 20px;
        text-decoration: none;
        display: block;
    }

    .dropdown-last-content a:hover {
        background-color: #ddd;
    }

    .dropdown-last:hover .dropdown-last-content {
        display: block;
    }

    .dropdown-last:hover .dropcontent {
        background-color: #3e8e41;
    }


    .js-snackbar-container {
        position: fixed !important;
        top: 120px !important;
        right: 0px !important;
    }

    .checkbox {
        padding: 10px 0px 10px 0px;
    }

    .checkbox label {
        font-size: 15px;
        padding-left: 15px;
        font-weight: 600;
    }

    #suggestion-list {
    float: left;
    list-style: none;
    margin-top: 8px;
    padding: 0;
    width: 200px;
    position: absolute;
    margin-left:30px;

}

#suggestion-list li a{text-decoration:none;}
#suggestion-list li a:hover{color: #556ee6;
    background-color: transparent;}

#suggestion-list li {
    padding: 10px;
    background: #f0f0f0;
    line-height:1;
    background:white;
    color:blue;
    display:block;
    text-align:center;
    box-shadow: 0 1rem 3rem rgb(0 0 0 / 18%);
}

#suggestion-list li:hover {
    background:white;
    color:blue;
    cursor: pointer;
}

.check-1 {
        margin-right: 10px;


    }

    .span {
        padding-left: 20px;
    } 
</style>

<head>

    <meta charset="utf-8" />
    <title>Big Apple ERP</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ url('assets/images/big_apple_erp_favicon.jpeg') }}">

 
    <!-- Bootstrap CSS only -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous"> -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- FontAwesome Icons --}}
    <link href="{{ url('assets/fontawesome-free-6.1.1-web/css/all.css') }}" rel="stylesheet" type="text/css" />

    <!-- Icons Css -->
    <link href="{{ url('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ url('assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    <!--custom css-->
    <link href="{{ url('assets/css/custom.css') }}" rel="stylesheet" type="text/css" />
    <!-- <link href="{{ url('assets/css/mycustom.css') }}" rel="stylesheet" type="text/css" /> -->

    <link href="{{ url('assets/css/configuration.css') }}" rel="stylesheet" type="text/css" />


    <link href="{{ url('assets/css/style.min.css') }}" rel="stylesheet" type="text/css" />

    {{-- Toaster CSS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"
        integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+ltw8Pm5zLusl1XIfwcxo8DbWCqMGKaWeNxWA8yrx5v3SaVpMvR3CA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
        
    <!-- <link href="{{ url('assets/css/toastr.css') }}" rel="stylesheet" type="text/css" /> -->

    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link href="{{ asset('css/snackbar.min.css') }}" rel="stylesheet" type="text/css" />

    {{-- SELECT 2 CSS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css"
        integrity="sha512-aD9ophpFQ61nFZP6hXYu4Q/b/USW7rpLCQLX6Bi0WJHXNO7Js/fUENpBQf/+P4NtpzNX0jSgR5zVvPOJp+W2Kg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="{{ asset('css/hummingbird-treeview.min.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css"> 
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css"   />

    
    {{-- pagination --}}
    {{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha/css/bootstrap.css" rel="stylesheet"> --}}
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" />
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    {{-- <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.16/datatables.min.js"></script> --}}

    <link rel="stylesheet" type="text/css" href="{{ asset('css/easymenu.css') }}" />

    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css" />
    
    
    <link
        href="https://cdn.jsdelivr.net/gh/fontenele/bootstrap-navbar-dropdowns@5.0.2/dist/css/bootstrap-navbar-dropdowns.min.css"
        rel="stylesheet">
        
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">

    <!----------- Added for datatable : START ----------------->
    <link href="//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="//cdn.datatables.net/buttons/1.5.6/css/buttons.bootstrap4.min.css" rel="stylesheet">


    <!----------- Added for datatable : END ----------------->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-TQ9GBQ2EK8"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'G-TQ9GBQ2EK8');
</script>

</head>

<body  style="background-color: #f8f8fb;">
    <!-- Begin page -->
    <div  class="container">

        <header id="page-topbar">
            <div class="navbar-header">
                
                <img class="logo" src="{{ url('assets/images/Logo.png') }} " id="mobilelogo" />
             
                <div class="d-inline-block">
                    <!-- LOGO -->
                    
                    <div class="logo" >
                        <button id="mobileMenu" type="button"
                            class="btn btn-sm px-3 font-size-16 d-lg-none header-item waves-effect waves-light"
                            data-bs-toggle="collapse" data-bs-target="#companynavdiv">
                            <i class="fa fa-fw fa-bars" ></i>
                        </button>
                    </div>
                </div>
                 
                <div class="d-flex"  style="width:500px ;" >
               <div id="mobileSearchField">
                <span class="bx bx-search-alt " id='search'></span>
                <div style="display:inline ; width:400px;" >
                <input type="text"  class="form-control autocomplete" id='txtsearchbox' placeholder="    Search..."     >
                <div id="suggesstion-box"  >

                <div class="d-flex"  style="width:500px ;" >
               <div id="mobileSearchField">
                <span class="bx bx-search-alt " id='search'></span>
                <div style="display:inline ; width:400px;" >
                <input type="text"  class="form-control autocomplete" id='txtsearchbox' placeholder="    Search..."     >
                <div id="suggesstion-box"  >
              
                <ul id="suggestion-list"  class='d-none'  >
                    <li>India</li>
                    <li>Pak</li>
                    <li>India</li>
                    <li>Pak</li>
                    <li>India</li>
                    <li>Pak</li>

                    <li>India</li>
                    <li>Pak</li>
                    <li>India</li>
                    <li>Pak</li>
                    <li>India</li>
                    <li>Pak</li>
                </ul>
                </div>
                </div>
                <div class="d-flex"  style="width:500px ;" >
               <div id="mobileSearchField">
                <span class="bx bx-search-alt " id='search'></span>
                <div style="display:inline ; width:400px;" >
                <input type="text"  class="form-control autocomplete" id='txtsearchbox' placeholder="    Search..."     >
                <div id="suggesstion-box"  >

                </div>


                 <button type="button" id="mobilebell" class="btn header-item noti-icon waves-effect" id="page-header-notifications-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="bx bx-bell bx-tada"></i><br>
                        <span class="badge bg-danger rounded-pill">3</span>
                    </button> 
                    <div class="d-inline-block" id="mobileProfile">
                        <button type="button" class="btn header-item" id="page-header-user-dropdown"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img class="rounded-circle header-profile-user"
                                src="{{ url('assets/images/users/admin.png') }}" alt="Header Avatar" style="rounded-circle {
                                    border-radius: 40%!important;
                                }">
                            <span class="d-none d-xl-inline-block ms-1"
                                key="t-henry">{{ substr(\Auth::user()->Nickname,0,5)  }}</span>
                            <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <!-- item-->
                            <a class="dropdown-item" href="#"><i class="bx bx-user font-size-16 align-middle me-1"></i>
                                <span key="t-profile">Profile</span></a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger">
                                <form method="POST" action="{{ route('dologout',['company_name'=>Session::get('company_name')]) }}" style="margin-top: 5px;">
                                    @csrf
                                    <i href="{{ route('dologout',['company_name'=>Session::get('company_name')]) }}" onclick="event.preventDefault();
                                                        this.closest('form').submit();"
                                        class="bx bx-power-off font-size-16 align-middle me-1 text-danger"
                                        title="Log Out" style="cursor:pointer">
                                        <span key="t-logout">Logout</span>
                                    </i>
                                </form>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            

            <!-- <ul id="menu"  >
	  <li><a href="#">Home</a></li>
		<li><a href="#">Tutorials</a>
			<ul>
				<li><a href="#">2nd Nav Link</a>
					<ul>
						<li><a href="#">3rd Nav Link</a></li>
						<li><a href="#">3rd Nav Link</a></li>
					</ul>
				</li>
				<li><a href="#">2nd Nav Link</a></li>
				<li><a href="#">2nd Nav Link</a></li>
				<li><a href="#">2nd Nav Link</a></li>
			</ul>
		</li>
		<li><a href="#">Resources</a>
			<ul>
				<li><a href="#">2nd Nav Link</a></li>
				<li><a href="#">2nd Nav Link</a></li>
				<li><a href="#">2nd Nav Link</a></li>
			</ul>
		</li>
		<li><a href="#">About Us</a>
			<ul>
				<li><a href="#">2nd Nav Link</a></li>
				<li><a href="#">2nd Nav Link</a></li>
				<li></li>
				<li><a href="#">2nd Nav Link</a></li>
				<li><a href="#">2nd Nav Link</a>
					<ul>
						<li><a href="#">3rd Nav Link</a></li>
						<li></li>
						<li><a href="#">3rd Nav Link</a>
							<ul>
								<li><a href="#">4th Nav Link</a></li>
								<li><a href="#">4th Nav Link</a>
									<ul>
										<li><a href="#">5th Nav Link</a></li>
										<li><a href="#">5th Nav Link</a>
											<ul>
												<li><a href="#">6th Nav Link</a>
													<ul>
														<li><a href="#">7th Nav Link</a></li>
														<li><a href="#">7th Nav Link</a></li>
														<li><a href="#">7th Nav Link</a></li>
													</ul>
												</li>
											</ul>
										</li>
									</ul>
								</li>
								<li></li>
								<li><a href="#">4th Nav Link</a></li>
								<li><a href="#">4th Nav Link</a>
									<ul>
										<li><a href="#">5th Nav Link</a></li>
										<li><a href="#">5th Nav Link</a></li>
									</ul>
								</li>
								<li><a href="#">4th Nav Link</a></li>
							</ul>
						</li>
					</ul>
				</li>
				<li><a href="#">2nd Nav Link</a></li>
				<li><a href="#">2nd Nav Link</a></li>
				<li></li>
				<li><a href="#">2nd Nav Link</a></li>
				<li><a href="#">2nd Nav Link</a></li>
				<li><a href="#">2nd Nav Link</a></li>
				<li><a href="#">2nd Nav Link</a>
					<ul>
						<li><a href="#">3rd Nav Link</a></li>
						<li><a href="#">3rd Nav Link</a>
							<ul>
								<li><a href="#">4th Nav Link</a></li>
								<li><a href="#">4th Nav Link</a></li>
								<li><a href="#">4th Nav Link</a></li>
							</ul>
						</li>
					</ul>
				</li>
				<li><a href="#">2nd Nav Link</a></li>
				<li><a href="#">2nd Nav Link</a></li>
				<li><a href="#">2nd Nav Link</a></li>
				<li><a href="#">2nd Nav Link</a></li>
			</ul>
		</li>
		<li><a href="#">Advertise</a></li>
		<li><a href="#">Submit</a></li>
		<li><a href="#">Contact Us</a></li>
	</ul> -->
        </header>

</div>

        <div  class="maintopnavdiv"  >

        <div class="navbar navbar-expand-md navbar-dark "style="" role="navigation" >
<style>
 
     .mainmenu li 
    {
        display:inline-block;
        position:relative;
       /* margin-top:14px; */
       height:40px;
    } 
    ul li ul.submenulist
    {
        position:absolute;
        display:none;
    }
 
        ul li:hover ul.submenulist
        {
        display:block;
       
        }
        ul li:hover ul.submenulist li
        {
        display:block;
       
        }
 
</style>
           
            <!-- <button type="button" class="btn btn-sm px-3 font-size-16 d-lg-none header-item waves-effect waves-light" data-bs-toggle="collapse" data-bs-target="#topnav-menu-content" aria-expanded="true">
                <i class="fa fa-fw fa-bars"></i>
            </button> -->
<div id="companynavdiv" class="collapse navbar-collapse companynavdiv">
<ul  class="navbar-nav active  mainmenu"  id="mobileNav">
                <li  class="nav-item dropdown" style="margin-left: 15px;color:black"><a  class="nav-link"     href='/companies' ><i class="fa fa-home" aria-hidden="true"></i>&nbsp&nbsp Home</a></li> 
                <li  class="nav-item dropdown" style="margin-left: 15px;color:black;margin-top:-10px"><a  class="nav-link"     href="/{{Session::get('company_name')}}/company-dashboard" ><i class="bx bxs-dashboard" aria-hidden="true"></i> Dashboard</a></li> 
               
                @foreach ($mainMenuDatas as $key => $mainMenu)

                  @if($mainMenu->Menu_name=='Transactions')


                @if (count($transactionMenuDatas))
                <!-- style="margin-left:50px" -->
                <li  class="nav-item dropdown "  >
                <a style="margin-left:15px;" class="nav-link  dropdown-toggle arrow-none " role="button" id="dropdown_trans" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <img src="https://cdn-icons-png.flaticon.com/512/1751/1751700.png" style="height: 12px;">&nbsp;&nbsp;
                    Transactions</a> 
                        <ul id="mobileChange"  class="submenulist dropdown-menu transaction_sub_menu trs" aria-labelledby="dropdown_trans">
                    @foreach ($transactionMenuDatas as $keyMenus => $transactionMenuDatasValue)
                    @php

                        $moduleTableDet = $transaction_menu_pages[$transactionMenuDatasValue->id];

                        @endphp
                 
                        @if (!empty($moduleTableDet))
                        <li  class="dropdown-item dropdown first"><a 
                   
                            class="dropdown-toggle me" id="dropdown_trans_{{$transactionMenuDatasValue->id}}" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false"
                            >{{ $transactionMenuDatasValue->mname }}</a>

                            <style>
                                  .customenu .showdrop
                                  {
                                    width:200px;
                                  }
                                .customenu .showdrop:hover .transactionaction
                                {
                                    display:block;
                                    margin-left:180px;
                                }
                                .customenu1 .le:hover .ex 
                                {
                                    display:block;
                                }
                               @media screen and (max-width:1000px){
                                    #mobileChange
                                    {
                                        margin-left:120px!important;
                                      
                                        margin-top:-20px!important;
                                        width:200px!important;
                                    }
                                    #submobileChange
                                    {
                                        width:200px!important;
                                    }
                               }
                                </style>
                        
                              <ul style="margin-top:-8px" id="submobileChange"  class="dropdown-menu customenu" aria-labelledby="dropdown_trans_{{$transactionMenuDatasValue->id}}">
                            
                            @foreach ($moduleTableDet as $table_dtl)
                                <li    class="dropdown-item dropdown showdrop" ><a 

                            class="dropdown-toggle" id="dropdown_trans_{{$transactionMenuDatasValue->id}}_{{$table_dtl['id']}}" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false" 
                            
                            href='#'>{{ $table_dtl['table_label'] }}</a>
                                  @php

                                $allowicons = trim($table_dtl['Insert_Roles']) == 'yes' || trim($table_dtl['Edit_Roles']) == 'yes' || trim($table_dtl['Delete_Roles']) == 'yes' || trim($table_dtl['View_Roles']) == 'yes' ? true : false;
                                @endphp
                                @if ($allowicons)
                                <ul style="margin-top:-8px"    class="transactionaction dropdown-menu" aria-labelledby="dropdown_trans_{{$transactionMenuDatasValue->id}}_{{$table_dtl['id']}}">
                                    @if (trim($table_dtl['Insert_Roles']) == 'yes')
                                    <li >
                                        <a href="{{URL::to('/'.Session::get('company_name').'/add-transaction-insert-role-fields/'.$table_dtl['Table_Name'].'/'.$table_dtl['id'])}}"
                                            class="transaction_menu_icon_link">
                                            <i
                                                class="fa-solid fa-plus fa-sm"></i>
                                        </a> 
                                    </li>
                                    @endif

                                    @if (trim($table_dtl['Edit_Roles']) == 'yes')
                                    <li    >
                                    <a href="{{URL::to('/'.Session::get('company_name').'/edit-transaction-table-data/'.$table_dtl['Table_Name'].'/'.$table_dtl['id'])}}"  class="transaction_menu_icon_link">  <i class="fa-solid fa-pen-to-square fa-xs"></i>  </a>
                                   </li>

                                    @endif

                                    @if (trim($table_dtl['View_Roles']) == 'yes')
                                     <li    ><a  href="{{URL::to('/'.Session::get('company_name').'/edit-transaction-table-data/'.$table_dtl['Table_Name'].'/'.$table_dtl['id'])}}"  class="transaction_menu_icon_link"> <i  class="fa-solid fa-eye fa-xs"></i>   </a></li>

                                    @endif
                                    @if (trim($table_dtl['Delete_Roles']) == 'yes')
                                    <!-- <li  >   <a href="#"  class="transaction_menu_icon_link">  <i  class="fa-solid fa-trash fa-xs"></i>   </a></li> -->
                                    @endif 
                                </ul>
                                @endif
                             </li>
                            @endforeach
                         </ul>
                         
                    </li>
                        @endif
                
                    @endforeach
                        </ul>
                        
                 </li>


                @endif
            

                  @else


                  <li  class="nav-item dropdown sub" style="margin-left: 15px"><a  
                  class="nav-link  dropdown-toggle arrow-none mr" role="button"   data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" 
                  href='@if(!empty($mainMenu->url))  {{URL::to($mainMenu->url)}} @else # @endif'    id="dropdown_{{$mainMenu->id}}" >
                  @if($mainMenu->Menu_name=="Payroll")
                  <i class="fa fa-user-o" aria-hidden="true"></i></i>&nbsp&nbsp
                  @elseif($mainMenu->Menu_name=="Configuration")
                  <i class="fa fa-cogs" aria-hidden="true"></i>&nbsp&nbsp
                  @elseif($mainMenu->Menu_name=="Company")
                  <i class="fa fa-building" aria-hidden="true"></i>&nbsp&nbsp
                  @elseif($mainMenu->Menu_name=="Reports")
                  <i class="fa fa-book" aria-hidden="true"></i>&nbsp&nbsp
                  @endif
                                                
                  {{$mainMenu->Menu_name}}</a>
                        @php
                            $menus = MainMenu::orderBy('Menu_name','asc')
                                ->whereNotIn('id', $roleMenusIds)
                                ->where('parent', $mainMenu->id)
                                ->where('parent', '!=', '0')
                                ->get();
                        @endphp

                               
                                                    
                            @if(count($menus)>0)
                            <ul   id="mobileChange" style="margin-top:10px;" class="submenulist dropdown-menu customenu1"   aria-labelledby="dropdown_{{$mainMenu->id}}">
                                                                
                                @foreach ($menus as $keyMenus => $valueMenus)
                                        @php
                                            $childMenu = MainMenu::orderBy('Menu_name')
                                                ->whereNotIn('id', $roleMenusIds)
                                                ->where('parent', $valueMenus->id)
                                                ->get();
                                        @endphp                                
                                        @if (count($childMenu))
                                          <li  class="dropdown-item dropdown le"><a
                                                class="dropdown-toggle ss2" id="dropdown{{$mainMenu->id}}-{{$valueMenus->id}}" data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false"
                                                
                                                href='{{ URL::to('/' . Session::get('company_name') . '/' . $valueMenus->url) }}'>
                                                
                                             
                                                
                                                {{$valueMenus->Menu_name}}</a>
                                                    
                                            
                                           <ul id="submobileChange" style="margin-top:-18px"  class="dropdown-menu ex" aria-labelledby="dropdown{{$mainMenu->id}}-{{$valueMenus->id}}">
                                            @foreach ($childMenu as $keyChildMenus => $valueChildMenus)
                                            
                                                    @php
                                                        $subChildMenu = MainMenu::orderBy('Menu_name')
                                                            ->whereNotIn('id', $roleMenusIds)
                                                            ->where('parent', $valueChildMenus->id) 
                                                            ->get();
                                                    @endphp
                                 
                                                                                                
                                                    @if (count($subChildMenu))
                                                    <li   class="dropdown-item dropdown"><a    class="dropdown-toggle  mrr1" id="dropdown{{$mainMenu->id}}-{{$valueMenus->id}}-{{$valueChildMenus->id}}" data-toggle="dropdown" aria-haspopup="true"
                                          aria-expanded="false"


                                            href='{{URL::to( '/' . Session::get('company_name') . '/' . $valueChildMenus->url) }}'>{{$valueChildMenus->Menu_name}}</a>

                                                        <ul style="margin-top:-8px" class="dropdown-menu sub11" aria-labelledby="dropdowndropdown{{$mainMenu->id}}-{{$valueMenus->id}}-{{$valueChildMenus->id}}" >
                                                        @foreach ($subChildMenu as $keysubChildMenu => $valuesubChildMenu)
                                                                <li  class="dropdown-item"><a  href='{{ URL::to('/' . Session::get('company_name') . '/' . $valuesubChildMenu->url) }}'>{{$valuesubChildMenu->Menu_name}} </a></li>
                                                                

                                                         @endforeach
                                                       </ul>
                                                       
                                            </li>
                                            @else

                                            <li   class="dropdown-item"><a    href='{{URL::to( '/' . Session::get('company_name') . '/' . $valueChildMenus->url) }}'>{{$valueChildMenus->Menu_name}}</a>
                            </li>
                                         
                                            @endif


                                            @endforeach
                                            </ul>

                                            </li>
                                            @else
                                            <li  class="dropdown-item"><a  href='{{ URL::to('/' . Session::get('company_name') . '/' . $valueMenus->url) }}'>{{$valueMenus->Menu_name}}</a>


                                        @endif
                            
                                @endforeach

                            </ul>
                            @endif
                        </li>

                  @endif
                

                        
                @endforeach

                
            
      </ul>

            </div>
                            </div>
                            </div>
                            
 

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
 
            <div  > 
<div class="container-fluid">
 