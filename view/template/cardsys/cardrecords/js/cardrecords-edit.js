$(document).ready(function() {
	//ʹ����
	$("#ownerName").yxselect_user({
		hiddenId : 'ownerId'
	});

	// ��̬����Ԥ��
	$("#cardNo").yxcombogrid_cardsinfo({
		hiddenId : 'cardId',
		height : 250,
		isShowButton : false,
		gridOptions : {
			isShowButton : true,
			showcheckbox : false
		}
	});


	validate({
		"cardNo" : {
			required : true
		},
		"ownerName" : {
			required : true
		},
		"useDate" : {
			required : true
		},
		"openMoney" : {
			required : true
		},
		"rechargerMoney" : {
			required : true
		},
		"useReson" : {
			required : true
		}
	});
})