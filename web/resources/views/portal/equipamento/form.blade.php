@extends('templete.app')
@section('body')

<div>
    <div>
        <ul style="display: inline-flex;">
            <li><h1 class="text-3xl text-gray-600 pb-6">{{ $editing ? 'Editar equipamento ' : 'Adicionar equipamento' }}</h1></li>
            @if($editing)
                <li style="margin-left: 15px;"><a href="{{route('delete_equipamento',['id'=>$equipamento->id])}}"><button class="flex items-center px-3 py-2 bg-red-500 rounded-lg text-white font-bold hover:bg-red-700 shadow" type="submit">Remover equipamento</button></a></li>
            @endif
        </ul>
    </div>
    <div class="bg-white rounded shadow overflow-hidden max-w-3xl">
        <form class="form" action="{{$editing ? route('update_equipamento', ['id'=>$equipamento->id]) : route('salvar_equipamento')}}" method="POST">
            @csrf
            <div class="p-8 -mr-6 -mb-8 flex flex-wrap">
                <div class="w-full">
                    <div class="pr-6 pb-8 w-full">
                        <label for="equipamento" class="block mb-2 font-semibold text-gray-600">Equipamento:</label>
                        <select for="equipamento" class="border flexborder-gray-300 rounded-lg p-2 block w-full border-1 bg-transparent" name="equipamento" required>
                            <option value="">Selecione</option>
                            @foreach ($tipo_equipamento as $t_e)
                                @if($editing)
                                    <option value="{{$t_e->id}}" {{ $equipamento->tipo_id == $t_e->id ? 'selected'  : ''}}>{{$t_e->descricao}}</option>
                                @else
                                    <option value="{{$t_e->id}}"> {{$t_e->descricao}} </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="pr-6 pb-8 w-full">
                    <label for="baia" class="block mb-2 font-semibold text-gray-600">Baia:</label>
                    <select for="baia" class="border flexborder-gray-300 rounded-lg p-2 block w-full border-1 bg-transparent" name="baia" required>
                        <option value="">Selecione</option>
                        @foreach ($baias as $b)
                            @if($editing)
                                <option value="{{$b->id}}" {{ $equipamento->baia_id == $b->id ? 'selected'  : ''}}>Baia {{$b->num_baia}}</option>
                            @else
                                <option value="{{$b->id}}">Baia {{$b->num_baia}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div class="pr-6 pb-8 w-full">
                    <label for="status_id" class="block mb-2 font-semibold text-gray-600">Status:</label>
                    <select for="status_id" class="border flexborder-gray-300 rounded-lg p-2 block w-full border-1 bg-transparent" name="status_id" required>
                        <option value="">Selecione</option>
                        @foreach ($status_equipamento as $s)
                            @if($editing)
                                <option value="{{$s->id}}" {{ $equipamento->status_id == $s->id ? 'selected'  : ''}}>{{$s->descricao}}</option>
                            @else
                                <option value="{{$s->id}}">{{$s->descricao}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div class="w-full">
                    <div class="pr-6 pb-8 w-full">
                        <label for="tag_rfid" class="block mb-2 font-semibold text-gray-600">Código do RFID:</label>
                        <input id="tag_rfid" value="{{ $editing ? $equipamento->uuid_tag : '' }}" type="text" autofocus class="border flexborder-gray-300 rounded-lg p-2 block w-full border-1" name="tag_rfid" placeholder="Ex.: XB01CD02" required>
                        @error('descricao')
                            <div class="text-red-500"></div>
                        @enderror
                    </div>
                </div>

                <div class="w-full">
                    <div class="pr-6 pb-8 w-full">
                        <label for="tombamento" class="block mb-2 font-semibold text-gray-600">Tombamento:</label>
                        <input id="tombamento" value="{{ $editing ? $equipamento->tombamento : '' }}" type="text" autofocus class="border flexborder-gray-300 rounded-lg p-2 block w-full border-1" name="tombamento" placeholder="Ex.: 32970" required>
                        @error('descricao')
                            <div class="text-red-500"></div>
                        @enderror
                    </div>
                </div>

                <div class="w-full">
                    <div class="pr-6 pb-8 w-full">
                        <label for="marca" class="block mb-2 font-semibold text-gray-600">Marca:</label>
                        <input id="marca" value="{{ $editing ? $equipamento->marca : '' }}" type="text" autofocus class="border flexborder-gray-300 rounded-lg p-2 block w-full border-1" name="marca" placeholder="Ex.: Hikari" required>
                        @error('descricao')
                            <div class="text-red-500"></div>
                        @enderror
                    </div>
                </div>

                <div class="w-full">
                    <div class="pr-6 pb-8 w-full">
                        <label for="modelo" class="block mb-2 font-semibold text-gray-600">Modelo:</label>
                        <input id="modelo" value="{{ $editing ? $equipamento->modelo : '' }}" type="text" autofocus class="border flexborder-gray-300 rounded-lg p-2 block w-full border-1" name="modelo" placeholder="Ex.: ABCD 20938" required>
                        @error('descricao')
                            <div class="text-red-500"></div>
                        @enderror
                    </div>
                </div>

                <div class="w-full">
                    <div class="pr-6 pb-8 w-full">
                        <label for="conserv" class="block mb-2 font-semibold text-gray-600">Estado de conservação:</label>
                        <input id="conserv" value="{{ $editing ? $equipamento->estado_conserv : '' }}" type="text" autofocus class="border flexborder-gray-300 rounded-lg p-2 block w-full border-1" name="conserv" placeholder="Ex.: Novo, sem marcas de uso">
                        @error('descricao')
                            <div class="text-red-500"></div>
                        @enderror
                    </div>
                </div>

            </div>
            <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex justify-between">
                <a href="" class="flex items-center text-red-700 hover:underline">Cancelar</a>
                <button class="flex items-center px-3 py-2 bg-green-500 rounded-lg text-white font-bold hover:bg-green-700 shadow" type="submit">Concluir</button>
            </div>
        </form>
    </div>
</div>

@endsection