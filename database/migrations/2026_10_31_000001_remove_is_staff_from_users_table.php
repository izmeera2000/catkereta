<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');
        $rolePivot = $columnNames['role_pivot_key'] ?? 'role_id';
        $modelMorphKey = $columnNames['model_morph_key'] ?? 'model_id';

        $roleId = DB::table($tableNames['roles'])
            ->where('name', 'staff')
            ->where('guard_name', 'web')
            ->value('id');

        if ($roleId === null) {
            $roleId = DB::table($tableNames['roles'])->insertGetId([
                'name' => 'staff',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        DB::table('users')
            ->where('is_staff', true)
            ->pluck('id')
            ->each(function ($userId) use ($tableNames, $rolePivot, $modelMorphKey, $roleId) {
                DB::table($tableNames['model_has_roles'])->insertOrIgnore([
                    $rolePivot => $roleId,
                    $modelMorphKey => $userId,
                    'model_type' => \App\Models\User::class,
                ]);
            });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_staff');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_staff')->default(false);
        });

        $tableNames = config('permission.table_names');
        $rolePivot = config('permission.column_names.role_pivot_key') ?? 'role_id';
        $modelMorphKey = config('permission.column_names.model_morph_key') ?? 'model_id';
        $staffRoleId = DB::table($tableNames['roles'])
            ->where('name', 'staff')
            ->where('guard_name', 'web')
            ->value('id');

        if ($staffRoleId !== null) {
            DB::table('users')
                ->whereIn('id', DB::table($tableNames['model_has_roles'])
                    ->select($modelMorphKey)
                    ->where($rolePivot, $staffRoleId)
                    ->where('model_type', \App\Models\User::class))
                ->update(['is_staff' => true]);
        }
    }
};
