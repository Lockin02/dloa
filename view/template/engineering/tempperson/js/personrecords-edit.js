$(document).ready(function() {
	// ∂ØÃ¨»À¡¶‘§À„
	$("#personName").yxcombogrid_tempperson({
		hiddenId : 'personId',
		height : 250,
		isShowButton : false,
		gridOptions : {
			isTitle : true,
			isShowButton : true,
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data){
					$("#idCardNo").val(data.idCardNo);
				}
			}
		}
	});

	validate({
		"personName" : {
			required : true
		},
		"money_v" : {
			required : true
		},
		"thisDate" : {
			required : true
		}
	});
})