/**
 * @author v.krasnoselskikh (karavaiker)
 * @description Добавляет к длинным блокам с текстом кнопку показать/скрыть.
 *
 * @constructor
 * @param {siring} heightParam - Высота скрываемого блока.
 * @param {boolean} showShadow - Применить градиент поверх текста в свернутом виде.
 */

(function($){
    $.fn.showHiddenText = function (options){
        //Set defaults params
        options = $.extend({
            heightParam : 180,
            showShadow : true
        }, options);

        var heightParam = options.height;
        var isShadow = options.showShadow;


        if ($(this).height() > heightParam ) {
            $(this).after('<a id="showHideText-btn"></a>');
            var triggerBtn = $("#showHideText-btn")

            var textBlock= $(this);
            var heightBeforeHiddenText = textBlock.height();
            var shadowBlock = '<div class=\"hiddenShadow\"></div>'

            //Add inline style for elements.
            textBlock.css({
                "position": "relative",
                "overflow": "hidden",
            });
            triggerBtn.css({'cursor':'pointer'});

            //Add gradient shadow
            if (isShadow){
                textBlock.append(shadowBlock);
                var style = ".hiddenShadow{background: -moz-linear-gradient(top,  rgba(255,255,255,0) 0%, rgba(255,255,255,1) 80%, rgba(255,255,255,1) 83%, rgba(255,255,255,1) 99%);background: -webkit-linear-gradient(top,  rgba(255,255,255,0) 0%,rgba(255,255,255,1) 80%,rgba(255,255,255,1) 83%,rgba(255,255,255,1) 99%);background: linear-gradient(to bottom,  rgba(255,255,255,0) 0%,rgba(255,255,255,1) 80%,rgba(255,255,255,1) 83%,rgba(255,255,255,1) 99%);filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#00ffffff', endColorstr='#ffffff',GradientType=0 );}";
                $('head').append('<style>'+style+'</style>');
                $('.hiddenShadow').css({
                    "position": "absolute",
                    "bottom": "0",
                    "width": "100%",
                    "height": "100px"
                });
            }

            function showText(){
                textBlock.animate({
                    height: heightBeforeHiddenText
                }, 300).data('hideText',false);
                $('.hiddenShadow').hide();
                $('#showHideText-btn').text('Скрыть текст');
            }
            function hideText(){
                textBlock.animate({ height: heightParam}, 400).data('hideText',true);
                $('.hiddenShadow').show();
                $('#showHideText-btn').text('Показать текст');
            }


            hideText();

            triggerBtn.click(function(){
                if(textBlock.data('hideText')) {
                    showText();
                } else {
                    hideText();
                }
            });
        }
        return true;
    }

    //Set default for .show-hidden-text element
    $(document).ready(function () {
        $('.show-hidden-text').showHiddenText({
            height: 120,
            showShadow: true
        });
    })

})(jQuery);

