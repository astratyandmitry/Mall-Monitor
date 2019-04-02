@php /** @var \App\Models\Cashbox|null $entity */ @endphp

@extends('layouts.app', $globals)

@section('content')
    <div class="heading">
        <div class="container">
            <div class="heading-content">
                <div class="heading-text">
                    {{ $globals['title'] }}
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="box">
                <div class="box-title">
                    <div class="box-title-text">
                        Данные кассы
                    </div>
                </div>

                <div class="box-content">
                    <form action="{{ $action }}" method="POST">
                        @if (isset($entity))
                            @method('PUT')
                        @endif

                        @csrf

                        @includeWhen(isset($entity), 'layouts.includes.form.hidden', [
                            'attribute' => 'store_id',
                        ])

                        @includeWhen(isset($entity), 'layouts.includes.form.hidden', [
                            'attribute' => 'mall_id',
                        ])

                        <div class="form-content">
                            <div class="form-section">
                                <div class="form-grid is-2col">
                                    @include('layouts.includes.form.dropdown', [
                                          'attribute' => 'mall_id',
                                          'label' => 'ТРЦ',
                                          'options' => \App\Repositories\MallRepository::getOptions(),
                                          'disabled' => isset($entity),
                                    ])

                                    @include('layouts.includes.form.dropdown', [
                                          'attribute' => 'store_id',
                                          'label' => 'Арендатор',
                                          'options' => \App\Repositories\StoreRepository::getOptions(old('store_id', optional($entity)->mall_id ?? -1)),
                                          'disabled' => isset($entity),
                                    ])
                                </div>
                            </div>

                            <div class="form-section">
                                @include('layouts.includes.form.input', [
                                    'attribute' => 'code',
                                    'label' => 'Код',
                                    'required' => true,
                                    'autofocus' => true,
                                ])
                            </div>
                        </div>

                        <div class="form-action">
                            <button type="submit" class="btn">
                                {{ isset($entity) ? 'Сохранить изменения' : 'Добавить кассу' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function () {
            $mallID = $('#mall_id');
            $storeID = $('#store_id');

            $mallID.on('change', function () {
                $storeID.html('<option>Загрузка...</option>').attr('disabled', true);

                $.ajax({
                    method: 'POST',
                    url: '/ajax/stores',
                    data: {
                        mall_id: $mallID.val()
                    },
                    success: function (response) {
                        $storeID.html(response).attr('disabled', false);
                    }
                });
            });
        });
    </script>
@endpush