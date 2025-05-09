<?php

   namespace Database\Seeders;

   use Illuminate\Database\Seeder;
   use App\Models\User;
   use App\Models\Property;

   class PropertySeeder extends Seeder
   {
       public function run(): void
       {

           User::factory()->landlord()->count(10)->create()->each(function ($landlord) {
               Property::factory()->count(rand(1, 3))->create([
                   'user_id' => $landlord->id,
               ]);
           });

           $specificLandlord = User::factory()->landlord()->create([
               'name' => 'Prime Landlord',
               'email' => 'prime.landloerd@example.com',
           ]);
           Property::factory()->count(5)->create([
               'user_id' => $specificLandlord->id,
               'active' => true,
           ]);
       }
   }
