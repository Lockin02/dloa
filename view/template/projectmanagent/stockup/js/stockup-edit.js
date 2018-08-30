$(function(){
	// ��Ʒ�嵥
	$("#equInfo").yxeditgrid({
		objName : 'stockup[equ]',
		url : '?model=projectmanagent_stockup_equ&action=listJson',
		param : {
			'stockupId' : $("#stockupId").val()
		},
		tableClass : 'form_in_table',
		colModel : [{
			display : '���ϱ��',
			name : 'productCode',
			tclass : 'readOnlyTxtNormal',
			readonly : true
		}, {
			display : '����Id',
			name : 'productId',
			type : 'hidden'
		}, {
			display : '��������',
			name : 'productName',
			tclass : 'readOnlyTxtNormal',
			readonly : true
		}, {
			display : '����ͺ�',
			name : 'productModel',
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		}, {
			display : '��λ',
			name : 'unitName',
			tclass : 'readOnlyTxtItem',
			readonly : true
		}, {
			display : '����',
			name : 'number',
			tclass : 'txtshort',
			validation : {
				required : true
			}
		}, {
			display : '������������',
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
	 * ��֤��Ϣ
	 */
	validate({
		"remark" : {
			required : true
		}
	});
});
