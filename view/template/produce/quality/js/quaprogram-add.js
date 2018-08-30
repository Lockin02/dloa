$(document).ready(function() {

	//渲染质检标准
	initQualitystandard("standardId");

	//检测标准
	var dimensionArr = getDimension();
	//检测方式
	var checkTypeArr = getCheckType();

	$("#innerTable").yxeditgrid({
		objName : 'quaprogram[items]',
		title : '质检方案明细',
		tableClass : 'form_in_table',
		colModel : [{
			display : '发票名目',
			name : 'productId',
			type : 'hidden'
		}, {
			display : '检验项目id',
			name : 'dimensionId',
			type : 'hidden'
		}, {
			display : '检验项目',
			name : 'dimensionName',
			validation : {
				required : true
			},
			type : 'select',
			options : dimensionArr,
			tclass : 'txt',
			emptyOption : true
		}, {
			display : '检查方式id',
			name : 'examTypeId',
			type : 'hidden'
		}, {
			display : '检查方式',
			name : 'examTypeName',
			validation : {
				required : true
			},
			type : 'select',
			options : checkTypeArr,
			tclass : 'txt',
			emptyOption : true
		}]
	});

	//表单验证
	validate({
		"programName" : {
			required : true
		}
	});
});