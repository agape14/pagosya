<?php

namespace App\Providers;
use Illuminate\Support\Facades\Validator;
use Anhskohbo\NoCaptcha\NoCaptchaServiceProvider;
use Anhskohbo\NoCaptcha\Facades\NoCaptcha;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('captcha', function ($attribute, $value, $parameters, $validator) {
            return NoCaptcha::verifyResponse($value, request()->ip());
        });

        Validator::replacer('captcha', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':attribute', $attribute, 'The :attribute is invalid.');
        });
    }
}
