var show_page = function(page) {
	$("#innoticeGrid").yxsubgrid("reload");
};
$(function() {
	$("#innoticeGrid").yxsubgrid({
		model : 'stock_withdraw_innotice',
		param : {docType : 'oa_produce_plan'},
		showcheckbox : false,
		isAddAction : false,
		isEditAction : false,
		isViewAction : true,
		isDelAction : false,
		title : '入库通知单',
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'noticeCode',
			display : '入库通知单号',
			sortable : true
		}, {
//			name : 'drawId',
//			display : '收货通知Id',
//			sortable : true,
//			hide : true
//		}, {
//			name : 'drawCode',
//			display : '收货通知编号',
//			width : 110,
//			sortable : true
//		}, {
			name : 'rObjCode',
			display : '源单业务编码',
			width : 110,
			sortable : true,
			hide : true
		}, {
			name : 'docType',
			display : '源单类型',
			process : function (v){
				if( v == 'oa_produce_plan' ){
					return '生产计划单'
				}
			},
			width : 70,
			sortable : true
		}, {
			name : 'docId',
			display : '源单号Id',
			sortable : true,
			hide : true
		}, {
			name : 'docCode',
			display : '源单号',
			width : 110,
			sortable : true
		}, {
			name : 'docStatus',
			display : '单据状态',
			width : 70,
			sortable : true,
			process : function(v){
				if(v == "YRK"){
					return '已入库';
				}else{
					return '未入库';
				}
			}
		}, {
			name : 'customerId',
			display : '客户Id',
			sortable : true,
			hide : true
		}, {
			name : 'customerName',
			display : '客户名称',
			width : 120,
			sortable : true
		}, {
			name : 'consignee',
			display : '收货人',
			width : 70,
			sortable : true
		}, {
			name : 'auditman',
			display : '审核人',
			width : 70,
			sortable : true
		}, {
			name : 'receiveDate',
			display : '收货日期',
			width : 70,
			sortable : true
		}, {
			name : 'isSign',
			display : '是否签收',
			width : 70,
			sortable : true
		}, {
			name : 'signman',
			display : '签收人',
			width : 70,
			sortable : true
		}, {
			name : 'signDate',
			display : '签收日期',
			width : 70,
			sortable : true
		}, {
			name : 'remark',
			display : '备注',
			sortable : true
		}],
		// 主从表格设置
		subGridOptions : {
			url : '?model=stock_withdraw_noticeequ&action=pageJson',
			param : [{
				paramId : 'mainId',
				colId : 'id'
			}],
			colModel : [{
				name : 'productCode',
				width : 100,
				display : '物料编号'
			}, {
				name : 'productName',
				width : 170,
				display : '物料名称'
			}, {
				name : 'productModel',
				width : 170,
				display : '型号/版本'
			}, {
				name : 'unitName',
				display : '单位',
				width : 70
			}, {
				name : 'number',
				display : '数量',
				width : 70
			}]
		},
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView',
			formHeight : 500,
			formWidth : 900
		},
		menusEx : [{
			name : 'bluepush',
			text : '下推入库单',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.docStatus == "WRK") {
					return true;
				} else {
					return false;
				}
			},
			action : function(row, rows, grid) {
				if (row) {
					// alert()
					showModalWin("index1.php?model=stock_instock_stockin&action=toBluePush&docType=RKPRODUCT&relDocType=RSCJHD&relDocId="
						+ row.id + "&relDocCode=" + row.noticeCode + "&docId=" + row.docId + "&rObjCode=" + row.rObjCode,1,row.id);
				} else {
					alert("请选中一条数据");
				}
			}
		}],
		//过滤数据
		comboEx : [{
			text : '单据状态',
			key : 'docStatus',
			value : 'WRK',
			data : [{
				text : '未入库',
				value : 'WRK'
			}, {
				text : '已入库',
				value : 'YRK'
			}]
		}],
		searchitems : [{
			display : "入库通知单号",
			name : 'noticeCodeSearch'
		}]
	});
});