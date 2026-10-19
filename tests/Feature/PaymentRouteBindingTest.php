<?php

namespace Tests\Feature;

use App\Http\Controllers\PaymentController;
use App\Http\Middleware\SetLocale;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Routing\ControllerDispatcher;
use PHPUnit\Framework\Attributes\DataProvider;
use ReflectionMethod;
use Tests\TestCase;

class PaymentRouteBindingTest extends TestCase
{
    public static function localizedPaymentRoutes(): array
    {
        $cases = [];

        foreach (['th', 'en'] as $locale) {
            foreach ([
                ['GET', 'kbank/inquiry', 'inquiryKBankPaymentTransition'],
                ['GET', 'payment-issue', 'paymentIssue'],
                ['GET', 'receipt', 'receipt'],
                ['POST', 'kbank/checkout', 'kbankCheckout'],
            ] as [$verb, $path, $action]) {
                $cases["{$locale}-{$action}"] = [$locale, $verb, $path, $action];
            }
        }

        return $cases;
    }

    #[DataProvider('localizedPaymentRoutes')]
    public function test_dispatcher_matches_route_parameters_to_controller_arguments(
        string $locale,
        string $verb,
        string $path,
        string $action,
    ): void {
        $request = Request::create("/{$locale}/payment-transitions/123/{$path}", $verb);
        $route = $this->app['router']->getRoutes()->match($request);
        $this->assertSame($action, $route->getActionMethod());

        // Supply the model normally loaded by SubstituteBindings, without a database.
        $order = new Order;
        $order->id = 123;
        $route->setParameter('order', $order);
        $request->setRouteResolver(fn () => $route);
        $this->app->instance('request', $request);

        $dispatcher = new ControllerDispatcher($this->app);
        $resolve = new ReflectionMethod(ControllerDispatcher::class, 'resolveParameters');
        $arguments = array_values($resolve->invoke($dispatcher, $route, new PaymentController, $action));

        $expected = $action === 'receipt' ? [$locale, $order] : [$request, $locale, $order];
        $this->assertSame($expected, $arguments);
        $this->assertSame(count($arguments), (new ReflectionMethod(PaymentController::class, $action))->getNumberOfParameters());

        if ($action === 'receipt') {
            // A missing session must redirect rather than throw a TypeError during dispatch.
            $response = (new SetLocale)->handle(
                $request,
                fn () => $dispatcher->dispatch($route, new PaymentController, $action),
            );
            $this->assertSame(302, $response->getStatusCode());
            $this->assertSame(url("/{$locale}/check-premium"), $response->getTargetUrl());
        }
    }
}
