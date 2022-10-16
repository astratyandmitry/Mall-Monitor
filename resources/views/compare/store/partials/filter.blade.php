<div class="filter">
  <div class="container">
    <form method="GET" class="filter-form">
      @include('layouts.includes.form.hidden', ['attribute' => 'graph_date_type', 'value' => request()->query('graph_date_type')])

      @if ( ! $currentUser->store_id)
        @if ( ! $currentUser->mall_id)
          @include('layouts.includes.form.dropdown', [
              'attribute' => 'mall_id',
              'value' => request()->query('mall_id'),
              'label' => 'ТРЦ',
              'placeholder' => 'Все',
              'options' => \App\Repositories\MallRepository::getOptions(),
          ])
        @endif

        @include('layouts.includes.form.dropdown', [
            'attribute' => 'type_id',
            'value' => request()->query('type_id'),
            'label' => 'Категория',
            'placeholder' => 'Все',
            'options' => \App\Repositories\StoreTypeRepository::getOptions(),
        ])
      @endif

      <button type="submit" class="btn">Применить фильтр</button>
    </form>
  </div>
</div>
