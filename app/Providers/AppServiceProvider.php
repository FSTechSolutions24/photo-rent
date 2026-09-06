<?php

namespace App\Providers;

use App\Contracts\FaceAnalysisProvider;
use App\Services\FaceRecognition\OpenCvFaceAnalysisProvider;
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
        $this->app->bind(FaceAnalysisProvider::class, function ($app) {
            $provider = config('face-recognition.provider');

            if ($provider !== 'opencv') {
                throw new \InvalidArgumentException("Unsupported face-recognition provider [{$provider}].");
            }

            return $app->make(OpenCvFaceAnalysisProvider::class);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
