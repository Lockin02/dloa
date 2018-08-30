// 用于新增/修改后回调刷新表格
var show_page = function(page) {
	$(".myChangeGrid").yxgrid("reload");
};
$(function() {
	$(".myChangeGrid").yxgrid({
		// 如果传入url，则用传入的url，否则使用model及action自动组装
		// url :
		model : 'purchase_task_basic',
		action : 'pageJsonMy',
		title : '采购任务变更列表',
		isToolBar : false,
		showcheckbox : false,

		// 列信息
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					display : '采购任务编号',
					name : 'taskNumb',
					sortable : true,
					width : 180
				}, {
					display : '任务下达人',
					name : 'createName',
					sortable : true
				}, {
					display : '任务负责人',
					name : 'sendName',
					sortable : true
				}, {
					display : '任务下达时间 ',
					name : 'sendTime',
					sortable : true,
					width : 60
				}, {
					display : '希望完成时间 ',
					name : 'dateHope',
					sortable : true,
					width : 80
				}],
		// 扩展右键菜单
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					parent.location="?model=purchase_task_basic&action=read&id="
							+ row.id+"&skey="+row['skey_'];
				} else {
					alert("请选中一条数据");
				}
			}

		}],
		// 快速搜索
		searchitems : [{
					display : '采购任务编号',
					name : 'taskNumb'
				}
		],
		// title : '客户信息',
		// 业务对象名称
		// boName : '供应商联系人',
		// 默认搜索字段名
		sortname : "updateTime",
		// 默认搜索顺序
		sortorder : "DESC",
		// 显示查看按钮
		isViewAction : false,
		// isAddAction : true,
		isEditAction : false,
		isDelAction : false
	});

});