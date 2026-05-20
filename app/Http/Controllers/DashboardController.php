<?php

namespace App\Http\Controllers;

use App\Http\Requests\DashboardStoreRequest;
use App\Http\Requests\DashboardUpdateRequest;
use App\Models\Dashboard;
use App\Models\Statut;
use App\Models\Strain;
use App\Models\User;
use App\Presenters\DashboardPresenter;
use App\Presenters\PlantPresenter;
use App\Repositories\DashboardRepository;
use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    // Views
    public function dashboard()
    {
        $dashboard = Dashboard::getCurrentDashboard();
        if ($dashboard) {
            $this->authorize('view', $dashboard);
        }
        $plants = $dashboard ? $dashboard->plants()->with(['statut', 'tags', 'properties', 'strain.tags', 'strain.properties', 'stages.checklist'])->get() : null;
        $strains = Strain::all();
        $status = Statut::all();
        $style = $plants ? sprintf('<style>%s</style>', implode(' ', [PlantPresenter::tagsStyle($plants), PlantPresenter::propertiesStyle($plants)])) : '';
        $switch = DashboardPresenter::templateSwitch();

        return view('dashboard', compact('dashboard', 'plants', 'strains', 'status', 'style', 'switch'));
    }

    public function today()
    {
        $dashboard = Dashboard::getCurrentDashboard();
        if ($dashboard) {
            $this->authorize('view', $dashboard);
        }
        $now = Carbon::now();

        $items = $dashboard ? $dashboard->plants()->with(['items' => function ($query) {
            $query->wherePivotNotNull('due')->wherePivotNull('checked');
        }])->get()->pluck('items')->flatten() : collect();

        $overdue = $items->filter(function ($item) use ($now) {
            return $item->pivot && $item->pivot->due && $item->pivot->due->lt($now);
        });
        $dueSoon = $items->filter(function ($item) use ($now) {
            return $item->pivot && $item->pivot->due && $item->pivot->due->between($now, $now->copy()->addDay());
        });

        return view('today', compact('overdue', 'dueSoon'));
    }

    public function index()
    {
        $this->authorize('viewAny', Dashboard::class);
        /** @var User $user */ // Hack for undefined method issue in vscode
        $user = auth()->user();
        $dashboards = $user->isAdmin() ? Dashboard::all() : $user->dashboards()->get();
        $default = Dashboard::getDefault($user->id);

        return view('dashboard.index', compact('dashboards', 'default'));
    }

    public function create()
    {
        $this->authorize('create', Dashboard::class);
        $dashboard = null;
        $users = User::all();

        $title = __('ui.create_new', ['item' => __('ui.dashboard')]);

        $options = [];
        foreach ($users as $user) {
            if ($user->id === auth()->user()->id || $dashboard && $dashboard->creator()->id === $user->id) {
                continue;
            }
            $options[] = view('template.form.select.option', ['value' => $user->id, 'title' => $user->name, 'selected' => false]);
        }

        return view('dashboard.edit', compact('dashboard', 'options', 'title'));
    }

    public function edit($id)
    {
        $dashboard = Dashboard::findOrFail($id);
        $this->authorize('update', $dashboard);
        $users = User::all();

        $title = __('ui.edit_item', ['item' => __('ui.dashboard'), 'name' => $dashboard->name]);

        $options = [];
        foreach ($users as $user) {
            if ($user->id === auth()->user()->id || $dashboard && $dashboard->creator()->id === $user->id) {
                continue;
            }
            $options[] = view('template.form.select.option', ['value' => $user->id, 'title' => $user->name, 'selected' => $dashboard->users()->where('id', $user->id)->exists()]);
        }

        return view('dashboard.edit', compact('dashboard', 'options', 'title'));
    }

    public function detail($id)
    {
        $dashboard = Dashboard::findOrFail($id);
        $this->authorize('view', $dashboard);

        return view('dashboard.detail', compact('dashboard'));
    }

    // Actions
    public function store(DashboardStoreRequest $request, DashboardService $dashboardService)
    {
        $this->authorize('create', Dashboard::class);
        $dashboardService->create($request->validated());

        return back()->with('success', __('ui.created_success', ['item' => __('ui.dashboard')]));
    }

    public function update(DashboardUpdateRequest $request, $id, DashboardService $dashboardService)
    {
        $dashboard = Dashboard::findOrFail($id);
        $this->authorize('update', $dashboard);
        $dashboardService->update($dashboard, $request->validated());

        return back()->with('success', __('ui.updated_success', ['item' => __('ui.dashboard')]));
    }

    public function destroy($id)
    {
        $dashboard = Dashboard::findOrFail($id);
        $this->authorize('delete', $dashboard);
        $dashboard->delete();

        return back()->with('success', __('ui.deleted_success', ['item' => __('ui.dashboard')]));
    }

    public function default($id)
    {
        /** @var User $user */ // Hack for undefined method issue in vscode
        $user = auth()->user();
        foreach ($user->dashboards as $dashboard) {
            $user->dashboards()->updateExistingPivot($dashboard->id, ['default' => false]);
        }

        $dashboard = Dashboard::findOrFail($id);
        $this->authorize('view', $dashboard);
        $dashboard->users()->updateExistingPivot($user->id, ['default' => true]);

        return back()->with('success', __('ui.default_dashboard_set'));
    }

    /**
     * Switch dashboard
     */
    public function switch(Request $request)
    {
        $dashboard = Dashboard::findOrFail($request->dashboard);
        $this->authorize('view', $dashboard);
        session()->put('dashboard_id', $dashboard->id);

        return back()->with('success', __('ui.dashboard_switched'));
    }

    /**
     * Get chart data
     */
    public function getChart($type, DashboardRepository $repo)
    {
        $dashboard = Dashboard::getCurrentDashboard();
        switch ($type) {
            case 'status':
                return response()->json($repo->statusCounts($dashboard));
            case 'watering':
                return response()->json($repo->wateringCounts($dashboard));
        }

        return response()->json([]);
    }
}
