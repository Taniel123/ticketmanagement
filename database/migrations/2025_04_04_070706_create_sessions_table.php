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
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id', 255)->primary(); // Change id to string
            $table->string('user_id')->nullable(); // to store the user associated with the session
            $table->text('payload'); // to store the session data as a serialized string
            $table->integer('last_activity'); // to store the last activity time of the session
            $table->string('ip_address')->nullable(); // to store the IP address
            $table->string('user_agent')->nullable(); // to store the user agent (browser info)
            $table->timestamps(); // created_at and updated_at timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
    }
};
