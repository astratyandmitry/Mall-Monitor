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
                    <li class="header-nav-list-item {{ isActive($active_section == 'stores', false) }}">
                        <a href="{{ route('stores.index') }}" class="header-nav-list-item-link">
                            Арендаторы
                        </a>
                    </li>
                    <li class="header-nav-list-item has-dropdown {{ isActive($active_section == 'reports', false) }}">
                        <a href="javascript:void(0)" class="header-nav-list-item-link ">
                            Отчеты
                            <i class="fa fa-angle-down"></i>
                        </a>

                        <ul class="header-nav-list-item-dropdown">
                            <li class="header-nav-list-item-dropdown-item {{ isActive($active_page == 'reports.mall', false) }}">
                                <a href="{{ route('reports.mall.index') }}" class="header-nav-list-item-dropdown-item-link">
                                    ТРЦ
                                </a>
                            </li>
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
                    <li class="header-nav-list-item has-dropdown {{ isActive($active_section == 'manage', false) }}">
                        <a href="#" class="header-nav-list-item-link">
                            Управление
                            <i class="fa fa-angle-down"></i>
                        </a>

                        <ul class="header-nav-list-item-dropdown">
                            <li class="header-nav-list-item-dropdown-item {{ isActive($active_page == '#', false) }}">
                                <a href="#" class="header-nav-list-item-dropdown-item-link">
                                    ТРЦ
                                </a>
                            </li>
                            <li class="header-nav-list-item-dropdown-item {{ isActive($active_page == '#', false) }}">
                                <a href="#" class="header-nav-list-item-dropdown-item-link">
                                    Арендаторы
                                </a>
                            </li>
                            <li class="header-nav-list-item-dropdown-item {{ isActive($active_page == '#', false) }}">
                                <a href="#" class="header-nav-list-item-dropdown-item-link">
                                    Кассы
                                </a>
                            </li>
                            <li class="header-nav-list-item-dropdown-item is-devider"></li>
                            <li class="header-nav-list-item-dropdown-item {{ isActive($active_page == '#', false) }}">
                                <a href="#" class="header-nav-list-item-dropdown-item-link">
                                    Категории
                                </a>
                            </li>
                            <li class="header-nav-list-item-dropdown-item is-devider"></li>
                            <li class="header-nav-list-item-dropdown-item {{ isActive($active_page == '#', false) }}">
                                <a href="#" class="header-nav-list-item-dropdown-item-link">
                                    Пользователи
                                </a>
                            </li>
                        </ul>
                    </li>
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
