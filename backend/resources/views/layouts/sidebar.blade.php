<div class="sidebar">

    <div class="logo">
        <i class="fa-solid fa-school"></i>
        <span>Tân Lập 3</span>
    </div>

    <ul class="sidebar-menu">

        <li>
            <a href="{{ route('admin.dashboard') }}"
               class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-house"></i>
                Dashboard
            </a>
        </li>
<li>
    <a href="{{ route('admin.accounts.index') }}">
        <i class="fa-solid fa-users-gear"></i>
        <span>Quản lý tài khoản</span>
    </a>
</li>
        <li>
            <a href="{{ route('students.index') }}"
               class="{{ request()->routeIs('students.*') ? 'active' : '' }}">
                <i class="fa-solid fa-user-graduate"></i>
                Học sinh
            </a>
        </li>

        <li>
            <a href="{{ route('teachers.index') }}">
                <i class="fa-solid fa-chalkboard-user"></i>
                Giáo viên
            </a>
        </li>

        <li>
            <a href="{{ route('classes.index') }}">
                <i class="fa-solid fa-school"></i>
                Lớp học
            </a>
        </li>

<li>
    <a href="{{ route('subjects.index') }}">
        <i class="fa-solid fa-book"></i>
        Môn học
    </a>
</li>

<li>
    <a href="{{ route('scores.index') }}">
        <i class="fa-solid fa-pen"></i>
        Điểm
    </a>
</li>

<li>
    <a href="{{ route('school-years.index') }}">
        <i class="fa-solid fa-calendar-days"></i>
        Năm học
    </a>
</li>


<a href="{{ route('announcements.index') }}"
   class="nav-link">

    <i class="fa-solid fa-bell"></i>

    <span>Thông báo</span>

</a>



    </ul>

    <div class="sidebar-footer">

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="logout-btn">
                <i class="fa-solid fa-right-from-bracket"></i>
                Đăng xuất
            </button>

        </form>

    </div>

</div>