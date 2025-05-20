<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Primeiro, vamos atualizar os valores existentes
        DB::table('coupons')
            ->where('type', 'percent')
            ->update(['type' => 'percentage']);

        // Agora, vamos modificar a coluna para aceitar os novos valores
        // Como não podemos alterar diretamente um ENUM no MySQL, precisamos recriar a coluna
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE coupons MODIFY COLUMN type ENUM('percentage', 'fixed') NOT NULL");
        } else {
            // Para outros bancos de dados, podemos tentar uma abordagem diferente
            Schema::table('coupons', function (Blueprint $table) {
                $table->string('type_temp')->default('percentage');
            });

            // Copiar os dados
            DB::table('coupons')->update([
                'type_temp' => DB::raw('type')
            ]);

            // Remover a coluna antiga e renomear a nova
            Schema::table('coupons', function (Blueprint $table) {
                $table->dropColumn('type');
            });

            Schema::table('coupons', function (Blueprint $table) {
                $table->renameColumn('type_temp', 'type');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Primeiro, vamos atualizar os valores existentes
        DB::table('coupons')
            ->where('type', 'percentage')
            ->update(['type' => 'percent']);

        // Agora, vamos modificar a coluna para aceitar os valores originais
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE coupons MODIFY COLUMN type ENUM('percent', 'fixed') NOT NULL");
        } else {
            // Para outros bancos de dados, podemos tentar uma abordagem diferente
            Schema::table('coupons', function (Blueprint $table) {
                $table->string('type_temp')->default('percent');
            });

            // Copiar os dados
            DB::table('coupons')->update([
                'type_temp' => DB::raw('type')
            ]);

            // Remover a coluna antiga e renomear a nova
            Schema::table('coupons', function (Blueprint $table) {
                $table->dropColumn('type');
            });

            Schema::table('coupons', function (Blueprint $table) {
                $table->renameColumn('type_temp', 'type');
            });
        }
    }
};
