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
                    @foreach($globals['breadcrumbs'] as $breadcrumb)
                        <li class="breadcrumbs-list-item is-devider">
                            <span class="breadcrumbs-list-item-devider">/</span>
                        </li>
                        <li class="breadcrumbs-list-item">
                            <a class="breadcrumbs-list-item-link" href="{{ $breadcrumb['link'] }}">
                                {{ $breadcrumb['name'] }}
                            </a>
                        </li>
                    @endforeach
                @endif
                <li class="breadcrumbs-list-item is-devider">
                    <span class="breadcrumbs-list-item-devider">/</span>
                </li>
                <li class="breadcrumbs-list-item">
                    <strong class="breadcrumbs-list-item-value">
                        @if (request()->route()->getActionMethod() == 'index')
                            {{ $label ?? $title }}
                        @else
                            {{ $title }}
                        @endif
                    </strong>
                </li>
            </ul>

            <div class="breadcrumbs-user">
                <div class="breadcrumbs-user-value">
                    {{ $currentUser->family_name }} {{ $currentUser->given_name }}
                    @if ($currentUser->mall_id || $currentUser->store_id)
                        ({{ $currentUser->store_id ? $currentUser->store->name : $currentUser->mall->name }})
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
