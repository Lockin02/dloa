$(function() {
	var projectId = $("#projectId").val();
	var parentName = $("#parentName").val();
	var budgetName = $("#budgetName").val();
	var budgetType = $("#budgetType").val();

	$("#esmbudgetGrid").yxeditgrid({
		url : '?model=engineering_budget_esmbudget&action=searhDetailJson',
		type : 'view',
		param : {
			"projectId" : projectId,
			"parentName" : parentName,
			"budgetName" : budgetName,
			"budgetType" : budgetType
		},
		//列信息
		colModel : [{
				display : 'id',
				name : 'id',
				type : 'hidden'
			}, {
				name : 'parentName',
				display : '费用大类',
				align : 'left',
				process : function(v,row){
					if(row.id == 'noId'){
						return v;
					}
					switch(row.budgetType){
						case 'budgetField' : return '<span class="blue">'+ v +'</span>';break;
						case 'budgetPerson' : return '<span class="green" >'+ v +'</span>';break;
						case 'budgetOutsourcing' : return '<span style="color:gray;">'+ v +'</span>';break;
						case 'budgetOther' : return '其他预算';break;
					}
				}
			}, {
				name : 'budgetName',
				display : '费用小类',
				align : 'left',
				width : 120
			}, {
				name : 'projectCode',
				display : '项目编号',
				type : 'hidden'
			}, {
				name : 'projectName',
				display : '项目名称',
				type : 'hidden'
			}, {
				name : 'price',
				display : '单价',
				process : function(v,row) {
                    if(row.customPrice == "1"){
                        return "<span class='blue' title='自定义价格'>" + moneyFormat2(v) + "</span>";
                    }else{
                        return moneyFormat2(v);
                    }
				},
				align : 'right',
				width : 80
			}, {
				name : 'numberOne',
				display : '数量1',
				align : 'right',
				width : 70
			}, {
				name : 'numberTwo',
				display : '数量2',
				align : 'right',
				width : 70
			}, {
				name : 'amount',
				display : '小计',
				align : 'right',
				process : function(v,row) {
					return moneyFormat2(v);
				},
				width : 80
			}, {
				name : 'budgetType',
				display : '费用属性',
				process : function(v){
					switch(v){
						case 'budgetField' : return '<span class="blue">现场预算</span>';break;
						case 'budgetPerson' : return '<span class="green">人力预算</span>';break;
						case 'budgetOutsourcing' : return '<span style="color:gray">外包预算</span>';break;
						case 'budgetOther' : return '其他预算';break;
					}
				},
				width : 80,
				type : 'hidden'
			}, {
				name : 'remark',
				display : '备注说明',
				align : 'left',
				width : 300
			}
		],
		toViewConfig : {
			formWidth : 900,
			formHeight : 400,
			showMenuFn : function(row) {
				if (row.id != "noId") {
					return true;
				}
				return false;
			},
			action : 'toView'
		},
		//过滤数据
		comboEx : [{
			text : '费用属性',
			key : 'budgetType',
			data : [{
				text : '现场预算',
				value : 'budgetField'
			}, {
				text : '人力预算',
				value : 'budgetPerson'
			}, {
				text : '外包预算',
				value : 'budgetOutsourcing'
			}, {
				text : '其他预算',
				value : 'budgetOther'
			}]
		}],
		searchitems : [{
				display : "费用小类",
				name : 'budgetNameSearch'
			}, {
				display : "费用大类",
				name : 'parentNameSearch'
			}
		],
		sortname : 'c.budgetType,c.parentName',
		sortorder : 'ASC'
	});
});