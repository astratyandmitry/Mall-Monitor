<header class="bg-indigo-darker fixed w-full pin-t shadow-md py-4">
    <div class="container flex items-center justify-between flex-wrap">
        <a href="{{ route('dashboard') }}" class="mr-12 no-underline flex flex-wrap items-center font-light text-indigo-darker uppercase">
            <div class="text-white text-white text-2xl font-bold">Mall</div>
            <div class="ml-2 font-bold text-xs bg-indigo text-indigo-lightest shadow py-2 px-4 rounded-full">Monitor</div>
        </a>

        <div class="flex-grow">
            <ul class="list-reset font-light flex-grow">
                <li class="inline-block mr-4">
                    <a href="{{ route('dashboard') }}"
                       class="no-underline  hover:text-white {{ $active == 'dashboard' ? 'font-normal text-white' : 'text-indigo-lightest' }}">
                        Обзор
                    </a>
                </li>
                <li class="inline-block mr-4">
                    <a href="{{ route('stores.index') }}"
                       class="no-underline  hover:text-white {{ $active == 'stores' ? 'font-normal text-white' : 'text-indigo-lightest' }}">
                        Заведения
                    </a>
                </li>
                <li class="inline-block mr-4">
                    <a href="{{ route('statistics.index') }}"
                       class="no-underline  hover:text-white {{ $active == 'statistics' ? 'font-normal text-white' : 'text-indigo-lightest' }}">
                        Статистика
                    </a>
                </li>
            </ul>
        </div>

        @isset ($currentMall)
            <div>
                <div class="cursor-pointer font-hairline text-indigo-lightest hover:text-white">
                    {{ $currentMall }}
                </div>
            </div>
        @endisset
    </div>
</header>
