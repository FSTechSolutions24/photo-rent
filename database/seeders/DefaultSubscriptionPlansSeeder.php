<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DefaultSubscriptionPlansSeeder extends Seeder
{
    /**
     * Add initial paid plans without replacing any plans already managed by an admin.
     */
    public function run(): void
    {
        if (SubscriptionPlan::query()->exists()) {
            $this->command?->info('Subscription plans already exist; no defaults were added.');

            return;
        }

        $plans = [
            [
                'name' => 'Essential',
                'price' => 299,
                'billing_cycle' => 'month',
                'storage_gb' => 50,
                'most_popular' => false,
                'features' => [
                    'Unlimited client galleries',
                    'Password-protected galleries',
                    'Custom portfolio page',
                ],
            ],
            [
                'name' => 'Professional',
                'price' => 599,
                'billing_cycle' => 'month',
                'storage_gb' => 200,
                'most_popular' => true,
                'features' => [
                    'Unlimited client galleries',
                    'Password-protected galleries',
                    'Custom portfolio page',
                    'Face recognition filters',
                ],
            ],
            [
                'name' => 'Studio',
                'price' => 999,
                'billing_cycle' => 'month',
                'storage_gb' => 500,
                'most_popular' => false,
                'features' => [
                    'Unlimited client galleries',
                    'Password-protected galleries',
                    'Custom portfolio page',
                    'Face recognition filters',
                    'High-volume gallery delivery',
                ],
            ],
        ];

        DB::transaction(function () use ($plans): void {
            foreach ($plans as $planData) {
                $features = $planData['features'];
                unset($planData['features']);

                $plan = SubscriptionPlan::create($planData);

                foreach ($features as $sortOrder => $feature) {
                    $plan->lines()->create([
                        'feature_name' => $feature,
                        'is_included' => true,
                        'sort_order' => $sortOrder,
                    ]);
                }
            }
        });

        $this->command?->info('Default paid subscription plans were added.');
    }
}
