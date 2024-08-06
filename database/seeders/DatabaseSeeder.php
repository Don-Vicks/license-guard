<?php

namespace Database\Seeders;

use App\Models\LicenseType;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Factories\LicenseTypeFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        // Ensure roles are created before assigning them to users
        $this->call(RoleSeeder::class);
        // Create multiple license types with specific attributes
        foreach([[
            'name' => 'Basic Plan',
            'amount' => '429.99',
            'duration' => 'monthly',
            'status' => true,
        ],
                    [
                        'name' => 'Standard Plan',
                        'amount' => '1500.99',
                        'duration' => 'quarterly',
                        'status' => true,
                    ],
                    [
                        'name' => 'Dedicated Plan',
                        'amount' => '899.99',
                        'duration' => 'bi-yearly',
                        'status' => true,
                    ],
                    [
                        'name' => 'Monthly Plan',
                        'amount' => '600.99',
                        'duration' => 'monthly',
                        'status' => true,
                    ],
                    [
                        'name' => 'Yearly Plan',
                        'amount' => '1990.99',
                        'duration' => 'yearly',
                        'status' => true,
                    ],
                    [
                        'name' => 'Daily Plan',
                        'amount' => '40.99',
                        'duration' => 'daily',
                        'status' => true,
                    ],
                    [
                        'name' => 'Weekly Plan',
                        'amount' => '100.99',
                        'duration' => 'weekly',
                        'status' => true,
                    ]
                    ,
                    [
                        'name' => 'Premium Plan',
                        'amount' => '12999.99',
                        'duration' => 'annually',
                        'status' => true,
                    ],
                    [
                        'name' => 'Enterprise Plan',
                        'amount' => '24999.99',
                        'duration' => 'annually',
                        'status' => true,
                    ],
                    [
                        'name' => 'Trial Plan',
                        'amount' => '0.00',
                        'duration' => 'weekly',
                        'status' => false,
                    ],] as $type)
        LicenseType::factory()->create(
            $type
        );
    }
}
