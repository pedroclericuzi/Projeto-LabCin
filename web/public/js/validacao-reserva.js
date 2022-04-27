$(document).ready(function() {
    enableDisableById();
    document.getElementById('data').addEventListener('change', function updateValue(e) { 
        disableInput(e.target.value);
    });

    document.getElementById('baia').addEventListener('click', function updateValue() { 
        var fullDate = $("#data").datepicker('getDate'); 
        var normalizedDate = getFormatedDate(fullDate);
        var isDisabled = $('#baia').is('disabled');
        if(!isDisabled){
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/verificar-baias',
                data: { date: normalizedDate }
            }).done(function( msg ) {
                var lengthBaiasSelect = $('#baia > option').length;
                console.log(msg);
                if(lengthBaiasSelect==1){
                    $(msg).each(function() {
                        $('#baia').append(`<option value='${this.id}'>Baia ${this.num_baia}</option>`);
                    });
                }
            });
        }
    });
});

function selectedDate(date) {
    disableInput(date);
    $("#baia").find('option').not(':first').remove();
}

function disableInput(date){
    if(date != "") {
        $('#baia').prop('disabled', false);
    } else {
        enableDisableById();
    }
}

function enableDisableById(){
    $('#baia').prop('disabled', true);
}

function getFormatedDate(fullDate){
    dia  = fullDate.getDate().toString(),
    diaF = (dia.length == 1) ? '0'+dia : dia,
    mes  = (fullDate.getMonth()+1).toString(), //+1 pois no getMonth Janeiro começa com zero.
    mesF = (mes.length == 1) ? '0'+mes : mes,
    anoF = fullDate.getFullYear();
    return anoF+"-"+mesF+"-"+diaF;
}
