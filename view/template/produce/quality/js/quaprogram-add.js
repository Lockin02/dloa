$(document).ready(function() {

	//��Ⱦ�ʼ��׼
	initQualitystandard("standardId");

	//����׼
	var dimensionArr = getDimension();
	//��ⷽʽ
	var checkTypeArr = getCheckType();

	$("#innerTable").yxeditgrid({
		objName : 'quaprogram[items]',
		title : '�ʼ췽����ϸ',
		tableClass : 'form_in_table',
		colModel : [{
			display : '��Ʊ��Ŀ',
			name : 'productId',
			type : 'hidden'
		}, {
			display : '������Ŀid',
			name : 'dimensionId',
			type : 'hidden'
		}, {
			display : '������Ŀ',
			name : 'dimensionName',
			validation : {
				required : true
			},
			type : 'select',
			options : dimensionArr,
			tclass : 'txt',
			emptyOption : true
		}, {
			display : '��鷽ʽid',
			name : 'examTypeId',
			type : 'hidden'
		}, {
			display : '��鷽ʽ',
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

	//����֤
	validate({
		"programName" : {
			required : true
		}
	});
});