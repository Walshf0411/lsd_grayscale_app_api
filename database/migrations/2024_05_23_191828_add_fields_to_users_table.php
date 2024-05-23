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
        Schema::table('users', function (Blueprint $table) {
            $table->string("first_name")->after("name")->nullable();
            $table->string("last_name")->after("first_name")->nullable();
            $table->date("date_of_birth")->after("last_name")->nullable();
            $table->string("gender")->after("date_of_birth")->nullable();
            $table->string("mobile_number")->after("gender")->nullable();

            $table->dropColumn("name");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->after("id");

            $table->dropColumn(["first_name", "last_name", "date_of_birth", "gender", "mobile_number"]);
        });
    }
};
