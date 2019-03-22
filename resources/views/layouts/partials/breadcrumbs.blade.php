<section class="breadcrumbs">
    <div class="container">
        <div class="breadcrumbs-content">
            <ul class="breadcrumbs-list">
                <li class="breadcrumbs-list-item">
                    <a class="breadcrumbs-list-item-link" href="{{ route('home') }}">
                        MallMonitor
                    </a>
                </li>
                @if (isset($globals['breadcrumbs']) && count($globals['breadcrumbs']))
                    @foreach($globals['breadcrumbs'] as $link => $name)
                        <li class="breadcrumbs-list-item is-devider">
                            <span class="breadcrumbs-list-item-devider">/</span>
                        </li>
                        <li class="breadcrumbs-list-item">
                            @if ($loop->last)
                                <strong class="breadcrumbs-list-item-value">{{ $name }}</strong>
                            @else
                                <a class="breadcrumbs-list-item-link" href="{{ $link }}">
                                    {{ $name }}
                                </a>
                            @endif
                        </li>
                    @endforeach
                @endif
            </ul>
        </div>
    </div>
</section>
