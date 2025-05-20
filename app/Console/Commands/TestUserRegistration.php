<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class TestUserRegistration extends Command
{
    protected $signature = 'test:user-registration';
    protected $description = 'Test user registration functionality';

    public function handle()
    {
        $this->info('Testing user registration...');
        
        // Verificar se a tabela users existe
        if (!Schema::hasTable('users')) {
            $this->error('Table users does not exist!');
            return 1;
        }
        
        // Verificar as colunas da tabela users
        $columns = Schema::getColumnListing('users');
        $this->info('Columns in users table: ' . implode(', ', $columns));
        
        // Verificar se a coluna username existe
        if (!in_array('username', $columns)) {
            $this->error('Column username does not exist in users table!');
            return 1;
        }
        
        // Verificar se a coluna role existe
        if (!in_array('role', $columns)) {
            $this->error('Column role does not exist in users table!');
            return 1;
        }
        
        try {
            // Criar um usuário de teste
            $user = new User();
            $user->name = 'Test User';
            $user->email = 'test' . time() . '@example.com';
            $user->username = 'testuser' . time();
            $user->password = Hash::make('password');
            
            // Adicionar role apenas se a coluna existir
            if (in_array('role', $columns)) {
                $user->role = 'visitante';
            }
            
            $user->save();
            
            $this->info('User created successfully!');
            $this->info('User ID: ' . $user->id);
            $this->info('User name: ' . $user->name);
            $this->info('User email: ' . $user->email);
            $this->info('User username: ' . $user->username);
            
            if (in_array('role', $columns)) {
                $this->info('User role: ' . $user->role);
            }
            
            return 0;
        } catch (\Exception $e) {
            $this->error('Error creating user: ' . $e->getMessage());
            return 1;
        }
    }
}
