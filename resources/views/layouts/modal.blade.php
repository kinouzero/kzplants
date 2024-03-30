<modal>
  <div class="modal fade" id="{{ request()->all('target')['target'] }}" tabindex="-1"
    aria-labelledby="{{ request()->all('target')['target'] }}Label" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="{{ request()->all('target')['target'] }}Label">
            @yield('title')
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          @yield('content')
        </div>
      </div>
    </div>
  </div>
</modal>
