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
        Schema::table('jobadder_details', function (Blueprint $table) {
            $table->dropColumn('client_id');
            $table->dropColumn('client_secret');
            $table->dropColumn('connection_id');
            $table->longText('refresh_token_response')->nullable()->after('refresh_token');
            $table->longText('auth_response')->nullable()->after('refresh_token');
            $table->string('token')->nullable()->after('refresh_token');
            $table->string('state')->nullable()->after('refresh_token');
            $table->string('code')->nullable()->after('refresh_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jobadder_details', function (Blueprint $table) {
            //
        });
    }
};
