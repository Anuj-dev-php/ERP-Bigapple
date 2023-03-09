@php

@endphp

@extends('layout.layout')
@section('content')
    <div>
        <span id="showID"></span>
    </div>

    <h2  class="menu-title  mb-5 font-size-18">   Data Restriction </h2>
  <div class="pagecontent" >
  <div class="container-fluid">

    <ul class="nav nav-tabs" id="menutablist" role="tablist">
        <li class="nav-item" role="locations">
            <button class="nav-link active" id="locations-tab" data-bs-toggle="tab" data-bs-target="#location" type="button"
                role="tab" aria-controls="location" aria-selected="true">Locations</button>
        </li>
        <li class="nav-item" role="products">
            <button class="nav-link" id="products-tab" data-bs-toggle="tab" data-bs-target="#products"
                type="button" role="tab" aria-controls="products" aria-selected="false">Products</button>
        </li>
        <li class="nav-item" role="customerSuppliers">
            <button class="nav-link" id="customerSuppliers-tab" data-bs-toggle="tab" data-bs-target="#customerSuppliers"
                type="button" role="tab" aria-controls="customerSuppliers" aria-selected="false">Customers/Suppliers</button>
        </li> 

        <li class="nav-item" role="inbox_tabs">
            <button class="nav-link" id="salesexecutive-tab" data-bs-toggle="tab" data-bs-target="#salesexecutive"
                type="button" role="tab" aria-controls="salesexecutive" aria-selected="false"> Sales Executive</button>
        </li>

        <li class="nav-item" role="restrict_customers">
            <button class="nav-link" id="restrict_customers-tab" data-bs-toggle="tab" data-bs-target="#restrictcustomers"
                type="button" role="tab" aria-controls="restrictcustomers" aria-selected="false"> Restict Customers</button>
        </li>

        <li class="nav-item" role="employees">
            <button class="nav-link" id="employees-tab" data-bs-toggle="tab" data-bs-target="#employees"
                type="button" role="tab" aria-controls="employees" aria-selected="false"> Employees</button>
        </li>


        <li class="nav-item" role="edit_status_tabs">
            <button class="nav-link" id="edit_status_tabs-tab" data-bs-toggle="tab"
                data-bs-target="#editstatus" type="button" role="tab" aria-controls="editstatus"
                aria-selected="false"> Edit Status</button>
        </li>

        <li class="nav-item" role="restriction_tranx">
            <button class="nav-link" id="restriction_tranx-tab" data-bs-toggle="tab"
                data-bs-target="#restriction_tranx" type="button" role="tab" aria-controls="restriction_tranx"
                aria-selected="false"> Restriction Tranx</button>
        </li>

        <li class="nav-item" role="restriction_voucher">
            <button class="nav-link" id="restriction_voucher-tab" data-bs-toggle="tab"
                data-bs-target="#restriction_voucher" type="button" role="tab" aria-controls="restriction_voucher"
                aria-selected="false"> Restriction Voucher</button>
        </li>



        <li class="nav-item" role="month_locking">
            <button class="nav-link" id="month_locking-tab" data-bs-toggle="tab"
                data-bs-target="#monthlocking" type="button" role="tab" aria-controls="monthlocking"
                aria-selected="false"> Month Locking</button>
        </li> 

        <li class="nav-item" role="division">
            <button class="nav-link" id="division-tab" data-bs-toggle="tab"
                data-bs-target="#division" type="button" role="tab" aria-controls="division"
                aria-selected="false">Division</button>
        </li> 


        <li class="nav-item" role="costcenter">
            <button class="nav-link" id="costcenter-tab" data-bs-toggle="tab"
                data-bs-target="#costcenter" type="button" role="tab" aria-controls="costcenter"
                aria-selected="false">Cost Center</button>
        </li> 


        <li class="nav-item" role="profitcenter">
            <button class="nav-link" id="profitcenter-tab" data-bs-toggle="tab"
                data-bs-target="#profitcenter" type="button" role="tab" aria-controls="profitcenter"
                aria-selected="false">Profit Center</button>
        </li> 



    </ul>
</div>


    <div class="tab-content" id="pagemenutablist"  >
        {{-- locations - Data --}}
        <div class="tab-pane fade show active small" id="location" role="tabpanel" aria-labelledby="locations-tab">
            <form id="frm_user_locations" enctype="multipart/form-data" method="post">
            <input type="hidden"  id="company_name"  value="{{Session::get('company_name')}}"  />
            <input type="hidden"  id="location_selected" name="location_selected"  value=""  />

            <div class="row div-controls"   >
                    <div class="col-6 text-end">  
                    <label   class="lbl_control">Select User:</label>
                    </div>
                    <div class="col-4 text-start">
                    <select class="form-control select-configure"   name="user_id" id="ddn_location_select_user"  >
                                <option value="">Select User</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->user_id }}</option>
                                @endforeach 
                            </select>
                    
                    </div> 
             </div> 



            <table style="width: 100%">
                <tbody>
                   
                    <tr>
                        <td align="center"  >
                         
                            
                        <p class="listheading">Unselected Locations</p>
                    <select size="4" name="unselected_locations[]" multiple="multiple" id="location_ddn_unselected_locations"   style="height:227px;width:167px;"></select></td>
                        <td class="div_arrows" ><br>
                        <input type="button" name="" value=">>" id="btn_locations_select"   class="button"><br><br><br><br>
                        <input type="button" name="" value="<<" id="btn_locations_unselect" class="button">
                        </td>
                        <td align="center"   style="padding:20px 0px;"> 
                        
                        <p class="listheading">Selected Locations</p>
                        <select size="4" name="selected_locations[]" multiple    id="location_ddn_selected_locations" style="height:227px;width:40%;margin-top: 0px"></select><br>
                          
                        </td>
                    </tr>
                    <tr>
                        <td  class="div_buttons" colspan="3">
                            <input type="button" name="btnsubmit" value="Save" id="btn_save_user_locations"   class="button btn-primary btn">
                            <input type="button" name="btncancel" value="Cancel" id="btn_cancel_user_locations" class="button  btn-primary btn">
                        </td>
                    </tr>
                </tbody>
            </table>

            </form>
        </div>
        {{-- Products - Data --}}
        <div class="tab-pane fade small" id="products" role="tabpanel" aria-labelledby="products-tab">
           <form id="frm_user_products" enctype="multipart/form-data" method="post"> 
            <input type="hidden"  id="products_selected" name="products_selected"  value=""  />

            
     <div class="row div-controls"   >
         <div class="col-6 text-end" > 
         <label    class="lbl_control">Select User:</label>
         </div>
         <div class="col-4 text-start">

            <select class="form-control select-configure" name="user_id" id="product_ddn_select_user"   >
                <option value="">Select User</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->user_id }}</option>
                @endforeach 
            </select>
       
         </div>
       
         </div > 


            <table style="width: 100%;border:1px o">
                <tbody>
                  
                    <tr>
                        <td align="center"   style="padding:20px 0px;">
                            
                        <p class="listheading">Unselected Products</p>
                        

                    <select size="4" name="unselected_products[]" multiple  id="product_ddn_unselected_products"   style="height:227px;width:167px;"></select></td>
                        <td  class="div_arrows"><br>
                        <input type="button" name="" value=">>" id="btn_products_select"   class="button"><br><br><br><br>
                        <input type="button" name="" value="<<" id="btn_products_unselect" class="button">
                        </td>
                        <td align="center"   style="padding:20px 0px;"> 
                            
                        <p class="listheading">Selected Products</p>
                        

                        <select size="4" name="selected_products[]" multiple    id="product_ddn_selected_products" style="height:227px;width:167px;margin-top: 0px"></select><br>
                          
                        </td>
                    </tr>
                    <tr>
                        <td class="div_buttons" colspan="3">
                            <input type="button" name="btnsubmit" value="Save" id="btn_save_user_products"   class="button btn-primary btn">
                            <input type="button" name="btncancel" value="Cancel" id="btn_cancel_user_products" class="button  btn-primary btn">
                        </td>
                    </tr>
                </tbody>
            </table>

            </form>

        </div>
       <!-- Customer Suppliers Start -->
        <div class="tab-pane fade small" id="customerSuppliers" role="tabpanel" aria-labelledby="customerSuppliers-tab">
        <form id="frm_customer_suppliers" enctype="multipart/form-data" method="post"> 
            <input type="hidden"  id="customers_selected" name="customers_selected"  value=""  />

            
     <div class="row div-controls"   >
        
         <div class="col-6 text-end"  >  
              <label class="lbl_control" >Select User</label>
         </div>
         <div class="col-4 text-start"  >
         <select class="form-control select-configure " name="user_id" id="customers_ddn_select_user"   >
                                <option value="">Select User</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->user_id }}</option>
                                @endforeach 
                            </select>
  
         </div> 
            
         </div> 


            <table style="width: 100%"> 
                <tbody>
                    
                    <tr>
                        <td align="center"   style=" padding-top:20px 0px;"> 
                            <p style="text-align:center;font-weight:bold;">Unselected Customers</p>
                    <select size="4" name="unselected_customers[]" multiple  id="customers_ddn_unselected_customers"   style="height:227px;width:167px;"></select></td>
                        <td  class="div_arrows" ><br>
                        <input type="button" name="" value=">>" id="btn_customers_select"   class="button"><br><br><br><br>
                        <input type="button" name="" value="<<" id="btn_customers_unselect" class="button">
                        </td>
                        <td align="center"  style=" padding:20px 0px;"> 
                        <p style="text-align:center;font-weight:bold;">Selected Customers</p>
                        <select size="4" name="selected_customers[]" multiple    id="customers_ddn_selected_customers" style="height:227px;width:167px;"></select><br>
                            
                        </td>
                    </tr>
                    <tr>
                        <td class="div_buttons" colspan="3">
                            <input type="button" name="btnsubmit" value="Save" id="btn_save_user_customers"   class="button btn-primary btn">
                            <input type="button" name="btncancel" value="Cancel" id="btn_cancel_user_customers" class="button  btn-primary btn">
                        </td>
                    </tr>
                </tbody>
            </table>

            </form>

        </div>
        <!-- Customer SUppliers End -->

        <!-- Sales Executive Start -->

        <div class="tab-pane fade small" id="salesexecutive" role="tabpanel" aria-labelledby="salesexecutive-tab"   >
 
        <form id="frm_sales_executive"   method="post"> 
            <input type="hidden"  id="salesmen_selected" name="salesmen_selected"  value=""  />

            <div class="row div-controls">
                    <div class="col-6 text-end"> 
                    <label    class="lbl_control">Select User:</label>
                    </div>
                    <div class="col-4 text-start">
                    <select class="form-control select-configure " name="user_id" id="salesexecutive_ddn_select_user"   >
                                <option value="">Select User</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->user_id }}</option>
                                @endforeach 
                            </select>
                    </div>


            </div>

        
            <table style="width: 100%"> 
                <tbody>
                 
                    <tr>
                        <td align="center"   style=" padding-top:20px 0px;"> 
                            <p class="listheading">Unselected Salesmen</p>
                    <select size="4" name="unselected_salesman[]" multiple  id="salesman_ddn_unselected"   style="height:227px;width:167px;"></select></td>
                        <td   class="div_arrows" ><br>
                        <input type="button" name="" value=">>" id="btn_salesman_select"   class="button"><br><br><br><br>
                        <input type="button" name="" value="<<" id="btn_salesman_unselect" class="button">
                        </td>
                        <td align="center"   style=" padding:20px 0px;"> 
                        <p  class="listheading">Selected Salesman</p>
                        <select size="4" name="selected_salesman[]" multiple    id="salesman_ddn_selected" style="height:227px;width:167px;"></select><br>
                            
                        </td>
                    </tr>
                    <tr>
                    
                        <td class="div_buttons" colspan="3">
                            <input type="button" name="btnsubmit" value="Save" id="btn_save_user_salesman"   class="button btn-primary btn">
                            &nbsp;
                            <input type="button" name="btncancel" value="Cancel" id="btn_cancel_user_salesman" class="button  btn-primary btn">
                        </td>
                    </tr>
                </tbody>
            </table>

            </form>
  
        </div>

        <!--  Sales Executive end-->

        <!-- Restrict Customer start -->


        <div class="tab-pane fade small" id="restrictcustomers" role="tabpanel" aria-labelledby="restrict_customers-tab"  >

                    
                    <form id="frm_restrict_customers"   method="post"> 
                        <input type="hidden"  id="restrictedcustomers_selected" name="restrictcustomers_selected"  value=""  />

                        <div class="row div-controls">
                                <div class="col-6 text-end"> 
                                <label    class="lbl_control">Select User:</label>
                                </div>
                                <div class="col-4 text-start">
                                <select class="form-control select-configure" name="user_id" id="restrictcustomers_ddn_select_user"   >
                                            <option value="">Select User</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->user_id }}</option>
                                            @endforeach 
                                        </select>
                                </div>


                        </div>

                    
                        <table style="width: 100%"> 
                            <tbody>
                            
                                <tr>
                                    <td align="center"   style=" padding-top:20px 0px;"> 
                                        <p class="listheading">Unrestricted Customers</p>
                                <select size="4" name="unrestricted_customers[]" multiple  id="restrictcustomers_ddn_unselected"   style="height:227px;width:167px;"></select></td>
                                    <td   class="div_arrows" ><br>
                                    <input type="button" name="" value=">>" id="btn_restrictcustomers_select"   class="button"><br><br><br><br>
                                    <input type="button" name="" value="<<" id="btn_restrictcustomers_unselect" class="button">
                                    </td>
                                    <td align="center"   style=" padding:20px 0px;"> 
                                    <p  class="listheading">Restricted Customers</p>
                                    <select size="4" name="selected_customers[]" multiple    id="restrictcustomers_ddn_selected" style="height:227px;width:167px;"></select><br>
                                        
                                    </td>
                                </tr>
                                <tr>
                                
                                    <td class="div_buttons" colspan="3">
                                        <input type="button" name="btnsubmit" value="Save" id="btn_save_user_restrictcustomers"   class="button btn-primary btn">
                                        &nbsp;
                                        <input type="button" name="btncancel" value="Cancel" id="btn_cancel_user_restrictcustomers" class="button  btn-primary btn">
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        </form>
 

        </div>
        <!-- Restrict customer end-->

        <!-- employees tab Start -->

        <div class="tab-pane fade small" id="employees" role="tabpanel" aria-labelledby="mastertabs-tab" >
             
        <form id="frm_employees"   method="post"> 
            <input type="hidden"  id="employees_selected" name="employees_selected"  value=""  />

            <div class="row div-controls"   >
                  <div class="col-6 text-end"> 
                      <label  class="lbl_control"> Select User :</label>
                    </div>
                    <div class="col-4 text-start"> 

                    <select class="form-control select-configure" name="user_id" id="employees_ddn_select_user"  >
                                <option value="">Select User</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->user_id }}</option>
                                @endforeach 
                            </select>

                    </div>
            </div>

            <table style="width: 100%"> 
                <tbody>
                    
                    <tr>
                        <td align="center"   style=" padding-top:20px 0px;"> 
                   
                            <p class="listheading">Unselected Employees</p>
                    <select size="4" name="unselected_employees[]" multiple  id="employees_ddn_unselected"   class="listselection"></select></td>
                        <td   class="div_arrows" ><br>
                        <input type="button" name="" value=">>" id="btn_employees_select"   class="button"><br><br><br><br>
                        <input type="button" name="" value="<<" id="btn_employees_unselect" class="button">
                        </td>
                        <td align="center"  style=" padding:20px 0px;"> 
                        <p  class="listheading">Selected Employees</p>
                        <select size="4" name="selected_employees[]" multiple    id="employees_ddn_selected"   class="listselection"></select><br>
                            
                        </td>
                    </tr>
                    <tr>
                        <td  class="div_buttons" colspan="3">
                            <input type="button" name="btnsubmit" value="Save" id="btn_save_user_employee"   class="button btn-primary btn">
                            <input type="button" name="btncancel" value="Cancel" id="btn_cancel_user_employee" class="button  btn-primary btn">
                        </td>
                    </tr>
                </tbody>
            </table>

            </form> 

        </div>

        <!-- employees  tab Ends -->

        <!-- Edit Status Starts -->

        <div class="tab-pane fade small" id="editstatus" role="tabpanel" aria-labelledby="editstatus-tab"  >
              
        <form id="frm_edit_status"  class="form-horizontal"     method="post"> 
            <input type="hidden"  id="editstatus_selected" name="editstatus_selected"  value=""  />
            
            <input type="hidden" id="edit_status_user_id" name="user_id" />

            <div class="row div-controls"   >
                    <div class="col-2 text-end"> 
                        <label class="lbl_control">Select User :</label>
                        </div>
                        <div class="col-4 text-start">  
                                <select class="form-control select-configure" name="role_id" id="editstatus_ddn_select_user"  >
                                        <option value="">Select User</option>
                                        @foreach ($users as $user)
                                            <option data-userid="{{$user->id}}" value="{{ $user->role_id }}">{{ $user->user_id }}</option>
                                        @endforeach 
                                    </select> 

                        </div>
                        <div class="col-2 text-end"> 
                        <label class="lbl_control">Select Table :</label>
                        </div>
                        <div class="col-3 text-start"> 
                        <select class="form-control select-configure " name="table_name" id="editstatus_ddn_select_tablename"   style="width:145px;">
                          <option value="">Select Table</option> 
                      </select>
                        </div>
                </div> 
                <table  style="width: 100%">

                    <tr>
                        <td align="center"  style=" padding-top:20px 0px;"> 
                   
                            <p class="listheading">Unselected Status</p>
                    <select size="4" name="unselected_status[]" multiple  id="editstatus_ddn_unselected"   class="listselection"></select></td>
                        <td  class="div_arrows" ><br>
                        <input type="button" name="" value=">>" id="btn_editstatus_select"   class="button"><br><br><br><br>
                        <input type="button" name="" value="<<" id="btn_editstatus_unselect" class="button">
                        </td>
                        <td align="center"  style=" padding:20px 0px;"> 
                        <p  class="listheading">Selected Status</p>
                        <select size="4" name="selected_status[]" multiple    id="editstatus_ddn_selected"   class="listselection"></select><br>
                            
                        </td>
                    </tr>
                    <tr>
                        <td class="div_buttons" colspan="3">
                            <input type="button" name="btnsubmit" value="Save" id="btn_save_user_edit_status"   class="button btn-primary btn">
                            <input type="button" name="btncancel" value="Cancel" id="btn_cancel_user_edit_status" class="button  btn-primary btn">
                        </td>
                    </tr>
                </tbody>
            </table>

            </form> 


        </div>

        <!-- edit status ends -->

        <!--   Restriction Transaction starts --> 
        <div class="tab-pane fade small" id="restriction_tranx" role="tabpanel" aria-labelledby="restriction_tranx-tab" > 
     
            
        <form id="frm_restrict_tranx"  class="form-inline"     method="post">   
        <div class="row div-controls"   >
            <div class="col-2 text-end"> 
            <label   for="restrictiontranx_ddn_select_user" class="lbl_control">Select User:</label>
            </div>
            <div class="col-4 text-start">
            <select class="form-control select-configure" name="role_id" id="restrictiontranx_ddn_select_user"    >
                                <option value="">Select User</option>
                                @foreach ($users as $user)
                                    <option data-userid="{{$user->id}}" value="{{ $user->role_id }}">{{ $user->user_id }}</option>
                                @endforeach 
                            </select> 
            </div>
            <div class="col-2 text-end"  > 
            <input type="hidden" name="user_id" id="restrictiontranx_user_id" /> 
            <label for="restrictiontranx_ddn_select_user"   class="lbl_control" >Select Table:</label>
            </div>
            <div class="col-4 text-start"  >
            <select class="form-control select-configure" name="table_id" id="restrictiontranx_select_tablename"   >
                          <option value="">Select Table</option>
                         
                      </select>
            </div> 
               
            </div>  
                <table  style="width: 100%;min-height:200px;">

                    <tr>
                        <td align="center" style=" padding:20px 0px" class="spaceExa"> 
                        <label class="lbl_control">Cannot add after:</label>
                        <label><input type="number" name="add_after_days" class="form-control select-configure" id="restrictiontranx_add_after" style="width:200px"  min="1"  /></label> <label   class="lbl_control">days</label>
                        </td> 
                    </tr>

                    <tr>
                        <td align="center"   style=" padding:20px 0px;margin-top:-400px" class="spaceExa"> 
                        <label  class="lbl_control">Cannot edit after:</label>
                       <label> <input type="number" name="edit_after_days"  class="form-control select-configure"  id="restrictiontranx_edit_after" style="width:200px"   min="1" /></label> <label   class="lbl_control">days</label>
                        </td> 
                    </tr>

                    <tr> 
                        <td align="center"  style=" padding:20px 0px;" > 
                        <label   class="lbl_control">Cannot delete after</label>
                        <label><input type="number" name="delete_after_days" class="form-control select-configure"  style="width:200px"  id="restrictiontranx_delete_after" min="1" /> </label><label   class="lbl_control">days</label>
                        </td> 
                    </tr>
                    <tr>
                        <td class="div_buttons">
                        <input type="button" name="btnsubmit" value="Save" id="btn_save_user_restrictiontranx"   class="button btn-primary btn">
                            <input type="button" name="btncancel" value="Cancel" id="btn_cancel_user_restrictiontranx" class="button btn-primary btn">
              
                        </td>
                    </tr> 
                </tbody>
            </table> 
            </form>  
        </div>
        <!-- Restriction Transaction ends -->

        <!-- Restrcition Transaction Voucher starts -->

        <div class="tab-pane fade small" id="restriction_voucher" role="tabpanel" aria-labelledby="restriction_voucher-tab" > 
     
            
        <form id="frm_restrict_voucher"  class="form-inline"     method="post">  


        <div class="row div-controls"   >
            <div class="col-2 text-end"> 
            <label   for="restrictionvoucher_ddn_select_user" class="lbl_control">Select User:</label>
            </div>
            <div class="col-4 text-start">
            <select class="form-control select-configure" name="role_id" id="restrictionvoucher_ddn_select_user"    >
                                <option value="">Select User</option>
                                @foreach ($users as $user)
                                    <option data-userid="{{$user->id}}" value="{{ $user->role_id }}">{{ $user->user_id }}</option>
                                @endforeach 
                            </select> 
            </div>
            <div class="col-2 text-end"  > 
            <input type="hidden" name="user_id" id="restrictionvoucher_user_id" /> 
            <label    class="lbl_control" >Select Voucher:</label>
            </div>
            <div class="col-4 text-start"  >
            <select class="form-control select-configure" name="vch_id" id="restrictionvoucher_select_vchtype"   >
                          <option value="">Select Voucher</option>
                         
                      </select>
            </div> 
               
            </div>  
                <table  style="width: 100%;min-height:400px;">

                    <tr>
                        <td align="center"   style=" padding:20px 0px;"> 
                        <label class="lbl_control">Cannot add after:</label>
                        <label><input type="number" class="form-control select-configure" name="add_after_days" id="restrictionvoucher_add_after" style="width:200px"  min="1"  /></label> <label   class="lbl_control">days</label>
                        </td> 
                    </tr>

                    <tr>
                        <td align="center"  style=" padding:20px 0px;"> 
                        <label  class="lbl_control">Cannot edit after:</label>
                     <label>   <input type="number" name="edit_after_days" class="form-control select-configure" style="width:200px"   id="restrictionvoucher_edit_after"   min="1" /></label> <label   class="lbl_control">days</label>
                        </td> 
                    </tr>

                    <tr>
                        <td align="center"  style=" padding:20px 0px;"> 
                        <label   class="lbl_control">Cannot delete after</label>
                        <label><input type="number" name="delete_after_days"  class="form-control select-configure" style="width:200px" id="restrictionvoucher_delete_after" min="1" /> </label><label   class="lbl_control">days</label>
                        </td> 
                    </tr>
                    <tr>
                        <td class="div_buttons">
                        <input type="button" name="btnsubmit" value="Save" id="btn_save_user_restrictionvoucher"   class="button btn-primary btn">
                            <input type="button" name="btncancel" value="Cancel" id="btn_cancel_user_restrictionvoucher" class="button btn-primary btn">
              
                        </td>
                    </tr> 
                </tbody>
            </table> 
            </form>  
        </div>


        
        <!-- Restrcition Transaction Voucher ends -->

<!-- month locking start -->
        <div class="tab-pane fade small" id="monthlocking" role="tabpanel" aria-labelledby="monthlocking-tab" > 

        <form id="frm_month_locking"  class="form-horizontal"     method="post"> 
            <input type="hidden"  id="monthlocking_selected" name="monthlocking_selected"  value=""  />
             
            <div class="row div-controls"   >
                    
                        <div class="col-6 text-end"> 
                        <label class="lbl_control">Select Month :</label>
                        </div>
                        <div class="col-4 text-start"> 
                        <select class="form-control select-configure" name="month" id="monthlocking_ddn_select_month"  >
                          <option value="">Select Month</option> 
                          <option value="1">Jan</option>
                          <option value="2">Feb</option>
                          <option value="3">March</option>
                          <option value="4">April</option>
                          <option value="5">May</option>
                          <option value="6">June</option>
                          <option value="7">July</option>
                          <option value="8">August</option>
                          <option value="9">September</option>
                          <option value="10">October</option>
                          <option value="11">November</option>
                          <option value="12">December</option>
                      </select>
                        </div>
                </div> 
                <table  style="width: 100%">

                    <tr>
                        <td align="center"  style=" padding-top:20px 0px;"> 
                   
                            <p class="listheading">Unblocked Roles</p>
                    <select size="4" name="unselected_roles[]" multiple  id="monthlocking_role_ddn_unselected"   class="listselection"></select></td>
                        <td  class="div_arrows" ><br>
                        <input type="button" name="" value=">>" id="btn_monthlocking_role_select"   class="button"><br><br><br><br>
                        <input type="button" name="" value="<<" id="btn_monthlocking_role_unselect" class="button">
                        </td>
                        <td align="center"  style=" padding:20px 0px;"> 
                        <p  class="listheading">Blocked Roles</p>
                        <select size="4" name="selected_roles[]" multiple    id="monthlocking_role_ddn_selected"   class="listselection"></select><br>
                            
                        </td>
                    </tr>
                    <tr>
                        <td class="div_buttons" colspan="3">
                            <input type="button" name="btnsubmit" value="Save" id="btn_save_month_locking"   class="button btn-primary btn">
                            <input type="button" name="btncancel" value="Cancel" id="btn_cancel_month_locking" class="button  btn-primary btn">
                        </td>
                    </tr>
                </tbody>
            </table>

            </form> 



        </div>
        <!-- month locking end -->

 <!-- division starts -->
        <div class="tab-pane fade small" id="division" role="tabpanel" aria-labelledby="division-tab" > 

<form id="frm_division"  class="form-horizontal"     method="post"> 
    <input type="hidden"  id="division_selected" name="division_selected"  value=""  />
     
    <div class="row div-controls"   >
            
                <div class="col-6 text-end"> 
                <label class="lbl_control">Select User :</label>
                </div>
                <div class="col-4 text-start"> 
                <select class="form-control select-configure" name="user" id="division_ddn_select_user"  >
                  <option value="">Select User</option> 
                  @foreach ($users as $user)
                                    <option  value="{{$user->id }}">{{ $user->user_id }}</option>
                                @endforeach 
 
              </select>
                </div>
        </div> 
        <table  style="width: 100%">

            <tr>
                <td align="center"  style=" padding-top:20px 0px;"> 
           
                    <p class="listheading">Unselected Divisions</p>
            <select size="4" name="unselected_divisions[]" multiple  id="division_ddn_unselected"   class="listselection"></select></td>
                <td  class="div_arrows" ><br>
                <input type="button" name="" value=">>" id="btn_division_select"   class="button"><br><br><br><br>
                <input type="button" name="" value="<<" id="btn_division_unselect" class="button">
                </td>
                <td align="center"  style=" padding:20px 0px;"> 
                <p  class="listheading">Selected Divisions</p>
                <select size="4" name="selected_divisions[]" multiple    id="division_ddn_selected"   class="listselection"></select><br>
                    
                </td>
            </tr>
            <tr>
                <td class="div_buttons" colspan="3">
                    <input type="button" name="btnsubmit" value="Save" id="btn_save_user_divisions"   class="button btn-primary btn">
                    <input type="button" name="btncancel" value="Cancel" id="btn_cancel_user_divisions" class="button  btn-primary btn">
                </td>
            </tr>
        </tbody>
    </table>

    </form> 



</div>
<!-- division ends -->
 

 <!-- cost center starts -->
 <div class="tab-pane fade small" id="costcenter" role="tabpanel" aria-labelledby="costcenter-tab" > 

<form id="frm_costcenter"  class="form-horizontal"     method="post"> 
    <input type="hidden"  id="costcenter_selected" name="costcenter_selected"  value=""  />
     
    <div class="row div-controls"   >
            
                <div class="col-6 text-end"> 
                <label class="lbl_control">Select User :</label>
                </div>
                <div class="col-4 text-start"> 
                <select class="form-control select-configure" name="user" id="costcenter_ddn_select_user"  >
                  <option value="">Select User</option> 
                  @foreach ($users as $user)
                                    <option  value="{{$user->id }}">{{ $user->user_id }}</option>
                                @endforeach 
 
              </select>
                </div>
        </div> 
        <table  style="width: 100%">

            <tr>
                <td align="center"  style=" padding-top:20px 0px;"> 
           
                    <p class="listheading">Unselected Cost <br/> Centers</p>
            <select size="4" name="unselected_costcenters[]" multiple  id="costcenters_ddn_unselected"   class="listselection"></select></td>
                <td  class="div_arrows" ><br>
                <input type="button" name="" value=">>" id="btn_costcenter_select"   class="button"><br><br><br><br>
                <input type="button" name="" value="<<" id="btn_costcenter_unselect" class="button">
                </td>
                <td align="center"  style=" padding:20px 0px;"> 
                <p  class="listheading">Selected Cost  <br/> Centers</p>
                <select size="4" name="selected_costcenters[]" multiple    id="costcenters_ddn_selected"   class="listselection"></select><br>
                    
                </td>
            </tr>
            <tr>
                <td class="div_buttons" colspan="3">
                    <input type="button" name="btnsubmit" value="Save" id="btn_save_user_costcenters"   class="button btn-primary btn">
                    <input type="button" name="btncancel" value="Cancel" id="btn_cancel_user_costcenters" class="button  btn-primary btn">
                </td>
            </tr>
        </tbody>
    </table>

    </form> 
 
</div>
<!-- cost center ends -->
 
 <!-- profit center starts -->
 <div class="tab-pane fade small" id="profitcenter" role="tabpanel" aria-labelledby="profitcenter-tab" > 

<form id="frm_profitcenter"  class="form-horizontal"     method="post"> 
    <input type="hidden"  id="profitcenter_selected" name="profitcenter_selected"  value=""  />
     
    <div class="row div-controls"   >
            
                <div class="col-6 text-end"> 
                <label class="lbl_control">Select User :</label>
                </div>
                <div class="col-4 text-start"> 
                <select class="form-control select-configure" name="user" id="profitcenter_ddn_select_user"  >
                  <option value="">Select User</option> 
                  @foreach ($users as $user)
                                    <option  value="{{$user->id }}">{{ $user->user_id }}</option>
                                @endforeach 
 
              </select>
                </div>
        </div> 
        <table  style="width: 100%">

            <tr>
                <td align="center"  style=" padding-top:20px 0px;"> 
           
                    <p class="listheading">Unselected Profit <br/> Centers</p>
            <select size="4" name="unselected_profitcenters[]" multiple  id="profitcenters_ddn_unselected"   class="listselection"></select></td>
                <td  class="div_arrows" ><br>
                <input type="button" name="" value=">>" id="btn_profitcenter_select"   class="button"><br><br><br><br>
                <input type="button" name="" value="<<" id="btn_profitcenter_unselect" class="button">
                </td>
                <td align="center"  style=" padding:20px 0px;"> 
                <p  class="listheading">Selected Profit  <br/> Centers</p>
                <select size="4" name="selected_profitcenters[]" multiple    id="profitcenters_ddn_selected"   class="listselection"></select><br>
                    
                </td>
            </tr>
            <tr>
                <td class="div_buttons" colspan="3">
                    <input type="button" name="btnsubmit" value="Save" id="btn_save_user_profitcenters"   class="button btn-primary btn">
                    <input type="button" name="btncancel" value="Cancel" id="btn_cancel_user_profitcenters" class="button  btn-primary btn">
                </td>
            </tr>
        </tbody>
    </table>

    </form> 
 
</div>
<!-- cost center ends -->

 
        </div>
    </div>
@endsection
@section('js')
    {{-- ROLE --}}
  
    <script type="text/javascript">
 
$("#ddn_location_select_user").change(function(){
    var userid=$(this).val();
    var companyname=$("#company_name").val();  
    loadUserLocations(companyname,userid);
  

});

function loadUserLocations(companyname,userid){

    var url='/'+companyname+'/data-restrictions/user-locations/'+userid; 
    $("#location_ddn_unselected_locations").empty();
        $("#location_ddn_selected_locations").empty();  
    $.get(url,function(data,status){  

        var resultarray=JSON.parse(JSON.stringify(data));

        var unselected=resultarray['unselected'];
        var selected=resultarray['selected'];
 

        for(let unselect of unselected){
            $("#location_ddn_unselected_locations").append("<option value='"+unselect['id']+"'>"+unselect['location']+"</option>");

        } 

        for(let select of selected){
            $("#location_ddn_selected_locations").append("<option value='"+select['id']+"'>"+select['location']+"</option>");

        }

        }); 

}

$("#btn_locations_select").click(function(){

  var selected=  $("#location_ddn_unselected_locations :selected") ;

  selected.each(function(){

    var id=$(this).val();
    var text=$(this).html();
    $(this).remove();
    $("#location_ddn_selected_locations").append("<option value='"+id+"'>"+text+"</option>"); 
  });
 
 
});

$("#btn_locations_unselect").click(function(){
    var selected=  $("#location_ddn_selected_locations :selected") ;

        selected.each(function(){

        var id=$(this).val();
        var text=$(this).html();
        $(this).remove();
        $("#location_ddn_unselected_locations").append("<option value='"+id+"'>"+text+"</option>"); 
        }); 
});

    $("#btn_cancel_user_locations").click(function(){
        var companyname=$("#company_name").val();   
        var userid=$("#ddn_location_select_user").val();

        loadUserLocations(companyname,userid)

    });

    $("#btn_save_user_locations").click(function(){
 
    var companyname=$("#company_name").val();  
    var url='/'+companyname+'/data-restrictions/user-locations'; 

    var locs=[];

  var selectedlocations=$("#location_ddn_selected_locations option");

  selectedlocations.each(function(){
      locs.push($(this).val());
  });
 
  $("#location_selected").val(JSON.stringify(locs)); 

        $.ajax({
            method:'POST',
            url:url,
            data:$("#frm_user_locations").serialize(),
            success:function(data){
                SnackbarMsg(data);
            }
        });
    });


    function loadUserProducts(userid){
        
        
        var url='/'+$("#company_name").val()+'/data-restrictions/user-products/'+userid;
        $("#product_ddn_unselected_products").empty();
        $("#product_ddn_selected_products").empty(); 
        
        
        $.get(url,function(data,status){

                var resultarray=JSON.parse(JSON.stringify(data));

                var selected=resultarray['selected'];
                var unselected=resultarray['unselected']; 
                
                for(let select of selected){
                    $("#product_ddn_selected_products").append("<option value='"+select['Id']+"'>"+select['Product']+"</option>");

                }

                for(let unselect of unselected){
                    $("#product_ddn_unselected_products").append("<option value='"+unselect['Id']+"'>"+unselect['Product']+"</option>");

                } 
                });

    }

    $("#product_ddn_select_user").change(function(){
        var userid=$(this).val();
        loadUserProducts(userid);
    });

    $("#btn_cancel_user_products").click(function(){
        var userid=    $("#product_ddn_select_user").val();
        loadUserProducts(userid);

    });


    $("#btn_save_user_products").click(function(){
        
        var url='/'+$("#company_name").val()+'/data-restrictions/save-user-products';

            var prds=[];

        var selectedproducts=$("#product_ddn_selected_products option");

        selectedproducts.each(function(){
            prds.push($(this).val());
        });
 
        $("#products_selected").val(JSON.stringify(prds));

        $.ajax({
            url:url,
            method:'POST',
            data:$("#frm_user_products").serialize(),
            success:function(data){
                SnackbarMsg(data);
            }
        }); 
    });
 
$("#btn_products_select").click(function(){

var selected=  $("#product_ddn_unselected_products :selected") ;

selected.each(function(){

  var id=$(this).val();
  var text=$(this).html();
  $(this).remove();
  $("#product_ddn_selected_products").append("<option value='"+id+"'>"+text+"</option>"); 
});


});

$("#btn_products_unselect").click(function(){
  var selected=  $("#product_ddn_selected_products :selected") ;

      selected.each(function(){

      var id=$(this).val();
      var text=$(this).html();
      $(this).remove();
      $("#product_ddn_unselected_products").append("<option value='"+id+"'>"+text+"</option>"); 
      }); 
});


$("#customers_ddn_select_user").change(function(){
    var userid=$(this).val(); 
    loadUserCustomers(userid);
    
});

function loadUserCustomers(userid){

    var url='/'+$("#company_name").val()+'/data-restrictions/user-customers/'+userid;
 
 $.get(url,function(data,status){

     var resultarray=JSON.parse(JSON.stringify(data));

     var selected=resultarray['selected'];

     var unselected=resultarray['unselected'];
     $("#customers_ddn_selected_customers").empty();

     for(let select of selected){
         
         $("#customers_ddn_selected_customers").append("<option value='"+select['Id']+"'>"+select['cust_id']+"</option>");

     }
     $("#customers_ddn_unselected_customers").empty();
     for(let unselect of  unselected){ 
         
         $("#customers_ddn_unselected_customers").append("<option value='"+unselect['Id']+"'>"+unselect['cust_id']+"</option>"); 
     }
 });

}
 


$("#btn_customers_select").click(function(){

var selected=  $("#customers_ddn_unselected_customers :selected") ;

selected.each(function(){

  var id=$(this).val();
  var text=$(this).html();
  $(this).remove();
  $("#customers_ddn_selected_customers").append("<option value='"+id+"'>"+text+"</option>"); 
});


});

$("#btn_customers_unselect").click(function(){
  var selected=  $("#customers_ddn_selected_customers :selected") ;

      selected.each(function(){

      var id=$(this).val();
      var text=$(this).html();
      $(this).remove();
      $("#customers_ddn_unselected_customers").append("<option value='"+id+"'>"+text+"</option>"); 
      }); 
});


$("#btn_save_user_customers").click(function(){

            var url='/'+$("#company_name").val()+'/data-restrictions/save-user-customers';

            var custs=[];

            var selectedcusts=$("#customers_ddn_selected_customers option");

            selectedcusts.each(function(){
            custs.push($(this).val());
            });

           $("#customers_selected").val(JSON.stringify(custs));

            $.ajax({
            url:url,
            method:'POST',
            data:$("#frm_customer_suppliers").serialize(),
            success:function(data){
                SnackbarMsg(data);
                    }
            }); 

});


$("#btn_cancel_user_customers").click(function(){
    var userid=$("#customers_ddn_select_user").val(); 
    loadUserCustomers(userid); 

});

$("#salesexecutive_ddn_select_user").change(function(){
    var userid=$(this).val(); 
    loadUserSalesman(userid); 

});

function loadUserSalesman(userid){

    var url='/'+$("#company_name").val()+'/data-restrictions/user-salesman/'+userid;
    $("#salesman_ddn_unselected").empty();
    $("#salesman_ddn_selected").empty(); 

    $.get(url,function(data,status){
        
      var resultarray=  JSON.parse(JSON.stringify(data));
 
      var selected=resultarray['selected'];

      var unselected=resultarray['unselected'];


      for(let select of selected){
        $("#salesman_ddn_selected").append("<option value='"+select['Id']+"'>"+select['Name']+"</option>"); 
      }

      for(let unselect of  unselected){
          $("#salesman_ddn_unselected").append("<option value='"+unselect['Id']+"'>"+unselect['Name']+"</option>"); 

      } 

    }); 

}
 
$("#btn_salesman_select").click(function(){

        var selected=  $("#salesman_ddn_unselected :selected") ;

        selected.each(function(){ 
        var id=$(this).val();
        var text=$(this).html();
        $(this).remove();
        $("#salesman_ddn_selected").append("<option value='"+id+"'>"+text+"</option>"); 
        }); 
});


$("#btn_salesman_unselect").click(function(){

var selected=  $("#salesman_ddn_selected :selected") ;

selected.each(function(){ 
var id=$(this).val();
var text=$(this).html();
$(this).remove();
$("#salesman_ddn_unselected").append("<option value='"+id+"'>"+text+"</option>"); 
}); 
});

$("#btn_cancel_user_salesman").click(function(){
    var userid=$("#salesexecutive_ddn_select_user").val();
    loadUserSalesman(userid) 
});


$("#btn_save_user_salesman").click(function(){

            var url='/'+$("#company_name").val()+'/data-restrictions/save-user-salesman';

            var salesman=[];

            var selectedsalesman=$("#salesman_ddn_selected option");

            selectedsalesman.each(function(){
                salesman.push($(this).val());
            });
 
 
            $("#salesmen_selected").val(JSON.stringify(salesman)); 

            $.ajax({
            url:url,
            method:'POST',
            data:$("#frm_sales_executive").serialize(),
            success:function(data){  
                 SnackbarMsg(data); 
                  }
            }); 

}); 

function loaduserEmployees(userid){
    var url='/'+$("#company_name").val()+'/data-restrictions/user-employees/'+userid;
 
 $.get(url,function(data,status){

     var resultarray=JSON.parse(JSON.stringify(data));

     var selected=resultarray['selected'];

     var unselected=resultarray['unselected'];
     $("#employees_ddn_selected").empty();

     for(let select of selected){
         
         $("#employees_ddn_selected").append("<option value='"+select['ID']+"'>"+select['EmployeeName']+"</option>");

     }
     $("#employees_ddn_unselected").empty();
     for(let unselect of  unselected){ 
         
         $("#employees_ddn_unselected").append("<option value='"+unselect['ID']+"'>"+unselect['EmployeeName']+"</option>"); 
     }
 });

}


$("#employees_ddn_select_user").change(function(){
    var userid=$(this).val();
    loaduserEmployees(userid); 
});



$("#btn_employees_select").click(function(){

            var selected=  $("#employees_ddn_unselected :selected") ;
            selected.each(function(){ 
            var id=$(this).val();
            var text=$(this).html();
            $(this).remove();
            $("#employees_ddn_selected").append("<option value='"+id+"'>"+text+"</option>"); 
            }); 


});




$("#btn_employees_unselect").click(function(){

        var selected=  $("#employees_ddn_selected :selected") ;

        selected.each(function(){ 
        var id=$(this).val();
        var text=$(this).html();
        $(this).remove();
        $("#employees_ddn_unselected").append("<option value='"+id+"'>"+text+"</option>"); 
        }); 
});


$("#btn_cancel_user_employee").click(function(){
  var userid=  $("#employees_ddn_select_user").val();
  loaduserEmployees(userid);

});



$("#btn_save_user_employee").click(function(){

var url='/'+$("#company_name").val()+'/data-restrictions/save-user-employees';

var employees=[];

var selectedemployees=$("#employees_ddn_selected option");

selectedemployees.each(function(){
    employees.push($(this).val());
});


$("#employees_selected").val(JSON.stringify(employees)); 

$.ajax({
url:url,
method:'POST',
data:$("#frm_employees").serialize(),
success:function(data){  
     SnackbarMsg(data); 
      }
}); 

}); 


$("#editstatus_ddn_select_user").change(function(){
    var roleid=$(this).val();
    var url="{{url('/')}}"+'/'+$("#company_name").val()+'/role-trans/'+roleid;
    var userid=$(this).children("option:selected").data("userid"); 
    $("#edit_status_user_id").val(userid);

    $.get(url,function(data,status){
        var resultarray=JSON.parse(JSON.stringify(data));

        var tables=resultarray['tables']; 
        $("#editstatus_ddn_select_tablename option:not(:first)").remove();

        for(let table of tables){
            $("#editstatus_ddn_select_tablename").append("<option value='"+table['Table_Name']+"'>"+table['table_label']+"</option>");

        } 

    }); 

});

function loadEditStatusFromUserAndTable(userid,tablename){

    var url='/'+$("#company_name").val()+'/data-restrictions/get-status-from-username-table/' ;
    var data={'userid':userid,'tablename':tablename};

    $("#editstatus_ddn_unselected").empty();
    $("#editstatus_ddn_selected").empty();

    $.ajax({
        url:url,
        method:'POST',
        data:data,
        success:function(data){ 
          var resultarray=JSON.parse(JSON.stringify(data));

          var selected=resultarray['selected'];
          var unselected=resultarray['unselected'];

          for(let unselect of unselected){
              $("#editstatus_ddn_unselected").append("<option value='"+unselect['id']+"'>"+unselect['StatusName']+"</option>");

          }

          for(let select of selected){
              $("#editstatus_ddn_selected").append("<option value='"+select['id']+"'>"+select['StatusName']+"</option>");

          } 
        }

    });


}

$("#editstatus_ddn_select_tablename").change(function(){ 
    var userid=$("#editstatus_ddn_select_user :selected").data('userid');;
    var tablename=   $(this).val();
    loadEditStatusFromUserAndTable(userid,tablename)
   
});




$("#btn_editstatus_select").click(function(){

        var selected=  $("#editstatus_ddn_unselected :selected") ;
        selected.each(function(){ 
        var id=$(this).val();
        var text=$(this).html();
        $(this).remove();
        $("#editstatus_ddn_selected").append("<option value='"+id+"'>"+text+"</option>"); 
        }); 


});

$("#btn_editstatus_unselect").click(function(){

            var selected=  $("#editstatus_ddn_selected :selected") ;

            selected.each(function(){ 
            var id=$(this).val();
            var text=$(this).html();
            $(this).remove();
            $("#editstatus_ddn_unselected").append("<option value='"+id+"'>"+text+"</option>"); 
            }); 
});

$("#btn_cancel_user_edit_status").click(function(){
    var userid=$("#editstatus_ddn_select_user").children("option:selected").data("userid");  
    var tablename=   $("#editstatus_ddn_select_tablename").val();
    loadEditStatusFromUserAndTable(userid,tablename)
    
});

$("#btn_save_user_edit_status").click(function(){

                
            var url='/'+$("#company_name").val()+'/data-restrictions/save-user-edit-status';

            var status=[];

            var selectedstatus=$("#editstatus_ddn_selected option");

            selectedstatus.each(function(){
                status.push($(this).val());
            }); 

            $("#editstatus_selected").val(JSON.stringify(status)); 

            $.ajax({
            url:url,
            method:'POST',
            data:$("#frm_edit_status").serialize(),
            success:function(data){  
                SnackbarMsg(data); 
                }
            }); 

});


$("#restrictiontranx_ddn_select_user").change(function(){
    var roleid=$(this).val();
    var url="{{url('/')}}"+'/'+$("#company_name").val()+'/role-trans/'+roleid;
  
    var userid=$(this).children("option:selected").data("userid"); 
    $("#restrictiontranx_user_id").val(userid);

    $.get(url,function(data,status){
        var resultarray=JSON.parse(JSON.stringify(data)); 

        var tables=resultarray['tables']; 
        $("#restrictiontranx_select_tablename option:not(:first)").remove();

        for(let table of tables){
            $("#restrictiontranx_select_tablename").append("<option value='"+table['Id']+"'>"+table['table_label']+"</option>");

        } 

    }); 

});

function loadRestrictTrax(userid,tableid){

    var url='/'+$("#company_name").val()+'/data-restrictions/get-retrict-tranx-from-username-table' ;
    var data={'userid':userid,'tableid':tableid};

        $("#restrictiontranx_add_after").val("");

        $("#restrictiontranx_edit_after").val("");

        $("#restrictiontranx_delete_after").val(""); 
 
    $.ajax({
        url:url,
        method:'POST',
        data:data,
        success:function(data){    
          var resultarray=JSON.parse(JSON.stringify(data))['days'];
          $("#restrictiontranx_add_after").val(resultarray['add_days']);
          $("#restrictiontranx_edit_after").val(resultarray['edit_days']);
          $("#restrictiontranx_delete_after").val(resultarray['delete_days']);
 
        }

    }); 
}



$("#restrictiontranx_select_tablename").change(function(){ 
    var userid=$("#restrictiontranx_ddn_select_user :selected").data('userid');;
    var tableid=   $(this).val(); 
    loadRestrictTrax(userid,tableid);
   
});

$("#btn_cancel_user_restrictiontranx").click(function(){ 
    var userid=$("#restrictiontranx_ddn_select_user :selected").data('userid');;
    var tableid=   $("#restrictiontranx_select_tablename").val(); 
    loadRestrictTrax(userid,tableid);
});

$("#btn_save_user_restrictiontranx").click(function(){

    
    var url='/'+$("#company_name").val()+'/data-restrictions/save-retrict-tranx-from-username-table' ;

    $.ajax({
        method:'POST',
        url:url,
        data:$("#frm_restrict_tranx").serialize(),
        success:function(data){
            SnackbarMsg(data);
        }
    });

});
 

$("#restrictionvoucher_ddn_select_user").change(function(){
    var roleid=$(this).val();
    var url="{{url('/')}}"+'/'+$("#company_name").val()+'/role-vouchers/'+roleid;
 
    var userid=$(this).children("option:selected").data("userid"); 
    $("#restrictionvoucher_user_id").val(userid);
    $("#restrictionvoucher_select_vchtype option:not(:first)").remove();

    $.get(url,function(data,status){
        var resultarray=JSON.parse(JSON.stringify(data));

        var tables=resultarray['vchtypes'];  

        for(let table of tables){
            $("#restrictionvoucher_select_vchtype").append("<option value='"+table['Id']+"'>"+table['Name']+"</option>");

        } 

    });  
});
 
function loadRestrictVoucher(userid,vchid){
    
    var url='/'+$("#company_name").val()+'/data-restrictions/get-retrict-vch-days-from-user' ;
    var data={'userid':userid,'vchid':vchid};

        $("#restrictionvoucher_add_after").val("");

        $("#restrictionvoucher_edit_after").val("");

        $("#restrictionvoucher_delete_after").val(""); 
 
    $.ajax({
        url:url,
        method:'POST',
        data:data,
        success:function(data){     
          var resultarray=JSON.parse(JSON.stringify(data))['vch_days'];
          $("#restrictionvoucher_add_after").val(resultarray['add_days']);
          $("#restrictionvoucher_edit_after").val(resultarray['edit_days']);
          $("#restrictionvoucher_delete_after").val(resultarray['delete_days']);
 
        }

    });

}


$("#restrictionvoucher_select_vchtype").change(function(){
    var userid=$("#restrictionvoucher_ddn_select_user :selected").data('userid');;
    var vchid=   $(this).val(); 
    loadRestrictVoucher(userid,vchid);

});

$("#btn_save_user_restrictionvoucher").click(function(){

    
    var url='/'+$("#company_name").val()+'/data-restrictions/save-retrict-vch-days' ;

    $.ajax({
        method:'POST',
        url:url,
        data:$("#frm_restrict_voucher").serialize(),
        success:function(data){
            SnackbarMsg(data);
        }
    });

});

$("#btn_cancel_user_restrictionvoucher").click(function(){
    var userid=$("#restrictionvoucher_ddn_select_user :selected").data('userid');;
    var vchid= $("#restrictionvoucher_select_vchtype").val(); 
    loadRestrictVoucher(userid,vchid);

});

function loadRolesByMonth(month){
    var url='/'+$("#company_name").val()+"/data-restrictions/get-restrict-role-by-month/"+month;
            
                
            $.get(url,function(data,status){
            var resultarray=JSON.parse(JSON.stringify(data));

            var selected=resultarray['selected'];
            var unselected=resultarray['unselected']; 

            $("#monthlocking_role_ddn_selected").empty();

            for(let select of selected){
                $("#monthlocking_role_ddn_selected").append("<option value='"+select['id']+"'>"+select['role_name']+"</option>");

            }
            $("#monthlocking_role_ddn_unselected").empty();

            for(let unselect of unselected){
                $("#monthlocking_role_ddn_unselected").append("<option value='"+unselect['id']+"'>"+unselect['role_name']+"</option>");

            }

            });

}

$("#monthlocking_ddn_select_month").change(function(){
    var month=$(this).val();
    loadRolesByMonth(month);


});

$("#btn_cancel_month_locking").click(function(){
    var month=$("#monthlocking_ddn_select_month").val();
    loadRolesByMonth(month);
});



$("#btn_monthlocking_role_select").click(function(){

var selected=  $("#monthlocking_role_ddn_unselected :selected") ;
selected.each(function(){ 
var id=$(this).val();
var text=$(this).html();
$(this).remove();
$("#monthlocking_role_ddn_selected").append("<option value='"+id+"'>"+text+"</option>"); 
}); 


});

$("#btn_monthlocking_role_unselect").click(function(){

    var selected=  $("#monthlocking_role_ddn_selected :selected") ;

    selected.each(function(){ 
    var id=$(this).val();
    var text=$(this).html();
    $(this).remove();
    $("#monthlocking_role_ddn_unselected").append("<option value='"+id+"'>"+text+"</option>"); 
    }); 
});

$("#btn_save_month_locking").click(function(){

            var url='/'+$("#company_name").val()+'/data-restrictions/save-restrict-role-by-month';

            var roles=[];

            var selectedroles=$("#monthlocking_role_ddn_selected option");

            selectedroles.each(function(){
                roles.push($(this).val());
            });


            $("#monthlocking_selected").val(JSON.stringify(roles)); 

            $.ajax({
            url:url,
            method:'POST',
            data:$("#frm_month_locking").serialize(),
            success:function(data){  
                SnackbarMsg(data); 
                }
            }); 

});


function loadUserRestrictCustomers(userid){

    var url='/'+$("#company_name").val()+'/data-restrictions/get-restrict-customers-from-user/'+userid;

    $.get(url,function(data,status){
        var resultarray=JSON.parse(JSON.stringify(data));

        var selected=resultarray['selected'];
        var unselected=resultarray['unselected'];

        $("#restrictcustomers_ddn_unselected").empty();
        $("#restrictcustomers_ddn_selected").empty();

        for(let select of selected){
            $("#restrictcustomers_ddn_selected").append("<option value='"+select['Id']+"'>"+select['Ptype']+"</option>");

        }

        for(let unselect of unselected){
            $("#restrictcustomers_ddn_unselected").append("<option value='"+unselect['Id']+"'>"+unselect['Ptype']+"</option>");

        }

    });

}


$("#restrictcustomers_ddn_select_user").change(function(){
    var userid=$(this).val();
    loadUserRestrictCustomers(userid);

}); 

        $("#btn_restrictcustomers_select").click(function(){

                    var selected=  $("#restrictcustomers_ddn_unselected :selected") ;
                    selected.each(function(){ 
                    var id=$(this).val();
                    var text=$(this).html();
                    $(this).remove();
                    $("#restrictcustomers_ddn_selected").append("<option value='"+id+"'>"+text+"</option>"); 
                    }); 


        });

        $("#btn_restrictcustomers_unselect").click(function(){

            var selected=  $("#restrictcustomers_ddn_selected :selected") ;

            selected.each(function(){ 
            var id=$(this).val();
            var text=$(this).html();
            $(this).remove();
            $("#restrictcustomers_ddn_unselected").append("<option value='"+id+"'>"+text+"</option>"); 
            }); 
        });


        $("#btn_save_user_restrictcustomers").click(function(){

            
            var url='/'+$("#company_name").val()+'/data-restrictions/save-restrict-customers-by-user';

            var partytypes=[];

            var selectedpartyes=$("#restrictcustomers_ddn_selected option");

            selectedpartyes.each(function(){
                partytypes.push($(this).val());
            }); 
            $("#restrictedcustomers_selected").val(JSON.stringify(partytypes)); 
 
            $.ajax({
            url:url,
            method:'POST',
            data:$("#frm_restrict_customers").serialize(),
            success:function(data){  
                SnackbarMsg(data); 
                }
            }); 


        });

        $("#btn_cancel_user_restrictcustomers").click(function(){
                var userid=  $("#restrictcustomers_ddn_select_user").val();
                  loadUserRestrictCustomers(userid);

        });





$("#division_ddn_select_user").change(function(){

    var userid=$(this).val(); 
    loadUserSelectedUnselectedDivisions(userid);
});


function loadUserSelectedUnselectedDivisions(userid){

    $("#division_ddn_unselected").empty();
    $("#division_ddn_selected").empty();

    $.get("{{url('/')}}/{{Session::get('company_name')}}/get-data-restrictions-user-divisions/"+userid,function(data,status){
 
        var result=JSON.parse(JSON.stringify(data));

        var unselected=result['unselected'];

        var selected=result['selected'];


        for(let unselect of  unselected){
            $("#division_ddn_unselected").append(`<option value='${unselect['Id']}'>${unselect['division']}</option>`);

        }


        for(let select of selected){
            $("#division_ddn_selected").append(`<option value='${select['Id']}'>${select['division']}</option>`);

        }
 
    });
 
}

$("#btn_division_select").click(function(){

    var selected=  $("#division_ddn_unselected :selected") ;
    selected.each(function(){ 
    var id=$(this).val();
    var text=$(this).html();
    $(this).remove();
    $("#division_ddn_selected").append("<option value='"+id+"'>"+text+"</option>"); 
    }); 
 
});

$("#btn_division_unselect").click(function(){

var selected=  $("#division_ddn_selected :selected") ;
selected.each(function(){ 
var id=$(this).val();
var text=$(this).html();
$(this).remove();
$("#division_ddn_unselected").append("<option value='"+id+"'>"+text+"</option>"); 
}); 

});


$("#btn_save_user_divisions").click(function(){

    var selected_divisions=[];

    var url="{{url('/')}}/{{Session::get('company_name')}}/data-restriction/save-user-divisions";

   var selectedoptions= $("#division_ddn_selected option");

   selectedoptions.each(function(){
    selected_divisions.push($(this).val());
   }); 

   $("#division_selected").val(JSON.stringify(selected_divisions));
 
   $.ajax({
            url:url,
            method:'POST',
            data:$("#frm_division").serialize(),
            success:function(data){  
                SnackbarMsg(data); 
                }
            }); 
 
});


$("#btn_cancel_user_divisions").click(function(){
    var userid=$("#division_ddn_select_user").val();
    loadUserSelectedUnselectedDivisions(userid)

});


$("#costcenter_ddn_select_user").change(function(){
    var userid=$(this).val(); 
    loadUserCostCenters(userid);
});

function loadUserCostCenters(userid){

    $("#costcenters_ddn_unselected").empty();
    $("#costcenters_ddn_selected").empty();

    $.get("{{url('/')}}/{{Session::get('company_name')}}/data-restrictions/get-user-cost-centers/"+userid,function(data,status){
      
        var result=JSON.parse(JSON.stringify(data));
        var selected=result['selected'];

        var unselected=result['unselected']; 

        for(let select of selected){

            $("#costcenters_ddn_selected").append(`<option value='${select['Id']}'>${select['Name']}</option>`);

        }


        for(let unselect of unselected){

            $("#costcenters_ddn_unselected").append(`<option value='${unselect['Id']}'>${unselect['Name']}</option>`);

        }

    });

}


$("#btn_costcenter_unselect").click(function(){

    var selected=  $("#costcenters_ddn_selected :selected") ;
    selected.each(function(){ 
    var id=$(this).val();
    var text=$(this).html();
    $(this).remove();
    $("#costcenters_ddn_unselected").append("<option value='"+id+"'>"+text+"</option>"); 
    }); 

});


$("#btn_costcenter_select").click(function(){

    var selected=  $("#costcenters_ddn_unselected :selected") ;
    selected.each(function(){ 
    var id=$(this).val();
    var text=$(this).html();
    $(this).remove();
    $("#costcenters_ddn_selected").append("<option value='"+id+"'>"+text+"</option>"); 
    }); 

});


$("#btn_save_user_costcenters").click(function(){

    var selected=$("#costcenters_ddn_selected option");

    var selectedoptions=[];

    selected.each(function(){
        selectedoptions.push($(this).val());
    });

    var url="{{url('/')}}/{{Session::get('company_name')}}/data-restrictions/save-user-cost-centers";

    $("#costcenter_selected").val(JSON.stringify(selectedoptions));

    $.ajax({
            url:url,
            method:'POST',
            data:$("#frm_costcenter").serialize(),
            success:function(data){  
                SnackbarMsg(data); 
                }
            }); 
 
 
});

 $("#btn_cancel_user_costcenters").click(function(){

    var userid=$("#costcenter_ddn_select_user").val();
    loadUserCostCenters(userid);

 });

 $("#profitcenter_ddn_select_user").change(function(){

    var userid=$(this).val();

    loadUserProfitCenters(userid);

 });


 function loadUserProfitCenters(userid){

    $("#profitcenters_ddn_unselected").empty();

    $("#profitcenters_ddn_selected").empty();


    $.get("{{url('/')}}/{{Session::get('company_name')}}/data-restrictions/get-user-profit-centers/"+userid,function(data,status){
 
        var result=JSON.parse(JSON.stringify(data));

        var selected=result['selected'];

        var unselected=result['unselected'];

        for(let select of selected ){

            $("#profitcenters_ddn_selected").append(`<option value='${select['Id']}'>${select['Name']}</option>`);

        }


        for(let unselect of unselected){
            $("#profitcenters_ddn_unselected").append(`<option value='${unselect['Id']}'>${unselect['Name']}</option>`);

        }
 
    });



 }


 $("#btn_profitcenter_select").click(function(){

var selected=  $("#profitcenters_ddn_unselected :selected") ;
selected.each(function(){ 
var id=$(this).val();
var text=$(this).html();
$(this).remove();
$("#profitcenters_ddn_selected").append("<option value='"+id+"'>"+text+"</option>"); 
}); 

});

$("#btn_profitcenter_unselect").click(function(){

var selected=  $("#profitcenters_ddn_selected :selected") ;
selected.each(function(){ 
var id=$(this).val();
var text=$(this).html();
$(this).remove();
$("#profitcenters_ddn_unselected").append("<option value='"+id+"'>"+text+"</option>"); 
}); 

});




$("#btn_save_user_profitcenters").click(function(){

var selected=$("#profitcenters_ddn_selected option");

var selectedoptions=[];

selected.each(function(){
    selectedoptions.push($(this).val());
});

var url="{{url('/')}}/{{Session::get('company_name')}}/data-restrictions/save-user-profit-centers";

$("#profitcenter_selected").val(JSON.stringify(selectedoptions));

$.ajax({
        url:url,
        method:'POST',
        data:$("#frm_profitcenter").serialize(),
        success:function(data){  
            SnackbarMsg(data); 
            }
        }); 


});

$("#btn_cancel_user_profitcenters").click(function(){

    var userid= $("#profitcenter_ddn_select_user").val();
    loadUserProfitCenters(userid);

});
 
</script>
@endsection
