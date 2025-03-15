<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a>
                    </li>
                    @if (View::hasSection('breadcrumb'))
                        <li class="breadcrumb-item"><a href="@yield('url')">@yield('breadcrumb')</a></li>
                    @endif
                    <li class="breadcrumb-item" aria-current="page">@yield('breadcrumb-text')</li>
                </ul>
            </div>
            <div class="col-md-12">
                <div class="page-header-title">
                    <h2 class="mb-0">@yield('title')</h2>
                </div>
            </div>
        </div>
    </div>
</div>
