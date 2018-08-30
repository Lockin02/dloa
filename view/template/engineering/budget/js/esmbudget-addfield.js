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
					initBudget(data.modelType);
				}
			}
		}
	});

	//初始化预算
	initBudget($("#templateId").val());

	/**
	 * 验证信息(用到从表验证前，必须先使用validate)
	 */
	validate({

	});
});

//初始化预算
function initBudget(templateId){
	var objGrid = $("#importTable");
	if(objGrid.children().length == 0){
		objGrid.yxeditgrid({
			objName : 'esmbudget[budgets]',
			url : '?model=finance_expense_customtemplate&action=getTemplateCostType',
			param : {
				'id' : templateId
			},
			event : {
				'reloadData' : function(e){
					initBudgetIds();
				},
				'removeRow' : function(e,rowNum){
					cancelCheck(rowNum);
				}
			},
			isAdd : false,
			colModel : [{
				display : '复制',
				type : 'statictext',
				width : 30,
				html : '<img src="images/icon/icon105.gif"/>',
				event : {
					'click' : function(e) {
						var rowNum = $(this).data("rowNum");
						//调用复制方法
						copyBudget(rowNum);
					}
				}
			}, {
				display : '预算项目Id',
				name : 'budgetId',
				type : 'hidden'
			}, {
				display : '上级id',
				name : 'parentId',
				type : 'hidden'
			}, {
				display : '费用大类',
				name : 'parentName',
				readonly : true,
				tclass : 'readOnlyTxt'
			}, {
				display : '预算小类',
				name : 'budgetName',
				tclass : 'readOnlyTxt',
				readonly : true
			}, {
				display : '单价',
				name : 'price',
				tclass : 'txtshort',
				type : 'money',
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
				type : 'money'
			}, {
				display : '备注说明',
				name : 'remark',
				tclass : 'txt'
			}]
		})
	}else{
		//如果已经存在可编辑表格，直接渲染
		objGrid.yxeditgrid("setParam",{
			'id' : templateId
		}).yxeditgrid("processData");
	}
}