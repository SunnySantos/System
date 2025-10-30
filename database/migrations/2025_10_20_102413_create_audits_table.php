<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('audits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); // who did it
            $table->string('event'); // created, updated, deleted
            $table->string('auditable_type'); // model class
            $table->unsignedBigInteger('auditable_id'); // model id
            $table->json('old_values')->nullable(); // old data
            $table->json('changed_values')->nullable(); // changed data
            $table->json('new_values')->nullable(); // new data
            $table->string('message')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->timestamps();
            $table->index(['auditable_type', 'auditable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audits');
    }
};
