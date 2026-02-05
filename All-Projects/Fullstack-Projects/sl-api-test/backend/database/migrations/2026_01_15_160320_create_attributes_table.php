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
        Schema::create('attribute_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->comment('color, ram, storage,...');
            $table->string('display_name', 100)->comment('Màu sắc, RAM, Dung lượng,...');
            $table->integer('display_order')->default(0);
            $table->timestamps();
        });

        Schema::create('attributes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attribute_group_id')->constrained('attribute_groups')->onDelete('cascade');
            $table->string('value', 100)->comment('Đen, Trắng, 8GB, 256GB,...');
            $table->string('color_code', 7)->nullable()->comment('#000000, #FFFFFF,...');
            $table->integer('display_order')->default(0);
            $table->timestamps();

            $table->index('attribute_group_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attributes');
        Schema::dropIfExists('attribute_groups');
    }
};
