var show_page = function(page) {
	$("#awaitGrid").yxsubgrid("reload");
};
$(function() {
	$("#awaitGrid").yxsubgrid({
		model : 'stock_outplan_outplan',
		action : 'awaitJson',
		param : {
			'docStatusArr' : ['WFH', 'BFFH'],
			'docTypeArr' : ['oa_contract_contract', 'oa_present_present','oa_contract_exchangeapply'],
			'issuedStatus' : '1',
			'isNeedConfirm' : '0'
		},
		title : '发货计划',
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
					display : '源单号',
					width : 160,
					sortable : true
				}, {
					name : 'docName',
					display : '源单名称',
					width : 160,
					sortable : true
				}, {
					name : 'week',
					display : '周次',
					width : 40,
					sortable : true
				}, {
					name : 'planIssuedDate',
					display : '下达日期',
					width : 80,
					sortable : true
				}, {
					name : 'docType',
					display : '发货类型',
					width : 60,
					sortable : true,
					process : function(v, row, g) {
						if (v == 'oa_contract_exchangeapply') {
							return "换货发货";
						} else if (v == 'oa_contract_contract') {
							if( row.contractTypeName == '' ){
								return "合同发货";
							}else{
								return row.contractTypeName;
							}
						} else if (v == 'oa_borrow_borrow') {
							return "借用发货";
						} else if (v == 'oa_present_present') {
							return "赠送发货";
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
					width : 60,
					process : function(v) {
						return v = '是';
					},
					sortable : true
				}, {
					name : 'docStatus',
					display : '状态',
					width : 60,
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
					width : 80,
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
						display : '产品名称',
						process : function(v, data, rowData,$row) {
							if (data.BToOTips == 1) {
								$row.attr("title", "该物料为借试用转销售的物料");
								return "<img src='images/icon/icon147.gif' />"+v;
							}
							return v;
						}
					}, {
						name : 'productModel',
						display : "规格型号",
						width : 150
					}, {
						name : 'productType',
						display : '规格类型',
						width : 150
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
			text : "下推出库单",
			icon : 'business',
			showMenuFn : function(row) {
				// if (row.docStatus == "YSH" && row.isRed == "0") {
				return true;
				// }
				// return false;
			},//TODO
			action : function(row, rows, grid) {
//				alert(row.docType);
				if(row.docType != "oa_contract_contract"){
					showModalWin("?model=stock_outstock_stockout&action=toBluePush&relDocId="
							+ row.id + "&docType=CKOTHER&relDocType=QTCKFHJH")
				}else{
					showModalWin("?model=stock_outstock_stockout&action=toBluePush&relDocId="
							+ row.id + "&docType=CKSALES&relDocType=XSCKFHJH")
				}
			}
		}],
		comboEx : [{
					text : '发货类型',
					key : 'docType',
					data : [{
								text : '换货发货',
								value : 'oa_contract_exchangeapply'
							}, {
								text : '合同发货',
								value : 'oa_contract_contract'
							}, {
								text : '借用发货',
								value : 'oa_borrow_borrow'
							}, {
								text : '赠送发货',
								value : 'oa_present_present'
							}]
				}],
		/**
		 * 快速搜索
		 */
		searchitems : [{
					display : '计划编号',
					name : 'planCode'
				}, {
					display : '关联业务编号',
					name : 'rObjCode'
				}, {
					display : '源单号',
					name : 'docCode'
				}]
	});
});