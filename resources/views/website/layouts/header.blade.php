<header class="modern-navbar">
    <div class="navbar-container">
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
                        <a href="{{ route('hotels') }}">Hotels</a>
                    </li>
                    <li class="menu-item menu-item-5">
                        <a href="{{route('apartments')}}">Apartments</a>
                    </li>
                    <li class="menu-item menu-item-5">
                        <a href="{{ route('tours') }}">Tours</a>
                    </li>
                    <li class="menu-item menu-item-5">
                        <a href="{{ route('cars') }}">Cars</a>
                    </li>
                    <li class="menu-item menu-item-5">
                        <a href="{{ route('flights') }}">Flights</a>
                    </li>
                    <li class="menu-item menu-item-5">
                        <a href="{{ route('blog') }}">Blog</a>
                    </li>
                    <li class="menu-item menu-item-6">
                        <a href="{{ route('contact-us') }}">Contacts</a>
                    </li>
                </ul>
            </ul>
        </nav>

        <!-- User Actions -->
        <div class="navbar-actions">
            <!-- Guest Actions -->
            <div class="guest-actions">
                <a href="become-a-partner.html" class="btn btn-partner">
                    <i class="far fa-handshake"></i>
                    <span class="btn-text">Become A Partner</span>
                </a>
                <a href="#gmz-login-popup" class="btn btn-signin gmz-box-popup" data-effect="mfp-zoom-in">
                    <i class="fal fa-sign-in"></i>
                    <span class="btn-text">Sign In</span>
                </a>
            </div>
        </div>
    </div>
</header>

<style>
    /* Modern Navbar Styles - Booking.com Inspired */
    .modern-navbar {
        background: #fff;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        position: sticky;
        top: 0;
        z-index: 2147483645;
        border-bottom: 1px solid #e1e5e9;
    }

    .navbar-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        height: 72px;
    }

    /* Brand/Logo */
    .navbar-brand {
        flex-shrink: 0;
    }

    .brand-link {
        display: flex;
        align-items: center;
        text-decoration: none;
        color: #262626;
        font-weight: 600;
        font-size: 24px;
    }

    .brand-logo {
        height: 40px;
        width: auto;
        object-fit: contain;
    }

    .brand-text {
        color: #003580;
        font-weight: 700;
    }

    /* Mobile Menu Toggle */
    .mobile-menu-toggle {
        display: none;
    }

    .hamburger-btn {
        background: none;
        border: none;
        padding: 8px;
        cursor: pointer;
        display: flex;
        flex-direction: column;
        gap: 4px;
        transition: all 0.3s ease;
    }

    .hamburger-btn:hover {
        background-color: #f8f9fa;
        border-radius: 6px;
    }

    .hamburger-line {
        width: 24px;
        height: 2px;
        background: #262626;
        transition: all 0.3s ease;
    }

    /* Navigation Menu */
    .navbar-menu {
        flex: 1;
        margin: 0 40px;
    }

    .nav-menu {
        display: flex;
        list-style: none;
        margin: 0;
        padding: 0;
        gap: 32px;
    }

    .nav-menu li {
        position: relative;
    }

    .nav-menu a {
        color: #262626;
        text-decoration: none;
        font-weight: 500;
        font-size: 16px;
        padding: 12px 0;
        display: block;
        transition: color 0.2s ease;
    }

    .nav-menu a:hover {
        color: #003580;
    }

    /* User Actions */
    .navbar-actions {
        display: flex;
        align-items: center;
        gap: 16px;
    }



    /* Guest Actions */
    .guest-actions {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .btn {
        padding: 10px 20px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 500;
        font-size: 14px;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: none;
        cursor: pointer;
        white-space: nowrap;
    }

    .btn-partner {
        background: transparent;
        color: #003580;
        border: 1px solid #003580;
    }

    .btn-partner:hover {
        background: #003580;
        color: white;
    }

    .btn-signin {
        background: #003580;
        color: white;
    }

    .btn-signin:hover {
        background: #002855;
        color: white;
    }

    .btn i {
        font-size: 14px;
    }

    .btn-text {
        font-size: 14px;
    }


    /* Hide mobile menu header on desktop/laptop */
    @media (min-width: 768px) {

        .mobile-menu-header,
        .mobile-menu-close,
        .mobile-menu-title {
            display: none !important;
            visibility: hidden !important;
            opacity: 0 !important;
        }
    }

    /* Responsive Design */
    @media (max-width: 991px) {
        .mobile-menu-toggle {
            display: block;
        }

        .navbar-menu {
            position: fixed;
            top: 0;
            left: -100%;
            width: 280px;
            height: 100vh;
            background: white;
            z-index: 2147483646;
            transition: left 0.3s ease;
            overflow-y: auto;
            margin: 0;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.15);
            pointer-events: auto;
            border-right: 1px solid #e1e5e9;
        }

        .navbar-menu.active {
            left: 0;
        }

        .nav-menu {
            flex-direction: column;
            gap: 0;
            padding: 20px 0;
        }

        .mobile-menu-header {
            display: none;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            border-bottom: 1px solid #e1e5e9;
            background: #f8f9fa;
        }

        .mobile-menu-close {
            background: none;
            border: none;
            font-size: 24px;
            color: #666;
            cursor: pointer;
            padding: 8px;
            border-radius: 4px;
            transition: all 0.2s ease;
        }

        .mobile-menu-close:hover {
            background: #e9ecef;
            color: #333;
        }

        /* Hide mobile menu header on large screens */
        @media (min-width: 769px) {
            .mobile-menu-header {
                display: none !important;
            }
        }

        .nav-menu a {
            padding: 16px 20px;
            display: block;
            text-decoration: none;
            color: #262626;
            transition: background-color 0.2s ease;
            cursor: pointer;
            position: relative;
            z-index: 2147483647;
        }

        .nav-menu a:hover {
            background-color: #f8f9fa;
        }

        .nav-menu li {
            border-bottom: 1px solid #e1e5e9;
            position: relative;
            z-index: 2147483647;
        }

        .menu-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: transparent;
            z-index: 2147483645;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            pointer-events: none;
        }

        .menu-overlay.active {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
            cursor: pointer;
        }

        .navbar-actions {
            gap: 8px;
        }

        .guest-actions {
            gap: 8px;
        }

        .btn {
            padding: 8px 16px;
            font-size: 13px;
        }

        .btn-text {
            font-size: 13px;
        }
    }

    @media (max-width: 768px) {
        .mobile-menu-header {
            display: flex;
        }

        .navbar-container {
            padding: 0 16px;
            height: 64px;
            position: relative;
        }

        .brand-logo {
            height: 32px;
        }

        .brand-text {
            font-size: 20px;
        }

        .profile-name {
            display: none;
        }

        .navbar-actions {
            gap: 8px;
        }

        .guest-actions {
            flex-direction: column;
            gap: 8px;
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border: 1px solid #e1e5e9;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            padding: 12px;
            min-width: 200px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .guest-actions.active {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .btn {
            width: 100%;
            justify-content: center;
            padding: 12px 16px;
            font-size: 14px;
        }

        .btn-text {
            font-size: 14px;
        }

        /* Mobile guest actions toggle */
        .guest-actions-toggle {
            display: block;
            background: none;
            border: none;
            padding: 8px;
            cursor: pointer;
            color: #262626;
            font-size: 18px;
        }

    }

    @media (max-width: 576px) {
        .navbar-container {
            padding: 0 12px;
            height: 60px;
            position: relative;
        }

        .brand-logo {
            height: 28px;
        }

        .brand-text {
            font-size: 18px;
        }

        .hamburger-line {
            width: 20px;
            height: 2px;
        }

        .navbar-actions {
            gap: 6px;
        }

        .guest-actions {
            min-width: 180px;
            right: 12px;
        }

        .btn {
            padding: 10px 12px;
            font-size: 13px;
        }

        .btn-text {
            font-size: 13px;
        }

    }

    @media (max-width: 480px) {
        .navbar-container {
            padding: 0 8px;
            position: relative;
        }

        .brand-text {
            font-size: 16px;
        }

        .navbar-actions {
            gap: 4px;
        }

        .guest-actions {
            min-width: 160px;
            right: 8px;
        }

        .btn {
            padding: 8px 10px;
            font-size: 12px;
        }

        .btn-text {
            font-size: 12px;
        }

    }
</style>

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

  });
</script>