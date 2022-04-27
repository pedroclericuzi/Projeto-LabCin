const axios = require('axios');
const URL = 'https://labcin.herokuapp.com/api/'
//const URL = 'http://127.0.0.1:8000/api/'
const fechar_path = 'baia/fechar/'
const abrir_path = 'baia/abrir/'
const pathToUpdateLocalTables = "database/update/";

var fecharBaia = function fecharBaia(baia, rfids) {
	return new Promise(function (resolve) {
		var checkRFID = rfids;
		if(checkRFID == undefined || checkRFID == "") {
			checkRFID = "NONE;";
		}
		var url = URL + fechar_path + baia + "/" + checkRFID
		//console.log(url);
		postAPI(url).then(function (val) {
			if (val == '400_network_error') {
				resolve(url);
			} else {
				resolve(val.data);
			}
		});
	});
};

var fecharBaiaSemRede = function fecharBaiaSemRede(url) {
	return new Promise(function (resolve) {
		postAPI(url).then(function (val) {
			if (val == '400_network_error') {
				resolve(url);
			} else {
				console.log("Registro enviado para a nuvem!");
				resolve(val.data);
			}
		});
	});
};

var abrirBaia = function abrirBaia(baia, user) {
	return new Promise(function (resolve, reject) {
		var url = URL + abrir_path + baia + "/" + user
		postAPI(url).then(function (val) {
			resolve(val.data)
		});
	});
};
var verificarSeExisteNovosDados = function verificarSeExisteNovosDados(params) {
	return new Promise(function (resolve, reject) {
		var url = URL + pathToUpdateLocalTables + params
		postAPI(url).then(function (val) {
			resolve(val.data)
		});
	});
};

var postAPI = function postAPI(url) {
	return new Promise(function (resolve) {
		axios.post(url).then(response => {
			resolve(response);
		}).catch(() => {
			resolve("400_network_error");
		})
	});
};

var getAPI = function getAPI(path) {
	return new Promise(function (resolve, reject) {
		const fullUrl = URL + "" + path;
		axios.get(fullUrl).then(response => {
			resolve(response);
		}).catch(error => {
			reject("400_network_error");
		})
	});
};

module.exports = {
	fecharBaia,
	abrirBaia,
	postAPI,
	getAPI,
	verificarSeExisteNovosDados,
	fecharBaiaSemRede
};