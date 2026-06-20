<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
 public function up()
    {
        Schema::create('ticket_transfer_requests', function (Blueprint $table) {
            $table->id();

            $table->foreignId('ticket_id')->constrained('tickets')->cascadeOnDelete();

            $table->foreignId('requested_by')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('from_technician_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('to_technician_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->text('reason')->nullable();

            $table->enum('status', [
                'pending',
                'approved',
                'rejected',
                'cancelled'
            ])->default('pending');

            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('approved_at')->nullable();

            $table->text('rejected_reason')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ticket_transfer_requests');
    }
};
