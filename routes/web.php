<?php
use MiladRahimi\PhpRouter\Router;

$Router = Router::create();

$Router->get('/', [App\Controllers\PAGES::class, 'index']);

$Router->get('/{game}', [App\Controllers\Buy::class, 'game']);
$Router->get('/{game}/{id}', [App\Controllers\Buy::class, 'account']);
$Router->get('/nap-tien', [App\Controllers\Recharge::class, 'recharge']);
$Router->get('/nap-the-cao', [App\Controllers\Recharge::class, 'cardRecharge']);
$Router->get('/cong-dong', [App\Controllers\Community::class, 'index']);
$Router->get('/cong-dong/post/{id}', [App\Controllers\Community::class, 'post']);

$Router->get('/shop', [App\Controllers\Shop::class, 'index']);
$Router->get('/callback/card', [App\Controllers\Recharge::class, 'Callback']);


$Router->group(['prefix' => '/shop'], function (Router $Router) {

    $Router->get('/{username}', [App\Controllers\Shop::class, 'shopPro']);
    $Router->get('/{username}/reviews', [App\Controllers\Shop::class, 'reviews']);
    $Router->get('/{username}/community', [App\Controllers\Shop::class, 'community']);

});

$Router->group(['middleware' => [AuthMiddleware::class]], function (Router $Router) {
    $Router->get('/dang-nhap', [App\Controllers\AuthController::class, 'loginViews']);
    $Router->get('/dang-ky', [App\Controllers\AuthController::class, 'registerViews']);
});

$Router->group(['middleware' => [Authorization::class]], function (Router $Router) {

    $Router->group(['prefix' => '/api'], function (Router $Router) {
        $Router->post('/account/promo', [App\Controllers\Buy::class, 'promo']);
        $Router->post('/{game}/{idacc}/buy', [App\Controllers\Buy::class, 'acc']);
    });

    $Router->get('/dang-xuat', [App\Controllers\AuthController::class, 'logout']);
    $Router->get('/orders', [App\Controllers\Profile::class, 'orders']);
    $Router->get('/thong-tin', [App\Controllers\Profile::class, 'profile']);

    $Router->group(['prefix' => '/thong-tin'], function (Router $Router) {
        $Router->get('/doi-mat-khau', [App\Controllers\Profile::class, 'ChangePass']);
        $Router->get('/nap-tien', [App\Controllers\Profile::class, 'RechargePro']);
        $Router->get('/nap-the-cao', [App\Controllers\Profile::class, 'CardProfile']);
    });

    $Router->get('/mo-shop', [App\Controllers\Shop::class, 'CreateShop']);

    $Router->get('/seller', [App\Controllers\Seller::class, 'index']);

    $Router->group(['prefix' => '/seller', 'middleware' => [Seller::class]], function (Router $Router) {

        $Router->get('/rating', [App\Controllers\Seller::class, 'rating']);
        $Router->get('/account', [App\Controllers\Seller::class, 'accountSeller']);

        $Router->group(['prefix' => '/account'], function (Router $Router) {

            $Router->get('/add', [App\Controllers\Seller::class, 'addAcc']);
            $Router->get('/{idacc}/delete', [App\Controllers\Seller::class, 'DeleteAccount']);
            $Router->get('/add/{game}', [App\Controllers\Seller::class, 'addAcc2']);
            $Router->get('/orders', [App\Controllers\Seller::class, 'orders']);
            $Router->get('/{id}/edit', [App\Controllers\Seller::class, 'EditAcc']);

        });

        $Router->get('/promo', [App\Controllers\Seller::class, 'Promo']);
        $Router->get('/withdraw', [App\Controllers\Seller::class, 'withdraw']);
        $Router->get('/setting', [App\Controllers\Seller::class, 'setting']);

        $Router->group(['prefix' => '/promo'], function (Router $Router) {
            $Router->get('/create', [App\Controllers\Seller::class, 'addPromo']);
            $Router->get('/{id}/edit', [App\Controllers\Seller::class, 'EditPromo']);
            $Router->get('/{id}/delete', [App\Controllers\Seller::class, 'DeletePromo']);
        });

    });

});

$Router->group(['middleware' => [Admin::class], 'prefix' => '/admin'], function (Router $Router) {

    $Router->get('/dashboard', [App\Controllers\AdminController::class, 'dashboard']);

    $Router->get('/member', [App\Controllers\AdminController::class, 'member']);
    $Router->get('/member/{id}/edit', [App\Controllers\AdminController::class, 'Editmember']);
    $Router->get('/member/{id}/delete', [App\Controllers\AdminController::class, 'Deletemember']);

    $Router->get('/shop', [App\Controllers\AdminController::class, 'shop']);
    $Router->get('/shop/{id}/edit', [App\Controllers\AdminController::class, 'Editshop']);
    $Router->get('/shop/{id}/delete', [App\Controllers\AdminController::class, 'Deleteshop']);

    $Router->get('/post', [App\Controllers\AdminController::class, 'post']);
    $Router->get('/post/{id}/delete', [App\Controllers\AdminController::class, 'Deleteshop']);

    $Router->get('/rut', [App\Controllers\AdminController::class, 'withdraw']);
    $Router->get('/rut/{id}/{type}', [App\Controllers\AdminController::class, 'withdrawType']);

    $Router->get('/bank', [App\Controllers\AdminController::class, 'bank']);
    $Router->get('/bank/{id}/delete', [App\Controllers\AdminController::class, 'Deletebank']);
    $Router->get('/bank/{id}/edit', [App\Controllers\AdminController::class, 'Editbank']);

    $Router->get('/card', [App\Controllers\AdminController::class, 'card']);

});

$Router->group(['prefix' => '/api'], function (Router $Router) {

    $Router->group(['middleware' => [AuthMiddlewareAPI::class]], function (Router $Router) {
        $Router->post('/login', [App\Controllers\AuthController::class, 'login']);
        $Router->post('/register', [App\Controllers\AuthController::class, 'register']);
    });

    $Router->group(['middleware' => [AuthorizationAPI::class]], function (Router $Router) {

        $Router->post('/profile/post', [App\Controllers\Community::class, 'createpost']);
        $Router->post('/profile/change-password', [App\Controllers\Profile::class, 'ChangePassword']);
        $Router->post('/profile/change-information', [App\Controllers\Profile::class, 'ChangeInfo']);
        $Router->get('/orders/{idacc}/views', [App\Controllers\Profile::class, 'OrdersInfo']);
        $Router->get('/orders/{idacc}/shop', [App\Controllers\Profile::class, 'OrdersShop']);
        $Router->post('/orders/{idacc}/rating', [App\Controllers\Profile::class, 'OrdersRating']);

        $Router->post('/recharge/addcard', [App\Controllers\Recharge::class, 'AddCard']);

        $Router->post('/shop/create', [App\Controllers\Shop::class, 'CreateShopSend']);

        $Router->post('/seller/setting', [App\Controllers\Seller::class, 'settingUpdate']);

        $Router->group(['middleware' => [SellerAPI::class]], function (Router $Router) {

            $Router->post('/seller/withdraw/create', [App\Controllers\Seller::class, 'CreateRut']);

            $Router->post('/seller/promo/create', [App\Controllers\Seller::class, 'CreatePromo']);
            $Router->post('/seller/promo/{id}/edit', [App\Controllers\Seller::class, 'UpdatePromo']);

            $Router->post('/seller/account/add/{game}', [App\Controllers\Seller::class, 'AddAccount']);
            $Router->post('/seller/account/{game}/{idacc}/edit', [App\Controllers\Seller::class, 'EditAccount']);

        });
    });

    $Router->group(['middleware' => [AdminAPI::class], 'prefix' => '/admin'], function (Router $Router) {
        $Router->post('/dashboard', [App\Controllers\AdminController::class, 'dashboardUP']);
        $Router->post('/member/{id}/edit', [App\Controllers\AdminController::class, 'EditmemberUP']);
        $Router->post('/shop/{id}/edit', [App\Controllers\AdminController::class, 'EditshopUP']);
        
        $Router->post('/bank/create', [App\Controllers\AdminController::class, 'addbank']);
        $Router->post('/bank/{id}/edit', [App\Controllers\AdminController::class, 'EditbankUP']);
    });

});

Bin::Router($Router);
