<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">{{ __('messages.app_title') }}</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('meter-reader.index') }}">{{ __('messages.reader') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('customers.index') }}">{{ __('messages.customer') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('setLocale', 'en') }}">English</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('setLocale', 'si') }}">සිංහල</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#">{{ __('messages.customer_account') }}</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
