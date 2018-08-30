// 用于新增/修改后回调刷新表格
var show_page = function(page) {
	$(".contractchange").yxgrid("reload");
};
$(function() {
	$(".contractchange").yxgrid({
		// 如果传入url，则用传入的url，否则使用model及action自动组装
		// url :
		model : 'purchase_change_contractchange',
		action : 'pageJsonMy',
		param:{notState:"9"},
		title : '采购订单变更列表',
		isToolBar : false,
		showcheckbox : false,

		// 列信息
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					display : '采购订单编号',
					name : 'hwapplyNumb',
					sortable : true,
					width : 180
				},  {
					display : '预计完成时间',
					name : 'dateHope',
					sortable : true
				}, {
					display : '供应商名称',
					name : 'suppName',
					sortable : true
				}, {
					display : '付款类型',
					name : 'paymetType',
					datacode : 'fkfs',
					sortable : true,
					width : 60
				}, {
					display : '发票类型',
					name : 'billingType',
					datacode : 'FPLX',
					sortable : true,
					width : 80
				}, {
					display : '审核状态',
					name : 'ExaStatus',
					sortable : true,
					width : 80
				}, {
					display : '备注',
					name : 'remark',
					sortable : true,
					width : 160
				}],
		// 扩展右键菜单
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					showOpenWin("?model=purchase_contract_purchasecontract&action=toTabView&id="
							+ row.id + "&applyNumb=" + row.applyNumb+"&skey="+row['skey_']);
				} else {
					alert("请选中一条数据");
				}
			}

		}, {
			text : '编辑',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '待提交' || row.ExaStatus == '打回') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					// showThickboxWin("?model=purchase_change_contractchange&action=init"
					// + "&id="
					// + row.id
					// + "&placeValuesBefore&TB_iframe=true&modal=false&height="
					// + 400 + "&width=" + 700);
					parent.location = "?model=purchase_contract_purchasecontract&action=toEditChange&id="
							+ row.id;
				}
			}
		},
				// {
				// text : '查看历史版本',
				// icon : 'view',
				// action : function(row,rows,grid){
				// if(row){
				// location =
				// "?model=purchase_change_contractchange&action=toViewHistory&id="
				// + row.applyNumb;
				// }
				// }
				// },
				{
					text : '提交审批',
					icon : 'add',
					showMenuFn : function(row) {
						if (row.ExaStatus == '待提交' || row.ExaStatus == '打回') {
							return true;
						}
						return false;
					},
					action : function(row, rows, grid) {
						if (row && row.ExaStatus != "部门审批"
								&& row.ExaStatus != "完成") {
							parent.location = "controller/purchase/change/ewf_index.php?actTo=ewfSelect&billId="
									+ row.id
									+ "&examCode=oa_purch_apply_basic&formName=采购合同审批";
						}
					}
				}],
		// 快速搜索
		searchitems : [{
					display : '订单编号',
					name : 'seachApplyNumb'
				},
								{
									display : '单据日期',
									name : 'orderTime'
								},
								{
									display : '供应商名称',
									name : 'suppName'
								},
								{
									display : '物料编号',
									name : 'productNumb'
								},
								{
									display : '物料名称',
									name : 'productName'
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