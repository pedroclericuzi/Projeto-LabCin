@extends('templete.app')
@section('body')

<div>
    <h1 class="text-3xl text-gray-600 pb-6">Equipamentos</h1>

    <div class="mb-6 flex justify-between items-center">
        <div class="flex items-center w-full max-w-md mr-4">
            <div class="flex w-2/3 bg-white shadow rounded">
                <input type="text" name="search" placeholder="Pesquisar.." class="relative w-full rounded px-6 py-3 focus:shadow-outline" wire:model.debounce.500ms="search">
            </div>
        </div>
    </div>

    <div class="mt-8">
        <h1 class="text-3xl text-gray-600 pb-6">Log de Baias</h1>
        <div class="bg-white rounded shadow overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr class="text-left font-bold text-gray-600">
                        <th class="px-6 pt-6 pb-4">Aluno</th> 
                        <th colspan="1" class="px-6 pt-6 pb-4">Monitor</th>
                        <th colspan="1" class="px-6 pt-6 pb-4">Mensagem</th>
                        <th colspan="1" class="px-6 pt-6 pb-4">Evento</th>
                        <th colspan="1" class="px-6 pt-6 pb-4">Ocorreu em</th>
                        <th colspan="1" class="px-6 pt-6 pb-4">Atualizado em</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600">
                    @forelse ($baias as $b)
                        <tr class="hover:bg-gray-100 focus-within:bg-gray-100 hover:text-gray-400 border-t">
                            <td>
                                <a href="{{ App\Http\Controllers\portal\PermisionsController::notIsAluno() ? route('show', ['slug'=>$b->baia_id]) : ''}}" class="px-6 py-4 flex items-center">
                                    {{ $b->mat_aluno }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ App\Http\Controllers\portal\PermisionsController::notIsAluno() ? route('show', ['slug'=>$b->baia_id]) : ''}}"  class="px-6 py-4 flex items-center">
                                    {{ $b->mat_monitor }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ App\Http\Controllers\portal\PermisionsController::notIsAluno() ? route('show', ['slug'=>$b->baia_id]) : ''}}"  class="px-6 py-4 flex items-center">
                                    {{ $b->mensagem }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ App\Http\Controllers\portal\PermisionsController::notIsAluno() ? route('show', ['slug'=>$b->baia_id]) : ''}}"  class="px-6 py-4 flex items-center">
                                    {{ App\Http\Controllers\portal\LogController::statusEvento($b->evento_id) }}
                                </a>
                            </td>
                            
                            <td>
                                <a href="{{ App\Http\Controllers\portal\PermisionsController::notIsAluno() ? route('show', ['slug'=>$b->baia_id]) : ''}}"  class="px-6 py-4 flex items-center">
                                    {{$b->created_at}}
                                </a>
                            </td>

                            <td>
                                <a href="{{ App\Http\Controllers\portal\PermisionsController::notIsAluno() ? route('show', ['slug'=>$b->baia_id]) : ''}}"  class="px-6 py-4 flex items-center">
                                    {{$b->updated_at}}
                                </a>
                            </td>

                            @if (App\Http\Controllers\portal\PermisionsController::notIsAluno() == true)
                                <td class="w-px">
                                    <a tabindex="-1" href="{{route('show', ['slug'=>$b->baia_id])}}"  class="px-4 flex items-center">
                                        <i class="fas fa-angle-right"></i>
                                    </a>
                                </td>
                            @endif

                        </tr>
                    @empty
                        <tr class="border-t">
                            <td colspan="4" class="p-4 text-center text-gray-400">Nenum registro foi encontrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

    <div class="mt-8">
        <h1 class="text-3xl text-gray-600 pb-6">Log de equipamentos</h1>
        <div class="bg-white rounded shadow overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr class="text-left font-bold text-gray-600">
                        <th class="px-6 pt-6 pb-4">Aluno</th> 
                        <th colspan="1" class="px-6 pt-6 pb-4">Monitor</th>
                        <th colspan="1" class="px-6 pt-6 pb-4">Mensagem</th>
                        <th colspan="1" class="px-6 pt-6 pb-4">Evento</th>
                        <th colspan="1" class="px-6 pt-6 pb-4">Ocorreu em</th>
                        <th colspan="1" class="px-6 pt-6 pb-4">Atualizado em</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600">
                    @forelse ($equipamentos as $equip)
                        <tr class="hover:bg-gray-100 focus-within:bg-gray-100 hover:text-gray-400 border-t">
                            <td>
                                <a href="{{ App\Http\Controllers\portal\PermisionsController::notIsAluno() ? route('show_equipamento', ['id'=>$equip->equipamento_id]) : ''}}" class="px-6 py-4 flex items-center">
                                    {{ $equip->mat_aluno }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ App\Http\Controllers\portal\PermisionsController::notIsAluno() ? route('show_equipamento', ['id'=>$equip->equipamento_id]) : ''}}"  class="px-6 py-4 flex items-center">
                                    {{ $equip->mat_monitor }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ App\Http\Controllers\portal\PermisionsController::notIsAluno() ? route('show_equipamento', ['id'=>$equip->equipamento_id]) : ''}}"  class="px-6 py-4 flex items-center">
                                    {{ $equip->mensagem }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ App\Http\Controllers\portal\PermisionsController::notIsAluno() ? route('show_equipamento', ['id'=>$equip->equipamento_id]) : ''}}"  class="px-6 py-4 flex items-center">
                                    {{ App\Http\Controllers\portal\LogController::statusEvento($equip->evento_id) }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ App\Http\Controllers\portal\PermisionsController::notIsAluno() ? route('show_equipamento', ['id'=>$equip->equipamento_id]) : ''}}"  class="px-6 py-4 flex items-center">
                                    {{$equip->created_at}}
                                </a>
                            </td>

                            <td>
                                <a href="{{ App\Http\Controllers\portal\PermisionsController::notIsAluno() ? route('show_equipamento', ['id'=>$equip->equipamento_id]) : ''}}"  class="px-6 py-4 flex items-center">
                                    {{$equip->updated_at}}
                                </a>
                            </td>

                            @if (App\Http\Controllers\portal\PermisionsController::notIsAluno() == true)
                                <td class="w-px">
                                    <a tabindex="-1" href="{{route('show_equipamento', ['id'=>$equip->equipamento_id])}}"  class="px-4 flex items-center">
                                        <i class="fas fa-angle-right"></i>
                                    </a>
                                </td>
                            @endif

                        </tr>
                    @empty
                        <tr class="border-t">
                            <td colspan="4" class="p-4 text-center text-gray-400">Nenum registro foi encontrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

    <div class="mt-8">
        <h1 class="text-3xl text-gray-600 pb-6">Log de Reservas</h1>
        <div class="bg-white rounded shadow overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr class="text-left font-bold text-gray-600">
                        <th class="px-6 pt-6 pb-4">Aluno</th> 
                        <th colspan="1" class="px-6 pt-6 pb-4">Monitor</th>
                        <th colspan="1" class="px-6 pt-6 pb-4">Mensagem</th>
                        <th colspan="1" class="px-6 pt-6 pb-4">Evento</th>
                        <th colspan="1" class="px-6 pt-6 pb-4">Ocorreu em</th>
                        <th colspan="1" class="px-6 pt-6 pb-4">Atualizado em</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600">
                    @forelse ($reservas as $r)
                        <tr class="hover:bg-gray-100 focus-within:bg-gray-100 hover:text-gray-400 border-t">
                            <td>
                                <a href="{{ App\Http\Controllers\portal\PermisionsController::notIsAluno() ? route('show_reserva', ['id'=>$r->reserva_id]) : ''}}" class="px-6 py-4 flex items-center">
                                    {{ $r->mat_aluno }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ App\Http\Controllers\portal\PermisionsController::notIsAluno() ? route('show_reserva', ['id'=>$r->reserva_id]) : ''}}"  class="px-6 py-4 flex items-center">
                                    {{ $r->mat_monitor }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ App\Http\Controllers\portal\PermisionsController::notIsAluno() ? route('show_reserva', ['id'=>$r->reserva_id]) : ''}}"  class="px-6 py-4 flex items-center">
                                    {{ $r->mensagem }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ App\Http\Controllers\portal\PermisionsController::notIsAluno() ? route('show_reserva', ['id'=>$r->reserva_id]) : ''}}"  class="px-6 py-4 flex items-center">
                                    {{ App\Http\Controllers\portal\LogController::statusEvento($r->evento_id) }}
                                </a>
                            </td>

                            <td>
                                <a href="{{ App\Http\Controllers\portal\PermisionsController::notIsAluno() ? route('show_reserva', ['id'=>$r->reserva_id]) : ''}}"  class="px-6 py-4 flex items-center">
                                    {{$r->created_at}}
                                </a>
                            </td>

                            <td>
                                <a href="{{ App\Http\Controllers\portal\PermisionsController::notIsAluno() ? route('show_reserva', ['id'=>$r->reserva_id]) : ''}}"  class="px-6 py-4 flex items-center">
                                    {{$r->updated_at}}
                                </a>
                            </td>

                            @if (App\Http\Controllers\portal\PermisionsController::notIsAluno() == true)
                                <td class="w-px">
                                    <a tabindex="-1" href="{{route('show_reserva', ['id'=>$r->reserva_id])}}"  class="px-4 flex items-center">
                                        <i class="fas fa-angle-right"></i>
                                    </a>
                                </td>
                            @endif

                        </tr>
                    @empty
                        <tr class="border-t">
                            <td colspan="4" class="p-4 text-center text-gray-400">Nenum registro foi encontrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</div>

@endsection