@extends('layouts.clean')

@section('content')
    <div class="w-full absolute pin h-full bg-indigo-darker flex items-center justify-center p-8 md:p-16">
        <div class="w-full max-w-sm">
            <div class="flex flex-wrap items-center justify-center font-light text-indigo-darker uppercase">
                <div class="text-white text-5xl font-bold">Mall</div>
                <div class="ml-2 font-bold bg-indigo text-2xl text-indigo-lightest shadow py-2 px-4 rounded-full">Monitor</div>
            </div>

            <form class="bg-white shadow-md rounded p-8 my-8">
                <div class="mb-4">
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-grey-darker focus:outline-none"
                           id="email" name="email" type="text" placeholder="E-mail">
                </div>

                <div class="mb-6">
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-grey-darker focus:outline-none"
                           id="password" type="password" placeholder="******">
                    {{--<div class="text-red text-xs mt-2 italic">Please choose a password.</div>--}}
                </div>


                <button class="bg-indigo hover:bg-indigo-dark text-white py-2 px-4 rounded focus:outline-none" type="submit">
                    Войти
                </button>
            </form>

            <div class="text-center text-grey text-sm">
                Все права защищены {{ config('app.name') }} © {{ date('Y') }}
            </div>
        </div>
    </div>
@endsection