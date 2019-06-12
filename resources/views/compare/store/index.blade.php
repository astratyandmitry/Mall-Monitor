@php /** @var array $graph */ @endphp
@php /** @var \stdClass[] $statistics */ @endphp
@php /** @var \App\Models\Cheque[] $cheques */ @endphp
@php /** @var \App\Models\Store $store */ @endphp
@php /** @var array $graphDateTypes*/ @endphp

@extends('layouts.app', $globals)

@section('content')
    <div class="heading">
        <div class="container">
            <div class="heading-content has-action">
                <div class="heading-text">
                    {{ $globals['title'] }}
                </div>

                <div class="heading-action">
                    @include('layouts.includes.field.dropdown', [
                        'attribute' => 'graph_date_type',
                        'options'  => $graph_date_types,
                        'without_placeholder' => true,
                    ])
                </div>
            </div>
        </div>
    </div>

    @include('compare.store.partials.filter')

    <div class="content">
        <div class="container">
            @if (count($statistics))
                <div class="box">
                    <div class="box-title">
                        <div class="box-title-text">
                            Сумма продаж
                        </div>
                    </div>

                    <div class="box-content">
                        <canvas id="statistics-amount" class="rounded-sm mb-16" height="80vh"></canvas>
                    </div>
                </div>

                <div class="box is-marged">
                    <div class="box-title">
                        <div class="box-title-text">
                            Количество продаж
                        </div>
                    </div>

                    <div class="box-content">
                        <canvas id="statistics-count" class="rounded-sm mb-16" height="80vh"></canvas>
                    </div>
                </div>

                <div class="box is-marged">
                    <div class="box-title">
                        <div class="box-title-text">
                            Средний чек
                        </div>
                    </div>

                    <div class="box-content">
                        <canvas id="statistics-avg" class="rounded-sm mb-16" height="80vh"></canvas>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @if (! count($statistics))
        <div class="information">
            <div class="container">
                <div class="information-box is-lg">
                    <div class="information-box-text">
                        Информация по указанному запросу отсутствует
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
    <script>
        Chart.defaults.global.tooltips.callbacks.label = function (tooltipItem) {
            return addCommas(tooltipItem.yLabel);
        }

        colors = [
            "#000000",
            "#ffebcd",
            "#0000ff",
            "#8a2be2",
            "#a52a2a",
            "#deb887",
            "#5f9ea0",
            "#7fff00",
            "#d2691e",
            "#ff7f50",
            "#6495ed",
            "#dc143c",
            "#00ffff",
            "#00008b",
            "#008b8b",
            "#b8860b",
            "#a9a9a9",
            "#006400",
            "#a9a9a9",
            "#bdb76b",
            "#8b008b",
            "#556b2f",
            "#ff8c00",
            "#9932cc",
            "#8b0000",
            "#e9967a",
            "#8fbc8f",
            "#483d8b",
            "#00ced1",
            "#9400d3",
            "#ff1493",
            "#00bfff",
            "#1e90ff",
            "#b22222",
            "#228b22",
            "#ff00ff",
            "#daa520",
            "#ffd700",
            "#808080",
            "#008000",
            "#adff2f",
            "#808080",
            "#ff69b4",
            "#cd5c5c",
            "#4b0082",
            "#7cfc00",
            "#fffacd",
            "#add8e6",
            "#f08080",
            "#90ee90",
            "#d3d3d3",
            "#ffb6c1",
            "#ffa07a",
            "#20b2aa",
            "#87cefa",
            "#778899",
            "#32cd32",
            "#faf0e6",
            "#ff00ff",
            "#800000",
            "#66cdaa",
            "#0000cd",
            "#ba55d3",
            "#9370db",
            "#3cb371",
            "#7b68ee",
            "#00fa9a",
            "#48d1cc",
            "#c71585",
            "#191970",
            "#808000",
            "#6b8e23",
            "#ffa500",
            "#ff4500",
            "#da70d6",
            "#98fb98",
            "#afeeee",
            "#db7093",
            "#ffdab9",
            "#cd853f",
            "#ffc0cb",
            "#b0e0e6",
            "#800080",
            "#663399",
            "#ff0000",
            "#bc8f8f",
            "#4169e1",
            "#8b4513",
            "#fa8072",
            "#f4a460",
            "#2e8b57",
            "#fff5ee",
            "#a0522d",
            "#c0c0c0",
            "#87ceeb",
            "#6a5acd",
            "#708090",
            "#fffafa",
            "#00ff7f",
            "#4682b4",
            "#d2b48c",
            "#008080",
            "#d8bfd8",
            "#ff6347",
            "#40e0d0",
            "#ee82ee",
            "#ffff00",
            "#9acd32"
        ];

        function getColor() {
            return colors[Math.floor(Math.random() * colors.length)];
        }

        function addCommas(nStr) {
            nStr += '';
            x = nStr.split('.');
            x1 = x[ 0 ];
            x2 = x.length > 1 ? '.' + x[ 1 ] : '';
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1)) {
                x1 = x1.replace(rgx, '$1' + ',' + '$2');
            }
            return x1 + x2;
        }

        new Chart('statistics-amount', {
            type: 'line',
            data: {
                labels: @json(array_values($graph['labels'])),
                datasets: [
                        @foreach($graph['amount'] as $store_id => $_data)
                    {
                        label: '{{ $store_names[$store_id] }}',
                        borderColor: getColor(),
                        data: @json(compare_data(array_keys($graph['labels']), $_data)),
                    },
                    @endforeach
                ]
            }
        });

        new Chart('statistics-count', {
            type: 'line',
            data: {
                labels: @json(array_values($graph['labels'])),
                datasets: [
                        @foreach($graph['count'] as $store_id => $_data)
                    {
                        label: '{{ $store_names[$store_id] }}',
                        borderColor: getColor(),
                        data: @json(compare_data(array_keys($graph['labels']), $_data)),
                    },
                    @endforeach
                ]
            }
        });

        new Chart('statistics-avg', {
            type: 'line',
            data: {
                labels: @json(array_values($graph['labels'])),
                datasets: [
                        @foreach($graph['avg'] as $store_id => $_data)
                    {
                        label: '{{ $store_names[$store_id] }}',
                        borderColor: getColor(),
                        data: @json(compare_data(array_keys($graph['labels']), $_data)),
                    },
                    @endforeach
                ]
            }
        });
    </script>
@endpush
