@php /** @var \App\Models\City|null $entity */ @endphp

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
                        Данные города
                    </div>
                </div>

                <div class="box-content">
                    <form action="{{ $action }}" method="POST">
                        @if (isset($entity))
                            @method('PUT')
                        @endif

                        @csrf

                        @includeWhen(isset($entity), 'layouts.includes.form.hidden', [
                            'attribute' => 'country_id',
                        ])

                        <div class="form-content">
                            <div class="form-section">
                                @include('layouts.includes.form.dropdown', [
                                      'attribute' => 'country_id',
                                      'label' => 'Страна',
                                      'options' => \App\Repositories\CountryRepository::getOptions(),
                                      'required' => true,
                                      'disabled' => isset($entity),
                                  ])
                            </div>

                            <div class="form-section">
                                @include('layouts.includes.form.input', [
                                    'attribute' => 'name',
                                    'label' => 'Название',
                                    'required' => true,
                                    'autofocus' => true,
                                ])
                            </div>
                        </div>

                        <div class="form-action">
                            <button type="submit" class="btn">
                                {{ isset($entity) ? 'Сохранить изменения' : 'Добавить город' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection