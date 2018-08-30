$(document).ready(function() {
	//渲染质检标准
	initQualitystandard("standardId",$("#standardIdHidden").val());

	//检测标准
	var dimensionArr = getDimension();
	//检测方式
	var checkTypeArr = getCheckType();

	$("#innerTable").yxeditgrid({
		url : '?model=produce_quality_quaprogramitem&action=listJson',
		objName : 'quaprogram[items]',
		param : { 'mainId' : $("#id").val() },
		title : '质检方案明细',
		tableClass : 'form_in_table',
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

	//表单验证
	validate({
		"programName" : {
			required : true
		}
	});
});