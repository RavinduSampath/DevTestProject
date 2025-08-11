<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Electricity Bill</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('meter-reader.index') }}">Reader</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('customers.index') }}">Customer</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('setLocale', 'en') }}">English</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('setLocale', 'si') }}">සිංහල</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#">Profile</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
