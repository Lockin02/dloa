$(document).ready(function() {

	$("#innerTable").yxeditgrid({
		url : '?model=produce_quality_quaprogramitem&action=listJson',
		objName : 'quaprogram[items]',
		param : { 'mainId' : $("#id").val() },
		title : '�ʼ췽����ϸ',
		tableClass : 'form_in_table',
		type : 'view',
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
			display : '��Ʊ��Ŀ',
			name : 'productId',
			type : 'hidden'
		}, {
			display : '������Ŀid',
			name : 'dimensionId',
			type : 'hidden'
		}, {
			display : '������Ŀ',
			name : 'dimensionName'
		}, {
			display : '��鷽ʽid',
			name : 'examTypeId',
			type : 'hidden'
		}, {
			display : '��鷽ʽ',
			name : 'examTypeName'
		}]
	});
});