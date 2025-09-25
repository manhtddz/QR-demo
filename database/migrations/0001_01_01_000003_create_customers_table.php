<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->increments('id')->index();
            $table->string('name', 128);
        });

        Schema::create('contracts', function (Blueprint $table) {
            $table->increments('id')->index();
            $table->smallInteger('customer_id')->index();
            $table->string('contract_details');
            $table->decimal('contract_amount', 10, 2);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
        Schema::dropIfExists('contracts');
    }
};
