/*
 * Created by v.krasnoselskikh on 15.02.16.
 */


function changeNumber()
    {
        //Настройка номеров телефонов
        var forYandexPeople = '+7 (910) 010-01-66';
        var forGooglePeople= '+ 7 (910) 010-01-65';
        
        var phone = "";
        //Код, который определяет по cookies, какой телефон нужно показать
        var source = get_cookie ( 'source_type' );
        if ( source == 'google' ) phone = forGooglePeople;
        else if ( source == 'yandex' ) phone = forYandexPeople;
        
        if (phone != "") {   
        //Подмена номера
        var elements = document.getElementsByClassName("icon-block-phone-number");
        for (var i = 0; i < elements.length; i++) {
            var number = elements[i];
            number.innerHTML = phone;
        }
        }; 
    }

function get_cookie ( cookie_name )
{
    var results = document.cookie.match ( '(^|;) ?' + cookie_name + '=([^;]*)(;|$)' );

    if ( results )
        return ( unescape ( results[2] ) );
    else
        return null;
}

document.addEventListener("DOMContentLoaded", changeNumber);
