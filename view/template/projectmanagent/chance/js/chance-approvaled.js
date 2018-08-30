var show_page = function(page) {
	$("#chanceGrid").yxgrid("reload");
};
$(function() {
	$("#chanceGrid").yxgrid({
		model : 'projectmanagent_chance_chance',
		action : 'approvaledJson',
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
//			ExaStatus : '完成'
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
//			text : '生成订单',
//			icon : 'add',
//			action : function (row,rows,grid){
//				if(row){
//					parent.location = "?model=projectmanagent_order_order&action=toAdd&id="+ row.id;
//				}else{
//					akert("请选中一条数据");
//				}
//			}
//		}
		],
		//快速搜索
		searchitems : [{
			display : '商机名称',
			name : 'chanceName'
		}, {
			display : '客户名称',
			name : 'customerName'
		}],
		//业务对象名称
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