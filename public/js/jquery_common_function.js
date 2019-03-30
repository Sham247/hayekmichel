/*
* Project Name  : Monocle
* Company       : Wave Code Logix
* Author        : Karthik K
* Created date  : 14 Oct, 2014
* Modified date : 08 Dec, 2014
* Description   : Page contains jquery common scripts
*/
$(function()
{
    /* Function used to send request via ajax type - created by Karthik K on 14 Oct, 2014 */
    $.fn.SendServerRequest = function(AssignVar) 
    {
        var DeclareVar = {
            SendUrl: '',
            FormType: 'POST',
            DataType: 'HTML',
            SentData: 'load=load'
        };
        var MergeVar = $.extend(DeclareVar,AssignVar);
        if (MergeVar.SendUrl != '') 
        {
            return $.ajax({
                url: MergeVar.SendUrl,
                type: MergeVar.FormType,
                dataType: MergeVar.DataType,
                headers: {'cache-control': 'no-cache'},
                data: MergeVar.SentData
            });
        }
    };
    /* Function used to hide alert message - created by Karthik K on 08 Nov, 2014 */
    $.fn.HideAlertMsg = function(AssignVar)
    {
        var DeclareVar = {
            FindSelector : '.alert',
            DelayTime    : 200000
        };
        var MergeVar = $.extend(DeclareVar,AssignVar);
        if(MergeVar.FindSelector != '')
        {
            if($(MergeVar.FindSelector).length > 0)
            {
                $(MergeVar.FindSelector).delay(MergeVar.DelayTime).slideUp('slow',function()
                {
                    $(this).remove();
                });
            }
        }
    };
    $.fn.McSimpleTooltip = function(AssignOptions)
    {
        var DeclareOptions  = {
            FieldObj:'',
            GetAttr:'show-tip'
        };
        var MergeOtions   = $.extend(DeclareOptions,AssignOptions);
        if(MergeOtions.FieldObj != '' && MergeOtions.GetAttr != '')
        {
            var removeTooltip   = 0;
            $('body').on('mouseenter mouseover mousemove',MergeOtions.FieldObj,function(event)
            {
                var GetCurrObj      = $(this);
                var ToolTipText     = GetCurrObj.attr(MergeOtions.GetAttr);
                if(typeof ToolTipText != undefined)
                {
                    $('#toolTip').remove();
                    var removeTooltip   = 50;
                    var getWidth        = GetCurrObj.width();
                    var getLeft         = GetCurrObj.offset().left;
                    var getRight        = $(window).width()-(getLeft+GetCurrObj.outerWidth(true));
                    var getTop          = GetCurrObj.offset().top;
                    var getBottom       = $(window).width()-(getTop+GetCurrObj.outerWidth(true));
                    var getWindowHeight = $(window).height();
                    var appendHtml      = "<div id='toolTip'  style='cursor:pointer;'><div id='toolTipArrow'><div id='toolTipArrowInner'></div></div>";
                    appendHtml         += "<div id='toolTipContent' class='hoverToolTip'><span id='toolTipMsg'>"+ToolTipText+"</span></div></div>";
                    $("body").append(appendHtml);
                    $('#toolTipContent #toolTipMsg').html(ToolTipText);
                    var getContentWidth = $('#toolTip').width();
                    var widthCompare    = Math.round((getWidth-getContentWidth)/2);
                    $('#toolTipArrow').css({'margin-left':getContentWidth/2-6});
                    $('#toolTipArrow').addClass('tooltipArrow');
                    var getTotalHeight  = getBottom-600;
                    $('#toolTip').css({'top':(getTop+GetCurrObj.outerHeight()+6),'margin-left':Math.round(getLeft+widthCompare-3)});
                    $('#toolTip').addClass('toolTipBottom');
                    $('#toolTip').show();
                    /*var TooltipVal = setInterval(function()
                    {
                        if($('#toolTip').is(':visible'))
                        {
                            $('#toolTip').remove();
                            clearInterval(TooltipVal);
                        }
                    },5000);*/
                }
            }).on('mouseleave',MergeOtions.FieldObj,function(event)
            {
                setTimeout(function()
                {
                    $('#toolTip').remove();
                },removeTooltip);
            });
        }
    };
    /* Function used to allow only number in textbox - created by Karthik K on 08 Dec, 2014 */
    $.fn.AllowOnlyNumbers = function(AssignVar)
    {
        var DeclareVar = {
            RestrictObj : ''
        };
        var MergeVar    = $.extend(DeclareVar,AssignVar);
        if(MergeVar.RestrictObj != '')
        {
            $('body').on('keypress',MergeVar.RestrictObj,function(GetCode)
            {
                if (GetCode.which != 8 && GetCode.which != 0 && (GetCode.which < 48 || GetCode.which > 57)) 
                {
                    return false;
                }
            });
        }
    };
});
$(function()
{
    /* Script used to hide and remove page alert message - created by Karthik K on 06 Nov, 2014 */
    $.fn.HideAlertMsg();
    /* Script used to change scroll position - created by Karthik K on 06 Nov, 2014 */
    $('body').on('click','#back-to-top',function()
    {
        $('html,body').animate({scrollTop:0},1000);
    });
});
$(window).scroll(function() 
{
    if ($(this).scrollTop()>50)
    {
        $('#back-to-top').fadeIn();  
    }
    else
    {
        $('#back-to-top').fadeOut();
    }
});