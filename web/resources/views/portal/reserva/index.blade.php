@extends('templete.app')
@section('body')

<div>
    <h1 class="text-3xl text-gray-600 pb-6">Reservas</h1>

    <div class="mb-6 flex justify-between items-center">
        <div class="flex items-center w-full max-w-md mr-4">
            <div class="flex w-2/3 bg-white shadow rounded">
                <input type="text" name="search" placeholder="Pesquisar.." class="relative w-full rounded px-6 py-3 focus:shadow-outline" wire:model.debounce.500ms="search">
            </div>
        </div>
        <div>
            @if (App\Http\Controllers\portal\PermisionsController::notIsAluno() == true)
            @endif
            <a href="{{route('criarReserva')}}" class="bg-red-600 px-3 py-2 rounded-lg shadow text-white font-bold hover:bg-red-700">
                Fazer uma reserva
            </a>
        </div>
    </div>

    <div class="bg-white rounded shadow overflow-x-auto">
        <table class="w-full whitespace-no-wrap">
            <thead>
                <tr class="text-left font-bold text-gray-600">
                    <th class="px-6 pt-6 pb-4">Nome</th>
                    <th colspan="1" class="px-6 pt-6 pb-4">Login</th>
                    <th colspan="1" class="px-6 pt-6 pb-4">Baia</th>
                    <th colspan="1" class="px-6 pt-6 pb-4">Para o dia</th>
                    <th colspan="1" class="px-6 pt-6 pb-4">Status</th>
                </tr>
            </thead>
            <tbody class="text-gray-600">
                @forelse ($reservas as $r)
                    <tr class="border-t">
                        <td>
                            <a href="{{route('show_reserva', ['id'=>$r->id])}}" class="px-6 py-4 flex items-center">
                                {{ App\Http\Controllers\portal\ReservaController::EncontrarAluno($r->cpf) }}
                            </a>
                        </td>

                        <td>
                            <a href="{{route('show_reserva', ['id'=>$r->id])}}" class="px-6 py-4 flex items-center">
                                {{ App\Http\Controllers\portal\ReservaController::Login($r->cpf) }}
                            </a>
                        </td>

                        <td>
                            <a href="{{route('show_reserva', ['id'=>$r->id])}}" class="px-6 py-4 flex items-center">
                                {{ App\Http\Controllers\portal\ReservaController::EncontrarBaia($r->baia_id) }}
                            </a>
                        </td>
                        <td>
                            <a href="{{route('show_reserva', ['id'=>$r->id])}}" class="px-6 py-4 flex items-center">
                                {{ App\Http\Controllers\portal\ReservaController::formatarData($r->data) }}
                            </a>
                        </td>
                        <td>
                            <a href="{{route('show_reserva', ['id'=>$r->id])}}" class="px-6 py-4 flex items-center">
                                {{ App\Http\Controllers\portal\ReservaController::EncontrarStatus($r->status_id) }}
                            </a>
                        </td>
                        <td class="w-px">
                            <a tabindex="-1" href=""  class="px-4 flex items-center">
                                <i class="fas fa-angle-right"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr class="border-t">
                        <td colspan="4" class="p-4 text-center text-gray-400">Nenhuma solicitação até o momento.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
