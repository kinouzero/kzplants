<div class="sidebar bg-dark position-fixed h-100 top-0 left-0 flex-column" id="sidebar">

  <div class="d-flex pt-3 px-3">
    <a class="text-white text-decoration-none d-flex align-items-center justify-content-center" href="/">
      <div class="sidebar-brand-icon rotate-n-15">
        <i class="fas fa-cannabis me-3"></i>
      </div>
      <div class="nav-title">kzPlants</div>
    </a>
    <a href="#" class="text-white toggle-sidebar"><i class="fas fa-bars"></i></a>
  </div>

  <ul class="navbar-nav flex-column mb-auto mx-auto w-100">

    <li class="divider px-3"></li>

    <li class="nav-item px-3 d-flex">
      <a class="nav-link py-1 text-center text-white rounded d-flex flex-nowrap align-items-center"
        href="{{ route('user.edit', ['id' => auth()->user()->id]) }}">
        <i class="fa-regular fa-circle-user me-2"></i>
        <span class="nav-title">{{ auth()->user()->name }}</span></a>
      <a class="nav-link py-1 text-center text-white rounded d-flex flex-nowrap align-items-center ms-auto text-end btn-form"
        title="Deconnexion" href="#" data-form="#logout-form">
        <i class="fas fa-arrow-right-from-bracket"></i>
      </a>
      <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">
        @csrf
      </form>
    </li>

    <li class="divider px-3"></li>

    <li class="nav-item px-3">
      <a class="nav-link py-1 text-center text-white rounded d-flex flex-nowrap align-items-center primary {{ Route::current()->getName() === 'dashboard' ? 'active' : '' }}"
        href="{{ route('dashboard') }}">
        <div class="nav-icon p-2 small rounded" style="background-color:#333">
          <i class="fas fa-fw fa-tachometer-alt"></i>
        </div>
        <span class="nav-title ms-2">Dashboard</span>
      </a>
    </li>

    <li class="divider px-3"></li>

    <div class="nav-title px-3 text-light fw-lighter text-uppercase small">Manage</div>

    <li class="nav-item px-3">
      <a class="nav-link py-1 text-center text-white rounded d-flex flex-nowrap align-items-center success {{ Str::contains(Route::current()->getName(), 'plant.') ? 'active' : '' }}"
        href="{{ route('plant.index') }}">
        <div class="nav-icon p-2 small rounded" style="background-color:#333">
          <i class="fas fa-fw fa-cannabis"></i>
        </div>
        <span class="nav-title ms-2">Plants</span>
      </a>
    </li>

    <li class="nav-item px-3">
      <a class="nav-link py-1 text-center text-white rounded d-flex flex-nowrap align-items-center success {{ Str::contains(Route::current()->getName(), 'strain.') ? 'active' : '' }}"
        href="{{ route('strain.index') }}">
        <div class="nav-icon p-2 small rounded" style="background-color:#333">
          <i class="fas fa-fw fa-seedling"></i>
        </div>
        <span class="nav-title ms-2">Strains</span>
      </a>
    </li>


    @if (auth()->user()->isAdmin())
      <li class="divider px-3"></li>

      <div class="nav-title px-3 text-light fw-lighter text-uppercase small">Admin</div>

      <li class="nav-item px-3">
        <a class="nav-link py-1 text-center text-white rounded d-flex flex-nowrap align-items-center danger {{ Str::contains(Route::current()->getName(), 'statut.') ? 'active' : '' }}"
          href="{{ route('statut.index') }}">
          <div class="nav-icon p-2 small rounded" style="background-color:#333">
            <i class="fas fa-fw fa-list-ul"></i>
          </div>
          <span class="nav-title ms-2">Status</span>
        </a>
      </li>

      <li class="nav-item px-3">
        <a class="nav-link py-1 text-center text-white rounded d-flex flex-nowrap align-items-center danger {{ Str::contains(Route::current()->getName(), 'tag.') ? 'active' : '' }}"
          href="{{ route('tag.index') }}">
          <div class="nav-icon p-2 small rounded" style="background-color:#333">
            <i class="fas fa-fw fa-tags"></i>
          </div>
          <span class="nav-title ms-2">Tags</span>
        </a>
      </li>

      <li class="nav-item px-3">
        <a class="nav-link py-1 text-center text-white rounded d-flex flex-nowrap align-items-center danger {{ Str::contains(Route::current()->getName(), 'property.') ? 'active' : '' }}"
          href="{{ route('property.index') }}">
          <div class="p-2 small rounded" style="background-color:#333">
            <i class="fas fa-fw fa-sitemap"></i>
          </div>
          <span class="nav-title ms-2">Properties</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link py-1 px-3 text-center text-white rounded d-flex flex-nowrap align-items-center danger {{ Str::contains(Route::current()->getName(), 'checklist.') || Str::contains(Route::current()->getName(), 'item.') ? '' : 'collapsed' }}"
          href="#" data-bs-toggle="collapse" data-bs-target="#collapseChecklist" aria-controls="collapseChecklist"
          aria-expanded="true">
          <div class="nav-icon p-2 small rounded" style="background-color:#333">
            <i class="fas fa-fw fa-list-check"></i>
          </div>
          <span class="nav-title ms-2">Checklists</span>
        </a>
        <div id="collapseChecklist"
          class="collapse {{ Str::contains(Route::current()->getName(), 'checklist.') || Str::contains(Route::current()->getName(), 'item.') ? 'show' : '' }}"
          aria-labelledby="headingChecklist" data-bs-parent="#accordionSidebar">
          <div class="bg-black text-white d-flex flex-column p-3 my-2">
            <a class="nav-link mx-3 d-flex flex-nowrap align-items-center warning {{ Str::contains(Route::current()->getName(), 'checklist.') ? 'active' : '' }}"
              href="{{ route('checklist.index') }}">
              <div class="nav-icon p-2 small rounded bg-dark">
                <i class="fas fa-fw fa-list-check"></i>
              </div>
              <span class="nav-title ms-2">Checklists</span>
            </a>
            <a class="nav-link mx-3 d-flex flex-nowrap align-items-center warning {{ Str::contains(Route::current()->getName(), 'item.') ? 'active' : '' }}"
              href="{{ route('item.index') }}">
              <div class="nav-icon p-2 small rounded bg-dark">
                <i class="fas fa-fw fa-check"></i>
              </div>
              <span class="nav-title ms-2">Items</span>
            </a>
          </div>
        </div>
      </li>

      <li class="nav-item">
        <a class="nav-link py-1 px-3 text-center text-white rounded d-flex flex-nowrap align-items-center danger {{ Str::contains(Route::current()->getName(), 'user.') || Str::contains(Route::current()->getName(), 'preference.') ? '' : 'collapsed' }}"
          href="#" data-bs-toggle="collapse" data-bs-target="#collapseUser" aria-controls="collapseUser"
          aria-expanded="true">
          <div class="nav-icon p-2 small rounded" style="background-color:#333">
            <i class="fas fa-fw fa-users"></i>
          </div>
          <span class="nav-title ms-2">Users</span>
        </a>
        <div id="collapseUser"
          class="collapse {{ Str::contains(Route::current()->getName(), 'user.') || Str::contains(Route::current()->getName(), 'preference.') ? 'show' : '' }}"
          aria-labelledby="headingUser" data-bs-parent="#accordionSidebar">
          <div class="bg-black text-white d-flex flex-column p-3 my-2">
            <a class="nav-link mx-3 d-flex flex-nowrap align-items-center warning {{ Str::contains(Route::current()->getName(), 'user.') ? 'active' : '' }}"
              href="{{ route('user.index') }}">
              <div class="nav-icon p-2 small rounded bg-dark">
                <i class="fas fa-fw fa-users"></i>
              </div>
              <span class="nav-title ms-2">Users</span>
            </a>
            <a class="nav-link mx-3 d-flex flex-nowrap align-items-center warning {{ Str::contains(Route::current()->getName(), 'preference.') ? 'active' : '' }}"
              href="{{ route('preference.index') }}">
              <div class="nav-icon p-2 small rounded bg-dark">
                <i class="fas fa-fw fa-cogs"></i>
              </div>
              <span class="nav-title ms-2">Preferences</span>
            </a>
          </div>
        </div>
      </li>
    @endif

  </ul>
</div>
