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

            $table->enum('type', UnitTypeEnum::values())->index();

            $table->decimal('value', 15, 8)->default(0);

            $table->boolean('status')->default(true)->index();

            $table->softDeletes();
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