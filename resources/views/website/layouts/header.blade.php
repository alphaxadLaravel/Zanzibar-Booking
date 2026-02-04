<header class="modern-navbar">
    <div class="navbar-container">
        <!-- Logo and Mobile Menu Section -->
        <div class="navbar-left">
            <!-- Logo Section -->
            <div class="navbar-brand">
                <a href="{{ route('index') }}" class="brand-link">
                    <img src="{{asset('logo.png')}}" alt="Zanzibar Bookings" class="brand-logo" />
                </a>
            </div>

            <!-- Mobile Menu Toggle -->
            <div class="mobile-menu-toggle">
                <button class="hamburger-btn" type="button" aria-label="Toggle navigation menu">
                    <span class="hamburger-line"></span>
                    <span class="hamburger-line"></span>
                    <span class="hamburger-line"></span>
                </button>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="navbar-menu">
            <div class="menu-overlay"></div>
            <div class="mobile-menu-header d-md-none">
                <span class="mobile-menu-title">Menu</span>
                <button class="mobile-menu-close" type="button" aria-label="Close menu">
                    <i class="far fa-times"></i>
                </button>
            </div>
            <ul class="nav-menu">
                <ul id="menu-primary-1" class="main-menu">
                    <li class="menu-item menu-item-1">
                        <a href="{{ route('index') }}">Home</a>
                    </li>
                    <li class="menu-item menu-item-2">
                        <a  href="{{ route('hotels') }}">Hotels</a>
                    </li>
                    <li class="menu-item menu-item-5">
                        <a  href="{{route('apartments')}}">Apartments</a>
                    </li>
                    <li class="menu-item menu-item-5">
                        <a  href="{{ route('activities') }}">Activities</a>
                    </li>
                    <li class="menu-item menu-item-5">
                        <a href="{{ route('packages') }}">Packages</a>
                    </li>
                    <li class="menu-item menu-item-5">
                        <a  href="{{ route('cars') }}">Cars</a>
                    </li>
                    <li class="menu-item menu-item-5">
                        <a href="{{route('flights.index')}}">Flights</a>
                    </li>
                    <li class="menu-item menu-item-5">
                        <a href="{{ route('blog') }}">Blog</a>
                    </li>
                </ul>
            </ul>
        </nav>

        <!-- User Actions -->
        <div class="navbar-actions">
            @php
                $authUser = auth()->user();
                $sidebarRoleName = $authUser?->role?->name;
                $sidebarIsPartner = $sidebarRoleName === 'Partner';
                $sidebarIsAdmin = in_array($sidebarRoleName, ['Super Admin', 'Admin'], true);
            @endphp
            <!-- Cart Icon -->
            <div class="cart-icon-container">
                <a href="{{ route('cart') }}" class="cart-link" title="View Cart">
                    <i class="mdi mdi-cart-outline cart-icon"></i>
                    <span class="cart-count" id="cart-count">
                        @auth
                            {{ \App\Models\BookingItem::where('user_id', Auth::id())->where('status', 'cart')->count() }}
                        @else
                            0
                        @endauth
                    </span>
                </a>
            </div>
            
            @auth
                <!-- Authenticated User Actions -->
                <div class="d-flex gap-3 align-items-center">
                    @if(!$sidebarIsPartner && !$sidebarIsAdmin)
                        <a href="#" class="btn btn-outline-primary mx-2" data-bs-toggle="modal" data-bs-target="#BecomePartner">
                            <i class="mdi mdi-handshake"></i>
                            <span class="btn-text">Become Partner</span>
                        </a>
                    @endif
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="mdi mdi-account"></i>
                            <span class="btn-text">{{ Auth::user()->firstname }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            @php
                                $dashboardRoles = ['Super Admin', 'Admin', 'Partner'];
                            @endphp
                            @if(in_array(optional(Auth::user()->role)->name, $dashboardRoles))
                                <li><a class="dropdown-item" href="{{ route('dashboard') }}">
                                    <i class="mdi mdi-view-dashboard me-2"></i>Dashboard
                                </a></li>
                            @endif
                            <li><a class="dropdown-item" href="#" onclick="openChangePasswordModal()">
                                <i class="mdi mdi-key me-2"></i>Change Password
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.my-bookings') }}">
                                <i class="mdi mdi-calendar-check me-2"></i>My Bookings
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('booking.lookup') }}">
                                <i class="mdi mdi-magnify me-2"></i>Look Up Booking
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="{{ route('logout') }}">
                                <i class="mdi mdi-logout me-2"></i>Logout
                            </a></li>
                        </ul>
                    </div>
                </div>
            @else
                <!-- Guest Actions -->
                <div class="d-flex gap-3">
                    <a href="#" class="btn btn-outline-primary mx-2 become-partner-btn">
                        <i class="mdi mdi-handshake"></i>
                        <span class="btn-text">Become A Partner</span>
                    </a>
                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        <i class="mdi mdi-login"></i>
                        <span class="btn-text">Sign In</span>
                    </a>
                </div>
            @endauth
        </div>
    </div>
</header>

<script>
    document.addEventListener("DOMContentLoaded", function () {
    // Mobile menu toggle
    const hamburgerBtn = document.querySelector(".hamburger-btn");
    const navbarMenu = document.querySelector(".navbar-menu");
    const menuOverlay = document.querySelector(".menu-overlay");

    if (hamburgerBtn && navbarMenu) {
      hamburgerBtn.addEventListener("click", function (e) {
        e.preventDefault();
        e.stopPropagation();
        navbarMenu.classList.toggle("active");
        menuOverlay.classList.toggle("active");
        document.body.style.overflow = navbarMenu.classList.contains(
          "active"
        )
          ? "hidden"
          : "";
      });

      // Close menu when clicking overlay
      if (menuOverlay) {
        menuOverlay.addEventListener("click", function (e) {
          e.preventDefault();
          e.stopPropagation();
          navbarMenu.classList.remove("active");
          menuOverlay.classList.remove("active");
          document.body.style.overflow = "";
        });
      }

      // Ensure menu links are clickable
      const menuLinks = navbarMenu.querySelectorAll("a");
      menuLinks.forEach((link) => {
        link.addEventListener("click", function (e) {
          // Allow the link to work normally
          console.log("Menu link clicked:", this.href);
        });
      });

      // Mobile menu close button
      const closeBtn = navbarMenu.querySelector(".mobile-menu-close");
      if (closeBtn) {
        closeBtn.addEventListener("click", function (e) {
          e.preventDefault();
          navbarMenu.classList.remove("active");
          menuOverlay.classList.remove("active");
          document.body.style.overflow = "";
        });
      }
    }

    // Mobile guest actions toggle for small screens
    const guestActions = document.querySelector(".guest-actions");
    if (guestActions && window.innerWidth <= 768) {
      // Create toggle button for mobile
      const toggleBtn = document.createElement("button");
      toggleBtn.className = "guest-actions-toggle";
      toggleBtn.innerHTML = '<i class="far fa-user"></i>';
      toggleBtn.setAttribute("aria-label", "Toggle guest actions");

      // Insert toggle button before guest actions
      guestActions.parentNode.insertBefore(toggleBtn, guestActions);

      toggleBtn.addEventListener("click", function (e) {
        e.stopPropagation();
        guestActions.classList.toggle("active");
      });

      // Close guest actions when clicking outside
      document.addEventListener("click", function (e) {
        if (
          !guestActions.contains(e.target) &&
          !toggleBtn.contains(e.target)
        ) {
          guestActions.classList.remove("active");
        }
      });
    }

    // Close mobile menu on window resize
    window.addEventListener("resize", function () {
      if (window.innerWidth > 991) {
        navbarMenu.classList.remove("active");
        menuOverlay.classList.remove("active");
        document.body.style.overflow = "";
      }

      // Handle guest actions toggle on resize
      if (window.innerWidth > 768) {
        if (guestActions) {
          guestActions.classList.remove("active");
        }
      }
    });

    // Redirect "Become Partner" button to login for guests
    @guest
        document.querySelectorAll('.become-partner-btn').forEach(function (el) {
            el.addEventListener('click', function (ev) {
                ev.preventDefault();
                ev.stopPropagation();
                const loginModalEl = document.getElementById('exampleModal');
                if (loginModalEl) {
                    const modal = bootstrap.Modal.getOrCreateInstance(loginModalEl);
                    modal.show();
                }
            });
        });
    @endguest

  });
</script>