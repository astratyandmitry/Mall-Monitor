<div class="filter">
    <div class="container">
        <form method="GET" class="filter-form">
            @include('layouts.includes.field.hidden', ['attribute' => 'sort_key', 'value' => 'id'])
            @include('layouts.includes.field.hidden', ['attribute' => 'sort_type', 'value' => 'asc'])

            <div class="grid">
                <div class="grid-sub">
                    <div class="grid-title">Текущий период</div>
                    <div class="fake-grid"></div>

                    @include('layouts.includes.form.input', [
                        'attribute' => 'current_date_from',
                        'value' => request()->query('current_date_from'),
                        'label' => 'Дата начала',
                        'placeholder' => 'dd.mm.yyyy',
                        'classes' => 'picker-date',
                    ])

                    @include('layouts.includes.form.input', [
                        'attribute' => 'current_time_from',
                        'value' => request()->query('current_time_from'),
                        'label' => 'Время начала',
                        'placeholder' => 'HH:ii',
                        'classes' => 'picker-time',
                    ])

                    @include('layouts.includes.form.input', [
                         'attribute' => 'current_date_to',
                         'value' => request()->query('current_date_to'),
                         'label' => 'Дата окончания',
                         'placeholder' => 'dd.mm.yyyy',
                         'classes' => 'picker-date',
                     ])

                    @include('layouts.includes.form.input', [
                        'attribute' => 'current_time_to',
                        'value' => request()->query('current_time_to'),
                        'label' => 'Время окончания',
                        'placeholder' => 'HH:ii',
                        'classes' => 'picker-time',
                    ])
                </div>

                <div class="grid-sub">
                    <div class="grid-title">Пердыдущий период</div>
                    <div class="fake-grid"></div>

                    @include('layouts.includes.form.input', [
                        'attribute' => 'past_date_from',
                        'value' => request()->query('past_date_from'),
                        'label' => 'Дата начала',
                        'placeholder' => 'dd.mm.yyyy',
                        'classes' => 'picker-date',
                    ])

                    @include('layouts.includes.form.input', [
                        'attribute' => 'past_time_from',
                        'value' => request()->query('past_time_from'),
                        'label' => 'Время начала',
                        'placeholder' => 'HH:ii',
                        'classes' => 'picker-time',
                    ])

                    @include('layouts.includes.form.input', [
                         'attribute' => 'past_date_to',
                         'value' => request()->query('past_date_to'),
                         'label' => 'Дата окончания',
                         'placeholder' => 'dd.mm.yyyy',
                         'classes' => 'picker-date',
                     ])

                    @include('layouts.includes.form.input', [
                        'attribute' => 'past_time_to',
                        'value' => request()->query('past_time_to'),
                        'label' => 'Время окончания',
                        'placeholder' => 'HH:ii',
                        'classes' => 'picker-time',
                    ])
                </div>
            </div>

            <button type="submit" class="btn">Применить фильтр</button>
        </form>
    </div>
</div>
