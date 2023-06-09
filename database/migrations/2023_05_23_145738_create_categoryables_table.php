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
        Schema::create("categoryables", function (Blueprint $table) {
            $table->bigInteger("category_id")->unsigned();
            $table->bigInteger("categoryable_id")->unsigned();
            $table->string("categoryable_type");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("categoryables");
    }
};
