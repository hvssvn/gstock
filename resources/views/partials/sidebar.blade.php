<!-- begin #sidebar -->
<div id="sidebar" class="sidebar">
    <!-- begin sidebar scrollbar -->
    <div data-scrollbar="true" data-height="100%">
        <!-- begin sidebar user -->
        <ul class="nav">
            <li class="nav-profile">
                <a href="javascript:;" data-toggle="nav-profile">
                    <div class="cover with-shadow"></div>
                    <div class="image">
                        <img src="{{ asset('assets/img/user/user-13.jpg') }}" alt="" />
                    </div>
                    <div class="info">
                        <b class="caret pull-right"></b>{{ Auth::user()->prenom }} {{ Auth::user()->nom }}
                        <small>{{ Auth::user()->role->nom ?? 'N/A' }}</small>
                    </div>
                </a>
            </li>
            <li>
                <ul class="nav nav-profile">
                    <li><a href="javascript:;"><i class="fa fa-envelope"></i> {{ Auth::user()->email }}</a></li>
                    <li><a href="javascript:;"><i class="fa fa-cog"></i> Mon Profil</a></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="dropdown-item" type="submit"><i class="fa fa-power-off"></i> Se déconnecter</button>
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
        <!-- end sidebar user -->
        <!-- begin sidebar nav -->
        <ul class="nav">

            <li class="nav-header">Navigation</li>

            <li class="{{ request()->is('dashboard') ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}">
                    <i class="fa fa-home"></i>
                    <span>Tableau de bord</span>
                </a>
            </li>

            <li class="has-sub {{ request()->is('users*') || request()->is('roles*') ? 'active' : '' }}">
                <a href="javascript:;">
                    <b class="caret"></b>
                    <i class="fa fa-users"></i>
                    <span>Utilisateurs</span>
                </a>
                <ul class="sub-menu">
                    <li><a href="{{ url('/users') }}">Liste des utilisateurs</a></li>
                    <li><a href="{{ url('/roles') }}">Rôles</a></li>
                </ul>
            </li>

            <li class="has-sub {{ request()->is('boutiques*') ? 'active' : '' }}">
                <a href="javascript:;">
                    <b class="caret"></b>
                    <i class="fa fa-store"></i>
                    <span>Boutiques</span>
                </a>
                <ul class="sub-menu">
                    <li><a href="{{ url('/boutiques') }}">Liste des boutiques</a></li>
                </ul>
            </li>

            <li class="has-sub {{ request()->is('produits*') || request()->is('categories*') || request()->is('mvtstocks*') ? 'active' : '' }}">
                <a href="javascript:;">
                    <b class="caret"></b>
                    <i class="fa fa-box"></i>
                    <span>Produits</span>
                </a>
                <ul class="sub-menu">
                    <li><a href="{{ url('/produits') }}">Tous les produits</a></li>
                    <li><a href="{{ url('/categories') }}">Catégories</a></li>
                    <li><a href="{{ url('/mvtstocks') }}">Mouvements de stock</a></li>
                </ul>
            </li>

            <li class="has-sub {{ request()->is('ventes*') || request()->is('ligneventes*') || request()->is('resumerjournaliers*') ? 'active' : '' }}">
                <a href="javascript:;">
                    <b class="caret"></b>
                    <i class="fa fa-shopping-cart"></i>
                    <span>Ventes</span>
                </a>
                <ul class="sub-menu">
                    <li><a href="{{ url('/ventes') }}">Historique des ventes</a></li>
                    <li><a href="{{ url('/ligneventes') }}">Lignes de vente</a></li>
                    <li><a href="{{ url('/resumerjournaliers') }}">Résumé journalier</a></li>
                </ul>
            </li>

            <li class="{{ request()->is('depenses*') ? 'active' : '' }}">
                <a href="{{ url('/depenses') }}">
                    <i class="fa fa-money-bill-wave"></i>
                    <span>Dépenses</span>
                </a>
            </li>

            <li class="{{ request()->is('historiques*') ? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-history"></i>
                    <span>
                        Historique &nbsp;
                        <span class="badge bg-warning text-dark" style="font-size: 8px;">Coming Soon</span>
                    </span>
                </a>
            </li>

            <!-- bouton pour réduire la sidebar -->
            <li>
                <a href="javascript:;" class="sidebar-minify-btn" data-click="sidebar-minify">
                    <i class="fa fa-angle-double-left"></i>
                </a>
            </li>
        </ul>
        <!-- end sidebar nav -->
    </div>
    <!-- end sidebar scrollbar -->
</div>
<div class="sidebar-bg"></div>
<!-- end #sidebar -->