<div id="sidebar">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header position-relative">
            <div class="d-flex justify-content-between align-items-center">
                <!-- Logo Section -->
                <div class="img-fluid d-flex align-items-center">
                    <h5 class="logo-text ms-3">Leaderboard Admin</h5>
                </div>
                <!-- Theme Toggle -->
                <div class="theme-toggle d-flex gap-2 align-items-center mt-2">
                    <!-- SVG icons and theme switcher -->
                </div>
                <!-- Sidebar Toggler -->
                <div class="sidebar-toggler x">
                    <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                </div>
            </div>
        </div>
        <div class="sidebar-menu">
            <ul class="menu">
                <li class="sidebar-title">Menu</li>

                <li class="sidebar-item {{ activeState('admin.dashboard') }}">
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-link">
                        <i class="bi bi-grid-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="sidebar-item {{ activeState('admin.participant.index') }}">
                    <a href="{{ route('admin.participant.index') }}" class="sidebar-link">
                        <i class="bi bi-people-fill"></i>
                        <span>Participant</span>
                    </a>
                </li>

                <li class="sidebar-item {{ activeState('admin.contest.index') }}">
                    <a href="{{ route('admin.contest.index') }}" class="sidebar-link">
                        <i class="bi bi-trophy-fill"></i>
                        <span>Contest</span>
                    </a>
                </li>

                <li class="sidebar-item {{ activeState('admin.team-contest.index') }}">
                    <a href="{{ route('admin.team-contest.index') }}" class="sidebar-link">
                        <i class="bi bi-people-fill"></i>
                        <span>Team</span>
                    </a>
                </li>

                <li class="sidebar-item {{ activeState('admin.score.index') }}">
                    <a href="{{ route('admin.score.index') }}" class="sidebar-link">
                        <i class="bi bi-person-fill"></i>
                        <span>Approve Score</span>
                    </a>
                </li>

                <!-- Logout Section -->
                <li class="sidebar-item">
                    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-flex align-items-center">
                        @csrf
                        <button type="submit" class="btn sidebar-link w-100 text-danger text-decoration-none d-flex align-items-center">
                            <i class="bi bi-box-arrow-right"></i>
                            <span class="ms-2">Logout</span>
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>
