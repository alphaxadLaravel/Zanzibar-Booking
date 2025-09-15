<div class="sidenav-menu ">
    <a href="{{ route('dashboard') }}" class="logo">
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

            <!-- HOTELS v -->
            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarHotels" aria-expanded="false" aria-controls="sidebarHotels"
                    class="side-nav-link">
                    <span class="menu-icon"><i class="mdi mdi-city-variant-outline"></i></span>
                    <span class="menu-text">Hotels</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarHotels">
                    <ul class="sub-menu">
                        <li class="side-nav-item"><a href="{{ route('admin.hotels') }}" class="side-nav-link">All
                                Hotels</a></li>
                        <li class="side-nav-item"><a href="{{ route('admin.hotels.create') }}" class="side-nav-link">Add
                                Hotel</a></li>
                    </ul>
                </div>
            </li>

            <!-- CARS v -->
            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarCars" aria-expanded="false" aria-controls="sidebarCars"
                    class="side-nav-link">
                    <span class="menu-icon"><i class="mdi mdi-car"></i></span>
                    <span class="menu-text">Cars</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarCars">
                    <ul class="sub-menu">
                        <li class="side-nav-item"><a href="{{ route('admin.cars') }}" class="side-nav-link">All Cars</a>
                        </li>
                        <li class="side-nav-item"><a href="{{ route('admin.cars.create') }}" class="side-nav-link">Add
                                Car</a></li>
                    </ul>
                </div>
            </li>

            <!-- TOURS v -->
            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarTours" aria-expanded="false" aria-controls="sidebarTours"
                    class="side-nav-link">
                    <span class="menu-icon"><i class="mdi mdi-map-marker-radius"></i></span>
                    <span class="menu-text">Tours</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarTours">
                    <ul class="sub-menu">
                        <li class="side-nav-item"><a href="{{ route('admin.tours') }}" class="side-nav-link">All
                                Tours</a></li>
                        <li class="side-nav-item"><a href="{{ route('admin.tours.create') }}" class="side-nav-link">Add
                                Tour</a></li>
                    </ul>
                </div>
            </li>

            <!-- APARTMENTS v -->
            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarApartments" aria-expanded="false"
                    aria-controls="sidebarApartments" class="side-nav-link">
                    <span class="menu-icon"><i class="mdi mdi-home-city"></i></span>
                    <span class="menu-text">Apartments</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarApartments">
                    <ul class="sub-menu">
                        <li class="side-nav-item"><a href="{{ route('admin.apartments') }}" class="side-nav-link">All
                                Apartments</a></li>
                        <li class="side-nav-item"><a href="{{ route('admin.apartments.create') }}"
                                class="side-nav-link">Add Apartment</a></li>
                    </ul>
                </div>
            </li>

            <!-- BLOG v -->
            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarBlog" aria-expanded="false" aria-controls="sidebarBlog"
                    class="side-nav-link">
                    <span class="menu-icon"><i class="mdi mdi-post-outline"></i></span>
                    <span class="menu-text">Blog</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarBlog">
                    <ul class="sub-menu">
                        <li class="side-nav-item"><a href="{{ route('admin.blog') }}" class="side-nav-link">All
                                Posts</a></li>
                        <li class="side-nav-item"><a href="{{ route('admin.blog.create') }}" class="side-nav-link">Add
                                Post</a></li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-title">Manage</li>

            <!-- BOOKINGS -->
            <li class="side-nav-item">
                <a href="{{ route('admin.bookings') }}" class="side-nav-link">
                    <span class="menu-icon"><i class="mdi mdi-calendar-check"></i></span>
                    <span class="menu-text">Bookings</span>
                </a>
            </li>

            <!-- USERS v -->
            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarUsers" aria-expanded="false" aria-controls="sidebarUsers"
                    class="side-nav-link">
                    <span class="menu-icon"><i class="mdi mdi-account-group"></i></span>
                    <span class="menu-text">Users</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarUsers">
                    <ul class="sub-menu">
                        <li class="side-nav-item"><a href="{{ route('admin.users') }}" class="side-nav-link">All
                                Users</a></li>
                        <li class="side-nav-item"><a href="{{ route('admin.users.create') }}" class="side-nav-link">Add
                                User</a></li>
                        <li class="side-nav-item"><a href="{{ route('admin.users.roles') }}"
                                class="side-nav-link">Roles</a></li>
                    </ul>
                </div>
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

            <!-- Settings v -->
            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarSettings" aria-expanded="false"
                    aria-controls="sidebarSettings" class="side-nav-link">
                    <span class="menu-icon"><i class="mdi mdi-cog"></i></span>
                    <span class="menu-text">Settings</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarSettings">
                    <ul class="sub-menu">
                        <li class="side-nav-item"><a href="{{ route('admin.settings.general') }}"
                                class="side-nav-link">General</a></li>
                        <li class="side-nav-item"><a href="{{ route('admin.settings.security') }}"
                                class="side-nav-link">Security</a></li>
                    </ul>
                </div>
            </li>

            <!-- My Bookings -->
            <li class="side-nav-item">
                <a href="{{ route('admin.my-bookings') }}" class="side-nav-link">
                    <span class="menu-icon"><i class="mdi mdi-book-open-variant"></i></span>
                    <span class="menu-text">My Bookings</span>
                </a>
            </li>

            <!-- Profile v -->
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