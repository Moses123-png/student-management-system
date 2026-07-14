<div class="sidebar col-md-2">
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-chart-line"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.students.*') ? 'active' : '' }}" href="{{ route('admin.students.index') }}">
                <i class="fas fa-users"></i> Students
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.classes.*') ? 'active' : '' }}" href="{{ route('admin.classes.index') }}">
                <i class="fas fa-chalkboard"></i> Classes
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.marks.*') ? 'active' : '' }}" href="{{ route('admin.marks.index') }}">
                <i class="fas fa-clipboard-list"></i> Marks
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.scholarships.*') ? 'active' : '' }}" href="{{ route('admin.scholarships.index') }}">
                <i class="fas fa-award"></i> Scholarships
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.report-cards.*') ? 'active' : '' }}" href="{{ route('admin.report-cards.index') }}">
                <i class="fas fa-file-pdf"></i> Report Cards
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.graduates.*') ? 'active' : '' }}" href="{{ route('admin.graduates.index') }}">
                <i class="fas fa-graduation-cap"></i> Graduates
            </a>
        </li>
    </ul>
</div>
