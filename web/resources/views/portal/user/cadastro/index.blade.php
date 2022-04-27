@extends('templete.index')
@section('content')

<div id="cadastrar">
    <div class="container">
        <div id="login-row" class="row justify-content-center align-items-center">
            <div id="login-column" class="col-md-4">
                <div class="col-md-12" style="text-align: center;">
                    <img class="text-center pt-5" style="max-width: 150px;" src="{{asset('img/cin.png')}}">
                </div>
                <div id="login-box" class="col-md-12" style="height: 560px;margin-bottom: 10vh;">
                    <form id="login-form" class="form" action="{{ route('cadastrar') }}" method="POST">
                        @csrf
                        <h3 class="text-center text-info-red">Cadastrar usuário</h3>
                        <div class="form-group">
                            <label for="nome" class="text-info-red">Nome:</label><br>
                            <input type="text" name="nome" id="nome" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="email" class="text-info-red">E-mail:</label><br>
                            <input type="text" name="email" id="email" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="matricula" class="text-info-red">Matrícula:</label><br>
                            <input type="text" name="matricula" id="matricula" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="password" class="text-info-red">Senha:</label><br>
                            <input type="password" name="password" id="password" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="confirm-password" class="text-info-red">Confirmar senha:</label><br>
                            <input type="password" name="confirm-password" id="confirm-password" class="form-control">
                        </div>
                        <div class="form-group">
                            <button type="submit" name="go" class="btn btn-block  btn-info" style="margin-bottom: 0.5rem;">Cadastrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection