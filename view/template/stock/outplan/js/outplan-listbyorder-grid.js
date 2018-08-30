var show_page = function(page) {
	$("#listbyorderGrid").yxsubgrid("reload");
};
$(function() {
	$("#listbyorderGrid").yxsubgrid({
		model : 'stock_outplan_outplan',
		action : 'pageByOrderId',
		param : {
			docId : $('#docId').val(),
			docType : $('#docType').val()
		},
		title : '发货计划',
		showcheckbox :false,
		isAddAction : false,
		isEditAction : false,
		isViewAction : false,
		isDelAction : false,
		customCode : 'listbyorderGrid',
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'week',
			display : '周次',
			width : 50,
			hide : true,
			sortable : true
		}, {
			name : 'customerName',
			display : '客户名称',
			width : 150,
			sortable : true
		}, {
			name : 'planCode',
			display : '计划编号',
			width : 90,
			hide : true,
			sortable : true
		}, {
			name : 'docId',
			display : '源单Id',
			hide : true,
			sortable : true
		}, {
			name : 'docCode',
			display : '源单号',
			width : 180,
			sortable : true
		}, {
			name : 'docName',
			display : '源单名称',
			width : 180,
			hide : true,
			sortable : true
		}, {
			name : 'docType',
			display : '发货类型',
			sortable : true,
			width : 60,
			process : function(v) {
				if (v == 'oa_contract_contract') {
					return "合同发货";
				}else if (v == 'oa_contract_exchangeapply'){
				    return "换货发货";
				}else if (v == 'oa_borrow_borrow'){
				    return "借用发货";
				}else if (v == 'oa_present_present'){
				    return "赠送发货";
				}
			}
		}, {
			name : 'isTemp',
			display : '是否变更',
			width : 60,
			process : function(v){
					(v == '1')? (v = '是'):(v = '否');
					return v;
			},
			sortable : true
		}, {
			name : 'planIssuedDate',
			display : '下达日期',
			width : 75,
			sortable : true,
			hide : true
		}, {
			name : 'stockName',
			display : '发货仓库',
			sortable : true,
			hide : true
		}, {
			name : 'type',
			display : '性质',
			datacode : 'FHXZ',
			width : 70,
			sortable : true,
			hide : true
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
			name : 'deliveryDate',
			display : '交货日期',
			width : 75,
			sortable : true
		}, {
			name : 'shipPlanDate',
			display : '计划发货日期',
			width : 75,
			sortable : true
		}, {
			name : 'status',
			display : '单据状态',
			width : 70,
			process : function(v){
				if( v == 'YZX' ){
					return "已执行";
				}else if( v == 'BFZX' ){
					return "部分执行";
				}else if( v == 'WZX' ){
					return "未执行";
				}else{
					return "未执行";
				}
			},
			sortable : true
		}, {
			name : 'isOnTime',
			display : '是否按时发货',
			width : 80,
			process : function(v){
					(v == '1')? (v = '是'):(v = '否');
					return v;
			},
			sortable : true
		}, {
			name : 'issuedStatus',
			display : '下达状态',
			width : 60,
			process : function(v){
					(v == '1')? (v = '已下达'):(v = '未下达');
					return v;
			},
			sortable : true
		}, {
			name : 'docStatus',
			display : '发货状态',
			width : 70,
			process : function(v){
				if( v == 'YWC' ){
					return "已发货";
				}else if( v == 'BFFH' ){
					return "部分发货";
				}else if( v == 'YGB' ){
					return "停止发货";
				}else
					return "未发货";
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
		subGridOptions : {
			url : '?model=stock_outplan_outplan&action=byOutplanJson',// 获取从表数据url
			// 传递到后台的参数设置数组
			param : [{
						paramId : 'mainId',// 传递给后台的参数名称
						colId : 'id'// 获取主表行数据的列名称
					}],
			// 显示的列
			colModel : [{
						name : 'productNo',
						width : 150,
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
					    name : 'number',
					    display : '数量',
						width : 50
					},{
					    name : 'unitName',
					    display : '单位',
						width : 50
					},{
					    name : 'executedNum',
					    display : '已发货数量',
						width : 60
					}]
		},


		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row) {
				showThickboxWin('?model=stock_outplan_outplan&action=toView&id='
						+ row.id
						+ '&docType='
						+ row.docType
						+ '&skey='
						+ row['skey_']
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=700');
			}
		}],
		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '计划编号',
			name : 'planCode'
		}]
	});
});