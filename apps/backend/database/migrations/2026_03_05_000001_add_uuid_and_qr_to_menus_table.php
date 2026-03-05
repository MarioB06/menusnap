<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('menus', 'uuid')) {
            Schema::table('menus', function (Blueprint $table) {
                $table->string('uuid', 36)->default('')->after('id');
            });
        }

        if (! Schema::hasColumn('menus', 'qr_code_path')) {
            Schema::table('menus', function (Blueprint $table) {
                $table->string('qr_code_path')->nullable()->after('sort_order');
            });
        }

        // Backfill existing menus with UUIDs
        foreach (DB::table('menus')->whereNull('uuid')->orWhere('uuid', '')->get() as $menu) {
            DB::table('menus')->where('id', $menu->id)->update(['uuid' => (string) Str::uuid()]);
        }

        // Now add unique index
        Schema::table('menus', function (Blueprint $table) {
            $table->unique('uuid');
        });
    }

    public function down(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->dropUnique(['uuid']);
            $table->dropColumn(['uuid', 'qr_code_path']);
        });
    }
};
