var show_page = function(page) {
	$("#chanceGrid").yxgrid("reload");
};
$(function() {
	$("#chanceGrid").yxgrid({
		model : 'projectmanagent_chance_chance',
		action : 'unApprovalJson',
		title : '销售商机',

		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'chanceCode',
			display : '商机编号',
			sortable : true
		}, {
			name : 'chanceName',
			display : '商机名称',
			sortable : true
		}, {
			name : 'customerName',
			display : '客户名称',
			sortable : true
		}, {
			name : 'trackman',
			display : '跟踪人',
			width : '200',
			sortable : true
		}, {
			name : 'customerProvince',
			display : '客户所属省份',
			sortable : true
		}, {
			name : 'customerType',
			display : '客户类型',
			datacode : 'KHLX',
			sortable : true
		}, {
			name : 'status',
			display : '商机状态',
			process : function(v) {
				if (v == 0) {
					return "已转商机";
				}else if(v == 3){
					return "关闭";
				}else if(v == 4){
					return "已转定单";
				}else if(v == 5){
				    return "保存"
				}
//				return "可接收状态";

			},
			sortable : true
		}, {
			name : 'ExaStatus',
			display : '审批状态',
			sortable : true
		}],
//		param : {
//			ExaStatus : '部门审批'
//		},
		//扩展右键菜单
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					showOpenWin("?model=projectmanagent_chance_chance&action=init&perm=view&id=" + row.id);
				} else {
					alert("请选中一条数据");
				}
			}

		}
//		,{
//			text : '关闭商机',
//			icon : 'delete',
//			action : function(row,rows,grid){
//				if(row){
//					showOpenWin("?model=projectmanagent_chance_chance&action=toclose&id=" + row.id);
//				}else{
//					alert("请选择一条数据");
//				}
//
//			}
//		}
		,{
			text : '审批',
			icon : 'edit',
			showMenuFn : function(row){
				if(row.ExaStatus == '部门审批'){
					return true;
				}
				return false;
			},
			action : function(row,rows,grid){
				if(row){
					parent.location = "controller/projectmanagent/chance/ewf_index.php?actTo=ewfExam&taskId="+row.taskId+"&spid="+row.spid+"&billId="+row.id+"&examCode=oa_sale_chance";
				}
			}
		}],
		//快速搜索
		searchitems : [{
			display : '商机名称',
			name : 'chanceName'
		}, {
			display : '客户名称',
			name : 'customerName'
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
		isDelAction : false,
		showcheckbox : false
	});
});