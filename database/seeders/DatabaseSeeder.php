<?php

namespace Database\Seeders;

use App\Models\ProductInfo;
use App\Models\ProductMaster;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create([
            'emp_id' => 22,
            'name' => 'Abhinav Namdeo',
            'email' => 'abhay22@gmail.com',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'phone' => '9898989898',
            'joined_at' => now(),
            'is_admin' => 1,
            'is_approve' => 1,
            'created_by' => 1,
        ]);

        $mouse = ProductMaster::create([
            'name' => 'Mouse',
            'stock' => 3,
            'type' => 'Electronic',
            'alloted' => 0,
            'created_by' => 1,
        ]);

        $cpu = ProductMaster::create([
            'name' => 'CPU',
            'stock' => 2,
            'type' => 'Electronic',
            'alloted' => 1,
            'created_by' => 1,
        ]);

        $tea = ProductMaster::create([
            'name' => 'Tea',
            'stock' => 1,
            'type' => 'Household',
            'alloted' => 0,
            'created_by' => 1,
        ]);

        $mousep = Purchase::create([
            'product_master_id' => $mouse->id,
            'bill_no' => Str::random(9),
            'company' => 'HP',
            'quantity' => $mouse->stock,
            'cost' => 9000.00,
            'date' => now(),
            'created_by' => 1
        ]);

        $cpup = Purchase::create([
            'product_master_id' => $cpu->id,
            'bill_no' => Str::random(9),
            'company' => 'Dell',
            'quantity' => $cpu->stock,
            'cost' => 9000.00,
            'date' => now(),
            'created_by' => 1
        ]);

        $teap = Purchase::create([
            'product_master_id' => $tea->id,
            'bill_no' => Str::random(9),
            'company' => 'Assam',
            'quantity' => $tea->stock,
            'cost' => 9000.00,
            'date' => now(),
            'created_by' => 1
        ]);

        ProductInfo::create([
            'purchase_id' => $mousep->id,
            'ref_no' => 'mouse1'
        ]);
        ProductInfo::create([
            'purchase_id' => $mousep->id,
            'ref_no' => 'mouse2'
        ]);
        ProductInfo::create([
            'purchase_id' => $mousep->id,
            'ref_no' => 'mouse3'
        ]);

        ProductInfo::create([
            'purchase_id' => $cpup->id,
            'ref_no' => 'cpu1'
        ]);
        ProductInfo::create([
            'purchase_id' => $cpup->id,
            'ref_no' => 'cpu2'
        ]);

        ProductInfo::create([
            'purchase_id' => $teap->id,
            'ref_no' => 'tea1'
        ]);

        User::factory(5)->create();
    }
}
