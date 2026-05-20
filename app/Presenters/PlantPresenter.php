<?php

namespace App\Presenters;

use App\Models\Checklist;
use App\Models\Comment;
use App\Models\Item;
use App\Models\Plant;
use App\Models\User;
use Carbon\Carbon;

class PlantPresenter
{
    public static function templateDetails(Plant $plant)
    {
        return view('template.dashboard.status.details', ['checklist' => $plant->currentChecklist(), 'item' => $plant->currentItem()]);
    }

    public static function templateTags(Plant $plant)
    {
        return view('template.plant.tags', ['plant' => $plant]);
    }

    public static function templateProperties(Plant $plant)
    {
        return view('template.plant.properties', ['plant' => $plant]);
    }

    public static function templateChecklists(Plant $plant)
    {
        return view('template.plant.checklists', ['plant' => $plant]);
    }

    public static function templateChecklist(Plant $plant, Checklist $checklist, ?Checklist $current)
    {
        return view('template.plant.checklist', ['template' => ChecklistPresenter::template($checklist, $plant, $current && $current->id == $checklist->id)]);
    }

    public static function templateChecklistTree(Plant $plant, ?Checklist $checklist = null, ?Checklist $current = null)
    {
        $template = [];
        if (! $current) {
            $current = $plant->currentChecklist();
        }
        foreach ($plant->stages as $stage) {
            if (! $stage->checklist) {
                continue;
            }

            $template[] = self::templateChecklist($plant, $stage->checklist, $current);
        }

        return implode('', $template);
    }

    public static function templateTimeline(Plant $plant)
    {
        $history = [];
        $tz = User::getUserTimezone(auth()->user());
        $created = Carbon::parse($plant->created_at, $tz);
        $history[$created->timestamp] = view('template.timeline.item', [
            'info' => view('template.timeline.item.info', ['date' => $created]),
            'content' => view('template.timeline.item.content', ['content' => 'Created']),
            'class' => 'mb-0',
        ]);

        if ($plant->history && $plant->history->count() > 0) {
            $hasWateringHistory = false;
            $hasCommentHistory = false;
            $hasPictureHistory = false;
            $checklistIds = [];
            $itemIds = [];
            $commentIds = [];
            $pictureIds = [];
            foreach ($plant->history as $entry) {
                $payload = json_decode($entry->data, true) ?: [];
                if (! empty($payload['checklist_id'])) {
                    $checklistIds[] = $payload['checklist_id'];
                }
                if (! empty($payload['item_id'])) {
                    $itemIds[] = $payload['item_id'];
                }
                if (! empty($payload['comment_id'])) {
                    $commentIds[] = $payload['comment_id'];
                }
                if (! empty($payload['picture_id'])) {
                    $pictureIds[] = $payload['picture_id'];
                }
                if (($payload['type'] ?? null) === 'watering') {
                    $hasWateringHistory = true;
                }
                if (in_array($payload['type'] ?? null, ['comment_add', 'comment_edit', 'comment_remove'], true)) {
                    $hasCommentHistory = true;
                }
                if (($payload['type'] ?? null) === 'picture_add') {
                    $hasPictureHistory = true;
                }
            }
            $checklists = Checklist::whereIn('id', array_unique($checklistIds))->get()->keyBy('id');
            $items = Item::whereIn('id', array_unique($itemIds))->get()->keyBy('id');
            $comments = Comment::whereIn('id', array_unique($commentIds))->get()->keyBy('id');
            $pictures = $plant->pictures->keyBy('id');

            foreach ($plant->history as $entry) {
                $payload = json_decode($entry->data, true) ?: [];
                $type = $payload['type'] ?? null;
                $date = Carbon::parse($entry->created_at, $tz);
                $content = null;
                $class = null;

                if (in_array($type, ['checklist_add', 'checklist_remove', 'checklist_initial_set', 'checklist_initial_remove'], true)) {
                    $checklistName = null;
                    if (! empty($payload['checklist_id']) && $checklists->has($payload['checklist_id'])) {
                        $checklistName = $checklists->get($payload['checklist_id'])->name;
                    }

                    $label = match ($type) {
                        'checklist_add' => 'Checklist added',
                        'checklist_remove' => 'Checklist removed',
                        'checklist_initial_set' => 'Initial checklist set',
                        'checklist_initial_remove' => 'Initial checklist removed',
                        default => 'Checklist update',
                    };

                    if ($checklistName) {
                        $label = sprintf('%s: %s', $label, $checklistName);
                    }

                    $content = view('template.timeline.item.content', ['content' => $label]);
                }

                if ($type === 'watering') {
                    $chemical = (bool) ($payload['chemical'] ?? false);
                    $content = view('template.timeline.item.content', ['content' => sprintf('Water with%s chemical', $chemical ? '' : 'out')]);
                    $class = $chemical ? 'w-chem' : 'wo-chem';
                }

                if (in_array($type, ['comment_add', 'comment_edit', 'comment_remove'], true)) {
                    $comment = null;
                    if (! empty($payload['comment_id']) && $comments->has($payload['comment_id'])) {
                        $comment = $comments->get($payload['comment_id']);
                    }

                    $label = match ($type) {
                        'comment_add' => 'Comment added',
                        'comment_edit' => 'Comment edited',
                        'comment_remove' => 'Comment removed',
                        default => 'Comment update',
                    };

                    if ($comment) {
                        $content = view('template.timeline.item.comment', ['comment' => $comment]);
                    } else {
                        $content = view('template.timeline.item.content', ['content' => $label]);
                    }
                }

                if ($type === 'picture_add') {
                    $picture = null;
                    if (! empty($payload['picture_id']) && $pictures->has($payload['picture_id'])) {
                        $picture = $pictures->get($payload['picture_id']);
                    }

                    if ($picture) {
                        $content = view('template.timeline.item.picture', ['picture' => $picture]);
                    } else {
                        $content = view('template.timeline.item.content', ['content' => 'Picture added']);
                    }
                }

                if (in_array($type, ['item_due', 'item_due_removed', 'item_toggle'], true)) {
                    $item = null;
                    if (! empty($payload['item_id']) && $items->has($payload['item_id'])) {
                        $item = $items->get($payload['item_id']);
                    }

                    if ($type === 'item_due') {
                        $due = $payload['due'] ?? null;
                        $label = $item ? sprintf('Due date set: %s', $item->name) : 'Due date set';
                        if ($due) {
                            $dueDate = Carbon::parse($due, $tz);
                            $label = sprintf('%s (%s)', $label, $dueDate->format('Y-m-d H:i'));
                        }
                        $content = view('template.timeline.item.content', ['content' => $label]);
                    } elseif ($type === 'item_due_removed') {
                        $label = $item ? sprintf('Due date removed: %s', $item->name) : 'Due date removed';
                        $content = view('template.timeline.item.content', ['content' => $label]);
                    } else {
                        $checked = (bool) ($payload['checked'] ?? false);
                        $label = $item ? sprintf('Item %s: %s', $checked ? 'checked' : 'unchecked', $item->name) : sprintf('Item %s', $checked ? 'checked' : 'unchecked');
                        $content = view('template.timeline.item.content', ['content' => $label]);
                    }
                }

                if ($content) {
                    $history[$date->timestamp] = view('template.timeline.item', [
                        'info' => view('template.timeline.item.info', ['date' => $date]),
                        'content' => $content,
                        'class' => $class,
                    ]);
                }
            }

            if (! $hasWateringHistory && $plant->waterings->count() > 0) {
                foreach ($plant->waterings as $watering) {
                    $date = Carbon::parse($watering->created_at, $tz);
                    $history[$date->timestamp] = view('template.timeline.item', [
                        'info' => view('template.timeline.item.info', ['date' => $date]),
                        'content' => view('template.timeline.item.content', ['content' => sprintf('Water with%s chemical', $watering->chemical ? '' : 'out')]),
                        'class' => $watering->chemical ? 'w-chem' : 'wo-chem',
                    ]);
                }
            }

            if (! $hasCommentHistory && $plant->comments->count() > 0) {
                foreach ($plant->comments as $comment) {
                    $date = Carbon::parse($comment->created_at, $tz);
                    $history[$date->timestamp] = view('template.timeline.item', [
                        'info' => view('template.timeline.item.info', ['date' => $date]),
                        'content' => view('template.timeline.item.comment', ['comment' => $comment]),
                        'class' => null,
                    ]);
                }
            }

            if (! $hasPictureHistory && $plant->pictures->count() > 0) {
                foreach ($plant->pictures as $picture) {
                    $date = Carbon::parse($picture->created_at, $tz);
                    $history[$date->timestamp] = view('template.timeline.item', [
                        'info' => view('template.timeline.item.info', ['date' => $date]),
                        'content' => view('template.timeline.item.picture', ['picture' => $picture]),
                        'class' => null,
                    ]);
                }
            }
        } else {
            if ($plant->waterings->count() > 0) {
                foreach ($plant->waterings as $watering) {
                    $date = Carbon::parse($watering->created_at, $tz);
                    $history[$date->timestamp] = view('template.timeline.item', [
                        'info' => view('template.timeline.item.info', ['date' => $date]),
                        'content' => view('template.timeline.item.content', ['content' => sprintf('Water with%s chemical', $watering->chemical ? '' : 'out')]),
                        'class' => $watering->chemical ? 'w-chem' : 'wo-chem',
                    ]);
                }
            }

            if ($plant->comments->count() > 0) {
                foreach ($plant->comments as $comment) {
                    $date = Carbon::parse($comment->created_at, $tz);
                    $history[$date->timestamp] = view('template.timeline.item', [
                        'info' => view('template.timeline.item.info', ['date' => $date]),
                        'content' => view('template.timeline.item.comment', ['comment' => $comment]),
                        'class' => null,
                    ]);
                }
            }

            if ($plant->pictures->count() > 0) {
                foreach ($plant->pictures as $picture) {
                    $date = Carbon::parse($picture->created_at, $tz);
                    $history[$date->timestamp] = view('template.timeline.item', [
                        'info' => view('template.timeline.item.info', ['date' => $date]),
                        'content' => view('template.timeline.item.picture', ['picture' => $picture]),
                        'class' => null,
                    ]);
                }
            }
        }

        if ($plant->checkedItems) {
            foreach ($plant->checkedItems as $item) {
                $date = Carbon::parse($item->pivot->checked, $tz);
                $history[$date->timestamp] = view('template.timeline.item', [
                    'info' => null,
                    'content' => view('template.timeline.period.content', ['content' => sprintf('%s<br/><i class="fas fa-check text-success fa-2xs me-2"></i>%s', $item->statut->name, $item->name), 'date' => $date, 'next' => null]),
                    'class' => 'period',
                ]);
            }
        }

        krsort($history);

        array_unshift($history, view('template.timeline.item', [
            'info' => null,
            'content' => view('template.timeline.period.content', ['content' => '<i class="fas fa-droplet fa-xs me-2"></i>Next watering', 'date' => null, 'next' => $plant->nextWateringChemical() ? 'biohazard text-danger' : 'water text-primary']),
            'class' => 'period',
        ]));

        return view('template.plant.timeline', ['history' => $history]);
    }

    public static function checklistProgress(Plant $plant): int
    {
        $checklist = $plant->currentChecklist();
        if (! $checklist) {
            return 0;
        }

        $total = $checklist->items()->count();
        if ($total === 0) {
            return 0;
        }

        $checked = $plant->items()->whereIn('item_id', $checklist->items()->pluck('id'))->wherePivotNotNull('checked')->count();

        return (int) round(($checked / $total) * 100);
    }

    public static function nextDue(Plant $plant): ?Carbon
    {
        $item = $plant->currentItem();
        if (! $item || ! $item->pivot || ! $item->pivot->due) {
            return null;
        }

        $tz = User::getUserTimezone(auth()->user());

        return Carbon::parse($item->pivot->due, $tz);
    }

    public static function tagsStyle($plants)
    {
        $template = [];
        if ($plants) {
            foreach ($plants as $plant) {
                foreach ($plant->tags as $tag) {
                    $template[$tag->id] = sprintf('.tag-%s { background-color:%s }', $tag->id, $tag->color);
                }
                foreach ($plant->strain->tags as $tag) {
                    $template[$tag->id] = sprintf('.tag-%s { background-color:%s }', $tag->id, $tag->color);
                }
            }
        }

        return implode(' ', $template);
    }

    public static function propertiesStyle($plants)
    {
        $template = [];
        if ($plants) {
            foreach ($plants as $plant) {
                foreach ($plant->properties as $property) {
                    $template[$property->id] = sprintf('.property-%s { background-color:%s }', $property->id, $property->color);
                }
                foreach ($plant->strain->properties as $property) {
                    $template[$property->id] = sprintf('.property-%s { background-color:%s }', $property->id, $property->color);
                }
            }
        }

        return implode(' ', $template);
    }
}
