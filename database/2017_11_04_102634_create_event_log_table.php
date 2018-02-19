<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventLogTable extends Migration
{

    public function up(): void
    {
        Schema::create('event_log', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('stream');
            $table->string('real_stream_name');
            $table->json('payload');
            $table->integer('version', false, 'true');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_log');
    }
}
