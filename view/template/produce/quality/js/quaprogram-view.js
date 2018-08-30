$(document).ready(function() {

	$("#innerTable").yxeditgrid({
		url : '?model=produce_quality_quaprogramitem&action=listJson',
		objName : 'quaprogram[items]',
		param : { 'mainId' : $("#id").val() },
		title : '质检方案明细',
		tableClass : 'form_in_table',
		type : 'view',
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
			display : '发票名目',
			name : 'productId',
			type : 'hidden'
		}, {
			display : '检验项目id',
			name : 'dimensionId',
			type : 'hidden'
		}, {
			display : '检验项目',
			name : 'dimensionName'
		}, {
			display : '检查方式id',
			name : 'examTypeId',
			type : 'hidden'
		}, {
			display : '检查方式',
			name : 'examTypeName'
		}]
	});
});