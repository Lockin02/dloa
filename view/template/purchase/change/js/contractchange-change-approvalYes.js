// 用于新增/修改后回调刷新表格
var show_page = function(page) {
	$(".approvalYesGrid").yxgrid("reload");
};
$(function() {
	$(".approvalYesGrid").yxgrid({
		// 如果传入url，则用传入的url，否则使用model及action自动组装
		// url :
		model : 'purchase_change_contractchange',
		action : 'pageJsonYes',
		title : '已审核的采购订单',
		isToolBar : false,
		showcheckbox : false,

		// 列信息
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					display : '订单编号',
					name : 'hwapplyNumb',
					sortable : true,
					width : '200'
				}, {
					display : '申请人名称',
					name : 'createName',
					sortable : true
				}, {
					display : '预计完成时间',
					name : 'dateHope',
					sortable : true
				}, {
					display : '审批状态',
					name : 'ExaStatus',
					width : 70,
					sortable : true
				}, {
					display : '审批时间',
					name : 'ExaDT',
					width : 70,
					sortable : true
				}, {
					display : '供应商名称',
					name : 'suppName',
					sortable : true
				}, {
					display : '发票类型',
					name : 'billingType',
					datacode : 'FPLX', // 数据字典编码
					width : 80,
					sortable : true
				}, {
					display : '付款方式',
					name : 'paymetType',
					datacode : 'fkfs',
					width : 80,
					sortable : true
				}, {
					display : '接收状态',
					name : 'isChanged',
					process : function(v) {
						if (v == 0) {
							return "已确认";
						}
						return "可接收状态";

					}
				}],
		// comboEx : [{
		// text : "接收状态",
		// key : 'isChanged',
		// data : [{
		// text : '已确认',
		// value : 0
		// }, {
		// text : '可接收状态',
		// value : 1
		// }]
		// }],
		param : {
			"ExaStatus" : "打回,完成"
		},
		// 扩展按钮
		buttonsEx : [],
		// 扩展右键菜单
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					// showThickboxWin("?model=purchase_contract_purchasecontract&action=init"
					// + "&id="
					// + row.id
					// +
					// "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height="
					// + 400 + "&width=" + 700);
					showOpenWin("?model=purchase_contract_purchasecontract&action=toTabView&id="+row.id+"&applyNumb="+row.applyNumb+"&skey="+row['skey_']);
				} else {
					alert("请选中一条数据");
				}
			}

		}
				// ,{
				// text : '审批情况',
				// icon : 'view',
				// action : function(row,rows,grid){
				// if(row){
				// showThickboxWin("controller/common/readview.php?itemtype=oa_purchase_apply_basic&pid="
				// +row.id
				// +"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=600");
				// }
				// }
				// }
		, {
			text : '确认',
			icon : 'add',
			 showMenuFn : function(row){
			 if(row.ExaStatus == '完成' && row.isChanged == '1'){
			 return true;
			 }
			 return false;
			 },
			action : function(row, rows, grid) {
				if (row && row.ExaStatus == "完成") {
					// location =
					// "?model=purchase_change_contractchange&action=coverChange&id="+row.id;

					if (window.confirm("确定要确认吗？")) {
						$.ajax({
							type : "POST",
							//url : "?model=purchase_change_contractchange&action=coverChange",
							url : "?model=common_changeLog&action=confirmChange",
							data : {
								id : row.id,
								logObj:'purchasecontract'
							},
							success : function(msg) {alert(msg)
								if (msg == 1) {
									alert("确认成功");
									show_page();
								} else {
									alert("确认不成功");
									show_page();
								}
							}
						});
					}
				}
			}

		}],
		// 快速搜索
		searchitems : [{
					display : '订单编号',
					name : 'hwapplyNumb'
				}, {
					display : '供应商名称',
					name : 'suppName'
				}],
		// title : '客户信息',
		// 业务对象名称
		// boName : '供应商联系人',
		// 默认搜索字段名
		sortname : "id",
		// 默认搜索顺序
		sortorder : "ASC",
		// 显示查看按钮
		isViewAction : false,
		isAddAction : true,
		isEditAction : false,
		isDelAction : false
	});

});