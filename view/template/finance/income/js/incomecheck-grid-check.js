var show_page = function() {
	$("#incomecheckGrid").yxgrid("reload");
};
$(function() {
	$("#incomecheckGrid").yxgrid({
		model: 'finance_income_incomecheck',
		title: '核销记录表',
		showcheckbox : false,
		isOpButton : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		param : {'contractIdArr' : $("#contractId").val(),'payConIdArr' : $("#payConId").val(),'incomeIdArr' : $("#incomeId").val()},
		//列信息
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		},
		{
			name: 'contractId',
			display: '合同id',
			sortable: true,
			hide: true
		},
		{
			name: 'contractCode',
			display: '合同编号',
			sortable: true,
			width : 120
		},
		{
			name: 'contractName',
			display: '合同名称',
			sortable: true,
			width : 120
		},
		{
			name: 'payConId',
			display: '付款条件id',
			sortable: true,
			hide: true
		},
		{
			name: 'payConName',
			display: '付款条件名称',
			width : 80,
			sortable: true
		},
		{
			name: 'incomeType',
			display: '源单类型',
			sortable: true,
			width : 80,
			process : function(v){
                switch(v){
                    case "0" : return '到款单';
                    case "1" : return '扣款申请';
                    case "2" : return '开票记录';
                }
			}
		},
		{
			name: 'incomeId',
			display: '源单id',
			sortable: true,
			hide: true
		},
		{
			name: 'incomeNo',
			display: '源单号',
			sortable: true,
			width : 130
		},
		{
			name: 'checkDate',
			display: '核销日期',
			sortable: true,
			width : 80
		},
		{
			name: 'checkMoney',
			display: '本次核销金额',
			sortable: true,
			width : 80,
			process : function(v,row){
				return row.isRed == "1" ? '<span class="red">-'+ moneyFormat2(v) +'</span>' : moneyFormat2(v);
			}
		},
		{
			name: 'remark',
			display: '备注',
			sortable: true,
            width : 150
		},
		{
			name: 'isRed',
			display: '是否红字',
			sortable: true,
			hide: true
		},
		{
			name: 'auditStatus',
			display: '审核状态',
			sortable: true,
			hide: true
		},
		{
			name: 'auditorName',
			display: '审核人',
			sortable: true,
			width : 80,
			hide: true
		},
		{
			name: 'auditDate',
			display: '审核时间',
			sortable: true,
			width : 120,
			hide: true
		},
		{
			name: 'createName',
			display: '创建人',
			sortable: true,
			width : 80
		},
		{
			name: 'createTime',
			display: '创建时间',
			sortable: true,
			width : 120,
			hide: true
		},
		{
			name: 'updateName',
			display: '修改人',
			sortable: true,
			width : 80,
            hide: true
		},
		{
			name: 'updateTime',
			display: '修改时间',
			sortable: true,
			width : 130
		}],
		toEditConfig: {
			action: 'toEdit'
		},
		toViewConfig: {
			action: 'toView'
		},
		//过滤数据
		comboEx : [{
			text : '源单类型',
			key : 'incomeType',
			value : $('#incomeType').val(),
			data : [{
				text : '到款单',
				value : '0'
			}, {
				text : '扣款申请',
				value : '1'
			}, {
				text : '开票记录',
				value : '2'
			}]
		}],
		searchitems: [{
			display: "合同编号",
			name: 'contractCodeSearch'
		},{
			display: "合同名称",
			name: 'contractNameSearch'
		},{
			display: "到款单号",
			name: 'incomeNoSearch'
		},{
			display: "备注信息",
			name: 'remarkSearch'
		}],
		sortname : 'updateTime'
	});
});