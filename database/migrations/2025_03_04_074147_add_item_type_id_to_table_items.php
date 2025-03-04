<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->foreignId('item_type_id')
                ->after('name')
                ->constrained('type_items')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        //
    }
};
