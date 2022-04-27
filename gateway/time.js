var getDateTime = function getDateTime() {
    var now     = new Date(); 
    var year    = now.getFullYear();
    var month   = now.getMonth()+1; 
    var day     = now.getDate();
    var hour    = now.getHours();
    var minute  = now.getMinutes();
    var second  = now.getSeconds(); 
    if(month.toString().length == 1) {
         month = '0'+month;
    }
    if(day.toString().length == 1) {
         day = '0'+day;
    }   
    if(hour.toString().length == 1) {
         hour = '0'+hour;
    }
    if(minute.toString().length == 1) {
         minute = '0'+minute;
    }
    if(second.toString().length == 1) {
         second = '0'+second;
    }   
    var dateTime = day+'/'+month+'/'+year+' '+hour+':'+minute+':'+second;   
     return dateTime;
}

var correctDateTime =  function correctDateTime(updatedTime) {
     var dateCorrectFormat = new Date(updatedTime).toISOString().replace(/T/, ' ').replace(/\..+/, '');
     return dateCorrectFormat;
}

module.exports = {
    getDateTime: getDateTime,
    correctDateTime: correctDateTime
};

/*
INSERT INTO permissao (id, descricao, created_at, updated_at) VALUES ()

INSERT INTO status_baia (id, descricao, created_at, updated_at) VALUES

INSERT INTO status_equipamento (id, descricao, created_at, updated_at) VALUES

INSERT INTO status_reserva (id, descricao, created_at, updated_at) VALUES

INSERT INTO tipo_equipamento (id, descricao, created_at, updated_at) VALUES

INSERT INTO tipo_evento (id, descricao, created_at, updated_at) VALUES

INSERT INTO status_user (id, descricao, created_at, updated_at) VALUES

INSERT INTO user (id, matricula, nome, rfid, disciplina_monitor, email, email_verified_at, password, remember_token, permissao_id, status, created_at, updated_at) VALUES
    
INSERT INTO baia (id, num_baia, user_usando, status_baia_id, created_at, updated_at) VALUES

INSERT INTO reserva (id, cpf, justificativa, observacoes, data, hora, baia_id, status_id, created_at, updated_at) VALUES

INSERT INTO change_log (id, mat_aluno, mat_monitor, mensagem, baia_id, reserva_id, equipamento_id, evento_id, created_at, updated_at) VALUES

INSERT INTO equipamento (id, uuid_tag, marca, modelo, tombamento, estado_conserv, baia_id, tipo_id, status_id, created_at, updated_at) VALUES
*/