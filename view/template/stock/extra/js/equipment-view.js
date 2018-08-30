$(document).ready(function() {

	$("#itemTable").yxeditgrid({
		objName : 'equipment[items]',
		url : '?model=stock_extra_equipmentpro&action=pageItemJson',
		type : 'view',
		param : {
			mainId : $("#id").val()
		},
		colModel : [ {
			name : 'productCode',
			display : '物料编号',
			sortable : true
		}, {
			name : 'productName',
			display : '物料名称',
			sortable : true,
			tclass : 'txt'
		}, {
			name : 'pattern',
			display : '规格型号',
			sortable : true,
			tclass : 'readOnlyTxtItem',
			readonly : true
		}, {
			name : 'unitName',
			display : '单位',
			sortable : true,
			tclass : 'readOnlyTxtItem',
			readonly : true
		} ]
	});
})