$(document).ready(function() {

});

//直接提交
function toSubmit(type){
	if(checkData() == 'pass'){
		switch(type){
			case 'sub':
				document.getElementById('form1').action="?model=outsourcing_vehicle_register&action=add&isSub=1";
				$('#form1').submit();
				break;
			default:
				document.getElementById('form1').action="?model=outsourcing_vehicle_register&action=add";
				$('#form1').submit();
				break;
		}
	}
}