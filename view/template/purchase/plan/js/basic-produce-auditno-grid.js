// 用于新增/修改后回调刷新表格
var show_page = function(page) {
	$("#basicNoGrid").yxsubgrid("reload");
};
$(function() {
	$("#basicNoGrid").yxsubgrid({
		// 如果传入url，则用传入的url，否则使用model及action自动组装
		// url :
		model : 'purchase_plan_basic',
		action : 'myAuditPj',
		title : '待审批采购申请列表',
		isToolBar : false,
		showcheckbox : true,
		param:{'purchType':"produce"},

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
					display : '审批状态',
					name : 'ExaStatus',
					sortable : true
				}, {
					display : '申请人',
					name : 'createName',
					sortable : true
				}, {
					display : '申请时间 ',
					name : 'sendTime',
					sortable : true
				}, {
					display : '希望完成时间 ',
					name : 'dateHope',
					sortable : true
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
						display : '物料名称'
					},{
						name : 'pattem',
						display : "规格型号"
					},{
						name : 'amountAll',
						display : "申请数量",
						width : 60
					},{
						name : 'dateHope',
						display : "希望完成日期"
					}]
		},

		//扩展按钮
		buttonsEx : [{
			name : 'sumbit',
			text : '审批',
			icon : 'edit',
			action : function(row, rows, grid) {
				if(row){
					parent.location = "controller/purchase/plan/ewf_produce_index.php?actTo=ewfExam&taskId="+row.taskId+"&spid="+row.spid+"&billId="+row.id+"&examCode=oa_purch_plan_basic&skey="+row['skey_'];
				}else{
					alert("请选择一条记录");
				}

			}
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
					parent.location = "controller/purchase/plan/ewf_produce_index.php?actTo=ewfExam&taskId="+row.taskId+"&spid="+row.spid+"&billId="+row.id+"&examCode=oa_purch_plan_basic&skey="+row['skey_'];
			}

		}],
		// 快速搜索
		searchitems : [{
					display : '采购申请编号',
					name : 'seachPlanNumb'
				},{
					display : '物料编号',
					name : 'productNumb'
				},{
					display : '物料名称',
					name : 'productName'
				},{
					display : '申请人',
					name : 'createName'
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