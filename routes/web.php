<?php

use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Front\WishlistGroupController;
use App\Mail\RedplayLicenseMail;
use App\Models\License;
use App\Models\Order;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::get('test/redplay-email', function () {
    $order = Order::orderBy('id', 'DESC')->first();
    $license = License::orderBy('id', 'DESC')->first();

    return new RedplayLicenseMail($order, $license);
});

Route::get('/checkoutv2', function(){
    return view('front.checkout.checkoutv2');
});


Route::post('trumbowyg-upload', 'Controller@trumbowygUpload')->name('trumbowyg-upload');

/**
 * Admin Section
 */
Route::group([
    'prefix' => 'admin',
    'namespace' => 'Admin'
], function () {
    Route::group([
        'as' => 'admin.'
    ], function () {
        /**
         * Notification Routes
         */

        Route::group([
            'as' => 'notifications-',
            'prefix' => 'notifications'
        ], function () {
            Route::get('show', 'NotificationController@show')->name('show');
            Route::get('count', 'NotificationController@count')->name('count');
            Route::get('mark-as-read', 'NotificationController@markAllAsRead')->name('mark-as-read');
            Route::get('clear/{notification_id}', 'NotificationController@clear')->name('clear');
            Route::get('notification', [NotificationController::class, 'notification'])->name('notification');
        });

        Route::group([
            'as' => 'bling.',
            'prefix' => 'bling'
        ], function () {
            Route::get('/', 'BlingController@authenticate')->name('authenticate');
            Route::get('/token', 'BlingController@setTokens')->name('set.token');
        });

        Route::group([
            'as' => 'conv-',
            'prefix' => 'conv'
        ], function () {
            Route::get('notf/show', 'NotificationController@conv_notf_show')->name('notf-show');
            Route::get('notf/count', 'NotificationController@conv_notf_count')->name('notf-count');
            Route::get('notf/clear', 'NotificationController@conv_notf_clear')->name('notf-clear');
        });

        Route::group([
            'as' => 'product-',
            'prefix' => 'product'
        ], function () {
            Route::get('notf/show', 'NotificationController@product_notf_show')->name('notf-show');
            Route::get('notf/count', 'NotificationController@product_notf_count')->name('notf-count');
            Route::get('notf/clear', 'NotificationController@product_notf_clear')->name('notf-clear');
        });

        Route::group([
            'as' => 'user-',
            'prefix' => 'user'
        ], function () {
            Route::get('notf/show', 'NotificationController@user_notf_show')->name('notf-show');
            Route::get('notf/count', 'NotificationController@user_notf_count')->name('notf-count');
            Route::get('notf/clear', 'NotificationController@user_notf_clear')->name('notf-clear');
        });

        Route::group([
            'as' => 'order-',
            'prefix' => 'order'
        ], function () {
            Route::get('notf/show', 'NotificationController@order_notf_show')->name('notf-show');
            Route::get('notf/count', 'NotificationController@order_notf_count')->name('notf-count');
            Route::get('notf/clear', 'NotificationController@order_notf_clear')->name('notf-clear');
        });

        Route::group([
            'as' => 'wishlist.',
            'prefix' => 'wishlist'
        ], function () {
            Route::get('/', 'WishlistGroupController@index')->name('index');
            Route::get('/datatables', 'WishlistGroupController@datatables')->name('datatables');
            Route::get('/show/{wishlistGroup}', 'WishlistGroupController@show')->name('show');
        });

        Route::group([
            'as' => 'receipt-',
            'prefix' => 'receipt'
        ], function () {
            Route::get('notf/show', 'NotificationController@receipt_notf_show')->name('notf-show');
            Route::get('notf/count', 'NotificationController@receipt_notf_count')->name('notf-count');
            Route::get('notf/clear', 'NotificationController@receipt_notf_clear')->name('notf-clear');
        });

        /** Authentication **/
        Route::get('login', 'LoginController@showLoginForm')->name('login');
        Route::post('login', 'LoginController@login')->name('login.submit');
        Route::get('forgot', 'LoginController@showForgotForm')->name('forgot');
        Route::post('forgot', 'LoginController@forgot')->name('forgot.submit');
        Route::get('logout', 'LoginController@logout')->name('logout');

        /** Profile **/
        Route::post('profile/update', 'DashboardController@profileupdate')->name('profile.update');
        Route::get('profile', 'DashboardController@profile')->name('profile');
        Route::post('password/update', 'DashboardController@changepass')->name('password.update');
        Route::get('password', 'DashboardController@passwordreset')->name('password');
        Route::post('profile/delete-image', 'DashboardController@deleteImage')->name('delete.image');

        /* Mercado Livre Dashboard */
        Route::group([
            'as' => 'meli-',
            'prefix' => 'mercadolivre',
            'middleware' => 'is.meli'
        ], function () {
            Route::get('dashboard', 'MercadoLivreController@dashboard')->name('dashboard');
        });

        /** Dashboard **/
        Route::get('/', 'DashboardController@index')->name('dashboard');
    });

    Route::group([
        'as' => 'admin-'
    ], function () {
        /**
         * Stores Routes
         */
        Route::group([
            'middleware' => 'permissions:stores'
        ], function () {
            /** Stores */
            Route::group([
                'as' => 'stores-',
                'prefix' => 'stores'
            ], function () {
                Route::get('create', 'StoreController@create')->name('create');
                Route::post('create', 'StoreController@store')->name('store');
                Route::get('edit/{id}', 'StoreController@edit')->name('edit');
                Route::post('update/{id}', 'StoreController@update')->name('update');
                Route::get('delete/{id}', 'StoreController@destroy')->name('delete');
                Route::get('status/{id1}/{id2}', 'StoreController@status')->name('status');
                Route::get('isconfig/{id}/{redirect?}', 'StoreController@isconfig')->name('isconfig');
                Route::get('datatables', 'StoreController@datatables')->name('datatables');
                Route::get('/', 'StoreController@index')->name('index');
            });
        });

        /**
         * Catalog Routes
         */
        Route::group([
            'middleware' => 'permissions:catalog'
        ], function () {
            /** Products */
            Route::group([
                'as' => 'prod-',
                'prefix' => 'products'
            ], function () {
                Route::post('edit-meli/{id}', 'ProductController@updateMeli')->name('update-meli');
                Route::get('edit-meli/{id}', 'ProductController@editMeli')->name('edit-meli');
                Route::get('meli-update/{id}', 'MercadoLivreController@updateItem')->name('meli-update');
                Route::get('meli-send/{id}', 'MercadoLivreController@sendItem')->name('meli-send');
                Route::get('physical/create', 'ProductController@createPhysical')->name('physical-create');
                Route::post('store', 'ProductController@store')->name('store');
                Route::get('edit/{id}', 'ProductController@edit')->name('edit');
                Route::post('edit/{id}', 'ProductController@update')->name('update');
                Route::post('upload/update/{id}', 'ProductController@uploadUpdate')->name('upload-update');
                Route::get('delete/{id}', 'ProductController@destroy')->name('delete');
                Route::get('copy/{id}', 'ProductController@copy')->name('copy');
                Route::get('status/{id1}/{id2}', 'ProductController@status')->name('status');
                Route::get('featured/{id1}/{id2}', 'ProductController@featured')->name('featured');
                Route::get('/catalog/{id1}/{id2}', 'ProductController@catalog')->name('catalog');
                Route::get('feature/{id}', 'ProductController@feature')->name('feature');
                Route::post('feature/{id}', 'ProductController@featuresubmit')->name('prod-feature');
                Route::get('fastedit/{id}', 'ProductController@fastedit')->name('fastedit');
                Route::post('fastedit/{id}', 'ProductController@fasteditsubmit')->name('prod-fastedit');
                Route::get('bulkedit', 'ProductController@bulkedit')->name('bulkedit');
                Route::post('bulkedit', 'ProductController@bulkeditsubmit')->name('prod-bulkedit');
                Route::post('bulkdelete', 'ProductController@bulkdeletesubmit')->name('bulkdelete');
                Route::get('getattributes', 'ProductController@getAttributes')->name('getattributes');
                Route::get('datatables/{slug?}', 'ProductController@datatables')->name('datatables');
                Route::get('/', 'ProductController@index')->name('index');
                Route::get('comprasparaguai', 'ProductController@updateXMLComprasParaguai')->name('comprasparaguai');
                Route::get('loja', 'ProductController@updateXMLGoogleAndFacebook')->name('updateloja');
                Route::post('delete-img', 'ProductController@deleteProductImage')->name('delete-img');
                Route::get('import_csv', 'ProductController@import')->name('import');
                Route::post('prepare_import', 'ProductController@prepareImport')->name('prepareImport');
                Route::post('import_submit', 'ProductController@importSubmit')->name('importsubmit');
                Route::post('end_import', 'ProductController@endImport')->name('endImport');
                Route::get('generate_thumbnails_ftp', 'ProductController@generateThumbnailsFtp')->name('generatethumbnails');
                Route::get('generate_thumbnails', 'ProductController@generateThumbnails')->name('btngeneratethumbnails');
            });

            /** Categories */
            Route::group([
                'as' => 'cat-',
                'prefix' => 'category'
            ], function () {
                Route::get('create', 'CategoryController@create')->name('create');
                Route::post('create', 'CategoryController@store')->name('store');
                Route::get('edit/{id}', 'CategoryController@edit')->name('edit');
                Route::post('edit/{id}', 'CategoryController@update')->name('update');
                Route::get('delete/{id}', 'CategoryController@destroy')->name('delete');
                Route::get('status/{id1}/{id2}', 'CategoryController@status')->name('status');
                Route::get('changeCatPos/{id1}/{pos}', 'CategoryController@changeCatPos')->name('changeCatPos');
                Route::get('datatables/{filter?}', 'CategoryController@datatables')->name('datatables');
                Route::get('/', 'CategoryController@index')->name('index');
                Route::post('/delete-image', 'CategoryController@deleteImage')->name('delete-image');
                Route::post('/delete-banner', 'CategoryController@deleteBanner')->name('delete-banner');
            });

            /** Subcategories */
            Route::group([
                'as' => 'subcat-',
            ], function () {
                Route::group([
                    'prefix' => 'subcategory'
                ], function () {
                    Route::get('create', 'SubCategoryController@create')->name('create');
                    Route::post('create', 'SubCategoryController@store')->name('store');
                    Route::get('edit/{id}', 'SubCategoryController@edit')->name('edit');
                    Route::post('edit/{id}', 'SubCategoryController@update')->name('update');
                    Route::get('delete/{id}', 'SubCategoryController@destroy')->name('delete');
                    Route::get('status/{id1}/{id2}', 'SubCategoryController@status')->name('status');
                    Route::get('datatables', 'SubCategoryController@datatables')->name('datatables');
                    Route::get('/', 'SubCategoryController@index')->name('index');
                });
                Route::get('load/subcategories/{id}/', 'SubCategoryController@load')->name('load');
            });

            /** Child Categories */
            Route::group([
                'as' => 'childcat-',
            ], function () {
                Route::group([
                    'prefix' => 'childcategory'
                ], function () {
                    Route::get('create', 'ChildCategoryController@create')->name('create');
                    Route::post('create', 'ChildCategoryController@store')->name('store');
                    Route::get('edit/{id}', 'ChildCategoryController@edit')->name('edit');
                    Route::post('edit/{id}', 'ChildCategoryController@update')->name('update');
                    Route::get('delete/{id}', 'ChildCategoryController@destroy')->name('delete');
                    Route::get('status/{id1}/{id2}', 'ChildCategoryController@status')->name('status');
                    Route::get('datatables', 'ChildCategoryController@datatables')->name('datatables');
                    Route::get('/', 'ChildCategoryController@index')->name('index');
                });
                Route::get('load/childcategories/{id}/', 'ChildCategoryController@load')->name('load');
            });

            /** Attributes */
            Route::group([
                'as' => 'attr-',
                'prefix' => 'attribute'
            ], function () {
                Route::get('{catid}/attrCreateForCategory', 'AttributeController@attrCreateForCategory')->name('createForCategory');
                Route::get('{subcatid}/attrCreateForSubcategory', 'AttributeController@attrCreateForSubcategory')->name('createForSubcategory');
                Route::get('{childcatid}/attrCreateForChildcategory', 'AttributeController@attrCreateForChildcategory')->name('createForChildcategory');
                Route::get('{id}/datatables/{type}', 'AttributeController@datatables')->name('datatables');
                Route::post('store', 'AttributeController@store')->name('store');
                Route::get('{attrid}/edit', 'AttributeController@edit')->name('edit');
                Route::post('edit/{id}', 'AttributeController@update')->name('update');
                Route::get('delete/{id}', 'AttributeController@destroy')->name('delete');
                Route::post('deleteattropt/{id}', 'AttributeController@deleteAttrOpt')->name('deleteattropt');
                Route::get('{id}/options', 'AttributeController@options')->name('options');
                Route::get('{id}/manage', 'AttributeController@manage')->name('manage');
            });

            /** Brands */
            Route::group([
                'as' => 'brand-',
            ], function () {
                Route::group([
                    'prefix' => 'brand'
                ], function () {
                    Route::get('datatables/{filter?}', 'BrandController@datatables')->name('datatables');
                    Route::get('create', 'BrandController@create')->name('create');
                    Route::post('create', 'BrandController@store')->name('store');
                    Route::get('edit/{id}', 'BrandController@edit')->name('edit');
                    Route::post('edit/{id}', 'BrandController@update')->name('update');
                    Route::get('delete/{id}', 'BrandController@destroy')->name('delete');
                    Route::get('status/{id1}/{id2}', 'BrandController@status')->name('status');
                    Route::get('/', 'BrandController@index')->name('index');
                    Route::get('generateThumbnails', 'BrandController@generateThumbnails')->name('generatethumbnails');
                    Route::post('/delete-image', 'BrandController@deleteImage')->name('delete-image');
                });
                Route::get('load/brands/{id}/', 'BrandController@load')->name('load');
            });
        });

        /**
         * Sell Routes
         */
        Route::group([
            'middleware' => 'permissions:sell'
        ], function () {
            /** Orders */
            Route::group([
                'as' => 'order-'
            ], function () {
                Route::group([
                    'prefix' => 'orders'
                ], function () {
                    Route::get('pending', 'OrderController@pending')->name('pending');
                    Route::get('processing', 'OrderController@processing')->name('processing');
                    Route::get('completed', 'OrderController@completed')->name('completed');
                    Route::get('simplified-checkout-datatables/{slug}', 'OrderController@datatablesSimplified')->name('simplified-datatables');
                    Route::get('simplified-checkout', 'OrderController@simplifiedCheckout')->name('simplified-checkout');
                    Route::get('/', 'OrderController@index')->name('index');
                });
                Route::group([
                    'prefix' => 'order'
                ], function () {
                    Route::get('edit/{id}', 'OrderController@edit')->name('edit');
                    Route::get('{id}/show', 'OrderController@show')->name('show');
                    Route::post('update/{id}', 'OrderController@update')->name('update');
                    Route::get('{id}/invoice', 'OrderController@invoice')->name('invoice');
                    Route::get('{id}/billetStatus', 'OrderController@billetStatus')->name('billet-status');
                    Route::get('{id}/receipt', 'OrderController@receipt')->name('receipt');
                    Route::get('order/managereceipt/{id}/{action}', 'OrderController@manageReceipt')->name('manage-receipt');
                    Route::post('{id}/license', 'OrderController@license')->name('license');
                    Route::get('{id}/print', 'OrderController@printpage')->name('print');
                    Route::get('datatables/{slug}', 'OrderController@datatables')->name('datatables');
                    Route::get('datatablesAll/{slug}', 'OrderController@datatablesAll')->name('datatablesAll');
                    Route::get('track/add', 'OrderTrackController@add')->name('track-add');
                    Route::post('track/store', 'OrderTrackController@store')->name('track-store');
                    Route::get('{id}/trackload', 'OrderTrackController@load')->name('track-load');
                    Route::post('track/update/{id}', 'OrderTrackController@update')->name('track-update');
                    Route::get('track/delete/{id}', 'OrderTrackController@delete')->name('track-delete');
                    Route::get('{id}/track', 'OrderTrackController@index')->name('track');
                    Route::post('email/', 'OrderController@emailsub')->name('emailsub');
                    Route::get('send_order/{id}', 'OrderController@sendOrder')->name('send-order');
                    Route::get('select_aex_city/{order_id}', 'AexController@selectAexCity')->name('select-aex-city');
                    Route::post('request_aex', 'AexController@requestaex')->name('request-aex');
                    Route::post('confirm_aex', 'AexController@confirmaex')->name('confirm-aex');
                    Route::get('confirm_melhorenvio_package/{order_id}', 'MelhorEnvioController@confirmPackageOptions')->name('confirm-melhorenvio-package');
                    Route::post('select_melhorenvio_service', 'MelhorEnvioController@selectService')->name('select-melhorenvio-service');
                    Route::post('request_melhorenvio', 'MelhorEnvioController@requestMelhorenvio')->name('request-melhorenvio');
                    Route::post('cart_melhorenvio', 'MelhorEnvioController@cartMelhorenvio')->name('cart-melhorenvio');
                    Route::post('checkout_melhorenvio', 'MelhorEnvioController@checkoutMelhorenvio')->name('checkout-melhorenvio');
                    Route::get('generate_melhorenvio/{request_id}', 'MelhorEnvioController@generateMelhorenvio')->name('generate-melhorenvio');
                    Route::post('generate_confirm_melhorenvio', 'MelhorEnvioController@generateConfirmMelhorenvio')->name('generate-confirm-melhorenvio');
                    Route::get('cancel_melhorenvio/{request_id}', 'MelhorEnvioController@cancelMelhorenvio')->name('cancel-melhorenvio');
                    Route::post('cancel_confirm_melhorenvio', 'MelhorEnvioController@cancelConfirmMelhorenvio')->name('cancel-confirm-melhorenvio');
                    Route::post('agencies_melhorenvio', 'MelhorEnvioController@getAgencies')->name('agencies-melhorenvio');
                    Route::get('melhorenvio_update_trackings', 'OrderController@updateMelhorenvioTrackings')->name('update-melhorenvio-trackings');
                });
            });

            /** Customers */
            Route::group([
                'as' => 'user-'
            ], function () {
                Route::group([
                    'prefix' => 'users'
                ], function () {
                    Route::get('edit/{id}', 'UserController@edit')->name('edit');
                    Route::post('edit/{id}', 'UserController@update')->name('update');
                    Route::get('delete/{id}', 'UserController@destroy')->name('delete');
                    Route::get('ban/{id1}/{id2}', 'UserController@ban')->name('ban');
                    Route::get('datatables', 'UserController@datatables')->name('datatables');
                    Route::get('/', 'UserController@index')->name('index');
                });
                Route::group([
                    'prefix' => 'user'
                ], function () {
                    Route::get('{id}/show', 'UserController@show')->name('show');
                });
            });

            /** Cart Abandonment */
            Route::group([
                'as' => 'cartabandonment-'
            ], function () {
                Route::group([
                    'prefix' => 'cartabandonments'
                ], function () {
                    Route::get('sendmail/{id}', 'CartAbandonmentController@sendMail')->name('sendmail');
                    Route::get('delete/{id}', 'CartAbandonmentController@destroy')->name('delete');
                    Route::get('datatables', 'CartAbandonmentController@datatables')->name('datatables');
                    Route::get('{id}/details', 'CartAbandonmentController@details')->name('details');
                    Route::get('/', 'CartAbandonmentController@index')->name('index');
                });
                Route::group([
                    'prefix' => 'cartabandonment'
                ], function () {
                    Route::get('{id}/show', 'CartAbandonmentController@show')->name('show');
                });
            });

            /** Back in Stock */
            Route::group([
                'as' => 'backinstock-'
            ], function () {
                Route::group([
                    'prefix' => 'backinstock'
                ], function () {
                    Route::get('datatables', 'BackInStockController@datatables')->name('datatables');
                    Route::get('/', 'BackInStockController@index')->name('index');
                });
            });
        });

        /**
         * Content Routes
         */
        Route::group([
            'middleware' => 'permissions:content'
        ], function () {
            /** Faq */
            Route::group([
                'as' => 'faq-',
                'prefix' => 'faq'
            ], function () {
                Route::get('create', 'FaqController@create')->name('create');
                Route::post('create', 'FaqController@store')->name('store');
                Route::get('edit/{id}', 'FaqController@edit')->name('edit');
                Route::post('update/{id}', 'FaqController@update')->name('update');
                Route::get('delete/{id}', 'FaqController@destroy')->name('delete');
                Route::get('datatables', 'FaqController@datatables')->name('datatables');
                Route::get('/', 'FaqController@index')->name('index');
            });

            Route::group([
                'as' => 'ps-',
                'prefix' => 'page-settings'
            ], function () {
                /** Contact Page **/
                Route::get('contact', 'PageSettingController@contact')->name('contact');

                /** Banners Home */
                Route::get('best-seller', 'PageSettingController@best_seller')->name('best-seller');
                Route::get('big-save', 'PageSettingController@big_save')->name('big-save');

                Route::post('update/all', 'PageSettingController@update')->name('update');
                Route::post('unlink', 'PageSettingController@unlink')->name('unlink');
            });

            Route::group([
                'as' => 'gs-',
                'prefix' => 'general-settings'
            ], function () {
                /** Popup **/
                Route::get('popup', 'GeneralSettingController@popup')->name('popup');
                Route::post('popup/delete/', 'GeneralSettingController@removePopUpImage')->name('delete-pop');
                /** Footer **/
                Route::get('footer', 'GeneralSettingController@footer')->name('footer');

                /** Page Not Found **/
                Route::get('error-banner', 'GeneralSettingController@errorbanner')->name('error-banner');
                Route::post('error-banner/delete/', 'GeneralSettingController@removeErrorBanner')->name('delete-error-banner');

                /** Buy/Return Policy */
                Route::get('policy', 'GeneralSettingController@policy')->name('policy');

                /** Crow Store Buy/Return Policy */
                Route::get('crowpolicy', 'GeneralSettingController@crowpolicy')->name('crowpolicy');

                /** Crow Store Vendor Policy */
                Route::get('vendorpolicy', 'GeneralSettingController@vendorpolicy')->name('vendorpolicy');

                /** Privacy Policy */
                Route::get('privacypolicy', 'GeneralSettingController@privacypolicy')->name('privacypolicy');

                /** Team Members Config */
                Route::get('team-show-whatsapp/{status}', 'GeneralSettingController@teamShowWhatsapp')->name('team-show-whatsapp');
                Route::get('team-show-header/{status}', 'GeneralSettingController@teamShowHeader')->name('team-show-header');
                Route::get('team-show-footer/{status}', 'GeneralSettingController@teamShowFooter')->name('team-show-footer');

                Route::post('update/all', 'GeneralSettingController@generalupdate')->name('update');
                Route::post('update/popup', 'GeneralSettingController@updatePopUp')->name('update-popup');
            });

            /** Pages */
            Route::group([
                'as' => 'page-',
                'prefix' => 'page'
            ], function () {
                Route::get('create', 'PageController@create')->name('create');
                Route::post('create', 'PageController@store')->name('store');
                Route::get('edit/{id}', 'PageController@edit')->name('edit');
                Route::post('update/{id}', 'PageController@update')->name('update');
                Route::get('delete/{id}', 'PageController@destroy')->name('delete');
                Route::get('header/{id1}/{id2}', 'PageController@header')->name('header');
                Route::get('footer/{id1}/{id2}', 'PageController@footer')->name('footer');
                Route::get('datatables', 'PageController@datatables')->name('datatables');
                Route::get('/', 'PageController@index')->name('index');
            });

            Route::group([
                'prefix' => 'team_member'
            ], function () {
                /** Team Members */
                Route::group([
                    'as' => 'team_member-'
                ], function () {
                    Route::get('create', 'TeamMemberController@create')->name('create');
                    Route::post('create', 'TeamMemberController@store')->name('store');
                    Route::get('edit/{id}', 'TeamMemberController@edit')->name('edit');
                    Route::post('edit/{id}', 'TeamMemberController@update')->name('update');
                    Route::get('delete/{id}', 'TeamMemberController@destroy')->name('delete');
                    Route::get('datatables', 'TeamMemberController@datatables')->name('datatables');
                    Route::get('/', 'TeamMemberController@index')->name('index');
                });

                /** Team Members Category */
                Route::group([
                    'as' => 'cteam_member-'
                ], function () {
                    Route::get('category/create', 'TeamMemberCategoryController@create')->name('create');
                    Route::post('category/create', 'TeamMemberCategoryController@store')->name('store');
                    Route::get('category/edit/{id}', 'TeamMemberCategoryController@edit')->name('edit');
                    Route::post('category/edit/{id}', 'TeamMemberCategoryController@update')->name('update');
                    Route::get('category/delete/{id}', 'TeamMemberCategoryController@destroy')->name('delete');
                    Route::get('category/datatables', 'TeamMemberCategoryController@datatables')->name('datatables');
                    Route::get('category', 'TeamMemberCategoryController@index')->name('index');
                });
            });

            /** Sliders */
            Route::group([
                'as' => 'sl-',
                'prefix' => 'slider'
            ], function () {
                Route::get('create', 'SliderController@create')->name('create');
                Route::post('create', 'SliderController@store')->name('store');
                Route::get('edit/{id}', 'SliderController@edit')->name('edit');
                Route::post('edit/{id}', 'SliderController@update')->name('update');
                Route::get('status/{id1}/{id2}', 'SliderController@status')->name('status');
                Route::get('changeSliderPos/{id1}/{pos}', 'SliderController@changeSliderPos')->name('changeSliderPos');
                Route::get('delete/{id}', 'SliderController@destroy')->name('delete');
                Route::get('datatables', 'SliderController@datatables')->name('datatables');
                Route::get('/', 'SliderController@index')->name('index');
            });

            /** Services */
            Route::group([
                'as' => 'service-',
                'prefix' => 'service'
            ], function () {
                Route::get('create', 'ServiceController@create')->name('create');
                Route::post('create', 'ServiceController@store')->name('store');
                Route::get('edit/{id}', 'ServiceController@edit')->name('edit');
                Route::post('edit/{id}', 'ServiceController@update')->name('update');
                Route::get('delete/{id}', 'ServiceController@destroy')->name('delete');
                Route::get('datatables', 'ServiceController@datatables')->name('datatables');
                Route::get('/', 'ServiceController@index')->name('index');
            });

            /** Banners */
            Route::group([
                'as' => 'sb-',
            ], function () {
                Route::get('top/small/banner/create', 'BannerController@create')->name('create');
                Route::get('top/small/banner/', 'BannerController@index')->name('index');
                Route::get('bottom/small/banner/create', 'BannerController@bottomcreate')->name('create-bottom');
                Route::get('bottom/small/banner/', 'BannerController@bottom')->name('bottom');
                Route::get('large/banner/create', 'BannerController@largecreate')->name('create-large');
                Route::get('large/banner/', 'BannerController@large')->name('large');
                Route::get('large/thumbnail/create', 'BannerController@thumbnailcreate')->name('create-thumbnail');
                Route::get('large/thumbnail', 'BannerController@thumbnail')->name('thumbnail');
                Route::get('large/filteredbanner', 'BannerController@filteredBanner')->name('filteredbanner');
                Route::get('large/filteredbanner/create', 'BannerController@filteredBannerCreate')->name('create-filteredbanner');

                Route::group([
                    'prefix' => 'banner'
                ], function () {
                    Route::post('create', 'BannerController@store')->name('store');
                    Route::get('edit/{id}', 'BannerController@edit')->name('edit');
                    Route::post('edit/{id}', 'BannerController@update')->name('update');
                    Route::get('delete/{id}', 'BannerController@destroy')->name('delete');
                    Route::get('datatables/{type}', 'BannerController@datatables')->name('datatables');
                });
            });

            Route::group([
                'prefix' => 'blog'
            ], function () {
                /** Blog */
                Route::group([
                    'as' => 'blog-'
                ], function () {
                    Route::get('create', 'BlogController@create')->name('create');
                    Route::post('create', 'BlogController@store')->name('store');
                    Route::get('edit/{id}', 'BlogController@edit')->name('edit');
                    Route::post('edit/{id}', 'BlogController@update')->name('update');
                    Route::get('delete/{id}', 'BlogController@destroy')->name('delete');
                    Route::get('datatables', 'BlogController@datatables')->name('datatables');
                    Route::get('/', 'BlogController@index')->name('index');
                });

                /** Blog Category */
                Route::group([
                    'as' => 'cblog-'
                ], function () {
                    Route::get('category/create', 'BlogCategoryController@create')->name('create');
                    Route::post('category/create', 'BlogCategoryController@store')->name('store');
                    Route::get('category/edit/{id}', 'BlogCategoryController@edit')->name('edit');
                    Route::post('category/edit/{id}', 'BlogCategoryController@update')->name('update');
                    Route::get('category/delete/{id}', 'BlogCategoryController@destroy')->name('delete');
                    Route::get('category/datatables', 'BlogCategoryController@datatables')->name('datatables');
                    Route::get('category', 'BlogCategoryController@index')->name('index');
                });
            });

            /** Reviews */
            Route::group([
                'as' => 'review-',
                'prefix' => 'review'
            ], function () {
                Route::get('create', 'ReviewController@create')->name('create');
                Route::post('create', 'ReviewController@store')->name('store');
                Route::get('edit/{id}', 'ReviewController@edit')->name('edit');
                Route::post('edit/{id}', 'ReviewController@update')->name('update');
                Route::get('delete/{id}', 'ReviewController@destroy')->name('delete');
                Route::get('datatables', 'ReviewController@datatables')->name('datatables');
                Route::get('/', 'ReviewController@index')->name('index');
            });

            /** Logo Slider */
            Route::group([
                'as' => 'partner-',
                'prefix' => 'partner'
            ], function () {
                Route::get('create', 'PartnerController@create')->name('create');
                Route::post('create', 'PartnerController@store')->name('store');
                Route::get('edit/{id}', 'PartnerController@edit')->name('edit');
                Route::post('edit/{id}', 'PartnerController@update')->name('update');
                Route::get('delete/{id}', 'PartnerController@destroy')->name('delete');
                Route::get('datatables', 'PartnerController@datatables')->name('datatables');
                Route::get('/', 'PartnerController@index')->name('index');
            });
        });

        /**
         * Config Routes
         */
        Route::group([
            'middleware' => 'permissions:config'
        ], function () {
            Route::group([
                'as' => 'gs-',
                'prefix' => 'general-settings'
            ], function () {
                /** Logo **/
                Route::get('logo', 'GeneralSettingController@logo')->name('logo');

                /** Favicon */
                Route::get('favicon', 'GeneralSettingController@fav')->name('fav');

                /** Loader */
                Route::get('admin/loader/{status}', 'GeneralSettingController@isadminloader')->name('is-admin-loader');
                Route::get('loader/{status}', 'GeneralSettingController@isloader')->name('isloader');
                Route::get('loader', 'GeneralSettingController@load')->name('load');

                /** Colors */
                Route::get('contents', 'GeneralSettingController@contents')->name('contents');

                /** Store */
                Route::get('storeconf', 'GeneralSettingController@storeconf')->name('storeconf');

                /** General Settings */
                Route::get('language/{status}', 'GeneralSettingController@language')->name('islanguage');
                Route::get('currency/{status}', 'GeneralSettingController@currency')->name('iscurrency');
                Route::get('currencyvalues/{status}', 'GeneralSettingController@currencyvalues')->name('showcurrencyvalues');
                Route::get('switch-currency-highlight/{status}', 'GeneralSettingController@switchCurrencyHighlight')->name('switchCurrenciHighlight');
                Route::get('home/{status}', 'GeneralSettingController@ishome')->name('ishome');
                Route::get('faq/{status}', 'GeneralSettingController@isfaq')->name('isfaq');
                Route::get('contact/{status}', 'GeneralSettingController@iscontact')->name('iscontact');
                Route::get('blog/{status}', 'GeneralSettingController@isblog')->name('isblog');
                Route::get('popup/{status}', 'GeneralSettingController@ispopup')->name('ispopup');
                Route::get('popup/newsletter/{status}', 'GeneralSettingController@isnewsletterpopup')->name('isnewsletterpopup');
                Route::get('whatsapp/{status}', 'GeneralSettingController@iswhatsapp')->name('iswhatsapp');
                Route::get('capcha/{status}', 'GeneralSettingController@iscapcha')->name('iscapcha');
                Route::get('email-verify/{status}', 'GeneralSettingController@isemailverify')->name('is-email-verify');
                Route::get('zip-validation/{status}', 'GeneralSettingController@iszipvalidation')->name('is-zip-validation');
                Route::get('attr-cards/{status}', 'GeneralSettingController@isattrcards')->name('isattrcards');
                /* Show brands on header menu */
                Route::get('brands-show-header/{status}', 'GeneralSettingController@brands')->name('is-brands');

                /** Product Settings */
                Route::get('productconf', 'GeneralSettingController@productconf')->name('productconf');
                Route::get('productprices/{status}', 'GeneralSettingController@productprices')->name('productprices');
                Route::get('iscartandbuyavailable/{status}', 'GeneralSettingController@iscartandbuyavailable')->name('iscartandbuyavailable');
                Route::get('showproductswithoutstock/{status}', 'GeneralSettingController@showproductswithoutstock')->name('showproductswithoutstock');
                Route::get('stock/{status}', 'GeneralSettingController@stock')->name('stock');
                Route::get('referencecode/{status}', 'GeneralSettingController@referencecode')->name('referencecode');
                Route::get('comment/{status}', 'GeneralSettingController@comment')->name('iscomment');
                Route::get('rating/{status}', 'GeneralSettingController@rating')->name('israting');
                Route::get('report/{status}', 'GeneralSettingController@isreport')->name('isreport');
                Route::get('attributeclickable/{status}', 'GeneralSettingController@attributeclickable')->name('attributeclickable');
                Route::get('isinvoicephoto/{status}', 'GeneralSettingController@isinvoicephoto')->name('isinvoicephoto');
                Route::get('showproductswithoutstockbaw/{status}', 'GeneralSettingController@showproductswithoutstockbaw')->name('showproductswithoutstockbaw');
                Route::get('isbackinstock/{status}', 'GeneralSettingController@isbackinstock')->name('isbackinstock');

                /** Cart */
                Route::get('cartconf', 'GeneralSettingController@cartconf')->name('cartconf');
                Route::get('iscart/{status}', 'GeneralSettingController@iscart')->name('iscart');
                Route::get('isStandardCheckout/{status}', 'GeneralSettingController@isStandardCheckout')->name('isStandardCheckout');
                Route::get('isSimplifiedCheckout/{status}', 'GeneralSettingController@isSimplifiedCheckout')->name('isSimplifiedCheckout');
                Route::get('guest/{status}', 'GeneralSettingController@guest')->name('guest');
                Route::get('completeProfile/{status}', 'GeneralSettingController@completeProfile')->name('completeProfile');
                Route::get('iscartabandonment/{status}', 'GeneralSettingController@iscartabandonment')->name('iscartabandonment');

                /** Shipping */
                Route::get('shippingconf', 'GeneralSettingController@shippingconf')->name('shippingconf');
                Route::get('multiple/shipping/{status}', 'GeneralSettingController@mship')->name('mship');
                Route::get('iscorreios/{status}', 'GeneralSettingController@iscorreios')->name('iscorreios');
                Route::get('isaex/{status}', 'GeneralSettingController@isaex')->name('isaex');
                Route::get('ismelhorenvio/{status}', 'GeneralSettingController@ismelhorenvio')->name('ismelhorenvio');
                Route::get('multiple/packaging/{status}', 'GeneralSettingController@mpackage')->name('mpackage');

                Route::get('isfedex/{status}', 'GeneralSettingController@isfedex')->name('isfedex');

                /** Payment Settings */
                Route::get('cod/{status}', 'GeneralSettingController@cod')->name('cod');
                Route::get('bankdeposit/{status}', 'GeneralSettingController@bankdeposit')->name('bank-deposit');
                Route::get('bancard/{status}', 'GeneralSettingController@bancard')->name('bancard');
                Route::get('mercadopago/{status}', 'GeneralSettingController@mercadopago')->name('mercadopago');
                Route::get('pagarme/{status}', 'GeneralSettingController@pagarme')->name('pagarme');
                Route::get('pagseguro/{status}', 'GeneralSettingController@pagseguro')->name('pagseguro');
                Route::get('pagseguro_sandbox/{sandbox}', 'GeneralSettingController@pagseguro_sandbox')->name('pagseguro-sandbox');
                Route::get('cielo/{status}', 'GeneralSettingController@cielo')->name('cielo');
                Route::get('pagopar/{status}', 'GeneralSettingController@pagopar')->name('pagopar');
                Route::get('rede/{status}', 'GeneralSettingController@rede')->name('rede');
                Route::get('rede_sandbox/{sandbox}', 'GeneralSettingController@rede_sandbox')->name('rede-sandbox');
                Route::get('paghiper/pix/{status}', 'GeneralSettingController@paghiperPix')->name('paghiper-pix');
                Route::get('paghiper-is-discount/pix/{status}', 'GeneralSettingController@paghiperPixIsDiscount')->name('paghiper-pix-is-discount');
                Route::get('paghiper/{status}', 'GeneralSettingController@paghiper')->name('paghiper');
                Route::get('paghiper-is-discount/{status}', 'GeneralSettingController@paghiperIsDiscount')->name('paghiper-is-discount');
                Route::get('paypal/{status}', 'GeneralSettingController@paypal')->name('paypal');
                Route::get('paypal_sandbox/{sandbox}', 'GeneralSettingController@paypal_sandbox')->name('paypal-sandbox');
                Route::get('pay42_pix/{status}', 'GeneralSettingController@pay42_pix')->name('pay42-pix');
                Route::get('pay42_card/{status}', 'GeneralSettingController@pay42_card')->name('pay42-card');
                Route::get('pay42_billet/{status}', 'GeneralSettingController@pay42_billet')->name('pay42-billet');
                Route::get('pay42_sandbox/{sandbox}', 'GeneralSettingController@pay42_sandbox')->name('pay42-sandbox');
                Route::get('pay42Currency/{currency}', 'GeneralSettingController@pay42Currency')->name('pay42-currency');
                Route::post('update/payment', 'GeneralSettingController@generalupdatepayment')->name('update-payment');

                /** Correios Settings */
                Route::get('correiosconf', 'GeneralSettingController@correiosconf')->name('correiosconf');

                /** AEX Settings */
                Route::get('aexconf', 'GeneralSettingController@aexconf')->name('aexconf');
                Route::get('aex_production/{production}', 'GeneralSettingController@aex_production')->name('aex-production');
                Route::get('aex_insurance/{insurance}', 'GeneralSettingController@aex_insurance')->name('aex-insurance');
                Route::get('update_aex_cities', 'AexController@updateaexcities')->name('update-aex-cities');
                Route::get('load_aex_cities', 'AexController@loadaexcities')->name('load-aex-cities');

                /** Melhor Envio Settings */
                Route::get('melhorenvioconf', 'GeneralSettingController@melhorenvioconf')->name('melhorenvioconf');
                Route::post('update/melhorenvio', 'GeneralSettingController@generalupdateMelhorenvio')->name('update-melhorenvio');
                Route::get('melhorenvio_production/{production}', 'GeneralSettingController@melhorenvio_production')->name('melhorenvio-production');
                Route::get('melhorenvio_insurance/{insurance}', 'GeneralSettingController@melhorenvio_insurance')->name('melhorenvio-insurance');
                Route::get('melhorenvio_receipt/{receipt}', 'GeneralSettingController@melhorenvio_receipt')->name('melhorenvio-receipt');
                Route::get('melhorenvio_ownhand/{ownhand}', 'GeneralSettingController@melhorenvio_ownhand')->name('melhorenvio-ownhand');
                Route::get('melhorenvio_collect/{collect}', 'GeneralSettingController@melhorenvio_collect')->name('melhorenvio-collect');
                Route::get('update_melhorenvio_companies', 'MelhorEnvioController@updatecompanies')->name('update-melhorenvio-companies');
                Route::get('load_melhorenvio_companies', 'GeneralSettingController@loadmelhorenviocompanies')->name('load-melhorenvio-companies');

                /** Fedex Settings */
                Route::get('fedexconf', 'GeneralSettingController@fedexconf')->name('fedexconf');
                Route::post('update/fedex', 'GeneralSettingController@generalUpdateFedex')->name('update-fedex');
                Route::get('fedex_production/{production}', 'GeneralSettingController@fedex_production')->name('fedex-production');

                /** Email Settings */
                Route::get('issmtp/{status}', 'GeneralSettingController@issmtp')->name('issmtp');
            });

            /** Home Page */
            Route::group([
                'as' => 'ps-',
                'prefix' => 'page-settings'
            ], function () {
                Route::post('update/home', 'PageSettingController@homeupdate')->name('homeupdate');
                Route::get('customize', 'PageSettingController@customize')->name('customize');
            });

            /** Currencies */
            Route::group([
                'as' => 'currency-',
                'prefix' => 'currency'
            ], function () {
                Route::get('create', 'CurrencyController@create')->name('create');
                Route::post('create', 'CurrencyController@store')->name('store');
                Route::get('edit/{id}', 'CurrencyController@edit')->name('edit');
                Route::post('update/{id}', 'CurrencyController@update')->name('update');
                Route::get('delete/{id}', 'CurrencyController@destroy')->name('delete');
                Route::get('status/{id1}/{id2}', 'CurrencyController@status')->name('status');
                Route::get('datatables', 'CurrencyController@datatables')->name('datatables');
                Route::get('datatables/base', 'CurrencyController@datatablesBase')->name('datatables-base');
                Route::get('/', 'CurrencyController@index')->name('index');
            });

            /** Payment Methods */
            Route::group([
                'as' => 'gs-payments-',
                'prefix' => 'payment-informations'
            ], function () {
                /** Gateways */
                Route::group([
                    'prefix' => 'gateway'
                ], function () {
                    Route::get('bancard', 'GeneralSettingController@paymentsinfoBancard')->name('bancard');
                    Route::get('mercadopago', 'GeneralSettingController@paymentsinfoMercadopago')->name('mercadopago');
                    Route::get('pagarme', 'GeneralSettingController@paymentsinfoPagarme')->name('pagarme');
                    Route::get('pagseguro', 'GeneralSettingController@paymentsinfoPagseguro')->name('pagseguro');
                    Route::get('cielo', 'GeneralSettingController@paymentsinfoCielo')->name('cielo');
                    Route::get('pagopar', 'GeneralSettingController@paymentsinfoPagopar')->name('pagopar');
                    Route::get('rede', 'GeneralSettingController@paymentsinfoRede')->name('rede');
                    Route::get('paghiper', 'GeneralSettingController@paymentsinfoPaghiper')->name('paghiper');
                    Route::get('paypal', 'GeneralSettingController@paymentsinfoPaypal')->name('paypal');
                    Route::get('pay42', 'GeneralSettingController@paymentsinfoPay42')->name('pay42');
                    Route::get('/', 'GeneralSettingController@paymentsinfoGateway')->name('gateway');
                });

                Route::get('deposit', 'GeneralSettingController@paymentsinfoDeposit')->name('deposit');
                Route::get('/', 'GeneralSettingController@paymentsinfo')->name('index');
            });

            /** Bank Account */
            Route::group([
                'as' => 'bank_account-',
                'prefix' => 'bank_account'
            ], function () {
                Route::get('create', 'BankAccountController@create')->name('create');
                Route::post('create', 'BankAccountController@store')->name('store');
                Route::get('edit/{id}', 'BankAccountController@edit')->name('edit');
                Route::post('edit/{id}', 'BankAccountController@update')->name('update');
                Route::get('delete/{id}', 'BankAccountController@destroy')->name('delete');
                Route::get('status/{id1}/{id2}', 'BankAccountController@status')->name('status');
                Route::get('datatables', 'BankAccountController@datatables')->name('datatables');
                Route::get('/', 'BankAccountController@index')->name('index');
            });

            /** Shipping */
            Route::group([
                'as' => 'shipping-',
                'prefix' => 'shipping'
            ], function () {
                Route::get('create', 'ShippingController@create')->name('create');
                Route::post('create', 'ShippingController@store')->name('store');
                Route::get('edit/{id}', 'ShippingController@edit')->name('edit');
                Route::post('edit/{id}', 'ShippingController@update')->name('update');
                Route::get('status/{id1}/{id2}', 'ShippingController@status')->name('status');
                Route::get('delete/{id}', 'ShippingController@destroy')->name('delete');
                Route::get('datatables', 'ShippingController@datatables')->name('datatables');
                Route::get('/', 'ShippingController@index')->name('index');
            });

            /** Shipping Prices */
            Route::group([
                'as' => 'shipping_prices-',
                'prefix' => 'shipping_prices'
            ], function () {
                Route::get('create', 'Shipping_pricesController@create')->name('create');
                Route::post('create', 'Shipping_pricesController@store')->name('store');
                Route::get('edit/{id}', 'Shipping_pricesController@edit')->name('edit');
                Route::post('edit/{id}', 'Shipping_pricesController@update')->name('update');
                Route::get('delete/{id}', 'Shipping_pricesController@destroy')->name('delete');
                Route::post('getStates', 'Shipping_pricesController@getStates')->name('getStates');
                Route::get('datatables', 'Shipping_pricesController@datatables')->name('datatables');
                Route::get('/', 'Shipping_pricesController@index')->name('index');
            });

            /** Package */
            Route::group([
                'as' => 'package-',
                'prefix' => 'package'
            ], function () {
                Route::get('create', 'PackageController@create')->name('create');
                Route::post('create', 'PackageController@store')->name('store');
                Route::get('edit/{id}', 'PackageController@edit')->name('edit');
                Route::post('edit/{id}', 'PackageController@update')->name('update');
                Route::get('delete/{id}', 'PackageController@destroy')->name('delete');
                Route::get('datatables', 'PackageController@datatables')->name('datatables');
                Route::get('/', 'PackageController@index')->name('index');
            });

            /** Pickup Location */
            Route::group([
                'as' => 'pick-',
                'prefix' => 'pickup'
            ], function () {
                Route::get('create', 'PickupController@create')->name('create');
                Route::post('create', 'PickupController@store')->name('store');
                Route::get('edit/{id}', 'PickupController@edit')->name('edit');
                Route::post('edit/{id}', 'PickupController@update')->name('update');
                Route::get('delete/{id}', 'PickupController@destroy')->name('delete');
                Route::get('datatables', 'PickupController@datatables')->name('datatables');
                Route::get('/', 'PickupController@index')->name('index');
            });

            Route::group([
                'as' => 'mail-'
            ], function () {
                /** Email Templates */
                Route::group([
                    'prefix' => 'email-templates'
                ], function () {
                    Route::get('datatables', 'EmailController@datatables')->name('datatables');
                    Route::get('{id}', 'EmailController@edit')->name('edit');
                    Route::post('{id}', 'EmailController@update')->name('update');
                    Route::get('/', 'EmailController@index')->name('index');
                });

                /** Email Config */
                Route::group([
                    'prefix' => 'email-config'
                ], function () {
                    Route::get('/', 'EmailController@config')->name('config');
                });
            });

            /** Languages */
            Route::group([
                'as' => 'lang-',
                'prefix' => 'languages'
            ], function () {
                Route::get('create', 'LanguageController@create')->name('create');
                Route::post('create', 'LanguageController@store')->name('store');
                Route::get('edit/{id}', 'LanguageController@edit')->name('edit');
                Route::post('edit/{id}', 'LanguageController@update')->name('update');
                Route::get('delete/{id}', 'LanguageController@destroy')->name('delete');
                Route::get('status/{id1}/{id2}', 'LanguageController@status')->name('st');
                Route::get('datatables', 'LanguageController@datatables')->name('datatables');
                Route::get('/', 'LanguageController@index')->name('index');
            });
        });

        /**
         * Marketing Routes
         */
        Route::group([
            'middleware' => 'permissions:marketing'
        ], function () {
            /** Coupons */
            Route::group([
                'as' => 'coupon-',
                'prefix' => 'coupon'
            ], function () {
                Route::get('create', 'CouponController@create')->name('create');
                Route::post('create', 'CouponController@store')->name('store');
                Route::get('edit/{id}', 'CouponController@edit')->name('edit');
                Route::post('edit/{id}', 'CouponController@update')->name('update');
                Route::get('delete/{id}', 'CouponController@destroy')->name('delete');
                Route::get('status/{id1}/{id2}', 'CouponController@status')->name('status');
                Route::get('datatables', 'CouponController@datatables')->name('datatables');
                Route::get('/', 'CouponController@index')->name('index');
            });

            /** Socials */
            Route::group([
                'as' => 'social-',
                'prefix' => 'social'
            ], function () {
                Route::post('update/all', 'SocialSettingController@socialupdateall')->name('update-all');
                Route::get('/', 'SocialSettingController@index')->name('index');
            });

            /** Popular Products **/
            Route::get('products/popular/{id}', 'SeoToolController@popular')->name('prod-popular');

            /** Better Rated Products **/
            Route::get('products/rated', 'SeoToolController@rated')->name('prod-rated');

            /** Meta Keywords **/
            Route::get('seotools/keywords', 'SeoToolController@keywords')->name('seotool-keywords');

            Route::post('seotools/analytics/update', 'SeoToolController@analyticsupdate')->name('seotool-analytics-update');

            Route::post('seotools/tagmanager/update', 'SeoToolController@tagmanagerupdate')->name('seotool-tagmanager-update');

            /** Newsletter Subscribers */
            Route::group([
                'as' => 'subs-',
                'prefix' => 'subscribers'
            ], function () {
                Route::get('datatables', 'SubscriberController@datatables')->name('datatables');
                Route::get('download', 'SubscriberController@download')->name('download');
                Route::get('/', 'SubscriberController@index')->name('index');
            });
        });

        /**
         * Marketplace Routes
         */
        Route::group([
            'middleware' => 'permissions:marketplace'
        ], function () {
            /** Affiliate Products */
            Route::group([
                'as' => 'import-',
                'prefix' => 'products'
            ], function () {
                Route::get('import/create', 'ImportController@createImport')->name('create');
                Route::post('import/store', 'ImportController@store')->name('store');
                Route::get('import/edit/{id}', 'ImportController@edit')->name('edit');
                Route::post('import/update/{id}', 'ImportController@update')->name('update');
                Route::get('import/datatables', 'ImportController@datatables')->name('datatables');
                Route::get('import/index', 'ImportController@index')->name('index');
            });

            Route::get('affiliate/products/delete/{id}', 'ProductController@destroy')->name('affiliate-prod-delete');

            /** Affiliate Customer Withdraws */
            Route::group([
                'as' => 'withdraw-',
                'prefix' => 'users'
            ], function () {
                Route::get('withdraw/{id}/show', 'UserController@withdrawdetails')->name('show');
                Route::get('withdraws/accept/{id}', 'UserController@accept')->name('accept');
                Route::get('withdraws/reject/{id}', 'UserController@reject')->name('reject');
                Route::get('withdraws/datatables', 'UserController@withdrawdatatables')->name('datatables');
                Route::get('withdraws', 'UserController@withdraws')->name('index');
            });

            /** Subscriptions */
            Route::group([
                'as' => 'subscription-',
                'prefix' => 'subscription'
            ], function () {
                Route::get('create', 'SubscriptionController@create')->name('create');
                Route::post('create', 'SubscriptionController@store')->name('store');
                Route::get('edit/{id}', 'SubscriptionController@edit')->name('edit');
                Route::post('edit/{id}', 'SubscriptionController@update')->name('update');
                Route::get('delete/{id}', 'SubscriptionController@destroy')->name('delete');
                Route::get('datatables', 'SubscriptionController@datatables')->name('datatables');
                Route::get('/', 'SubscriptionController@index')->name('index');
            });

            /** Vendors */
            Route::group([
                'as' => 'vendor-',
                'prefix' => 'vendors'
            ], function () {
                Route::get('withdraws/reject/{id}', 'VendorController@reject')->name('withdraw-reject');
                Route::get('withdraws/accept/{id}', 'VendorController@accept')->name('withdraw-accept');
                Route::get('withdraw/{id}/show', 'VendorController@withdrawdetails')->name('withdraw-show');
                Route::get('withdraws/datatables', 'VendorController@withdrawdatatables')->name('withdraw-datatables');
                Route::get('verify/{id}', 'VendorController@verify')->name('verify');
                Route::post('verify/{id}', 'VendorController@verifySubmit')->name('verify-submit');
                Route::get('secret/login/{id}', 'VendorController@secret')->name('secret');
                Route::get('status/{id1}/{id2}', 'VendorController@status')->name('st');
                Route::get('withdraws', 'VendorController@withdraws')->name('withdraw-index');
                Route::get('subs/datatables', 'VendorController@subsdatatables')->name('subs-datatables');
                Route::get('sub/{id}', 'VendorController@sub')->name('sub');
                Route::get('subs', 'VendorController@subs')->name('subs');
                Route::get('color', 'VendorController@color')->name('color');
                Route::get('edit/{id}', 'VendorController@edit')->name('edit');
                Route::post('edit/{id}', 'VendorController@update')->name('update');
                Route::get('delete/{id}', 'VendorController@destroy')->name('delete');
                Route::get('{id}/show', 'VendorController@show')->name('show');
                Route::get('datatables', 'VendorController@datatables')->name('datatables');
                Route::get('/', 'VendorController@index')->name('index');
            });

            /** Verifications */
            Route::group([
                'as' => 'vr-',
                'prefix' => 'verificatons'
            ], function () {
                Route::get('show', 'VerificationController@show')->name('show');
                Route::get('status/{id1}/{id2}', 'VerificationController@status')->name('st');
                Route::get('delete/{id}', 'VerificationController@destroy')->name('delete');
                Route::get('datatables/{status}', 'VerificationController@datatables')->name('datatables');
                Route::get('/', 'VerificationController@index')->name('index');
            });

            Route::group([
                'as' => 'gs-',
                'prefix' => 'general-settings'
            ], function () {
                /** Affiliate Info **/
                Route::get('affilate/{status}', 'GeneralSettingController@isaffilate')->name('isaffilate');
                Route::get('affilate', 'GeneralSettingController@affilate')->name('affilate');

                /** Vendor Registration **/
                Route::get('vendor-registration/{status}', 'GeneralSettingController@regvendor')->name('regvendor');

                /** Marketplace Config **/
                Route::get('marketplaceconf', 'GeneralSettingController@marketplaceconf')->name('marketplaceconf');
            });
        });

        /**
         * System Routes
         */
        Route::group([
            'middleware' => 'permissions:system'
        ], function () {
            /** Users */
            Route::group([
                'as' => 'staff-',
                'prefix' => 'staff'
            ], function () {
                Route::get('create', 'StaffController@create')->name('create');
                Route::post('create', 'StaffController@store')->name('store');
                Route::get('show/{id}', 'StaffController@show')->name('show');
                Route::get('edit/{id}', 'StaffController@edit')->name('edit');
                Route::post('update/{id}', 'StaffController@update')->name('update');
                Route::get('delete/{id}', 'StaffController@destroy')->name('delete');
                Route::get('datatables', 'StaffController@datatables')->name('datatables');
                Route::get('/', 'StaffController@index')->name('index');
            });

            /** Roles */
            Route::group([
                'as' => 'role-',
                'prefix' => 'role'
            ], function () {
                Route::get('create', 'RoleController@create')->name('create');
                Route::post('create', 'RoleController@store')->name('store');
                Route::get('edit/{id}', 'RoleController@edit')->name('edit');
                Route::post('edit/{id}', 'RoleController@update')->name('update');
                Route::get('delete/{id}', 'RoleController@destroy')->name('delete');
                Route::get('datatables', 'RoleController@datatables')->name('datatables');
                Route::get('/', 'RoleController@index')->name('index');
            });

            /** User Default Image **/
            Route::get('user/default/image', 'UserController@image')->name('user-image');

            /** Admin Languages */
            Route::group([
                'as' => 'tlang-',
                'prefix' => 'adminlanguages'
            ], function () {
                Route::get('create', 'AdminLanguageController@create')->name('create');
                Route::post('create', 'AdminLanguageController@store')->name('store');
                Route::get('edit/{id}', 'AdminLanguageController@edit')->name('edit');
                Route::post('edit/{id}', 'AdminLanguageController@update')->name('update');
                Route::get('delete/{id}', 'AdminLanguageController@destroy')->name('delete');
                Route::get('status/{id1}/{id2}', 'AdminLanguageController@status')->name('st');
                Route::get('datatables', 'AdminLanguageController@datatables')->name('datatables');
                Route::get('/', 'AdminLanguageController@index')->name('index');
            });

            Route::group([
                'as' => 'gs-',
                'prefix' => 'general-settings'
            ], function () {
                /** Compras Paraguai Settings */
                Route::get('comprasparaguai/{status}', 'GeneralSettingController@iscomprasparaguai')->name('iscomprasparaguai');
                Route::get('storecomprasparaguai/{id}', 'GeneralSettingController@storecomprasparaguai')->name('storecomprasparaguai');

                /** Loja Update Settings */
                Route::get('lojaupdate/{status}', 'GeneralSettingController@islojaupdate')->name('islojaupdate');
                Route::get('storelojaupdate/{id}', 'GeneralSettingController@storelojaupdate')->name('storelojaupdate');
            });
        });

        /**
         * Support Routes
         */
        Route::group([
            'middleware' => 'permissions:support'
        ], function () {
            /** Ratings */
            Route::group([
                'as' => 'rating-',
                'prefix' => 'ratings'
            ], function () {
                Route::get('show/{id}', 'RatingController@show')->name('show');
                Route::get('delete/{id}', 'RatingController@destroy')->name('delete');
                Route::get('datatables', 'RatingController@datatables')->name('datatables');
                Route::get('/', 'RatingController@index')->name('index');
            });

            /** Comments */
            Route::group([
                'as' => 'comment-',
                'prefix' => 'comments'
            ], function () {
                Route::get('show/{id}', 'CommentController@show')->name('show');
                Route::get('delete/{id}', 'CommentController@destroy')->name('delete');
                Route::get('datatables', 'CommentController@datatables')->name('datatables');
                Route::get('/', 'CommentController@index')->name('index');
            });

            /** Reports */
            Route::group([
                'as' => 'report-',
                'prefix' => 'reports'
            ], function () {
                Route::get('show/{id}', 'ReportController@show')->name('show');
                Route::get('delete/{id}', 'ReportController@destroy')->name('delete');
                Route::get('datatables', 'ReportController@datatables')->name('datatables');
                Route::get('/', 'ReportController@index')->name('index');
            });

            /** Tickets */
            Route::get('message/load/{id}', 'MessageController@messageshow')->name('message-load');
            Route::post('message/post', 'MessageController@postmessage')->name('message-store');
            Route::get('message/{id}', 'MessageController@message')->name('message-show');
            Route::get('message/{id}/delete', 'MessageController@messagedelete')->name('message-delete');
            Route::get('messages/datatables/{type}', 'MessageController@datatables')->name('message-datatables');
            Route::get('tickets', 'MessageController@index')->name('message-index');
            Route::post('user/send/message', 'MessageController@usercontact')->name('send-message');

            /** Disputes **/
            Route::get('disputes', 'MessageController@disputes')->name('message-dispute');

            /** Group Email **/
            Route::get('groupemail', 'EmailController@groupemail')->name('group-show');
            Route::post('groupemailpost', 'EmailController@groupemailpost')->name('group-submit');
        });

        /**
         * Super Routes
         */
        Route::group([
            'middleware' => 'super'
        ], function () {
            Route::group([
                'as' => 'gs-',
                'prefix' => 'general-settings'
            ], function () {
                /** Integrations **/
                Route::group([
                    'as' => 'integrations-',
                    'prefix' => 'integrations'
                ], function () {
                    /* Meli Routes */
                    Route::group([
                        'as' => 'mercadolivre-',
                        'prefix' => 'mercadolivre',
                        'middleware' => 'is.meli'
                    ], function () {
                        Route::post('update', 'MercadoLivreController@update')->name('update');
                        Route::get('/', 'MercadoLivreController@index')->name('index');
                    });
                    Route::get('jivochat', 'GeneralSettingController@integrationsJivochat')->name('jivochat');
                    Route::get('disqus', 'GeneralSettingController@integrationsDisqus')->name('disqus');
                    Route::get('cronjob', 'GeneralSettingController@integrationsCronjob')->name('cronjob');
                    Route::get('ftp', 'GeneralSettingController@integrationsFtp')->name('ftp');
                    Route::get('xml', 'GeneralSettingController@integrationsXml')->name('xml');
                    Route::post('xml', 'SocialSettingController@socialupdate')->name('update');
                });

                Route::get('integrations', 'GeneralSettingController@integrations')->name('integrations');

                /** Talkto Settings */
                Route::get('talkto/{status}', 'GeneralSettingController@talkto')->name('talkto');

                /** Jivochat Settings */
                Route::get('jivochat/{status}', 'GeneralSettingController@jivochat')->name('jivochat');

                /** Disqus Settings */
                Route::get('disqus/{status}', 'GeneralSettingController@isdisqus')->name('isdisqus');

                /** Maintenance */
                Route::get('maintenance', 'GeneralSettingController@maintain')->name('maintenance');
                Route::get('maintain/{status}', 'GeneralSettingController@ismaintain')->name('maintain');
                Route::get('darkmode/{status}', 'GeneralSettingController@isdarkmode')->name('darkmode');

                /** maintenance Page  */
                Route::post('maintenance/delete/', 'GeneralSettingController@removemaintenanceBanner')->name('delete-maintenance');
            });

            /** Socials */
            Route::group([
                'as' => 'social-',
                'prefix' => 'social'
            ], function () {
                Route::get('facebook/{status}', 'SocialSettingController@facebookup')->name('facebookup');
                Route::get('facebook', 'SocialSettingController@facebook')->name('facebook');
                Route::get('google/{status}', 'SocialSettingController@googleup')->name('googleup');
                Route::get('google', 'SocialSettingController@google')->name('google');
                Route::post('update', 'SocialSettingController@socialupdate')->name('update');
            });

            /** Analytics */
            Route::get('seotools/analytics', 'SeoToolController@analytics')->name('seotool-analytics');

            /** Tag Manager */
            Route::get('seotools/tagmanager', 'SeoToolController@tagmanager')->name('seotool-tagmanager');

            /** Facebook Pixel */
            Route::get('seotools/fbpixel', 'SeoToolController@fbpixel')->name('seotool-fbpixel');
            Route::post('seotools/fbpixel/update', 'SeoToolController@fbpixelupdate')->name('seotool-fbpixel-update');

            /** Activity Log */
            Route::group([
                'as' => 'activitylog-',
                'prefix' => 'activitylog'
            ], function () {
                Route::get('properties/{id}', 'ActivitylogController@properties')->name('properties');
                Route::get('datatables', 'ActivitylogController@datatables')->name('datatables');
                Route::get('/', 'ActivitylogController@index')->name('index');
            });

            Route::get('cache/clear', function () {
                Artisan::call('cache:clear');
                Artisan::call('config:clear');
                Artisan::call('route:clear');
                Artisan::call('view:clear');
                return redirect()->route('admin.dashboard')->with('cache', __('System Cache Has Been Removed.'));
            })->name('cache-clear');
        });

        /**
         * Global Admin Routes
         */

        /** Gallery */
        Route::group([
            'as' => 'gallery-',
            'prefix' => 'gallery'
        ], function () {
            Route::post('store', 'GalleryController@store')->name('store');
            Route::get('delete', 'GalleryController@destroy')->name('delete');
            Route::get('show', 'GalleryController@show')->name('show');
        });

        /** Category Gallery */
        /** Gallery */
        Route::group([
            'as' => 'categorygallery-',
            'prefix' => 'categorygallery'
        ], function () {
            Route::post('store', 'CategoryGalleryController@store')->name('store');
            Route::get('delete', 'CategoryGalleryController@destroy')->name('delete');
            Route::get('show', 'CategoryGalleryController@show')->name('show');
            Route::get('{catid}/open', 'CategoryGalleryController@open')->name('open');
        });

        /** Custom prod */
        Route::group([
            'as' => 'customprod-',
            'prefix' => 'customprod'
        ], function () {
            Route::post('store', 'CustomProductController@logoUpload')->name('store');
            Route::get('download/{file}', 'CustomProductController@downloadLogo')->name('download');
        });
    });
});

/**
 * Front Section
 */
Route::get('download-list-pdf', 'Front\FrontendController@downloadListPDF')->name('download-list-pdf');

Route::group(['middleware' => 'maintenance'], function () {
    Route::group([
        'namespace' => 'Front'
    ], function () {
        //  CRONJOB
        Route::get('/vendor/subscription/check', 'FrontendController@subcheck');

        /** Bancard */
        Route::group([
            'as' => 'bancard.',
        ], function () {
            Route::post('bancard-submit', 'App\Http\Controllers\Front\BancardController@store')->name('submit');
            Route::get('bancard-callback', 'BancardController@bancardCallback')->name('notify');
            Route::post('bancard-callback', 'BancardController@bancardFinish')->name('finish');
            Route::post('bancard-rollback/{shop_process_id}', 'BancardController@bancardRollback')->name('rollback');
            Route::post('/bancard-close-modal', 'BancardController@bancardCloseModal')->name('bancard.close.modal');
        });

        /** Mercadopago */
        Route::group([
            'as' => 'mercadopago.',
        ], function () {
            Route::post('mercadopago-submit', 'MercadopagoController@store')->name('submit');
            Route::get('mercadopago-callback', 'MercadopagoController@mercadopagoCallback')->name('notify');
        });

        /** Cielo */
        Route::group([
            'as' => 'cielo.',
        ], function () {
            Route::post('cielo-submit', 'CieloController@store')->name('submit');
            Route::post('cielo-callback', 'CieloController@cieloCallback')->name('notify');
        });

        /** Pagarme */
        Route::group([
            'as' => 'pagarme.',
        ], function () {
            Route::post('pagarme-submit', 'PagarmeController@store')->name('submit');
            Route::post('pagarme-callback', 'PagarmeController@pagarmeCallback')->name('notify');
        });

        /** Pagseguro */
        Route::group([
            'as' => 'pagseguro.',
        ], function () {
            Route::post('pagseguro-submit', 'PagseguroController@store')->name('submit');
            Route::post('pagseguro-callback', 'PagseguroController@pagseguroCallback')->name('notify');
        });

        /** Rede */
        Route::group([
            'as' => 'rede.',
        ], function () {
            Route::post('rede-submit', 'RedeController@store')->name('submit');
            Route::post('rede-callback', 'RedeController@redeCallback')->name('notify');
            Route::get('rede-call-form/{pedido}', 'RedeController@callForm', function ($pedido) {
                return $pedido;
            })->name('callForm');
        });

        /** PagHiper */
        Route::group([
            'as' => 'paghiper.',
        ], function () {
            Route::post('paghiper-pix-submit', 'PaghiperPixController@store')->name('pix-submit');
            Route::post('paghiper-submit', 'PaghiperController@store')->name('submit');
        });

        /** Paypal */
        Route::group([
            'as' => 'paypal.',
        ], function () {
            Route::post('paypal-submit', 'PayPalController@store')->name('submit');
            Route::get('paypal-callback', 'PayPalController@paypalCallback')->name('notify');
        });
        /** Pagopar */
        Route::group([
            "as" => "pagopar.",
            "prefix" => "pagopar"
        ], function () {
            Route::post("submit", "PagoparNovoController@store")->name("submit");
            Route::post("callback", "PagoparNovoController@callback")->name("callback");
            Route::post("check-order-status/{hash}", "PagoparNovoController@checkOrderStatus")->name("check-order-status");
        });

        /** Pay42 */
        Route::group([
            'as' => 'pay42.',
        ], function () {
            /** Pay42 Pix*/
            Route::post('pay42-pix-submit', 'Pay42PixController@store')->name('pix-submit');
            /** Pay42 billet*/
            Route::post('pay42-billet-submit', 'Pay42BilletController@store')->name('billet-submit');
            /** Pay42 card */
            Route::post('pay42-card-submit', 'Pay42CardController@store')->name('card-submit');
            Route::post('pay42-callback', 'Pay42CardController@pay42Callback')->name('notify');
            Route::get('pay42-call-form/{pedido}', 'Pay42CardController@callForm', function ($pedido) {
                return $pedido;
            })->name('callForm');
        });

        Route::post('cashondelivery', 'CashOnDeliveryController@store')->name('cash.submit');
        Route::post('bankdeposit', 'App\Http\Controllers\Front\BankDepositController@store')->name('bank.submit');

        Route::get('addnumcart', 'CartController@addnumcart');
        Route::get('addtonumcart', 'CartController@addtonumcart');
        Route::get('addbyone', 'CartController@addbyone');
        Route::get('reducebyone', 'CartController@reducebyone');
        Route::get('carts/coupon', 'CartController@coupon');
        Route::get('carts/view', 'CartController@cartview');

        Route::get('autosearch/product/{slug}', 'FrontendController@autosearch');

        Route::get('afbuy/{slug}', 'CatalogController@affProductRedirect')->name('affiliate.product');

        Route::get('checkout/payment/return', 'PaymentController@payreturn')->name('payment.return');
        Route::get('checkout/simplified/return', 'PaymentController@payreturnSimplifidCheckout')->name('simplified_checkout-payment.return');

        Route::group([
            'as' => 'front.'
        ], function () {
            Route::get('/accept-cookies', 'FrontendController@acceptCookies')->name('acceptcookies');
            Route::get('stores', 'VendorController@stores')->name('stores')->middleware('vendor.redirect');
            Route::get('store/{category}', 'VendorController@index')->name('vendor')->middleware('vendor.redirect');
            Route::get('brand/{brand}', 'CatalogController@brand')->name('brand');
            Route::get('brands', 'CatalogController@brands')->name('brands');
            Route::post('subscriber/store', 'FrontendController@subscribe')->name('subscribe');
            Route::get('carts', 'CartController@cart')->name('cart');
            Route::get('order/track/{id}', 'FrontendController@trackload')->name('track.search');
            Route::get('checkout/cep', 'CheckoutController@getCep')->name('cep');
            Route::get('checkout/getCorreios', 'CheckoutController@getCorreios')->name('getCorreios');
            Route::get('checkout/aex', 'CheckoutController@getAex')->name('aex');
            Route::get('checkout/getStatesOptions', 'FrontendController@getStatesOptions')->name('getStatesOptions');
            Route::get('checkout/getCitiesOptions', 'FrontendController@getCitiesOptions')->name('getCitiesOptions');
            Route::get('checkout/getShippingsOptions', 'CheckoutController@getShippingsOptions')->name('getShippingsOptions');
            Route::get('checkout/payment/{slug1}/{slug2}', 'CheckoutController@loadpayment')->name('load.payment');
            Route::get('checkout/simplified', 'CheckoutSimplifiedController@create')->name('simplified_checkout-create');
            Route::get('checkout/{abandonment?}', 'CheckoutController@checkout')->name('checkout');
            Route::get('team_member', 'FrontendController@team_member')->name('team_member');
            Route::get('receipt/', 'FrontendController@receipt')->name('receipt');
            Route::get('receipt/{order_number?}', 'FrontendController@receiptNumber')->name('receipt-number');
            Route::get('receipt/search/{order_number}', 'FrontendController@receiptSearch')->name('receipt-search');
            Route::post('receipt/upload/{id?}', 'FrontendController@uploadReceipt')->name('upload-receipt');
            Route::post('contact', 'FrontendController@contactemail')->name('contact.submit');
            Route::get('contact/refresh_code', 'FrontendController@refresh_code');
            Route::get('contact', 'FrontendController@contact')->name('contact');
            Route::get('policy', 'FrontendController@policy')->name('policy');
            Route::get('terms-of-service', 'FrontendController@crowpolicy')->name('crowpolicy');
            Route::get('vendor-terms-of-service', 'FrontendController@vendorpolicy')->name('vendorpolicy');
            Route::get('privacy-policy', 'FrontendController@privacypolicy')->name('privacypolicy');
            Route::get('faq', 'FrontendController@faq')->name('faq');
            Route::get('blog/tag/{slug}', 'FrontendController@blogtags')->name('blogtags');
            Route::get('blog/archive/{slug}', 'FrontendController@blogarchive')->name('blogarchive');
            Route::get('blog/category/{slug}', 'FrontendController@blogcategory')->name('blogcategory');
            Route::get('blog/{id}', 'FrontendController@blogshow')->name('blogshow');
            Route::get('blog-search', 'FrontendController@blogsearch')->name('blogsearch');
            Route::get('blog', 'FrontendController@blog')->name('blog');
            Route::get('category/{category?}/{subcategory?}/{childcategory?}', 'CatalogController@category')->name('category');
            Route::get('category/{slug1}/{slug2}', 'CatalogController@subcategory')->name('subcat');
            Route::get('category/{slug1}/{slug2}/{slug3}', 'CatalogController@childcategory')->name('childcat');
            Route::get('categories', 'CatalogController@categories')->name('categories');
            Route::get('currency/{id}', 'FrontendController@currency')->name('currency');
            Route::get('language/{id}/{idCurrency}', 'FrontendController@language')->name('language');
            Route::post('item/review', 'CatalogController@reviewsubmit')->name('review.submit');
            Route::get('item/view/review/{id}', 'CatalogController@reviews')->name('reviews');
            Route::get('item/{slug}', 'CatalogController@product')->name('product');
            Route::get('extras', 'FrontendController@extraIndex')->name('extraIndex');
            Route::get('{slug}', 'FrontendController@page')->name('page');
            Route::get('/', 'FrontendController@index')->name('index');
        });

        Route::group([
            'as' => 'product.'
        ], function () {
            Route::group([
                'prefix' => 'item'
            ], function () {
                Route::post('report', 'CatalogController@report')->name('report');
                Route::post('reply/{id}', 'CatalogController@reply')->name('reply');
                Route::post('reply/edit/{id}', 'CatalogController@replyedit')->name('reply.edit');
                Route::get('reply/delete/{id}', 'CatalogController@replydelete')->name('reply.delete');
                Route::post('comment/store', 'CatalogController@comment')->name('comment');
                Route::post('comment/edit/{id}', 'CatalogController@commentedit')->name('comment.edit');
                Route::get('comment/delete/{id}', 'CatalogController@commentdelete')->name('comment.delete');
                Route::get('quick/view/{id}/', 'CatalogController@quick')->name('quick');
                Route::get('compare/add/{id}', 'CompareController@addcompare')->name('compare.add');
                Route::get('compare/remove/{id}', 'CompareController@removecompare')->name('compare.remove');
                Route::get('compare/view', 'CompareController@compare')->name('compare');
                Route::post('backinstock/notify-me/{id}/', 'BackInStockController@notifyme')->name('backinstock');
            });

            Route::get('addcart/{id}', 'CartController@addcart')->name('cart.add');
            Route::get('cart/wedding/{user}/{id}', 'CartController@addToCartAndRedirectWedding')->name('cart.redirect.wedding');
            Route::get('removecart/{id}', 'CartController@removecart')->name('cart.remove');
            Route::get('addtocart/{id}', 'CartController@addtocart')->name('cart.quickadd');
        });

        Route::get('carts/coupon', 'CartController@coupon');
        Route::get('carts/coupon/check', 'CartController@couponcheck');
    });

    /**
     * User Section
     */
    Route::group([
        'namespace' => 'User'
    ], function () {
        Route::group([
            'prefix' => 'user',
        ], function () {
            Route::group([
                'as' => 'user.'
            ], function () {
                Route::get('login', 'LoginController@showLoginForm')->name('login');
                Route::post('login', 'LoginController@login')->name('login.submit');
                Route::group([
                    'as' => 'wedding.',
                    'prefix' => 'wedding',
                    'middleware' => 'wedding',
                ], function () {
                    Route::get('store/{product}', 'WeddingListController@store')->name('store')->middleware('auth');
                    Route::get('buy/{user}/{product_id}', 'WeddingListController@buyProduct')->name('buy')->middleware('auth');
                    Route::post('privacy/', 'WeddingListController@privacy')->name('privacy')->middleware('auth');
                    Route::get('show/{user}', 'WeddingListController@show')->name('show');
                    Route::get('download/{user}', 'WeddingListController@download')->name('download');
                });
            });

            Route::group([
                'as' => 'user-'
            ], function () {
                Route::get('subscription/{id}', 'UserController@vendorrequest')->name('vendor-request');
                Route::post('vendor-request', 'UserController@vendorrequestsub')->name('vendor-request-submit');
                Route::get('affilate/withdraw', 'WithdrawController@index')->name('wwt-index');
                Route::get('affilate/withdraw/create', 'WithdrawController@create')->name('wwt-create');
                Route::post('affilate/withdraw/create', 'WithdrawController@store')->name('wwt-store');
                Route::get('affilate/code', 'WithdrawController@affilate_code')->name('affilate-code');
                Route::get('reset', 'UserController@resetform')->name('reset');
                Route::post('reset', 'UserController@reset')->name('reset-submit');
                Route::get('admin/disputes', 'MessageController@adminDiscordmessages')->name('dmessage-index');
                Route::get('admin/tickets', 'MessageController@adminmessages')->name('message-index');
                Route::get('admin/message/load/{id}', 'MessageController@messageload')->name('message-load');
                Route::post('admin/message/post', 'MessageController@adminpostmessage')->name('message-store');
                Route::get('admin/message/{id}', 'MessageController@adminmessage')->name('message-show');
                Route::get('admin/message/{id}/delete', 'MessageController@adminmessagedelete')->name('message-delete1');
                Route::post('admin/user/send/message', 'MessageController@adminusercontact')->name('send-message');
                Route::post('message/post', 'MessageController@postmessage')->name('message-post');
                Route::get('message/load/{id}', 'MessageController@msgload')->name('vendor-message-load');
                Route::get('message/{id}/delete', 'MessageController@messagedelete')->name('message-delete');
                Route::get('message/{id}', 'MessageController@message')->name('message');
                Route::get('messages', 'MessageController@messages')->name('messages');
                Route::get('favorite/{id1}/{id2}', 'UserController@favorite')->name('favorite');
                Route::get('favorite/seller/{id}/delete', 'UserController@favdelete')->name('favorite-delete');
                Route::get('favorite/seller', 'UserController@favorites')->name('favorites');
                Route::get('print/order/print/{id}', 'OrderController@orderprint')->name('order-print');
                Route::get('order/tracking', 'OrderController@ordertrack')->name('order-track');
                Route::get('order/trackings/{id}', 'OrderController@trackload')->name('order-track-search');
                Route::get('order/{id}', 'OrderController@order')->name('order');
                Route::get('orders', 'OrderController@orders')->name('orders');
                Route::get('order/uploadreceipt/{id}', 'OrderController@uploadReceiptGet')->name('upload-receipt-get');
                Route::post('order/uploadreceipt/{id?}', 'OrderController@uploadReceipt')->name('upload-receipt');
                Route::get('package', 'UserController@package')->name('package');
                Route::get('profile', 'UserController@profile')->name('profile');
                Route::post('profile', 'UserController@profileupdate')->name('profile-update');
                Route::get('dashboard', 'UserController@index')->name('dashboard');
                Route::get('wishlist/add/{id}/{group?}', 'WishlistController@addwish')->name('wishlist-add');
                Route::get('wishlist/remove/{id}', 'WishlistController@removewish')->name('wishlist-remove');
                Route::get('wishlists', [WishlistGroupController::class, 'index'])->name('wishlists');
                Route::get('wishlists/{wishlistGroup}', [WishlistGroupController::class, 'show'])->name('wishlists.show');
                Route::post('wishlists/{wishlistGroup}', [WishlistGroupController::class, 'changePrivacity'])->name('wishlists.privacity');
                Route::delete('wishlists/{wishlistGroup}', [WishlistGroupController::class, 'destroy'])->name('wishlists.destroy');
                Route::post('wishlists', [WishlistGroupController::class, 'store'])->name('wishlists.store');
                Route::get('register/verify/{token}', 'RegisterController@token')->name('register-token');
                Route::post('register', 'RegisterController@register')->name('register-submit');
                Route::get('forgot', 'ForgotController@showforgotform')->name('forgot');
                Route::get('reset/{token}', 'ForgotController@showPasswordForm')->name('reset-token');
                Route::post('reset/{token}', 'ForgotController@resetPassword')->name('reset-password');
                Route::post('forgot', 'ForgotController@forgot')->name('forgot-submit');
                Route::get('logout', 'LoginController@logout')->name('logout');
            });

            Route::post('user/contact', 'MessageController@usercontact');
        });
    });

    Route::group([
        'prefix' => 'auth',
    ], function () {
        Route::get('{provider}', 'User\SocialRegisterController@redirectToProvider')->name('social-provider');
        Route::get('{provider}/callback', 'User\SocialRegisterController@handleProviderCallback');
    });

    Route::group([
        'namespace' => 'Vendor'
    ], function () {
        Route::group([
            'prefix' => 'vendor',
            'as' => 'vendor.'
        ], function () {
            Route::get('login', 'LoginController@showLoginForm')->name('login');
            Route::post('login', 'LoginController@login')->name('login.submit');
            Route::post('register', 'RegisterController@register')->name('register-submit');
        });
    });

    Route::group([
        'middleware' => 'vendor'
    ], function () {
        /**
         * Vendor Section
         */
        Route::group([
            'namespace' => 'Vendor',
            'middleware' => 'vendor'
        ], function () {
            Route::group([
                'prefix' => 'vendor',
                'as' => 'vendor-'
            ], function () {
                Route::get('subscription/', 'SubscriptionController@index')->name('subscription-index');
                Route::post('gallery/store', 'GalleryController@store')->name('gallery-store');
                Route::get('gallery/show', 'GalleryController@show')->name('gallery-show');
                Route::get('gallery/delete', 'GalleryController@destroy')->name('gallery-delete');
                Route::get('load/subcategories/{id}/', 'VendorController@subcatload')->name('subcat-load');
                Route::get('load/childcategories/{id}/', 'VendorController@childcatload')->name('childcat-load');
                Route::get('getattributes', 'ProductController@getAttributes')->name('prod-getattributes');
                Route::post('social/update', 'VendorController@socialupdate')->name('social-update');
                Route::get('social', 'VendorController@social')->name('social-index');
                Route::get('package/create', 'PackageController@create')->name('package-create');
                Route::post('package/store', 'PackageController@store')->name('package-store');
                Route::get('package/datatables', 'PackageController@datatables')->name('package-datatables');
                Route::get('package/{id}', 'PackageController@edit')->name('package-edit');
                Route::get('package/delete/{id}', 'PackageController@destroy')->name('package-delete');
                Route::post('package/update/{id}', 'PackageController@update')->name('package-update');
                Route::get('package', 'PackageController@index')->name('package-index');
                Route::get('banner', 'VendorController@banner')->name('banner');
                Route::post('delete', 'VendorController@deleteImage')->name('delete-banner');
                Route::get('service/create', 'ServiceController@create')->name('service-create');
                Route::post('service/store', 'ServiceController@store')->name('service-store');
                Route::get('service/edit/{id}', 'ServiceController@edit')->name('service-edit');
                Route::post('service/update/{id}', 'ServiceController@update')->name('service-update');
                Route::get('service/delete/{id}', 'ServiceController@destroy')->name('service-delete');
                Route::get('service/datatables', 'ServiceController@datatables')->name('service-datatables');
                Route::get('service', 'ServiceController@index')->name('service-index');
                Route::get('withdraw/create', 'WithdrawController@create')->name('wt-create');
                Route::post('withdraw/create', 'WithdrawController@store')->name('wt-store');
                Route::get('withdraw', 'WithdrawController@index')->name('wt-index');
                Route::get('products/fastedit/{id}', 'ProductController@fastedit')->name('prod-fastedit');
                Route::post('products/fastedit/{id}', 'ProductController@fasteditsubmit')->name('prod-fastedit-submit');
                Route::post('products/upload/update/{id}', 'ProductController@uploadUpdate')->name('prod-upload-update');
                Route::get('products/status/{id1}/{id2}', 'ProductController@status')->name('prod-status');
                //Route::get('products/physical/create', 'ProductController@createPhysical')->name('prod-physical-create');
                //Route::get('products/digital/create', 'ProductController@createDigital')->name('prod-digital-create');
                //Route::get('products/license/create', 'ProductController@createLicense')->name('prod-license-create');
                //Route::post('products/store', 'ProductController@store')->name('prod-store');
                //Route::get('products/edit/{id}', 'ProductController@edit')->name('prod-edit');
                Route::get('products/copy/{id}', 'ProductController@copy')->name('prod-copy');
                Route::post('products/copy/{id}', 'ProductController@copySubmit')->name('prod-copy-submit');
                //Route::post('products/edit/{id}', 'ProductController@update')->name('prod-update');
                Route::get('products/delete/{id}', 'ProductController@destroy')->name('prod-delete');
                Route::get('products/import/create', 'ImportController@createImport')->name('import-create');
                Route::get('products/import/edit/{id}', 'ImportController@edit')->name('import-edit');
                Route::post('products/import/update/{id}', 'ImportController@update')->name('import-update');
                Route::post('products/import/store', 'ImportController@store')->name('import-store');
                Route::get('products/import/datatables', 'ImportController@datatables')->name('import-datatables');
                Route::get('products/import/index', 'ImportController@index')->name('import-index');
                Route::post('products/import-submit', 'ProductController@importSubmit')->name('prod-importsubmit');
                Route::get('products/import', 'ProductController@import')->name('prod-import');
                Route::get('products/types', 'ProductController@createPhysical')->name('prod-types');
                Route::post('profile/delete-image', 'VendorController@deleteProfileImage')->name('delete.image');
                Route::get('profile', 'VendorController@profile')->name('profile');
                Route::post('profile', 'VendorController@profileupdate')->name('profile-update');
                Route::get('products/datatables', 'ProductController@datatables')->name('prod-datatables');
                Route::get('products/datatablesadmin', 'ProductController@datatablesAdmin')->name('prod-datatablesadmin');
                Route::get('products', 'ProductController@index')->name('prod-index');
                Route::get('order/notf/count/{id}', 'NotificationController@order_notf_count')->name('order-notf-count');
                Route::get('order/notf/show/{id}', 'NotificationController@order_notf_show')->name('order-notf-show');
                Route::get('order/notf/clear/{id}', 'NotificationController@order_notf_clear')->name('order-notf-clear');
                Route::get('order/datatables', 'OrderController@datatables')->name('order-datatables');
                Route::get('order/{id}/show', 'OrderController@show')->name('order-show');
                Route::get('order/{id}/invoice', 'OrderController@invoice')->name('order-invoice');
                Route::get('order/{id}/print', 'OrderController@printpage')->name('order-print');
                Route::get('order/{id1}/status/{status}', 'OrderController@status')->name('order-status');
                Route::post('order/email/', 'OrderController@emailsub')->name('order-emailsub');
                Route::get('order/edit/{id}', 'OrderController@edit')->name('order-edit');
                Route::post('update/{id}', 'OrderController@update')->name('order-update');
                Route::post('order/license/', 'OrderController@license')->name('order-license');
                Route::get('orders', 'OrderController@index')->name('order-index');
                Route::get('warning/verify/{id}', 'VendorController@warningVerify')->name('warning');
                Route::get('verify', 'VendorController@verify')->name('verify');
                Route::post('verify', 'VendorController@verifysubmit')->name('verify-submit');
                Route::get('dashboard', 'VendorController@index')->name('dashboard');
                Route::get('withdraw/datatables', 'WithdrawController@datatables')->name('withdraw-datatables');
            });
        });
    });
});
