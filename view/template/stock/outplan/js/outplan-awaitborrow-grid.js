var show_page = function(page) {
	$("#awaitborrowGrid").yxsubgrid("reload");
};
$(function() {
	$("#awaitborrowGrid").yxsubgrid({
		model : 'stock_outplan_outplan',
		action : 'awaitJsonBorrow',
		param : {
			'docStatusArr' : ['WFH', 'BFFH'],
			'docTypeArr' : ['oa_borrow_borrow']
		},
		title : '借试用发货计划',
		showcheckbox : false,
		isAddAction : false,
		isEditAction : false,
		isViewAction : false,
		isDelAction : false,
		// 列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'planCode',
			display : '计划编号',
			sortable : true
		}, {
			name : 'rObjCode',
			display : '关联业务编号',
			width : 120,
			sortable : true
		}, {
			name : 'docId',
			display : '合同Id',
			hide : true,
			sortable : true
		}, {
			name : 'docCode',
			display : '借试用编号',
			href : '?model=projectmanagent_borrow_borrow&action=init&perm=view&id=',
			hrefCol : 'docId',
			width : 160,
			sortable : true
		}, {
//			name : 'docName',
//			display : '借试用名称',
//			width : 160,
//			sortable : true
//		}, {
			name : 'createName',
			display : '申请人',
			width : 80,
			sortable : true
		}, {
			name : 'createId',
			display : '申请人Id',
			hide : true,
			sortable : true
		}, {
			name : 'createSection',
			display : '申请部门',
			sortable : true
		}, {
			name : 'createSectionId',
			display : '申请部门Id',
			hide : true,
			sortable : true
		}, {
			name : 'week',
			display : '周次',
			width : 40,
			sortable : true
		}, {
			name : 'planIssuedDate',
			display : '下达日期',
			width : 70,
			sortable : true
		}, {
			name : 'docType',
			display : '发货类型',
			sortable : true,
			width : 60,
			process : function(v) {
				if (v == 'oa_sale_order') {
					return "销售发货";
				} else if (v == 'oa_sale_lease') {
					return "租赁发货";
				} else if (v == 'oa_sale_service') {
					return "服务发货";
				} else if (v == 'oa_sale_rdproject') {
					return "研发发货";
				} else if (v == 'oa_borrow_borrow') {
					return "借用发货";
				}
			}
		}, {
			name : 'stockName',
			display : '发货仓库',
			sortable : true
		}, {
			name : 'type',
			display : '性质',
			width : 40,
			datacode : 'FHXZ',
			sortable : true
		}, {
			name : 'purConcern',
			display : '采购人员关注重点',
			hide : true,
			sortable : true
		}, {
			name : 'shipConcern',
			display : '发货人员关注',
			hide : true,
			sortable : true
		}, {
			name : 'issuedStatus',
			display : '下达状态',
			hide : true,
			process : function(v) {
				(v == '1') ? (v = '是') : (v = '否');
				return v;
			},
			sortable : true
		}, {
			name : 'docStatus',
			display : '状态',
			width : 50,
			process : function(v) {
				if (v == 'YWC') {
					return "已完成";
				} else if (v == 'WFH') {
					return "未发货";
				} else
					return "部分发货";
			},
			sortable : true
		}, {
			name : 'shipPlanDate',
			display : '计划发货日期',
			width : 70,
			sortable : true
		}, {
			name : 'isOnTime',
			display : '按时发货',
			width : 60,
			process : function(v) {
				(v == '1') ? (v = '是') : (v = '否');
				return v;
			},
			sortable : true
		}, {
			name : 'delayType',
			display : '延期原因归类',
			hide : true,
			sortable : true
		}, {
			name : 'delayReason',
			display : '未发具体原因',
			hide : true,
			sortable : true
		}],
		// 主从表格设置
		//主从表中加了个字段   规格型号   2013.7.5
		subGridOptions : {
			url : '?model=stock_outplan_outplan&action=byOutplanJson',
			param : [{
				paramId : 'mainId',
				colId : 'id'
			}],
			colModel : [{
				name : 'productNo',
				width : 100,
				display : '产品编号'
			}, {
				name : 'productName',
				width : 200,
				display : '产品名称'
			}, {
				name : 'productModel',
				width : 200,
				display : '规格型号'
			},{
				name : 'number',
				display : '数量',
				width : 50
			}, {
				name : 'unitName',
				display : '单位',
				width : 50
			}, {
				name : 'executedNum',
				display : '已发货数量',
				width : 60
			}]
		},

		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row) {
				showOpenWin('?model=stock_outplan_outplan&action=toView&id='
						+ row.id
						+ '&docType='
						+ row.docType
						+ '&skey='
						+ row['skey_']
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=700');
			}
		}, {
			name : 'business',
			text : "下推调拨单",
			icon : 'business',
			showMenuFn : function(row) {
				return true;
			},
			action : function(row, rows, grid) {
				showModalWin("?model=stock_allocation_allocation&action=toBluePush&relDocId="
						+ row.id + "&relDocType=DBDYDLXFH")
			}
		}],
		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '计划编号',
			name : 'planCode'
		}, {
			display : '关联业务单编号',
			name : 'rObjCode'
		}, {
			display : '源单号',
			name : 'docCode'
		}]
	});
});