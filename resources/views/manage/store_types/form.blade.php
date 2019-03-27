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
                        Данные категории
                    </div>
                </div>

                <div class="box-content">
                    <form action="{{ $action }}" method="POST">
                        @if (isset($entity))
                            @method('PUT')
                        @endif

                        @csrf

                        <div class="form-content">
                            <div class="form-section">
                                <div class="form-grid is-2col" style="grid-template-columns: 2fr 1fr">
                                    @include('layouts.includes.form.input', [
                                        'attribute' => 'name',
                                        'label' => 'Название',
                                        'required' => true,
                                        'autofocus' => true,
                                    ])

                                    @include('layouts.includes.form.input', [
                                        'attribute' => 'color',
                                        'label' => 'Цвет',
                                        'required' => true,
                                        'helper' => '#<b>53f24s</b> — без решётки'
                                    ])
                                </div>
                            </div>
                        </div>

                        <div class="form-action">
                            <button type="submit" class="btn">
                                {{ isset($entity) ? 'Сохранить изменения' : 'Добавить категорию' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection