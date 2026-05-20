<?php

namespace App\Presenters;

use App\Models\Checklist;
use App\Models\Plant;
use App\Models\User;
use Carbon\Carbon;

class ChecklistPresenter
{
    public static function template(Checklist $checklist, ?Plant $plant = null, bool $active = false)
    {
        return view('template.checklist.card', ['checklist' => $checklist, 'plant' => $plant, 'active' => $active, 'completed' => $plant ? $checklist->isCompleted($plant) : false]);
    }

    private static function templateItemLine($item, $plant, $page, $current)
    {
        $route = sprintf('template.checklist.item.line.%s', $page);
        $tz = User::getUserTimezone(auth()->user());

        $plantItem = $plant->items()->where('item_id', $item->id)->first();

        $now = Carbon::now($tz);
        $flush = $plantItem && $plantItem->pivot->flush ? true : false;
        $due =
          $plantItem && $plantItem->pivot->due
          ? Carbon::parse($plantItem->pivot->due, $tz)
          : null;
        $checked =
          $plantItem && $plantItem->pivot->checked
          ? Carbon::parse($plantItem->pivot->checked, $tz)
          : null;

        $dayBeforeDue = $due ? Carbon::parse($due, $tz)->subDay() : null;
        $restMoreThan1Day = $due ? $now->lessThan($dayBeforeDue) : null;
        $lessThan24h = $due ? $now->diffInHours($due, false) < 24 && $now->diffInHours($due, false) > 0 : null;

        return view($route, $page === 'detail' ? ['item' => $item, 'plant' => $plant, 'checklist' => $item->checklist, 'current' => $current && ($current->id === $item->id), 'hours' => ['due' => $due, 'checked' => $checked], 'conditions' => ['restMoreThan1Day' => $restMoreThan1Day, 'lessThan24h' => $lessThan24h], 'flush' => $flush] : ['item' => $item, 'plant' => $plant, 'flush' => $flush]);
    }

    public static function templateItemsTree(Plant $plant, Checklist $checklist, $page, $current = null, $item = null)
    {
        $template = [];
        if (! $item) {
            $item = $checklist->firstItem();
        }
        if (! $current) {
            $current = $plant->currentItem();
        }

        if (! $item) {
            return '';
        }

        $template[] = self::templateItemLine($item, $plant, $page, $current);
        if ($child = $item->child) {
            $template[] = self::templateItemsTree($plant, $checklist, $page, $current, $child);
        }

        return implode('', $template);
    }
}
