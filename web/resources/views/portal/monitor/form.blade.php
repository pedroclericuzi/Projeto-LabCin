@extends('templete.app')
@section('body')

<div>
    <div>
        <ul style="display: inline-flex;">
            <li><h1 class="text-3xl text-gray-600 pb-6">Adicionar monitor</h1></li>
        </ul>
    </div>
    <div class="bg-white rounded shadow overflow-hidden max-w-3xl">
        <form class="form" action="{{route('searchMonitor')}}" method="POST">
            @csrf
            <div class="p-8 -mr-6 -mb-8 flex flex-wrap">
                <div class="w-full">
                    <div class="pr-6 pb-8 w-full">
                        <label for="matricula" class="block mb-2 font-semibold text-gray-600">Matrícula:</label>
                        <input id="matricula" value="{{ $matricula }}" type="text" autofocus class="border flexborder-gray-300 rounded-lg p-2 block w-full border-1" name="matricula" placeholder="Ex.: 10110210310" required>
                        @error('descricao')
                            <div class="text-red-500"></div>
                        @enderror
                    </div>
                </div>

                <div class="w-full">
                    <div class="pr-6 pb-8 w-full">
                        <label for="disciplina" class="block mb-2 font-semibold text-gray-600">Disciplina:</label>
                        <input id="disciplina" type="text" autofocus class="border flexborder-gray-300 rounded-lg p-2 block w-full border-1" name="disciplina" placeholder="Ex.: Sistemas Digitais" required>
                        @error('descricao')
                            <div class="text-red-500"></div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex justify-between">
                <a href="" class="flex items-center text-red-700 hover:underline">Cancelar</a>
                <button class="flex items-center px-3 py-2 bg-green-500 rounded-lg text-white font-bold hover:bg-green-700 shadow" type="submit">{{ $editing ? 'Concluir' : 'Adicionar monitor' }}</button>
            </div>
        </form>
    </div>
</div>
@endsection