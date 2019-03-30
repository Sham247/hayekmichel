<?php 
/*
* Project Name  : Monocle
* Company		: Wave Code Logix
* Author 		: Karthik K
* Created date  : 22 Dec, 2014
* Modified date : 22 Dec, 2014
* Description 	: Page contains display reports list
*/ 
$EnablePage	= "DisplayReports";
if(isset($DisplayPage) && $DisplayPage != '')
{
	$EnablePage = $DisplayPage;
}
$this->load->view('common/FoundationHeader');  ?>
<!-- Main Content -->
<div class="main-wrapper">
   	<div class="row main-content display_page_content"><?php 
   		$this->load->view('common/DisplayStatusMsg'); ?>
   		<div class='large-3 columns'>
            <h4>Reports</h4>
            <ul class='ReportCategoryUl'><?php 
            $CategoryCount = 0;
            if(isset($CategoryList) && count($CategoryList)>0)
            {
               $FindCategory  = '';
               foreach($CategoryList as $CategoryVal)
               {
                  if($CategoryVal->CategoryId != $FindCategory)
                  {
                     $FindCategory     = $CategoryVal->CategoryId; 
                     $SubCategoryList  = '';
                     $AddActiveClass = $InnerActiveClass = "";
                     if($CategoryCount == 0)
                     {
                        $AddActiveClass   = "ActiveLink";
                     }
                     $InnerLoop = 0;
                     foreach($CategoryList as $SubCategoryVal)
                     {
                        if($InnerLoop == 0 && $CategoryCount == 0)
                        {
                           $InnerActiveClass   = "ActiveLink";
                        }
                        else
                        {
                           $InnerActiveClass   = "";
                        }
                        if($CategoryVal->CategoryId == $SubCategoryVal->CategoryId)
                        {
                           $SubCategoryList .= "<li>
                              <a href='javascript:;' class='SubCategoryLink ".$InnerActiveClass."' sub-category-id='".EncodeValue($SubCategoryVal->SubCategoryId)."'>".$SubCategoryVal->SubCategoryName."</a>
                           </li>";
                           $InnerLoop++;
                        }
                     }?>
                     <li>
                        <a href='javascript:;' class='CategoryLink <?php echo $AddActiveClass; ?>' category-id='<?php echo EncodeValue($CategoryVal->CategoryId); ?>'><?php echo $CategoryVal->CategoryName ?></a><?php 
                        if($SubCategoryList != '')
                        {
                           echo "<ul class='ReportSubCategoryUl' id='ReportSubCategoryUl".EncodeValue($CategoryVal->CategoryId)."'>".$SubCategoryList."</ul>";
                        }?>
                     </li><?php
                  }
                  $CategoryCount++;
               }
            }?>
            </ul>
   		</div>
   		<div class='large-9 columns DisplayReportsContent'>
   			<?php $this->load->view($EnablePage); ?>
   		</div>
	</div><!-- row main content end -->
</div><?php
$this->load->view('common/FoundationFooter'); ?>