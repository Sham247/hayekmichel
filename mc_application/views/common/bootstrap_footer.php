<?php 
/*
* Project Name  : Monocle
* Company		: Wave Code Logix
* Author 		: Karthik K
* Created date  : 13 Oct, 2014
* Modified date : 13 Oct, 2014
* Description 	: Page contains bootstrap HTML framework footer section
*/ ?>
			</div>
		</div>
	</div>
	<footer class="container-fluid footer_bottom">
		<div class="row">
			<div class="span2"></div>
			<div class="span10 privacy">
				<div class="footer-part">
					<div class="row">
						<div class="large-10 columns copyright">
							<p>© 2014 Monocle Health Data LLC, All Rights Reserved.</p>
						</div>
						<div class="large-2 columns">
							<div id="back-to-top"><a href="#top"></a></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</footer>
	<script src="<?php echo JsUrl('jquery.js'); ?>"></script>
	<script type="text/javascript">
	$(function()
	{
		if($('.alert').length >0)
	    {
	        if($('.alert.no-hide').length == 0)
	        {
	            $('.alert-success,.alert-danger,.alert-error').delay(5000).slideUp(function()
	            {
	                $(this).remove();
	            });
	        }
	    }
	    /* forgotten password modal */
$('#forgot-form').bind('shown', function () {
	"use strict";
    $('#usernamemail').focus();
});

$('#forgot-form').bind('hidden', function () {
	"use strict";
    $('#username').focus();
});
$('#forgotsubmit').click(function(){
	if ( $(this).text() != 'Done') {
		$('#forgotform').submit();
	}
})

$('#forgotform').submit(function (e) {
	"use strict";

	e.preventDefault();
	$('#forgotsubmit').button('loading');

	var post = $('#forgotform').serialize();
	var action = $('#forgotform').attr('action');

	$("#message").slideUp(350, function () {

		$('#message').hide();

		$.post(action, post, function (data) {
			$('#message').html(data);
			document.getElementById('message').innerHTML = data;
			$('#message').slideDown('slow');
			$('#usernamemail').focus();
			if (data.match('success') !== null) {
				$('#forgotform').slideUp('slow');
				$('#forgotsubmit').button('complete');
				$('#forgotsubmit').click(function (eb) {
					eb.preventDefault();
					$('#forgot-form').modal('hide');
				});
			} else {
				$('#forgotsubmit').button('reset');
			}
		});
	});
});
	});
	</script>
	<script src="<?php echo JsUrl('bootstrap/bootstrap-modal.js'); ?>"></script>
	<script src="<?php echo JsUrl('bootstrap/bootstrap-button.js'); ?>"></script>
  </body>
</html>        