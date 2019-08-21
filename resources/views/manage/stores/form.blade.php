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
                <form action="{{ $action }}" method="POST">
                    @if (isset($entity))
                        @method('PUT')
                    @endif

                    @csrf

                    <div class="box-title">
                        <div class="box-title-text">
                            Данные арендатора
                        </div>
                    </div>

                    <div class="box-content">
                        <div class="form-content">
                            <div class="form-section">
                                @if ( ! $currentUser->mall_id)
                                    @include('layouts.includes.form.dropdown', [
                                          'attribute' => 'mall_id',
                                          'label' => 'ТРЦ',
                                          'options' => \App\Repositories\MallRepository::getOptions(),
                                          'required' => true,
                                      ])
                                @else
                                    @include('layouts.includes.form.hidden', ['attribute' => 'mall_id', 'value' => $currentUser->mall_id])
                                @endif

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
                                ])

                                @include('layouts.includes.form.input', [
                                    'attribute' => 'business_identification_number',
                                    'helper' => '12 цифр',
                                    'label' => 'БИН',
                                    'required' => true,
                                ])
                            </div>

                            <div class="form-section">
                                @include('layouts.includes.form.input', [
                                    'attribute' => 'rentable_area',
                                    'label' => 'Арендуемая площать',
                                    'helper' => 'кв. м.',
                                    'required' => true,
                                ])

                                @if (isset($entity) && $entity->trashed())
                                    @include('layouts.includes.form.checkbox', [
                                        'attribute' => 'activate',
                                        'label' => 'Активировать арендатора',
                                    ])
                                @endif
                            </div>

                            @if (isset($entity))
                                <div class="form-action">
                                    <button type="submit" class="btn">
                                        Сохранить изменения
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if (!isset($entity))
                        <div class="box-title">
                            <div class="box-title-text">
                                Аккаунт разработчика
                            </div>
                        </div>

                        <div class="box-content">
                            <div class="form-section">
                                <div class="form-grid is-2col">
                                    @include('layouts.includes.form.input', [
                                        'attribute' => 'username',
                                        'label' => 'Логин',
                                        'autofocus' => true,
                                    ])

                                    @include('layouts.includes.form.password', [
                                        'attribute' => 'password',
                                        'label' => 'Пароль',
                                    ])
                                </div>
                            </div>

                            <div class="form-action">
                                <button type="submit" class="btn">
                                    Добавить арендатора
                                </button>
                            </div>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
@endsection
