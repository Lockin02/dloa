$(document).ready(function() {
	/**
	 * ��֤��Ϣ
	 */
	validate({
		"budgetName" : {
			required : true
		},
		"numberOne" : {
			required : false,
			custom : ['onlyNumber']
		},
		"numberTwo" : {
			required : false,
			custom : ['onlyNumber']
		}
	});

	// ��̬���Ԥ����Ŀ����
	$("#budgetName").yxcombogrid_budgetdl({
		hiddenId : 'budgetId',
		width : 600,
		isShowButton : false,
		gridOptions : {
			isShowButton : true,
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#parentId").val(data.parentId);
					$("#parentName").val(data.parentName);
				}
			}
		}
	});

});