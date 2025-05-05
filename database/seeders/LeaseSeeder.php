<?php

namespace Database\Seeders;

use App\Models\Lease;
use App\Models\Unit;
use App\Models\Tenants;
use App\Models\Property;
use App\Models\User;
use Illuminate\Database\Seeder;

class LeaseSeeder extends Seeder
{
    public function run(): void
    {
        $activeTenants = Tenants::where('status', 'active')->take(15)->get();         $inactiveTenants = Tenants::where('status', 'inactive')->take(3)->get();

        
        $activeLeasesData = [];
        foreach (range(0, min(9, $activeTenants->count() - 1)) as $index) {
            $landlord = User::factory()->landlord()->create();
            $property = Property::factory()->create(['user_id' => $landlord->id]);
            $unit = Unit::factory()->state(['status' => 'occupied'])->create([
                'property_id' => $property->id,
            ]);
            $activeLeasesData[] = Lease::factory()->raw([
                'unit_id' => $unit->id,
                'tenant_id' => $activeTenants[$index]->id,
                'status' => 'active',
            ]);
        }
        Lease::insert($activeLeasesData);

        $specificLandlord = User::factory()->landlord()->create([
            'name' => 'Premier Landlord',
            'email' => 'premier.landlord@example.com',
        ]);
        $specificProperty = Property::factory()->create([
            'user_id' => $specificLandlord->id,
            'name' => 'Premier Apartments',
            'active' => true,
        ]);
        $specificUnit = Unit::factory()->create([
            'property_id' => $specificProperty->id,
            'unit_number' => 'Unit 301',
            'status' => 'occupied',
            'rent_amount' => 2200.00,
        ]);
        $specificTenant = Tenants::factory()->create([
            'name' => 'Alice Johnson',
            'email' => 'alice.johnson@example.com',
            'date_of_birth' => '1985-03-22',
            'id_number' => 'ID-12345678',
            'status' => 'active',
        ]);
        Lease::factory()->create([
            'unit_id' => $specificUnit->id,
            'tenant_id' => $specificTenant->id,
            'start_date' => '2025-01-01',
            'end_date' => '2025-12-31',
            'rent_amount' => 2200.00,
            'security_deposit' => 4400.00,
            'payment_day' => 1,
            'lease_type' => 'fixed-term',
            'status' => 'active',
            'terms' => 'Standard lease terms: No smoking, no subletting without approval.',
        ]);


        $upcomingLeasesData = [];
        foreach (range(10, min(14, $activeTenants->count() - 1)) as $index) {
            if (!isset($activeTenants[$index])) {
                continue;
            }
            $unit = Unit::factory()->state(['status' => 'reserved'])->create([
                'property_id' => Property::factory()->create([
                    'user_id' => User::factory()->landlord()->create()->id,
                ])->id,
            ]);
            $upcomingLeasesData[] = Lease::factory()->raw([
                'unit_id' => $unit->id,
                'tenant_id' => $activeTenants[$index]->id,
                'status' => 'upcoming',
            ]);
        }
        Lease::insert($upcomingLeasesData);
        foreach (range(0, min(2, $inactiveTenants->count() - 1)) as $index) {
            if (!isset($inactiveTenants[$index])) {
                continue;
            }
        $expiredLeasesData = [];
        foreach (range(0, min(2, $inactiveTenants->count() - 1)) as $index) {
            $unit = Unit::factory()->state(['status' => 'vacant'])->create([
                'property_id' => Property::factory()->create([
                    'user_id' => User::factory()->landlord()->create()->id,
                ])->id,
            ]);
            $expiredLeasesData[] = Lease::factory()->raw([
                'unit_id' => $unit->id,
                'tenant_id' => $inactiveTenants[$index]->id,
                'start_date' => '2023-01-01',
                'end_date' => '2023-12-31',
                'status' => 'expired',
            ]);
        }
        Lease::insert($expiredLeasesData);
    }
}
}
