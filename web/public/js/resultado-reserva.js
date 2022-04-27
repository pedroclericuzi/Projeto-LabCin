$(document).ready(function() {
    $('#reject').click(function(){
        console.log("não aceitou");
        var $input = $( this );
        atualizar($input.attr("data-id"), '/rejeitar-reserva');
    });

    $('#accept').click(function(){
        var $input = $( this );
        atualizar($input.attr("data-id"), '/aceitar-reserva');
    });
});

function atualizar(id, url){
    let observacoes = document.getElementById("observacoes").value;
    $.ajax({
        type: "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: url,
        data: { observacoes: observacoes, id: id },
        success: function(data){
            alert("Operação realizada com sucesso!!");
            document.location.reload();
        },
        error: function(data){
            console.log(data);
            alert("Ocorreu um erro");
        }
    });
}


function accept(){
    console.log("aceitou");
    
}


