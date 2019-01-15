@extends('layouts.app', $globals)

@section('content')
    <div class="shadow-lg rounded">
        <div class="p-8">
            <h1 class="mb-8">
                <a target="_blank"
                   href="{{ route('daily_report.export', ['date' => @$_GET['date'], 'store_id' => @$_GET['store_id']]) }}"
                   class="float-right no-underline text-sm bg-green py-2 px-4 text-white rounded font-normal hover:bg-green-light cursor-pointer">
                    Экспортировать в Excel
                </a>

                {{ $currentMall->name }}
                <span class="text-grey-darker font-normal">/ {{ $globals['title'] }}</span>
            </h1>

            <form method="get" class="p-8 bg-grey-lighter rounded-sm mb-8">
                <div class="w-full relative">
                    <div class="text-sm font-bold text-grey-dark mb-2">
                        Период выборки
                    </div>

                    <select name="date" id="date"
                            class="block appearance-none w-full text-sm bg-white border border-grey-light text-grey-darker py-2 px-4 rounded leading-tight focus:outline-none">
                        @foreach($dates as $date)
                            <option value="{{ $date }}"
                                    @if ($selected_date == $date) selected @endif>{{ $date }}</option>
                        @endforeach
                    </select>

                    <div class="pointer-events-none absolute pin-y pin-r flex items-center pr-4 pt-6 text-grey-darker">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                        </svg>
                    </div>
                </div>

                <div class="w-full relative mt-4">
                    <div class="text-sm font-bold text-grey-dark mb-2">
                        Заведение
                    </div>

                    <select name="store_id" id="store_id"
                            class="block appearance-none w-full text-sm bg-white border border-grey-light text-grey-darker py-2 px-4 rounded leading-tight focus:outline-none">
                        <option></option>
                        @foreach(\App\Models\Store::all() as $store)
                            <option value="{{ $store->id }}"
                                    @if ($selected_store == $store->id) selected @endif>{{ $store->name }}</option>
                        @endforeach
                    </select>

                    <div class="pointer-events-none absolute pin-y pin-r flex items-center pr-4 pt-6 text-grey-darker">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                        </svg>
                    </div>
                </div>

                <div class="mt-4 text-right">
                    <button type="submit"
                            class="text-sm bg-indigo py-2 outline-none px-4 text-white rounded font-normal hover:bg-indigo-dark cursor-pointer">
                        Применить фильтр
                    </button>
                </div>
            </form>

            @if (count($cheques))
                <div class="statistics">
                    <div class="pb-4 font-bold flex w-full">
                        @if ($selected_store)
                            <div class="pl-4 text-grey-darker w-full">
                                Код касссы
                            </div>
                        @else
                            <div class="pl-4 text-grey-darker w-full">
                                Заведение
                            </div>

                            <div class="px-4 text-grey-darker w-96">
                                Код касссы
                            </div>
                        @endif

                        <div class="px-4 text-grey-darker w-96">
                            Номер документа
                        </div>

                        <div class="px-4 text-grey-darker w-96">
                            Тип операции
                        </div>

                        <div class="px-4 text-grey-darker w-64 text-right">
                            Сумма
                        </div>

                        <div class="pr-4 text-grey-darker text-right w-96">
                            Дата и время
                        </div>
                    </div>

                    @php $amount = 0 @endphp
                    @php $count = 0 @endphp
                    @foreach($cheques as $cheque)
                        <div class="border-t border-grey-lighter flex w-full py-4 hover:bg-grey-lighter hover:rounded-sm hover:border-transparent">
                            @if ($selected_store)
                                <div class="w-full pl-4">
                                    {{ $cheque->kkm_code }}
                                </div>
                            @else
                                <div class="w-full pl-4 text-grey-darkest">
                                    {{ $cheque->store->name }}
                                </div>

                                <div class="text-grey-darkest w-96 px-4">
                                    {{ $cheque->kkm_code }}
                                </div>
                            @endif

                            <div class="text-grey-darkest w-96 px-4">
                                {{ $cheque->number }}
                            </div>

                            <div class="text-grey-darkest w-96 px-4">
                                {{ $cheque->type->name }}
                            </div>

                            <div class="text-grey-darkest w-64 px-4 text-right">
                                {{ number_format($cheque->amount) }} ₸
                            </div>

                            <div class="text-grey-darkest text-right w-96 pr-4">
                                {{ $cheque->created_at->format('d.m.Y H:i:s') }}
                            </div>
                        </div>
                    @endforeach

                    <div class="rounded-sm font-bold flex w-full py-4 bg-grey-light">
                        <div class="pl-4 text-grey-darker w-1/2">
                            Количество чеков: {{ number_format($statistic->count()) }}
                        </div>

                        <div class="pr-4 text-grey-darker text-right w-1/2">
                            Сумма: {{ number_format($statistic->sum('amount')) }} ₸
                        </div>
                    </div>

                    {{ $cheques->appends(getNotEmptyQueryParameters())->links('vendor.pagination.default') }}
                </div>
            @else
                <div class="bg-grey -mx-8 px-8 py-16 -mb-8 rounded-b">
                    <div class="text-white text-center text-lg font-light">
                        Информация по заведениям отсутствует
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
