<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('UserSubscription', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('subscription_id');
            $table->string('start_date', 10);
            $table->string('end_date', 10);
            $table->string('renew_date', 10);
            $table->boolean('is_delete')->default(false);
            $table->timestamps();
            $table->foreign('user_id', 'fk_us')
                ->references('id')
                ->on('UserMaster')
                ->onDelete('cascade');
            $table->foreign('subscription_id', 'fk_us_sm')
                ->references('id')
                ->on('SubscriptionMaster')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('UserSubscription');
    }
};
