<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $totalUsers = 100;
        $password = Hash::make('password123');

        $this->command->info("Seeding {$totalUsers} users...");
        $bar = $this->command->getOutput()->createProgressBar($totalUsers);
        $bar->start();

        for ($i = 1; $i <= $totalUsers; $i++) {
            User::create([
                'id' => Str::uuid()->toString(),
                'name' => "User {$i}",
                'email' => "user{$i}@example.com",
                'password' => $password,
                'email_verified_at' => now(),
                'is_admin' => false,
            ]);
            $bar->advance();
        }

        $bar->finish();
        $this->command->newLine(2);
        $this->command->info("✅ Done seeding {$totalUsers} users");
    }
}
