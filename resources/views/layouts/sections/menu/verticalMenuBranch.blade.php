@php
  use Illuminate\Support\Facades\Route;
  $configData = Helper::appClasses();
@endphp

<style>
  /* =========================
      MENU STYLE
  ========================= */

  .layout-menu .menu-link {
    border-radius: 8px !important;
    transition: background-color 0.2s ease-in-out,
                color 0.2s ease-in-out;
  }

  /* ACTIVE MENU */
  .layout-menu .menu-item.active > .menu-link {
    background-color: rgba(0, 0, 0, 0.06) !important;
    color: #111 !important;
  }

  /* ACTIVE ICON & TEXT */
  .layout-menu .menu-item.active > .menu-link i,
  .layout-menu .menu-item.active > .menu-link div,
  .layout-menu .menu-item.active > .menu-link span {
    color: #111 !important;
  }

  /* HOVER */
  .layout-menu .menu-item:not(.active) > .menu-link:hover {
    background-color: rgba(0, 0, 0, 0.04) !important;
    color: #111 !important;
  }

  /* =========================
      FIX COLLAPSED LOGO
  ========================= */

  .app-brand-logo {
    flex-shrink: 0 !important;
  }

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

  <!-- BRAND -->
  @if(!isset($navbarFull))
  <div class="app-brand demo d-flex align-items-center justify-content-between px-3">

    <a href="{{ url('/branch') }}"
       class="app-brand-link d-flex align-items-center gap-2 overflow-hidden text-decoration-none">

      <span class="app-brand-logo demo flex-shrink-0">
        @include('_partials.macros', [
          "width" => 25,
          "withbg" => 'var(--bs-primary)'
        ])
      </span>

      <span class="app-brand-text demo menu-text fw-semibold text-truncate">
        {{ config('variables.templateName') }}
      </span>
    </a>

    <a href="javascript:void(0);"
       class="layout-menu-toggle menu-link text-large flex-shrink-0 ms-2 d-flex align-items-center justify-content-center">

      <svg width="24"
           height="24"
           viewBox="0 0 24 24"
           fill="none"
           xmlns="http://www.w3.org/2000/svg">

        <path
          d="M8.47365 11.7183C8.11707 12.0749 8.11707 12.6531 8.47365 13.0097L12.071 16.607C12.4615 16.9975 12.4615 17.6305 12.071 18.021C11.6805 18.4115 11.0475 18.4115 10.657 18.021L5.83009 13.1941C5.37164 12.7356 5.37164 11.9924 5.83009 11.5339L10.657 6.707C11.0475 6.31653 11.6805 6.31653 12.071 6.707C12.4615 7.09747 12.4615 7.73053 12.071 8.121L8.47365 11.7183Z"
          fill-opacity="0.9" />

        <path
          d="M14.3584 11.8336C14.0654 12.1266 14.0654 12.6014 14.3584 12.8944L18.071 16.607C18.4615 16.9975 18.4615 17.6305 18.071 18.021C17.6805 18.4115 17.0475 18.4115 16.657 18.021L11.6819 13.0459C11.3053 12.6693 11.3053 12.0587 11.6819 11.6821L16.657 6.707C17.0475 6.31653 17.6805 6.31653 18.071 6.707C18.4615 7.09747 18.4615 7.73053 18.071 8.121L14.3584 11.8336Z"
          fill-opacity="0.4" />
      </svg>
    </a>
  </div>
  @endif

  <div class="menu-inner-shadow"></div>

  <ul class="menu-inner py-1">

    @foreach ($menuData[2]->menu as $menu)

      {{-- MENU HEADER --}}
      @if (isset($menu->menuHeader))

        <li class="menu-header mt-4">
          <span class="menu-header-text">
            {{ __($menu->menuHeader) }}
          </span>
        </li>

      @else

        {{-- ACTIVE MENU --}}
        @php
  $activeClass = '';
  $currentRouteName = Route::currentRouteName() ?? '';

  // MENU BIASA
  if (
      isset($menu->slug) &&
      !isset($menu->submenu)
  ) {

      $slugs = is_array($menu->slug)
          ? $menu->slug
          : [$menu->slug];

      foreach ($slugs as $slug) {

          if (
              !empty($slug) &&
              $currentRouteName === $slug
          ) {
              $activeClass = 'active';
              break;
          }
      }
  }

  // PARENT SUBMENU
  elseif (isset($menu->submenu)) {

      foreach ($menu->submenu as $subMenu) {

          $subSlugs = is_array($subMenu->slug)
              ? $subMenu->slug
              : [$subMenu->slug];

          foreach ($subSlugs as $slug) {

              if (
                  !empty($slug) &&
                  $currentRouteName === $slug
              ) {
                  $activeClass = 'active open';
                  break 2;
              }
          }
      }
  }
@endphp

        {{-- MENU ITEM --}}
        <li class="menu-item {{ $activeClass }}">

          <a href="{{ isset($menu->url)
                      ? url($menu->url)
                      : 'javascript:void(0);' }}"

             class="menu-link {{ isset($menu->submenu)
                                ? 'menu-toggle'
                                : '' }}"

             @if (isset($menu->target))
               target="_blank"
             @endif>

            @isset($menu->icon)
              <i class="{{ $menu->icon }}"></i>
            @endisset

            <div>
              {{ __($menu->name ?? '') }}
            </div>

            @isset($menu->badge)
              <div class="badge bg-{{ $menu->badge[0] }} rounded-pill ms-auto">
                {{ $menu->badge[1] }}
              </div>
            @endisset

          </a>

          {{-- SUBMENU --}}
          @isset($menu->submenu)
            @include('layouts.sections.menu.submenu', [
              'menu' => $menu->submenu
            ])
          @endisset

        </li>

      @endif

    @endforeach

  </ul>

</aside>
