// 用于新增/修改后回调刷新表格
var show_page = function(page) {
	$(".serviceContractUnDoGrid").yxgrid("reload");
};
$(function() {
	$(".serviceContractUnDoGrid").yxgrid({
		//如果传入url，则用传入的url，否则使用model及action自动组装

		model : 'engineering_serviceContract_serviceContract',
		action : 'myPagejson',
		param : {
			'ExaStatusArr' : '部门审批,打回',
			"orderPrincipalId" : $("#userId").val()
		},
		title : '在审批的服务合同',
		showcheckbox : false,
		isToolBar : false,
		customCode : 'serlistundo',
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'orderCode',
			display : '鼎利合同号',
			sortable : true,
			width : 210
		}, {
			name : 'orderTempCode',
			display : '临时合同号',
			sortable : true,
			width : 210
		}, {
			name : 'orderName',
			display : '合同名称',
			sortable : true,
			width : 210
		}, {
			name : 'cusName',
			display : '客户名称',
			sortable : true,
			width : 150
		}, {
			name : 'orderTempMoney',
			display : '预计合同金额',
			sortable : true,
			width : 100,
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			name : 'orderMoney',
			display : '签约合同金额',
			sortable : true,
			width : 100,
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			name : 'areaName',
			display : '归属区域',
			sortable : true,
			width : 100
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
				}
			},
			width : 90
		}, {
			name : 'ExaStatus',
			display : '审批状态',
			sortable : true,
			width : 100
		}, {
			name : 'sign',
			display : '是否签约',
			sortable : true,
			width : 70
		}, {
			name : 'orderstate',
			display : '纸质合同状态',
			sortable : true,
			width : 100
		}, {
			name : 'parentOrder',
			display : '父合同名称',
			sortable : true,
			hide : true
		}, {
					name : 'objCode',
					display : '业务编号',
					width : 150
				}],

		//扩展右键菜单
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					showOpenWin("?model=engineering_serviceContract_serviceContract&action=toViewTab&id="
							+ row.id + "&skey=" + row['skey_'])
				} else {
					alert("请选中一条数据");
				}
			}

		}, {
			text : '编辑',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '未审批' || row.ExaStatus == '打回') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showOpenWin("?model=engineering_serviceContract_serviceContract&action=init&id="
							+ row.id + "&skey=" + row['skey_'])
				} else {
					alert("请选中一条数据");
				}
			}
		}

		, {
			text : '提交审核',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.ExaStatus == '未审批' || row.ExaStatus == '打回') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin('controller/engineering/serviceContract/ewf_index.php?actTo=ewfSelect&billId='
							+ row.id
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
				}
			}
		}, {
			text : '审批情况',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.ExaStatus == '保存') {
					return true;
				}
				return true;
			},
			action : function(row) {

				showThickboxWin('controller/engineering/serviceContract/readview.php?itemtype=oa_sale_service&pid='
						+ row.id
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');

			}
		}, {
			text : '导出',
			icon : 'add',
//			showMenuFn : function (row){
//				   if(row.exportOrder == 1){
//				       return true;
//				   }
//				       return false;
//				},
			action : function(row) {
				window
						.open('?model=contract_common_allcontract&action=importCont&id='
								+ row.id
								+ '&type=oa_sale_service'
								+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
			}
		},{
			text : '附件上传',
			icon : 'add',
			action: function(row){
				     showThickboxWin ('?model=projectmanagent_order_order&action=toUploadFile&id='
				                      + row.id
				                      +'&type=oa_sale_service'
				                      + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=200&width=600');
			}
		   }],
		//快速搜索
		searchitems : [{
			display : '合同名称',
			name : 'orderName'
		}, {
			display : '合同编号',
			name : 'orderCodeOrTempSearch'
		}, {
					name : 'objCode',
					display : '业务编号',
					width : 150
				}],
		// title : '客户信息',
		//业务对象名称
		//						boName : '供应商联系人',
		//默认搜索字段名
		sortname : "orderName",
		//默认搜索顺序
		sortorder : "ASC",
		//显示查看按钮
		isViewAction : false,
		//						isAddAction : true,
		isEditAction : false,
		isDelAction : false
			//						//查看扩展信息
			//						toViewConfig : {
			//							action : 'toRead',
			//							formWidth : 400,
			//							formHeight : 340
			//						},

			//由于涉及到工作流的跳转问题，于2010年12月27日注释
			//在弹出的窗口对审批工作流提交审批后，与在主窗口提交，在跳转后的处理上暂时无法兼容处理。
			//						toAddConfig : {
			//									text : '新建',
			//									icon : '',
			//									/**
			//									 * 默认点击新增按钮触发事件
			//									 */
			//
			//									toAddFn : function(p) {
			////										showThickboxWin("?model=engineering_serviceContract_serviceContract&action=toAddContract" +
			////												"&placeValuesBefore&TB_iframe=true&modal=false&height=650&width=950");
			//										showOpenWin("?model=engineering_serviceContract_serviceContract&action=toAddContract2");
			//
			//									}
			//						}

	});

});