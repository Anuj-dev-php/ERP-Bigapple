<?php
// url 66 thak

use App\Http\Controllers\Accounts\DocController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Company\CompanyController;
use App\Http\Controllers\Company\CompanyDashboardController;
use App\Http\Controllers\Configuration\RoleController;
use App\Http\Controllers\Configuration\DataRestrictionController;
use App\Http\Controllers\Configuration\VoucherSchedulerController;
use App\Http\Controllers\Configuration\FieldConditionController; 
use App\Http\Controllers\Configuration\FaIntegrationController;
use App\Http\Controllers\Configuration\CreateFormatController;
use App\Http\Controllers\Configuration\CreateVoucherFormatController;
use App\Http\Controllers\Configuration\DefineVoucherNumberController;
use App\Http\Controllers\Configuration\DefineWorkflowController; 
use App\Http\Controllers\Configuration\DesignSmsFormatController;
use App\Http\Controllers\Configuration\CreateTransactionController;
use App\Http\Controllers\Configuration\CopyTransactionController;
use App\Http\Controllers\Configuration\DefineDocumentNumberController;
use App\Http\Controllers\Configuration\TransactionActionRolesController;
use App\Http\Controllers\Configuration\EmailConfigurationController;
use App\Http\Controllers\Configuration\CreateModuleController;
use App\Http\Controllers\Configuration\UserController;
use App\Http\Controllers\Configuration\DataSelectionController;
use App\Http\Controllers\Configuration\ReportPrintController;
use App\Http\Controllers\Reports\FinalAccountsController;
use App\Http\Controllers\Reports\KoolReportController;
use App\Http\Controllers\Reports\ZipController;
use App\Http\Controllers\Reports\CashBankBookController;
use App\Http\Controllers\Reports\RegisterController;
use App\Http\Controllers\Reports\StockRegisterController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/dashboard', function () {
    return view('dashboard');
});
  //login 
Route::get('/loginpage',function(){
    return view('loginpage');
});
Route::get('/send-auto-invoices',[TransactionActionRolesController::class,'sendGeneratedInvoicesAutoMode']);
Route::post('/get-logout',[UserController::class,'getLogout'])->name('get-logout');
Route::get('/generate-gst-invoice/{dbname}/{tablename}/{dataid}',[TransactionActionRolesController::class,'generateGstInvoice']); 
Route::get('/delete-generated-files-after-24hour',[FinalAccountsController::class,'deleteCreatedFilesAfter24Hours']);
Route::get('/process-queue-jobs',[FinalAccountsController::class,'processQueueJobs']);
Route::middleware(['auth'])->group(function () {
    Route::resource('/companies', CompanyController::class);
       
        // Create User
        Route::post('/save-user', [RoleController::class, 'createUpdateUser']);
        Route::get('/get-user-details/{user}',[RoleController::class,'getUserDetails']);
        Route::get('/get-all-users',[RoleController::class,'getAllUsers']);

    // Route::resource('/', CompanyDashboardController::class);
    Route::prefix('{company_name}')->middleware(['multi.database'])->group(function () {
        Route::resource('/', CompanyDashboardController::class);

        //subledger
        Route::get('/subledger/{accountid?}', [DocController::class, 'ViewSubLedger'])->name('company.subledger_new');;
        Route::get('/general-ledger', [DocController::class, 'ViewGeneralLedger']);
        Route::get('/trial-balance', [DocController::class, 'ViewTrialBalance']);
        Route::get('/get-accounts-tree', [DocController::class, 'getAccountsTree'])->name('company.getAccountsTree');
        Route::post('/general-ledger-list', [DocController::class, 'showGeneralLedger'])->name('company.showGeneralLedger');
        Route::post('generalledgerlist_xls',[DocController::class, 'generalledgerlist_xls'])->name('generalledgerlist_xls');
        Route::post('/show-trial-balance', [DocController::class, 'showTrialBalance'])->name('company.showTrialBalance');
        Route::post('/general-ledger', [DocController::class, 'ViewGeneralLedger'])->name('company.submitgeneralledger');;
        Route::post('/getdata/subledger', [DocController::class, 'GetSubLedger'])->name('company.subledger');
        Route::get('/test-collection-paging',[DocController::class,'testCollectionPaging']);
        Route::post('/subledger', [DocController::class, 'ViewSubLedger']) ;
        Route::post('/dologout',[UserController::class,'dologout'])->name('dologout');
        //Configuration Menu
        Route::resource('/roles-menu', RoleController::class);
        Route::get('/get-role', [RoleController::class, 'getRole']);
        Route::get('/fetch-roles', [RoleController::class, 'fetchRoles']);
        Route::get('/get-roles-list', [RoleController::class, 'getRolesList']);
        Route::post('/role-maps', [RoleController::class, 'roleMaps']);
        Route::get('/role-vouchers/{roleid}',[RoleController::class,'roleVouchers']);

        // Field Level
        Route::get('/role-trans/{roleid}', [RoleController::class, 'roleTran'])->name('get_role_trans');
        Route::get('/trans-fields/{roleid}/{tablename}', [RoleController::class, 'transactionFields']);
        Route::post('/update-trans-fields', [RoleController::class, 'updateTransactionFields']);
        // Inbox tabs
        Route::post('/add-inbox-tabname', [RoleController::class, 'addInboxTabName']);
        Route::post('/update-inbox-tabname', [RoleController::class, 'updateInboxTabName']);

        // Inbox Tabs Hiding
        Route::post('/save-role-inboxtabs-hiding', [RoleController::class, 'submitRoleInboxTabsHiding']);
        Route::get('/get-role-inboxtabs-hiding/{roleid}', [RoleController::class, 'getRoleInboxTabsHiding']);

        // Masters
        Route::post('/add-master-name', [RoleController::class, 'addMasterName']);
        Route::post('/update-master-name', [RoleController::class, 'updateMasterName']);

        // Master Restrictions
        Route::post('/save-role-master-restrictions', [RoleController::class, 'submitRoleMasterRestrictions']);
        Route::get('/get-role-master-restrictions/{roleid}', [RoleController::class, 'getRoleMasterRestrictions']);

        // Menu Level
        Route::post('/save-menu-level', [RoleController::class, 'submitMenuLevel']);
        Route::get('/get-role-menu-level/{roleid}', [RoleController::class, 'getRoleMenuLevel']);

        // Account Level
        Route::post('/save-role-account-level', [RoleController::class, 'saveAccountLevel']);
        Route::get('/get-role-account-level/{roleid}', [RoleController::class, 'getAccountLevel']);


        // Data Restrictions starts

        Route::get('/data-restrictions', [DataRestrictionController::class, 'index']);

        // Locations
        Route::get('/data-restrictions/user-locations/{userid}', [DataRestrictionController::class, 'getUserLocations']);
        Route::post('/data-restrictions/user-locations', [DataRestrictionController::class, 'saveUserLocations']);

        // Products
        Route::get('/data-restrictions/user-products/{userid}',[DataRestrictionController::class , 'getUserProducts']);
        Route::post('/data-restrictions/save-user-products',[DataRestrictionController::class , 'saveUserProducts']);

        // Customers and suppliers
        Route::get('/data-restrictions/user-customers/{userid}',[DataRestrictionController::class ,'getUserCustomers']);
        Route::post('/data-restrictions/save-user-customers',[DataRestrictionController::class ,'saveUserCustomers']);

        // Salesman
        Route::get('/data-restrictions/user-salesman/{userid}',[DataRestrictionController::class ,'getUserSalesman']);
        Route::post('/data-restrictions/save-user-salesman',[DataRestrictionController::class ,'saveUserSalesman']);

        // Employees
        Route::get('/data-restrictions/user-employees/{userid}',[DataRestrictionController::class ,'getUserEmployees']);
        Route::post('/data-restrictions/save-user-employees',[DataRestrictionController::class ,'saveUserEmployees']);

        // Edit Status
        Route::post('/data-restrictions/get-status-from-username-table',[DataRestrictionController::class ,'getStatusFromUserAndTablename']);
        Route::post('/data-restrictions/save-user-edit-status',[DataRestrictionController::class ,'saveStatusFromUserAndTablename']);

        // Restrict Transaction
        Route::post('/data-restrictions/get-retrict-tranx-from-username-table',[DataRestrictionController::class ,'getRestrictTranxDaysFromUserAndTablename']);
        Route::post('/data-restrictions/save-retrict-tranx-from-username-table',[DataRestrictionController::class ,'saveRestrictTranxDaysFromUserAndTablename']);
      
        // Restrcit Voucher
    
        Route::post('/data-restrictions/get-retrict-vch-days-from-user',[DataRestrictionController::class ,'getRestrictVoucherDays']);
        Route::post('/data-restrictions/save-retrict-vch-days',[DataRestrictionController::class ,'saveRestrictVoucherDays']);

        // Month Locking
        Route::get('/data-restrictions/get-restrict-role-by-month/{month}',[DataRestrictionController::class,'getRestrictRolesByMonth']);
        Route::post('/data-restrictions/save-restrict-role-by-month',[DataRestrictionController::class,'saveRestrictRolesByMonth']);


        //REstrict customers
        Route::get('/data-restrictions/get-restrict-customers-from-user/{user}',[DataRestrictionController::class,'getRestrictCustomersFromUser']);
        Route::post('/data-restrictions/save-restrict-customers-by-user',[DataRestrictionController::class,'saveRestrictCustomersForUser']);
        //  Data Restriction ends


        //Role-Reports
        Route::get('/get-role-reports-from-user/{role}',[RoleController::class,'getReportsFromUser']);
        Route::post('/save-role-reports',[RoleController::class,'saveRoleReports']);

        // Role-Modules
        Route::get('/get-role-modules/{role}',[RoleController::class,'getRoleModules']);
        Route::post('/save-role-modules',[RoleController::class,'saveRoleModules']);

        // Voucher Schedular
        Route::get('/voucher-scheduler',[VoucherSchedulerController::class,'index']);
        Route::post('/voucher-scheduler-add',[VoucherSchedulerController::class,'add']);
        Route::get('/voucher-scheduler-delete/{id}',[VoucherSchedulerController::class,'delete']);
        Route::post('/search-voucher-no',[VoucherSchedulerController::class,'searchVoucherNumbers']);
        Route::get('/voucher-scheduler-detail/{id}',[VoucherSchedulerController::class,'getvoucherSchedulerDetail']);
        Route::post('/delete-voucher-scheduler',[VoucherSchedulerController::class,'deleteVoucherSchedulers']);
        // Route::get('/voucher-scheduler-edit/{id}',[VoucherSchedulerController::class,'edit']);
 
        

        // Field Condition Controller
        Route::get('/field-conditions',[FieldConditionController::class,'index']);
        Route::get('/field-conditions-edit',[FieldConditionController::class,'edit']);
        Route::post('/search-transactions',[FieldConditionController::class,'searchTransactions']);
        Route::post('/get-transaction-fields',[FieldConditionController::class,'getTransactionFields']);
        Route::post('/get-transaction-field-values',[FieldConditionController::class,'getFieldValues']);
        Route::post('/save-tran-field-values',[FieldConditionController::class,'saveTransactionFieldValues']);
        Route::post('/transaction-all-fields',[FieldConditionController::class,'getAllTransactionFields']);
        Route::post('/get-field-condition-new-row',[FieldConditionController::class,'getNewTransactionFieldRow']);
        Route::get('/check-field-condition-view',[FieldConditionController::class,'checkFieldConditionView']);


        // Email Configuration Controller
        Route::get('/email-configuration',[EmailConfigurationController::class,'index']);
        Route::get('/add-edit-email-configuration/{id?}',[EmailConfigurationController::class,'addedit']);
        Route::post('/get-transaction-print-templates',[EmailConfigurationController::class,'getTransactionPrintTemplates']);
        Route::post('/get-emailconfiguration-another-row',[EmailConfigurationController::class,'getEmailConfigurationAnotherRow']);
        Route::post('/get-transaction-print-template-rows',[EmailConfigurationController::class,'getEmailConfigurationRows']);
        Route::post('/submitemailconfiguration',[EmailConfigurationController::class,'submitEmailConfiguration']);
        Route::post('/email-configuration-delete',[EmailConfigurationController::class,'deleteEmailConfiguration']);



      
        // Design SMS Format Controller
        Route::get('/design-whatsapp-format',[DesignSmsFormatController::class,'index']);
        Route::post('/design-sms-format-add',[DesignSmsFormatController::class,'add']);
        Route::get('/design-sms-format-field/{name}',[DesignSmsFormatController::class,'field']);
        Route::get('/design-sms-format-delete/{id}',[DesignSmsFormatController::class,'delete']);
        Route::post('/delete-sms-formats',[DesignSmsFormatController::class,'deleteSmsFormats']); 
        Route::get('/email-whatsapp-scheduler',[DesignSmsFormatController::class,'emailSchedular']);
        Route::post("/email-whatsapp-scheduler",[DesignSmsFormatController::class,'emailSchedular']); 
        Route::post("/get-table-ids" ,[DesignSmsFormatController::class,'getTableIds'] ); 
        Route::post("/delete-email-schedulars",[DesignSmsFormatController::class,'deleteEmailSchedulars']);

        // Fa Integration
        Route::get('/fa-integration',[FaIntegrationController::class,'index']);
        Route::get('/fa-integration/add',[FaIntegrationController::class,'addFaIntegration']);
        Route::get('/get-sub-voucher-types/{vouchertype}',[FaIntegrationController::class,'getSubVoucherTypes']);
        Route::post('/search-accounts',[FaIntegrationController::class,'searchAccounts']);
        Route::post('/fa-integration/submitadd',[FaIntegrationController::class,'submitAddFaIntegration']);
        Route::get('/fa-integration/edit/{tranaccountid}',[FaIntegrationController::class,'editFaIntegration']);
        Route::post('/fa-integration/delete',[FaIntegrationController::class,'deleteFaIntegration']);
        Route::post('/search-sub-accounts',[FaIntegrationController::class,'searchSubAccounts']);




        // Create Format
        Route::get('/create-format',[CreateFormatController::class,'index']);
        Route::get('/add-edit-create-format/{tempid?}',[CreateFormatController::class,'addUpdateCreateFormat']);
        Route::post('/submitcreateformat',[CreateFormatController::class,'submitCreateFormat']);
        Route::post('/delete-create-format',[CreateFormatController::class,'deleteCreateFormat']);



        // Create Voucher Format
        Route::get('/create-voucher-format',[CreateVoucherFormatController::class,'index']);
        Route::get('/add-edit-create-voucher-format/{tempid?}',[CreateVoucherFormatController::class,'addEditVoucherFormat']);
        Route::post('/submit-create-voucher-format',[CreateVoucherFormatController::class,'submitCreateVoucherFormat']);
        Route::post('/delete-create-voucher-format',[CreateVoucherFormatController::class,'deleteCreateVoucherFormat']);

        // Define Voucher Numbers
        Route::get('/define-voucher-numbers',[DefineVoucherNumberController::class,'index']);
        Route::post('/addsubvouchertype',[DefineVoucherNumberController::class,'addSubVoucherType']);
        Route::get('/get-sub-voucher-datatable-types',[DefineVoucherNumberController::class,'getSubVoucherTypes']);
        Route::post('/update-sub-voucher-type',[DefineVoucherNumberController::class,'updateSubVoucherType']);
        Route::get('/get-voucher-numbers/{vouchertypeid}',[DefineVoucherNumberController::class,'getVoucherNumbers']);
        Route::post('/add-voucher-number-to-type',[DefineVoucherNumberController::class,'addVoucherNumberToVoucherType']);
        Route::post('/update-voucher-number',[DefineVoucherNumberController::class,'updateVoucherNumber']);


        // Define Work flow 
        Route::get('/define-work-flow',[DefineWorkFlowController::class,'index']);
        Route::get('/add-edit-define-workflow/{workflowheadid?}',[DefineWorkFlowController::class,'addEditDefineDefineWorkflow']);
        Route::post('/get-new-workflow-save-row/{rownum}',[DefineWorkFlowController::class,'getNewWorkflowSaveRow']);
        Route::post('/submitdefineworkflow',[DefineWorkFlowController::class,'submitDefineWorkflow']);
        Route::get('/get-new-workflow-inbox-row/{rownum}',[DefineWorkFlowController::class,'getNewWorkflowInboxRow']);
        Route::post('/delete-define-workflows',[DefineWorkFlowController::class,'deleteDefineWorkflows']);

        
        
        // Create Transaction
        Route::get('/create-transactions', [CreateTransactionController::class,'index']);
        Route::get('/add-edit-createtransaction',[CreateTransactionController::class,'create']);
        Route::post('/submitcreatetransaction',[CreateTransactionController::class,'store']);
        Route::get('/add-edit-createtransaction/{tranid}',[CreateTransactionController::class,'edit']);
        Route::post('/delete-create-transactions',[CreateTransactionController::class,'destroy']);
        Route::get('/view-transaction-fields/{tranid}',[CreateTransactionController::class,'viewTransactionFields']);
        Route::post('/create-transactions', [CreateTransactionController::class,'index']);
        Route::get('/add-edit-transaction-field/{tranid}/{fieldid?}',[CreateTransactionController::class,'addEditTransactionField']);
        Route::post('/submittranfield',[CreateTransactionController::class,'submitTranField']);
        Route::get('/get-table-with-fields/{tablename}',[CreateTransactionController::class,'getTableWithFields']);
        Route::post('/get-autopopulate-mapping-fields',[CreateTransactionController::class,'getAutopopulateMappingFields']);
        Route::post('/delete-transaction-fields',[CreateTransactionController::class,'deleteTransactionFields']);
        Route::post('/view-transaction-fields/{tranid}',[CreateTransactionController::class,'viewTransactionFields']);
        // Copy Transaction
        Route::get('/copy-transaction',[CopyTransactionController::class,'index']);
        Route::get('/get-company-transactions',[CopyTransactionController::class,'getCompanyTransactions']);
        Route::post('/submitcopytransaction',[CopyTransactionController::class,'submitCopyTransactions']);



        // Transaction Action Roles
        Route::get('/add-transaction-insert-role-fields/{tablename}/{tranid}',[TransactionActionRolesController::class,'addTransactionInsertRoleFields'])->name('transactions_insert');
        Route::post('/get-function2-fieldvalues',[TransactionActionRolesController::class,'getFunction2FieldValues']);
        Route::post('/get-function4-tablerows',[TransactionActionRolesController::class,'getFunction4TableRows']);
        Route::post('/get-function5-codes',[TransactionActionRolesController::class,'getFunction5Codes']);
        Route::post('/get-function19-fieldvalues',[TransactionActionRolesController::class,'getFunction19FieldValues']);
        Route::post('/get-function18-users',[TransactionActionRolesController::class,'getFunction18Users']);
        Route::post('/get-function22-accbal',[TransactionActionRolesController::class,'getFunction22AccBalance']);
        Route::get('/get-customer-balance/{custid}',[TransactionActionRolesController::class,'getCustomerAccBalance']);
        Route::get('/get-function20-user',[TransactionActionRolesController::class,'getFunction20CurrentUser']);
        Route::post('/get-function3-fieldvalues-checkoptions',[TransactionActionRolesController::class,'getFunction3FieldValuesCheckOptions']);
        Route::get('/get-function4-tablerows-checkoptions/{tablename}',[TransactionActionRolesController::class,'getFunction4CheckOptions']);
        Route::get('/get-function5-codes-checkoptions/{tablename}',[TransactionActionRolesController::class,'getFunction5CodesCheckOptions']);
        Route::get('/get-function2-fieldvalues-checkoptions/{tablename}',[TransactionActionRolesController::class,'getFunction2FieldValuesCheckOptions']);
        Route::get('/get-function18-users-checkoptions',[TransactionActionRolesController::class,'getFunction18UsersCheckOptions']);
        Route::get('/get-Function14-Currency',[TransactionActionRolesController::class,'getFunction14Currency']);
        Route::post('/get-Function14-All-currencies',[TransactionActionRolesController::class,'getFunction14AllCurrencies']);
        Route::post('/get-Function15-Exchange-Rate',[TransactionActionRolesController::class,'getFunction15ExchangeRate']);
        Route::post('/get-function3-fieldvalues',[TransactionActionRolesController::class,'getFunction3FieldValues']);
        Route::post('/get-function24-fieldvalues-checkoptions',[TransactionActionRolesController::class,'getFunction24FieldValuesCheckOptions']);
        Route::post('/get-function16-uoms',[TransactionActionRolesController::class,'getFunction16Uoms']);
        Route::post('/get-function24-fieldvalues',[TransactionActionRolesController::class,'getFunction24FieldValues']);
        Route::get('/get-Function21-Without-Fieldvalues/{tablename}',[TransactionActionRolesController::class,'getFunction21WithoutFieldValues']);
        Route::get('/get-Function21-With-Fieldvalues/{tablename}',[TransactionActionRolesController::class,'getFunction21WithFieldValues']);
        Route::post('/getFunction21SingleFieldValue-For-Det',[TransactionActionRolesController::class,'getFunction21SinfleFieldValueForDet']);
        Route::post('/getFunction21-SingleFieldValue-For-WithoutDet',[TransactionActionRolesController::class,'getFunction21SingleFieldValueForWithoutDet']);
        Route::get('/get-Function45-Without-Fieldvalues/{tablename}',[TransactionActionRolesController::class,'getFunction45WithoutFieldValues']);
        Route::get('/get-Function45-With-Fieldvalues/{tablename}',[TransactionActionRolesController::class,'getFunction45WithFieldValues']);
        Route::post('/getFunction45-SingleFieldValue-For-WithoutDet',[TransactionActionRolesController::class,'getFunction45SingleFieldValueForWithoutDet']);
        Route::post('/getFunction45SingleFieldValue-For-Det',[TransactionActionRolesController::class,'getFunction45SinfleFieldValueForDet']);
        Route::post('/add-new-detail-field-row',[TransactionActionRolesController::class,'addNewDetailFieldRow']);
        Route::get('/get-Function11-Field-Formulas/{tablename}',[TransactionActionRolesController::class,'getFunction11FieldFormulas']);
        Route::get('/get-Function11-Det-Dependent-Formula-Fields/{tablename}',[TransactionActionRolesController::class,'getFunction11FieldDetDependentFormulas']);
        Route::post('/calculate-Function11-Pricing-Field-Value',[TransactionActionRolesController::class,'calculateFunction11PricingFieldValue']);
        Route::get('/get-Function11-Field-Formulas-Only-Header/{tablename}',[TransactionActionRolesController::class,'getFunction11FieldFormulasOnlyHeader']);
        Route::post('/get-Function24-Det-Fields-to-load',[TransactionActionRolesController::class,'getFunction24DetFieldsToLoadFromFunction4']);
        Route::get('/get-Function30-Fields-From-Table/{tablename}',[TransactionActionRolesController::class,'getFunction30FieldsFromTable']);
        Route::post('/calculate-Function30-Field-Value',[TransactionActionRolesController::class,'calculateFunction30FieldValue']);
        Route::post('/calculate-All-Function11-Pricing-Field-Value',[TransactionActionRolesController::class,'calculateAllFunction11PricingFieldValue']);
        Route::post('/get-Function17-Batch-Numbers',[TransactionActionRolesController::class,'getFunction17BatchNumbers']);
        
        Route::post("/get-transaction-sub-detail-rows",[TransactionActionRolesController::class,'getTransactionSubDetailRows']);


        // Define Document Number 
        Route::get('/define-document-number',[DefineDocumentNumberController::class,'index']);
        Route::get('/get-Transaction-Table-Codes/{tablename}',[DefineDocumentNumberController::class,'getTransactionTableCodes']);
        Route::post('/update-document-number',[DefineDocumentNumberController::class,'submitDocumentNumber']);

        // Create Module
        Route::get('/create-module',[CreateModuleController::class,'index']);
        Route::get('/get-All-Modules',[CreateModuleController::class,'getAllModules']);
        Route::post('/update-module-name',[CreateModuleController::class,'updateModuleName']);
        Route::get('/get-Module-Transactions/{transactionid}',[CreateModuleController::class,'getModuleTransactions']);
        Route::post('/submit-module-transactions',[CreateModuleController::class,'submitModuleTransactions']);
        Route::post('/create-module-name',[CreateModuleController::class,'createModuleName']);
        Route::get('/get-Module-Transactions-For-Sequence/{moduleid}',[CreateModuleController::class,'getModuleTransactionsForSequence']);
        Route::get("/get-module-transaction-table-fields-selected-unselected/{tablename}",[CreateModuleController::class,'getModuleTransactionFieldsSelectedUnselected']);
        // Route::post('/update-module-txn-sequence',[CreateModuleController::class,'submitModuleTxnSequence']);
        Route::get('/get-role-transactions-in-module/{roleid}',[CreateModuleController::class,'getRoleTransactionAtModules']);
        Route::post('/submit-module-role-transactions',[CreateModuleController::class,'submitModuleRoleTransactions']);
        Route::get('/get-module-role-report-shortcuts/{roleid}',[CreateModuleController::class,'getModuleRoleReportShortcuts']);
        Route::post('/submit-module-role-reportshortcuts',[CreateModuleController::class,'submitModuleRoleReportShortcuts']);
        Route::get('/get-module-acshortcut-vouchertypes/{roleid}',[CreateModuleController::class,'getModuleRoleVoucherTypes']);
        Route::post('/submit-module-role-acshortcuts',[CreateModuleController::class,'submitModuleRoleAcShortcuts']);
        Route::get('/get-module-ac-report-shortcuts/{roleid}',[CreateModuleController::class,'getModuleAcReportShortcuts']);
        Route::post('/submit-module-role-acreportshortcuts',[CreateModuleController::class,'submitModuleRoleAcReportShortcuts']);
        Route::post('/update-module-txn-sequence',[CreateModuleController::class,'updateModuleTxnSequences']);
        Route::post('/add-module-report-name',[CreateModuleController::class,'addModuleReportByName']);
        Route::get('/get-module-report-modules',[CreateModuleController::class,'getModuleReportModules']);
        Route::post('/update-report-modules-sequences',[CreateModuleController::class,'updateReportModuleSequences']);
        Route::post('/update-module-report-module-name',[CreateModuleController::class,'updateModuleReportModuleName']);
        Route::get('/get-module-report-module-reports/{rmid}',[CreateModuleController::class,'getReportModuleReports']);
        Route::post('/submit-module-addreport-reports',[CreateModuleController::class,'submitAddReportReports']);
        Route::get('/get-module-reportmodule-sequence-rpts/{rmid}',[CreateModuleController::class,'getReportModuleSequenceRpts']);
        Route::post('/update-module-reportmodule-rpts-sequence',[CreateModuleController::class,'updateReportModuleRptsSequences']);
        Route::post('/submit-module-company-news',[CreateModuleController::class,'submitCompanyNews']);
        Route::get('/get-module-company-news',[CreateModuleController::class,'getModuleCompanyNews']);
        Route::post('/update-module-company-news',[CreateModuleController::class,'updateModuleCompanyNews']);
        Route::post('/submit-module-email-configuration',[CreateModuleController::class,'submitModuleEmailConfiguration']);
        Route::post('/update-module-email-conf',[CreateModuleController::class,'updateModuleEmailConfiguration']);
        Route::get('/get-Module-All-Email-Confs',[CreateModuleController::class,'getAllEmailConfs']);
        Route::get('/get-Module-Menus-with-Sequence',[CreateModuleController::class,'getModuleMenusWithSequence']);
        Route::post('/update-module-menu-sequence',[CreateModuleController::class,'updateModuleMenuSequence']);
        Route::post("/submit-module-transaction-fields",[CreateModuleController::class,'submitModuleTransactionFields']);
        Route::get('/get-Function4-Fieldconditions/{tablename}', [TransactionActionRolesController::class,'getFunction4FieldNamesWithFieldConditions']);
        Route::post('/get-Function4-Fieldcondition-Restricted-Field-Value',[TransactionActionRolesController::class,'getFunction4FieldConditionRestrictedValue']);
        Route::post('/submit-Add-Transaction-Insert-Role-Fields',[TransactionActionRolesController::class,'submitAddTransactionInsertRoleFields']);


   
        Route::post("/get-transaction-table-fields-with-sequence",[CreateModuleController::class,'getTransactionTableFieldsWithSequence']);
        
        Route::post('/validate-Submit-Transaction-TableData',[TransactionActionRolesController::class,'validateSubmitTransactionTableData']);

        Route::post('/get-AddEditTransaction-Get-Call-Data',[TransactionActionRolesController::class,'getTransactionCallDataForSelection']);

        Route::post("/check-Transaction-Call-Data-For-Multiple-Main-Id",[TransactionActionRolesController::class,'checkTransactionCallDataForMultipleMainId']);

        Route::post("/get-Transaction-Call-Data-For-Selected",[TransactionActionRolesController::class,'getTransactionCallDataForSelectedDataFromTblLinkData']);
    
        Route::get("/checkFieldDisplayValue",[TransactionActionRolesController::class,'checkFieldDisplayValue']);


        // Data Selection 

        Route::get("/data-selection",[DataSelectionController::class,'index']);
        Route::post("/get-Function4-Fields-From-Table",[DataSelectionController::class,'getFunction4FieldsFromTable']);
        Route::post("/submit-data-selection",[DataSelectionController::class,'submitDataSelection']);
        Route::get("/get-all-data-selection",[DataSelectionController::class,'getAllDataSelection']);
        Route::get("/get-Edit-Data-Selection/{data_selection_id}",[DataSelectionController::class,'editDataSelectionById']);
        Route::post("/get-Function4-keyfield-All-Values",[DataSelectionController::class,'getFunction4KeyFieldAllValues']);
        Route::get("/delete-data-selection-user-key-value/{id}",[DataSelectionController::class,'deleteUserKeyValue']);

        // Edit Transaction Table Data
        
        Route::get("/edit-transaction-table-data/{tablename}/{tableid}/{dataid?}",[TransactionActionRolesController::class,'editTransactionTableData']);
        
        Route::get("/edit-transaction-table-single-data/{tablename}/{tableid}/{dataid}",[TransactionActionRolesController::class,'editTransactionTableSingleData'])->name('company.edit-transaction-table-single-data');;
   
        Route::post("/get-transaction-table-data-by-id",[TransactionActionRolesController::class,'getTransactionTableDataById']);

        Route::post("/update-txn-field-sequence",[CreateModuleController::class,'updateTxnFieldSequence']);
        
        Route::post("/edit-transaction-table-data/{tablename}/{tableid}",[TransactionActionRolesController::class,'editTransactionTableData']);

        Route::get("/get-edit-tran-data-search-fields",[TransactionActionRolesController::class,'getEditTranDataSearchFields']);

        Route::get("/reset-edit-tran-data-search",[TransactionActionRolesController::class,'resetEditTranDataSearch']);

        Route::post("/get-prev-next-transaction-table-record",[TransactionActionRolesController::class,'getPrevNextTransactionTableRecord']);
        
        Route::get("/get-edit-tran-data-history/{docno}",[TransactionActionRolesController::class,'getEditTranDataHistoryUsingDocno']);
        
        Route::post("/get-transaction-sub-detail-form-in-json",[TransactionActionRolesController::class,'getTransactionSubDetailFormInJson']);

        Route::post("/get-tran-customer-account-receivable-details",[TransactionActionRolesController::class,'getTransactionCustomerAccountReceivableDetails']);
   
        Route::post("/get-transaction-receivablepayable-amount-adjustments",[TransactionActionRolesController::class,'getTransactionReceivablePayableAmountAdjustments']);
   
        Route::post("/check-transaction-credit-limit-exceeded",[TransactionActionRolesController::class,'checkTransactionCreditLimitExceeded']);
   
        Route::post("/check-transaction-credit-days-exceeded",[TransactionActionRolesController::class,'checkTransactionCreditDaysLimitExceeded']);
        
        Route::post("/check-products-stock-availability",[TransactionActionRolesController::class,'checkTransactionProductsStockAvailability']);
   
        Route::post("/delete-tran-table-data-by-ids",[TransactionActionRolesController::class,'deleteTransactionTableDataByIds']);
   

        Route::post("/check-detail-row-before-delete-is-referenced",[TransactionActionRolesController::class,'checkDetailRowIsReferencedBeforeDelete']);
   
        Route::post("/check-tran-details-are-referenced-check-delete",[TransactionActionRolesController::class,'checkTranDetailsAreReferencedCheckDelete']);
 
        Route::get("/get-edit-tran-data-receivables/{docno}",[TransactionActionRolesController::class,'getEditTranDataReceivables']);

        Route::post("/get-copy-data-ids-or-docnumbers-from-transactiontable",[TransactionActionRolesController::class,'getCopyDataIdsAndDocNumbersFromTransactionTable']);

        Route::post("/get-transaction-table-data-id-by-docno",[TransactionActionRolesController::class,'getCopyDataSpecificIdFromDocNumberGiven']);
   
        Route::post("/get-transaction-table-reject-reason-by-data-id",[TransactionActionRolesController::class,'getTransactionTableDataRejectReasonByDataId']);

        Route::post("/submit-transaction-table-reject-reason-using-data-id",[TransactionActionRolesController::class,'submitTransactionTableRejectReasonUsingDataId']);

        Route::get("/get-data-restrictions-user-divisions/{userid}", [DataRestrictionController::class, 'getUserDivisions']);
 
        Route::post("/data-restriction/save-user-divisions", [DataRestrictionController::class,'saveUserDivisions']);

        Route::get("/data-restrictions/get-user-cost-centers/{userid}", [DataRestrictionController::class,'getUserCostCenters']);

        Route::post("/data-restrictions/save-user-cost-centers", [DataRestrictionController::class,'saveUserCostCenters']);

        Route::get("/data-restrictions/get-user-profit-centers/{userid}", [DataRestrictionController::class,'getUserProfitCenters']);

        Route::post("/data-restrictions/save-user-profit-centers", [DataRestrictionController::class,'saveUserProfitCenters']);

        Route::get("/get-dashboard-charts-data",[CompanyDashboardController::class,'getDashboardChartsData']);
        
        Route::get("/get-tran-account-receivable-details/{accid}",[TransactionActionRolesController::class,'getTransactionAccountReceivableDetails']);
   
        Route::post("/get-account-receivablepayable-amount-adjustments",[TransactionActionRolesController::class,'getAccountReceivablePayableAmountAdjustments']);
   
   
       Route::get("/test-report-print",[ReportPrintController::class,'testreportprint']);
   
       Route::get("/edit-transaction-table-data-excel-download/{tablename}",[TransactionActionRolesController::class,'editTransactionTableDataExcelDownload']);
   
        Route::get("/get-expense-charts-data",[CompanyDashboardController::class,'getExpenseChartsData']);

        Route::get("/get-individual-sales-charts-data",[CompanyDashboardController::class,'getIndividualSalesChartsData']);

        
        Route::get("/get-divisional-sales-charts-data",[CompanyDashboardController::class,'getDivisionalSalesChartsData']);

        Route::get("/get-sales-charts-data",[CompanyDashboardController::class,'getSalesChartsData']);

        Route::get("get-no-of-pending-quotes",[CompanyDashboardController::class,'getNoOfPendingQuotes']);
        
        Route::get("/get-no-of-pending-orders",[CompanyDashboardController::class,'getNoOfPendingOrders']);

        Route::get("/get-no-of-pending-invoices",[CompanyDashboardController::class,'getNoOfPendingInvoices']);

        Route::get("/get-no-of-ageing-receivables-and-amount",[CompanyDashboardController::class,'getNoOfAgeingReceivablesAndAmount']);

        Route::get("/get-no-of-pending-rma-cases",[CompanyDashboardController::class,'getNoOfPendingRmaCases']);

        Route::get("/get-dashboard-pending-data/{purpose}",[CompanyDashboardController::class,'getDashboardPendingData']);

        Route::get('/test-upload-pdf-from-url',[TransactionActionRolesController::class,'testUploadPdfFromUrl']);

        Route::get('/test-send-event-mail',[TransactionActionRolesController::class,'testSendEventMail']);

        Route::post("/edit-tran-data-submit-print-report",[TransactionActionRolesController::class,'submitPrintReport']);

        Route::get("/get-searched-table-transactions/{searchtxt}",[CompanyDashboardController::class,'getSearchedTableTransactions']);

        Route::get("/generate-gst-token",[TransactionActionRolesController::class,'generateGstToken']);
        Route::get("/check-gst-number/{gstnumber}",[TransactionActionRolesController::class,'checkGstNumber']);


        // Reports

        Route::get('/treestyle-trial-balances-of-g-type/{accountid}/{level_no}',[FinalAccountsController::class,'reportFreeStyleTrialBalances']);

        Route::get('/get-child-accounts/{accountid}',[FinalAccountsController::class,'getChildAccounts']);

        Route::post('/treestyle-trial-balances',[FinalAccountsController::class,'reportFreeStyleTrialBalances']);
        
        Route::get('/treestyle-trial-balances',[FinalAccountsController::class,'reportFreeStyleTrialBalances'])->name('company.treestyle-trial-balances');

        Route::get('/test-report',[FinalAccountsController::class,'testReport']);

        Route::get("/search-account-in-tree/{searchtext}",[FinalAccountsController::class,'searchAccountByName']);

        Route::post('/search-by-account-name',[FinalAccountsController::class,'searchByAccountName']);

        Route::post("/set-report-free-style-search-values-in-session",[FinalAccountsController::class,'setReportFreeStyleSearchValuesInSession']);
  
        Route::get("/treestyle-trial-balances-of-a-type/{accountid}",[FinalAccountsController::class,'openSubledgerFromFreeStyleReport']);
    
        Route::get("/koolreport",[KoolReportController::class,'openkoolreport']);

        Route::get("/account-report",[FinalAccountsController::class,'openAccountReport']);

        
        Route::get("/get-edit-tran-data-history-table-and-id/{tablename}/{id}",[TransactionActionRolesController::class,'getEditTranDataHistoryUsingTableAndId']);

        Route::get("/download-subledger-excel",[DocController::class,'downloadSubledgerExcel']);


        Route::get('/test-download-pdf',  [DocController::class,'testDownloadPdf']);

        Route::get("/test-download-excel",[DocController::class,'testDownloadExcel']);

        Route::get('/general-ledger-new', [DocController::class, 'openGeneralLedger'])->name('company.general_ledger_new');
        Route::post('/general-ledger-new', [DocController::class, 'openGeneralLedger'])->name('company.general_ledger_new_submit');

        Route::get("/general-ledger-new-download/{format?}",[DocController::class,'downloadGeneralLedgerByFormat'])->name('company.download_general_ledger');

        Route::get("/cancel-cache-report-input-by-name/{reportname}",[DocController::class,'cancelCacheReportInputsByName'])->name('company.cancel_cache_report_inputs');;
        
        Route::post('/search-by-account-name-restricted',[FinalAccountsController::class,'searchByAccountNameRestricted']);

        Route::get("/download-subledger/{format?}",[DocController::class,'downloadSubledger']);
        Route::get('/trial-balances',[FinalAccountsController::class,'reportTrialBalances'])->name('company.trial-balances');
        
        Route::post('/trial-balances',[FinalAccountsController::class,'reportTrialBalances']);
 
        Route::get('/trial-balances-of-g-type/{accountid}/{level_no}',[FinalAccountsController::class,'reportTrialBalances']); 

        Route::get("/treestyle-p-and-l/{report_type?}/{report_for?}",[FinalAccountsController::class,'reportTreeStylePandL'])->name('company.treestyle-p-and-l');;

        Route::post("/treestyle-p-and-l",[FinalAccountsController::class,'reportTreeStylePandL']);
    
        Route::post("/send-subledger-report-email-whatsapp",[DocController::class,'sendSubledgerEmailWhatsapp']);

        Route::get("/test-download-subledger-and-mail",[DocController::class,'testDownloadSubledgerAndMail']);

        Route::get("/test-sequential",[FinalAccountsController::class,'testSequential']);

        Route::get("/download-tree-style-trial-balances/{format?}",[FinalAccountsController::class,'downloadTreeStyleTrialBalancesReport']);

        
        Route::get("/download-trial-balances/{format?}",[FinalAccountsController::class,'downloadTrialBalancesReport']);

        Route::get("/get-url-for-edit-transaction-data-by-bill-no/{bill_no}",[FinalAccountsController::class,'getUrlForEditTransactionDataByBillNo']);
        
        Route::post("/set-general-subledger-cache-inputs",[DocController::class,'setGeneralSubledgerCacheInputs']);

        Route::get("/download-treestyle-drilldown-report/{format?}",[FinalAccountsController::class,'downloadTreestyleDrilldownReport']);

        Route::get("/trial-balances-open-childaccounts/{account_id}/{account_level}",[FinalAccountsController::class,'reportTrialBalances']);

        Route::get("/download-trial-balances-drilldown-report/{format?}",[FinalAccountsController::class,'downloadTrialBalancesDrilldownReport']);
 
        Route::get("/test-download-invoice-from-url",[TransactionActionRolesController::class,'testDownloadInvoiceFromUrl']);

        Route::get("/track-order-by-billno/{gsi_id}",[TransactionActionRolesController::class,'trackOrderByBillNumber']);

        Route::get('/download-zip' ,[ZipController::class,'download']);

        Route::get("/test-whatsapp-send",[TransactionActionRolesController::class,'testWhatsAppSend']); 
        Route::get("/generate-temperary-url-to-files",[FinalAccountsController::class,'generateTemperaryUrl']);

        Route::get("/check-custom-fields",[TransactionActionRolesController::class,'checkCustomFields']);

        Route::get("/get-whatsapp-custom-fields",[TransactionActionRolesController::class,'getWhatsappCustomFields']);

        Route::get("/add-another-whatsapp-custom-field/{noofcustomfield}",[TransactionActionRolesController::class,'addAnotherWhatsappCustomField']);

    
  
        Route::get("/download-treestyle-p-and-l-report",[FinalAccountsController::class,'downloadTreeStylePandLReport']);
      
        Route::get("/download-treestyle-p-and-l-report/{report_type}/{format}",[FinalAccountsController::class,'downloadTreeStylePandLReport']);

        Route::post("/set-treestyle-trial-balance-drilldown-settings",[FinalAccountsController::class,'setTreestyleTrialBalanceDrilldownSettingsFromReport']);

       

        Route::get("/stock-ledger",[StockRegisterController::class,'openStockLedger'])->name('company.stock-ledger');;
        Route::post("/stock-ledger",[StockRegisterController::class,'openStockLedger']);

        Route::get("/reset-stock-ledger",[StockRegisterController::class,'resetStockLedger']);

        Route::get("/stock-ledger-fifo",[StockRegisterController::class,'stockLedgerFifo']);

        Route::get("/get-child-products/{productid}",[StockRegisterController::class,'getChildProducts']); 

        Route::get("/get-role-stockrate-restrictions/{roleid}", [RoleController::class,'getRoleStockRateRestrictions' ]);

        Route::post("/save-stockrate-restriction", [RoleController::class,'saveStockRateRestriction']);

        Route::get("/download-stock-ledger/{format}",[StockRegisterController::class,'downloadStockLedger']);

        Route::post("/send-email-whatsapp-stock-ledger",[StockRegisterController::class,'sendEmailWhatsappStockLedger'])->name('company.send-stock-ledger');
  
        Route::get("/stock-statement",[StockRegisterController::class,'openStockStatement'])->name('company.stock-statement');

        Route::post("/stock-statement",[StockRegisterController::class,'openStockStatement']) ;

        Route::get("/reset-stock-statement",[StockRegisterController::class,'resetStockStatement']);

        Route::get("/download-stock-statement/{format?}",[StockRegisterController::class,'downloadStockStatement']);

        Route::post("/send-email-whatsapp-stock-statement",[StockRegisterController::class,'sendEmailWhatsappStockStatement'])->name('company.send-stock-statement');
        
        Route::get("/stock-movement",[StockRegisterController::class,'openStockMovement'])->name('company.stock-movement');

        Route::get("/reset-stock-movement/{mode}",[StockRegisterController::class,'resetStockMovement']);

        Route::post("/stock-movement",[StockRegisterController::class,'openStockMovement']);

        Route::get("/download-stock-movement/{format?}",[StockRegisterController::class,'downloadStockMovement']);

        Route::post("/send-email-whatsapp-stock-movement",[StockRegisterController::class,'sendEmailWhatsappStockMovement'])->name('company.send-stock-movement');

        Route::get("/check-product-detail",[StockRegisterController::class,'checkProductDetail']);

        Route::get("/fast-moving-items",[StockRegisterController::class,'fastMovingItems'])->name('company.stock-fast-moving-items');
 
        Route::post("/fast-moving-items",[StockRegisterController::class,'fastMovingItems']);

        Route::get("/slow-moving-items",[StockRegisterController::class,'fastMovingItems'])->name('company.stock-slow-moving-items');
 
        Route::post("/slow-moving-items",[StockRegisterController::class,'fastMovingItems']);

        Route::get("/download-slow-fast-moving-items/{mode}/{format?}",[StockRegisterController::class,'downloadSlowFastMovingItems']);
 
        Route::post("/send-email-whatsapp-stock-fast-slow-moving-items/{report_name}",[StockRegisterController::class,'sendEmailWhatsappStockFastSlowMovingItems'])->name('company.send-stock-fast-slow-moving-items');
  
        

    });
});


