<div class="sidebar col-md-2">
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('teacher.dashboard') ? 'active' : '' }}" href="{{ route('teacher.dashboard') }}">
                <i class="fas fa-chart-line"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('teacher.class.*') ? 'active' : '' }}" href="{{ route('teacher.class.show') }}">
                <i class="fas fa-chalkboard"></i> My Class
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('teacher.students.*') ? 'active' : '' }}" href="{{ route('teacher.students.index') }}">
                <i class="fas fa-users"></i> Students
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('teacher.marks.*') ? 'active' : '' }}" href="{{ route('teacher.marks.entry') }}">
                <i class="fas fa-clipboard-list"></i> Enter Marks
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('teacher.report-cards.*') ? 'active' : '' }}" href="#">
                <i class="fas fa-file-pdf"></i> Report Cards
            </a>
        </li>
    </ul>
</div>
