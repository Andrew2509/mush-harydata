<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pending_refunds', function (Blueprint $table) {
            $table->id();
            $table->string('order_id');
            $table->string('no_pembeli');
            $table->decimal('jumlah', 15, 2);
            $table->string('layanan')->nullable();
            $table->enum('status', ['pending', 'claimed', 'expired'])->default('pending');
            $table->string('claimed_by')->nullable();
            $table->timestamps();

            $table->index('no_pembeli');
            $table->index('status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pending_refunds');
    }
};
