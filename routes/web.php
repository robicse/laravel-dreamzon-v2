<?php

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

/* artisan command */
Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    return 'cache clear';
});
Route::get('/config-cache', function() {
    $exitCode = Artisan::call('config:cache');
    return 'config:cache';
});
Route::get('/view-cache', function() {
    $exitCode = Artisan::call('view:cache');
    return 'view:cache';
});
Route::get('/view-clear', function() {
    $exitCode = Artisan::call('view:clear');
    return 'view:clear';
});
/* artisan command */





Route::get('/', function () {
    //return view('welcome');
    return redirect()->route('login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => ['auth']], function() {
    Route::resource('roles','RoleController');
    Route::resource('users','UserController');
    Route::resource('stores','StoreController');
    Route::resource('stores','StoreController');
    Route::resource('productCategories','ProductCategoryController');
    Route::resource('productSubCategories','ProductSubCategoryController');
    Route::resource('productBrands','ProductBrandController');
    Route::resource('products','ProductController');
    Route::resource('party','PartyController');
    Route::resource('productPurchases','ProductPurchaseController');
    Route::resource('productSales','ProductSaleController');
    Route::resource('productSaleReturns','ProductSaleReturnController');


    Route::get('productPurchases-invoice','ProductPurchaseController@invoice')->name('productPurchases-invoice');
    Route::get('productPurchases-invoice-print','ProductPurchaseController@invoicePrint')->name('productPurchases-invoice-print');
    Route::get('productSales-invoice/{id}','ProductSaleController@invoice')->name('productSales-invoice');
    Route::get('productSales-invoice-print/{id}','ProductSaleController@invoicePrint')->name('productSales-invoice-print');
    Route::get('sub-category-list','ProductController@subCategoryList');
    Route::get('product-relation-data','ProductPurchaseController@productRelationData');
    Route::get('product-sale-relation-data','ProductSaleController@productSaleRelationData');
    Route::get('stock-list','StockController@stockList')->name('stock.index');
    Route::get('returnable-sale-product-list','ProductSaleReturnController@returnableSaleProduct')->name('returnable.sale.product');
    Route::post('sale-product-return','ProductSaleReturnController@saleProductReturn')->name('sale.product.return');
    Route::get('transaction-list','TransactionController@transactionList')->name('transaction.index');
    Route::get('transaction-loss-profit','TransactionController@lossProfit')->name('transaction.lossProfit');
    Route::get('delivery-list','TransactionController@deliveryList')->name('delivery.index');
    Route::post('party/new-party','ProductSaleController@newParty')->name('parties.store.new');
    Route::post('party/supplier/new-party','ProductPurchaseController@newParty')->name('parties.supplier.store.new');
    Route::post('pay-due','ProductSaleController@payDue')->name('pay.due');

    //excel
    Route::get('export', 'UserController@export')->name('export');
    Route::get('importExportView', 'ExportExcelController@importExportView');
    Route::post('import', 'ExportExcelController@import')->name('import');

    Route::get('transaction/export/', 'TransactionController@export')->name('transaction.export');
    Route::get('delivery/export/', 'TransactionController@deliveryExport')->name('delivery.export');
    Route::get('loss-profit/export/', 'TransactionController@lossProfitExport')->name('loss.profit.export');
    Route::get('stock/export/', 'StockController@export')->name('stock.export');

    // custom start
    Route::post('/roles/permission','RoleController@create_permission');
    Route::post('/user/active','UserController@activeDeactive')->name('user.active');
});
