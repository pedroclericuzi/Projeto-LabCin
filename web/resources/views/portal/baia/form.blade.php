@extends('templete.app')
@section('body')

<div>
    <div>
        <ul style="display: inline-flex;">
            <li><h1 class="text-3xl text-gray-600 pb-6">{{ $editing ? 'Baia '.$baia->num_baia : 'Baias' }}</h1></li>
            @if($editing)
                <li style="margin-left: 15px;"><a href="{{route('delete_baia',['id'=>$baia->id])}}"><button class="flex items-center px-3 py-2 bg-red-500 rounded-lg text-white font-bold hover:bg-red-700 shadow" type="submit">Remover baia</button></a></li>
            @endif
        </ul>
    </div>
    <div class="bg-white rounded shadow overflow-hidden max-w-3xl">
        <form class="form" action="{{$editing ? route('update_baia', ['id'=>$baia->id]) : route('salvar_baia')}}" method="POST">
            @csrf
            <div class="p-8 -mr-6 -mb-8 flex flex-wrap">
                <div class="w-full">
                    <div class="pr-6 pb-8 w-full">
                        <label for="num_baia" class="block mb-2 font-semibold text-gray-600">Número da baia:</label>
                        <input id="num_baia" value="{{ $editing ? $baia->num_baia : '' }}" type="text" autofocus class="border flexborder-gray-300 rounded-lg p-2 block w-full border-1" name="num_baia" placeholder="Ex.: 01" required>
                        @error('descricao')
                            <div class="text-red-500"></div>
                        @enderror
                    </div>
                </div>
                <div class="pr-6 pb-8 w-full">
                    <label for="status_id" class="block mb-2 font-semibold text-gray-600">Status:</label>
                    <select for="status_id" class="border flexborder-gray-300 rounded-lg p-2 block w-full border-1 bg-transparent" name="status_id" required>
                        <option value="">Selecione</option>
                        @foreach ($status_baia as $s)
                            @if($editing)
                                <option value="{{$s->id}}" {{ $baia->status_baia_id == $s->id ? 'selected'  : ''}}>{{$s->descricao}}</option>
                            @else
                                <option value="{{$s->id}}">{{$s->descricao}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex justify-between">
                <a href="" class="flex items-center text-red-700 hover:underline">Cancelar</a>
                <button class="flex items-center px-3 py-2 bg-green-500 rounded-lg text-white font-bold hover:bg-green-700 shadow" type="submit">{{ $editing ? 'Concluir' : 'Adicionar Baia' }}</button>
            </div>
        </form>
    </div>
</div>

@endsection