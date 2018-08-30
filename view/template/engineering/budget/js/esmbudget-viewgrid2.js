$(function() {
	var projectId = $("#projectId").val();

	$("#esmbudgetGrid").yxeditgrid({
		url : '?model=engineering_budget_esmbudget&action=searhJson',
		type : 'view',
		param : {
			"projectId" : projectId
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
				width : 200,
				process : function(v,row){
					if(row.id == 'noId' && row.budgetType != 'budgetPerson' && row.budgetType!= 'budgetField' && row.budgetType!= 'budgetTrial'){
						return v;
					}
					switch(row.budgetType){
						case 'budgetField' : return '<span class="blue">'+ v +'</span>';break;
						case 'budgetPerson' : return '<span class="green">'+ v +'</span>';break;
						case 'budgetOutsourcing' : return '<span style="color:gray;">'+ v +'</span>';break;
						case 'budgetOther' : return '其他预算';break;
						case 'budgetDevice' : return '<span style="color:brown;">'+ v +'</span>';break;
						case 'budgetTrial' : return '<span style="color:orange;">'+ v +'</span>';break; 
					}
				}
			}, {
				name : 'budgetName',
				display : '费用小类',
				align : 'left',
				width : 200,
				process : function(v,row){
					if(row.id == 'noId') return v;
					else if(row.budgetType == 'budgetPerson'){
						return "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=engineering_budget_esmbudget" +
						"&action=toSearchDetailList&parentName=" + row.parentName  + "&projectId="+ projectId + "&budgetType=" + row.budgetType +"&budgetName="  +
						"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=900\")'>"+ v + "<span style='color:green'>"+row.detCount + "</span></a>";
					}
					else if(row.budgetType == 'budgetDevice'){
						return v;
					}
					else{
						if(row.isImport == 1){//由项目费用维护导入的数据
							return "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=engineering_cost_esmcostmaintain" +
							"&action=toSearchDetailList&parentName=" + row.parentName+ "&budgetName=" + row.budgetName  + "&projectId="+ projectId +
							"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=900\")'>" + v + "<span style='color:green'>"+row.detCount + "</span></a>";
						}else{
							return "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=engineering_budget_esmbudget" +
							"&action=toSearchDetailList&parentName=" + row.parentName+ "&budgetName=" + row.budgetName  + "&projectId="+ projectId + "&budgetType=" + row.budgetType+
							"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=900\")'>" + v + "<span style='color:green'>"+row.detCount + "</span></a>";	
						}
					}
				}
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
					return moneyFormat2(v);
				},
				width : 80,
				type : 'hidden'
			}, {
				name : 'numberOne',
				display : '数量1',
				width : 70,
				type : 'hidden'
			}, {
				name : 'numberTwo',
				display : '数量2',
				width : 70,
				type : 'hidden'
			}, {
				name : 'amount',
				display : '预算',
				align : 'right',
				process : function(v,row) {
					if(row.isImport == 1 && row.status == 0){//导入的数据,未审核的数据标红
						return "<span class='red'>" + moneyFormat2(v) + "</span>" ;
					}else{
                        return moneyFormat2(v);
                    }
				},
				width : 80
			}, {
				name : 'actFee',
				display : '决算',
				align : 'right',
				process : function(v,row) {
					return moneyFormat2(v);
				},
				width : 80
			}, {
				name : 'actFeeWait',
				display : '待审核决算',
				align : 'right',
				process : function(v,row) {
					if(row.isImport == 1){//导入的数据
						if(row.status == 0){//未审核的数据标红
							return "<span class='red'>" + moneyFormat2(v) + "</span>" ;
						}else{
							return moneyFormat2(v);
						}
					}
				},
				width : 80
			}, {
				name : 'feeProcess',
				display : '费用进度',
				align : 'right',
				process : function(v,row) {
					if(v){
						if(v * 1> 100){
							return "<span class='red'>" + v + " %</span>" ;
						}else{
							return v + " %" ;
						}
					}
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
				process : function(v,row) {
					if(row.isImport == 1){//后台导入的数据,添加标红备注提醒
						if(row.actFee == undefined){//变更页面显示
							return "<span class='red'>后台导入数据</span>";
						}else{
							if(row.status == 0){
								return "<span class='red'>后台导入数据，未审核</span>";
							}else{
								return "<span class='red'>后台导入数据，已审核</span>";
							}	
						}					
					}else{
						return v;
					}
				},
				width : 400
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
		searchitems : [{
				display : "费用小类",
				name : 'budgetNameSearch'
			}, {
				display : "费用大类",
				name : 'parentNameSearch'
			}
		]
	}),
	
	$("#budgetType").change(function(){
		var projectId = $("#projectId").val();
		var budgetType = $("#budgetType").val();
		var paramObj = {
			projectId : projectId,
			budgetType : budgetType
		};
		$("#esmbudgetGrid").yxeditgrid("setParam",paramObj).yxeditgrid("processData");
	})
});

