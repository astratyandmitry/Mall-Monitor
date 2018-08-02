@extends('layouts.clean')

@section('content')
    <div class="w-full absolute pin h-full bg-indigo-darker flex items-center justify-center p-8 md:p-16">
        <div class="w-full lg:w-3/5 xl:w-2/5">
            <div class="font-light text-indigo-darker uppercase">
                <div class="flex flex-wrap items-center ">
                    <div class="text-white text-white text-3xl lg:text-5xl font-bold">
                        Ошибка
                    </div>

                    <div class="ml-4 font-bold text-1xl lg:text-2xl bg-indigo text-indigo-lightest shadow py-3 px-6 rounded-full">
                        419
                    </div>
                </div>
            </div>

            <div class="mt-8 text-2xl text-indigo-lightest font-thin">
                Срок действия страницы истек за время неактивности. Попробуйте повторить попытку.
            </div>
        </div>
    </div>
@endsection