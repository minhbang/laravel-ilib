<?php
// Backend
Route::group(['prefix' => 'ilib/backend', 'namespace' => 'Minhbang\ILib\Controllers\Backend'], function () {
    // Dasboard
    Route::get('/', ['as' => 'ilib.backend.dashboard', 'uses' => 'HomeController@index']);

    //Category Manage
    Route::group(['prefix' => 'category', 'as' => 'ilib.backend.category.'], function () {
        Route::get('data', ['as' => 'data', 'uses' => 'CategoryController@data']);
        Route::get('{category}/create', ['as' => 'createChildOf', 'uses' => 'CategoryController@createChildOf']);
        Route::post('move', ['as' => 'move', 'uses' => 'CategoryController@move']);
        Route::post('{category}', ['as' => 'storeChildOf', 'uses' => 'CategoryController@storeChildOf']);
        Route::post('{category}/quick_update', ['as' => 'quick_update', 'uses' => 'CategoryController@quickUpdate']);
    });
    Route::resource('category', 'CategoryController');

    // Ebook Manage
    Route::group(['prefix' => 'ebook', 'as' => 'ilib.backend.ebook.'], function () {
        Route::get('data', ['as' => 'data', 'uses' => 'EbookController@data']);
        Route::get('select/{query}', ['as' => 'select', 'uses' => 'EbookController@select']);
        Route::get('{ebook}/preview', ['as' => 'preview', 'uses' => 'EbookController@preview']);
        Route::post('{ebook}/quick_update', ['as' => 'quick_update', 'uses' => 'EbookController@quickUpdate']);
        Route::post('{ebook}/status/{status}', ['as' => 'status', 'uses' => 'EbookController@status']);
    });
    Route::resource('ebook', 'EbookController');

    // Enum Manage
    Route::group(['prefix' => 'enum', 'as' => 'ilib.backend.enum.'], function () {
        Route::post('order', ['as' => 'order', 'uses' => 'EnumController@order']);
        Route::get('of/{type}', ['as' => 'type', 'uses' => 'EnumController@index']);
        Route::post('{enum}/quick_update', ['as' => 'quick_update', 'uses' => 'EnumController@quickUpdate']);
    });
    Route::resource('enum', 'EnumController', ['except' => 'show']);

    // Reader Manage: quản lý bạn đọc
    Route::group(['prefix' => 'reader', 'as' => 'ilib.backend.reader.'], function () {
        Route::get('data', ['as' => 'data', 'uses' => 'ReaderController@data']);
        Route::get('select/{query}', ['as' => 'select', 'uses' => 'ReaderController@select']);
        Route::post('{reader}/quick_update', ['as' => 'quick_update', 'uses' => 'ReaderController@quickUpdate']);
    });
    Route::resource('reader', 'ReaderController', ['except' => ['create', 'edit', 'update']]);

    // Ebook Reader Manage: phân quyền đọc ebook tạm thời cho reader
    Route::group(['prefix' => 'reader_ebook', 'as' => 'ilib.backend.reader_ebook.'], function () {
        Route::get('/', ['as' => 'index', 'uses' => 'ReaderEbookController@index']);
        Route::get('data', ['as' => 'data', 'uses' => 'ReaderEbookController@data']);
        Route::post('/', ['as' => 'store', 'uses' => 'ReaderEbookController@store']);
        Route::delete('{reader}/{ebook}', ['as' => 'destroy', 'uses' => 'ReaderEbookController@destroy']);
        Route::post('{reader}/{ebook}', ['as' => 'quick_update', 'uses' => 'ReaderEbookController@quickUpdate']);
    });
});

// Frontend
Route::group(['prefix' => 'ilib', 'as' => 'ilib.', 'namespace' => 'Minhbang\ILib\Controllers\Frontend'], function () {
    Route::get('/', ['as' => 'index', 'uses' => 'HomeController@index']);
    Route::get('category/{category}', ['as' => 'category.show', 'uses' => 'CategoryController@show']);
    // Ebook
    Route::group(['prefix' => 'ebook', 'as' => 'ebook.'], function () {
        Route::get('{ebook}', ['as' => 'show', 'uses' => 'EbookController@show']);
        Route::get('{ebook}/{slug}.pdf', ['as' => 'full', 'uses' => 'EbookController@full']);
    });
    // Search
    Route::get('search', ['as' => 'search', 'uses' => 'SearchController@index']);
});