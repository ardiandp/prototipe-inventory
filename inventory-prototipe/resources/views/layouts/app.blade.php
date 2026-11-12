<!doctype html>
<html lang="id">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
  <meta name="color-scheme" content="light dark" />
  <title>@yield('title', 'Dashboard') — Inventory & Warehouse System</title>

  <!-- Color Mode Init Script (Prevents flash on theme load) -->
  <script>
    (() => {
      'use strict';
      const root = document.documentElement;
      const STORAGE_KEY = 'lte-theme';
      let stored = null;
      try {
        stored = localStorage.getItem(STORAGE_KEY);
      } catch {}
      const authored = root.getAttribute('data-bs-theme');
      let resolved = 'light';
      if (stored === 'dark' || stored === 'light') {
        resolved = stored;
      } else if (authored === 'dark' || authored === 'light') {
        resolved = authored;
      } else if (globalThis.matchMedia && globalThis.matchMedia('(prefers-color-scheme: dark)').matches) {
        resolved = 'dark';
      }
      root.setAttribute('data-bs-theme', resolved);
      root.style.colorScheme = resolved;
    })();
  </script>

  <!-- Google Fonts: Source Sans 3 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" />
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
  <!-- OverlayScrollbars -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css" />
  <!-- AdminLTE 4 Main CSS -->
  <link rel="stylesheet" href="{{ asset('theme/AdminLTE-4/dist/css/adminlte.min.css') }}" />

  <style>
    body {
      font-family: 'Source Sans 3', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
    }
    .brand-text {
      letter-spacing: 0.5px;
    }
    .nav-header {
      font-size: 0.75rem;
      text-transform: uppercase;
      font-weight: 700;
      letter-spacing: 0.5px;
      padding: 0.75rem 1rem 0.25rem 1rem;
      color: rgba(255, 255, 255, 0.4);
    }
    .user-avatar-sm {
      width: 32px;
      height: 32px;
      object-fit: cover;
    }
  </style>

  @stack('styles')
</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
  <div class="app-wrapper">
    <!-- Topbar Navigation -->
    @include('layouts.partials.topbar')

    <!-- Sidebar Navigation -->
    @include('layouts.partials.sidebar')

    <!-- Main Content Shell -->
    <main class="app-main">
      <!-- Breadcrumb & Page Header -->
      @include('layouts.partials.header')

      <!-- Page Content -->
      <div class="app-content py-3">
        <div class="container-fluid">
          @yield('content')
        </div>
      </div>
    </main>

    <!-- Footer -->
    @include('layouts.partials.footer')
  </div>

  <!-- Global Toast Notifications Container -->
  @include('layouts.partials.toasts')

  <!-- Core JavaScript Libraries -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"></script>
  <!-- AdminLTE 4 Main JS -->
  <script src="{{ asset('theme/AdminLTE-4/dist/js/adminlte.min.js') }}"></script>

  <!-- OverlayScrollbars Initialization -->
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const sidebarWrapper = document.querySelector('.sidebar-wrapper');
      if (sidebarWrapper && typeof OverlayScrollbarsGlobal !== 'undefined') {
        OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
          scrollbars: {
            theme: 'os-theme-light',
            autoHide: 'leave',
            clickScroll: true,
          },
        });
      }
    });
  </script>

  <!-- Color Mode Toggle Logic -->
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      document.querySelectorAll('[data-bs-theme-value]').forEach(btn => {
        btn.addEventListener('click', () => {
          const val = btn.getAttribute('data-bs-theme-value');
          const root = document.documentElement;
          if (val === 'auto') {
            const isDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            root.setAttribute('data-bs-theme', isDark ? 'dark' : 'light');
            root.style.colorScheme = isDark ? 'dark' : 'light';
            localStorage.removeItem('lte-theme');
          } else {
            root.setAttribute('data-bs-theme', val);
            root.style.colorScheme = val;
            localStorage.setItem('lte-theme', val);
          }
          // Update active checkmarks if present
          document.querySelectorAll('[data-bs-theme-value]').forEach(b => b.classList.remove('active'));
          btn.classList.add('active');
        });
      });
    });
  </script>

  @stack('scripts')
</body>
</html>
