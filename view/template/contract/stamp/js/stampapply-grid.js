var show_page = function(page) {
	$("#stampapplyGrid").yxgrid("reload");
};
$(function() {
	$("#stampapplyGrid").yxgrid({
		model : 'contract_stamp_stampapply',
		title : '盖章申请',
		isEditAction : false,
		isDelAction : false,
		isOpButton : false,
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'status',
			display : '盖章',
			sortable : true,
			width : 50,
			align : 'center',
			process : function(v,row){
				if(v=="1"){
					return '<img title="已盖章" src="images/icon/ok3.png" style="width:15px;height:15px;">';
				}else if(v=='2'){
					return "已关闭";
				}else{
					return "未盖章";
				}
			}
		}, {
			name : 'applyUserId',
			display : '申请人id',
			sortable : true,
			hide : true
		}, {
			name : 'applyUserName',
			display : '申请人',
			sortable : true,
        	width : 80
		}, {
			name : 'applyDate',
			display : '申请日期',
			sortable : true,
        	width : 75
		}, {
			name : 'contractType',
			display : '文件类型',
			sortable : true,
        	width : 70,
        	datacode : 'HTGZYD'
		}, {
			name : 'fileName',
			display : '文件名',
			sortable : true
		}, {
			name : 'signCompanyName',
			display : '文件发往单位',
			sortable : true,
        	width : 130
		}, {
			name : 'contractMoney',
			display : '合同金额',
			sortable : true,
			process : function(v){
				return moneyFormat2(v);
			},
			hide : true
		}, {
			name : 'stampType',
			display : '盖章类型',
			sortable : true
		}, {
			name : 'stampCompanyId',
			display : '公司ID',
			sortable : true,
			hide : true
		}, {
			name : 'stampCompany',
			display : '公司名',
			sortable : true
		},{
			name : 'useMatters',
			display : '使用事项',
			sortable : true	
		}, {
			name : 'useMattersId',
			display : '使用事项id',
			sortable : true,
			hide : true
		}, {
			name : 'attn',
			display : '业务经办人',
			sortable : true,
			width : 80
		}, {
			name : 'attnId',
			display : '业务经办人Id',
			sortable : true,
			hide : true
		}, {
			name : 'attnDept',
			display : '业务经办人部门',
			sortable : true,
			hide : true
		}, {
			name : 'attnDeptId',
			display : '业务经办人部门Id',
			sortable : true,
			hide : true
		}, {
			name : 'isNeedAudit',
			display : '是否需要审批',
			sortable : true,
			hide : true
		}, {
			name : 'ExaStatus',
			display : '审批状态',
			sortable : true,
			width : 70
		}, {
			name : 'objCode',
			display : '业务编号',
			width : 120,
			sortable : true,
			hide : true
		}, {
			name : 'batchNo',
			display : '盖章批号',
			sortable : true,
			hide : true
		}, {
			name : 'contractId',
			display : '合同id',
			sortable : true,
			hide : true
		}, {
			name : 'contractCode',
			display : '合同编号',
        	width : 130,
			sortable : true
		}, {
			name : 'contractName',
			display : '合同名称',
			sortable : true,
        	width : 130
		}, {
			name : 'remark',
			display : '备注说明',
			sortable : true
		}],
		toAddConfig : {
			action : 'toAdd',
			formHeight : 500,
			formWidth : 900
		},
		toViewConfig : {
			action : 'toView',
			formHeight : 500,
			formWidth : 900
		},
		searchitems : [{
			display : "合同编号",
			name : 'contractCodeSer'
		},{
			display : "申请人",
			name : 'applyUserNameSer'
		}],
		// 盖章状态数据过滤
		comboEx : [{
			text: "合同类型",
			key: 'contractType',
			datacode : 'HTGZYD'
		},{
			text: "盖章状态",
			key: 'status',
			value :'0',
			data : [{
				text : '未盖章',
				value : '0'
			}, {
				text : '已盖章',
				value : '1'
			}, {
				text : '已关闭',
				value : '2'
			}]
		}]
	});
});