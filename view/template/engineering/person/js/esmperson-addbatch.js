$(document).ready(function() {
	$("#importTable").yxeditgrid({
		objName : 'esmperson[person]',
		colModel : [{
			display : '人员级别id',
			name : 'personLevelId',
			type : 'hidden'
		}, {
			display : '人员等级',
			name : 'personLevel',
			validation : {
				required : true
			},
			readonly : true,
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_eperson({
					hiddenId : 'importTable_cmp_personLevelId' + rowNum,
					nameCol : 'personLevel',
					width : 600,
					gridOptions : {
						showcheckbox : false,
					isTitle : true,
						event : {
							row_dblclick : (function(rowNum) {
								return function(e, row, rowData) {
									g.getCmpByRowAndCol(rowNum,'coefficient').val(rowData.coefficient);
//									g.getCmpByRowAndCol(rowNum,'price').val(rowData.price);
									setMoney("importTable_cmp_price" +  rowNum,rowData.price,2);
								}
							})(rowNum)
						}
					}
				});
			}
		}, {
			display : '需求开始日期',
			name : 'planBeginDate',
			type : 'date',
			tclass : 'txtmiddle Wdate',
			event : {
				blur : function() {
					var rowNum = $(this).data("rowNum");
					var g = $(this).data("grid");
					var planBeginDate = $(this).val();
					var planEndDate = g.getCmpByRowAndCol(rowNum,'planEndDate').val();
					if(planBeginDate != "" && planEndDate != ""){
						var days = DateDiff(planBeginDate,planEndDate) + 1 ;
						g.getCmpByRowAndCol(rowNum,'days').val(days);
						calPersonBatch(rowNum);
					}
				}
			},
			value : $("#planBeginDate").val()
		}, {
			display : '需求结束日期',
			name : 'planEndDate',
			type : 'date',
			tclass : 'txtmiddle Wdate',
			event : {
				blur : function() {
					var rowNum = $(this).data("rowNum");
					var g = $(this).data("grid");
					var planEndDate = $(this).val();
					var planBeginDate = g.getCmpByRowAndCol(rowNum,'planBeginDate').val();
					if(planBeginDate != "" && planEndDate != ""){
						var days = DateDiff(planBeginDate,planEndDate) + 1 ;
						g.getCmpByRowAndCol(rowNum,'days').val(days);
						calPersonBatch(rowNum);
					}
				}
			},
			value : $("#planEndDate").val()
		}, {
			display : '天数',
			name : 'days',
			readonly : true,
			tclass : 'readOnlyTxtMiddle',
			validation : {
				required : true
			},
			tclass : 'readOnlyTxtShort',
			readonly : true,
			value : $("#days").val()
		}, {
			display : '数量',
			name : 'number',
			tclass : 'txtshort',
			event : {
				blur : function() {
					calPersonBatch($(this).data("rowNum"));
				}
			}
		}, {
			display : '单价',
			name : 'price',
			tclass : 'txtshort',
			type : 'hidden'
		}, {
			display : '计量系数',
			name : 'coefficient',
			tclass : 'readOnlyTxtShort',
			readonly : true,
			type : 'hidden'
		}, {
			display : '人工天数',
			name : 'personDays',
			tclass : 'txtshort'
		}, {
			display : '人力成本',
			name : 'personCostDays',
			tclass : 'txtshort'
		}, {
			display : '人力成本金额',
			name : 'personCost',
			tclass : 'txtshort',
			type : 'money'
		}, {
			display : '备注说明',
			name : 'remark'
		}]
	})

	/**
	 * 验证信息(用到从表验证前，必须先使用validate)
	 */
	validate({

	});
});
