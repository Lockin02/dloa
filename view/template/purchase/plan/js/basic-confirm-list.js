// 用于新增/修改后回调刷新表格
var show_page = function(page) {
	$("#confirmGrid").yxsubgrid("reload");
};
$(function() {
	$("#confirmGrid").yxsubgrid({
		// 如果传入url，则用传入的url，否则使用model及action自动组装
		// url :
		model : 'purchase_plan_basic',
		action : 'myConfirmListPageJson',
		title : '待物料确认的采购申请',
		isToolBar : false,
		showcheckbox : false,
		bodyAlign:'center',
		param : {
			productSureStatusArr:'0,2'
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
					sortable : false
				}, {
					display : '采购申请编号',
					name : 'planNumb',
					sortable : true,
					width : 120
				}, {
					display : '审批状态',
					name : 'ExaStatus',
					sortable : true
				}, {
					display : '确认状态',
					name : 'productSureStatus',
					process : function(v, data) {
						if (v == 0) {
							return "未确认";
						} else if (v == 1) {
							return "已确认";
						} else {
							return "部分确认";
						}
					}
				}, {
					display : '申请源单据号',
					name : 'sourceNumb',
					sortable : true,
					width : 120
				}, {
					display : '申请人',
					name : 'sendName',
					sortable : true
				}, {
					display : '申请时间 ',
					name : 'sendTime',
					sortable : true,
					width : 80
				}],
		comboEx : [{
					text : '确认状态',
					key : 'productSureStatusArr',
					data : [{
								text : '未确认',
								value : 0
							}, {
								text : '部分确认',
								value : 2
							}, {
								text : '已确认',
								value : 1
							}]
				}],
		// 主从表格设置
		subGridOptions : {
			url : '?model=purchase_plan_equipment&action=pageJsonForConfirm',
			param : [{
						paramId : 'basicId',
						colId : 'id'
					},{
						paramId : 'purchTypeEqu',
						colId : 'purchType'
					}],
			colModel : [{
						name : 'productCategoryName',
						display : '物料类别',
						width : 50
					}, {
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
					}, {
						name : 'pattem',
						display : "规格型号"
					}, {
						name : 'unitName',
						display : "单位",
						width : 50
					}, {
						name : 'amountAll',
						display : "申请数量",
						width : 70
					}, {
						name : 'dateHope',
						display : "希望完成日期"
					}, {
						name : 'isBack',
						display : "是否打回",
						process : function(v, data) {
							return v == 1 ? "是" : "否";
						}
					}]
		},
		// 扩展右键菜单
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					if(row.purchType=="oa_asset_purchase_apply"){
							showThickboxWin("?model=asset_purchase_apply_apply&action=purchView&id="
							+ row.id+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height=900&width=1000");
					}else{
					location = "?model=purchase_plan_basic&action=read&id="
							+ row.id + "&purchType=" + row.purchType + "&skey="
							+ row['skey_'];
					}
				} else {
					alert("请选中一条数据");
				}
			}

		}, {
			text : '物料确认',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.productSureStatus == 1) {
					return false;
				}
				return true;
			},
			action : function(row, rows, grid) {
				if (row) {
					if(row.purchType=="oa_asset_purchase_apply"){
						location = "?model=asset_purchase_apply_apply&action=toConfirmProduct&id="
								+ row.id
								+ "&purchType="
								+ row.purchType
								+ "&skey="
								+ row['skey_'];

					}else{
						location = "?model=purchase_plan_basic&action=toConfirmProduct&id="
								+ row.id
								+ "&purchType="
								+ row.purchType
								+ "&skey="
								+ row['skey_'];
					}
				} else {
					alert("请选中一条数据");
				}
			}

		}],
		// 快速搜索
		searchitems : [{
					display : '采购申请编号',
					name : 'planNumbUnion'
				}, {
					display : '物料编号',
					name : 'productNumbUnion'
				}, {
					display : '物料名称',
					name : 'productNameUnion'
				}, {
					display : '申请源单据号',
					name : 'sourceNumbUnion'
				}],
		// title : '客户信息',
		// 业务对象名称
		// boName : '供应商联系人',
		// 默认搜索字段名
//		sortname : "updateTime",
		// 默认搜索顺序
//		sortorder : "DESC",
		// 显示查看按钮
		isViewAction : false,
		// isAddAction : true,
		isEditAction : false,
		isDelAction : false
	});

});