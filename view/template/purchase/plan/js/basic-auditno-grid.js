// 用于新增/修改后回调刷新表格
var show_page = function(page) {
	$("#basicNoGrid").yxgrid("reload");
};
$(function() {
	$("#basicNoGrid").yxgrid({
		// 如果传入url，则用传入的url，否则使用model及action自动组装
		// url :
		model : 'purchase_plan_basic',
		action : 'myAuditPj',
		title : '待审批采购申请列表',
		isToolBar : false,
		showcheckbox : false,
//		param:{'purchType':"assets"},

		// 列信息
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					display : '采购申请编号',
					name : 'planNumb',
					sortable : true,
					width : 180
				},  {
					display : '采购类型',
					name : 'purchTypeCName',
					sortable : true
				}, {
					display : '审批状态',
					name : 'ExaStatus',
					sortable : true
				},{
					display : '申请源单据号',
					name : 'sourceNumb',
					sortable : true,
					width:180
				}, {
					display : '申请人',
					name : 'createName',
					sortable : true
				}, {
					display : '申请时间 ',
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
					location="?model=purchase_plan_basic&action=read&id="
							+ row.id + "&purchType=" + row.purchType+"&skey="+row['skey_'];
				} else {
					alert("请选中一条数据");
				}
			}

		},{
			name : 'sumbit',
			text : '审批',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '部门审批') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
					parent.location = "controller/purchase/plan/ewf_index.php?actTo=ewfExam&taskId="+row.taskId+"&spid="+row.spid+"&billId="+row.id+"&purchType="+row.purchType+"&examCode=oa_purch_plan_basic&skey="+row['skey_'];
			}

		}],
		// 快速搜索
		searchitems : [{
					display : '采购申请编号',
					name : 'seachPlanNumb'
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