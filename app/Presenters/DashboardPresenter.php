<?php

namespace App\Presenters;

class DashboardPresenter
{
    public static function templateSwitch()
    {
        $options = [];
        foreach (auth()->user()->dashboards as $board) {
            $options[] = view('template.form.select.option', [
                'value' => $board->id,
                'title' => $board->name,
                'selected' => $board->id === session('dashboard_id'),
            ]);
        }

        return view('template.form.floating', [
            'type' => 'select',
            'class' => ['input' => 'select2'],
            'id' => 'dashboard',
            'name' => 'dashboard',
            'extra' => ['input' => 'required autofocus'],
            'options' => implode('', $options),
            'label' => 'Dashboard',
            'placeholder' => 'Switch dashboard',
        ]);
    }
}
