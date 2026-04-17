@php
  use Illuminate\Support\Facades\Route;
  $configData = Helper::appClasses();
@endphp

<style>
  /* ===== KUSTOMISASI MENU ===== */

  /* Memberikan border-radius ke SEMUA menu agar transisi mulus dan ukuran konsisten */
  .layout-menu .menu-link {
    border-radius: 8px !important;
    transition: background-color 0.2s ease-in-out, color 0.2s ease-in-out;
  }

  /* ACTIVE MENU (override warna bawaan menjadi netral) */
  .layout-menu .menu-item.active > .menu-link {
    background-color: rgba(0, 0, 0, 0.06) !important;
    color: #111 !important;
  }

  /* Menetralkan warna ikon dan teks di dalam menu yang aktif */
  .layout-menu .menu-item.active > .menu-link i,
  .layout-menu .menu-item.active > .menu-link div,
  .layout-menu .menu-item.active > .menu-link span {
    color: #111 !important;
  }

  /* Efek Hover untuk menu yang TIDAK aktif */
  .layout-menu .menu-item:not(.active) > .menu-link:hover {
    background-color: rgba(0, 0, 0, 0.04) !important;
    color: #111 !important;
  }

  /* ===== FIX LOGO & TEKS SAAT MINIMIZE ===== */

  .app-brand-logo {
    flex-shrink: 0 !important;
  }

  /* HANYA sembunyikan teks nama web dan panah SAAT tertutup
     DAN tidak sedang disorot mouse (not hover) */
  .layout-menu-collapsed:not(.layout-menu-hover) .app-brand-text,
  .layout-menu-collapsed:not(.layout-menu-hover) .layout-menu-toggle {
    display: none !important;
  }

  .layout-menu-collapsed:not(.layout-menu-hover) .app-brand-link {
    overflow: visible !important;
    justify-content: flex-start;
  }
</style>

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

  @if(!isset($navbarFull))
  <div class="app-brand demo d-flex align-items-center justify-content-between px-3">

    <a href="{{ url('/admin') }}" class="app-brand-link d-flex align-items-center gap-2 overflow-hidden text-decoration-none">
      <span class="app-brand-logo demo flex-shrink-0">
        @include('_partials.macros', ["width" => 25, "withbg" => 'var(--bs-primary)'])
      </span>
      <span class="app-brand-text demo menu-text fw-semibold text-truncate">
        {{ config('variables.templateName') }}
      </span>
    </a>

    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large flex-shrink-0 ms-2 d-flex align-items-center justify-content-center">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
        <path d="M8.47 11.71C8.11 12.07 8.11 12.65 8.47 13.00L12.07 16.60C12.46 17.00 12.46 17.63 12.07 18.02C11.68 18.41 11.04 18.41 10.65 18.02L5.83 13.19C5.37 12.73 5.37 11.99 5.83 11.53L10.65 6.70C11.04 6.31 11.68 6.31 12.07 6.70C12.46 7.09 12.46 7.73 12.07 8.12L8.47 11.71Z" fill-opacity="0.9"/>
        <path d="M14.35 11.83C14.06 12.12 14.06 12.60 14.35 12.89L18.07 16.60C18.46 17.00 18.46 17.63 18.07 18.02C17.68 18.41 17.04 18.41 16.65 18.02L11.68 13.04C11.30 12.66 11.30 12.05 11.68 11.68L16.65 6.70C17.04 6.31 17.68 6.31 18.07 6.70C18.46 7.09 18.46 7.73 18.07 8.12L14.35 11.83Z" fill-opacity="0.4"/>
      </svg>
    </a>

  </div>
  @endif

  <div class="menu-inner-shadow"></div>

  <ul class="menu-inner py-1">
    @foreach ($menuData[0]->menu as $menu)

      {{-- HEADER MENU --}}
      @if (isset($menu->menuHeader))
        <li class="menu-header mt-4">
          <span class="menu-header-text">{{ __($menu->menuHeader) }}</span>
        </li>
      @else

        {{-- LOGIKA ACTIVE MENU --}}
        @php
          $activeClass = '';
          $currentRouteName = Route::currentRouteName() ?? '';

          if ($currentRouteName === $menu->slug) {
            $activeClass = 'active';
          } elseif (isset($menu->submenu)) {
            $slugs = is_array($menu->slug) ? $menu->slug : [$menu->slug];
            foreach ($slugs as $slug) {
              if (!empty($slug) && str_starts_with($currentRouteName, $slug)) {
                $activeClass = 'active open';
                break;
              }
            }
          }
        @endphp

        {{-- ITEM MENU --}}
        <li class="menu-item {{ $activeClass }}">
          <a href="{{ isset($menu->url) ? url($menu->url) : 'javascript:void(0);' }}"
             class="menu-link {{ isset($menu->submenu) ? 'menu-toggle' : '' }}"
             @if (isset($menu->target)) target="_blank" @endif>

            @isset($menu->icon)
              <i class="{{ $menu->icon }}"></i>
            @endisset

            <div>{{ __($menu->name ?? '') }}</div>

            @isset($menu->badge)
              <div class="badge bg-{{ $menu->badge[0] }} rounded-pill ms-auto">
                {{ $menu->badge[1] }}
              </div>
            @endisset
          </a>

          {{-- SUBMENU --}}
          @isset($menu->submenu)
            @include('layouts.sections.menu.submenu', ['menu' => $menu->submenu])
          @endisset
        </li>

      @endif
    @endforeach
  </ul>

</aside>
