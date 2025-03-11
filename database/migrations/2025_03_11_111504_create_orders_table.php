<?php

use App\Enums\OrderStatusType;
use App\Enums\PaymentStatusType;
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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products');
            $table->integer('quantity');
            $table->decimal('total_price', 10, 2);
            $table->foreignId('payer_id')->constrained('clients')->where('client_type', 'payer');
            $table->foreignId('receiver_id')->constrained('clients')->where('client_type', 'receiver');
            $table->enum('payment_status', PaymentStatusType::values());
            $table->enum('order_status', OrderStatusType::values());
            $table->text('comments')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
