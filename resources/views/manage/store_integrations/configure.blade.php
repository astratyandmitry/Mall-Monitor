@php /** @var \App\Models\StoreIntegration $entity */ @endphp

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
          <form action="{{ $action }}" method="POST">
            @csrf

            <div class="form-content">
              <div class="form-section">
                @foreach(\App\Models\StoreIntegration::getFields() as $key => $val)
                  <div class="form-grid is-2col is-marged">
                    @include('layouts.includes.array.input', [
                          'attribute' => "_{$key}",
                          'forceValue' => $val,
                          'disabled' => true,
                          'data_key' => 'config',
                      ])

                    @include('layouts.includes.array.dropdown', [
                          'attribute' => $key,
                          'options' => $entity->columns,
                          'data_key' => 'config',
                           'default' => -1,
                      ])
                  </div>
                @endforeach
              </div>

              <div class="form-section">
                <div class="form-section-heading">
                  <div class="form-section-heading-text">
                    Типы операций
                  </div>
                </div>

                @foreach(\App\Repositories\ChequeTypeRepository::getOptions() as $key => $val)
                  <div class="form-grid is-2col is-marged">
                    @include('layouts.includes.array.input', [
                          'attribute' => "_{$key}",
                          'forceValue' => $val,
                          'disabled' => true,
                          'data_key' => 'types',
                      ])

                    @include('layouts.includes.array.input', [
                          'attribute' => $key,
                          'placeholder' => 'Значение, или оставить пустым при отсутсвии',
                          'data_key' => 'types',
                      ])
                  </div>
                @endforeach
              </div>

              <div class="form-section">
                <div class="form-section-heading">
                  <div class="form-section-heading-text">
                    Виды платежей
                  </div>
                </div>

                @foreach(\App\Repositories\ChequePaymentRepository::getOptions() as $key => $val)
                  <div class="form-grid is-2col is-marged">
                    @include('layouts.includes.array.input', [
                          'attribute' => "_{$key}",
                          'forceValue' => $val,
                          'disabled' => true,
                          'data_key' => 'payments',
                      ])

                    @include('layouts.includes.array.input', [
                          'attribute' => $key,
                          'placeholder' => 'Значение, или оставить пустым при отсутсвии',
                          'data_key' => 'payments',
                      ])
                  </div>
                @endforeach
              </div>
            </div>

            <div class="form-action">
              <button type="submit" class="btn">
                Сохранить изменения
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
    })
  </script>
@endpush
