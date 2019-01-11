<?php

use App\Http\Controllers\Auth\Test\TestController;
use App\Http\Controllers\Backend\Auth\Cart\CartController;
use App\Http\Controllers\Backend\Auth\Role\RoleController;
use App\Http\Controllers\Backend\Auth\User\UserController;
use App\Http\Controllers\Backend\Auth\Client\ClientController;
use App\Http\Controllers\Backend\Auth\Course\CourseController;
use App\Http\Controllers\Backend\Client\CcregistrantController;
use App\Http\Controllers\Backend\Client\CtransactionController;
use App\Http\Controllers\Backend\Auth\Client\CheckoutController;
use App\Http\Controllers\Backend\Auth\Profile\ProfileController;
use App\Http\Controllers\Backend\Auth\User\UserAccessController;
use App\Http\Controllers\Backend\Auth\User\UserSocialController;
use App\Http\Controllers\Backend\Auth\User\UserStatusController;
use App\Http\Controllers\Backend\Auth\User\UserSessionController;
use App\Http\Controllers\Backend\Auth\User\UserPasswordController;
use App\Http\Controllers\Backend\Auth\Course\CourseReviewController;
use App\Http\Controllers\Backend\Auth\Course\CourseStatusController;
use App\Http\Controllers\Backend\Client\CcregistrantstatusController;
use App\Http\Controllers\Backend\Client\CtransactionstatusController;
use App\Http\Controllers\Backend\Auth\User\UserConfirmationController;
use App\Http\Controllers\Backend\Auth\Transaction\TransactionController;
use App\Http\Controllers\Backend\Auth\Transaction\TransactionStatusController;
use App\Http\Controllers\Backend\Auth\Courseregistrant\CourseregistrantController;
use App\Http\Controllers\Backend\Auth\Courseregistrant\CourseregistrantStatusController;

/*
 * All route names are prefixed with 'admin.auth'.
 */
Route::group([
    'prefix'     => 'auth',
    'as'         => 'auth.',
    'namespace'  => 'Auth',
    'middleware' => ['role:super-admin|writer']], function () {

     /*
     * User Management
     */
    Route::group(['namespace' => 'User'], function () {

        /*
         * User Status'
         */
        Route::get('user/deactivated', [UserStatusController::class, 'getDeactivated'])->name('user.deactivated');
        Route::get('user/deleted', [UserStatusController::class, 'getDeleted'])->name('user.deleted');

        /*
         * User CRUD
         */
        Route::get('user', [UserController::class, 'index'])->name('user.index');
        Route::get('user/create', [UserController::class, 'create'])->name('user.create');
        Route::post('user', [UserController::class, 'store'])->name('user.store');

        /*
         * Specific User
         */
        Route::group(['prefix' => 'user/{user}'], function () {
            // User
            Route::get('/', [UserController::class, 'show'])->name('user.show');
            Route::get('edit', [UserController::class, 'edit'])->name('user.edit');
            Route::patch('/', [UserController::class, 'update'])->name('user.update');
            Route::delete('/', [UserController::class, 'destroy'])->name('user.destroy');

            // Account
            Route::get('account/confirm/resend', [UserConfirmationController::class, 'sendConfirmationEmail'])->name('user.account.confirm.resend');

            // Status
            Route::get('mark/{status}', [UserStatusController::class, 'mark'])->name('user.mark')->where(['status' => '[0,1]']);

            // Social
            Route::delete('social/{social}/unlink', [UserSocialController::class, 'unlink'])->name('user.social.unlink');

            // Confirmation
            Route::get('confirm', [UserConfirmationController::class, 'confirm'])->name('user.confirm');
            Route::get('unconfirm', [UserConfirmationController::class, 'unconfirm'])->name('user.unconfirm');

            // Password
            Route::get('password/change', [UserPasswordController::class, 'edit'])->name('user.change-password');
            Route::patch('password/change', [UserPasswordController::class, 'update'])->name('user.change-password.post');

            // Access
            Route::get('login-as', [UserAccessController::class, 'loginAs'])->name('user.login-as');

            // Session
            Route::get('clear-session', [UserSessionController::class, 'clearSession'])->name('user.clear-session');

            // Deleted
            Route::get('delete', [UserStatusController::class, 'delete'])->name('user.delete-permanently');
            Route::get('restore', [UserStatusController::class, 'restore'])->name('user.restore');
        });
    });

    /*
     * Role Management
     */
    // Route::group(['namespace' => 'Role'], function () {
    //     Route::get('role', [TestController::class, 'index'])->name('role.index');
    //     Route::get('role/create', [TestController::class, 'create'])->name('role.create');
    //     Route::post('role', [TestController::class, 'store'])->name('role.store');

    //     Route::group(['prefix' => 'role/{role}'], function () {
    //         Route::get('edit', [TestController::class, 'edit'])->name('role.edit');
    //         Route::patch('/', [TestController::class, 'update'])->name('role.update');
    //         Route::delete('/', [TestController::class, 'destroy'])->name('role.destroy');
    //     });
    // });
});

/*
 * All route names are prefixed with 'admin.auth'.
 */
Route::group([
    'prefix'     => 'auth',
    'as'         => 'auth.',
    'namespace'  => 'Auth',
    'middleware' => 'role:'.config('access.users.default_role')

    ,
], function () {

    Route::group(['namespace' => 'client'], function () {

        /*
       * Ccregistrant Status'
       */
        Route::get('ccregistrant/deactivated', [CcregistrantstatusController::class, 'getdeactivated'])->name('ccregistrant.deactivated');
        Route::get('ccregistrant/deleted', [CcregistrantstatusController::class, 'getDeleted'])->name('ccregistrant.deleted');
        Route::get('ccregistrant/mark/{id}', [CcregistrantstatusController::class, 'mark'])->name('ccregistrant.mark');
        // Access
        /*
        * Ccregistrant CRUD
        */
        Route::get('ccregistrant', [CcregistrantController::class, 'index'])->name('ccregistrant.index');
        Route::get('ccregistrant/create', [CcregistrantController::class, 'create'])->name('ccregistrant.create');
        Route::get('ccregistrant/newcreate', [CcregistrantController::class, 'newcreate'])->name('ccregistrant.newcreate');
        Route::post('ccregistrant', [CcregistrantController::class, 'store'])->name('ccregistrant.store');

        /*
         * Specific Ccregistrant
         */
        Route::group(['prefix' => 'ccregistrant/{ccregistrant}'], function () {
            // Deleted
            Route::get('delete', [CcregistrantstatusController::class, 'delete'])->name('ccregistrant.delete-permanently');
            Route::get('restore', [CcregistrantstatusController::class, 'restore'])->name('ccregistrant.restore');
            Route::get('/', [CcregistrantController::class, 'show'])->name('ccregistrant.show');
            Route::get('edit', [CcregistrantController::class, 'edit'])->name('ccregistrant.edit');
            Route::patch('/', [CcregistrantController::class, 'update'])->name('ccregistrant.update');
            Route::delete('/', [CcregistrantController::class, 'destroy'])->name('ccregistrant.destroy');

    //            Route::get('mark/{status}', [CcregistrantstatusController::class, 'mark'])->name('ccregistrant.mark')->where(['status' => '[0,1]']);


        });
    });


    /*
     * Ctransaction Management
     */

    Route::group(['namespace' => 'ctransaction'], function () {

        /*
       * Ctransaction Status'
       */
        Route::get('ctransaction/deactivated', [CtransactionstatusController::class, 'getdeactivated'])->name('ctransaction.deactivated');
        Route::get('ctransaction/deleted', [CtransactionstatusController::class, 'getDeleted'])->name('ctransaction.deleted');
        Route::get('ctransaction/mark/{id}', [CtransactionstatusController::class, 'mark'])->name('ctransaction.mark');
        // Access
        /*
        * Ctransaction CRUD
        */
        Route::get('ctransaction', [CtransactionController::class, 'index'])->name('ctransaction.index');
        Route::get('ctransaction/create', [CtransactionController::class, 'create'])->name('ctransaction.create');
        Route::post('ctransaction', [CtransactionController::class, 'store'])->name('ctransaction.store');

        /*
         * Specific Ctransaction
         */
        Route::group(['prefix' => 'ctransaction/{ctransaction}'], function () {
            // Deleted
            Route::get('delete', [CtransactionstatusController::class, 'delete'])->name('ctransaction.delete-permanently');
            Route::get('restore', [CtransactionstatusController::class, 'restore'])->name('ctransaction.restore');
            Route::get('/', [CtransactionController::class, 'show'])->name('ctransaction.show');
            Route::get('edit', [CtransactionController::class, 'edit'])->name('ctransaction.edit');
            Route::patch('/', [CtransactionController::class, 'update'])->name('ctransaction.update');
            Route::delete('/', [CtransactionController::class, 'destroy'])->name('ctransaction.destroy');

//            Route::get('mark/{status}', [CtransactionstatusController::class, 'mark'])->name('ctransaction.mark')->where(['status' => '[0,1]']);


        });
    });





    // new setup for student/client defualt role

    Route::group(['namespace' => 'Client'], function () {
        // Access
        /*
        * Client CRUD
        */
        Route::get('client', [ClientController::class, 'index'])->name('client.index');
        Route::get('client/create', [ClientController::class, 'create'])->name('client.create');
        Route::post('client', [ClientController::class, 'store'])->name('client.store');
        /*
         * Specific Client
         */
        Route::group(['prefix' => 'client/{client}'], function () {
            // Deleted

            Route::get('/', [ClientController::class, 'show'])->name('client.show');
            Route::get('edit', [ClientController::class, 'edit'])->name('client.edit');
            Route::patch('/', [ClientController::class, 'update'])->name('client.update');
            Route::get('/', [ClientController::class, 'destroy'])->name('client.destroy');

        });
    });

    Route::group(['namespace' => 'Cart'], function () {

        Route::get('cart', [CartController::class, 'index'])->name('cart.index');
        Route::get('cart/enrollCourse/{id}', [CartController::class, 'enrollCourse'])->name('cart.enrollCourse');
//        Route::get('/{cart}', [CartController::class, 'updateDropdownCart'])->name('cart.updateDropdownCart');
        Route::get('cart/{cart', [CartController::class, 'update'])->name('cart.updateCourse');
       ;
        Route::group(['prefix' => 'cart/{cart}'], function () {
            Route::get('/', [CartController::class, 'show'])->name('cart.showCourse');
            Route::get('/', [CartController::class, 'destroy'])->name('cart.destroyCourse');
        });
    });

    Route::group(['namespace' => 'Checkout'], function () {
//        admin.auth.checkout.shipping
        Route::get('step', [CheckoutController::class, 'step1'])->name('checkout.step');
        Route::POST('step2', [CheckoutController::class, 'step2'])->name('checkout.step2');

        Route::group(['prefix' => 'checkout/{checkout}'], function () {
            // Deleted
            Route::get('/', [CheckoutController::class, 'show'])->name('checkout.show');
            Route::get('edit', [CheckoutController::class, 'edit'])->name('checkout.edit');
            Route::PUT('/', [CheckoutController::class, 'update'])->name('checkout.update');
        });
    });

    Route::group(['namespace' => 'Profile'], function () {

        Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
        Route::get('/profile/{link}', [ProfileController::class, 'profile.index_link']);

        Route::get('/courses', [ProfileController::class, 'courses'])->name('profile.courses');
        Route::get('course/view/{id}', [ProfileController::class, 'course_details'])->name('profile.course_details');

        Route::get('/address', [ProfileController::class, 'address'])->name('profile.address');
//        Route::post('/review/{id}', [ProfileController::class, 'addReview'])->name('profile.addReview');
        Route::post('/update/address', [ProfileController::class, 'updateAddress'])->name('profile.address.update');

        Route::get('/thankyou', [ProfileController::class, 'thankyou'])->name('profile.thankyou');
        Route::get('/password', [ProfileController::class, 'password'])->name('profile.password');
        Route::PATCH('/password/update', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    });

    Route::group(['namespace' => 'Profile'], function () {

        Route::post('review', [CourseReviewController::class, 'store'])->name('course.review');
        Route::group(['prefix' => 'course/{course}'], function () {
//        Route::get('/', [CourseReviewController::class, 'store'])->name('course.review');


        });
    });






});

