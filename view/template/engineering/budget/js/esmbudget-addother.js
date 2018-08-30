$(document).ready(function() {

	//初始化预算
	initBudget();

	/**
	 * 验证信息(用到从表验证前，必须先使用validate)
	 */
	validate({

	});
});

//初始化预算
function initBudget(){
	var parentId = $("#parentId").val();
	var parentName = $("#parentName").val();

	$("#importTable").yxeditgrid({
		objName : 'esmbudget[budgets]',
		event : {
			'reloadData' : function(e){

			}
		},
		colModel : [{
			display : '上级id',
			name : 'parentId',
			type : 'hidden',
			value : parentId
		}, {
			display : '费用大类',
			name : 'parentName',
			readonly : true,
			tclass : 'readOnlyTxt',
			value : parentName
		}, {
			display : '预算项目Id',
			name : 'budgetId',
			type : 'hidden'
		}, {
			display : '预算小类',
			name : 'budgetName',
			validation : {
				required : true
			}
		}, {
			display : '单价',
			name : 'price',
			tclass : 'txtshort',
			type : 'money',
			event : {
				blur : function() {
					countAll($(this).data("rowNum"));
				}
			},
			width : 80
		}, {
			display : '数量1',
			name : 'numberOne',
			tclass : 'txtshort',
			type : 'int',
			event : {
				blur : function() {
					countAll($(this).data("rowNum"));
				}
			},
			width : 80
		}, {
			display : '数量2',
			name : 'numberTwo',
			tclass : 'txtshort',
			type : 'int',
			event : {
				blur : function() {
					countAll($(this).data("rowNum"));
				}
			},
			width : 80
		}, {
			display : '金额',
			name : 'amount',
			tclass : 'txtshort',
			type : 'money',
			width : 80
		}, {
			display : '备注说明',
			name : 'remark',
			tclass : 'txt'
		}]
	})
}