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
        Schema::create("testimonials", function (Blueprint $table) {
            $table->id();
            $table->text("body")->nullable();
            $table->string("stars", 10);
            $table->bigInteger("testimony_id")->unsigned();
            $table->string("testimony_type");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("testimonials");
    }
};
