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
    Route::get('change-password/{id}','UserController@changedPassword')->name('password.change_password');
    Route::post ('change-password-update','UserController@changedPasswordUpdated')->name('password.change_password_update');

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
    Route::resource('deliveryService','DeliveryServiceController');
    Route::resource('productExpireds','ProductExpiredController');
    Route::resource('stockTransfers','StockTransferController');

    Route::get('product-pos-purchases','ProductPosPurchaseController@create')->name('product.pos.purchases.create');
    Route::get('selected-pos-purchase-form','ProductPosPurchaseController@selectedform');
    Route::get('append-row','ProductPosPurchaseController@appendToRow');


    Route::get('productPurchases-invoice','ProductPurchaseController@invoice')->name('productPurchases-invoice');
    Route::get('productPurchases-invoice-print','ProductPurchaseController@invoicePrint')->name('productPurchases-invoice-print');
    Route::get('productSales-invoice/{id}','ProductSaleController@invoice')->name('productSales-invoice');
    Route::get('productSales-invoice-print/{id}','ProductSaleController@invoicePrint')->name('productSales-invoice-print');
    Route::get('sub-category-list','ProductController@subCategoryList');
    Route::get('check-barcode','ProductController@checkBarcode');
    Route::get('product-relation-data','ProductPurchaseController@productRelationData');
    Route::get('product-sale-relation-data','ProductSaleController@productSaleRelationData');
    Route::get('stock-list','StockController@stockList')->name('stock.index');
    Route::get('stock-details/{store_id}','StockController@stockDetails');
    Route::get('stock-summary-list','StockController@stockSummaryList')->name('stock.summary.list');
    Route::get('stock-summary/{store_id}','StockController@stockSummary');
    Route::get('returnable-sale-product-list','ProductSaleReturnController@returnableSaleProduct')->name('returnable.sale.product');
    Route::post('sale-product-return','ProductSaleReturnController@saleProductReturn')->name('sale.product.return');
    Route::get('get-returnable-product/{sale_id}','ProductSaleReturnController@getReturnableProduct');
    Route::get('transaction-list','TransactionController@transactionList')->name('transaction.index');
    Route::get('transaction-details/{store_id}','TransactionController@transactionDetails')->name('transaction-details');
    Route::get('transaction-loss-profit','TransactionController@lossProfit')->name('transaction.lossProfit');
    Route::get('transaction-loss-profit-details/{store_id}','TransactionController@transactionLossProfitDetails');
    Route::get('delivery-list','TransactionController@deliveryList')->name('delivery.index');
    Route::post('party/new-party','ProductSaleController@newParty')->name('parties.store.new');
    Route::post('party/supplier/new-party','ProductPurchaseController@newParty')->name('parties.supplier.store.new');
    Route::post('pay-due','ProductSaleController@payDue')->name('pay.due');
    Route::get('expired-product-list','ProductExpiredController@expiredProduct')->name('expired.product');
    Route::post('expired-product-store','ProductExpiredController@expiredProductStore')->name('expired.product.store');

    Route::post('productPurchase/findPurchaseOrder', 'ProductPurchaseController@index');



    Route::get('productPosSales/list','ProductPosSaleController@index')->name('productPosSales.index');
    Route::get('productPosSales','ProductPosSaleController@create')->name('productPosSales.create');
    Route::get('get-product-by-store','ProductPosSaleController@showProductByStore');
    Route::get('sale/{id}/data', 'ProductPosSaleController@listData')->name('sale.data');
    Route::get('sale/loadform/{discount}/{total}/{paid}', 'ProductPosSaleController@loadForm');
    Route::get('pos/print/{id}/{status}', 'PointOfSaleController@print')->name('pointOfSale.print');
    Route::get('pos/print2/{id}/{status}', 'PointOfSaleController@print2')->name('pointOfSale.print2');


    Route::get('selectedform/{product_code}/{store_id}','ProductPosSaleController@selectedform');
    Route::get('add-to-cart','CartController@addToCart');
    Route::get('delete-cart-product/{rowId}','CartController@deleteCartProduct');
    Route::get('delete-all-cart-product','CartController@deleteAllCartProduct');
    Route::post('pos_insert', 'ProductPosSaleController@postInsert');

    // stock sync
    Route::get('stock_sync','StockController@stock_sync')->name('stock_sync');


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
