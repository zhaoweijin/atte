<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->increments('id');

            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');

            $table->nullableTimestamps();
            $table->softDeletes();

            $table->string('name')->nullable();
            $table->string('last_ip')->nullable();
            $table->timestamp('last_login_date')->nullable();

            $table->string('address1')->nullable();
            $table->string('address2')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->text('email_footer')->nullable();

            $table->boolean('is_active')->default(false);
            $table->boolean('is_banned')->default(false);
            $table->boolean('is_beta')->default(false);

            $table->string('stripe_access_token', 55)->nullable();
            $table->string('stripe_refresh_token', 55)->nullable();
            $table->string('stripe_secret_key', 55)->nullable();
            $table->string('stripe_publishable_key', 55)->nullable();
            $table->text('stripe_data_raw', 55)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('accounts');
    }
}
