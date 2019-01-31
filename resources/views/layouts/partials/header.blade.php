<header class="bg-indigo-darker fixed w-full pin-t shadow-md p-4 z-50">
    <div class="flex items-center justify-between flex-wrap">
        <a href="{{ route('dashboard') }}" class="mr-12 no-underline flex flex-wrap items-center font-light text-indigo-darker uppercase">
            <div class="text-white text-white text-2xl font-bold">Mall</div>
            <div class="ml-2 font-bold text-xs bg-indigo text-indigo-lightest shadow py-2 px-4 rounded-full">Monitor</div>
        </a>

        <div class="flex-grow">
            <ul class="list-reset font-light flex-grow">
                <li class="inline-block mr-4">
                    <a href="{{ route('dashboard') }}"
                       class="no-underline font-normal hover:text-white {{ $active == 'dashboard' ? 'text-white' : 'text-indigo-lightest' }}">
                        Обзор
                    </a>
                </li>
                <li class="inline-block mr-4">
                    <a href="{{ route('stores.index') }}"
                       class="no-underline font-normal hover:text-white {{ $active == 'stores' ? 'text-white' : 'text-indigo-lightest' }}">
                        Заведения
                    </a>
                </li>
                <li class="inline-block mr-4">
                    <a href="{{ route('report_mall.index') }}"
                       class="no-underline font-normal hover:text-white {{ $active == 'report_mall' ? 'text-white' : 'text-indigo-lightest' }}">
                        Отчет по ТРЦ
                    </a>
                </li>
                <li class="inline-block mr-4">
                    <a href="{{ route('report_store.index') }}"
                       class="no-underline font-normal hover:text-white {{ $active == 'report_store' ? 'text-white' : 'text-indigo-lightest' }}">
                        Отчет по арендаторам
                    </a>
                </li>
                <li class="inline-block mr-4">
                    <a href="{{ route('report_detail.index') }}"
                       class="no-underline font-normal hover:text-white {{ $active == 'report_detail' ? 'text-white' : 'text-indigo-lightest' }}">
                        Детальный отчет
                    </a>
                </li>
            </ul>
        </div>

        @isset ($currentMall)
            <div>
                <a href="{{ route('auth.signout') }}" class="no-underline text-indigo-lightest hover:text-white">Выйти</a>
                {{--<div class="cursor-pointer font-hairline text-indigo-lightest hover:text-white">--}}
                    {{--{{ $currentMall->name }}--}}
                {{--</div>--}}
            </div>
        @endisset
    </div>
</header>
