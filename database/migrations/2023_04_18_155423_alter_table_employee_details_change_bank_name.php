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
        Schema::table('employee_details', function (Blueprint $table) {
            $table->string('sort_code')->after('date_of_joining')->nullable();
            $table->string('account_number')->after('date_of_joining')->nullable();
            $table->string('bank_name')->after('date_of_joining')->nullable();
            $table->longText('emp_picture')->after('date_of_joining')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_details', function (Blueprint $table) {
            //
        });
    }
};
