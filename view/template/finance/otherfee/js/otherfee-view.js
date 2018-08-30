$(document).ready(function() {
	validate({
		"debit_v" : {
			process : function(v){
				return moneyFormat2(v);
				}
		}
	
	});


})