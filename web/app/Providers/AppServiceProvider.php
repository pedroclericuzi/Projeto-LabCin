<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * 
     * @return void
     */
    public function boot()
    {

        if (config('app.env') === 'production') {
            \URL::forceScheme('https');
        }

        Schema::defaultStringLength(191);
        config([
            'permissao.ALUNO_ID' => 1,
            'permissao.PROFESSOR_ID' => 2,
            'permissao.MONITOR_ID' => 3,
            'permissao.FUNCIONARIO_ID' => 4
        ]);

        config([
            'status_user.LIBERADO' => 1,
            'status_user.BLOQUEADO' => 2
        ]);

        config([
            'status_baia.LIBERADA' => 1,
            'status_baia.EM_USO' => 2,
            'status_baia.BLOQUEADA' => 3,
            'status_baia.VAZIA' => 4,
            'status_baia.MANUTENCAO' => 5,
            'status_baia.EM_CHECAGEM' => 6
        ]);

        config([
            'status_equipamento.LIVRE' => 1,
            'status_equipamento.EM_USO' => 2,
            'status_equipamento.BLOQUEADO' => 3,
            'status_equipamento.EMPRESTADO' => 4,
            'status_equipamento.RESERVADO' => 5,
            'status_equipamento.RESERVADO' => 6,
            'status_equipamento.AUSENTE' => 7
        ]);

        config([
            'status_reserva.LIBERADA' => 1,
            'status_reserva.CANCELADA' => 2,
            'status_reserva.PENDENTE' => 3
        ]);

        config([
            'log.ERRO' => 1,
            'log.REGISTRO' => 2,
            'log.USO' => 3,
            'log.ATUALIZACAO' => 4
        ]);

        config([
            'hora_aula.INICIO' => "7:00:00",
            'hora_aula.FIM' => "19:00:00"
        ]);
    }
}
