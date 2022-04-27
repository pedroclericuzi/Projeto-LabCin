@extends('templete.index')
@section('content')

<div id="login">
    <div class="container">
        <div id="login-row" class="row justify-content-center align-items-center">
            <div id="login-column" class="col-md-4">
                <div class="col-md-12" style="text-align: center;">
                    <img class="text-center pt-5" style="max-width: 150px;" src="{{asset('img/cin.png')}}">
                </div>
                <div id="login-box" class="col-md-12">
                    <form id="login-form" class="form" action="{{ route('logar') }}" method="POST">
                        @csrf
                        <h3 class="text-center text-info-red">Sistema LabCIn</h3>
                        <div class="form-group">
                            <label for="matricula" class="text-info-red">Matrícula:</label><br>
                            <input type="text" name="matricula" id="matricula" value="{{$matricula}}" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="password" class="text-info-red">Senha:</label><br>
                            <input type="password" name="password" id="password" class="form-control">
                        </div>
                        <div class="form-group">
                            <button type="submit" name="go" class="btn btn-block  btn-info" style="margin-bottom: 0.5rem;">Entrar</button>
                            <ul style="display: inline-flex;list-style: none;padding-left: 0rem;margin-left: -0.5vw;">
                                <li><a href="{{ route('cadastrar') }}" id="bt_create_account" class="btn btn-block btn-default">Criar uma conta</a></li>
                                <li><a href="{{ route('esqueceu-senha') }}" class="btn btn-block btn-default">Esqueceu a senha?</a></li>
                            </ul>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection