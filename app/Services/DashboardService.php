<?php

namespace App\Services;

use App\Models\Dashboard;
use Illuminate\Support\Arr;

class DashboardService
{
    public function create(array $data): Dashboard
    {
        $dashboard = new Dashboard;
        $dashboard->fill(Arr::only($data, ['name', 'description', 'color']));
        $dashboard->save();

        $dashboard->users()->attach(auth()->id(), ['creator' => true]);

        $users = Arr::get($data, 'users', []);
        if ($users) {
            $dashboard->users()->attach($users);
        }

        return $dashboard;
    }

    public function update(Dashboard $dashboard, array $data): Dashboard
    {
        $dashboard->fill(Arr::only($data, ['name', 'description', 'color']));
        $dashboard->save();

        $users = Arr::get($data, 'users', []);
        $dashboard->users()->wherePivot('creator', false)->sync($users);

        return $dashboard;
    }
}
