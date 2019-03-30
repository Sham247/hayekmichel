<?php 
/*
* Project Name  : Monocle
* Company		: Wave Code Logix
* Author 		: Karthik K
* Created date  : 13 Oct, 2014
* Modified date : 13 Oct, 2014
* Description 	: Page contains foundation HTML framework footer section
*/
			if(isset($RemoveAboutUs) == false)
			{?>
				<footer class="footer_wrapper">
					<div class="row footer-part">
						<div class="large-12 columns">
							<div class="row">
								<div class="large-8 columns">
									<h4 class="footer-title">About Us</h4>
									<div class="divdott"></div>
									<div class="large-3 columns small_center">
										<img class="botlogo" src="<?php echo ImageUrl('monocle_logo_footer.png'); ?>" alt="" />
									</div>
									<div class="large-9 columns" style="float:left">
										<div class="footer_part_content">
											<p>Monocle Health Data is the only company ranking doctors and hospitals on both efficiency and quality for both chronic illnesses and episodic procedures using data from multiple credible data sources.  Monocle’s unique Intel Innovation Award winning method matches patients with specific needs to providers demonstrating superior performance in quality and efficiency.</p>
										</div>
									</div>
								</div>	
								<div class="large-4 columns small_center">
									<h4 class="footer-title">Contact info</h4>
									<div class="divdott"></div>
									<div class="footer_part_content">
										<ul class="about-info">
											<li><i class="icon-envelope"></i><a href="mailto:info@monocle.com">info@monocle.com</a></li>
										</ul>
										<ul class="social-icons">
										<li><a href="#"><i class="icon-pinterest"></i></a></li>
										<li><a href="#"><i class="icon-twitter"></i></a></li>
										<li><a href="#"><i class="icon-facebook"></i></a></li>
									</ul>
									</div>
								</div>
							</div>
						</div>
					</div><?php 
			}
			else
			{
				echo "<footer>";

			} ?>
			<div class="privacy footer_bottom">
				<div class="footer-part">
					<div class="row">
						<div class="large-10 columns copyright">
							<p >&copy; 2016 Monocle Health Data LLC, All Rights Reserved.</p>
						</div><?php 
						if(isset($RemoveGoToTop) == false)
						{?>
							<div class="large-2 columns">
								<div id="back-to-top"><a href="javascript:;"></a></div>
							</div><?php 
						}?>
					</div>
				</div>
			</div>
		</footer>
		<script src="<?php echo JsUrl('foundation/foundation.min.js'); ?>"></script>
		<script src="<?php echo JsUrl('foundation/modernizr.js'); ?>"></script>
		<script type="text/javascript">
		$(document).foundation();
		$(function()
		{
			$.fn.McSimpleTooltip({FieldObj:'.McToolTip'});
		});
		</script>
		<?php 
		if(isset($ScriptPage) && $ScriptPage != '')
		{
			$this->load->view($ScriptPage);
		} ?>
	</body>
</html>