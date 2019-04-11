<div class="filter {{ isRequestEmpty() ? 'is-hidden' : '' }}">
    <div class="container">
        <form method="GET" class="filter-form">
            @include('layouts.includes.field.hidden', ['attribute' => 'sort_key', 'value' => 'store_id'])
            @include('layouts.includes.field.hidden', ['attribute' => 'sort_type', 'value' => 'asc'])

            @if ( ! $currentUser->store_id)
                @if ( ! $currentUser->mall_id)
                    @include('layouts.includes.form.dropdown', [
                        'attribute' => 'mall_id',
                        'value' => request()->query('mall_id'),
                        'label' => 'ТРЦ',
                        'placeholder' => 'Все',
                        'options' => \App\Repositories\MallRepository::getOptions(),
                    ])
                @endif

                <div class="grid is-3">
                    @if (request()->get('mall_id'))
                        @include('layouts.includes.form.dropdown', [
                           'attribute' => 'store_id',
                            'value' => request()->query('store_id'),
                           'placeholder' => 'Все',
                           'label' => 'Бренд',
                           'options' => \App\Repositories\StoreRepository::getOptions(request()->get('mall_id')),
                       ])

                        @include('layouts.includes.form.dropdown', [
                           'attribute' => 'store_legal',
                            'value' => request()->query('store_legal'),
                           'placeholder' => 'Все',
                           'label' => 'Юр. наименование',
                           'options' => \App\Repositories\StoreRepository::getLegalOptions(request()->get('mall_id')),
                       ])
                    @else
                        @include('layouts.includes.form.dropdown-grouped', [
                           'attribute' => 'store_id',
                           'placeholder' => 'Все',
                           'value' => request()->query('store_id'),
                           'label' => 'Бренд',
                           'options' => \App\Repositories\StoreRepository::getOptionsGrouped(),
                       ])

                        @include('layouts.includes.form.dropdown-grouped', [
                            'attribute' => 'store_legal',
                            'placeholder' => 'Все',
                            'value' => request()->query('store_legal'),
                            'label' => 'Юр. наименование',
                            'options' => \App\Repositories\StoreRepository::getLegalOptionsGrouped(),
                        ])
                    @endif

                    @include('layouts.includes.form.input', [
                        'attribute' => 'store_bin',
                        'value' => request()->query('store_bin'),
                        'label' => 'БИН',
                        'placeholder' => 'Любой',
                    ])
                </div>

                @if (request()->query('store_id') || request()->query('store_legal'))
                    @include('layouts.includes.form.dropdown', [
                       'attribute' => 'cashbox_id',
                        'value' => request()->query('cashbox_id'),
                       'placeholder' => 'Все',
                       'label' => 'Номер кассы',
                       'options' => \App\Repositories\CashboxRepository::getOptionsForStore(request()->query('store_id', request()->query('store_legal'))),
                   ])
                @endif

                <div class="grid">
                    @include('layouts.includes.form.dropdown', [
                        'attribute' => 'type_id',
                        'value' => request()->query('type_id'),
                        'label' => 'Категория',
                        'placeholder' => 'Все',
                        'options' => \App\Repositories\StoreTypeRepository::getOptions(),
                    ])

                    <div class="grid-sub is-sm-2">
                        @include('layouts.includes.form.dropdown', [
                           'attribute' => 'sort',
                            'value' => request()->query('sort'),
                           'placeholder' => 'Все',
                           'label' => 'ТОП',
                           'options' => $store_sort,
                       ])

                        @include('layouts.includes.form.input', [
                           'attribute' => 'limit',
                           'value' => request()->query('limit'),
                           'placeholder' => 'Все',
                           'label' => 'Ограничение',
                           'type' => 'number',
                       ])
                    </div>
                </div>
            @endif

            <div class="grid">
                <div class="grid-sub">
                    @include('layouts.includes.form.input', [
                        'attribute' => 'date_from',
                        'value' => request()->query('date_from'),
                        'label' => 'Дата начала',
                        'placeholder' => 'dd.mm.yyyy',
                    ])

                    @include('layouts.includes.form.input', [
                        'attribute' => 'time_from',
                        'value' => request()->query('time_from'),
                        'label' => 'Время начала',
                        'placeholder' => 'HH:ii',
                    ])
                </div>

                <div class="grid-sub">
                    @include('layouts.includes.form.input', [
                         'attribute' => 'date_to',
                         'value' => request()->query('date_to'),
                         'label' => 'Дата окончания',
                         'placeholder' => 'dd.mm.yyyy',
                     ])

                    @include('layouts.includes.form.input', [
                        'attribute' => 'time_to',
                        'value' => request()->query('time_to'),
                        'label' => 'Время окончания',
                        'placeholder' => 'HH:ii',
                    ])
                </div>
            </div>

            <button type="submit" class="btn">Применить фильтр</button>
        </form>
    </div>
</div>
