/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


function enviar() {
    document.getElementById('form').submit();
}

function createLoader() {
    req = null;
    if (window.XMLHttpRequest) {
        req = new XMLHttpRequest();
    } else if (window.ActiveXObject) {
        req = new ActiveXObject("Microsoft.XMLHTTP");
        if (!req) {
            req = null;
        }
    }
    return req;
}

function ajaxValue(url) {
    var req = createLoader();
    var tmp = httpValue(req, url);
    return trim(breakDestroy(req.responseText));
}

function httpValue(req, url) {
    req.open("GET", url, false);
    var val;
    req.onreadystatechange = function () {
        if (req.readyState == 4) {
            if (req.status == 200) {
                val = req.responseText;
                return val;
            } else {
                alert(url);
                alert("Houve um problema ao obter os dados:\n" + req.statusText);
            }
        }
    }
    req.send('');
}

function trim(inputString) {
    if (typeof inputString != "string") {
        return inputString;
    }
    var retValue = inputString;
    var ch = retValue.substring(0, 1);
    while (ch == " ") { // checa os espaï¿½os apartir do comeï¿½o da string
        retValue = retValue.substring(1, retValue.length);
        ch = retValue.substring(0, 1);
    }
    ch = retValue.substring(retValue.length - 1, retValue.length);
    while (ch == " ") { // checa os espaï¿½os apartir do fim da string
        retValue = retValue.substring(0, retValue.length - 1);
        ch = retValue.substring(retValue.length - 1, retValue.length);
    }
    while (retValue.indexOf("  ") != -1) { // Note that there are two spaces in the string - look for multiple spaces within the string
        retValue = retValue.substring(0, retValue.indexOf("  ")) + retValue.substring(retValue.indexOf("  ") + 1, retValue.length);
    }
    return retValue; // retorna a string sem espaï¿½os
}

function breakDestroy(str) {
    str = escape(str);
    str = str.split("%0D").join("");
    str = str.split("%0A").join("");
    str = unescape(str);
    return str;
}

function menu(_k) {
    window.location.href = '?mode=' + _k;
}


function numberMask(obj, len) {
    if (len == undefined) {
        len = 0;
    }
    var xLen = parseFloat(Math.pow(10, len), 10);
    obj.value = obj.value.replace(",", ".");
    if (obj.value.indexOf(".") != obj.value.lastIndexOf(".")) {
        obj.value = obj.value.substr(0, obj.value.indexOf(".") + 1);
    }
    if (isNaN(obj.value)) {
        obj.value = 0;
    }
    if (obj.value.indexOf(".") >= 0) {
        var tmp = obj.value.split(".");
        obj.value = tmp[0] + "." + tmp[1].substr(0, len);
    }
}