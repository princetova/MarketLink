<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->string('phone', 32)->nullable()->after('email');
            $table->string('role', 20)->nullable()->index()->after('phone');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropIndex(['role']);
            $table->dropColumn(['phone', 'role']);
        });
    }
};
