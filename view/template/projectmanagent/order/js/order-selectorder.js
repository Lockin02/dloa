(function($) {
	$.woo.yxgrid.subclass('woo.yxgrid_selectorder', {
		options : {
			model : 'projectmanagent_order_order',
            action : 'presentInfoJson',
            title : '合同列表',
			/**
			 * 是否显示查看按钮/菜单
			 */
			isViewAction : false,
			/**
			 * 是否显示修改按钮/菜单
			 */
			isEditAction : false,
			// 列信息
			colModel :
			[
				{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'tablename',
					display : '合同类型',
					sortable : true,
					width : 80,
					process : function(v) {
						if (v == 'oa_sale_order') {
							return "销售合同";
						} else if (v == 'oa_sale_service') {
							return "服务合同";
						} else if (v == 'oa_sale_lease') {
							return "租赁合同";
						} else if (v == 'oa_sale_rdproject') {
							return "研发合同";
						}
					}
				}, {
					name : 'orderCode',
					display : '鼎利合同号',
					sortable : true,
					width : 180
//					hide : true,
//					process : function(v,row) {
//						if(v == ""){
//							return "<span style='color:blue'>" + row.orderTempCode + "</span>";
//						}else{
//							return v;
//						}
//					}
				}, {
					name : 'orderTempCode',
					display : '临时合同号',
					sortable : true,
					width : 180,
					hide : false
				}, {
					name : 'customerName',
					display : '客户名称',
					sortable : true,
					width : 180
				}, {
					name : 'customerType',
					display : '客户类型',
					sortable : true,
					datacode : 'KHLX',
					width : 70,
					hide : true
				}, {
					name : 'orderName',
					display : '合同名称',
					sortable : true,
					width : 150,
					hide : true
				}, {
					name : 'sign',
					display : '是否签约',
					sortable : true,
					width : 70,
					hide : true
				}, {
					name : 'prinvipalName',
					display : '合同负责人',
					sortable : true,
					width : 80
				}, {
					name : 'areaName',
					display : '归属区域',
					sortable : true,
					width : 80
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
						} else if (v == '5') {
							return "已合并";
						} else if (v == '5') {
							return "已拆分";
						}
					},
					width : 80
				}, {
					name : 'ExaStatus',
					display : '审批状态',
					sortable : true,
					width : 80
				}
			],
			// 快速搜索
			searchitems : [
				{
					display : '合同编号',
					name : 'orderCodeOrTempSearch'
				}, {
					display : '合同名称',
					name : 'orderName'
				}, {
					display : '客户名称',
					name : 'customerName'
				}
			],

			// 默认搜索顺序
			sortorder : "DESC"
		}
	});
})(jQuery);