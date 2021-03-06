<?php

namespace Mollie\Providers;

use Mollie\Controllers\PaymentController;
use Plenty\Plugin\RouteServiceProvider as PlentyRouteServiceProvider;
use Plenty\Plugin\Routing\ApiRouter;
use Plenty\Plugin\Routing\Router;

/**
 * Class RouteServiceProvider
 * @package Mollie\Providers
 */
class RouteServiceProvider extends PlentyRouteServiceProvider
{
    /**
     * @param ApiRouter $apiRouter
     */
    public function map(ApiRouter $apiRouter, Router $router)
    {
        $apiRouter->version(
            ['v1'],
            ['namespace' => 'Mollie\Controllers', 'middleware' => ['oauth']],
            function ($apiRouter) {

                // Shipping Settings
                $apiRouter->get('mollie/methods', 'SettingsController@getMethods');
                $apiRouter->put('mollie/methods/{id}/settings', 'SettingsController@saveMethodSetting');
            }
        );

        $router->get('mollie/check', PaymentController::class . '@checkPayment');
        $router->post('mollie/submit-creditcard', PaymentController::class . '@createOrderByCreditCard');

        $apiRouter->version(
            ['v1'],
            ['namespace' => 'Mollie\Controllers'],
            function ($apiRouter) {

                //Frontend routes
                $apiRouter->put('mollie/activate_apple_pay', 'PaymentController@activateApplePay');
                $apiRouter->get('mollie/init_payment', 'PaymentController@reInit');
                $apiRouter->post('mollie/webhook', 'PaymentController@webHook');
            }
        );
    }
}