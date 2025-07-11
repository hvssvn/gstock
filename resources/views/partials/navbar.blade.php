<!-- begin #header -->
<div id="header" class="header navbar-default">
    <!-- begin navbar-header -->
    <div class="navbar-header">
        <a href="{{ route('dashboard') }}" class="navbar-brand">
            <span class="navbar-logo"></span> <b>Daaraytech</b> App
        </a>
        <button type="button" class="navbar-toggle" data-click="sidebar-toggled">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
    </div>
    <!-- end navbar-header -->

    <!-- begin header-nav -->
    <ul class="navbar-nav navbar-right">
        <li class="dropdown navbar-user">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <img src="{{ asset('assets/img/user/user-13.jpg') }}" alt="" />
                <span class="d-none d-md-inline">{{ Auth::user()->prenom.' '.Auth::user()->nom  ?? 'Utilisateur' }} </span> <b class="caret"></b>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <a href="#" class="dropdown-item">Voir Profil</a>
                <div class="dropdown-divider"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="dropdown-item" type="submit">Se déconnecter</button>
                </form>
            </div>
        </li>
    </ul>
    <!-- end header-nav -->
</div>
<!-- end #header -->
