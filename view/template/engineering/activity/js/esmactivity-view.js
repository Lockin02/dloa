$(document).ready(function() {
	//任务成员渲染
	$("#activityMembers").yxeditgrid({
		objName : 'esmEditArr',
		url : '?model=engineering_activity_esmactmember&action=listJson',
		type : 'view',
		isAdd : false,
		tableClass : 'form_in_table',
		param : {
			'activityId' : $("#id").val()
		},
		colModel : [{
			display : '姓名',
			name : 'memberName',
			width : 200
		}, {
			display : '人员等级',
			name : 'personLevel',
			width : 130
		}, {
			display : '角色',
			name : 'roleName',
			width : 130
		}, {
			display : '实际开始',
			name : 'actBeginDate',
			width : 120
		}, {
			display : '实际结束',
			name : 'actEndDate',
			width : 120
		}, {
			display : '天数',
			name : 'personDays'
		}, {
			display : '人力成本',
			name : 'personCostDays',
			type : 'hidden'
		}, {
			display : '人力成本金额',
			name : 'personCost',
			process : function(v){
				return moneyFormat2(v);
			},
			type : 'hidden'
		}]
	});

	//任务人力预算
	$("#activityPersons").yxeditgrid({
		objName : 'esmEditArr',
		url : '?model=engineering_person_esmperson&action=taskListJson',
		type : 'view',
		isAdd : false,
		tableClass : 'form_in_table',
		param : {
			'activityId' : $("#id").val()
		},
		colModel : [{
			display : '人员等级',
			name : 'personLevel',
			width : 120
		}, {
			display : '预计开始',
			name : 'planBeginDate',
			width : 120
		}, {
			display : '预计结束',
			name : 'planEndDate',
			width : 120
		}, {
			display : '天数',
			name : 'days'
		}, {
			display : '数量',
			name : 'number'
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
			process : function(v){
				return moneyFormat2(v);
			}
		}]
	});

	//费用预算渲染
	$("#activityBudgets").yxeditgrid({
		objName : 'esmbudget[budgets]',
		url : '?model=engineering_budget_esmbudget&action=listJson',
		param : {
			'activityId' : $("#id").val(),
			'projectId' : $("#projectId").val()
		},
		tableClass : 'form_in_table',
		type : 'view',
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
			name : 'parentName'
		}, {
			display : '预算项目',
			name : 'budgetName'
		}, {
			display : '单价',
			name : 'price',
			tclass : 'txtshort',
			type : 'money',
			process : function(v){
				return moneyFormat2(v);
			}
		}, {
			display : '数量1',
			name : 'numberOne',
			tclass : 'txtshort'
		}, {
			display : '单位1',
			name : 'unit',
			tclass : 'txtshort',
			type : 'hidden'
		}, {
			display : '数量2',
			name : 'numberTwo',
			tclass : 'txtshort'
		}, {
			display : '单位2',
			name : 'unitTwo',
			tclass : 'txtshort',
			type : 'hidden'
		}, {
			display : '金额',
			name : 'amount',
			tclass : 'txtshort',
			type : 'money',
			process : function(v){
				return moneyFormat2(v);
			}
		}, {
			display : '备注说明',
			name : 'remark',
			width : 220
		}]
	});

	//费用明细表头
	var feeColModel = [{
		display : 'createId',
		name : 'createId',
		type : 'hidden'
	},{
		display : '录入人员',
		name : 'createName',
		width : 120
	}];
	var feeTitle = "";
	var tempJson = "";
	//获取费用项
	$.ajax({
	    type: "POST",
	    url: "?model=engineering_cost_esmcostdetail&action=getFeeTitle",
	    data: {
	    	'activityId' : $("#id").val(),
			'projectId' : $("#projectId").val()
		},
	    async: false,
	    success: function(data){
	    	if(data){
	   			feeTitle = eval("(" + data + ")");
	   			for(var i = 0;i < feeTitle.length ; i++){
	   				tempJson = {
						display : feeTitle[i].costType,
						name :  feeTitle[i].costTypeId,
						process : function(v){
							if(v == "" || v == undefined){
								return '0.00';
							}
							return moneyFormat2(v);
						}
	   				};
					feeColModel.push(tempJson);
	   			}
	    	}
		}
	});
	if(feeTitle){
		//费用明细
		$("#activityFees").yxeditgrid({
			url : '?model=engineering_cost_esmcostdetail&action=feeListJson',
			param : {
				'activityId' : $("#id").val(),
				'projectId' : $("#projectId").val()
			},
			tableClass : 'form_in_table',
			type : 'view',
			colModel : feeColModel
		});
	}else{
		$("#activityFees").html("没有相关费用信息");
	}
});
