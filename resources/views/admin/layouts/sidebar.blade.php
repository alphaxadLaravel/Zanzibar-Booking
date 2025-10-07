<div class="sidenav-menu ">
    <a href="{{ route('index') }}" class="logo">
        <span class="logo logo-light">
            <span class="logo-lg text-white fw-bold fs-5" style="letter-spacing:1px;">Zanzibar Bookings</span>
            <span class="logo-sm"><img src="{{asset('logo.png')}}" alt="small logo"></span>
        </span>

        <span class="logo logo-dark">
            <span class="logo-lg"><img src="{{asset('logo.png')}}" alt="dark logo"></span>
            <span class="logo-sm"><img src="{{asset('logo.png')}}" alt="small logo"></span>
        </span>
    </a>

    <button class="button-on-hover">
        <i class="mdi mdi-menu"></i>
    </button>

    <button class="button-close-offcanvas">
        <i class="mdi mdi-close"></i>
    </button>

    <div class="scrollbar" data-simplebar>
        <ul class="side-nav">
            <li class="side-nav-title">ADMIN</li>

            <!-- Dashboard -->
            <li class="side-nav-item">
                <a href="{{ route('dashboard') }}" class="side-nav-link">
                    <span class="menu-icon"><i class="mdi mdi-view-dashboard"></i></span>
                    <span class="menu-text">Dashboard</span>
                </a>
            </li>

            <li class="side-nav-title">All Services</li>

            <!-- HOTELS -->
            <li class="side-nav-item">
                <a href="{{ route('admin.deal', 'hotel') }}" class="side-nav-link">
                    <span class="menu-icon"><i class="mdi mdi-city-variant-outline"></i></span>
                    <span class="menu-text">Hotels</span>
                </a>
            </li>

            <!-- TOURS -->
            <li class="side-nav-item">
                <a href="{{ route('admin.deal', 'package') }}" class="side-nav-link">
                    <span class="menu-icon"><i class="mdi mdi-map-marker-radius"></i></span>
                    <span class="menu-text">Packages</span>
                </a>
            </li>

            <li class="side-nav-item">
                <a href="{{ route('admin.deal', 'activity') }}" class="side-nav-link">
                    <span class="menu-icon"><i class="mdi mdi-map-marker-radius"></i></span>
                    <span class="menu-text">Activities</span>
                </a>
            </li>

            <!-- CARS -->
            <li class="side-nav-item">
                <a href="{{ route('admin.deal', 'car') }}" class="side-nav-link">
                    <span class="menu-icon"><i class="mdi mdi-car"></i></span>
                    <span class="menu-text">Cars</span>
                </a>
            </li>

            <!-- APARTMENTS -->
            <li class="side-nav-item">
                <a href="{{ route('admin.deal', 'apartment') }}" class="side-nav-link">
                    <span class="menu-icon"><i class="mdi mdi-home-city"></i></span>
                    <span class="menu-text">Apartments</span>
                </a>
            </li>

            <!-- BLOG -->
            <li class="side-nav-item">
                <a href="{{ route('admin.blog') }}" class="side-nav-link">
                    <span class="menu-icon"><i class="mdi mdi-post-outline"></i></span>
                    <span class="menu-text">Blog</span>
                </a>
            </li>

            <li class="side-nav-title">Manage</li>

            <!-- BOOKINGS -->
            <li class="side-nav-item">
                <a href="{{ route('admin.bookings') }}" class="side-nav-link">
                    <span class="menu-icon"><i class="mdi mdi-calendar-check"></i></span>
                    <span class="menu-text">Bookings</span>
                </a>
            </li>

            <li class="side-nav-item">
                <a href="{{ route('admin.users') }}" class="side-nav-link">
                    <span class="menu-icon"><i class="mdi mdi-account-group"></i></span>
                    <span class="menu-text">Users</span>
                </a>
            </li>

            <!-- PAYMENTS -->
            <li class="side-nav-item">
                <a href="{{ route('admin.payments') }}" class="side-nav-link">
                    <span class="menu-icon"><i class="mdi mdi-cash-multiple"></i></span>
                    <span class="menu-text">Payments</span>
                </a>
            </li>

            <li class="side-nav-title">Settings</li>
            <li class="side-nav-item">
                <a href="{{ route('admin.categories') }}" class="side-nav-link">
                    <span class="menu-icon"><i class="mdi mdi-shape-outline"></i></span>
                    <span class="menu-text">Categories</span>
                </a>
            </li>
            <li class="side-nav-item">
                <a href="{{ route('admin.features') }}" class="side-nav-link">
                    <span class="menu-icon"><i class="mdi mdi-star-outline"></i></span>
                    <span class="menu-text">Features</span>
                </a>
            </li>

            <li class="side-nav-title">Account</li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarSettings" aria-expanded="false"
                    aria-controls="sidebarSettings" class="side-nav-link">
                    <span class="menu-icon"><i class="mdi mdi-cog"></i></span>
                    <span class="menu-text">Settings</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarSettings">
                    <ul class="sub-menu">
                        <li class="side-nav-item"><a href="{{ route('admin.system.settings') }}" class="side-nav-link">System Settings</a></li>
                        <li class="side-nav-item"><a href="{{ route('admin.manage.content', 'about-us') }}" class="side-nav-link">About Us</a></li>
                        <li class="side-nav-item"><a href="{{ route('admin.manage.content', 'become-a-partner') }}" class="side-nav-link">Become a Partner</a></li>
                        <li class="side-nav-item"><a href="{{ route('admin.manage.content', 'our-commitment') }}" class="side-nav-link">Our Commitment</a></li>
                        <li class="side-nav-item"><a href="{{ route('admin.manage.content', 'terms-conditions') }}" class="side-nav-link">Terms & Conditions</a></li>
                        <li class="side-nav-item"><a href="{{ route('admin.manage.content', 'privacy-policy') }}" class="side-nav-link">Privacy Policy</a></li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a href="{{ route('admin.contact.messages') }}" class="side-nav-link">
                    <span class="menu-icon"><i class="mdi mdi-email"></i></span>
                    <span class="menu-text">Contact Messages</span>
                </a>
            </li>

            <li class="side-nav-item">
                <a href="{{ route('admin.my-bookings') }}" class="side-nav-link">
                    <span class="menu-icon"><i class="mdi mdi-book-open-variant"></i></span>
                    <span class="menu-text">My Bookings</span>
                </a>
            </li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarProfile" aria-expanded="false" aria-controls="sidebarProfile"
                    class="side-nav-link">
                    <span class="menu-icon"><i class="mdi mdi-account-circle"></i></span>
                    <span class="menu-text">Profile</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarProfile">
                    <ul class="sub-menu">
                        <li class="side-nav-item"><a href="{{ route('admin.profile') }}" class="side-nav-link">View
                                Profile</a></li>
                        <li class="side-nav-item"><a href="{{ route('admin.profile.edit') }}" class="side-nav-link">Edit
                                Profile</a></li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</div>