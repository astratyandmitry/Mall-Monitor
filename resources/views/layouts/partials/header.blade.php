<section class="header">
    <div class="container">
        <div class="header-content">
            <div class="header-logotype">
                <a href="{{ route('home') }}" title="{{ config('app.name') }}" class="header-logotype-link">
                    {{ config('app.name') }}
                </a>
            </div>

            <div class="header-hamburger">
                <i class="fa fa-bars header-hamburger-icon"></i>
            </div>

            <div class="header-nav">
                <ul class="header-nav-list">
                    <li class="header-nav-list-item {{ isActive($active_section == 'dashboard', false) }}">
                        <a href="{{ route('dashboard') }}" class="header-nav-list-item-link">
                            Обзор
                        </a>
                    </li>
                    @if ( ! $currentUser->mall_id)
                        <li class="header-nav-list-item {{ isActive($active_section == 'malls', false) }}">
                            <a href="{{ route('malls.index') }}" class="header-nav-list-item-link">
                                ТРЦ
                            </a>
                        </li>
                    @endif
                    @if ( ! $currentUser->store_id)
                        <li class="header-nav-list-item {{ isActive($active_section == 'stores', false) }}">
                            <a href="{{ route('stores.index') }}" class="header-nav-list-item-link">
                                Арендаторы
                            </a>
                        </li>
                    @endif
                    <li class="header-nav-list-item has-dropdown {{ isActive(in_array($active_section, ['placement', 'compare', 'reports']), false) }}">
                        <a href="javascript:void(0)" class="header-nav-list-item-link">
                            Детализация
                            <i class="fa fa-angle-down"></i>
                        </a>

                        <ul class="header-nav-list-item-dropdown">
                            <li class="header-nav-list-item-dropdown-item">
                                <span class="header-nav-list-item-dropdown-item-heading">
                                    Положение
                                </span>
                            </li>
                            @if ( ! $currentUser->store_id)
                                <li class="header-nav-list-item-dropdown-item {{ isActive($active_page == 'placement.mall', false) }}">
                                    <a href="{{ route('placement.mall.index') }}" class="header-nav-list-item-dropdown-item-link">
                                        ТРЦ
                                    </a>
                                </li>
                            @endif
                            <li class="header-nav-list-item-dropdown-item {{ isActive($active_page == 'placement.store', false) }}">
                                <a href="{{ route('placement.store.index') }}" class="header-nav-list-item-dropdown-item-link">
                                    Арендаторы
                                </a>
                            </li>
                            <li class="header-nav-list-item-dropdown-item is-devider"></li>
                            <li class="header-nav-list-item-dropdown-item">
                                <span class="header-nav-list-item-dropdown-item-heading">
                                    Сравнение
                                </span>
                            </li>
                            @if ( ! $currentUser->store_id)
                                <li class="header-nav-list-item-dropdown-item {{ isActive($active_page == 'compare.mall', false) }}">
                                    <a href="{{ route('compare.mall.index') }}" class="header-nav-list-item-dropdown-item-link">
                                        ТРЦ
                                    </a>
                                </li>
                            @endif
                            <li class="header-nav-list-item-dropdown-item {{ isActive($active_page == 'compare.store', false) }}">
                                <a href="{{ route('compare.store.index') }}" class="header-nav-list-item-dropdown-item-link">
                                    Арендаторы
                                </a>
                            </li>
                            <li class="header-nav-list-item-dropdown-item is-devider"></li>
                            <li class="header-nav-list-item-dropdown-item">
                                <span class="header-nav-list-item-dropdown-item-heading">
                                    Отчеты
                                </span>
                            </li>
                            @if ( ! $currentUser->store_id)
                                <li class="header-nav-list-item-dropdown-item {{ isActive($active_page == 'reports.mall', false) }}">
                                    <a href="{{ route('reports.mall.index') }}" class="header-nav-list-item-dropdown-item-link">
                                        ТРЦ
                                    </a>
                                </li>
                            @endif
                            <li class="header-nav-list-item-dropdown-item {{ isActive($active_page == 'reports.store', false) }}">
                                <a href="{{ route('reports.store.index') }}" class="header-nav-list-item-dropdown-item-link">
                                    Арендаторы
                                </a>
                            </li>
                            <li class="header-nav-list-item-dropdown-item {{ isActive($active_page == 'reports.detail', false) }}">
                                <a href="{{ route('reports.detail.index') }}" class="header-nav-list-item-dropdown-item-link">
                                    Детальный
                                </a>
                            </li>
                        </ul>
                    </li>
                    @if ( ! $currentUser->store_id && ! $currentUser->is_readonly)
                        <li class="header-nav-list-item has-dropdown {{ isActive($active_section == 'manage', false) }}">
                            <a href="#" class="header-nav-list-item-link">
                                Управление
                                <i class="fa fa-angle-down"></i>
                            </a>

                            <ul class="header-nav-list-item-dropdown">
                                @if ( ! $currentUser->mall_id)
                                    <li class="header-nav-list-item-dropdown-item {{ isActive($active_page == 'manage.malls', false) }}">
                                        <a href="{{ route('manage.malls.index') }}" class="header-nav-list-item-dropdown-item-link">
                                            ТРЦ
                                        </a>
                                    </li>
                                @endif
                                <li class="header-nav-list-item-dropdown-item {{ isActive($active_page == 'manage.stores', false) }}">
                                    <a href="{{ route('manage.stores.index') }}" class="header-nav-list-item-dropdown-item-link">
                                        Арендаторы
                                    </a>
                                </li>
                                <li class="header-nav-list-item-dropdown-item {{ isActive($active_page == 'manage.cashboxes', false) }}">
                                    <a href="{{ route('manage.cashboxes.index') }}" class="header-nav-list-item-dropdown-item-link">
                                        Кассы
                                    </a>
                                </li>
                                @if ( ! $currentUser->mall_id)
                                    <li class="header-nav-list-item-dropdown-item is-devider"></li>
                                    <li class="header-nav-list-item-dropdown-item {{ isActive($active_page == 'manage.users', false) }}">
                                        <a href="{{ route('manage.users.index') }}" class="header-nav-list-item-dropdown-item-link">
                                            Пользователи
                                        </a>
                                    </li>
                                    <li class="header-nav-list-item-dropdown-item is-devider"></li>
                                    <li class="header-nav-list-item-dropdown-item {{ isActive($active_page == 'manage.developers', false) }}">
                                        <a href="{{ route('manage.developers.index') }}" class="header-nav-list-item-dropdown-item-link">
                                            Разработчики
                                        </a>
                                    </li>
                                    <li class="header-nav-list-item-dropdown-item {{ isActive($active_page == 'manage.store_integrations', false) }}">
                                        <a href="{{ route('manage.store_integrations.index') }}" class="header-nav-list-item-dropdown-item-link">
                                            Конфигурации
                                        </a>
                                    </li>
                                    <li class="header-nav-list-item-dropdown-item is-devider"></li>
                                    <li class="header-nav-list-item-dropdown-item {{ isActive($active_page == 'manage.store_types', false) }}">
                                        <a href="{{ route('manage.store_types.index') }}" class="header-nav-list-item-dropdown-item-link">
                                            Категории
                                        </a>
                                    </li>
                                    <li class="header-nav-list-item-dropdown-item {{ isActive($active_page == 'manage.cities', false) }}">
                                        <a href="{{ route('manage.cities.index') }}" class="header-nav-list-item-dropdown-item-link">
                                            Города
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    <li class="header-nav-list-item is-right">
                        <a href="{{ route('auth.signout') }}" class="header-nav-list-item-link">
                            Выйти
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>
