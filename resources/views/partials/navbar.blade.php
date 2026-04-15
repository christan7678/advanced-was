<nav class="custom-navbar">
    <div class="navbar-inner container-fluid px-4">

    
        <a class="navbar-brand" href="{{ route('home') }}">Event<span class="nav-span">Book</span></a>

        {{-- Desktop nav links --}}
        <ul class="nav-links">
            <li><a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a></li>
            <li><a class="nav-link {{ request()->routeIs('events.*') ? 'active' : '' }}" href="{{ route('events.index') }}">Events</a></li>
            <li><a class="nav-link {{ request()->routeIs('bookings.*') ? 'active' : '' }}" href="{{ route('bookings.index') }}">Bookings</a></li>
            <li><a class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}" href="{{ route('profile.index') }}">Profile</a></li>
        </ul>

        {{-- Right-side actions --}}
        <div class="nav-actions">

            {{-- Search --}}
            <form action="{{ route('events.index') }}" method="GET" class="search-form" id="searchForm">
                <button type="button" class="icon-btn search-icon-btn" id="searchIconBtn" title="Search">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.868-3.834zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                    </svg>
                </button>
                <div class="search-input-wrap">
                    <svg class="search-icon-inner" xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.868-3.834zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                    </svg>
                    <input type="text" name="search" class="search-input"
                        placeholder="Search events…" value="{{ request('search') }}">
                </div>
                <button type="submit" class="search-submit">Search</button>
            </form>

            {{-- Profile mega dropdown --}}
            <div class="profile-mega-wrap" id="profileWrap">
                <button class="icon-btn profile-mega-btn" id="profileBtn" title="Account" type="button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm4 6a4 4 0 0 0-8 0h8z"/>
                    </svg>
                </button>

                <div class="profile-mega profile-dropdown-auth" id="profileMega">
                    <div class="profile-mega-inner">
                        <div class="profile-mega-col">

                            @guest
                                <p class="profile-mega-label">Account</p>

                                <a class="profile-mega-item" href="{{ route('login') }}">
                                    <div class="profile-mega-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd" d="M6 3.5a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-2a.5.5 0 0 0-1 0v2A1.5 1.5 0 0 0 6.5 14h8A1.5 1.5 0 0 0 16 12.5v-9A1.5 1.5 0 0 0 14.5 2h-8A1.5 1.5 0 0 0 5 3.5v2a.5.5 0 0 0 1 0v-2z"/>
                                            <path fill-rule="evenodd" d="M11.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H1.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="profile-mega-item-title">Login</div>
                                        <div class="profile-mega-item-desc">Sign in to your account</div>
                                    </div>
                                </a>

                                <a class="profile-mega-item" href="{{ route('register') }}">
                                    <div class="profile-mega-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H1s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C9.516 10.68 8.289 10 6 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
                                            <path fill-rule="evenodd" d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="profile-mega-item-title">Register</div>
                                        <div class="profile-mega-item-desc">Create a new account</div>
                                    </div>
                                </a>
                            @endguest

                            @auth
                                <p class="profile-mega-label">My Account</p>

                                <div class="profile-user-card">
                                    <div class="profile-user-avatar">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>

                                    <div class="profile-user-info">
                                        <div class="profile-user-name">{{ Auth::user()->name }}</div>
                                        <div class="profile-user-email">{{ Auth::user()->email }}</div>
                                    </div>
                                </div>

                                <div class="profile-dropdown-links">
                                    <a class="profile-mega-item" href="{{ route('profile.index') }}">
                                        <div class="profile-mega-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm4 6a4 4 0 0 0-8 0h8z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="profile-mega-item-title">My Profile</div>
                                            <div class="profile-mega-item-desc">View account details</div>
                                        </div>
                                    </a>

                                    <a class="profile-mega-item" href="{{ route('bookings.index') }}">
                                        <div class="profile-mega-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M0 4.5A1.5 1.5 0 0 1 1.5 3h13A1.5 1.5 0 0 1 16 4.5v2A1.5 1.5 0 0 1 14.5 8H14v1.5a.5.5 0 0 1-1 0V8H3v1.5a.5.5 0 0 1-1 0V8h-.5A1.5 1.5 0 0 1 0 6.5v-2zM1.5 4a.5.5 0 0 0-.5.5v2a.5.5 0 0 0 .5.5H2V6a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v1h.5a.5.5 0 0 0 .5-.5v-2a.5.5 0 0 0-.5-.5h-13z"/>
                                                <path d="M3 7h10v5.5a1.5 1.5 0 0 1-1.5 1.5h-7A1.5 1.5 0 0 1 3 12.5V7z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="profile-mega-item-title">My Bookings</div>
                                            <div class="profile-mega-item-desc">See your booked tickets</div>
                                        </div>
                                    </a>
                                </div>

                                <div class="profile-dropdown-divider"></div>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="profile-logout-btn">
                                        <span class="profile-mega-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                                <path fill-rule="evenodd" d="M6 3.5a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-2a.5.5 0 0 0-1 0v2A1.5 1.5 0 0 0 6.5 14h8A1.5 1.5 0 0 0 16 12.5v-9A1.5 1.5 0 0 0 14.5 2h-8A1.5 1.5 0 0 0 5 3.5v2a.5.5 0 0 0 1 0v-2z"/>
                                                <path fill-rule="evenodd" d="M11.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H1.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/>
                                            </svg>
                                        </span>
                                        <span>Logout</span>
                                    </button>
                                </form>
                            @endauth

                        </div>
                    </div>
                </div>
            </div>

            {{-- Hamburger (mobile only) --}}
            <div class="profile-mega-wrap hamburger-wrap">
                <button class="icon-btn hamburger-btn" id="hamburgerBtn" title="Menu" type="button">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
                </button>
 
                <div class="profile-mega mobile-menu-dropdown" id="menuPopup">
                    {{-- Inline search inside mobile menu --}}
                    <div class="mobile-search-bar">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                        <form action="{{ route('events.index') }}" method="GET" style="flex:1">
                            <input type="text" name="search" placeholder="Search events…" value="{{ request('search') }}" class="mobile-search-input">
                        </form>
                    </div>
 
                    <div class="profile-mega-inner">
                        <div class="profile-mega-col">
                            <p class="profile-mega-label">Navigation</p>
 
                            <a class="profile-mega-item {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                                <div class="profile-mega-icon">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                                </div>
                                <div class="profile-mega-item-title">Home</div>
                                <svg class="profile-mega-chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                            </a>
 
                            <a class="profile-mega-item {{ request()->routeIs('events.*') ? 'active' : '' }}" href="{{ route('events.index') }}">
                                <div class="profile-mega-icon">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                                </div>
                                <div class="profile-mega-item-title">Events</div>
                                <svg class="profile-mega-chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                            </a>
 
                            <a class="profile-mega-item {{ request()->routeIs('bookings.*') ? 'active' : '' }}" href="{{ route('bookings.index') }}">
                                <div class="profile-mega-icon">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 9a3 3 0 0 0 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 0 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2z"/></svg>
                                </div>
                                <div class="profile-mega-item-title">Bookings</div>
                                <svg class="profile-mega-chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                            </a>
 
                            <a class="profile-mega-item {{ request()->routeIs('profile.*') ? 'active' : '' }}" href="{{ route('profile.index') }}">
                                <div class="profile-mega-icon">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                </div>
                                <div class="profile-mega-item-title">Profile</div>
                                <svg class="profile-mega-chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                            </a>
 
                            <p class="profile-mega-label" style="border-top: 1px solid #f1f3f5; padding-top: 14px; margin-top: 6px;">Account</p>
 
                            <a class="profile-mega-item" href="{{ route('login') }}">
                                <div class="profile-mega-icon">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                                </div>
                                <div class="profile-mega-item-title">Sign in</div>
                                <svg class="profile-mega-chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                            </a>
 
                            <a class="profile-mega-item" href="{{ route('register') }}">
                                <div class="profile-mega-icon">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
                                </div>
                                <div class="profile-mega-item-title">Create account</div>
                                <svg class="profile-mega-chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>







        </div>
    </div>

</nav>