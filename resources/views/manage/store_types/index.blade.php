@php /** @var \App\Models\City[] $entities */ @endphp

@extends('layouts.app', $globals)

@section('content')
  <div class="heading">
    <div class="container">
      <div class="heading-content has-action">
        <div class="heading-text">
          {{ $globals['title'] }}
        </div>

        <div class="heading-action">
          <a href="{{ route('manage.store_types.create') }}" class="btn heading-action-button is-outlined">
            Добавить категорию
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
            Список категорий
          </div>
        </div>

        <div class="box-content">
          <table class="table" border="0" cellpadding="0" cellspacing="0" width="100%">
            <thead>
            <tr>
              <th nowrap width="64">
                ID
                @include('layouts.includes.table.sorting', ['attribute' => 'id'])
              </th>
              <th nowrap>
                Название
                @include('layouts.includes.table.sorting', ['attribute' => 'name'])
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
                      'attribute' => 'name',
                      'placeholder' => 'Название',
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
                    {{ $entity->name }}
                  </td>
                  <td class="is-icons">
                    <a href="{{ route('manage.store_types.edit', $entity) }}" title="Редактировать">
                      <i class="fa fa-pencil"></i>
                    </a>
                  </td>
                </tr>
              @endforeach
            @else
              <tr>
                <td colspan="3" class="is-empty">
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
