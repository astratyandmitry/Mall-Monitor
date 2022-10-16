@php /** @var \App\Models\Developer|null $entity */ @endphp

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
            Данные конфигурации
          </div>
        </div>

        <div class="box-content">
          <form action="{{ $action }}" method="POST" enctype="multipart/form-data">
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
                        'required' => true,
                    ])

                  @include('layouts.includes.form.dropdown', [
                        'attribute' => 'store_id',
                        'label' => 'Арендатор',
                        'options' => \App\Repositories\StoreRepository::getOptions(old('store_id', optional($entity)->mall_id ?? -1)),
                        'required' => true,
                    ])
                </div>
              </div>

              <div class="form-section">
                <div class="form-grid is-2col">
                  @include('layouts.includes.form.dropdown', [
                      'attribute' => 'type_id',
                      'label' => 'Тип',
                      'required' => true,
                      'options' => \App\Repositories\StoreIntegrationTypeRepository::getOptions(),
                  ])

                  @include('layouts.includes.form.input', [
                      'attribute' => 'file',
                      'label' => 'Файл',
                      'helper' => 'до 10 строк',
                      'required' => true,
                      'type' => 'file',
                      'disabled' => true,
                  ])
                </div>
              </div>
            </div>

            <div class="form-action">
              <button type="submit" class="btn">
                {{ isset($entity) ? 'Сохранить изменения' : 'Добавить конфигурацию' }}
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

      $mallID.change()

      $file = $('#file')
      $typeID = $('#type_id')

      var fileAccepts = {
        1: 'text/xml,application/xml',
        2: 'application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
      }

      $typeID.on('change', function () {
        if (!$(this).val()) {
          $file.attr('disabled', true).val('')
        } else {

          $file.attr('disabled', false).attr('accept', fileAccepts[$(this).val()])
        }
      })

      $typeID.change()
    })
  </script>
@endpush
