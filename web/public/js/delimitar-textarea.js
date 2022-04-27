$(document).ready(function() {
    let justificativaStyleid = "#justificativa";
    let qtdTextoJustificativa = "#qtd_texto_justificativa";

    let observacoesStyleid = "#observacoes";
    let qtdTextoObservacoes = "#qtd_texto_observacoes";

    delimiterText(justificativaStyleid,qtdTextoJustificativa);
    delimiterText(observacoesStyleid,qtdTextoObservacoes);
});

function delimiterText(id,qtdTexto){
    $(id).on('input', function(e) {
        var tval = $(id).val(),
            tlength = tval.length,
            limit = 500,
            remain = parseInt(limit - tlength);
        
        $(qtdTexto).text(tlength + "/500");
        if (remain <= 0 && e.which !== 0 && e.charCode !== 0) {
            $(id).val((tval).substring(0, tlength - 1));
        } else if (tlength >= limit-1) {
            $(id).val(tval.substr(0,limit-1));
        }
    })
}