<?
class AddLeadToCRM
{
    /**
     * Created by Promedia.
     * User: Vladimir Krasnoselskikh
     * Date: 27.11.17
     * Time: 11:59
     * Так как кампания Амадо создала формы не через стандартный обработчик битрикса,
     * по этому пришлось писать класс, который отлавливает события при добавлении элемента в инфоблок (так устроенны их формы)
     * и, если, добавленый элемент - форма заявки, то отправить это событие в CRM.
     */
    // создаем обработчик события "OnBeforeIBlockElementAdd"
    function OnBeforeIBlockElementAddHandler(&$arFields) 
    {
        $_params = Array(
            "url_to_crm_handler" => "https://altenergo.bitrix24.ru/rest/82/bv3q1fwnjevh5sds/crm.lead.add/",
        );
        $url_page = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        if ($arFields["IBLOCK_ID"] == 18) {
            $crm_parametrs = Array(
                'TITLE' => $arFields['NAME'],
                'NAME' => $arFields["PROPERTY_VALUES"]["75"],
                'SOURCE_ID' => 'WEB',
                'STATUS_ID' => 'NEW',
                'PHONE' => array(array("VALUE" => $arFields["PROPERTY_VALUES"]["76"], "VALUE_TYPE" => "MOBILE" )),
                'EMAIL' => array(array("VALUE" => $arFields["PROPERTY_VALUES"]["77"], "VALUE_TYPE" => "WORK" )),
                'SOURCE_DESCRIPTION' => $url_page,
                'ASSIGNED_BY_ID' => 70,
                'COMMENTS' => '',
                'WEB' => $url_page
            );

            switch ($arFields['IBLOCK_SECTION'][0]) {
                case 40:
                    $crm_parametrs['TITLE'] = "Форма обратной связи";
                    break;
                case 41:
                    $crm_parametrs['TITLE'] = "Оформил закакз на сайте";
                    break;
                case 42:
                    $crm_parametrs['TITLE'] = "Заказал обратный звонок";
                    break;
                case 43:
                    $crm_parametrs['TITLE'] = "Задал вопрос";
                    break;
                case 44:
                    $crm_parametrs['TITLE'] = "Запросил цену";
                    break;
            };

            if ($arFields["PROPERTY_VALUES"]["78"]) {
                $crm_parametrs['COMMENTS'] .= 'Вопрос от клиента:' . $arFields["PROPERTY_VALUES"]["78"].' ';
            };
            if ($arFields["PROPERTY_VALUES"]["81"]) {
                $db_props = CIBlockElement::GetByID($arFields["PROPERTY_VALUES"]["81"]);
                while ($ar_props = $db_props->Fetch()) {
                    $valueProp = $ar_props["NAME"];
                }
                $crm_parametrs['COMMENTS'] .= 'Услуга:' . $valueProp.' ';
            };
            if ($arFields["PROPERTY_VALUES"]["80"]) {
                $db_props = CIBlockElement::GetByID($arFields["PROPERTY_VALUES"]["80"]);
                while ($ar_props = $db_props->Fetch()) {
                    $valueProp = $ar_props["NAME"];
                }
                $crm_parametrs['COMMENTS'] .= 'Типовой проект:' . $valueProp.' ';
            };
            if ($arFields["PROPERTY_VALUES"]["85"]) {
                $crm_parametrs['COMMENTS'] .= 'Вопрос от клиента:' . $arFields["PROPERTY_VALUES"]["85"].' ';
            };

            if (isset($_COOKIE['utm_source']) ){
                $crm_parametrs['UTM_SOURCE'] = $_COOKIE['utm_source'];
            };

            if (isset($_COOKIE['utm_medium']) ){
                $crm_parametrs['UTM_MEDIUM'] = $_COOKIE['utm_medium'];
            };

            if (isset($_COOKIE['utm_campaign']) ){
                $crm_parametrs['UTM_CAMPAIGN'] = $_COOKIE['utm_campaign'];
            };

            $crmCurl = curl_init();
            curl_setopt_array($crmCurl, array(
                CURLOPT_URL => $_params['url_to_crm_handler'],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => http_build_query(array('fields' => $crm_parametrs,'params' => array("REGISTER_SONET_EVENT" => "Y")))
            ));
            $response = curl_exec($crmCurl);
            curl_close($crmCurl);

            AddMessage2Log(print_r("Ответ на Ваш запрос: " . $response, true), "AddLeadToCRM");
        }
    }
}