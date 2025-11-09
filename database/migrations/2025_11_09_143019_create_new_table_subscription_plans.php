<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewTableSubscriptionPlans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->decimal('price', 10, 2);
            $table->string('billing_cycle')->default('month');
            $table->boolean('is_popular')->default(false);
            $table->dateTime('available_from')->nullable();
            $table->dateTime('available_to')->nullable();
        });

        // Child table (lines)
        Schema::create('subscription_plan_lines', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('subscription_plan_id')->constrained('subscription_plans')->onDelete('cascade');
            $table->string('feature_name');
            $table->text('description')->nullable();
            $table->boolean('is_included')->default(true);
            $table->integer('sort_order')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscription_plans');
        Schema::dropIfExists('subscription_plan_lines');
    }
}
