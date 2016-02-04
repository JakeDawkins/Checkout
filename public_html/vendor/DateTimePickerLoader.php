

<!-- imports for datetime -->
<link rel="stylesheet" type="text/css" href="<?php echo dirname(__FILE__); ?>/DateTimePicker/dist/DateTimePicker.css" />
<script type="text/javascript" src="<?php echo dirname(__FILE__); ?>/DateTimePicker/dist/DateTimePicker.js"></script>
	
<!--[if lt IE 9]>
	<link rel="stylesheet" type="text/css" href="<?php echo dirname(__FILE__); ?>/DateTimePicker/dist/DateTimePicker-ltie9.css" />
	<script type="text/javascript" src="<?php echo dirname(__FILE__); ?>/DateTimePicker/dist/DateTimePicker-ltie9.js"></script>
<![endif]-->
 
<!-- For i18n Support -->
<script type="text/javascript" src="<?php echo dirname(__FILE__); ?>/DateTimePicker/dist/i18n/DateTimePicker-i18n.js"></script>

<script>
	$(document).ready(function(){

		//set up dateTime box
		$("#dtBox").DateTimePicker({
			isPopup: true,
			animationDuration: 200
		});

		//block form submit while dateTime entry box is open
		$(document).on("keypress",'form',function (e) {
			var code = e.keyCode || e.which;
			if(code == 13 && $("#dtBox").css('display') == 'block'){
				e.preventDefault();
				//instead of submiting, click "set"
				//clickSet(); //TODO -- does not submit if not clicked a field in dtBox
				return false;
			}
		});

		function clickSet(){
			$(".dtpicker-buttonSet").click();
		}

	});
</script>