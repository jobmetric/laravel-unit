<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use JobMetric\Unit\Enums\UnitTypeEnum;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create(config('unit.tables.unit'), function (Blueprint $table) {
            $table->id();

            $table->string('type')->index();
            /**
             * unit type
             *
             * value: weight, length, currency, number, crypto, ...
             * use: @extends UnitTypeEnum
             */

            $table->decimal('value', 20, 10)->default(0);

            $table->boolean('status')->default(true)->index();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists(config('unit.tables.unit'));
    }
};
