// 用于新增/修改后回调刷新表格
var show_page = function(page) {
	$("#myApplyGrid").yxsubgrid("reload");
};
$(function() {
	$("#myApplyGrid").yxsubgrid({
		// 如果传入url，则用传入的url，否则使用model及action自动组装
		// url :
		model : 'purchase_plan_basic',
		action : 'myListPageJson',
		title : '已关闭采购申请',
		isToolBar : false,
		showcheckbox : false,
		param : {
			'state' : '3'
		},

		// 列信息
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					display : '采购类型',
					name : 'purchTypeCName',
					sortable : false,
					width : 80
				}, {
					display : '采购申请编号',
					name : 'planNumb',
					sortable : true,
					width : 120
				},  {
					display : '申请源单据号',
					name : 'sourceNumb',
					sortable : true,
					width : 120
				}, {
					display : '批次号',
					name : 'batchNumb',
					sortable : true,
					width : 120
				}, {
					display : '申请时间 ',
					name : 'sendTime',
					sortable : true,
					width : 80
				}, {
					display : '希望完成时间 ',
					name : 'dateHope',
					sortable : true,
					width : 80
				}, {
					display : '申请原因 ',
					name : 'applyReason',
					sortable : true,
					width : 300
				}, {
					display : '关闭原因 ',
					name : 'closeRemark',
					sortable : true,
					width :300
				}],
		// 主从表格设置
		subGridOptions : {
			url : '?model=purchase_plan_equipment&action=pageJson',
			param : [{
						paramId : 'basicId',
						colId : 'id'
					}],
			colModel : [ {
						name : 'productNumb',
						display : '物料编号'
					}, {
						name : 'productName',
						width : 200,
						display : '物料名称',
						process : function(v, data) {
							if (v == "") {
								return data.inputProductName;
							}
							return v;
						}
					},{
						name : 'amountAll',
						display : "申请数量",
						width : 70
					}, {
						name : 'amountAllOld',
						display : "原申请数量",
						width : 70
					}, {
						name : 'dateIssued',
						display : "申请日期",
						width : 80
					}, {
						name : 'dateHope',
						display : "希望完成日期",
						width : 80
					}]
		},
		// 扩展右键菜单
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					location = "?model=purchase_plan_basic&action=read&id="
							+ row.id + "&purchType=" + row.purchType + "&skey="
							+ row['skey_'];
				} else {
					alert("请选中一条数据");
				}
			}

		},  {
			name : 'aduit',
			text : '审批情况',
			icon : 'view',
			showMenuFn : function(row) {
				if ((row.ExaStatus == "完成" || row.ExaStatus == "打回")
						&& (row.purchType == "assets"
								|| row.purchType == "rdproject" || row.purchType == "produce")) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_purch_plan_basic&pid="
							+ row.id
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=800");
				}
			}
		}],
		// 快速搜索
		searchitems : [{
					display : '采购申请编号',
					name : 'seachPlanNumb'
				}, {
					display : '物料编号',
					name : 'productNumb'
				}, {
					display : '物料名称',
					name : 'productName'
				}, {
					display : '申请源单据号',
					name : 'sourceNumb'
				}, {
					display : '批次号',
					name : 'batchNumb'
				}],
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