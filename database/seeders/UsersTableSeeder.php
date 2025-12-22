<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $startIndex = 4_00_001; // 
        $totalUsers = 3_00_000; // 
        $batchSize  = 2000;
        $password   = bcrypt('password123');

        DB::statement('SET autocommit=0');
        DB::statement('SET unique_checks=0');
        DB::statement('SET foreign_key_checks=0');
        ini_set('memory_limit', '2G');

        $this->command->info("Seeding {$totalUsers} users starting from #{$startIndex} in batches of {$batchSize}...");
        $bar = $this->command->getOutput()->createProgressBar($totalUsers);
        $bar->start();

        $endIndex = $startIndex + $totalUsers - 1;

        for ($i = $startIndex; $i <= $endIndex; $i += $batchSize) {
            $users = [];
            $now = now();
            $end = min($i + $batchSize - 1, $endIndex);

            for ($j = $i; $j <= $end; $j++) {
                $users[] = [
                    'name'       => "Userd1 {$j}",
                    'email'      => "userd1{$j}@example.com",
                    'two_factor_secret' =>NULL,
                    'two_factor_recovery_codes' =>NULL,
                    'two_factor_confirmed_at' =>NULL,
                    'remember_token' =>NULL,
                    'password'   => $password,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            DB::table('users')->insert($users);
            $bar->advance(count($users));

            // free memory manually
            unset($users);
        }

        $bar->finish();
        $this->command->newLine(2);
        $this->command->info("✅ Done seeding {$totalUsers} more users (up to #{$endIndex})");

        DB::statement('SET autocommit=1');
        DB::statement('SET unique_checks=1');
        DB::statement('SET foreign_key_checks=1');
        DB::commit();
    }
}
