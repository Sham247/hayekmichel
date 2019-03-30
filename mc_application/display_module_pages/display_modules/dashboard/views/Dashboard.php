<?php 
/*
* Project Name  : Monocle
* Company		: Wave Code Logix
* Author 		: Karthik K
* Created date  : 15 Oct, 2014
* Modified date : 15 Oct, 2014
* Description 	: Page contains foundation framework home page
*/ 
$GetReportLink      = $this->config->item("reports_link");
$ExcelReportLink    = $this->config->item('excel_reports_link');
$this->load->view('common/FoundationHeader');  ?>
<!-- Main Content -->
<div class="main-wrapper" id='display_dashboard'>
    <div class="row main-content">
    	<div class="main-wrapper"><?php 
        if($GetReportLink != '' || $ExcelReportLink != '')
        {?>
            <div style="margin-top: 20px; float: right;"><?php
            if($GetReportLink != '')
            {?>
                <a href='<?php echo $GetReportLink; ?>'>Go to Table Reports</a><?php 
            }
            if($ExcelReportLink != '')
            {?>
                <a style='margin:0 0px 0 50px' href='<?php echo $ExcelReportLink; ?>' target='_blank'>View Excel Reports</a><?php 
            }?>
            </div><?php 
        }?>
            <div data-ng-controller="AppCtrl">
                <div data-ng-hide="isSpecificPage()" data-ng-cloak=""></div> 
                <div class="view-container">
                    <section data-ng-view="" id="content" class="animate-fade-up">
                        <div class='dashboard_content'>
                            <img id='img-spinner' src='<?php echo ImageUrl("loading_spinner.gif"); ?>' alt='Loading'/>
                        </div>
                    </section>
                </div>
            </div>
            <!-- Start Main Slider -->
        </div>
    </div><!-- row main content end -->
</div><?php
$this->load->view('common/FoundationFooter'); ?>