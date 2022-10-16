@php /** @var \App\Models\User|null $entity */ @endphp

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
            Данные пользователя
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
                <div class="form-grid is-2col is-marged">
                  @include('layouts.includes.form.dropdown', [
                        'attribute' => 'mall_id',
                        'label' => 'ТРЦ',
                        'options' => \App\Repositories\MallRepository::getOptions(),
                    ])

                  @include('layouts.includes.form.dropdown', [
                        'attribute' => 'store_id',
                        'label' => 'Арендатор',
                        'options' => \App\Repositories\StoreRepository::getOptions(old('store_id', optional($entity)->mall_id ?? -1)),
                    ])
                </div>

                @include('layouts.includes.form.checkbox', [
                    'attribute' => 'is_readonly',
                    'label' => 'Доступ только к чтению и отчетам',
                ])
              </div>

              <div class="form-section">
                <div class="form-grid is-2col">
                  @include('layouts.includes.form.input', [
                      'attribute' => 'email',
                      'label' => 'E-mail',
                      'required' => true,
                      'autofocus' => true,
                      'type' => 'email',
                  ])

                  @include('layouts.includes.form.input', [
                      'attribute' => 'phone',
                      'label' => 'Номер телефона',
                      'placeholder' => '+7(---)-------'
                  ])

                  @include('layouts.includes.form.input', [
                      'attribute' => 'given_name',
                      'label' => 'Имя',
                      'required' => true,
                  ])

                  @include('layouts.includes.form.input', [
                      'attribute' => 'family_name',
                      'label' => 'Фамилия',
                      'required' => 'true',
                  ])
                </div>
              </div>

              <div class="form-section">
                @include('layouts.includes.form.password', [
                    'attribute' => 'new_password',
                    'label' => isset($entity) ? 'Новый пароль' : 'Пароль',
                    'required' => ! isset($entity),
                ])

                @if (isset($entity) && ! $entity->is_active)
                  @include('layouts.includes.form.checkbox', [
                      'attribute' => 'is_readonly',
                      'label' => 'Активировать пользователя',
                  ])
                @endif
              </div>
            </div>

            <div class="form-action">
              <button type="submit" class="btn">
                {{ isset($entity) ? 'Сохранить изменения' : 'Добавить пользователя' }}
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
      $mallID = $('#mall_id')
      $storeID = $('#store_id')

      $mallID.on('change', function () {
        $storeID.html('<option>Загрузка...</option>').attr('disabled', true)

        $.ajax({
          method: 'POST',
          url: '/ajax/stores',
          data: {
            mall_id: $mallID.val()
          },
          success: function (response) {
            $storeID.html(response).attr('disabled', false)
          }
        })
      })
    })
  </script>
@endpush
