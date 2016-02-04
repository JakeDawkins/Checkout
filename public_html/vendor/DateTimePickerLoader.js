$(document).ready(function(){

	//set up dateTime box
	$("#dtBox").DateTimePicker({
		isPopup: true,
		animationDuration: 200,
		minuteInterval: 5
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