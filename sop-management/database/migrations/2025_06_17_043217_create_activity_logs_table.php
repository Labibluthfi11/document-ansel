<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivityLogsTable extends Migration
{
    public function up()
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('action');
            $table->string('description')->nullable();
            $table->unsignedBigInteger('document_id')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->timestamps(); // <--- INI WAJIB, otomatis isi created_at dan updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('activity_logs');
    }
}
