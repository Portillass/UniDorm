<?php

namespace App\Services;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TenantService
{
    public function createTenantDatabase(User $user): Tenant
    {
        $dbName = 'tenant_' . $user->id;
        $dbUser = 'tenant_' . Str::random(8);
        $dbPassword = Str::random(16);

        // Create database
        DB::statement("CREATE DATABASE IF NOT EXISTS {$dbName}");

        // Create user and grant privileges
        DB::statement("CREATE USER '{$dbUser}'@'%' IDENTIFIED BY '{$dbPassword}'");
        DB::statement("GRANT ALL PRIVILEGES ON {$dbName}.* TO '{$dbUser}'@'%'");
        DB::statement("FLUSH PRIVILEGES");

        // Create tenant record
        $tenant = Tenant::create([
            'user_id' => $user->id,
            'db_name' => $dbName,
            'db_host' => config('database.connections.mysql.host'),
            'db_user' => $dbUser,
            'db_password' => $dbPassword,
            'is_active' => true,
        ]);

        // Run tenant migrations
        $this->runTenantMigrations($tenant);

        return $tenant;
    }

    protected function runTenantMigrations(Tenant $tenant): void
    {
        // Set tenant connection
        config([
            'database.connections.tenant.database' => $tenant->db_name,
            'database.connections.tenant.username' => $tenant->db_user,
            'database.connections.tenant.password' => $tenant->db_password,
        ]);

        DB::purge('tenant');
        DB::reconnect('tenant');

        // Run migrations
        $migrationsPath = database_path('migrations/tenant');
        if (file_exists($migrationsPath)) {
            $this->runMigrations($migrationsPath);
        }
    }

    protected function runMigrations(string $path): void
    {
        $migrations = glob($path . '/*.php');
        foreach ($migrations as $migration) {
            require_once $migration;
            $migrationClass = $this->getMigrationClass($migration);
            (new $migrationClass)->up();
        }
    }

    protected function getMigrationClass(string $path): string
    {
        $fileName = basename($path, '.php');
        return '\\Database\\Migrations\\Tenant\\' . str_replace('.php', '', $fileName);
    }
} 