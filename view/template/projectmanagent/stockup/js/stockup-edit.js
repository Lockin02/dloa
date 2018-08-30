$(function(){
	// 产品清单
	$("#equInfo").yxeditgrid({
		objName : 'stockup[equ]',
		url : '?model=projectmanagent_stockup_equ&action=listJson',
		param : {
			'stockupId' : $("#stockupId").val()
		},
		tableClass : 'form_in_table',
		colModel : [{
			display : '物料编号',
			name : 'productCode',
			tclass : 'readOnlyTxtNormal',
			readonly : true
		}, {
			display : '物料Id',
			name : 'productId',
			type : 'hidden'
		}, {
			display : '物料名称',
			name : 'productName',
			tclass : 'readOnlyTxtNormal',
			readonly : true
		}, {
			display : '规格型号',
			name : 'productModel',
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		}, {
			display : '单位',
			name : 'unitName',
			tclass : 'readOnlyTxtItem',
			readonly : true
		}, {
			display : '数量',
			name : 'number',
			tclass : 'txtshort',
			validation : {
				required : true
			}
		}, {
			display : '期望到货日期',
			name : 'projArraDate',
			tclass : 'txtshort',
			type : 'date'
		}],
		isAddAndDel : false,
		isAddOneRow : false
	});
});


$(function(){

	/**
	 * 验证信息
	 */
	validate({
		"remark" : {
			required : true
		}
	});
});
