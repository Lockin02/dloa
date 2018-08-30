// 用于新增/修改后回调刷新表格
var show_page = function(page) {
	$("#myEquGrid").yxgrid("reload");
};
	function viewPlan(basicId,purchType){
	    var skey = "";
	    $.ajax({
		    type: "POST",
		    url: "?model=purchase_plan_basic&action=md5RowAjax",
		    data: {"id" : basicId},
		    async: false,
		    success: function(data){
		   	   skey = data;
			}
		});

		location="index1.php?model=purchase_plan_basic&action=read&id="+basicId+"&purchType="+purchType+"&skey=" + skey;
	}

$(function() {
	$("#myEquGrid").yxgrid({
		// 如果传入url，则用传入的url，否则使用model及action自动组装
		// url :
		model : 'purchase_plan_equipment',
		action : 'pageJsonAllList',
		title : '采购申请物料汇总',
		isToolBar : false,
		showcheckbox : false,

		// 列信息
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				},{
					display : 'basicId',
					name : 'basicId',
					sortable : true,
					hide : true
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
					},{
					display : '采购申请编号',
					name : 'basicNumb',
					sortable : true,
					width : 110,
					process : function(v, row) {
						return "<a href='#' onclick='viewPlan(\""
								+ row.basicId
								+"\",\""
								+row.purchType
								+ "\")' >"
								+ v
								+ "</a>";
					}
				},{
					display : '审批状态',
					name : 'ExaStatus',
					sortable : true,
					width:60
				}, {
					display : '申请源单据号',
					name : 'sourceNumb',
					sortable : true,
					width : 110
				}, {
					display : '批次号',
					name : 'batchNumb',
					sortable : true
				}, {
						name : 'amountAll',
						display : "申请数量",
						width : 70
					},{
						name : 'amountIssued',
						display : "已下达任务数量"
					},

						{
					display : '申请时间 ',
					name : 'dateIssued',
					sortable : true,
					width : 80
				}, {
					display : '希望完成时间 ',
					name : 'dateHope',
					sortable : true,
					width : 80
				}],
		// 扩展右键菜单
//		menusEx : [{
//			text : '查看',
//			icon : 'view',
//			action : function(row, rows, grid) {
//				if (row) {
//					location="?model=purchase_plan_basic&action=read&id="
//							+ row.id + "&purchType=" + row.purchType+"&skey="+row['skey_'];
//				} else {
//					alert("请选中一条数据");
//				}
//			}
//
//		}],
		// 快速搜索
		searchitems : [{
					display : '物料名称',
					name : 'productName'
				},{
					display : '物料编号',
					name : 'seachProductNumb'
				},{
					display : '采购申请编号',
					name : 'basicNumb'
				},{
					display : '批次号',
					name : 'searchBatchNumb'
				}
		],
		// title : '客户信息',
		// 业务对象名称
		// boName : '供应商联系人',
		// 默认搜索字段名
		sortname : "id",
		// 默认搜索顺序
		sortorder : "DESC",
		// 显示查看按钮
		isViewAction : false,
		// isAddAction : true,
		isEditAction : false,
		isDelAction : false
	});

});