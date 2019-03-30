<?php 
/*
* Project Name  : Monocle
* Company		: Wave Code Logix
* Author 		: Karthik K
* Created date  : 01 Dec, 2014
* Modified date : 01 Dec, 2014
* Description 	: Page contains display added users list
*/  ?>
<div class="nav_tab_content">
	<div class='row row_content_header'>
		<div class='large-12 columns'>
			<div class='fright'>
				<a href='<?php echo base_url('viewusers'); ?>' class='BtnAddUser BtnGraySmall' id='BtnAddUser'>
					<?php echo GetLangLabel('BtnBackToUserList'); ?>
				</a>
			</div>
		</div>
	</div>
	<?php 
	if(isset($AccessLog) && count($AccessLog)>0)
	{?>
		<div class='row'>
			<div class='large-8 columns large-centered'>
				<div class='DynamicRowContent'>
					<div class='tab_row_container'>
						<div class='row nav_row_head'>
							<div class='large-1 columns row_field small-1'>S.No</div>
							<div class='large-6 columns row_field small-6'><?php echo GetLangLabel('TxtTitleLastLogin'); ?></div>
							<div class='large-5 columns row_field small-5'><?php echo GetLangLabel('TxtTitleLocation'); ?></div>
						</div>
						<div class='DisplayAllContentList'><?php 
						$LogCount = 0;
						foreach($AccessLog as $DisplayLog)
						{
							$LogCount++; ?>
							<div class='row nav_row_content'>
								<div class='large-1 columns row_field small-1'><?php echo $LogCount.'. '; ?></div>
								<div class='large-6 columns row_field small-6'>
									<?php echo date('M d, Y',$DisplayLog->logged_on).' at '.date('h:m a',$DisplayLog->logged_on); ?>
								</div>
								<div class='large-5 columns row_field small-5'>
									<?php echo $DisplayLog->ip_address; ?>
								</div>
							</div><?php
						}?>
						</div>
					</div>
				</div>
			</div>
		</div><?php 
	}	
	else
	{?>
		<div class='row nav_row_content'>
			<div class='large-12 columns NoRecordsMsg'>
				<?php echo GetLangLabel('MsgNoAccessLogFound'); ?>
			</div>
		</div>
		<?php
	}?>
</div>