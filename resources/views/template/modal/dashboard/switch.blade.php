@include('template.modal', [
    'id' => 'switch-dashboard',
    'title' => 'Switch dashboard',
    'action' => route('switch'),
    'body' => $switch,
    'footer' =>
        '<button type="submit" class="btn btn-outline-secondary"><i class="fas fa-arrows-rotate me-2"></i>Switch</button>',
])
