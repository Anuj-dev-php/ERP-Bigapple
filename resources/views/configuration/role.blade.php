@php
use App\Models\RolesMap;
use App\Models\User;
@endphp

@extends('layout.layout')
@section('content')
    <div>
        <span id="showID"></span>
    </div>

   
  <h2 class="menu-title">  roles menu</h2>
    
  <div class="pagecontent" >
  <div class="container-fluid">

<div class="row ">

      <ul class="nav nav-tabs" id="menutablist" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button"
                role="tab" aria-controls="home" aria-selected="true">Transactions</button>
        </li>

        <li class="nav-item" role="reports">
            <button class="nav-link" id="reports-tab" data-bs-toggle="tab" data-bs-target="#reports" type="button"
                role="tab" aria-controls="reports" aria-selected="true">Reports</button>
        </li>


        <li class="nav-item" role="createRole">
            <button class="nav-link" id="createRole-tab" data-bs-toggle="tab" data-bs-target="#createRole"
                type="button" role="tab" aria-controls="createRole" aria-selected="false"  >Create
                Role</button>
        </li>
        <li class="nav-item" role="createUser">
            <button class="nav-link" id="createUser-tab" data-bs-toggle="tab" data-bs-target="#createUser"
                type="button" role="tab" aria-controls="createUser" aria-selected="false">Create User</button>
        </li>


        <li class="nav-item" role="field_level">
            <button class="nav-link" id="field-level-tab" data-bs-toggle="tab" data-bs-target="#fieldLevel"
                type="button" role="tab" aria-controls="fieldLevel" aria-selected="false"> Field Level</button>
        </li>
        
        <li class="nav-item" role="module_level">
            <button class="nav-link" id="module-level-tab" data-bs-toggle="tab" data-bs-target="#modulelevel"
                type="button" role="tab" aria-controls="modulelevel" aria-selected="false">Module Level</button>
        </li>


        <li class="nav-item" role="inbox_tabs">
            <button class="nav-link" id="inbox_tabs-tab" data-bs-toggle="tab" data-bs-target="#inboxtabs"
                type="button" role="tab" aria-controls="inboxtabs" aria-selected="false"> Inbox Tabs</button>
        </li>

        <li class="nav-item" role="inbox_tabs_hiding">
            <button class="nav-link" id="inbox_tabs_hiding-tab" data-bs-toggle="tab" data-bs-target="#inboxtabshiding"
                type="button" role="tab" aria-controls="inboxtabshiding" aria-selected="false"> Inbox Tabs Hiding</button>
        </li>

        <li class="nav-item" role="master_tabs">
            <button class="nav-link" id="master_tabs-tab" data-bs-toggle="tab" data-bs-target="#mastertabs"
                type="button" role="tab" aria-controls="mastertabs" aria-selected="false"> Masters</button>
        </li>


        <li class="nav-item" role="master_restriction_tabs">
            <button class="nav-link" id="master_restriction_tabs-tab" data-bs-toggle="tab"
                data-bs-target="#masterrestrictiontabs" type="button" role="tab" aria-controls="masterrestrictiontabs"
                aria-selected="false"> Master Restrictions</button>
        </li>

        


        <li class="nav-item" role="menu_level_tabs">
            <button class="nav-link" id="menu_level_tabs-tab" data-bs-toggle="tab" data-bs-target="#menuleveltabs"
                type="button" role="tab" aria-controls="menuleveltabs" aria-selected="false">Menu Level</button>
        </li>

        <li class="nav-item" role="account_level_tabs">
            <button class="nav-link" id="account_level_tabs-tab" data-bs-toggle="tab"
                data-bs-target="#accountleveltabs" type="button" role="tab" aria-controls="accountleveltabs"
                aria-selected="false">Account Level</button>
        </li>
        <li class="nav-item" role="stockrate_restriction_tabs">
            <button class="nav-link" id="stockrate_restriction_tabs-tab" data-bs-toggle="tab"
                data-bs-target="#stockraterestrictiontabs" type="button" role="tab" aria-controls="stockraterestrictiontabs"
                aria-selected="false"> Stock Rate Restrictions</button>
        </li>



    </ul>
    </div>
    
    <div class="tab-content" id="pagemenutablist"  >
        {{-- Transactions TAB - Data --}}
        <div class="tab-pane fade show active small" id="home" role="tabpanel" aria-labelledby="home-tab">
       
                <div class="row">
                    <form id="frm_create_transactions"   method="POST">
                        @csrf
                 

                        <div class="row div-controls">
                            <div class="col-6 text-end"> <label    class="lbl_control">Select Role:</label></div>
                            <div class="col-4 text-start">

                            <select class="form-control select-configure" name="role_name" id="create_transactions_ddn_select_role">
                                            <option value=""> -- SELECT -- </option>
                                            @foreach ($roleDatas as $roleKey => $roleValue)
                                                <option value={{ $roleValue->id }}>{{ $roleValue->role_name }}</option>
                                            @endforeach
                                        </select>

                            </div>
                           
                        </div>

                        <div class="row">
                            <div class="col-9 mx-auto"> 
                            <div class="card mtb-2">
					<div class="card-body">
						<div class=" mx-auto table-responsive">
                            <table  class="table table-striped"  id="datatable" style="margin-top:20px;">
                                <thead>
                                    <tr>
                                        <th scope="col" style="width:200px!important;"  >Transaction</th>
                                        <th scope="col">Insert</th>
                                        <th scope="col">Edit</th>
                                        <th scope="col">Delete</th>
                                        <th scope="col">View</th>
                                        <th scope="col">Print</th>
                                        <th scope="col">Master</th>
                                        <th scope="col">History</th>
                                        <th scope="col">Export</th>
                                        <th scope="col">Copy</th>
                                    </tr>
                                </thead>
                                <tbody id="toAppend_Record">
                                </tbody>
                            </table>
                            </div>
                            </div>
                            </div>
                        </div>
                    </div>
                        <input type="hidden" name="data" id="createTransactions_data" />
                       
                        <div class="btn-group btn-group-sm div_buttons" role="group" aria-label="Basic example"
                            style="margin-top : 5px; display:none" id="buttonGroupTransactions">
                            <button type="button" class="button btn-primary btn-sm"  id="btn_save_create_transactions">Save</button>
                            <button type="button" class="button btn-primary btn-sm"  id="btn_cancel_create_transactions">Cancel</button>
                            <button type="button" class="button btn-primary btn-sm" id="btn_select_all_create_transactions">Select   All</button>
                            <button type="button" class="button btn-primary btn-sm"   id="btn_unselect_all_create_transactions" >Cancel  All</button>
                        </div>
                    </form>
                </div> 

        </div>
        <!-- Create Reports Tab --> 
        <div class="tab-pane fade small" id="reports" role="tabpanel" aria-labelledby="reports-tab" >

        <form id="frm_reports" enctype="multipart/form-data" method="post"> 
            <input type="hidden"  id="reports_selected" name="reports_selected"  value=""  />

            
     <div class="row div-controls"   >
        
         <div class="col-6 text-end"  >  
              <label class="lbl_control" >Select Role</label>
         </div>
         <div class="col-4 text-start"  >
         <select class="form-control select-configure" name="role_id" id="reports_ddn_select_role"   >
                                <option value="">Select Role</option>
                                @foreach ($roleDatas as $role)
                                    <option value="{{$role->id}}">{{ $role->role_name}}</option>
                                @endforeach 
                            </select>
  
         </div> 
            
         </div> 


            <table style="width: 100%"> 
                <tbody>
                    
                    <tr>
                        <td align="center"   style=" padding-top:20px 0px;"> 
                            <p style="text-align:center;font-weight:bold;">Unselected Reports</p>
                    <select size="4" name="unselected_reports[]" multiple  id="reports_ddn_unselected_reports"   style="height:227px;width:167px;"></select></td>
                        <td  class="div_arrows" ><br>
                        <input type="button" name="" value=">>" id="btn_reports_select"   class="button"><br><br><br><br>
                        <input type="button" name="" value="<<" id="btn_reports_unselect" class="button">
                        </td>
                        <td align="center"  style=" padding:20px 0px;"> 
                        <p style="text-align:center;font-weight:bold;">Selected Reports</p>
                        <select size="4" name="selected_reports[]" multiple    id="reports_ddn_selected_reports" style="height:227px;width:167px;"></select><br>
                            
                        </td>
                    </tr>
                    <tr>
                        <td class="div_buttons" colspan="3">
                            <input type="button" name="btnsubmit" value="Save" id="btn_save_role_reports"   class="button btn btn-primary ">
                            <input type="button" name="btncancel" value="Cancel" id="btn_cancel_role_reports" class="button  btn btn-primary">
                        </td>
                    </tr>
                </tbody>
            </table>

            </form> 
        </div>

        <div class="tab-pane fade small" id="createRole" role="tabpanel" aria-labelledby="createRole-tab" >
            @if (Session::has('success'))
                <div class="alert alert-success" role="alert">
                    {{ Session::get('success') }}
                </div>
            @endif
            <form  id="formClear">
                <div class="row  div-controls">
                    <div class="col-6 text-end">
                         <label for="role_name" class="fw-bolder" style="margin-top:10px">Enter Role Name<font color="red">*</font> : </label>
                    </div>
                    <div class="col-4 text-start">
                             <input type="text"  id="save_role_name" name="role_name" class="form-control select-configure">
                        </div>
                        <input type="hidden" id="role_name_edit_id" />
                        <input type="hidden" id="role_name_edit_mode" value="show" />

                        <div class="col-2 text-start" style="margin-left:-250px">
                            
                        <button type="button" class="btn btn-primary" onclick="return saveRole()">Save</button>
                        </div>
 
                    </div>

               
            </form>

            <!-- Add Role Modal -->
            <div class="modal fade" id="roleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form id="form_role_name">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Role : Edit</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="role_name" class="form-label">Role Name</label>
                                    <input type="hidden" class="form-control" name="id" id="roleIdFetch">
                                    <input type="text" class="form-control" name="role_name" id="edit_role_name">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-info" onclick="return updateRole()">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div> 
            <div class="card"  style="width:50%;margin:40px auto;" >
					<div class="card-body">
						<div class=" mx-auto table-responsive">
                <table class="table table-striped table-hover" cellspacing="0" cellpadding="4">
                    <thead>
                        <tr>
                            <th scope="col">Role ID</th>
                            <th scope="col">Role Name</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody id="toAppendRoles">
                        @foreach($roleDatas as $role)
                        <tr>
                        <td>{{$role->id}}</td>
                        <td  data-rolename="{{$role->role_name}}"  id="td_role_{{$role->id}}">{{$role->role_name}}</td>
                        <td>
                        <a href='javascript:void(0)'   data-id="{{$role->id}}" id="btn-edit-save-role-name_{{$role->id}}"  class='btn btn-outline-secondary btn-edit-save-role-name btn-sm'>Edit</a>
                        <a href='javascript:void(0)'  data-id="{{$role->id}}" id="btn-cancel-edit-role-name_{{$role->id}}"   class='btn btn-outline-secondary btn-cancel-edit-role-name btn-sm d-none'>Cancel</a>

                        </td></tr> 
                               
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        </div>
        </div>
        {{-- Create User TAB - Data --}}
        <div class="tab-pane fade small" id="createUser" role="tabpanel" aria-labelledby="createUser-tab">
            <div class="container" style="margin-top:10px auto 10px auto;  ">
                <form id="createUserForm">
                    <input type="hidden" name="formmode" value="add"  id="createUserForm_formmode" />
                    <input type="hidden" name="userid"  id='createUserForm_userid'  />
                    <div class="row">
                            <input type="hidden"  name="data"  id="create_user_data" />
                            <div class="col-6 text-end mtb">  <label class="lbl_control">Select User For Edit :</label></div>
                            <div class="col-4 mtb-2">
                                    <select style="margin-top:5px" id="createuser_ddn_select_user" class="form-control select-configure"><option value="">Select User</option>
                                    @foreach($users as $userid=>$useridname)
                                    <option value="{{$userid}}">{{$useridname}}</option>

                                    @endforeach
                                
                                    </select>
                            </div>
                             
                            <div class="col-2 mtb text-start">
                                <input type="button" class="btn btn-sm btn-primary" value="Edit" id="createUserForm_edit_user" />
                                
                            <input type="button" class="btn btn-sm btn-primary" value="Cancel" id="createUserForm_edit_user_cancel" />
                            </div>
                            

                        <div class="col-6 text-end mtb">  <label class="lbl_control">User Name :</label></div>
                        <div class="col-4  mtb"  >   <input type="text" id="createuser_userName" class="form-control select-configure" name="username" placeholder="Enter User Name"></div>
                       
                        <div class="col-6  text-end  mtb">  <label class="lbl_control">Password :</label></div>
                        <div class="col-4  mtb">       <input type="password" id="createuser_userPassword" class="form-control select-configure" name="userpassword"  placeholder="Enter Password"></div>
                        <div class="col-6  text-end  mtb">  <label class="lbl_control">Email ID :</label></div>
                        <div class="col-4  mtb">     <input type="text" id="createuser_userEmail" class="form-control select-configure" name="useremail" placeholder="Enter Email ID"></div>
                        <div class="col-6  text-end  mtb">  <label class="lbl_control">Email PWD :</label></div>
                        <div class="col-4  mtb">  <input type="password" id="createuser_userEmailPass" class="form-control select-configure" name="emailpassword"  placeholder="Enter Email Password"></div>
                        <div class="col-6  text-end  mtb">  <label class="lbl_control">Mobile Number:</label></div>
                        <div class="col-4  mtb">  <input type="text" id="createuser_userMobile" class="form-control select-configure" name="mobilenumber" placeholder="Enter Mobile Number"></div>
                        <div class="col-6  text-end  mtb">  <label class="lbl_control">Nick Name:</label></div>
                        <div class="col-4  mtb">   <input type="text" id="createuser_userNickName" class="form-control select-configure" name="usernickname" placeholder="Enter Nick Name"></div>
                    </div>
 
                    <div class="row" >
                        <div class="col-9 mx-auto">
                        <div class="card">
					<div class="card-body">
						<div class=" mx-auto table-responsive">
                        <table class="table table-striped ">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Company Name</th>
                                    <th scope="col">DB Name</th>
                                    <th scope="col">Role Name</th>
                                    <th scope="col">User Head</th> 
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($companyDatas as $companyKey => $companyValue)
                                    <tr>
                                        <td><input type="checkbox"   class="usercompanies"  value="{{ $companyValue->id }}">{{ $companyValue->id }}</td>
                                        <td>{{ $companyValue->comp_name }}</td>
                                        <td>{{ $companyValue->db_name}}</td>
                                        <td>
                                            <select class="form-control select-configure" style="width:150px" id="createUserForm_userRole_{{ $companyValue->id}}">
                                             <option value="">Select User Role</option> 
                                                @if (!empty($cmproles[$companyValue->id]))
                                                    @foreach ($cmproles[$companyValue->id] as $key => $value)
                                                        <option value="{{ $key }}">{{ $value}}
                                                        </option>
                                                    @endforeach
                                                @else
                                                    <option value="">no data</option>
                                                @endif
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control select-configure" style="width:150px"  id="createUserForm_userHead_{{$companyValue->id}}">
                                                <option value="">Select User Head</option> 
                                                @if (!empty($users))
                                                    @foreach ($users as $key => $value)
                                                        <option value="{{ $key}}">{{ $value }}
                                                        </option>
                                                    @endforeach
                                                @else
                                                    <option value="">no data</option>
                                                @endif
                                            </select>
                                        </td>
                                        
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </div>
                        </div>
                        </div>
                        </div>
                        <div  class="div_buttons"> 
                            <button type="button" class="button btn btn-primary" id="btn_save_create_user" >Save</button>
                            <button type="button" class="button btn btn-primary" id="btn_cancel_create_user">Cancel</button> 
                    </div>
                    </div>
                 
                </form>
            </div>
        </div>


        <!-- Field Level Tab STart -->

        <div class="tab-pane fade small" id="fieldLevel" role="tabpanel" aria-labelledby="fieldLevel-tab" >

                <div class="row div-controls" >
                    <div class="col-3 text-end">
                        <label for="ddnFieldValueSelectRoles" class="lbl_control">Select  Role:</label>
                    </div>
                    <div class="col-4 text-start">
                    <select class="form-control select-configure" id="ddnFieldValueSelectRoles"
                        data-companyname="{{ Session::get('company_name') }}" name="select_role">
                        <option value="">Select Role</option>
                        @foreach ($roleDatas as $role)
                            <option value="{{ $role->id }}">{{ $role->role_name }}</option>
                        @endforeach

                    </select>
                    </div>
                    <div class="col-2 text-end">
                    <label for="ddn_transaction_select"  class="lbl_control" >Select
                        Transaction:</label>
                    </div>
                    <div class="col-4 text-start">
                    <select id="ddn_transaction_select" class="form-control select-configure"
                        name="transaction_select">
                        <option value="">Select Transaction</option>
                    </select>
                    </div>
                </div>

            
            <form method='post' id="frm_transaction_fields_update">
                <input type="hidden" id="transactionfields_data" name="data" />
                <input type="hidden" id="transactionfields_role" name="role" />
                <input type="hidden" id="transactionfields_tablename" name="tablename" />


                <div   style="width:40%;margin:50px  auto 0px auto; ">

                <div class="card">
					<div class="card-body">
						<div class=" mx-auto table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <th class="text-center" >Fields</th>
                            <th  class="text-center"   >Hide</th>
                            <th  class="text-center"  >Read Only</th>
                        </thead>
                        <tbody id="tbody_transaction_fields" style="max-height:150px;overflow-y:auto;  ">
                        
                        </tbody>
                    </table>
                    </div>
                    </div>
                    </div>
                </div>

                <div class="col-12 text-center div_buttons" style="margin-top:100px;"> <button type="button" class="btn btn-primary btn-sm"
                        id="btn_save_transaction_fields">Submit</button>
                    <button type="button" class="btn btn-primary btn-sm" id="btn_cancel_transaction_fields">Cancel</button>
                </div>

            </form>
        </div>
        <!-- Field Level Tab Ends -->

        <!-- Module Level Start -->

        <div class="tab-pane fade small" id="modulelevel" role="tabpanel" aria-labelledby="modulelevel-tab" >

                        <form id="frm_modules" enctype="multipart/form-data" method="post"> 
                            <input type="hidden"  id="modules_selected" name="modules_selected"  value=""  />

                            
                        <div class="row div-controls"   >

                        <div class="col-6 text-end"  >  
                            <label class="lbl_control" >Select Role</label>
                        </div>
                        <div class="col-4 text-start"  >
                        <select class="form-control select-configure" name="role_id" id="modules_ddn_select_role"   >
                                                <option value="">Select Role</option>
                                                @foreach ($roleDatas as $role)
                                                    <option value="{{$role->id}}">{{ $role->role_name}}</option>
                                                @endforeach 
                                            </select>

                        </div> 
                            
                        </div> 


                            <table style="width: 100%"> 
                                <tbody>
                                    
                                    <tr>
                                        <td align="center"   style=" padding-top:20px 0px;"> 
                                            <p style="text-align:center;font-weight:bold;">Unselected Modules</p>
                                    <select size="4" name="unselected_modules[]" multiple  id="modules_ddn_unselected_modules"   style="height:227px;width:167px;"></select></td>
                                        <td  class="div_arrows" ><br>
                                        <input type="button" name="" value=">>" id="btn_modules_select"   class="button"><br><br><br><br>
                                        <input type="button" name="" value="<<" id="btn_modules_unselect" class="button">
                                        </td>
                                        <td align="center"  style=" padding:20px 0px;"> 
                                        <p style="text-align:center;font-weight:bold;">Selected Modules</p>
                                        <select size="4" name="selected_modules[]" multiple    id="modules_ddn_selected_modules" style="height:227px;width:167px;"></select><br>
                                            
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="div_buttons" colspan="3">
                                            <input type="button" name="btnsubmit" value="Save" id="btn_save_role_modules"   class="button btn btn-primary ">
                                            <input type="button" name="btncancel" value="Cancel" id="btn_cancel_role_modules" class="button btn btn-primary ">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            </form> 
                    </div> 

        <!-- Module Level Ends -->
        
        <!-- Inbox tab Start -->

            <div class="tab-pane fade small" id="inboxtabs" role="tabpanel" aria-labelledby="inboxtabs-tab" >

                <form  id="frm_inbox_tabs">
                    <div class="row div-controls">
                        <div class="col-4 text-end">     <label for="role_name" class="fw-bolder">Enter Inbox Tab Name<font color="red">*</font> : </label></div>
                            <div class="col-6 text-end">  <input class="form-control select-configure"  type="text" id="inbox_tab_name_txt" name="inbox_tab_name">
                            </div>
                            <div class="col-2 text-end" style="margin-left:-500px">
                             <button type="button" class="btn btn-primary" data-companyname="{{ Session::get('company_name') }}" id="btn_add_inbox_tab_name">Save</button>
</div>
                     </div>
               
                  
                </form>
                <div class="table-shrink" style="width:40%;margin:auto;">


                <div class="card">
					<div class="card-body">
						<div class=" mx-auto table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Inbox Tab ID</th>
                                <th scope="col">Inbox Tab Name</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <input type="hidden" id="inbox_tab_edit_id" />
                        <input type="hidden" id="inbox_tab_edit_mode" value="show" />
                        <tbody id="tblbody_tabnames" data-count="{{ count($inboxtabs) }}">
                            @if (count($inboxtabs) == 0)
                                <tr>
                                    <td colspan='3'>No Data</td>
                                </tr>
                            @else
                                @foreach ($inboxtabs as $inboxtab)
                                    <tr id="tr_inboxtab_{{ $inboxtab->id }}">
                                        <td>{{ $inboxtab->id }}</td>
                                        <td id="td_tabname_{{ $inboxtab->id }}" data-tabname="{{ $inboxtab->tab_name }}"
                                            class="inbox_tab_name" data-id="{{ $inboxtab->id }}">
                                            {{ $inboxtab->tab_name }}</td>
                                        <td> <a id="btn_edit_save_inboxtab_{{ $inboxtab->id }}"
                                                class='btn btn-outline-secondary btn-sm btn-edit-save-inbox-tab'
                                                data-id="{{ $inboxtab->id }}">Edit</a>
                                            <a id="btn_cancel_inboxtab_{{ $inboxtab->id }}"
                                                class='btn btn-outline-secondary btn-sm btn-cancel-inbox-tab d-none'>Cancel</a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    </div>
                    </div>
                    </div>
                </div>


            </div>

            <!-- Inbox tab Ends -->

            <!-- Inbox Tabs Hiding Start -->


            <div class="tab-pane fade small" id="inboxtabshiding" role="tabpanel" aria-labelledby="inboxtabshiding-tab" >


                <form  id="frm_inbox_tabs_hiding"  >

                    <div class="row div-controls">
                        <div class="col-5 text-end">     <label for="role_name" class="lbl_control">Select Role <font color="red">*</font> : </label></div>

                        <div class="col-4 text-start">
                        <select class="form-control select-configure" id='ddnInboxTabsHideRoles' name="role">
                            <option value=''>Select Role</option>
                            @foreach ($roleDatas as $roleKey => $roleValue)
                                <option value={{ $roleValue->id }}>{{ $roleValue->role_name }}</option>
                            @endforeach
                        </select>

                        </div>

                        
                    </div> 
                    <div class="row">
                    <div class="col-5 text-end">     <label  class="lbl_control">Select Inbox Tabs : </label></div>

                    <div class="col-4 text-start" id="div_role_inboxtabs_hide" style="max-height:200px;overflow-y:auto;"></div>

                    </div>
                  


                    <div class="form-group mt-5 text-center div_buttons">

                        <input type="button" class="btn btn-sm btn-primary" value="Submit"     id="btn_save_role_inboxtabs_hide" />

                        <input type="button" class="btn btn-sm btn-primary" value="Cancel"    id='btn_cancel_role_inboxtabs_hide' />


                    </div>


                </form>

            </div>
            <!-- Inbox Tabs Hiding End -->

        <!-- Master tab Start -->

        <div class="tab-pane fade small" id="mastertabs" role="tabpanel" aria-labelledby="mastertabs-tab" >

            <form   id="frm_masters"  >
                <div class="row  div-controls">
                    <div class="col-4 text-end"><label class="lbl_control " >Enter Master Name*</label></div>
                    <div class="col-6 text-start">
                    <input  type="text" id="master_name_txt" name="master_name" class="form-control select-configure">
                    </div>
                    <div class="col-2 text-start" style="margin-left:-400px">
                          <button type="button" class="btn btn-primary"   data-companyname="{{ Session::get('company_name') }}" id="btn_add_master_name">Save</button>
                      </div>
                </div>
            </form>


            <!-- Edit Tab Name Modal starts -->
            <div class="modal fade" id="editMasterNameModal" tabindex="-1" aria-labelledby="editMasterNameModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form id="form_master_edit">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editMasterNameModalLabel">Master: Edit</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Master Name</label>
                                    <input type="hidden" class="form-control" name="id" id="edit_masterId">
                                    <input type="text" class="form-control" name="master_name" id="edit_mastername">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-info" onclick="submitMasterEdit();">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!--Modal dialog to edit tab name ends  -->
            <div style="overflow : auto; height : 400px;width:40%;margin:20px auto 0px auto;;" class="table-shrink" >
                <input type="hidden" id="master_edit_id" />
                <input type="hidden" id="master_edit_mode" value="show" />
                <div class="card">
					<div class="card-body">
						<div class=" mx-auto table-responsive">
                <table class="table table-striped " >
                    <thead>
                        <tr>
                            <th scope="col">Master ID</th>
                            <th scope="col">Master Name</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody id="tblbody_masternames" data-count="{{ count($masters) }}">
                        @if (count($masters) == 0)
                            <tr>
                                <td colspan='3'>No Data</td>
                            </tr>
                        @else
                            @foreach ($masters as $master)
                                <tr id='tr_master_{{ $master->id }}'>
                                    <td>{{ $master->id }}</td>
                                    <td id="td_mastername_{{ $master->id }}" class="master_name_cell"
                                        data-mastername="{{ $master->master_name }}" contenteditable="true">
                                        {{ $master->master_name }}</td>
                                    <td> <a id="btn_edit_save_master_{{ $master->id }}" data-id="{{ $master->id }}"
                                            data-mastername="{{ $master->master_name }}"
                                            class='btn btn-outline-secondary btn-sm btn-edit-save-master'>Edit</a>
                                        <a id="btn_edit_cancel_master_{{ $master->id }}"
                                            class="'btn btn-outline-secondary btn-sm  btn-cancel-edit-master d-none ">Cancel</a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif

                    </tbody>
                </table>
                </div>
                </div>
                </div>
            </div>


        </div>

        <!-- Master tab Ends -->

        <!-- Master Tab Restrictions Starts -->

        <div class="tab-pane fade small" id="masterrestrictiontabs" role="tabpanel"  aria-labelledby="masterrestrictiontabs-tab"  >

            <form   id="frm_master_restrictions"  >

                    <div class="row div-controls">
                         
                        <div class="col-5 text-end"><label class="lbl_control" style="margin-top:10px">Select Role <font color="red">*</font> : </label></div>
                        <div class="col-4 text-start">

                             <select class="form-control select-configure" id='ddnMasterRestrictionRoles' name="role">
                                <option value=''>Select Role</option>
                                @foreach ($roleDatas as $roleKey => $roleValue)
                                    <option value={{ $roleValue->id }}>{{ $roleValue->role_name }}</option>
                                @endforeach
                            </select>

                        </div> 
                    </div>

                    <div class="row" style="margin-top:30px;">
                        <div class="col-5 text-end">
                        <label class="lbl_control">Select Masters : </label>

                        </div>
                        <div class="col-4 text-start" id="div_role_master_restrictions" style="max-height:200px;overflow-y:auto;margin-top:20px;">
                        </div> 

                    </div>
 
                <div class="form-group div_buttons mt-5">

                    <input type="button" class="btn btn-sm btn-primary" value="Submit"
                        id="btn_save_role_master_restrictions" />

                    <input type="button" class="btn btn-sm btn-primary" value="Cancel"
                        id='btn_cancel_role_master_restrictions' />


                </div>


            </form>

        </div>
        <!-- Master Tab Restriction Ends -->
        
   <!-- Menu Level tab starts -->

   <div class="tab-pane fade small" id="menuleveltabs" role="tabpanel" aria-labelledby="menuleveltabs-tab" >

<div id="treeview_container" class="hummingbird-treeview">
    <form id="frm_menu_level">

        <div class="row div-controls">
            <div class="col-6 text-end">     
                <label for="ddnMenuLevelRoles" class="lbl_control" style="font-size:0.89rem;margin-top:6px">Select Role<font color="red">*</font> :
            </label>
        </div>
            <div class="col-4 text-start">
            <select class="form-control select-configure" id="ddnMenuLevelRoles" style="font-size:0.80rem;" name="role">
                <option value="">Select Role</option>
                @foreach ($roleDatas as $role)
                    <option value="{{ $role->id }}">{{ $role->role_name }}</option>
                @endforeach
                <option></option>
            </select>

            </div>
        </div>


        <input type="hidden" name="menus" id="hf_menu_level_menus" />
        <div class="row" style="margin-top:20px;">
            <div class="col-5 text-end"  >
            <label  class="lbl_control" style="font-size:0.9rem;font-weight:bold;">Select Menus :
            </label>
           </div>

           <div class="col-4 text-start"    style="margin-top:10px;;height:300px; overflow-y: scroll;  ">
           <ul id="menu_level_tree" class="hummingbird-base">
                @foreach ($menus as $menuid => $menuname)
                    <li data-id="{{ $menuid }}"> @if(!empty($submenus[$menuid])) <i class="fa fa-plus"></i> @endif <label> <input
                                type="checkbox" class="menu_level_menus" value="{{ $menuid }}" />
                            {{ $menuname }}</label>

                        <ul>
                            @foreach ($submenus[$menuid] as $submenuid => $submenuname)
                                <li data-id="{{ $submenuid }}">
                                   @if(!empty($subsubmenus[$submenuid] )) <i class="fa fa-plus"></i> @endif
                                     <label>
                                        <input class="menu_level_menus" type="checkbox"
                                            value="{{ $submenuid }}" /> {{ $submenuname }} </label>

                                    <ul>
                                        @foreach ($subsubmenus[$submenuid] as $subsubmenuid => $subsubmenuname)
                                            <li><label><input type="checkbox" class="menu_level_menus"
                                                        value="{{ $subsubmenuid }}" />
                                                    {{ $subsubmenuname }}</label></li>
                                        @endforeach
                                    </ul>
                                </li>
                            @endforeach
                        </ul>

                    </li>
                @endforeach
            </ul>

                </div>


       </div>

       
        <div class="form-group div_buttons" style="margin-top:10px;">
            <input type="button" class="btn btn-sm btn-primary" value="Save"  style="font-size:0.9rem;" id="btn_save_menu_level" />
            <input type="button" class="btn btn-sm btn-primary" value="Cancel"   style="font-size:0.9rem;"  id="btn_cancel_menu_level" />
        </div>

    </form>
</div>
</div>


<!-- Menu Level Tab Ends -->
<!-- Account Level Tab Starts -->
<div class="tab-pane fade small" id="accountleveltabs" role="tabpanel" aria-labelledby="accountleveltabs-tab" >

<form id="frm_account_level" class="form-inline"  >

                <div class="div-controls row">
                        <div class="col-5 text-end">
                        <label for="ddnAccountLevelRoles" class="lbl_control" style="margin-top:6px">Select Role <font color="red">*</font> :  </label>

                        </div>
                        <div class="col-4 text-start">

                                <select class="form-control select-configure" id="ddnAccountLevelRoles" name="role">
                                    <option value="">Select Role</option>
                                    @foreach ($roleDatas as $role)
                                        <option value="{{ $role->id }}">{{ $role->role_name }}</option>
                                    @endforeach
                                    <option></option>
                                </select> 
                        </div> 
                </div>

    <input type="hidden" name="accountypes" value="{{ json_encode($accounttypes) }}" />

    <div class="col-6 mx-auto"  >
    <div class="card">
        <div class="card-body">
            <div class=" mx-auto table-responsive">
        <table class="table table-striped">
            <thead>
                <th>Account</th>
                <th>Insert</th>
                <th>Edit</th>
                <th>Delete</th>
                <th>View</th>
                <th>Print</th>
            </thead>
            <tbody>
                @foreach ($accounttypes as $accounttype)
                    <tr>
                        <td>{{ $accounttype }}</td>
                        <td><input type="checkbox" name="insert[]" class="accountlevel_insert"
                                value="{{ $accounttype }}" /></td>
                        <td><input type="checkbox" name="edit[]" class="accountlevel_edit"
                                value="{{ $accounttype }}" /></td>
                        <td><input type="checkbox" name="delete[]" class="accountlevel_delete"
                                value="{{ $accounttype }}" /></td>
                        <td><input type="checkbox" name="view[]" class="accountlevel_view"
                                value="{{ $accounttype }}" /></td>
                        <td><input type="checkbox" name="print[]" class="accountlevel_print"
                                value="{{ $accounttype }}" /></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
                </div>
                </div>
                </div>
      
    </div>
    <div class="div_buttons" style="margin-top:30px;">
           <input type="button" class="btn btn-sm btn-primary" id="btn_save_account_level" value="Save" />
             <input type="button" class="btn btn-sm btn-primary" id="btn_cancel_account_level" value="Cancel" />
            </div>

</form>

</div>
<!-- Account Level Tab ENds -->



          <!-- Stock Rate Restrictions Starts -->

          <div class="tab-pane fade small" id="stockraterestrictiontabs" role="tabpanel"  aria-labelledby="stockraterestrictiontabs-tab"  >

<form   id="frm_stockrate_restrictions"  >

        <div class="row div-controls">
             
            <div class="col-5 text-end"   ><label class="lbl_control" style="margin-top:10px">Select Role <font color="red">*</font> : </label></div>
            <div class="col-4 text-start"   >

                 <select class="form-control select-configure" id='ddnStockRateRestrictionRoles' name="role">
                    <option value=''>Select Role</option>
                    @foreach ($roleDatas as $roleKey => $roleValue)
                        <option value={{ $roleValue->id }}>{{ $roleValue->role_name }}</option>
                    @endforeach
                </select>

            </div> 
        </div>

        <div class="row" style="margin-top:30px;">
            <div class="col-5 text-end"  >
            <label class="lbl_control">Show : </label>
 

            </div>

            <div class="col-4 text-start"   >
						<label    > <input class="form-check-input" type="checkbox"  id="chk_rate" name="rate" value="1"     >&nbsp;  Rate </label>
                        &nbsp;       &nbsp;     
                        <label  > <input class="form-check-input" type="checkbox" id="chk_specrate"  name="specrate" value="1"     >&nbsp; Spec Rate </label>
                        &nbsp;       &nbsp;     
                        <label  > <input class="form-check-input" type="checkbox" id="chk_showamount"  name="showamount" value="1"     >&nbsp; Show Amount </label>

                </div>

          

        </div>

    <div class="form-group div_buttons mt-5">

        <input type="button" class="btn btn-sm btn-primary" value="Submit"
            id="btn_save_role_stockrate_restrictions" />

        <input type="button" class="btn btn-sm btn-primary" value="Cancel"
            id='btn_cancel_role_stockrate_restrictions' />


    </div>


</form>

</div>
<!-- Master Tab Restriction Ends -->



                            </div>
                            </div>
    </div>
@endsection
@section('js')
{{-- Create User --}}
    <script> 
        function saveUser() {
            toastr.options = {
                "closeButton": true,
                "newestOnTop": true,
                "positionClass": "toast-top-left"
            };
            let userCompany = [];
            let userRole = [];
            let userHead = [];
            let token = "{{ csrf_token() }}";
            let userName = $('#userName').val();
            let userPassword = $('#userPassword').val();
            let userEmail = $('#userEmail').val();
            let userEmailPass = $('#userEmailPass').val();
            let userMobile = $('#userMobile').val();
            let userNickName = $('#userNickName').val();
            $("input[name='userCompany']:checked").each(function() {
                userCompany.push(this.value);
            });
            $("select[name='userRole[]'] option:selected").each(function() {
                userRole.push(this.value);
            });
            $("select[name='userHead[]'] option:selected").each(function() {
                userHead.push(this.value);
            });
            $.ajax({
                url: "{{ url(Session::get('company_name') . '/save-user') }}",
                method: "POST",
                dataType: "json",
                data: {
                    _token: token,
                    userName: userName,
                    userPassword: userPassword,
                    userEmail: userEmail,
                    userEmailPass: userEmailPass,
                    userMobile: userMobile,
                    userNickName: userNickName,
                    userCompany: userCompany,
                    userRole: userRole,
                    userHead: userHead,
                },
                success: function(response) {
                    console.log(response);
                    // toastr.success(response.success);
                }
            });
            $('#createUserForm').trigger("reset");
        }
    </script>

    {{-- ROLE --}}
    <script>
        function getRolesList() {
            $.ajax({
                type: "GET",
                url: "{{ url(Session::get('company_name') . '/get-roles-list') }}",
                dataType: "json",
                success: function(data) {
                    let toAppend = "";
                    $.each(data, function(k, v) {
                        toAppend += `
                            <tr>
                                <td>${v.id}</td>
                                <td>${v.role_name}</td>
                                <td>
                                    <a href='javascript:void(0)' onclick='editRole(${v.id})'
                                        class='btn btn-outline-secondary btn-sm'>Edit</a>
                                </td>
                            </tr>
                        `;
                    });
                    $('#toAppendRoles').html(toAppend);
                }
            });
        }

        function editRole(id) {
            $("#roleIdFetch").val(id);
            $("#submitValue").html("Submit");
            $.ajax({
                url: "{{ url(Session::get('company_name') . '/get-role') }}",
                method: "GET",
                dataType: "json",
                data: {
                    id: id
                },
                success: function(data) {
                    $('#edit_role_name').val(data.role_name.trim());
                }
            });
            $('#roleModal').modal('show');
        }

        function saveRole() {
            toastr.options = {
                "closeButton": true,
                "newestOnTop": true,
                "positionClass": "toast-top-left"
            };
            let token = "{{ csrf_token() }}";
            let role_name = $('#save_role_name').val();
            $.ajax({
                url: "{{ url(Session::get('company_name') . '/roles-menu') }}",
                method: "POST",
                dataType: "json",
                data: {
                    _token: token,
                    role_name: role_name
                },
                success: function(response) { 
                    SnackbarMsg(response);
                    getRolesList();
                }
            });
            $('#formClear').trigger("reset");
        }

        function updateRole() {
            toastr.options = {
                "closeButton": true,
                "newestOnTop": true,
                "positionClass": "toast-top-left"
            };
            let token = "{{ csrf_token() }}";
            let id = $('#roleIdFetch').val();
            let role_name = $('#edit_role_name').val();
            $.ajax({
                url: "{{ url(Session::get('company_name') . '/roles-menu') }}",
                method: "POST",
                dataType: "json",
                data: {
                    _token: token,
                    id: id,
                    role_name: role_name
                },
                success: function(response) {
                    toastr.success(response.success);
                    getRolesList();
                }
            });
            $('#roleModal').modal('hide');
        }
    </script>
  
    <script src="{{ asset('js/checkboxtree.min.js') }}"></script>

    <script src="{{ asset('js/hummingbird-treeview.min.js') }}"></script>
    <script>
        $(document).ready(function() {
       
            
           $.fn.hummingbird.defaults.collapseAll= false;

           $("#menu_level_tree").hummingbird( );





            function fetchRoleTransactions(roleid){
                $.ajax({
                    type: "GET",
                    url: "{{ url(Session::get('company_name') . '/fetch-roles') }}",
                    dataType: "json",
                    data: {
                        role_id: roleid
                    },
                    success: function(data) { 

                        var resultarray=JSON.parse(JSON.stringify(data) );

                        var tables=resultarray['transactions'];
 
                        let toAppend = "";

                        for(let tbl of tables){
                            toAppend +=`<tr><td><input type='hidden' name='transactions[]' value="${tbl['Id']}" /> ${tbl['table_label']}</td>
                            <td> <input type='checkbox' class='transaction_chk transaction_insertchk'    value="${tbl['Id']}" /></td>
                            <td> <input type='checkbox' class='transaction_chk  transaction_editchk'   value="${tbl['Id']}" /></td>
                            <td> <input type='checkbox' class='transaction_chk  transaction_deletechk' value="${tbl['Id']}" /></td>
                            <td> <input type='checkbox' class='transaction_chk  transaction_viewchk' value="${tbl['Id']}" /></td>
                            <td> <input type='checkbox' class='transaction_chk transaction_printchk'   value="${tbl['Id']}" /></td>
                            <td> <input type='checkbox' class='transaction_chk  transaction_masterchk'   value="${tbl['Id']}" /></td>
                            <td> <input type='checkbox' class='transaction_chk  transaction_historychk'   value="${tbl['Id']}" /></td>
                            <td> <input type='checkbox' class='transaction_chk  transaction_ammendchk'    value="${tbl['Id']}" /></td>
                            <td> <input type='checkbox' class='transaction_chk   transaction_copychk'  value="${tbl['Id']}" /></td>
                            </tr>`;

                        }
                        $('#toAppend_Record').html(toAppend);

 
                        var insert=resultarray['insert'];  
                        for(let ins of insert){ 
                            $(".transaction_insertchk[value='"+ins+"']").prop('checked',true); 
                        }

                        var edit=resultarray['edit'];  
                        for(let ed of edit){
                            $(".transaction_editchk[value='"+ed+"']").prop('checked',true); 

                        }

                        var deletearray=resultarray['delete'];  

                        for(let del of deletearray){
                            $(".transaction_deletechk[value='"+del+"']").prop('checked',true); 
                        }

                        var view=resultarray['view'];  

                        for(let vw of view){
                            $(".transaction_viewchk[value='"+vw+"']").prop('checked',true); 
                        }

                        var print=resultarray['print'];

                        for(let pr of print){
                            $(".transaction_printchk[value='"+pr+"']").prop('checked',true); 
                        }
                        var master=resultarray['master'];

                        for(let mt of master){
                            $(".transaction_masterchk[value='"+mt+"']").prop('checked',true); 
                        }

                        var history=resultarray['history'];
 

                        for(let ht of history){
                            $(".transaction_historychk[value='"+ht+"']").prop('checked',true); 
                        }

                        var amend=resultarray['amend']; 

                        for(let ad  of amend){
                            $(".transaction_ammendchk[value='"+ad+"']").prop('checked',true); 
                        }


                        var copy=resultarray['copy'];

                        for(let cp of copy){
                            $(".transaction_copychk[value='"+cp+"']").prop('checked',true); 
                        }
  
                        if(tables.length>0){
                            $("#buttonGroupTransactions").attr("style", "display:block");
                        }
                        else{
                            $("#buttonGroupTransactions").attr("style", "display:none");
                        }
                         
                    }
                });

            }
            $("#btn_cancel_create_transactions").click(function(){

                var roleid=$("#create_transactions_ddn_select_role").val();
                fetchRoleTransactions(roleid);
                });
            $("#create_transactions_ddn_select_role").change(function() {
                var roleID = $(this).val();
                fetchRoleTransactions(roleID);
               
            });

            $("#ddnFieldValueSelectRoles").change(function() {
                var roleid = $(this).val();
                var companyname = $(this).data("companyname");

                var url = "{{url('/')}}"+"/" + companyname + "/role-trans/" + roleid;

                $("#tbody_transaction_fields").empty();
                $("#tbody_transaction_fields").append("<tr><td colspan='3'>No Data</td></tr>");


                $.get(url, function(data, status) {

                    var transactions = JSON.parse(JSON.stringify(data));
                    var tables = transactions['tables'];

                    $('#ddn_transaction_select option:not(:first)').remove();
                    $('#ddn_transaction_select').val('');

                    for (table of tables) {
                        $('#ddn_transaction_select').append("<option value='" + table[
                            'Table_Name'] + "'>" + table['table_label'] + "</option>");

                    }

                });

            });

            function LoadTransactionFields(roleid, tablename) {
              
                var companyname = $("#ddnFieldValueSelectRoles").data('companyname');
                var url = "{{url('/')}}/" + companyname + "/trans-fields/" + roleid + '/' + tablename;


                $.get(url, function(data, status) {

                    var data = JSON.parse(JSON.stringify(data));

                    var fields = data['transactionfields'];

                    var fields_det = data['transactionfields_det'];


                    $("#tbody_transaction_fields").empty();

                    if (fields.length == 0 && fields_det.length == 0) {
                        $("#tbody_transaction_fields").append(
                            "<tr><td colspan='3'>No Fields Found</td></tr>");
                        return
                    }
                    $("#tbody_transaction_fields").empty();

                    for (let field of fields) {

                        var ishided = (field['hide'].replace(/\s+/g, '') == 'False' ? false : true);
                        var isreadonly = (field['rdol'].replace(/\s+/g, '') == 'False' ? false : true);

                        $("#tbody_transaction_fields").append("<tr><td>" + field['fld_label'] + " (" +
                            field['Table_Name'] +
                            ")</td><td class='text-center'><input type='checkbox' name='hidefields[]' class='tran_hiddenfields' value='" +
                            field['Field_Name'] + "' " + (ishided == true ? 'checked' : '') +
                            " /></td><td  class='text-center'><input type='checkbox' class='tran_readonlyfields' name='readonlyfields[]'  value='" +
                            field['Field_Name'] + "' " + (isreadonly == true ? 'checked' : '') +
                            " /></td></tr>");

                    }


                    for (let field of fields_det) {

                        var ishided = (field['hide'].replace(/\s+/g, '') == 'False' ? false : true);
                        var isreadonly = (field['rdol'].replace(/\s+/g, '') == 'False' ? false : true);

                        $("#tbody_transaction_fields").append("<tr><td>" + field['fld_label'] + " (" +
                            field['Table_Name'] +
                            ")</td><td  class='text-center'><input type='checkbox' name='hidefields[]' class='tran_hiddenfields_det' value='" +
                            field['Field_Name'] + "' " + (ishided == true ? 'checked' : '') +
                            " /></td><td  class='text-center'><input type='checkbox' class='tran_readonlyfields_det' name='readonlyfields[]'  value='" +
                            field['Field_Name'] + "' " + (isreadonly == true ? 'checked' : '') +
                            " /></td></tr>");


                    }
                });


            }

            $("#ddn_transaction_select").change(function() {
                var companyname = $("#ddnFieldValueSelectRoles").data('companyname');

                var tablename = $(this).val();
                var roleid = $("#ddnFieldValueSelectRoles").val();

                LoadTransactionFields(roleid, tablename)


            });

            $("#btn_save_transaction_fields").click(function() {


                $("#transactionfields_tablename").val($("#ddn_transaction_select").val());
                $("#transactionfields_role").val($("#ddnFieldValueSelectRoles").val());

                var hiddenfields = $(".tran_hiddenfields");

                var hf_fields = [];

                hiddenfields.each(function() {
                    var fieldid = $(this).val();
                    hf_fields.push({
                        'field_id': fieldid,
                        'hide': $(this).is(':checked')
                    });
                });

                var readonlyfields = $(".tran_readonlyfields");

                var readonly_fields = [];

                readonlyfields.each(function() {
                    var fieldid = $(this).val();
                    readonly_fields.push({
                        'field_id': fieldid,
                        'readonly': $(this).is(':checked')
                    });
                });

                var hiddenfields_det = $(".tran_hiddenfields_det");

                var hf_fields_det = [];

                hiddenfields_det.each(function() {
                    var fieldid = $(this).val();
                    hf_fields_det.push({
                        'field_id': fieldid,
                        'hide': $(this).is(':checked')
                    });
                });


                var readonlyfields_det = $(".tran_readonlyfields_det");

                var readonly_fields_det = [];

                readonlyfields_det.each(function() {
                    var fieldid = $(this).val();
                    readonly_fields_det.push({
                        'field_id': fieldid,
                        'readonly': $(this).is(':checked')
                    });
                });


                var data = {
                    'hidden_fields': hf_fields,
                    'readonly_fields': readonly_fields,
                    'hidden_fields_det': hf_fields_det,
                    'readonly_fields_det': readonly_fields_det
                };

                var companyname = $("#ddnFieldValueSelectRoles").data('companyname');

                $("#transactionfields_data").val(JSON.stringify(data));

                var url = "/" + companyname + "/update-trans-fields";


                $.ajax({
                    type: 'POST',
                    url: url,
                    data: $("#frm_transaction_fields_update").serialize(),
                    success: function(result) {

                        var data = JSON.parse(JSON.stringify(result));

                        if (data['status'] == 'success') {
                            SnackBar({
                                message: "Fields saved successfully",
                            })
                            //  alert("Fields Saved successfully");
                        }

                    }
                });


            });

            $("#btn_cancel_transaction_fields").click(function() {


                var tablename = $("#ddn_transaction_select").val();
                var roleid = $("#ddnFieldValueSelectRoles").val();

                LoadTransactionFields(roleid, tablename);

            });

        });

        $("#btn_add_inbox_tab_name").click(function() {

            var inboxtabname = $("#inbox_tab_name_txt").val();

            var companyname = $("#btn_add_inbox_tab_name").data("companyname");
            var url = "/" + companyname + '/add-inbox-tabname';


            $.ajax({
                url: url,
                data: $("#frm_inbox_tabs").serialize(),
                type: 'POST',
                success: function(data) {

                    var data = JSON.parse(JSON.stringify(data));

                    if (data['status'] == 'success') {
                        SnackBar({
                            message: "Inbox Tab added successfully",
                            status: "success"
                        });

                        var tab = data['tab'];
                        var noofrecords = $("#tblbody_tabnames").data("count");


                        if (noofrecords == 0) {
                            $("#tblbody_tabnames").empty();
                        }

                        noofrecords = noofrecords + 1;

                        $("#tblbody_tabnames").data("count", noofrecords);

                        $("#tblbody_tabnames").prepend("<tr id='tr_inboxtab_" + tab['id'] + "'><td>" +
                            tab['id'] + "</td> <td  id='td_tabname_" + tab['id'] +
                            "'  data-tabname='" + tab['tab_name'] +
                            "' class='inbox_tab_name' data-id='" + tab['id'] + "'  >" + tab[
                                'tab_name'] + "</td><td> <a  id='btn_edit_save_inboxtab_" + tab[
                                'id'] +
                            "' class='btn btn-outline-secondary btn-sm btn-edit-save-inbox-tab'    data-id='" +
                            tab['id'] + "'>Edit</a> <a  id='btn_cancel_inboxtab_" + tab['id'] +
                            "' class='btn btn-outline-secondary btn-sm btn-cancel-inbox-tab d-none'   >Cancel</a></td></tr>"
                        );



                        $("#inbox_tab_name_txt").val("");
                    } else {
                        SnackBar({
                            message: "Tab Name already present",
                            status: 'error'
                        });
                    }

                }

            });
        });


        $("#btn_save_role_inboxtabs_hide").click(function() {

            var role = $("#ddnInboxTabsHideRoles").val();

            if (role == '') {
                SnackBar({
                    message: "Please select Role",
                    status: 'error'
                });
                return false;
            }


            var companyname = $("#btn_add_inbox_tab_name").data("companyname");

            var url = "/" + companyname + '/save-role-inboxtabs-hiding';

            $.ajax({
                method: 'POST',
                url: url,
                data: $("#frm_inbox_tabs_hiding").serialize(),
                success: function(data) {

                    SnackbarMsg(data);

                }
            });


        });

        $("#ddnInboxTabsHideRoles").change(function() {

            getRoleInboxTabsHided();

        });

        $("#btn_cancel_role_inboxtabs_hide").click(function() {

            getRoleInboxTabsHided();
        });

        function openEditTabNameModal(id, tabname) {
            $("#editTabNameModal").modal("show");
            $("#edit_inboxtabname").val(tabname);
            $("#edit_inboxtabId").val(id);
        }

        function submitInboxTabEdit() {

            var tabname = $("#edit_inboxtabname").val();

            if (tabname == '') {
                return;

            }

            var companyname = $("#btn_add_inbox_tab_name").data("companyname");
            var url = "/" + companyname + '/update-inbox-tabname';
            var tabid = $("#edit_inboxtabId").val();


            $.ajax({
                method: 'POST',
                url: url,
                data: $("#form_inboxtab_edit").serialize(),
                success: function(data) {
                    var result = SnackbarMsg(data);

                    var resultarray = JSON.parse(JSON.stringify(data));

                    if (resultarray['status'] == 'success') {

                        var tab = resultarray['tab'];

                        $("#tr_inboxtab_" + tab['id']).html("<td>" + tab['id'] + "</td><td id='td_tabname_" +
                            tab['id'] + "'>" + tab['tab_name'] +
                            "</td><td> <a  onclick='openEditTabNameModal(" + tab['id'] + ",\"" + tab[
                                'tab_name'] + "\");'  data-id='" + tab['id'] + "' data-tabname='" + tab[
                                'tab_name'] +
                            "' class='btn btn-outline-secondary btn-sm btn-edit-inbox-tab'>Edit</a></td>");

                        $("#editTabNameModal").modal("hide");

                    }


                }
            })

        }


        function changeValue(id, value, tranId) {
            if ($(id).is(":checked")) {
                $(id).val("yes");
                console.log($(id).val());
            } else {
                $(id).val("no");
            }
        }

        function selectAll() {
            $('.form-check-input').each(function() {
                $(this).val('yes');
                $(this).prop('checked', true);
            });
        }

        function unSelectAll() {
            $('.form-check-input').each(function() {
                $(this).val('no');
                $(this).prop('checked', false);
            });
        }


        function getRoleInboxTabsHided() {

            var role = $("#ddnInboxTabsHideRoles").val();

            var companyname = $("#btn_add_inbox_tab_name").data("companyname");

            var url = "/" + companyname + '/get-role-inboxtabs-hiding/' + role;

            
            $("#div_role_inboxtabs_hide").empty();

            $.get(url, function(data, status) {
                var data = JSON.parse(JSON.stringify(data));

                var inboxtabs=data['inboxtabs']; 

                for(let inboxtab of inboxtabs){
                    $("#div_role_inboxtabs_hide").append("<div class='checkbox role_inboxtabs_hide_chk' ><input type='checkbox' value='"+inboxtab['id']+"' name='inboxtabs_hide[]' class='role_inbox_tab_hiding_chks' ><label class='lbl_role_inboxtab'>"+inboxtab['tab_name']+"</label></div>");

                }
          
                var inboxtabshided = data['inboxtabshided'];
                var checkboxes = $(".role_inbox_tab_hiding_chks");
                checkboxes.each(function() {
                    var id = $(this).val();

                    var hided =inboxtabshided.includes(id);

                    if (hided) {
                        $(this).prop('checked', true);
                    } else {
                        $(this).prop('checked', false);
                    }
                });
            });

        }


        $("#btn_add_master_name").click(function() {
            var companyname = $("#btn_add_master_name").data('companyname');
            var url = '/' + companyname + '/add-master-name';
            $.ajax({
                url: url,
                method: 'POST',
                data: $("#frm_masters").serialize(),
                success: function(data) {
                    var canadd = SnackbarMsg(data);
                    var resultarray = JSON.parse(JSON.stringify(data));

                    if (canadd) {
                        var master = resultarray['master'];
                        var count = $("#tblbody_masternames").data("count");

                        if (count == 0) {
                            $("#tblbody_masternames").empty();
                        }

                        count = count + 1;
                        $("#master_name_txt").val("");

                        $("#tblbody_masternames").data("count", count);

                        $("#tblbody_masternames").prepend("<tr id='tr_master_" + master['id'] +
                            "'><td>" + master['id'] + "</td>  <td id='td_mastername_" + master[
                                'id'] + "' class='master_name_cell' data-mastername='" + master[
                                'master_name'] + "' contenteditable='true'>" + master[
                                'master_name'] + "</td><td> <a id='btn_edit_save_master_" + master[
                                'id'] + "'  data-id='" + master['id'] + "' data-mastername='" +
                            master[
                                'master_name'] +
                            "' class='btn btn-outline-secondary btn-sm btn-edit-save-master'>Edit</a><a id='btn_edit_cancel_master_" +
                            master['id'] +
                            "' class='btn btn-outline-secondary btn-sm  btn-cancel-edit-master d-none'>Cancel</a></td></tr>"
                        );

                    }
                }

            });

        });
    </script>
    <script>
        $('#cancel').click(function() {
            location.reload();
        });

        function openEditMasterNameModal(masterid, mastername) {
            $("#editMasterNameModal").modal("show");
            $("#edit_mastername").val(mastername);
            $("#edit_masterId").val(masterid);
        }

        function submitMasterEdit() {

            var companyname = $("#btn_add_master_name").data('companyname');
            var url = '/' + companyname + '/update-master-name';

            $.ajax({
                url: url,
                method: 'POST',
                data: $("#form_master_edit").serialize(),
                success: function(data) {

                    var canadd = SnackbarMsg(data);
                    if (canadd == true) {
                        $("#editMasterNameModal").modal("hide");
                        var result = JSON.parse(JSON.stringify(data));
                        var master = result['master'];
                        $("#tr_master_" + master['id']).html("<td>" + master['id'] + "</td><td>" + master[
                                'master_name'] + "</td><td><a onclick='openEditMasterNameModal(" + master[
                                'id'] + ",\"" + master['master_name'] + "\")'  data-id=" + master['id'] +
                            " data-mastername='" + master['master_name'] +
                            "'  class='btn btn-outline-secondary btn-sm btn-edit-master'>Edit</a></td>");

                    }
                }
            })

        }

        $("#btn_save_role_master_restrictions").click(function() {

            var companyname = $("#btn_add_master_name").data('companyname');
            var url = '/' + companyname + '/save-role-master-restrictions';


            $.ajax({
                url: url,
                method: 'POST',
                data: $("#frm_master_restrictions").serialize(),
                success: function(data) {

                    SnackbarMsg(data);

                }

            });
        });


        $("#ddnMasterRestrictionRoles").change(function() {

            var roleid = $(this).val();

            LoadRoleRestrictions(roleid);

        });

        function LoadRoleRestrictions(roleid) {

            var companyname = $("#btn_add_master_name").data('companyname');
            var url = '/' + companyname + '/get-role-master-restrictions/' + roleid;

            $.get(url, function(data, status) {

                var resultarray = JSON.parse(JSON.stringify(data));

                var masters= resultarray['masters'];
                $("#div_role_master_restrictions").empty(); 

                for(let master of masters)
                {
                    $("#div_role_master_restrictions").append("  <div class='checkbox'> <input type='checkbox' value='"+master['id']+"' name='master_restriction[]'  class='role_master_restriction_chk'><label class='role_inboxtabs_hide_chk'>"+master['master_name']+"</label></div>"); 

                }
             
                var restrictions = resultarray['masterrestrictions'];

                var masters = $(".role_master_restriction_chk");

                masters.each(function() {

                    var masterid = $(this).val();

                    if (restrictions.includes(masterid)) {

                        $(this).prop('checked', true);

                    } else {
                        $(this).prop('checked', false);
                    }

                });

            });

        }


        $("#btn_cancel_role_master_restrictions").click(function() {

            var roleid = $("#ddnMasterRestrictionRoles").val();
            LoadRoleRestrictions(roleid);


        });

        $("#btn_save_menu_level").click(function() {

            var companyname = $("#btn_add_master_name").data('companyname');
            var url = '/' + companyname + '/save-menu-level';


            var chks = $(".menu_level_menus");
            var chkresult = [];

            chks.each(function() {
                chkresult.push({
                    'menuid': $(this).val(),
                    'hide': $(this).prop('checked')
                });
            })

            $("#hf_menu_level_menus").val(JSON.stringify(chkresult));

            $.ajax({
                url: url,
                method: "POST",
                data: $("#frm_menu_level").serialize(),
                success: function(data) {

                    var isvalid = SnackbarMsg(data);

                }
            });
        });

        function LoadRoleMenuLevels(companyname, roleid) {

            var url = '/' + companyname + '/get-role-menu-level/' + roleid;
            $.get(url, function(data, status) {
                var resultarray = JSON.parse(JSON.stringify(data));

                var menus = resultarray['menus'];
                var chks = $(".menu_level_menus");

                chks.each(function() {
                    var id = $(this).val();

                    if (menus.includes(id)) {
                        $(this).prop('checked', true);
                    } else {
                        $(this).prop('checked', false);
                    }

                });


            })
        }

        $("#ddnMenuLevelRoles").change(function() {
            var roleid = $(this).val();

            var companyname = $("#btn_add_master_name").data('companyname');

            LoadRoleMenuLevels(companyname, roleid);
        });

        $("#btn_cancel_menu_level").click(function() {
            var roleid = $("#ddnMenuLevelRoles").val();

            var companyname = $("#btn_add_master_name").data('companyname');

            LoadRoleMenuLevels(companyname, roleid);

        });


        $("#btn_save_account_level").click(function() {

            var companyname = $("#btn_add_master_name").data('companyname');

            var url = '/' + companyname + '/save-role-account-level';

            $.ajax({
                method: 'POST',
                url: url,
                data: $("#frm_account_level").serialize(),
                success: function(data) {

                    SnackbarMsg(data);

                }
            });

        });

        $("#ddnAccountLevelRoles").change(function() {
            var roleid = $(this).val();
            var companyname = $("#btn_add_master_name").data('companyname');
            LoadRoleAccountLevelPermissions(companyname, roleid);
        });

        function LoadRoleAccountLevelPermissions(companyname, roleid) {

            var url = '/' + companyname + '/get-role-account-level/' + roleid;

            $.get(url, function(data, status) {
                var resultarray = JSON.parse(JSON.stringify(data));

                var chkinserts = $(".accountlevel_insert");
                var chkedit = $(".accountlevel_edit");
                var chkdelete = $(".accountlevel_delete");
                var chkview = $(".accountlevel_view");
                var chkprint = $(".accountlevel_print");

                chkinserts.each(function() {

                    var tran = $(this).val();

                    if (resultarray['insert'].includes(tran)) {
                        $(this).prop("checked", true);
                    } else {
                        $(this).prop("checked", false);
                    }
                });

                chkedit.each(function() {

                    var tran = $(this).val();

                    if (resultarray['edit'].includes(tran)) {
                        $(this).prop("checked", true);
                    } else {
                        $(this).prop("checked", false);
                    }
                });

                chkdelete.each(function() {

                    var tran = $(this).val();

                    if (resultarray['delete'].includes(tran)) {
                        $(this).prop("checked", true);
                    } else {
                        $(this).prop("checked", false);
                    }
                });

                chkview.each(function() {

                    var tran = $(this).val();

                    if (resultarray['view'].includes(tran)) {
                        $(this).prop("checked", true);
                    } else {
                        $(this).prop("checked", false);
                    }
                });


                chkprint.each(function() {

                    var tran = $(this).val();

                    if (resultarray['print'].includes(tran)) {
                        $(this).prop("checked", true);
                    } else {
                        $(this).prop("checked", false);
                    }
                });

            });

        }

        $("#btn_cancel_account_level").click(function() {
            var roleid = $("#ddnAccountLevelRoles").val();
            var companyname = $("#btn_add_master_name").data('companyname');
            LoadRoleAccountLevelPermissions(companyname, roleid);

        });

        $("#tblbody_tabnames").on("click", ".btn-edit-save-inbox-tab", function() {
            var id = $(this).data('id');
            if ($("#inbox_tab_edit_mode").val() == 'show') {
                $(this).html("Save");
                $("#td_tabname_" + id).attr("contenteditable", true);
                $("#td_tabname_" + id).focus();
                $("#inbox_tab_edit_mode").val("edit");

                $("#btn_cancel_inboxtab_" + id).removeClass("d-none");
                $("#inbox_tab_edit_id").val(id);
                $("#btn_edit_save_inboxtab_" + id).html("Save");

            } else {
                var newtabname = $("#td_tabname_" + id).text();
                var data = {
                    'id': id,
                    'tab_name': newtabname
                };
                var companyname = $("#btn_add_master_name").data('companyname');
                var url = '/' + companyname + '/update-inbox-tabname';

                $.post(url, data, function(response) {
                    var isvalid = SnackbarMsg(response);
                    if (isvalid) {
                        cancelEditInboxTab(newtabname);
                        getRoleInboxTabsHided();
                    }

                });

            }

        });


        function cancelEditInboxTab(newtabname = '') {
            var id = $("#inbox_tab_edit_id").val();
            $("#td_tabname_" + id).attr("contenteditable", false);

            if (newtabname == '') {
                $("#td_tabname_" + id).html($("#td_tabname_" + id).data('tabname'));
            } else {
                $("#td_tabname_" + id).html(newtabname);
            }

            $("#inbox_tab_edit_id").val("");

            $("#inbox_tab_edit_mode").val("show");

            $("#btn_edit_save_inboxtab_" + id).html("Edit");
            $("#btn_cancel_inboxtab_" + id).addClass("d-none");
            $("#btn_edit_save_inboxtab_" + id).html("Edit");
        }


        $("#tblbody_tabnames").on("click", ".btn-cancel-inbox-tab", function() {
            cancelEditInboxTab();
        });

        $("#tblbody_masternames").on("click", ".btn-edit-save-master", function() {

            var id = $(this).data('id');
            $("#master_edit_id").val(id);

            if ($("#master_edit_mode").val() == 'show') {

                $(this).html("Save");
                $("#td_mastername_" + id).attr("contenteditable", true);
                $("#td_mastername_" + id).focus();
                $("#master_edit_mode").val("edit");
                $("#btn_edit_cancel_master_" + id).removeClass("d-none");
                $("#master_edit_id").val(id);

            } else {

                var newmastername = $("#td_mastername_" + id).text();
                var data = {
                    'id': id,
                    'master_name': newmastername
                };
                var companyname = $("#btn_add_master_name").data('companyname');
                var url = '/' + companyname + '/update-master-name';

                $.post(url, data, function(response) {
                    var isvalid = SnackbarMsg(response);
                    if (isvalid) {
                        cancelEditMaster(newmastername);
                        var roleid=$("#ddnMasterRestrictionRoles").val();
                        LoadRoleRestrictions(roleid);
                    }

                });


            }

        });



        function cancelEditMaster(newmastername = '') {
            var id = $("#master_edit_id").val();
            $("#td_master_" + id).attr("contenteditable", false);

            if (newmastername == '') {
                $("#td_mastername_" + id).html($("#td_mastername_" + id).data('mastername'));
            } else {
                $("#td_mastername_" + id).html(newmastername);
            }

            $("#master_edit_id").val("");

            $("#master_edit_mode").val("show");

            $("#btn_edit_save_master_" + id).html("Edit");
            $("#btn_edit_cancel_master_" + id).addClass("d-none");
        }


        $("#tblbody_masternames").on("click", ".btn-cancel-edit-master", function() {
            cancelEditMaster();

        });

        $("#btn_save_create_user").click(function(){
            var companies=[];

            $(".usercompanies:checked").each(function(){
                var id=$(this).val(); 
                companies.push(id);
            }); 

            var isvalid=true;

            var data=[];
            var rolesentered=true;

            for(let companyid of companies){
                if($("#userRole_"+companyid).val()==''){
                    rolesentered=false; 
                }
                data.push({'cmpid':companyid,'userrole':$("#createUserForm_userRole_"+companyid).val() ,'userhead':$("#createUserForm_userHead_"+companyid).val()});
            }

            if(rolesentered==false){

              SnackBar({    message:"Please select Roles of companies",status:'error'  });

              return false;

            }

            $("#create_user_data").val(JSON.stringify(data)); 

            var companyname = $("#btn_add_master_name").data('companyname');
            // var url='/'+companyname+'/save-user';
            var url="{{URL::to('/save-user')}}";
 
            $.ajax({
                url:url,
                data:$("#createUserForm").serialize() ,
                method:'POST',
                success:function(data){ 
                    SnackbarMsg(data);   
                    loadAllUsers();
                }
            });

            return false;


        });

        function loadEditUserForm(userid){ 

            var url="{{URL::to('/get-user-details')}}/"+userid;

            $("#createUserForm_formmode").val("edit");
            $("#createUserForm_userid").val(userid);

            $.get(url,function(data,status){
           var resultarray=JSON.parse(JSON.stringify(data));

           var user=resultarray['user'];

           $("#createuser_userName").val(user['user_id']);
           
           $("#createuser_userName").attr('disabled',true);

           $("#createuser_userEmail").val(user['email']);

           var nickname=(user['Nickname']==null?'':user['Nickname']);

           $("#createuser_userNickName").val(nickname); 

           $("#createuser_userEmailPass").val("");

           var mobnum=(user['mob_num']==null?'':user['mob_num']);

           $("#createuser_userMobile").val(mobnum);
           $("#createuser_userPassword").val("");

           var usercmps=resultarray['usercompanies'];  

           $(".createuserform_cmpselectrole").val("");
           $(".createuserform_cmpselecthead").val("");


           var userheadid,userroleid;
           for(let usercmp of usercmps){
        
                $(".usercompanies[value='"+usercmp['compid']+"']").prop('checked',true);
                userroleid=(usercmp['roleid']==null?'':usercmp['roleid']); 
                $("#createUserForm_userRole_"+usercmp['compid']).val(userroleid);
                userheadid=(usercmp['user_head_id']==null?'':usercmp['user_head_id']); 
                $("#createUserForm_userHead_"+usercmp['compid']).val( userheadid);

           } 
 
            }); 

        }
 
        $("#createUserForm_edit_user").click(function(){
             var userid= $("#createuser_ddn_select_user").val();
            loadEditUserForm(userid);

        });

        function cancelcreateUserForm(){
            $("#createUserForm_formmode").val("add");
          

          $("#createUserForm_userid").val("");
      
         $("#createuser_userName").attr('disabled',false);
         
         $(".usercompanies").prop('checked',false);

         $(".createuserform_cmpselectrole").val("");
         $(".createuserform_cmpselecthead").val("");

         $("#createuser_userName").val("");
         $("#createuser_userPassword").val("");
         $("#createuser_userEmail").val("");
         $("#createuser_userEmailPass").val("");
         $("#createuser_userMobile").val("");
         $("#createuser_userNickName").val("");   
         $("#createuser_ddn_select_user").val("");

        }

        $("#createUserForm_edit_user_cancel").click(function(){
            
            cancelcreateUserForm();

        });

        $("#btn_cancel_create_user").click(function(){
            
            cancelcreateUserForm();

        });

        function loadAllUsers(){

            $.get("{{URL::to('/get-all-users')}}",function(data,status){

                var users=JSON.parse(JSON.stringify(data))['users'];

                $("#createuser_ddn_select_user option:not(:first)").remove(); 

                for(let id in users){
                    $("#createuser_ddn_select_user").append("<option value='"+id+"'>"+users[id]+"</option>");
                }

            })

        }

        function loadroleReports(roleid){

            var companyname =     $("#ddnFieldValueSelectRoles").data("companyname");

                var url='/'+companyname+'/get-role-reports-from-user/'+roleid;
                $("#reports_ddn_selected_reports").empty();
                $("#reports_ddn_unselected_reports").empty();

                $.get(url,function(data,status){

                    var resultarray=JSON.parse(JSON.stringify(data));

                    for(let select of resultarray['selected']){
                        $("#reports_ddn_selected_reports").append("<option value='"+select['reportid']+"'>"+select['reportname']+"</option>");

                    }

                    for(let unselect of resultarray['unselected']){
                        $("#reports_ddn_unselected_reports").append("<option value='"+unselect['reportid']+"'>"+unselect['reportname']+"</option>");
                    } 

                });

        }


        $("#reports_ddn_select_role").change(function(){
            var roleid=$(this).val();
            loadroleReports(roleid);
          
        });

        $("#btn_reports_unselect").click(function(){

                         var selected=  $("#reports_ddn_selected_reports :selected") ;

                        selected.each(function(){ 
                        var id=$(this).val();
                        var text=$(this).html();
                        $(this).remove();
                        $("#reports_ddn_unselected_reports").append("<option value='"+id+"'>"+text+"</option>"); 
                        }); 
                });


                $("#btn_reports_select").click(function(){

                    var selected=  $("#reports_ddn_unselected_reports :selected") ;

                    selected.each(function(){ 
                    var id=$(this).val();
                    var text=$(this).html();
                    $(this).remove();
                    $("#reports_ddn_selected_reports").append("<option value='"+id+"'>"+text+"</option>"); 
                    }); 
                    });


                                        
                    $("#btn_save_role_reports").click(function(){
                        
                      var companyname =     $("#ddnFieldValueSelectRoles").data("companyname");

                    var url='/'+companyname+'/save-role-reports';

                    var reports=[];

                    var selectedreports=$("#reports_ddn_selected_reports option");

                    selectedreports.each(function(){
                        reports.push($(this).val());
                    });


                    $("#reports_selected").val(JSON.stringify(reports)); 

                    $.ajax({
                    url:url,
                    method:'POST',
                    data:$("#frm_reports").serialize(),
                    success:function(data){  
                        SnackbarMsg(data); 
                        }
                    }); 

                    });

                    $("#btn_cancel_role_reports").click(function(){
                        var roleid=$("#reports_ddn_select_role").val(); 
                        loadroleReports(roleid); 
                    });


                    function loadRoleModules(roleid){

                        var companyname =     $("#ddnFieldValueSelectRoles").data("companyname");

                        var url='/'+companyname+'/get-role-modules/'+roleid;
                        $("#modules_ddn_unselected_modules").empty();
                        $("#modules_ddn_selected_modules").empty();

                        $.get(url, function(data,status){

                            var resultarray=JSON.parse(JSON.stringify(data));

                            for(let select of resultarray['selected']){

                                $("#modules_ddn_selected_modules").append("<option value='"+select['id']+"'>"+select['mname']+"</option>");

                            }

                            for(let unselect of resultarray['unselected']){
                                $("#modules_ddn_unselected_modules").append("<option value='"+unselect['id']+"'>"+unselect['mname']+"</option>");

                            }
                            

                        }); 

                    }

                    $("#modules_ddn_select_role").change(function(){
                        var roleid=$(this).val();
                        loadRoleModules(roleid);
                       
                    });

 
                        $("#btn_modules_select").click(function(){ 
                        var selected=  $("#modules_ddn_unselected_modules :selected") ;

                            selected.each(function(){ 
                            var id=$(this).val();
                            var text=$(this).html();
                            $(this).remove();
                            $("#modules_ddn_selected_modules").append("<option value='"+id+"'>"+text+"</option>"); 
                            }); 


                        });


                        $("#btn_modules_unselect").click(function(){ 
                        var selected=  $("#modules_ddn_selected_modules :selected") ;

                            selected.each(function(){ 
                            var id=$(this).val();
                            var text=$(this).html();
                            $(this).remove();
                            $("#modules_ddn_unselected_modules").append("<option value='"+id+"'>"+text+"</option>"); 
                            });  
                        });


                        $("#btn_save_role_modules").click(function(){
                        
                        var companyname =     $("#ddnFieldValueSelectRoles").data("companyname");
  
                      var url='/'+companyname+'/save-role-modules';
  
                      var modules=[];
  
                      var selectedmodules=$("#modules_ddn_selected_modules option");
  
                      selectedmodules.each(function(){
                          modules.push($(this).val());
                      });
 
                      $("#modules_selected").val(JSON.stringify(modules)); 
  
                      $.ajax({
                      url:url,
                      method:'POST',
                      data:$("#frm_modules").serialize(),
                      success:function(data){  
                          SnackbarMsg(data); 
                          }
                      }); 
  
                      }); 

                      $("#btn_cancel_role_modules").click(function(){
                          var roleid=   $("#modules_ddn_select_role").val();
                          loadRoleModules(roleid); 
                      });


                      $("#btn_save_create_transactions").click(function(){
                          
                        var companyname =     $("#ddnFieldValueSelectRoles").data("companyname");

                        var url=   "{{url('/')}}/"+companyname+'/role-maps';
 

                        var trans=[];

                        var transactions=$("input[name='transactions[]']");

                        transactions.each(function(){
                            trans.push($(this).val());

                        });

                        var data=[];
 
                        var insert=$(".transaction_insertchk:checked");
                        var insertarray=[];
                        insert.each(function(){
                            insertarray.push($(this).val());

                        });
                        var edit=$(".transaction_editchk:checked");
                        var editarray=[];
                        edit.each(function(){
                            editarray.push($(this).val());
                        });
                        var deleting=$(".transaction_deletechk:checked");
                        var deletearray=[];
                        deleting.each(function(){
                            deletearray.push($(this).val());

                        });
                        
                        var view=$(".transaction_viewchk:checked");
                        var viewarray=[];
                        view.each(function(){
                            viewarray.push($(this).val());
                        });

                        
                        var print=$(".transaction_printchk:checked");
                        var printarray=[];
                        print.each(function(){
                            printarray.push($(this).val());
                        });

                        var master=$(".transaction_masterchk:checked");

                        var masterarray=[];
                        master.each(function(){
                            masterarray.push($(this).val());
                        }); 
                        
                        var history=$(".transaction_historychk:checked");
                        var historyarray=[];

                        history.each(function(){
                            historyarray.push($(this).val());
                        });

                        
                        var amend=$(".transaction_ammendchk:checked");
                        var amendarray=[];
                        amend.each(function(){
                            amendarray.push($(this).val());
                        });

                        var copyarray=[]; 
                        var copy=$(".transaction_copychk:checked"); 
                        copy.each(function(){
                            copyarray.push($(this).val());
                        }); 

                        var insertres,editresult,deleteresult,viewresult,printresult,masterresult,historyresult,amendresult,copyresult;

                        for(let tranid of trans){
                            insertres=insertarray.includes(tranid)?'yes':'no';
                            editresult=editarray.includes(tranid)?'yes':'no';
                            deleteresult=deletearray.includes(tranid)?'yes':'no';
                            viewresult=viewarray.includes(tranid)?'yes':'no';
                            printresult=printarray.includes(tranid)?'yes':'no';
                            masterresult=masterarray.includes(tranid)?'yes':'no';
                            historyresult=historyarray.includes(tranid)?'yes':'no';
                            amendresult=amendarray.includes(tranid)?'yes':'no';
                            copyresult=copyarray.includes(tranid)?'yes':'no'; 

                            data.push({'tran_id':tranid,'insert':insertres ,'edit':editresult,'delete':deleteresult,'view':viewresult ,'print':printresult ,'master':masterresult ,'history':historyresult ,'amend':amendresult,'copy':copyresult});

                        } 

                        $("#createTransactions_data").val( JSON.stringify(data)); 

                        $.ajax({
                            url:url,
                            method:'POST',
                            data:$("#frm_create_transactions").serialize(),
                            success:function(data){     SnackbarMsg(data);    }
                            }); 

                      });

                      $("#btn_select_all_create_transactions").click(function(){
                          $(".transaction_chk").prop("checked",true); 

                      });

                      $("#btn_unselect_all_create_transactions").click(function(){
                          $(".transaction_chk").prop("checked",false); 

                      });

                       
       $("#toAppendRoles").on("click", ".btn-edit-save-role-name", function() {
            var id = $(this).data('id');
            if ($("#role_name_edit_mode").val() == 'show') {
                $(this).html("Save");
                $("#td_role_" + id).attr("contenteditable", true);
                $("#td_role_" + id).focus();
                $("#role_name_edit_mode").val("edit");

                $("#btn-cancel-edit-role-name_" + id).removeClass("d-none");
                $("#role_name_edit_id").val(id); 

            } else {
                var newrolename = $("#td_role_" + id).text();
                var data = {
                    'id': id,
                    'role_name': newrolename
                };
                var companyname = $("#btn_add_master_name").data('companyname');
                var url = '/' + companyname + '/roles-menu'; 

                $.post(url, data, function(response) {
                    var isvalid = SnackbarMsg(response);
                    if (isvalid) {
                        cancelEditRoleName(newrolename); 
                    }

                });

            }

        });


        function cancelEditRoleName(newrolename = '') {
            var id = $("#role_name_edit_id").val();
            $("#td_role_" + id).attr("contenteditable", false);

            if (newrolename == '') {

                $("#td_role_" + id).html($("#td_role_" + id).data('rolename'));
            } else {
                $("#td_role_" + id).html(newrolename);
            }

            $("#role_name_edit_id").val("");

            $("#role_name_edit_mode").val("show");

            $("#btn-edit-save-role-name_" + id).html("Edit");
            $("#btn-cancel-edit-role-name_" + id).addClass("d-none"); 
        }


        $("#toAppendRoles").on("click", ".btn-cancel-edit-role-name", function() {
            cancelEditRoleName();
        });


        function getRateSpecRateRestriction(){

            
            var role_id=$("#ddnStockRateRestrictionRoles").val();

            $.get("{{url('/')}}/{{$companyname}}/get-role-stockrate-restrictions/"+role_id,function(data){
 
                var result=JSON.parse(JSON.stringify(data));

                if(result['rate']==true){

                    $("#chk_rate").prop('checked',true);

                }
                else{
                    
                    $("#chk_rate").prop('checked',false); 
                }


                if(result['spec_rate']==true){
                    $("#chk_specrate").prop('checked',true);

                }
                else{
                    $("#chk_specrate").prop('checked',false);
                }

                if(result['show_amount']==true){
                    $("#chk_showamount").prop('checked',true);

                }
                else{
                    $("#chk_showamount").prop('checked',false);
                }
 


            }); 

        }


        $("#ddnStockRateRestrictionRoles").change(function(){

            getRateSpecRateRestriction();

 
        });


        $("#btn_save_role_stockrate_restrictions").click(function(){

            
            var role_id=$("#ddnStockRateRestrictionRoles").val();

            
          var rate=  $("#chk_rate").prop('checked' );

         var spec_rate=   $("#chk_specrate").prop('checked');

         var showamount=$("#chk_showamount").prop('checked');

         $.post("{{url('/')}}/{{$companyname}}/save-stockrate-restriction",{'role_id':role_id,'rate':rate,'spec_rate':spec_rate,'show_amount':showamount},function(data){
 
            SnackbarMsg(data);

            var result=JSON.parse(JSON.stringify(data));

            if(result['rate']==1){
                $("#chk_rate").prop('checked',true )

            }
            else{
                $("#chk_rate").prop('checked',false )
            }

            if(result['spec_rate']==1){
                $("#chk_specrate").prop('checked',true);

                }
                else{
                    $("#chk_specrate").prop('checked',false);
                    
                }

                
            if(result['show_amount']==1){
                $("#chk_showamount").prop('checked',true);

                }
                else{
                    $("#chk_showamount").prop('checked',false);
                    
                }


         });

 
        });
 

    </script>
@endsection
