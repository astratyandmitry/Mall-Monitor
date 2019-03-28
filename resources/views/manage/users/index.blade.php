@php /** @var \App\Models\User[] $entities */ @endphp

@extends('layouts.app', $globals)

@section('content')
    <div class="heading">
        <div class="container">
            <div class="heading-content has-action">
                <div class="heading-text">
                    {{ $globals['title'] }}
                </div>

                <div class="heading-action">
                    <a href="{{ route('manage.users.create') }}" class="btn heading-action-button is-outlined">
                        Добавить пользователя
                    </a>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.partials.status')

    <div class="content">
        <div class="container">
            <div class="box">
                <div class="box-title">
                    <div class="box-title-text">
                        Список пользователей
                    </div>
                </div>

                <div class="box-content">
                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th nowrap width="64">
                                ID
                                @include('layouts.includes.table.sorting', ['attribute' => 'id'])
                            </th>
                            <th nowrap>
                                E-mail
                                @include('layouts.includes.table.sorting', ['attribute' => 'email'])
                            </th>
                            <th nowrap width="240">
                                ТРЦ
                                @include('layouts.includes.table.sorting', ['attribute' => 'mall_id'])
                            </th>
                            <th nowrap width="320">
                                Арендатор
                                @include('layouts.includes.table.sorting', ['attribute' => 'store_id'])
                            </th>
                            <th nowrap width="120">
                                Статус
                                @include('layouts.includes.table.sorting', ['attribute' => 'deleted_at'])
                            </th>
                            <th nowrap class="is-right" width="80">
                            </th>
                        </tr>
                        <form method="GET">
                            @include('layouts.includes.field.hidden', ['attribute' => 'sort_key', 'value' => 'id'])
                            @include('layouts.includes.field.hidden', ['attribute' => 'sort_type', 'value' => 'asc'])

                            <tr class="is-filter">
                                <th nowrap class="field">
                                    @include('layouts.includes.field.input', [
                                        'attribute' => 'id',
                                        'placeholder' => 'ID',
                                    ])
                                </th>
                                <th nowrap class="field">
                                    @include('layouts.includes.field.input', [
                                        'attribute' => 'email',
                                        'placeholder' => 'E-mail',
                                    ])
                                </th>
                                <th nowrap class="field">
                                    @include('layouts.includes.field.dropdown', [
                                        'attribute' => 'mall_id',
                                        'placeholder' => 'Все',
                                        'options' => \App\Repositories\MallRepository::getOptions(),
                                    ])
                                </th>
                                <th nowrap class="field">
                                    @if (request()->get('mall_id'))
                                        @include('layouts.includes.field.dropdown', [
                                           'attribute' => 'store_id',
                                           'placeholder' => 'Все',
                                           'options' => \App\Repositories\StoreRepository::getOptions(request()->get('mall_id')),
                                       ])
                                    @else
                                        @include('layouts.includes.field.dropdown-grouped', [
                                           'attribute' => 'store_id',
                                           'placeholder' => 'Все',
                                           'options' => \App\Repositories\StoreRepository::getOptionsGrouped(),
                                       ])
                                    @endif
                                </th>
                                <th nowra class="field">
                                    @include('layouts.includes.field.dropdown', [
                                        'attribute' => 'filter',
                                        'placeholder' => 'Все',
                                        'options' => [
                                            1 => 'Активные',
                                            2 => 'Неактивные',
                                        ],
                                    ])
                                </th>
                                <th nowrap>
                                    <button type="submit" class="btn">Найти</button>
                                </th>
                            </tr>
                        </form>
                        </thead>
                        <tbody>
                        @if (count($entities))
                            @foreach($entities as $entity)
                                <tr>
                                    <td nowrap>
                                        {{ $entity->id }}
                                    </td>
                                    <td nowrap>
                                        {{ $entity->email }}
                                    </td>
                                    <td nowrap>
                                        @if ($entity->mall)
                                            {{ $entity->mall->name }}
                                        @else
                                            <div class="badge is-inline">
                                                Отсутствует
                                            </div>
                                        @endif
                                    </td>
                                    <td nowrap>
                                        @if ($entity->store)
                                            {{ $entity->store->name }}
                                        @else
                                            <div class="badge is-inline">
                                                Отсутствует
                                            </div>
                                        @endif
                                    </td>
                                    <td nowrap>
                                        <div class="badge is-inline {{ $entity->trashed() ? 'is-danger' : 'is-success' }}">
                                            {{ $entity->trashed() ? 'Неактивный' : 'Активный' }}
                                        </div>
                                    </td>
                                    <td class="is-icons">
                                        @if (!$entity->trashed())
                                            <a href="{{ route('manage.users.edit', $entity) }}" title="Редактировать">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                        @endif

                                        <a href="{{ route('manage.users.toggle', $entity) }}"
                                           title="{{ $entity->trashed() ? 'Восстановить' : 'Удалить' }}">
                                            <i class="fa {{ $entity->trashed() ? 'fa-undo' : 'fa-trash' }}"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="is-empty">
                                    Информация по указанному запросу отсутствует
                                </td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>

            {{ $entities->appends(paginateAppends())->links('vendor.pagination.default') }}
        </div>
    </div>
@endsection
