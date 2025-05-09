<?php

use Illuminate\Support\Facades\Cache;
use App\Models\Payments;

it('caches the payments list', function () {

    Cache::shouldReceive('remember')
        ->once()
        ->with('payments_list', 60, \Closure::class)
        ->andReturn(collect([
            ['id' => 1, 'amount' => 1000],
            ['id' => 2, 'amount' => 2000],
        ]));

    $payments = Cache::remember('payments_list', 60, function () {
        return Payments::all();
    });

    expect($payments)->toHaveCount(2);
    expect($payments->first())->toMatchArray(['id' => 1, 'amount' => 1000]);
});
