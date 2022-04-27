const mariadb = require('mariadb');
var query = require('./query.js');
var time = require('./time.js');
var webserver = require('./webserver.js');
const pathToAllData = "database/all";

const pool = mariadb.createPool({
  host: 'localhost', 
  database : 'labcin',
  user:'root', 
  password: '',
  port:'3307'
});

/*const pool = mariadb.createPool({
  host: 'localhost', 
  database : 'labcin',
  user:'cin', 
  password: 'labcin'
});*/

var runSqlScript = function runSqlScript(script) {
  return new Promise(async (resolve, reject) => {
    await pool.getConnection().then(conn => {  
      conn.query(script)
        .then(data => {
          var rows = JSON.stringify(data);
          resolve(rows);
        })
        .catch(err => {
          conn.end();
          reject("Error: " + err.message);
        })
        conn.release();
    }).catch(err => {
      console.log("Deu erro");
      console.log(err);
      reject("Erro na conexão com o banco de dados. Verifique a conexão e tente novamente.");
    });
  });
}

function feedDatabaseWithAllData(rows) {
  if (rows.code == 200) {
    //console.log(rows)
    var keys = Object.keys(rows);
    keys.forEach(key => {
      if (key !== 'code') {
        var updatedTime = "";
        rows[key].forEach(values => {
          var scriptInsert = query.genereteScript_Insert(key, values);
          runSqlScript(scriptInsert).then(function() {
            console.log(`${key} > Successful`);
          }).catch(function(rej) {
            console.log(`${key} > Deu erro, não inseriu. Motivo: ${rej}`);
          });
          updatedTime = values['updated_at'];
        });
        var dateCorrectFormat = time.correctDateTime(updatedTime);
        runSqlScript(query.scriptInsert_Backup(key, dateCorrectFormat))
        .then(function() { 
          console.log("Beckup > Successful") 
        }) .catch(function(rej) {
          console.log("Deu erro, não inseriu. Motivo: " + rej); 
        });
      }
    })
  }
}

async function getUpdatedData(table, date) {
  const parametersPost = table + "/" + date;
  await webserver.verificarSeExisteNovosDados(parametersPost).then(function(rows) {
    var time2 = require('./time.js');
    var keys = Object.keys(rows);
    keys.forEach(key => {
      if (key !== 'code') {
        var updatedTime = "";
        console.log(table + " - " + rows[key].length)
        if (rows[key].length > 0) {
          rows[key].forEach(values => {
            var scriptUpdateOrInsert = query.genereteScript_UpdateOrInsert(table, values);
            console.log(scriptUpdateOrInsert);
            runSqlScript(scriptUpdateOrInsert).then(function() {
              console.log(`${key} > Successful`);
            }).catch(function(rej) {
              console.log(`${key} > Deu erro, não inseriu. Motivo: ${rej}`);
            });
            updatedTime = values['updated_at'];
          });
          var dateCorrectFormat = time2.correctDateTime(updatedTime);
          console.log("Data " + dateCorrectFormat)
          runSqlScript(query.scriptUpdate_Backup(table, dateCorrectFormat))
          .then(function() { 
            console.log("Beckup > Successful") 
          }) .catch(function(rej) {
            console.log("Deu erro, não inseriu. Motivo: " + rej); 
          });
        }
      }
    })
  }).catch(err => {
    console.log(err); 
  });
}

var dailyBackup = function dailyBackup() {
  runSqlScript(query.checkBackup()).then(data => {
    const responseJSON = JSON.parse(data);
    if (responseJSON.length == 0) {
      webserver.getAPI(pathToAllData).then(function(val) {
        var rows = val.data;
        feedDatabaseWithAllData(rows);
      }).catch(err => {
        console.log(err);
      });
    } else {
      responseJSON.forEach(function(response) {
        if (isMutableDataTable(response.tabela)){
          runSqlScript(query.removeOldRegister()).then(() => {
            console.log(`Dados antigos removidos`);
          }).catch(function(rej) {
            console.log(`Não foi possível remover 
              os dados antigos. Motivo: ${rej}`);
          });
        }
        getUpdatedData(response.tabela, response.updated_at);
      });
    }
  });
}

function isMutableDataTable(table) {
  return (table == 'change_log' || 
        table == 'reserva' ||
        table == 'user')
}

var checkOfflineRegistersTable = function checkOfflineRegistersTable() {
  runSqlScript(query.getAllOfflineRegisters()).then(data => {
    const responseJSON = JSON.parse(data);
    if (responseJSON.length > 0) {
      responseJSON.forEach(function(response) {
        console.log("Alguma solicitação foi feita no momento em que o gateway estava sem internet.");
        webserver.fecharBaiaSemRede(response.requisicao).then(() => {
          runSqlScript(query.deleteFrom("registro_offline", response.id));
        }).catch(err => {
          console.log(err);
        });
      });
    }
  });
}

module.exports = {
	runSqlScript,
	dailyBackup,
  checkOfflineRegistersTable
};