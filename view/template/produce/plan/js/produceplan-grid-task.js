var show_page = function(page) {
	$("#produceplanGrid").yxgrid("reload");
};

$(function() {
	var isCanAdd = $("#taskId").val() > 0 ? true : false; //是否可新增标志
	$("#produceplanGrid").yxgrid({
		model: 'produce_plan_produceplan',
		param : {
			taskId : $("#taskId").val()
		},
		title: '生产计划',
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		isOpButton : false,
		showcheckbox : false,
		//列信息
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		},{
			name: 'docCode',
			display: '单据编号',
			sortable: true,
			width : 110,
			process : function (v ,row) {
				return "<a href='#' onclick='showModalWin(\"?model=produce_plan_produceplan&action=toViewTab&id=" + row.id + "\",1)'>" + v + "</a>";
			}
		},{
			name: 'docStatus',
			display: '单据状态',
			sortable: true,
			width : 60,
			align : 'center',
			process : function(v ,row) {
				switch (v) {
				case '0':
					return "未确定";
					break;
				case '1':
					return "未领料";
					break;
				case '2':
					return "正在审批";
					break;
				case '3':
					return "正在领料";
					break;
				case '4':
					return "部分领料";
					break;
				case '5':
					return "领料完成";
					break;
				case '6':
					return "已关闭";
					break;
				case '7':
					return "部分入库";
					break;
				case '8':
					return "已完成";
					break;
				default:
					return "--";
				}
			}
		},{
			name: 'urgentLevel',
			display: '优先级',
			sortable: true,
			align : 'center'
		},{
			name: 'docDate',
			display: '单据日期',
			sortable: true,
			width : 80,
			align : 'center'
		},{
			name: 'proType',
			display: '物料类型',
			sortable: true
		},{
			name: 'productName',
			display: '配置名称',
			sortable: true,
			width : 200
		},{
			name: 'productCode',
			display: '配置编码',
			sortable: true
		},{
			name: 'planNum',
			display: '数量',
			sortable: true,
			width : 60
		},{
			name: 'qualifiedNum',
			display: '质检合格数量',
			sortable: true,
			width : 80
		},{
			name: 'stockNum',
			display: '入库数量',
			sortable: true,
			width : 60
		},{
			name: 'taskCode',
			display: '生产任务单号',
			sortable: true,
			width : 120
		},{
			name: 'relDocCode',
			display: '合同编号',
			sortable: true
		},{
			name: 'applyDocCode',
			display: '生产申请单号',
			sortable: true
		},{
			name: 'customerName',
			display: '客户名称',
			sortable: true,
			width : 150
		},{
			name: 'productionBatch',
			display: '生产批次',
			sortable: true
		},{
			name: 'planStartDate',
			display: '计划开始时间',
			sortable: true,
			align : 'center'
		},{
			name: 'planEndDate',
			display: '计划结束时间',
			sortable: true,
			align : 'center'
		},{
			name: 'chargeUserName',
			display: '责任人',
			sortable: true,
			align : 'center'
		},{
			name: 'saleUserName',
			display: '销售代表',
			sortable: true,
			align : 'center'
		},{
			name: 'deliveryDate',
			display: '交货日期',
			sortable: true,
			align : 'center'
		},{
			name: 'remark',
			display: '备注',
			sortable: true,
			width : 350
		}],

		//扩展右键菜单
		menusEx : [{
			text : '打印',
			icon : 'view',
			action : function(row) {
				showModalWin('?model=produce_plan_produceplan&action=toView&id=' + row.id ,'1');
			}
		},{
			text : '变更',
			icon : 'edit',
			action : function(row) {
				showThickboxWin('?model=produce_plan_produceplan&action=toEdit&id='
					+ row.id
					+ '&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=900');
			}
		}],

		//下拉过滤
		comboEx : [{
			text : '优先级',
			key : 'urgentLevelCode',
			datacode : 'SCJHYXJ'
		},{
			text : '单据状态',
			key : 'docStatus',
			data : [{
				text : '未确定',
				value : '0'
			},{
				text : '执行中',
				value : '1'
			},{
				text : '已完成',
				value : '2'
			},{
				text : '已关闭',
				value : '3'
			}]
		}],

		toAddConfig: {
			toAddFn : function(p ,g) {
				if (g && $("#taskId").val() > 0) {
					showModalWin("?model=produce_plan_produceplan&action=toAddByTask&taskId=" + $("#taskId").val() ,'1');
				}
			}
		},
		toViewConfig: {
			toViewFn : function(p ,g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showModalWin("?model=produce_plan_produceplan&action=toViewTab&id=" + get[p.keyField] ,'1');
				}
			}
		},

		searchitems: [{
			name: 'docCode',
			display: '单据编号'
		},{
			name: 'docDate',
			display: '单据日期'
		},{
			name: 'productName',
			display: '配置名称'
		},{
			name: 'productCode',
			display: '配置编码'
		},{
			name: 'taskCode',
			display: '生产任务单号'
		},{
			name: 'relDocCode',
			display: '合同编号'
		},{
			name: 'applyDocCode',
			display: '生产申请单号'
		},{
			name: 'customerName',
			display: '客户名称'
		},{
			name: 'productionBatch',
			display: '生产批次'
		},{
			name: 'planStartDate',
			display: '计划开始时间'
		},{
			name: 'planEndDate',
			display: '计划结束时间'
		},{
			name: 'chargeUserName',
			display: '责任人'
		},{
			name: 'urgentLevel',
			display: '优先级'
		},{
			name: 'saleUserName',
			display: '销售代表'
		},{
			name: 'deliveryDate',
			display: '交货日期'
		}]
	});
});