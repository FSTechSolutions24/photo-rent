<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\Schema;

class PublicSiteController extends Controller
{
    public function home()
    {
        $plans = collect();

        if (Schema::hasTable('subscription_plans')) {
            $plans = SubscriptionPlan::where('price', '>', 0)
                ->with(['lines' => function ($query) {
                    $query->where('is_included', true)->orderBy('sort_order');
                }])
                ->orderBy('price')
                ->get();
        }

        if ($plans->isEmpty()) {
            $plans = collect([
                ['name' => 'Essential', 'price' => 299, 'billing_cycle' => 'month', 'most_popular' => false, 'lines' => [
                    ['feature_name' => '50 GB storage'],
                    ['feature_name' => 'Unlimited client galleries'],
                    ['feature_name' => 'Password-protected galleries'],
                    ['feature_name' => 'Custom portfolio page'],
                ]],
                ['name' => 'Professional', 'price' => 599, 'billing_cycle' => 'month', 'most_popular' => true, 'lines' => [
                    ['feature_name' => '200 GB storage'],
                    ['feature_name' => 'Unlimited client galleries'],
                    ['feature_name' => 'Password-protected galleries'],
                    ['feature_name' => 'Face recognition filters'],
                ]],
                ['name' => 'Studio', 'price' => 999, 'billing_cycle' => 'month', 'most_popular' => false, 'lines' => [
                    ['feature_name' => '500 GB storage'],
                    ['feature_name' => 'Unlimited client galleries'],
                    ['feature_name' => 'Password-protected galleries'],
                    ['feature_name' => 'High-volume gallery delivery'],
                ]],
            ]);
        }

        return view('welcome', compact('plans'));
    }

    public function about()
    {
        return view('public.about');
    }

    public function contact()
    {
        return view('public.contact');
    }

    public function privacy()
    {
        return view('public.privacy');
    }

    public function delivery()
    {
        return view('public.delivery');
    }

    public function refunds()
    {
        return view('public.refunds');
    }
}
