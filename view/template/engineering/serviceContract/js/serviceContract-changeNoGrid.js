// 用于新增/修改后回调刷新表格
var show_page = function(page) {
	$(".changeNoGrid").yxgrid("reload");
};
$(function() {
	$(".changeNoGrid").yxgrid({
		//如果传入url，则用传入的url，否则使用model及action自动组装
		//						 url :
		model : 'engineering_serviceContract_serviceContract',
		action : 'changeAuditNo',
		title : '待审批的变更服务合同',
		formHeight : 600,
		isToolBar : false,
		showcheckbox : false,

		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'parentOrder',
			display : '父合同名称',
			sortable : true,
			hide : true
		}, {
			name : 'orderCode',
			display : '鼎利合同号',
			sortable : true
		}, {
			name : 'orderTempCode',
			display : '临时合同号',
			sortable : true
		}, {
			name : 'orderName',
			display : '合同名称',
			sortable : true
		}, {
			name : 'state',
			display : '合同状态',
			sortable : true,
			process : function(v) {
				if (v == '0') {
					return "未提交";
				} else if (v == '1') {
					return "审批中";
				} else if (v == '2') {
					return "执行中";
				} else if (v == '3') {
					return "已关闭";
				} else if (v == '4') {
					return "已完成";
				}
			},
			width : 90
		}, {
			name : 'ExaStatus',
			display : '审批状态',
			sortable : true,
			width : 90
		}, {
			name : 'transmit',
			display : '任务书状态',
			sortable : true,
			process : function(v) {
				if (v == '0') {
					return "未下达";
				} else if (v == '1') {
					return "已下达";
				}
			}
		}],
		//扩展按钮
		//						buttonsEx : [],
		param : {
			ExaStatus : '部门审批'
		},
		//扩展右键菜单
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					showOpenWin("?model=engineering_serviceContract_serviceContract&action=toReadTab&perm=view&id="
							+ row.contractId + "&skey="+row['skey_']);
				} else {
					alert("请选中一条数据");
				}
			}

		}, {
			text : '审批',
			icon : 'edit',
			action : function(row, rows, grid) {
				if (row) {
					location = "controller/engineering/serviceContract/ewf_change_index.php?actTo=ewfExam&taskId="
							+ row.task
							+ "&spid="
							+ row.id
							+ "&billId="
							+ row.contractId + "&examCode=oa_contract_service"  + "&skey="+row['skey_'];
				}
			}
		}],
		//快速搜索
		searchitems : [{
			display : '合同名称',
			name : 'orderName'
		},{
			display : '合同编号',
			name : 'orderCodeOrTempSearch'
		}],
		// title : '客户信息',
		//业务对象名称
		//						boName : '供应商联系人',
		//默认搜索字段名
		sortname : "id",
		//默认搜索顺序
		sortorder : "ASC",
		//显示查看按钮
		isViewAction : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false
	});

});