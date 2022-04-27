@extends('templete.app')
@section('body')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="{{asset('js/validacao-reserva.js')}}"></script>
<script src="{{asset('js/delimitar-textarea.js')}}"></script>
<script src="{{asset('js/resultado-reserva.js')}}"></script>

<div>
    <div>
        <ul style="display: inline-flex;">
            <li><h1 class="text-3xl text-gray-600 pb-6">{{ $editing ? 'Ver reserva ' : 'Realizar reserva' }}</h1></li>
            @if($editing && $naoAluno && !$cancelado)
                <li style="margin-left: 15px;"><a href="{{route('cancelar_reserva',['id'=>$reserva->id])}}"><button class="flex items-center px-3 py-2 bg-red-500 rounded-lg text-white font-bold hover:bg-red-700 shadow" type="submit">Cancelar reserva</button></a></li>
            @endif
            @if($editing && $solicitante && $cancelado)
                <li style="margin-left: 15px;"><a href="{{route('excluir_reserva',['id'=>$reserva->id])}}"><button class="flex items-center px-3 py-2 bg-red-500 rounded-lg text-white font-bold hover:bg-red-700 shadow" type="submit">Excluir registro</button></a></li>
            @endif
        </ul>
    </div>
    <div class="bg-white rounded shadow overflow-hidden max-w-3xl">
        <form class="form" action="{{ ($editing) ? route('atualizar_reserva', ['id'=>$reserva->id]) : route('agendar')}}" method="POST">
            @csrf
            <div class="p-8 -mr-6 -mb-8 flex flex-wrap">
                
                <div class="w-full" style="display: flex;">
                    <div class="pr-6 pb-8 w-full">
                        <label for="data" class="block mb-2 font-semibold text-gray-600">Agendar para:</label>
                        <input id="data" value="{{ $editing ? App\Http\Controllers\portal\ReservaController::formatarData($reserva->data) : '' }}" type="text" maxlength="10" autofocus class="date form-control border flexborder-gray-300 rounded-lg p-2 block w-full border-1" name="data" placeholder="Ex.: 04-06-2021" required readonly>
                        @error('descricao')
                            <div class="text-red-500"></div>
                        @enderror
                    </div>

                    <div class="pr-6 pb-8 w-full">
                        <label for="hora" class="block mb-2 font-semibold text-gray-600">Horário de chegada:</label>
                        <input id="hora" {{ ((!$editing) || ($editing && $pendente && $solicitante))  ? "" : "readonly" }} value="{{ $editing ? App\Http\Controllers\portal\ReservaController::formatarHora($reserva->hora) : '' }}" type="time" autofocus class="border flexborder-gray-300 rounded-lg p-2 block w-full border-1" name="hora" placeholder="Ex.: 08:00" required>
                        @error('descricao')
                            <div class="text-red-500"></div>
                        @enderror
                    </div>
                </div>
                
                <div class="pr-6 pb-8 w-full">
                    <label for="baia" class="block mb-2 font-semibold text-gray-600">Baia:</label>
                    <select id="baia" class="border flexborder-gray-300 rounded-lg p-2 block w-full border-1 bg-transparent" name="baia" required>
                        <option value="">Selecione</option>
                        @if($editing)
                            @foreach ($baias as $b)
                                <option value="{{$b->id}}" {{ $reserva->baia_id == $b->id ? 'selected'  : ''}}>Baia {{$b->num_baia}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                

                <div class="w-full">
                    <div class="pr-6 pb-8 w-full">
                        <label for="justificativa" class="block mb-2 font-semibold text-gray-600">Justificativa:</label>
                        <textarea {{ ((!$editing) || ($editing && $pendente && $solicitante)) ? "" : "readonly" }} id="justificativa" class="border flexborder-gray-300 rounded-lg p-2 block w-full border-1" name="justificativa" 
                            placeholder="Ex.: Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500." 
                            required rows="4" cols="45">{{ $editing ? $reserva->justificativa : '' }}</textarea>
                        <p id="qtd_texto_justificativa" style="float: right;">0/500</p>
                        @error('descricao')
                            <div class="text-red-500"></div>
                        @enderror
                    </div>
                </div>

                <div class="w-full" style="display: {{ ($editing && !$solicitante && $naoAluno) ? '' : 'none' }};">
                    <div class="pr-6 pb-8 w-full">
                        <label for="observacoes" class="block mb-2 font-semibold text-gray-600">Observações:</label>
                        <textarea  id="observacoes" class="border flexborder-gray-300 rounded-lg p-2 block w-full border-1" name="observacoes" 
                            placeholder="Ex.: Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500." 
                            rows="4" cols="45">{{ $editing ? $reserva->observacoes : '' }}</textarea>
                        <p id="qtd_texto_observacoes" style="float: right;">0/500</p>

                        @error('descricao')
                            <div class="text-red-500"></div>
                        @enderror
                    </div>
                </div>

            </div>
            <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex justify-between">
                <a href="{{route('reservas')}}" class="flex items-center text-red-700 hover:underline">Voltar para Reservas</a>
                @if( (!$editing) || ($editing && $pendente && $solicitante) ) 
                    <button class="flex items-center px-3 py-2 bg-green-500 rounded-lg text-white font-bold hover:bg-green-700 shadow" type="submit"> {{ $editing ? "Salvar alterações" : "Concluir" }} </button>
                @else
                    @if($editing && $pendente && !$solicitante && $naoAluno)
                        <ul style="display: inline-flex;">
                            <li style="margin-left: 15px;"><input class="flex items-center px-3 py-2 bg-red-500 rounded-lg text-white font-bold hover:bg-red-700 shadow" type="button" id="reject" data-id="{{$reserva->id}}" value="Rejeitar"></li>
                            <li style="margin-left: 15px;"><input class="flex items-center px-3 py-2 bg-green-500 rounded-lg text-white font-bold hover:bg-green-700 shadow" id="accept" type="button" data-id="{{$reserva->id}}" value="Aceitar"></li>
                        </ul>
                    @endif
                @endif
            </div>
        </form>
    </div>
</div>

@if( (!$editing) || ($editing && $pendente && $solicitante) )
    <script type="text/javascript">
        $('.date').datepicker({
            dateFormat: "dd-mm-yy",
            minDate:new Date(),
            maxDate: 20,
            onSelect: function (dateText, inst) {
                selectedDate(dateText);
            },
        });
    </script>
@endif
@endsection