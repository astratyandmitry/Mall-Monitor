@php /** @var \App\Models\Mall|null $entity */ @endphp

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
                        Данные арендатора
                    </div>
                </div>

                <div class="box-content">
                    <form action="{{ $action }}" method="POST">
                        @if (isset($entity))
                            @method('PUT')
                        @endif

                        @csrf

                        @includeWhen(isset($entity), 'layouts.includes.form.hidden', [
                            'attribute' => 'mall_id',
                        ])

                        <div class="form-content">
                            <div class="form-section">
                                @include('layouts.includes.form.dropdown', [
                                      'attribute' => 'mall_id',
                                      'label' => 'ТРЦ',
                                      'options' => \App\Repositories\MallRepository::getOptions(),
                                      'required' => true,
                                      'disabled' => isset($entity),
                                  ])

                                @include('layouts.includes.form.dropdown', [
                                      'attribute' => 'type_id',
                                      'label' => 'Категория',
                                      'options' => \App\Repositories\StoreTypeRepository::getOptions(),
                                      'required' => true,
                                  ])
                            </div>

                            <div class="form-section">
                                @include('layouts.includes.form.input', [
                                    'attribute' => 'name',
                                    'label' => 'Название',
                                    'required' => true,
                                    'autofocus' => true,
                                ])

                                @include('layouts.includes.form.input', [
                                    'attribute' => 'name_legal',
                                    'label' => 'Юр. наименование',
                                    'required' => true,
                                    'autofocus' => true,
                                ])

                                @include('layouts.includes.form.input', [
                                    'attribute' => 'business_identification_number',
                                    'helper' => '12 цифр',
                                    'label' => 'БИН',
                                    'required' => true,
                                ])
                            </div>
                        </div>

                        <div class="form-action">
                            <button type="submit" class="btn">
                                {{ isset($entity) ? 'Сохранить изменения' : 'Добавить арендатора' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection