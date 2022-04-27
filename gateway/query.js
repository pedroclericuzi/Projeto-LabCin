var time = require('./time');
var config = require('./config/default');

var checkBackup = function checkBackup() {
	return "SELECT * FROM backup";
};

var getAllBaias = function getAllBaias() {
	return "SELECT * FROM baia";
};

var getAllOfflineRegisters = function getAllOfflineRegisters() {
	return "SELECT * FROM registro_offline";
};

var scriptInsert_Backup = function scriptInsert_Backup(table, date){
    var script = `INSERT INTO backup (tabela, updated_at) SELECT * FROM (SELECT \'${table}\' AS tabela, \'${date}\' AS updated_at) AS tmp WHERE NOT EXISTS (SELECT tabela FROM backup WHERE tabela = \'${table}\') LIMIT 1;`;
    return script; 
}

var scriptUpdate_Backup = function scriptUpdate_Backup(table, date){
    var script = `UPDATE backup SET tabela = '${table}', updated_at = '${date}' WHERE tabela = '${table}'`;
    return script; 
}

var scriptInsert_RunningOffline = function scriptInsert_RunningOffline(data){
    var script = `INSERT INTO registro_offline (requisicao) VALUES ('${data}');`;
    return script; 
}

var genereteScript_Insert = function genereteScript_Insert(table, data){
    var columns = "(";
    var line = "(";
    Object.keys(data).forEach(key => {
        columns = columns.concat(key);
        if (key == 'updated_at' || key == 'created_at') {
            var dateCorrectFormat = time.correctDateTime(data[key]);
            if(key == 'updated_at'){
                if (table == "user") {
                    //line = line.concat("\'" + dateCorrectFormat + "\', \'"+ config.defaultPassword() +"\')");
                    line = line.concat(setStringLine(dateCorrectFormat) + ", \'"+ config.defaultPassword() +"\')");
                    columns = columns.concat(", password)");
                } else {
                    line = line.concat(setStringLine(dateCorrectFormat) + ")");
                    columns = columns.concat(")");
                }
            } else {
                line = line.concat(setStringLine(dateCorrectFormat) + ", ");
                columns = columns.concat(", ");
            }
        } else {
            //line = line.concat("\'" + data[key] + "\', ");
            line = line.concat(setStringLine(data[key]) + ", ");
            columns = columns.concat(", ");
        }
    })
    return `INSERT INTO ${table} ${columns} VALUES ${line};`
}

var genereteScript_UpdateOrInsert = function genereteScript_UpdateAndInsert(table, data){
    var columns = "(";
    var line = "(";
    var id = "";
    var columnUpdatedData = "";
    Object.keys(data).forEach(key => {
        columns = columns.concat(key);
        if (key == 'updated_at' || key == 'created_at') {
            var dateCorrectFormat = time.correctDateTime(data[key]);
            if(key == 'updated_at'){
                if (table == "user") {
                    //line = line.concat("\'" + dateCorrectFormat + "\', \'"+ config.defaultPassword() +"\')");
                    line = line.concat(setStringLine(dateCorrectFormat) + ", \'"+ config.defaultPassword() +"\')");
                    
                    //line = line.concat(setStringLine(dateCorrectFormat) +` AS ${key}` + ", \'"+ config.defaultPassword() +`\' AS password)`);
                    columns = columns.concat(", password)");
                    columnUpdatedData = columnUpdatedData.concat(`${key} = ${setStringLine(dateCorrectFormat)}, `);
                    columnUpdatedData = columnUpdatedData.concat(`password = ${setStringLine(config.defaultPassword())};`);
                } else {
                    
                    line = line.concat(setStringLine(dateCorrectFormat) + ")");
                    //line = line.concat(setStringLine(dateCorrectFormat) + ` AS ${key})`);
                    columns = columns.concat(")");
                    columnUpdatedData = columnUpdatedData.concat(`${key} = ${setStringLine(dateCorrectFormat)};`);
                }
            } else {
                line = line.concat(setStringLine(dateCorrectFormat) + ", ");
                //line = line.concat(setStringLine(dateCorrectFormat) + ` AS ${key}, `);
                columns = columns.concat(", ");
                columnUpdatedData = columnUpdatedData.concat(`${key} = ${setStringLine(dateCorrectFormat)}, `);
            }
        } else {
            if (isNumber(key)) {
                if (key == 'id') {
                    id = "" + data[key];
                }
                columnUpdatedData = columnUpdatedData.concat(`${key} = ${data[key]}, `);
                //line = line.concat(data[key] + ` AS ${key}, `);
                line = line.concat(data[key] + ", ");
            } else {
                columnUpdatedData = columnUpdatedData.concat(`${key} = \'${data[key]}\', `);
                //line = line.concat(setStringLine(data[key]) + ` AS ${key}, `);
                line = line.concat(setStringLine(data[key]) + ", ");
            }
            columns = columns.concat(", ");
        }
    })
    return `INSERT INTO ${table} ${columns} VALUES ${line} ON DUPLICATE KEY UPDATE ${columnUpdatedData}`;
}

var deleteFrom = function deleteFrom(table, id) {
	return `DELETE from ${table} WHERE id=${id};`;
};

var removeOldRegister = function removeOldRegister(table) {
    return `DELETE FROM ${table} WHERE created_at < DATE_SUB(NOW(), INTERVAL 4 MONTH);`;
}

function isNumber(key) {
    return (key == 'id' || 
        key == 'status_id' ||
        key == 'null' || 
        key == 'baia_id' ||
        key == 'status_baia_id' ||
        key == 'user_usando' ||
        key == 'reserva_id' ||
        key == 'equipamento_id' ||
        key == 'evento_id' ||
        key == 'tipo_id' ||
        key == 'permissao_id')
}

function setStringLine(value) {
    if (value == null || value == "null") {
        return null;
    }
    return "\'" + value + "\'";
}

module.exports = {
    checkBackup: checkBackup,
    removeOldRegister: removeOldRegister,
    getAllBaias: getAllBaias,
    getAllOfflineRegisters,
    genereteScript_Insert:genereteScript_Insert,
    scriptInsert_Backup: scriptInsert_Backup,
    genereteScript_UpdateOrInsert: genereteScript_UpdateOrInsert,
    scriptUpdate_Backup: scriptUpdate_Backup,
    scriptInsert_RunningOffline,
    deleteFrom
};
