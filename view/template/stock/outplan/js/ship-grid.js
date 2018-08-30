var show_page = function(page) {
	$("#shipGrid").yxgrid("reload");
};
$(function() {
	$("#shipGrid").yxgrid({
		model : 'stock_outplan_ship',
		title : '发货单',
		showcheckbox : false,
		isAddAction : false,
		isEditAction : false,
		isViewAction : false,
		isDelAction : false,
		customCode : 'shipGrid',

		// 扩展按钮
		buttonsEx : [{
			name : 'import',
			text : '发货单新增',
			icon : 'add',
			action : function(row, rows, grid) {
				showThickboxWin("?model=stock_outplan_ship&action=toAddWithoutPlan"
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=950");
			}
		}],

		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row) {
				showThickboxWin('?model=stock_outplan_ship&action=toView&id='
						+ row.id
						+ '&skey='
						+ row['skey_']
						+ '&docType='
						+ row.docType
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=550&width=800');
			}
		}, {
			text : '查看邮寄信息',
			icon : 'view',
			action : function(row) {
				showModalWin('?model=mail_mailinfo&action=listByShip&docId='
						+ row.id);
			}
		}, {
			text : '打印',
			icon : 'view',
			action : function(row) {
				showThickboxWin('?model=stock_outplan_ship&action=toPrint&id='
						+ row.id
						+ '&skey='
						+ row['skey_']
						+ '&docType='
						+ row.docType
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=900');
			}
		}, {
			text : '编辑',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.shipStatus == "2") {
					return false;
				} else if (row.isSign == "1") {
					return false;
				} else {
					return true;
				}

			},
			action : function(row) {
				showThickboxWin('?model=stock_outplan_ship&action=toEdit&id='
						+ row.id
						+ '&skey='
						+ row['skey_']
						+ '&docType='
						+ row.docType
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=1100');
			}
		}, {
			text : '签收',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.shipStatus == "2" && row.isSign != 1) {
					return true;
				} else if (row.shipStatus == "0" && row.isSign != 1) {
					return true;
				} else {
					return false;
				}
			},
			action : function(row) {
				showThickboxWin('?model=stock_outplan_ship&action=toSign&id='
						+ row.id
						+ '&skey='
						+ row['skey_']
						+ '&docType='
						+ row.docType
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=1100');
			}
		}, {
			text : '导出',
			icon : 'edit',
			action : function(row) {
				window
						.open('?model=stock_outplan_ship&action=toExportExcel&id='
								+ row.id
								+ '&skey='
								+ row['skey_']
								+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=700');
			}
		}, {
			text : "删除",
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.shipStatus == "2") {
					return false;
				} else if (row.isSign == "1") {
					return false;
				} else {
					return true;
				}
			},
			action : function(row) {
				if (confirm('确定要删除发货单？')) {
					$.ajax({
						type : 'POST',
						url : '?model=stock_outplan_ship&action=ajaxDelete&skey='
								+ row['skey_'],
						data : {
							id : row.id
						},
						success : function(data) {
							if (data == 0) {
								alert("删除失败");
							} else {
								alert("删除成功");
								show_page();
							}
							return false;
						}
					});
				}
			}
		}],
		comboEx : [{
					text : '发货方式',
					key : 'shipType',
					data : [{
								text : '发货',
								value : 'order'
							}, {
								text : '借用',
								value : 'borrow'
							}, {
								text : '租用',
								value : 'lease'
							}, {
								text : '试用',
								value : 'trial'
							}, {
								text : '更换',
								value : 'change'
							}]
				}, {
					text : '发货状态',
					key : 'shipStatus',
					data : [{
								text : '未发货',
								value : 1
							}, {
								text : '已发货',
								value : 2
							}, {
								text : '- - - -',
								value : 0
							}]
				}],
		// 列信息
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'shipCode',
					display : '发货单号',
					width : 120,
					sortable : true,
					hide : true
				}, {
					name : 'customerName',
					display : '客户名称',
					width : 180,
					sortable : true
				}, {
					name : 'planCode',
					display : '发货计划号',
					width : 90,
					sortable : true,
					hide : true
				}, {
					name : 'docType',
					display : '源单类型',
					sortable : true,
					width : 60,
					process : function(v, row, g) {
						if (v == 'oa_contract_contract') {
							if( row.contractTypeName == '' ){
								return "合同发货";
							}else{
								return row.contractTypeName;
							}
						} else if (v == 'oa_borrow_borrow') {
							return "借用发货";
						} else if (v == 'oa_service_accessorder') {
							return "配件订单";
						} else if (v == 'oa_service_repair_apply') {
							return "维修申请单";
						}
					}
				}, {
					name : 'rObjCode',
					display : '关联业务编号',
					width : 120,
					sortable : true,
					hide : true
				}, {
					name : 'docCode',
					display : '源单编号',
					width : 180,
					sortable : true
				}, {
					name : 'customerContCode',
					display : '客户合同号',
					width : 120,
					sortable : true,
					hide : true
				}, {
					name : 'shipType',
					display : '发货方式',
					sortable : true,
					width : 60,
					process : function(v) {
						if (v == 'order') {
							return "发货";
						} else if (v == 'borrow') {
							return "借用";
						} else if (v == 'lease') {
							return "租用";
						} else if (v == 'trial') {
							return "试用";
						} else if (v == 'change') {
							return "更换";
						}
					}

				}, {
					name : 'shipStatus',
					display : '发货状态',
					process : function(v) {
						if (v == '2') {
							return "已发货";
						} else if (v == '1') {
							return '未发货';
						} else {
							return '- - - -';
						}
					},
					width : 60,
					sortable : true
				}, {
					name : 'shipDate',
					display : '发货日期',
					width : 75,
					sortable : true
				}, {
					name : 'isSign',
					display : '是否签收',
					process : function(v) {
						(v == '1') ? (v = '是') : (v = '否');
						return v;
					},
					width : 60,
					sortable : true
				}, {
					name : 'shipman',
					display : '发货人',
					width : 80,
					sortable : true
				}, {
					name : 'outstockman',
					display : '出库人',
					width : 80,
					sortable : true
				}, {
					name : 'auditman',
					display : '审核人',
					width : 80,
					sortable : true
				}, {
					name : 'signDate',
					display : '签收日期',
					hide : true,
					sortable : true
				}],
		/**
		 * 快速搜索
		 */
		searchitems : [{
					display : '发货单号',
					name : 'shipCode'
				}, {
					display : '发货计划号',
					name : 'planCode'
				}, {
					display : '关联业务单编号',
					name : 'rObjCode'
				}, {
					display : '鼎利合同号',
					name : 'docCode'
				}, {
					display : '备注',
					name : 'itemRemark'
				}]
	});
});