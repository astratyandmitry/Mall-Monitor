<div class="filter {{ isRequestEmpty() ? 'is-hidden' : '' }}">
    <div class="container">
        <form method="GET" class="filter-form">
            @include('layouts.includes.field.hidden', ['attribute' => 'sort_key', 'value' => 'id'])
            @include('layouts.includes.field.hidden', ['attribute' => 'sort_type', 'value' => 'asc'])

            @if ( ! $currentUser->mall_id)
                @include('layouts.includes.form.dropdown', [
                    'attribute' => 'mall_id',
                    'value' => request()->query('mall_id'),
                    'label' => 'ТРЦ',
                    'options' => \App\Repositories\MallRepository::getOptions(),
                ])
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
