$(document).ready(function() {
	//��Ⱦ�ʼ��׼
	initQualitystandard("standardId",$("#standardIdHidden").val());

	//����׼
	var dimensionArr = getDimension();
	//��ⷽʽ
	var checkTypeArr = getCheckType();

	$("#innerTable").yxeditgrid({
		url : '?model=produce_quality_quaprogramitem&action=listJson',
		objName : 'quaprogram[items]',
		param : { 'mainId' : $("#id").val() },
		title : '�ʼ췽����ϸ',
		tableClass : 'form_in_table',
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
			name : 'dimensionName',
			validation : {
				required : true
			},
			type : 'select',
			options : dimensionArr,
			tclass : 'txt',
			emptyOption : true
//			process : function($input, rowData) {
//				var rowNum = $input.data("rowNum");
//				var g = $input.data("grid");
//				$input.yxcombogrid_qualitydimension({
//					hiddenId : 'innerTable_cmp_dimensionId' + rowNum,
//					width : 200,
//					gridOptions : {
//						event : {
//							row_dblclick : (function(rowNum) {
//
//							})(rowNum)
//						}
//					}
//				});
//			}
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
//			process : function($input, rowData) {
//				var rowNum = $input.data("rowNum");
//				var g = $input.data("grid");
//				$input.yxcombogrid_qualitychecktype({
//					hiddenId : 'innerTable_cmp_examTypeId' + rowNum,
//					width : 200,
//					gridOptions : {
//						event : {
//							row_dblclick : (function(rowNum) {
//
//							})(rowNum)
//						}
//					}
//				});
//			}
		}]
	});

	//����֤
	validate({
		"programName" : {
			required : true
		}
	});
});