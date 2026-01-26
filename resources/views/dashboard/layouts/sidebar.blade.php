<div class="main-sidebar sidebar-style-2">
  @if (Auth::user()->role == 'admin')
     @include('dashboard.layouts.menu.menuadmin')
     {{-- ini harus sesuai sama nama folder sama file nya --}}
     @elseif (Auth::user()->role == 'user')
     @include('dashboard.layouts.menu.menuuser')
  @endif
</div>