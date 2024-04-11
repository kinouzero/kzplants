<?php

namespace App\Http\Controllers;

use App\Models\Dashboard;
use App\Models\Plant;
use App\Models\Statut;
use App\Models\Strain;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller {

  // Views
  public function dashboard() {
    $dashboard = Dashboard::getCurrentDashboard();
    $plants    = $dashboard ? $dashboard->plants()->get() : null;
    $strains   = Strain::all();
    $status    = Statut::all();
    $style     = $plants ? sprintf('<style>%s</style>', implode(' ', [Plant::tagsStyle($plants), Plant::propertiesStyle($plants)])) : '';
    $switch    = Dashboard::templateSwitch();

    return view('dashboard', compact('dashboard', 'plants', 'strains', 'status', 'style', 'switch'));
  }

  public function index() {
    /** @var User $user */ // Hack for undefined method issue in vscode
    $user       = auth()->user();
    $dashboards = $user->isAdmin() ? Dashboard::all() : $user->dashboards();
    $default    = Dashboard::getDefault($user->id);

    return view('dashboard.index', compact('dashboards', 'default'));
  }

  public function create() {
    $dashboard = null;
    $users     = User::all();

    $title = 'Create new dashboard';

    $options   = [];
    foreach ($users as $user) {
      if ($user->id === auth()->user()->id || $dashboard && $dashboard->creator()->id === $user->id) continue;
      $options[] = view('template.form.select.option', ['value' => $user->id, 'title' => $user->name, 'selected' => false]);
    }

    return view('dashboard.edit', compact('dashboard', 'options', 'title'));
  }

  public function edit($id) {
    $dashboard = Dashboard::findOrFail($id);
    $users     = User::all();

    $title = sprintf('Edit dashboard: %s', $dashboard->name);

    $options   = [];
    foreach ($users as $user) {
      if ($user->id === auth()->user()->id || $dashboard && $dashboard->creator()->id === $user->id) continue;
      $options[] = view('template.form.select.option', ['value' => $user->id, 'title' => $user->name, 'selected' => $dashboard->users()->where('id', $user->id)->exists()]);
    }

    return view('dashboard.edit', compact('dashboard', 'options', 'title'));
  }

  public function detail($id) {
    $dashboard = Dashboard::findOrFail($id);

    return view('dashboard.detail', compact('dashboard'));
  }

  // Actions
  public function store(Request $request) {
    $dashboard = new Dashboard();

    $args = $request->all();

    $validatedData = $request->validate([
      'name'        => 'required|string',
      'description' => 'string',
      'color'       => 'string',
    ]);

    $dashboard->name        = $validatedData['name'];
    $dashboard->description = $validatedData['description'];
    $dashboard->color       = $validatedData['color'];
    $dashboard->save();

    // Creator
    $dashboard->users()->attach(auth()->user()->id, ['creator' => true]);

    // Users
    $users = isset($args['users']) ? User::whereIn('id', $args['users'])->get() : null;
    if ($users && $users->count() > 0) $dashboard->users()->attach($users->pluck('id')->toArray());

    return back()->with('success', 'Dashboard created successfully.');
  }

  public function update(Request $request, $id) {
    $dashboard = Dashboard::findOrFail($id);

    $args = $request->all();

    // Users
    $users = isset($args['users']) ? User::whereIn('id', $args['users'])->get() : null;
    $dashboard->users()->wherePivot('creator', false)->sync($users ? $users->pluck('id')->toArray() : []);
    unset($args['users']);

    $dashboard->update($args);

    return back()->with('success', 'Dashboard updated successfully.');
  }

  public function destroy($id) {
    $dashboard = Dashboard::findOrFail($id);
    $dashboard->delete();

    return back()->with('success', 'Dashboard deleted successfully.');
  }

  public function default($id) {
    /** @var User $user */ // Hack for undefined method issue in vscode
    $user = auth()->user();
    $user->dashboards()->updateExistingPivot($user->id, ['default' => false]);

    $dashboard = Dashboard::findOrFail($id);
    $dashboard->users()->updateExistingPivot($user->id, ['default' => true]);

    return back()->with('success', 'Default dashboard set successfully.');
  }

  /**
   * Switch dashboard
   */
  public function switch(Request $request) {
    $dashboard = Dashboard::findOrFail($request->dashboard);
    session()->put('dashboard_id', $dashboard->id);

    return back()->with('success', 'Dashboard switched successfully.');
  }

  /**
   * Get chart data
   */
  public function getChart($type) {
    $dashboard = Dashboard::getCurrentDashboard();
    $plants    = $dashboard ? $dashboard->plants()->get() : null;

    $data = [];
    switch ($type) {
      case 'status':
        $status = Statut::all();

        foreach ($status as $statut) $data[$statut->id] = [
          'name'  => $statut->name,
          'count' => 0,
          'color' => $statut->color
        ];
        foreach ($plants as $plant) $data[$plant->statut->id] = [
          'name'  => $plant->statut->name,
          'count' => ++$data[$plant->statut->id]['count'],
          'color' => $plant->statut->color
        ];
        break;
      case 'watering':
        $chem   = '#e74a3b';
        $noChem = '#4e73df';

        $countChem = $countNoChem = 0;
        foreach ($plants as $plant) {
          $countChem += $plant->waterWithChemical();
          $countNoChem += $plant->waterWithoutChemical();
        }

        $data = [
          ['name' => 'Chemical', 'count' => $countChem, 'color' => $chem],
          ['name' => 'No Chemical', 'count' => $countNoChem, 'color' => $noChem]
        ];
        break;
    }

    return response()->json(array_values($data));
  }
}
