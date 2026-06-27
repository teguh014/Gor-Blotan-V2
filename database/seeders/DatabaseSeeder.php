<?php

namespace Database\Seeders;

use App\Models\Court;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin User ────────────────────────────────────────────────────────
        User::create([
            'name'     => 'Administrator',
            'email'    => 'admin@badmintonhall.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        // ── Demo Customer ─────────────────────────────────────────────────────
        User::create([
            'name'     => 'Budi Santoso',
            'email'    => 'customer@badmintonhall.com',
            'password' => Hash::make('password'),
            'role'     => 'customer',
        ]);

        // ── Demo Courts ───────────────────────────────────────────────────────
        $courts = [
            [
                'name'           => 'Lapangan A',
                'description'    => 'Lapangan badminton standar internasional dengan lantai kayu parket premium.',
                'price_per_hour' => 75000,
                'facilities'     => ['AC', 'Lampu LED', 'Loker', 'Shower'],
                'is_active'      => true,
            ],
            [
                'name'           => 'Lapangan B',
                'description'    => 'Lapangan indoor dengan pencahayaan optimal dan ventilasi udara terbaik.',
                'price_per_hour' => 60000,
                'facilities'     => ['Kipas Angin', 'Lampu LED', 'Parkir Luas'],
                'is_active'      => true,
            ],
            [
                'name'           => 'Lapangan VIP',
                'description'    => 'Lapangan eksklusif dengan fasilitas lengkap dan ruang tunggu ber-AC.',
                'price_per_hour' => 120000,
                'facilities'     => ['AC', 'Lampu LED', 'Loker', 'Shower', 'Ruang VIP', 'Minuman Gratis'],
                'is_active'      => true,
            ],
        ];

        foreach ($courts as $court) {
            Court::create($court);
        }
    }
}
