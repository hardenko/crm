<?php

use App\Enums\ClientLegalFormEnum;
use App\Enums\ClientTypeEnum;
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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone', 13)->unique();
            $table->string('comments')->nullable();
            $table->enum('legal_form', ClientLegalFormEnum::values())->nullable();
            $table->string('bank_account')->nullable();
            $table->enum('client_type', ClientTypeEnum::values())->nullable();
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
