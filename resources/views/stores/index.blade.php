@extends('layouts.app', $globals)

@section('content')
    <div class="shadow-lg rounded">
        <div class="p-8">
            <h1 class="mb-8">
                {{ $currentMall->name }}
                <span class="text-grey-darker font-normal">/ {{ $globals['title'] }}</span>
            </h1>

            <div class="flex w-full">
                @foreach($stores->chunk(round(count($stores) / 3)) as $chunk)
                    <div class="w-1/3">
                        @foreach($chunk as $store)
                            <div class="mb-4 w-full">
                                <h2 class="font-normal m-0">
                                    <a href="{{ $store->link() }}"
                                       class="no-underline text-grey-dark border-b border-grey hover:border-transparent">{{ $store->name }}</a>
                                </h2>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>

            {{ $stores->links('vendor.pagination.default') }}
        </div>
    </div>
@endsection
