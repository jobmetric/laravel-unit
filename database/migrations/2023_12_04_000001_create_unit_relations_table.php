<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create(config('unit.tables.unit_relation'), function (Blueprint $table) {
            $table->foreignId('unit_id')->index()->constrained(config('unit.tables.unit'))->cascadeOnUpdate()->cascadeOnDelete();

            $table->morphs('unitable');
            /**
             * unitable to: any model
             */

            $table->string('type')->index();
            $table->decimal('value', 15, 8)->index();

            $table->dateTime('created_at')->index()->default(DB::raw('CURRENT_TIMESTAMP'));

            $table->unique([
                'unit_id',
                'unitable_type',
                'unitable_id',
                'type'
            ], 'UNIT_RELATION_UNIQUE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists(config('unit.tables.unit_relation'));
    }
};
