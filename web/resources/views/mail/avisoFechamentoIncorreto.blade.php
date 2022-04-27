<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    </head>
    <body>

        @if ($permissao == config("permissao.ALUNO_ID"))
            <p>
                Olá, {{$aluno}}!
            </p><p></p>

            @if ($tipoNotificacao == 1)
                <p>
                    Detectamos a ausência de um ou mais equipamentos, retorne à baia e verifique. 
                </p>
                <p>
                    Isso pode ter sido ocasionado porque o equipamento não foi devolvido para a baia da maneira correta e o sistema não identificou sua presença.
                    Caso o problema persista procure um de nossos monitores.
                </p>
            @elseif ($tipoNotificacao == 2)
                <p>
                    Foi realizada a checagem e desta vez o(s) equipamento(s) foi inserido corretamente. Obrigado!
                </p>
            @else
                <p>
                    Foi realizada a checagem, mas os equipamentos continuam fora da baia. Em caso de mau funcionamento, procure um dos nossos monitores.
                </p>
            @endif
        @else
            <p>
                Olá, monitor!
            </p><p></p>
            @if ($tipoNotificacao == 1)
                <p>
                    Detectamos a ausência de um ou mais equipamentos, o aluno já foi notificado.
                </p>
            @elseif ($tipoNotificacao == 2)
                <p>
                    O aluno voltou a baia para realizar a checagem e desta vez o(s) equipamento(s) foi inserido corretamente.
                </p>
            @elseif ($tipoNotificacao == 403)
                <p>
                    A baia foi aberta manualmente e ao ser fechada detectamos a ausência de um ou mais equipamentos.
                </p>
                <p>
                    Favor verifique e procure um professor responsável caso seja necessário.
                </p>
            @else
                <p>
                    O aluno voltou a baia para realizar a checagem mas os equipamentos continuam fora da baia.
                </p>
                <p>
                    Fale com o aluno ou verifique a baia para ter certeza que não é um mau funcionamento.
                </p>
            @endif
            <p>
                <b>Aluno: {{$aluno}}</b> 
            </p>
            <p>
                <b>Matrícula: {{$matAluno}}</b> 
            </p>
            <p>
                <b>Baia: {{$baia}}</b> 
            </p>
        @endif
    </body>
</html>