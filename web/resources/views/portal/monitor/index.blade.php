@extends('templete.app')
@section('body')

<div>
    <h1 class="text-3xl text-gray-600 pb-6">Monitores</h1>

    <div class="mb-6 flex justify-between items-center">
        <div class="flex items-center w-full max-w-md mr-4">
            <div class="flex w-2/3 bg-white shadow rounded">
                <input type="text" name="search" placeholder="Pesquisar.." class="relative w-full rounded px-6 py-3 focus:shadow-outline" wire:model.debounce.500ms="search">
            </div>
        </div>
        <div>
            <a href="{{route('add_monitor')}}" class="bg-red-600 px-3 py-2 rounded-lg shadow text-white font-bold hover:bg-red-700">
                Adicionar monitor
            </a>
        </div>
    </div>

    <div class="bg-white rounded shadow overflow-x-auto">
        <table class="w-full whitespace-no-wrap">
            <thead>
                <tr class="text-left font-bold text-gray-600">
                    <th class="px-6 pt-6 pb-4">Nome</th> 
                    <th colspan="1" class="px-6 pt-6 pb-4">Email</th>
                    <th colspan="1" class="px-6 pt-6 pb-4">Disciplina</th>
                    <th colspan="1" class="px-6 pt-6 pb-4">Última atualização</th>
                </tr>
            </thead>
            <tbody class="text-gray-600">
                @forelse ($monitores as $m)
                    <tr class="border-t">
                        <td>
                            <a class="px-6 py-4 flex items-center">
                                {{ App\Http\Controllers\portal\MonitorController::formattedName($m->nome) }}
                            </a>
                        </td>
                        <td>
                            <a class="px-6 py-4 flex items-center">
                                {{$m->email}}
                            </a>
                        </td>
                        <td>
                            <a class="px-6 py-4 flex items-center">
                                {{$m->disciplina_monitor}}
                            </a>
                        </td>
                        <td>
                            <a class="px-6 py-4 flex items-center">
                                {{$m->updated_at}}
                            </a>
                        </td>
                        <td class="w-px">
                            <a tabindex="-1" href="{{route('delete_monitor', ['id'=>$m->id])}}"  class="px-4 flex items-center">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>
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
