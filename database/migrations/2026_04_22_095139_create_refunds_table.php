<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refunds', function (Blueprint $table) {
            $table->id();

            $table->foreignId('payment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();

            $table->string('refund_code')->unique();

            $table->decimal('refund_amount', 10, 2);

            $table->string('refund_reason')->nullable();

            $table->enum('status', [
                'pending',
                'approved',
                'rejected',
                'completed',
                'failed'
            ])->default('pending');

            $table->timestamp('refunded_at')->nullable();

            // which admin processed
            $table->foreignId('processed_by')->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('refunds');
    }
}
