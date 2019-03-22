@extends('layouts.app', $globals)

@section('content')
    <div class="shadow-lg rounded">
        <div class="p-8">
            <h1 class="mb-8">
                <a target="_blank"
                   href="{{ route('report_mall.export', ['date_from' => @$_GET['date_from'],'date_to' => @$_GET['date_to']]) }}"
                   class="float-right no-underline text-sm bg-green py-2 px-4 text-white rounded font-normal hover:bg-green-light cursor-pointer">
                    Экспортировать в Excel
                </a>

                {{ $currentMall->name }}
                <span class="text-grey-darker font-normal">/ {{ $globals['title'] }}</span>
            </h1>

            <form method="get" class="p-8 bg-grey-lighter rounded-sm mb-8">
                <div class="w-full">
                    <div class="flex mt-4">
                        <div class="w-1/2 mr-4">
                            <div class="text-sm font-bold text-grey-dark mb-2">
                                Дата начала
                            </div>

                            <input type="datetime-local" name="date_from" placeholder="Начиная" value="{{ @$_GET['date_from'] }}"
                                   class="block appearance-none text-sm w-full bg-white border border-grey-light text-grey-darker py-2 px-4 rounded leading-tight focus:outline-none">
                        </div>

                        <div class="w-1/2 pl-4">
                            <div class="text-sm font-bold text-grey-dark mb-2">
                                Дата конца
                            </div>

                            <input type="datetime-local" name="date_to" placeholder="Заканчивая" value="{{ @$_GET['date_to'] }}"
                                   class="block appearance-none text-sm w-full bg-white border border-grey-light text-grey-darker py-2 px-4 rounded leading-tight focus:outline-none">
                        </div>
                    </div>
                </div>

                <div class="mt-4 text-right">
                    <button type="submit"
                            class="text-sm bg-indigo py-2 outline-none px-4 text-white rounded font-normal hover:bg-indigo-dark cursor-pointer">
                        Применить фильтр
                    </button>
                </div>
            </form>

            @if (count($statistics))
                <div class="statistics">
                    <div class="pb-4 font-bold flex w-full">
                        <div class="pl-4 text-grey-darker w-full">
                            ТРЦ
                        </div>

                        <div class="px-4 text-grey-darker w-48">
                            Кол-во чеков
                        </div>

                        <div class="px-4 text-grey-darker w-48">
                            Средний чек
                        </div>

                        <div class="pr-4 text-grey-darker text-right w-64">
                            Сумма чеков
                        </div>
                    </div>

                    @php $amount = 0 @endphp
                    @php $count = 0 @endphp
                    @foreach($statistics as $statistic)
                        @php $amount += $statistic['amount'] @endphp
                        @php $count += $statistic['count'] @endphp
                        @php $mall = \App\Models\Mall::find($statistic['mall_id']) @endphp

                        <div class="border-t border-grey-lighter flex w-full py-4 hover:bg-grey-lighter hover:rounded-sm hover:border-transparent">
                            <div class="w-full text-grey-darkest pl-4">
                                {{ $mall->name }}
                            </div>

                            <div class="text-grey-darkest w-48 px-4">
                                {{ number_format($statistic['count']) }}
                            </div>

                            <div class="text-grey-darkest w-48 px-4">
                                {{ number_format(round($statistic['amount'] / $statistic['count'])) }} ₸
                            </div>

                            <div class="text-grey-darkest text-right w-64 pr-4">
                                {{ number_format($statistic['amount']) }} ₸
                            </div>
                        </div>
                    @endforeach

                    <div class="rounded-sm font-bold flex w-full py-4 bg-grey-light">
                        <div class="pl-4 text-grey-darker w-full"></div>

                        <div class="px-4 text-grey-darker w-48">
                            {{ number_format($count) }}
                        </div>

                        <div class="px-4 text-grey-darker w-48">
                            {{ number_format(round($amount / $count)) }} ₸
                        </div>

                        <div class="pr-4 text-grey-darker text-right w-64">
                            {{ number_format($amount) }} ₸
                        </div>
                    </div>
                </div>
            @endif

            @if (! count($statistics))
                <div class="bg-grey -mx-8 px-8 py-16 -mb-8 rounded-b">
                    <div class="text-white text-center text-lg font-light">
                        Информация по заведениям отсутствует
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function () {
            var customDate = {
                $select: $('select#date_type'),
                $dates: $('#custom-date')
            };

            customDate.$select.on('change', function () {
                if ($(this).val() == 4) {
                    customDate.$dates.slideDown(160);
                } else {
                    customDate.$dates.slideUp(160);
                    customDate.$dates.find('input').val('');
                }
            });
        });
    </script>
@endpush
