<div class="filter">
    <div class="container">
        <form method="GET" class="filter-form">
            @if ( ! $currentUser->mall_id)
                @include('layouts.includes.form.dropdown', [
                    'attribute' => 'mall_id',
                    'value' => request()->query('mall_id'),
                    'label' => 'ТРЦ',
                    'options' => \App\Repositories\MallRepository::getOptions(),
                    'placeholder' => 'Все',
                ])
            @endif

            <div class="divider"></div>

            <div class="grid">
                @include('layouts.includes.form.dropdown', [
                    'attribute' => 'current_type',
                    'value' => request()->query('current_type'),
                    'label' => 'Текущий период',
                    'options' => \App\Storage::$filterCurrentTypes,
                    'placeholder' => 'Указать вручную',
                ])

                @include('layouts.includes.form.dropdown', [
                    'attribute' => 'past_type',
                    'value' => request()->query('past_type'),
                    'label' => 'Предыдущий период',
                    'options' => \App\Storage::$filterPastTypes,
                    'disabled' => request()->query('current_type') == '',
                    'placeholder' => 'Пердыдущий текущему периоду',
                ])
            </div>

            <div class="{{ request()->query('current_type') || isRequestEmpty() ? 'is-hidden' : '' }}" id="manual-dates">
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
            </div>

            <button type="submit" class="btn">Применить фильтр</button>
        </form>
    </div>
</div>

@push('scripts')
    <script>
        $(function () {
            $('#current_type').on('change', function () {
                $('#past_type').attr('disabled', !$(this).val());

                if ($(this).val()) {
                    $('#manual-dates').slideUp(160);
                    $('#manual-dates input').each(function () {
                        $(this).val('');
                    });
                    $('#manual-dates .picker-time').attr('disabled', true);
                } else {
                    $('#manual-dates').slideDown(160);
                }
            });
        });
    </script>
@endpush
