var net = require('net');
var schedule = require('node-schedule');
var webserver = require('./webserver.js');
var database = require('./database.js');
var query = require('./query.js');
var checkInternetConnected = require('check-internet-connected');

const abrirBaiaCode = "1"
const fecharBaiaCode = "2"
//const ip = '169.254.188.3'; //'169.254.191.189';

var estaOffline = false;

const server = net.createServer((activeServer) => {
  // 'connection' listener.
	activeServer.setEncoding("utf8");
  console.log('Uma baia foi conectada');
  activeServer.on('data', function (data) {
		/* INICIO APAGAR AQUI */
		console.log(""); // CONTAGEM
		console.log('Recebeu a mensagem ' + data + ' em ' + dataHora())
		/* FIM APAGAR AQUI */
		const dataRequested = data + "";
		const codigosEnviadosPlaca = dataRequested.split("#")
		if (codigosEnviadosPlaca[0] == abrirBaiaCode) {
			/* INICIO APAGAR AQUI */
			console.log(""); // CONTAGEM
			console.log('Enviou pra API') // CONTAGEM
			inicio = new Date().getTime() // CONTAGEM
			/* FIM APAGAR AQUI */
			webserver.abrirBaia(codigosEnviadosPlaca[1], codigosEnviadosPlaca[2])
				.then(function (val) {
				/* INICIO APAGAR AQUI */
				total = new Date().getTime() - inicio; // CONTAGEM
				//console.log('|----- O dado chegou na API em ' + val.start_date); // CONTAGEM
				console.log('|----- O tempo de processamento dentro da API foi de ' + val.execution_time + ' milissegundos'); // CONTAGEM
				console.log('|----- Somente o tempo do transporte (Gateway -> API / API -> Gateway) durou '+ (total - val.execution_time) + ' milissegundos'); // CONTAGEM
				console.log('|----- O tempo total entre o envio, o processamento e a resposta da API foi de '+ total + ' milissegundos'); 
				console.log("");
				/* FIM APAGAR AQUI */
				var res = val.num_baia + "#";
				if (val.code == 200) {
					activeServer.write(res + 'allowed\n');
					/* INICIO APAGAR AQUI */
					console.log('Enviado de volta à baia '+ val.num_baia  + ' em ' + dataHora())
					console.log("------------ PROCESSO FINALIZADO -------------"); // CONTAGEM
					/* FIM APAGAR AQUI */
				}  else if (val.code == 10) {
					activeServer.write(res + 'blocked\n');
					/* INICIO APAGAR AQUI */
					console.log('Enviado de volta à baia '+ val.num_baia  + ' em ' + dataHora())
					console.log("------------ PROCESSO FINALIZADO -------------"); // CONTAGEM
					/* FIM APAGAR AQUI */
				} else if (val.code == 204) {
					activeServer.write(res + 'notfound\n');
					/* INICIO APAGAR AQUI */
					console.log('Enviado de volta à baia '+ val.num_baia  + ' em ' + dataHora())
					console.log("------------ PROCESSO FINALIZADO -------------"); // CONTAGEM
					/* FIM APAGAR AQUI */
				} else {
					activeServer.write(res + 'danied\n');
					/* INICIO APAGAR AQUI */
					console.log('Enviado de volta à baia '+ val.num_baia  + ' em ' + dataHora())
					console.log("------------ PROCESSO FINALIZADO -------------"); // CONTAGEM
					/* FIM APAGAR AQUI */
				}
			});
		} else if (codigosEnviadosPlaca[0] == fecharBaiaCode) {
			/* INICIO APAGAR AQUI */
			console.log(""); // CONTAGEM
			console.log('Enviou pra API'); // CONTAGEM
			inicio = new Date().getTime(); // CONTAGEM
			/* FIM APAGAR AQUI */
			webserver.fecharBaia(codigosEnviadosPlaca[1], codigosEnviadosPlaca[2])
				.then(function (val) {
				/* INICIO APAGAR AQUI */
				total = new Date().getTime() - inicio; // CONTAGEM
				//console.log('|----- O dado chegou na API em ' + val.start_date); // CONTAGEM
				console.log('|----- O tempo de processamento dentro da API foi de ' + val.execution_time + ' milissegundos'); // CONTAGEM
				console.log('|----- Somente o tempo do transporte (Gateway -> API / API -> Gateway) durou '+ (total - val.execution_time) + ' milissegundos'); // CONTAGEM
				console.log('|----- O tempo total entre o envio, o processamento e a resposta da API foi de '+ total + ' milissegundos'); 
				console.log(""); // CONTAGEM
				/* FIM APAGAR AQUI */
				var res = val.num_baia + "#";
				if (val.code == undefined) {
					const insertInOfflineTable = query.scriptInsert_RunningOffline(val);
					database.runSqlScript(insertInOfflineTable);
					activeServer.write(res + 'no_network\n');
					/* INICIO APAGAR AQUI */
					console.log('Enviado de volta à baia '+ val.num_baia  + ' em ' + dataHora())
					console.log("------------ PROCESSO FINALIZADO -------------"); // CONTAGEM
				} else {
					if (val.code == 200 || val.code == -1) {
						activeServer.write(res + 'locked\n');
						/* INICIO APAGAR AQUI */
						console.log('Enviado de volta à baia '+ val.num_baia  + ' em ' + dataHora())
						console.log("------------ PROCESSO FINALIZADO -------------"); // CONTAGEM
					} else {
						activeServer.write(res + 'blocked\n');
						/* INICIO APAGAR AQUI */
						console.log('Enviado de volta à baia '+ val.num_baia  + ' em ' + dataHora())
						console.log("------------ PROCESSO FINALIZADO -------------"); // CONTAGEM
					}
				}
			});
		} else {
			console.log('Received: ' + dataRequested)
		}
  });

	setInterval(function(){
		testeConectividade(activeServer);
	}, 15000);

	activeServer.on('error', (err) => {
		console.log("erro")
	});

	activeServer.on('end', () => {
		console.log('client disconnected');
	});
});

/* INICIO APAGAR AQUI */
var dataHora = function dataHora() {
	var data = new Date();

	// Guarda cada pedaço em uma variável
	var dia     = data.getDate();           // 1-31
	var dia_sem = data.getDay();            // 0-6 (zero=domingo)
	var mes     = data.getMonth();          // 0-11 (zero=janeiro)
	var ano2    = data.getYear();           // 2 dígitos
	var ano4    = data.getFullYear();       // 4 dígitos
	var hora    = data.getHours();          // 0-23
	var min     = data.getMinutes();        // 0-59
	var seg     = data.getSeconds();        // 0-59
	var mseg    = data.getMilliseconds();   // 0-999
	var tz      = data.getTimezoneOffset(); // em minutos

	// Formata a data e a hora (note o mês + 1)
	var str_data = dia + '/' + (mes+1) + '/' + ano4;
	var str_hora = hora + ':' + min + ':' + seg + ':' + mseg;
	return str_data + ' às ' + str_hora;
}
/* FIM APAGAR AQUI */

var testeConectividade = function testeConectividade(activeServer) {
	const config = {
		timeout: 5000,
		retries: 3,
		domain: "google.com",
	}
	checkInternetConnected(config)
	.then(() => {
		database.checkOfflineRegistersTable();
		if (estaOffline) {
			console.log("Conexão reestabelecida.");
			activeServer.write('locked\n');
			estaOffline = false;
		}
	})
	.catch(() => {
		console.log("Sem internet.");
		estaOffline = true;
	});
}

schedule.scheduleJob({hour: 05, minute: 00}, function(){
	console.log("Entrou aqui");
	database.dailyBackup();
});

server.listen(8080, () => {
  console.log('Servidor Ativo');
});