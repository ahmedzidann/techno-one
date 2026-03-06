<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\Area\RegionController;
use App\Http\Controllers\Admin\Area\CountryController;
use App\Http\Controllers\Admin\Area\ProvinceController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BankController;
use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ChequeController;
use App\Http\Controllers\Admin\ClientAdjustmentController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\ClientPaymentSettingController;
use App\Http\Controllers\Admin\ClientSubscriptionController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\DestructionController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\EsalatController;
use App\Http\Controllers\Admin\HeadBackPurchasesController;
use App\Http\Controllers\Admin\HeadBackSalesController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\ItemInstallationController;
use App\Http\Controllers\Admin\PreparingItemController;
use App\Http\Controllers\Admin\ProductAdjustmentController;
use App\Http\Controllers\Admin\ProductionController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductLowBalanceController;
use App\Http\Controllers\Admin\PurchasesController;
use App\Http\Controllers\Admin\PurchasesRequestController;
use App\Http\Controllers\Admin\RasiedAyniController;
use App\Http\Controllers\Admin\Reports\AccountStatements\CustomerAccountStatementController;
use App\Http\Controllers\Admin\Reports\AccountStatements\SupplierAccountStatmentController;
use App\Http\Controllers\Admin\Reports\Bills\PurchasesBillController;
use App\Http\Controllers\Admin\Reports\Bills\SalesBillController;
use App\Http\Controllers\Admin\Reports\Customer\CustomerAccountController;
use App\Http\Controllers\Admin\Reports\Productive\ProductiveMovementController;
use App\Http\Controllers\Admin\Reports\Storage\StorageCheckController;
use App\Http\Controllers\Admin\RepresentativeClientController;
use App\Http\Controllers\Admin\RepresentativeController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SalesController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ShapeController;
use App\Http\Controllers\Admin\StorageController;
use App\Http\Controllers\Admin\StoreManagerController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\SupplierVoucherController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\ZonesSettingController;
use App\Http\Controllers\Admin\CouponConvertController;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::get('admin/login', [AuthController::class, 'loginView'])->name('admin.login');
Route::post('admin/login', [AuthController::class, 'postLogin'])->name('admin.postLogin');

// Admin Routes Group
Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function () {
    Route::get('/', [HomeController::class, 'index'])->name('admin.index');
    Route::get('logout', [AuthController::class, 'logout'])->name('admin.logout');

    // Admin Management
    Route::resource('admins', AdminController::class);
    Route::get('get-employees', [EmployeeController::class, 'getEmployee'])->name('admin.getEmployees');
    Route::get('get-clients', [ClientAdjustmentController::class, 'getClients'])->name('admin.getClients');
     Route::get('get-traders', [CouponConvertController::class, 'gettraders'])->name('admin.gettraders');
    Route::get('activateAdmin', [AdminController::class, 'activate'])->name('admin.active.admin');

    // Roles and Settings
    Route::resource('roles', RoleController::class);
    Route::resource('settings', SettingController::class);

    // Units and Categories
    Route::resource('unites', UnitController::class);
    Route::resource('categories', CategoryController::class);

    // Productive and Branches
    Route::resource('product', ProductController::class);
    Route::resource('branches', BranchController::class);

    // Storage and Rasied Ayni
    Route::resource('storages', StorageController::class);
    Route::resource('rasied_ayni', RasiedAyniController::class);
    Route::get('rasied_ayni_for_productive/{id}', [RasiedAyniController::class, 'rasied_ayni_for_productive'])->name('admin.rasied_ayni_for_productive');
    Route::get('getStorageForBranch/{id}', [RasiedAyniController::class, 'getStorageForBranch'])->name('admin.getStorageForBranch');
    Route::get('gitCreditForProductive/{id}', [RasiedAyniController::class, 'gitCreditForProductive'])->name('admin.gitCreditForProductive');

    // Area Management
    Route::resource('countries', CountryController::class);
    Route::resource('provinces', ProvinceController::class);
    Route::resource('regions', RegionController::class);

    // Clients and Suppliers
    Route::resource('clients', ClientController::class);
    Route::get('getCitiesForGovernorate/{id}', [ClientController::class, 'getCitiesForGovernorate'])->name('admin.getCitiesForGovernorate');
    Route::get('getRegionsForCity/{id}', [ClientController::class, 'getRegionsForCity'])->name('admin.getRegionsForCity');
    Route::get('clientsbystatus/{status}', [ClientController::class, 'ClientsByStatus'])->name('admin.clientsbystatus');
    Route::post('/admin/clients/update-status', [ClientController::class, 'updateStatus'])->name('admin.clients.update_status');

    Route::resource('suppliers', SupplierController::class);

    // Item Installations
    Route::resource('itemInstallations', ItemInstallationController::class);
    Route::get('makeRowDetailsForItemInstallations', [ItemInstallationController::class, 'makeRowDetailsForItemInstallations'])->name('admin.makeRowDetailsForItemInstallations');
    Route::get('getSubProductive', [ItemInstallationController::class, 'getSubProductive'])->name('admin.getSubProductive');
    Route::get('getProductiveDetails', [ItemInstallationController::class, 'getProductiveDetails'])->name('admin.getProductiveDetails');
    Route::get('getProductiveDetailsForPurchase/{id}', [ItemInstallationController::class, 'getProductiveDetailsForPurchase'])->name('admin.getProductiveDetailsForPurchase');
    Route::get('getProductiveTypeKham', [ItemInstallationController::class, 'getProductiveTypeKham'])->name('admin.getProductiveTypeKham');
    Route::get('getProductiveTypeTam', [ItemInstallationController::class, 'getProductiveTypeTam'])->name('admin.getProductiveTypeTam');
    Route::get('getProductiveTamDetails/{id}', [ItemInstallationController::class, 'getProductiveTamDetails'])->name('admin.getProductiveTamDetails');
    Route::get('getAllProductive', [ItemInstallationController::class, 'getAllProductive'])->name('admin.getAllProductive');
    Route::get('getAllBanks', [BankController::class, 'getAllBanks'])->name('admin.getAllBanks');

    // Esalat and Supplier Vouchers
    Route::resource('esalat', EsalatController::class);
    Route::get('getClientForEsalat', [EsalatController::class, 'getClientForEsalat'])->name('admin.getClientForEsalat');
    Route::get('getClientNameForEsalat/{id}', [EsalatController::class, 'getClientNameForEsalat'])->name('admin.getClientNameForEsalat');
    Route::get('getClients', [EsalatController::class, 'getClients'])->name('admin.getClients');
    Route::get('testing', [EsalatController::class, 'testing'])->name('admin.testing');

    Route::resource('supplier_vouchers', SupplierVoucherController::class);
    Route::get('getSupplierForVouchers', [SupplierVoucherController::class, 'getSupplierForVouchers'])->name('admin.getSupplierForVouchers');
    Route::get('getSupplierNameForVouchers/{id}', [SupplierVoucherController::class, 'getSupplierNameForVouchers'])->name('admin.getSupplierNameForVouchers');
    Route::get('getSupplier', [SupplierVoucherController::class, 'getSupplier'])->name('admin.getSupplier');

    // Purchases and Sales
    Route::resource('purchases', PurchasesController::class);
    Route::get('update-purchase-status/{id}', [PurchasesController::class, 'updatePurchaseStatus'])->name('update.purchase-status');
    Route::get('getPurchasesDetails/{id}', [PurchasesController::class, 'getPurchasesDetails'])->name('admin.getPurchasesDetails');
    Route::get('purchases-for-supplier/{supplier_id}', [PurchasesController::class, 'getPurchasesForSupplier'])->name('admin.getPurchasesForSupplier');
    Route::get('getStorages', [PurchasesController::class, 'getStorages'])->name('admin.getStorages');
    Route::get('makeRowDetailsForPurchasesDetails', [PurchasesController::class, 'makeRowDetailsForPurchasesDetails'])->name('admin.makeRowDetailsForPurchasesDetails');

    // Purchases-requests and Sales
    Route::resource('purchases-requests', PurchasesRequestController::class);

    Route::resource('head_back_purchases', HeadBackPurchasesController::class);
    Route::get('getHeadBackPurchasesDetails/{id}', [HeadBackPurchasesController::class, 'getHeadBackPurchasesDetails'])->name('admin.getHeadBackPurchasesDetails');
    Route::get('/head-back-purchases/invoice-details/{purchase_id}', [HeadBackPurchasesController::class, 'getInvoiceDetails'])
        ->name('admin.head-back-purchases.invoice-details');
    Route::get('/head-back-purchases/invoice-details-edit/{purchases_id}', [HeadBackPurchasesController::class, 'getInvoiceDetailsEdit'])
        ->name('admin.head-back-purchases.invoice-details-edit');
    Route::get('/head-back-sales/invoice-details/{sale_number_id}', [HeadBackSalesController::class, 'getInvoiceDetails'])
        ->name('admin.head-back-sales.invoice-details');
    Route::get('/head-back-sales/invoice-details-edit/{sale_number_id}', [HeadBackSalesController::class, 'getInvoiceDetailsEdit'])
        ->name('admin.head-back-sales.invoice-details-edit');

    Route::resource('sales', SalesController::class);
    Route::get('getSalesDetails/{id}', [SalesController::class, 'getSalesDetails'])->name('admin.getSalesDetails');
    Route::get('makeRowDetailsForSalesDetails', [SalesController::class, 'makeRowDetailsForSalesDetails'])->name('admin.makeRowDetailsForSalesDetails');
    Route::post('sales/update-status', [SalesController::class, 'updateStatus'])->name('admin.update-sales-status');

    Route::resource('head_back_sales', HeadBackSalesController::class);
    Route::get('getHeadBackSalesDetails/{id}', [HeadBackSalesController::class, 'getHeadBackSalesDetails'])->name('admin.getHeadBackSalesDetails');

    // Production and Destruction
    Route::resource('productions', ProductionController::class);
    Route::get('getProductionDetails/{id}', [ProductionController::class, 'getProductionDetails'])->name('admin.getProductionDetails');
    Route::get('makeRowDetailsForProductionDetails', [ProductionController::class, 'makeRowDetailsForProductionDetails'])->name('admin.makeRowDetailsForProductionDetails');
    Route::get('product-price/{id}', [ProductController::class, 'getPrice'])->name('admin.getProductPrice');
    Route::resource('destruction', DestructionController::class);
    Route::get('getDestructionDetails/{id}', [DestructionController::class, 'getDestructionDetails'])->name('admin.getDestructionDetails');
    Route::get('makeRowDetailsForDestructionDetails', [DestructionController::class, 'makeRowDetailsForDestructionDetails'])->name('admin.makeRowDetailsForDestructionDetails');
    Route::get('getDestructionPrice', [DestructionController::class, 'getDestructionPrice'])->name('admin.getDestructionPrice');

    // Reports
    Route::get('customerAccountStatements', [CustomerAccountStatementController::class, 'index'])->name('admin.customerAccountStatements');
    Route::get('customer-account-state', [CustomerAccountController::class, 'index'])->name('admin.customerAccountState');
    Route::get('customers-balances', [CustomerAccountController::class, 'customers_balances'])->name('admin.customers_balances');
    Route::get('supplierAccountStatements', [SupplierAccountStatmentController::class, 'index'])->name('admin.supplierAccountStatements');
    Route::resource('purchasesBills', PurchasesBillController::class);
    Route::resource('salesBills', SalesBillController::class);

    // Employees and Companies
    Route::resource('employees', EmployeeController::class);
    Route::resource('companies', CompanyController::class);
    Route::get('getCompanies', [CompanyController::class, 'getCompanies'])->name('admin.get-companies');
    Route::resource('shapes', ShapeController::class);
    Route::get('getShapes', [ShapeController::class, 'getShapes'])->name('admin.get-shapes');

    // Prepare Items
    Route::resource('prepare-items', PreparingItemController::class);
    Route::get('batch-numbers', [PreparingItemController::class, 'getBatchNumbers'])->name('admin.getBatches');
    Route::post('update-status', [PreparingItemController::class, 'updateIsPrepared'])->name('update.prepare-status');

    Route::resource('store-managers', StoreManagerController::class);

    // representatives
    Route::resource('representatives', RepresentativeController::class);
    Route::get('representatives/{representative}/details', [RepresentativeController::class, 'details'])->name('representatives.details');
    Route::get('representatives-data', [RepresentativeController::class, 'getRepresentatives'])->name('admin.getRepresentatives');
    Route::get('distributors-data', [RepresentativeController::class, 'getDistributors'])->name('admin.getDistributors');

    // representative-clients
    Route::get('representative-clients', [RepresentativeClientController::class, 'index'])->name('representative-clients.index');
    Route::post('representative-clients/delete', [RepresentativeClientController::class, 'destroy'])->name('representative-clients.delete');
    Route::post('add-clients-to-representative', [RepresentativeClientController::class, 'AddClientsToRepresentative'])->name('add-clients-to-representative.create');

    // client-payment-settings
    Route::resource('client-payment-settings', ClientPaymentSettingController::class);
    Route::post('client-payment-settings-data', [ClientPaymentSettingController::class, 'getClientPaymentSetting'])->name('admin.getClientPaymentSettings');

    Route::get('sales-for-client/{client_id}', [SalesController::class, 'getSalesForClient'])->name('admin.getSalesForClient');

    // ProductiveMovement
    Route::resource('productive-movement', ProductiveMovementController::class);

    // storage check
    Route::resource('storage-check', StorageCheckController::class);

    // product-adjustments
    Route::resource('product-adjustments', ProductAdjustmentController::class);
    Route::get('makeRowDetailsForProductAdjustment', [ProductAdjustmentController::class, 'makeRowDetailsForProductAdjustment'])->name('admin.makeRowDetailsForProductAdjustment');

    // client-adjustments
    Route::resource('client-adjustments', ClientAdjustmentController::class);

    Route::get('/get-child-cities', [ZonesSettingController::class, 'getChildCities'])->name('admin.getChildCities');

    //cheques
    Route::resource('/cheques', ChequeController::class)->only('index');
    Route::post('/update-cheque-status', [ChequeController::class, 'changeStatusChequeStatus'])->name('admin.changeStatusChequeStatus');
    Route::get('/customer-balance', [SalesController::class, 'customerBalance'])->name('admin.customerBalance');
    
    //products-low-balance
    Route::get('/products-low-balance', [ProductLowBalanceController::class, 'index'])->name('admin.products-low-balance');

    //client_subscriptions
    Route::resource('/client-subscriptions', ClientSubscriptionController::class);
    Route::resource('/coupons-converts', CouponConvertController::class);
    Route::get('coupons-status/{status}', [CouponConvertController::class, 'CouponStatus'])->name('admin.coupons-status');
    Route::post('/admin/coupons-status/update-status', [CouponConvertController::class, 'updateStatus'])->name('admin.coupons-status.update_status');
    
});
