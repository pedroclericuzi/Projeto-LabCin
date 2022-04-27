@extends('templete.app')
@section('body')

<div>
    <h1 class="text-3xl text-gray-600 pb-6">Baias</h1>

    <div class="mb-6 flex justify-between items-center">
        <div class="flex items-center w-full max-w-md mr-4">
            <div class="flex w-2/3 bg-white shadow rounded">
                <input type="text" name="search" placeholder="Pesquisar.." class="relative w-full rounded px-6 py-3 focus:shadow-outline" wire:model.debounce.500ms="search">
            </div>
        </div>
        <div>
            @if (App\Http\Controllers\portal\PermisionsController::notIsAluno() == true)
                <a href="{{route('criar_baia')}}" class="bg-red-600 px-3 py-2 rounded-lg shadow text-white font-bold hover:bg-red-700">
                    Criar Baia
                </a>
            @endif
        </div>
    </div>

    <div class="bg-white rounded shadow overflow-x-auto">
        <table class="w-full whitespace-no-wrap">
            <thead>
                <tr class="text-left font-bold text-gray-600">
                    <th class="px-6 pt-6 pb-4">Baia</th> 
                    <th colspan="1" class="px-6 pt-6 pb-4">Status</th>
                    <th colspan="1" class="px-6 pt-6 pb-4">Última atualização</th>
                </tr>
            </thead>
            <tbody class="text-gray-600">
                @forelse ($baias as $baia)
                    <tr class="hover:bg-gray-100 focus-within:bg-gray-100 hover:text-gray-400 border-t">
                        <td>
                            @if (App\Http\Controllers\portal\PermisionsController::notIsAluno() == true)
                                <a href="{{route('show', ['slug'=>$baia->id])}}" class="px-6 py-4 flex items-center">
                                    Baia {{$baia->num_baia}}
                                </a>
                            @else
                                <p class="px-6 py-4 flex items-center">
                                    Baia {{$baia->num_baia}}
                                </p>
                            @endif
                        </td>
                        <td>

                            @if (App\Http\Controllers\portal\PermisionsController::notIsAluno() == true)
                                <a href="{{route('show', ['slug'=>$baia->id])}}"  class="px-6 py-4 flex items-center">
                                    {{ App\Http\Controllers\portal\BaiaController::getDescricao($baia->status_baia_id) }}
                                </a>
                            @else
                                <p class="px-6 py-4 flex items-center">
                                    {{ App\Http\Controllers\portal\BaiaController::getDescricao($baia->status_baia_id) }}
                                </p>
                            @endif                            
                        </td>
                        <td>

                            @if (App\Http\Controllers\portal\PermisionsController::notIsAluno() == true)
                                <a href="{{route('show', ['slug'=>$baia->id])}}"  class="px-6 py-4 flex items-center">
                                    {{$baia->updated_at}}
                                </a>
                            @else
                                <p class="px-6 py-4 flex items-center">
                                    {{$baia->updated_at}}
                                </p>
                            @endif

                            
                        </td>

                        @if (App\Http\Controllers\portal\PermisionsController::notIsAluno() == true)
                            <td class="w-px">
                                <a tabindex="-1" href="{{route('show', ['slug'=>$baia->id])}}"  class="px-4 flex items-center">
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

@endsection
