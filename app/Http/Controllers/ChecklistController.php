<?php

namespace App\Http\Controllers;

use App\Models\Checklist;
use Illuminate\Http\Request;

class ChecklistController extends Controller
{
    public function getItems(Request $request, $id)
    {
        $checklist = Checklist::findOrFail($id);
        $this->authorize('view', $checklist);

        return $checklist->items;
    }
}
