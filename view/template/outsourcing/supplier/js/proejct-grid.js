var show_page = function(page) {
	$("#proejctGrid").yxgrid("reload");
};
$(function() {
	$("#proejctGrid").yxgrid({
		model : 'outsourcing_supplier_proejct',
		title : '项目信息',
		bodyAlign:'center',
		isAddAction:false,
		isEditAction:false,
		isDelAction:false,
		showcheckbox:false,
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},  {
			name : 'projectCode',
			width:'120',
			display : '项目编号',
			sortable : true,
			process : function(v,row){
					return "<a href='#' onclick='showModalWin(\"?model=engineering_project_esmproject&action=viewTab&id=" + row.projectId +"\",1)'>" + v + "</a>";
			}
		}, {
			name : 'projectName',
			display : '项目名称',
			width:'150',
			sortable : true
		}, {
			name : 'suppCode',
			display : '供应商编号',
			width:'80',
			sortable : true
		}, {
			name : 'suppName',
			display : '外包供应商',
			width:'150',
			sortable : true
		}, {
			name : 'outContractCode',
			display : '外包编号',
			width:'120',
			sortable : true,
			process : function(v,row){
				 var formId = row.outContractId;
			        var skey = "";
			        $.ajax({
					    type: "POST",
					    url: "?model=contract_outsourcing_outsourcing&action=md5RowAjax",
					    data: { "id" : formId },
					    async: false,
					    success: function(data){
					   	   skey = data;
						}
					});
					return "<a href='#' onclick='showModalWin(\"?model=contract_outsourcing_outsourcing&action=viewTab&id=" + row.outContractId + '&skey=' + skey +"\",1)'>" + v + "</a>";
			}
		},{
			name : 'projectType',
			display : '项目类型',
			width:'70',
			sortable : true
		},{
			name : 'outsourcingName',
			display : '外包方式',
			width:'70',
			sortable : true
		}, {
			name : 'personNum',
			display : '人员数量',
			width:'50',
			sortable : true
		}, {
			name : 'beginDate',
			display : '项目实施周期(开始)',
			sortable : true
		}, {
			name : 'endDate',
			display : '项目实施周期(结束)',
			sortable : true
		}, {
			name : 'totalMoney',
			display : '外包金额',
			sortable : true
		}, {
			name : 'checkScore',
			display : '考核得分',
			width:'50',
			sortable : true
		}, {
			name : 'deductReason',
			display : '扣分原因',
			width:'250',
			align:'left',
			sortable : true
		}, {
			name : 'evaluate',
			display : '项目评价',
			width:'250',
			align:'left',
			sortable : true
		}],
		lockCol:['projectCode','projectName'],//锁定的列名
		toAddConfig : {
			action : 'toAdd&suppId='+$("#suppId").val(),
			formWidth : 800,
			formHeight : 500
		},

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
							display : "项目编号",
							name : 'projectCode'
						},{
							display : "项目名称",
							name : 'projectName'
						}]
	});
});