$(document).ready(function() {
	// 模板选择渲染
	$("#templateName").yxcombogrid_expensemodel({
		hiddenId :  'templateId',
		isFocusoutCheck : false,
		height : 300,
		isShowButton : true,
		isClear : false,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data){
					initTemplate(data.modelType);
				}
			}
		}
	});

	$("#importTable").yxeditgrid({
		objName : 'esmbudget[budgets]',
		url : '?model=engineering_budget_esmbudget&action=listJson',
		param : {
			'activityId' : $("#activityId").val(),
			'projectId' : $("#projectId").val()
		},
		event : {
			'reloadData' : function(e){
				//缓存表格对象
				var thisGrid = $("#importTable");
				var colObj = thisGrid.yxeditgrid("getCmpByCol", "budgetName");
				if(colObj.length == 0){
//					alert(1);
				}
			}
		},
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
			display : '预算项目Id',
			name : 'budgetId',
			type : 'hidden'
		}, {
			display : '上级id',
			name : 'parentId',
			type : 'hidden'
		}, {
			display : '费用分类',
			name : 'parentName',
			readonly : true,
			tclass : 'readOnlyTxt'
		}, {
			display : '预算项目',
			name : 'budgetName',
			validation : {
				required : true
			},
			readonly : true,
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_budgetdl({
					hiddenId : 'importTable_cmp_budgetId' + rowNum,
					nameCol : 'budgetName',
					width : 600,
					height : 300,
					gridOptions : {
						showcheckbox : false,
						event : {
							row_dblclick : (function(rowNum) {
								return function(e, row, rowData) {
									g.getCmpByRowAndCol(rowNum,'parentId').val(rowData.parentId);
									g.getCmpByRowAndCol(rowNum,'parentName').val(rowData.parentName);
								}
							})(rowNum)
						}
					}
				});
			}
		}, {
			display : '单价',
			name : 'price',
			tclass : 'txtshort',
			type : 'money',
			validation : {
				required : true
			},
			event : {
				blur : function() {
					countAll($(this).data("rowNum"));
				}
			}
		}, {
			display : '数量1',
			name : 'numberOne',
			tclass : 'txtshort',
			type : 'int',
			validation : {
				required : true,
				custom : ['onlyNumber']
			},
			event : {
				blur : function() {
					countAll($(this).data("rowNum"));
				}
			}
		}, {
			display : '数量2',
			name : 'numberTwo',
			tclass : 'txtshort',
			type : 'int',
			event : {
				blur : function() {
					countAll($(this).data("rowNum"));
				}
			}
		}, {
			display : '金额',
			name : 'amount',
			tclass : 'txtshort',
			type : 'money',
			validation : {
				required : true
			}
		}, {
			display : '备注说明',
			name : 'remark',
			tclass : 'txt'
		}, {
			display : '项目id',
			name : 'projectId',
			type : 'hidden',
			value : $("#projectId").val()
		}, {
			display : '项目编号',
			name : 'projectCode',
			type : 'hidden',
			value : $("#projectCode").val()
		}, {
			display : '项目名称',
			name : 'projectName',
			type : 'hidden',
			value : $("#projectName").val()
		}, {
			display : '任务id',
			name : 'activityId',
			type : 'hidden',
			value : $("#activityId").val()
		}, {
			display : '任务名称',
			name : 'activityName',
			type : 'hidden',
			value : $("#activityName").val()
		}]
	})

	/**
	 * 验证信息(用到从表验证前，必须先使用validate)
	 */
	validate({

	});
});
