@extends('templete.index')
@section('content')

<!--<script>       
    $(document).ready(function () {              
        $('#bt_create_account').on('click', function() {
            //if( this.value === 'Simon') 
            $('#login').hide();
            $('#cadastrar').show();
        });
    });
</script>!-->

<div id="login">
    <div class="container">
        <div id="login-row" class="row justify-content-center align-items-center">
            <div id="login-column" class="col-md-4">
                <div class="col-md-12" style="text-align: center;">
                    <img class="text-center pt-5" style="max-width: 150px;" src="{{asset('img/cin.png')}}">
                </div>
                <div id="login-box" class="col-md-12" style="height: 220px;">
                    <form id="login-form" class="form" action="{{ route('logar') }}" method="POST">
                        @csrf
                        <h3 class="text-center text-info-red">Redefinir senha</h3>
                        <div class="form-group">
                            <label for="email" class="text-info-red">E-mail:</label><br>
                            <input type="email" name="email" id="email" class="form-control">
                        </div>
                        <div class="form-group">
                            <button type="submit" name="go" class="btn btn-block  btn-info" style="margin-bottom: 0.5rem;">Enviar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection