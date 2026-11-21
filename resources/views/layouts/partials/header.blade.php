<div class="app-content-header py-3 border-bottom bg-body">
  <div class="container-fluid">
    <div class="row align-items-center">
      <div class="col-sm-6">
        <h3 class="mb-0 fw-bold fs-4 text-body">
          @yield('page_title', 'Dashboard')
        </h3>
        <p class="text-body-secondary small mb-0">
          @yield('page_description', 'Prototipe Sistem Manajemen Stok & Pergudangan')
        </p>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-end mb-0 mt-2 mt-sm-0 small">
          <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}" class="text-decoration-none">Home</a></li>
          @yield('breadcrumb')
        </ol>
      </div>
    </div>
  </div>
</div>

