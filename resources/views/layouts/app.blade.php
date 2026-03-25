<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>BPKH Task Management</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
  <nav class="navbar navbar-expand-lg app-navbar navbar-light border-bottom">
    <div class="container">
      <a class="navbar-brand fw-semibold" href="{{ route('home') }}">BPKH Task Management</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#appNavbar" aria-controls="appNavbar" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="appNavbar">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          @auth
            <li class="nav-item">
              <a class="nav-link" href="{{ route('projects.index') }}">Projects</a>
            </li>
            @if (auth()->user()->isAdmin())
              <li class="nav-item">
                <a class="nav-link" href="{{ route('users.index') }}">Users</a>
              </li>
            @endif
            <li class="nav-item">
              <a class="nav-link" href="{{ route('profile.edit') }}">Profile</a>
            </li>
          @endauth
        </ul>
        <ul class="navbar-nav ms-auto">
          @auth
            <li class="nav-item d-flex align-items-center me-2 text-muted">
              {{ auth()->user()->name }}
            </li>
            <li class="nav-item">
              <form action="{{ route('logout') }}" method="post">
                @csrf
                <button class="btn btn-outline-dark btn-sm" type="submit">Logout</button>
              </form>
            </li>
          @else
            <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Register</a></li>
          @endauth
        </ul>
      </div>
    </div>
  </nav>

  <main class="py-4">
    <div class="container">
      @include('partials.alerts')
      @yield('content')
    </div>
  </main>

  <div class="modal fade" id="confirmActionModal" tabindex="-1" aria-labelledby="confirmActionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="confirmActionModalLabel">Konfirmasi tindakan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" data-confirm-message>
          Kamu yakin ingin melanjutkan?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
          <button type="button" class="btn btn-danger" data-confirm-submit>Ya, lanjutkan</button>
        </div>
      </div>
    </div>
  </div>
</body>

</html>
