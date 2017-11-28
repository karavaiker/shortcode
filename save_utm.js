//На один день записывает источник пользователя в cookie

var getParameterByName = function (name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

var createCookie = function(name, value, days) {
    var expires;
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toGMTString();
    }
    else {
        expires = "";
    }
    document.cookie = name + "=" + value + expires + "; path=/";
}


var source = getParameterByName('utm_source');
var medium = getParameterByName('utm_medium');
var campaign = getParameterByName('utm_campaign');

if (source){
	createCookie('utm_source',source,1 )
}

if (medium){
	createCookie('utm_medium',source,1 )
}

if (campaign){
	createCookie('utm_campaign',source,1 )
}