<?php

namespace App\Providers;

use App\Sale;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Validator as Validation;

class AppValidatorExtendExtenstionProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->extendedSaleableValidator();
    }

    /**
     * Check if item saleable exists in storage.
     */
    protected function extendedSaleableValidator()
    {
        Validator::extend('saleable' , function(string $attribute, array $sale, array $parameters, Validation $validator) {
            if(! class_exists($class = $sale['type'])) {
                return false;
            }

            return ! is_null($class::where('id', $sale['id'])->first());
        });
        Validator::replacer('saleable', function ($message, $attribute, $rule, $parameters) {
            return 'The ' . $attribute . ' is invalid.';
        });
    }
}
