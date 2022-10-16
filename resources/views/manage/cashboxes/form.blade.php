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

            <div class="form-content">
              <div class="form-section">
                <div class="form-grid {{ ! $currentUser->mall_id ?  'is-2col' : null }} ">
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
                        'attribute' => 'store_id',
                        'label' => 'Арендатор',
                        'options' => \App\Repositories\StoreRepository::getOptions(old('store_id', optional($entity)->mall_id ?? -1)),
                        'required' => true,
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

                @if (isset($entity) && $entity->trashed())
                  @include('layouts.includes.form.checkbox', [
                      'attribute' => 'activate',
                      'label' => 'Активировать кассу',
                  ])
                @endif
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
