<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
    <div class="container">

        <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="navbar-nav mx-auto me-auto mb-2 mb-md-0">
                <li class="nav-item">
                    <a class="nav-link" href="/"><span class="fas fa-fw fa-home"></span> Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ ($title == 'person' ? 'active' : null) }}" aria-current="page" href="{{ route('person') }}"><span class="fas fa-fw fa-user"></span> Person</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ ($title == 'organization' ? 'active' : null) }}" href="{{ route('organization') }}"><span class="fas fa-fw fa-dungeon"></span> Organization</a>
                </li>
            </ul>
        </div>
    </div>
</nav>