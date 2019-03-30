<?php /*<script type="text/javascript" src="<?php echo JsUrl('jquery_canvas.js'); ?>"></script> */ ?>
<script type="text/javascript">
$(function()
{
    $('body').on('click','ul.ReportCategoryUl a.CategoryLink',function()
    {
        var CurrObj    = $(this);
        var CategoryId = CurrObj.attr('category-id');
        $('ul.ReportCategoryUl a.CategoryLink').removeClass('ActiveLink');
        CurrObj.addClass('ActiveLink');
        if(CategoryId != '')
        {
            $('ul.ReportCategoryUl .ReportSubCategoryUl').each(function()
            {
                if($(this).attr('id') == 'ReportSubCategoryUl'+CategoryId)
                {
                    $('ul.ReportCategoryUl a.SubCategoryLink').removeClass('ActiveLink');
                    $('#ReportSubCategoryUl'+CategoryId).slideDown(function()
                    {
                        $('li:first a.SubCategoryLink',$(this)).addClass('ActiveLink');
                        var SubCategoryId = $('li:first a.SubCategoryLink',$(this)).attr('sub-category-id');
                        $.fn.GetSubCategoryResult({SubCategoryId:SubCategoryId});
                    });
                }
                else
                {
                    $(this).slideUp();
                }
            });
        }
    });
    $('body').on('click','ul.ReportCategoryUl li ul.ReportSubCategoryUl a.SubCategoryLink',function()
    {
        var SubCatObj = $(this);
        var SubCategoryId = SubCatObj.attr('sub-category-id');
        if(SubCatObj.hasClass('ActiveLink') == false && SubCategoryId != '')
        {
            $('ul.ReportCategoryUl a.SubCategoryLink').removeClass('ActiveLink');
            SubCatObj.addClass('ActiveLink');
            $.fn.GetSubCategoryResult({SubCategoryId:SubCategoryId});
        }
    });
});
$.fn.GetSubCategoryResult = function(AssignVal)
{
    var DeclareVal = {
        SubCategoryId:''
    };
    var MergeVal = $.extend(DeclareVal,AssignVal);
    if(MergeVal.SubCategoryId != '')
    {
        $('.DisplayReportsContent').html("<div class='txt_center'><img id='img-spinner' src='<?php echo ImageUrl("loading_spinner.gif"); ?>' alt='Loading'/></div>");
        var PostData    = "SubCategoryId="+MergeVal.SubCategoryId;
        var SendRequest = $.fn.SendServerRequest({SendUrl:'<?php echo base_url("reportresult"); ?>',SentData:PostData,DataType:'JSON'});
        SendRequest.success(function(GetResponse)
        {
            if(GetResponse.ShowReports != '')
            {
                $('.DisplayReportsContent').html(GetResponse.ShowReports);
                $('html,body').animate({scrollTop:100},1000);
            }
        });
    }
};
<?php /*
  window.onload = function () {
    var chart = new CanvasJS.Chart("chartContainer",
    {
      title:{
      text: "Claims Comparison Methods"   
      },
      axisY:{
        title:""   
      },
      data: [
      {        
        type: "stackedColumn",
        toolTipContent: "{label}<br/><span style='\"'color: {color};'\"'><strong>{name}</strong></span>: {y}mn tonnes",
        name: "Method 1",
        showInLegend: "true",
        dataPoints: [
        {  y: 111338 , label: "USA"},
        {  y: 49088, label: "Russia" },
        {  y: 62200, label: "China" },
        {  y: 90085, label: "India" },
        {  y: 38600, label: "Australia"},
        {  y: 48750, label: "SA"}
        
        ]
      },
      {        
        type: "stackedColumn",
        toolTipContent: "{label}<br/><span style='\"'color: {color};'\"'><strong>{name}</strong></span>: {y}mn tonnes",
        name: "Method 1",
        showInLegend: "true",
        dataPoints: [
        {  y: 111338 , label: "USA"},
        {  y: 49088, label: "Russia" },
        {  y: 62200, label: "China" },
        {  y: 90085, label: "India" },
        {  y: 38600, label: "Australia"},
        {  y: 48750, label: "SA"}
        
        ]
      },  {        
        type: "stackedColumn",
        toolTipContent: "{label}<br/><span style='\"'color: {color};'\"'><strong>{name}</strong></span>: {y}mn tonnes",
        name: "Method 2",
        showInLegend: "true",
        dataPoints: [
        {  y: 135305 , label: "USA"},
        {  y: 107922, label: "Russia" },
        {  y: 52300, label: "China" },
        {  y: 3360, label: "India" },
        {  y: 39900, label: "Australia"},
        {  y: 0, label: "SA"}
        
        ]
      }            
      ]
      ,
      legend:{
        cursor:"pointer",
        itemclick: function(e) {
          if (typeof (e.dataSeries.visible) ===  "undefined" || e.dataSeries.visible) {
	          e.dataSeries.visible = false;
          }
          else
          {
            e.dataSeries.visible = true;
          }
          chart.render();
        }
      }
    });

    chart.render();
  }*/ ?>
  </script>