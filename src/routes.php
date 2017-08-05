<?php
// Backend
// ---------------------------------------------------------------------------------------------------------------------
Route::group( [
    'prefix'     => 'ilib/backend',
    'as'         => 'ilib.backend.',
    'middleware' => config( 'ilib.middlewares.backend' ),
    'namespace'  => 'Minhbang\ILib\Controllers\Backend',
], function () {
    // Dasboard
    Route::get( '/', [ 'as' => 'dashboard', 'uses' => 'HomeController@index' ] );
    // Statistics
    Route::group( [ 'prefix' => 'statistics', 'as' => 'statistics.' ], function () {
        Route::get( 'enum/{type}', [ 'as' => 'enum', 'uses' => 'StatisticsController@enum' ] );
        Route::get( 'category', [ 'as' => 'category', 'uses' => 'StatisticsController@category' ] );
        Route::get( 'read', [ 'as' => 'read', 'uses' => 'StatisticsController@read' ] );
        Route::get( 'read_data', [ 'as' => 'read_data', 'uses' => 'StatisticsController@read_data' ] );
    } );

    //Category Manage
    Route::group( [ 'prefix' => 'category', 'as' => 'category.' ], function () {
        Route::get( 'of/{type}', [ 'as' => 'type', 'uses' => 'CategoryController@index' ] );
        Route::get( 'data', [ 'as' => 'data', 'uses' => 'CategoryController@data' ] );
        Route::get( '{category}/create', [ 'as' => 'createChildOf', 'uses' => 'CategoryController@createChildOf' ] );
        Route::post( 'move', [ 'as' => 'move', 'uses' => 'CategoryController@move' ] );
        Route::post( '{category}', [ 'as' => 'storeChildOf', 'uses' => 'CategoryController@storeChildOf' ] );
        Route::post( '{category}/quick_update', [ 'as' => 'quick_update', 'uses' => 'CategoryController@quickUpdate' ] );
    } );
    Route::resource( 'category', 'CategoryController' );

    // Ebook Manage
    Route::group( [ 'prefix' => 'ebook', 'as' => 'ebook.' ], function () {
        Route::get( 'status/{status}', [ 'as' => 'index_status', 'uses' => 'EbookController@index' ] );
        Route::get( 'data/{status?}', [ 'as' => 'data', 'uses' => 'EbookController@data' ] );
        Route::get( 'select/{query}', [ 'as' => 'select', 'uses' => 'EbookController@select' ] );
        Route::get( '{file}/preview', [ 'as' => 'preview', 'uses' => 'EbookController@preview' ] );
        Route::post( '{ebook}/quick_update', [ 'as' => 'quick_update', 'uses' => 'EbookController@quickUpdate' ] );
        Route::post( '{ebook}/status/{status}', [ 'as' => 'status', 'uses' => 'EbookController@status' ] );
        Route::post( '{ebook}/status_up', [ 'as' => 'status_up', 'uses' => 'EbookController@statusUp' ] );
        Route::get( '{ebook}/edit_up', [ 'as' => 'edit_up', 'uses' => 'EbookController@editUp' ] );
    } );
    Route::resource( 'ebook', 'EbookController' );

    // Enum Manage
    Route::group( [ 'prefix' => 'enum', 'as' => 'enum.' ], function () {
        Route::post( 'order', [ 'as' => 'order', 'uses' => 'EnumController@order' ] );
        Route::get( 'of/{type}', [ 'as' => 'type', 'uses' => 'EnumController@index' ] );
        Route::post( '{enum}/quick_update', [ 'as' => 'quick_update', 'uses' => 'EnumController@quickUpdate' ] );
    } );
    Route::resource( 'enum', 'EnumController', [ 'except' => 'show' ] );

    // Reader Manage: quản lý bạn đọc
    Route::group( [ 'prefix' => 'reader', 'as' => 'reader.' ], function () {
        Route::get( 'data', [ 'as' => 'data', 'uses' => 'ReaderController@data' ] );
        Route::get( 'select/{query}', [ 'as' => 'select', 'uses' => 'ReaderController@select' ] );
        Route::post( '{reader}/quick_update', [ 'as' => 'quick_update', 'uses' => 'ReaderController@quickUpdate' ] );
    } );
    Route::resource( 'reader', 'ReaderController', [ 'except' => [ 'create', 'edit', 'update' ] ] );

    // Ebook Reader Manage: phân quyền đọc ebook tạm thời cho reader
    Route::group( [ 'prefix' => 'reader_ebook', 'as' => 'reader_ebook.' ], function () {
        Route::get( '/', [ 'as' => 'index', 'uses' => 'ReaderEbookController@index' ] );
        Route::get( 'data', [ 'as' => 'data', 'uses' => 'ReaderEbookController@data' ] );
        Route::post( '/', [ 'as' => 'store', 'uses' => 'ReaderEbookController@store' ] );
        Route::delete( '{reader}/{ebook}', [ 'as' => 'destroy', 'uses' => 'ReaderEbookController@destroy' ] );
        Route::post( '{reader}/{ebook}', [ 'as' => 'quick_update', 'uses' => 'ReaderEbookController@quickUpdate' ] );
    } );

    // Load du lieu tu ILib 4.0
    /*Route::group(['prefix' => 'ilib40', 'as' => 'ilib.backend.ilib40.'], function () {
        Route::get('/', ['as' => 'index', 'uses' => 'Ilib40Controller@index']);
    });*/
} );

// Frontend
// ---------------------------------------------------------------------------------------------------------------------
Route::group( [
    'prefix'     => 'ilib',
    'as'         => 'ilib.',
    'middleware' => config( 'ilib.middlewares.frontend' ),
    'namespace'  => 'Minhbang\ILib\Controllers\Frontend',
], function () {
    // Home
    Route::get( '/', [ 'as' => 'index', 'uses' => 'HomeController@index' ] );
    // Category
    Route::get( 'category/{slug}', [ 'as' => 'category.show', 'uses' => 'CategoryController@show' ] );
    // Ebook
    Route::group( [ 'prefix' => 'ebook', 'as' => 'ebook.' ], function () {
        // Xem chi tiết
        Route::get( '{ebook}', [ 'as' => 'detail', 'middleware' => [ 'reader:detail' ], 'uses' => 'EbookController@detail' ] );
        // Đọc toàn văn
        Route::get( '{ebook}/{file}/{slug}', [ 'as' => 'view', 'middleware' => [ 'reader:view' ], 'uses' => 'EbookController@view' ] );
        // Download
        Route::get( '{ebook}/{file}/download/{slug}.pdf', [ 'as' => 'download', 'middleware' => [ 'reader:download' ], 'uses' => 'EbookController@download' ] );
        // Upload
        Route::get( 'user/upload', [ 'as' => 'upload', 'middleware' => [ 'reader:upload' ], 'uses' => 'EbookController@upload' ] );
        Route::post( 'user/upload', [ 'middleware' => [ 'reader:upload' ], 'uses' => 'EbookController@store' ] );
    } );
    // Search
    Route::get( 'search', [ 'as' => 'search', 'uses' => 'SearchController@index' ] );
} );