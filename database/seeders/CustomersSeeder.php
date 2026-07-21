<?php

namespace Database\Seeders;

use App\Models\Customers;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = [];
        for ($i=0; $i < 10; $i++) {
            $customers[] = [
                'name' => 'customer ' . $i,
                'email' => 'customer' . $i . '@example.com',
                'phone' => fake()->unique()->phoneNumber(),
            ];
        }

        Customers::upsert($customers, ['email'], ['name', 'email']);
    }
}
