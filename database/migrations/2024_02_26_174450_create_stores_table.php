<?php

use App\Models\User;
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
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained();
            $table->string('name')->nullable(false);
            $table->string('moto')->nullable(false);
            $table->string('street')->nullable(false);
            $table->string('city')->nullable(false);
            $table->string('country')->nullable(false);
            $table->string('line address 1')->nullable(false);
            $table->string('line address 2');
            $table->string('deal_description')->comment('azadi deal');
            $table->string('deal_operator')->comment('=, % etc');
            $table->integer('deal_value')->comment('10');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
