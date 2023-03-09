<head>

    <meta charset="utf-8" />
    <title>Big Apple ERP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ url('assets/images/big_apple_erp_favicon.jpeg') }}">

    <!-- Bootstrap Css OLD -->
    {{-- <link href="{{ url('assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" /> --}}

    <!-- Bootstrap CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <!-- Icons Css -->
    <link href="{{ url('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ url('assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    {{-- Toaster CSS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"
        integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+ltw8Pm5zLusl1XIfwcxo8DbWCqMGKaWeNxWA8yrx5v3SaVpMvR3CA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

        <style>
            

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



.table {
    caption-side: bottom;
    border-collapse: collapse;
}


.table thead{position: sticky;top: 0;background-color: white;}

            </style>

</head>

<body data-topbar="dark" data-layout="horizontal">
    <!-- Begin page -->
    <div id="layout-wrapper">

        <header id="page-topbar">
            <div class="navbar-header">
                <div class="d-flex">
                    <!-- LOGO -->

                    <button type="button"
                        class="btn btn-sm px-3 font-size-16 d-lg-none header-item waves-effect waves-light"
                        data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
                        <i class="fa fa-fw fa-bars"></i>
                    </button>

                </div>

                <div class="d-flex">
                    <div class="dropdown d-inline-block">
                        <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img class="rounded-circle header-profile-user"
                                src="{{ url('assets/images/users/admin.png') }}" alt="Header Avatar">
                            <span class="d-none d-xl-inline-block ms-1" key="t-henry">{{\Auth::user()->user_id}}</span>
                            <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <!-- item-->
                            <a class="dropdown-item" href="#"><i class="bx bx-user font-size-16 align-middle me-1"></i>
                                <span key="t-profile">Profile</span></a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger">
                                <form method="POST" action="{{ route('get-logout') }}" style="margin-top: 5px;">
                                    @csrf
                                    <i href="{{ route('get-logout') }}" onclick="event.preventDefault();
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
        </header>

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">
                <div class="card">
					<div class="card-body">
						<div class=" mx-auto table-responsive">
							<table class="table  table-striped" id="datatable">
                        <thead>
                            <th scope="col">#</th>
                            <th scope="col">ID</th>
                            <th scope="col">Company Name</th>
                            <th scope="col">DataBase Name</th>
                            <th scope="col">Start Date</th>
                            <th scope="col">End Date</th>
                        </thead>
                        <tbody>
                            @foreach ($datas as $key => $data)
                                <tr>
                                    <td>
                                        <a class="fw-bolder link-secondary"
                                            href="{{ url($data->db_name . '/' . 'company-dashboard') }}">Go to</a>
                                    </td>
                                    <td>{{ $data->id }}</td>
                                    <td>{{ $data->comp_name }}</td>
                                    <td>{{ $data->db_name }}</td>
                                    <td>{{ date('d/m/Y',strtotime($data->fs_date)) }}</td>
                                    <td>{{   date('d/m/Y',strtotime($data->fe_date ))}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
</div>
</div>
</div>
                </div> <!-- container-fluid -->
            </div>
            <!-- End Page-content -->
            <footer class="footer" style="position: fixed;">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <script>
                                document.write(new Date().getFullYear())
                            </script> © Big Apple.
                        </div>
                        <div class="col-sm-6">
                            <div class="text-sm-end d-none d-sm-block">
                                Design & Develop by ModernTech
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->

    <!-- JAVASCRIPT -->
    <script src="{{ url('assets/libs/jquery/jquery.min.js') }}"></script>

    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>

    <script src="{{ url('assets/libs/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ url('assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ url('assets/libs/node-waves/waves.min.js') }}"></script>

</body>

</html>
