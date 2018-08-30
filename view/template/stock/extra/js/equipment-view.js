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
			display : '���ϱ��',
			sortable : true
		}, {
			name : 'productName',
			display : '��������',
			sortable : true,
			tclass : 'txt'
		}, {
			name : 'pattern',
			display : '����ͺ�',
			sortable : true,
			tclass : 'readOnlyTxtItem',
			readonly : true
		}, {
			name : 'unitName',
			display : '��λ',
			sortable : true,
			tclass : 'readOnlyTxtItem',
			readonly : true
		} ]
	});
})