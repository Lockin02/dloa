var show_page = function(page) {
	$("#tostorageGrid").yxsubgrid("reload");
};
$(function() {
	$("#tostorageGrid").yxsubgrid({
		model : 'projectmanagent_borrow_borrow',
		action : 'toStoragePageJson',
		param : {
           // 'isproShipcondition' : '0'
		},
		title : '待调拨员工借用',
		//按钮
		isViewAction : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		customCode : 'tostorage',
		//列信息
		colModel : [
			{
			name : 'DeliveryStatus',
			display : '',
			sortable : false,
			width : '20',
			align : 'center',
			process : function(v, row) {
				if (row.DeliveryStatus == 'YFH' || row.status == '3') {
					return "<img src='images/icon/icon073.gif' />";
				} else {
					return "<img src='images/icon/green.gif' />";
				}
			}
		},
		{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'Code',
			display : '编号',
			sortable : true,
			width : 180
		}, {
			name : 'Type',
			display : '类型',
			sortable : true,
			width : 60
		}, {
			name : 'limits',
			display : '范围',
			sortable : true,
			width : 60
		},{
			name : 'timeType',
			display : '借用期限',
			sortable : true,
			width : 60
		}, {
			name : 'reason',
			display : '申请理由',
			sortable : true,
			width : 200
		}, {
			name : 'remark',
			display : '备注',
			sortable : true,
			width : 200
		}, {
			name : 'ExaStatus',
			display : '审批状态',
			sortable : true,
			width : 90
		}, {
			name : 'ExaDT',
			display : '审批时间',
			sortable : true
		},/*{
			name : 'tostorage',
			display : '仓管确认',
			sortable : true,
			process : function(v){
  				if( v == '0'){
  					return "无需确认";
  				}else if(v == '1'){
  					return "确认中";
  				}else if(v == '2'){
  					return "确认完成";
  				}
  			}
		},*/{
			name : 'status',
			display : '单据状态',
			sortable : true,
			process : function(v){
  				if( v == '0'){
  					return "正常";
  				}else if(v == '1'){
  					return "部分归还";
  				}else if(v == '2'){
  					return "关闭";
  				}else if(v == '3'){
  				    return "退回";
  				}else if(v == '4'){
  				    return "续借申请中"
  				}else if(v == '5'){
  				    return "转至执行部"
  				}else if(v == '6'){
  				    return "转借确认中"
  				}
  			}
		},{
			name : 'backStatus',
			display : '归还状态',
			sortable : true,
			process : function(v){
  				if( v == '0'){
  					return "未归还";
  				}else if(v == '1'){
  					return "已归还";
  				}else if(v == '2'){
  					return "部分归还";
  				}
  			}
		}
		,{
			name : 'DeliveryStatus',
			display : '发货状态',
			sortable : true,
			process : function(v){
  				if( v == 'WFH'){
  					return "未发货";
  				}else if(v == 'YFH'){
  					return "已发货";
  				}else if(v == 'BFFH'){
	                return "部分发货";
	            }
  			}
		}
		,{
			name : 'createName',
			display : '申请人',
			sortable : true

		},{
			name : 'objCode',
			display : '业务编码',
			sortable : true

		}],
		event : {
			afterloaddata : function(e, data) {
				if (data) {
					for (var i = 0; i < data.collection.length; i++) {
						if(data.collection[i].tostorage == 1){
							$('#row' + data.collection[i].id).css('color', 'blue');
						}
					}
				}
			}
		},
		comboEx : [{
					text : '发货状态',
					key : 'DeliveryStatus',
					data : [{
								text : '未发货',
								value : '0'
							}, {
								text : '已发货',
								value : '1'
							}, {
								text : '部分发货',
								value : '2'
							}]
				}],
		// 主从表格设置
		//主从表中加了个字段   物料版本/型号   2013.7.5
						subGridOptions : {
							subgridcheck : true,
							url : '?model=common_contract_allsource&action=equJson',// 获取从表数据url
							// 传递到后台的参数设置数组
							param : [ {
								'docType' : 'oa_borrow_borrow'
							}, {
								paramId : 'borrowId',// 传递给后台的参数名称
								colId : 'id'// 获取主表行数据的列名称
							} ],
							// 显示的列
							colModel : [ {
								name : 'productName',
								width : 200,
								display : '物料名称'
							}, {
								name : 'productModel',
								width : 150,
								display : '物料版本/型号'
							}, {
								name : 'number',
								display : '数量',
								width : 40
							}, {
								name : 'lockNum',
								display : '锁定数量',
								width : 50,
								process : function(v) {
									if (v == '') {
										return 0;
									} else
										return v;
								}
							}, {
								name : 'exeNum',
								display : '库存数量',
								width : 50,
								process : function(v) {
									if (v == '') {
										return 0;
									} else
										return v;
								}
							}, {
								name : 'issuedShipNum',
								display : '已下达发货数量',
								width : 90,
								process : function(v) {
									if (v == '') {
										return 0;
									} else
										return v;
								}
							}, {
								name : 'executedNum',
								display : '已发货数量',
								width : 60
							}, {
								name : 'issuedPurNum',
								display : '已下达采购数量',
								width : 90,
								process : function(v) {
									if (v == '') {
										return 0;
									} else
										return v;
								}
							}, {
								name : 'issuedProNum',
								display : '已下达生产数量',
								width : 90,
								process : function(v) {
									if (v == '') {
										return 0;
									} else
										return v;
								}
							}, {
								name : 'backNum',
								display : '退库数量',
								width : 60
							}, {
								name : 'projArraDate',
								display : '计划交货日期',
								width : 80,
								process : function(v) {
									if (v == null) {
										return '无';
									} else {
										return v;
									}
								}
							} ]
						},
		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '编号',
			name : 'Code'
		},{
			display : '申请人',
			name : 'createName'
		}],
		// 扩展右键菜单
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row) {
				if (row.limits == '员工') {
					showOpenWin("?model=projectmanagent_borrow_borrow&action=proDisViewTab&id="
							+ row.id + "&skey=" + row['skey_']);
				}else{
				    showOpenWin("?model=projectmanagent_borrow_borrow&action=init&perm=view&id="
							+ row.id + "&skey=" + row['skey_']);
				}
			}

		},{
			text : '领料提醒',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.limits == '客户' || row.DeliveryStatus == '1' || row.tostorage == '1' || row.status == '2' || row.status == '3' || row.status == '5'|| row.status == '6') {
					return false;
				}
				return true;
			},
			action : function(row, rows, grid) {
				if (row) {
					$.ajax({
						type : "POST",
						url : "?model=projectmanagent_borrow_borrow&action=toRemindMail&id=" + row.id,
						success : function(msg) {
							if (msg == 1) {
								alert('提醒邮件发送成功！');
								$("#tostorageGrid").yxsubgrid("reload");
							}else{
							    alert('更新失败！');
							}
						}
					});
				} else {
					alert("请选中一条数据");
				}

			}
		}
//		,{
//			text : '确认物料',
//			icon : 'edit',
//			showMenuFn : function(row) {
//				if (row.tostorage == '1' ) {
//					return true;
//				}
//				return false;
//			},
//			action : function(row, rows, grid) {
//				if (row) {
//
//					showOpenWin("?model=projectmanagent_borrow_borrow&action=storageEdit&id="
//							+ row.id
//							+ "&skey="
//							+ row['skey_']
//							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900")
//				} else {
//					alert("请选中一条数据");
//				}
//			}
//		}
		, {
			text : '下推调拨单',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.DeliveryStatus == '1' || row.tostorage == '1' || row.status == '2' || row.status == '3' || row.status == '5'|| row.status == '6') {
					return false;
				}
				return true;
			},
			action : function(row, rows, grid) {
				if (row) {

					showModalWin("?model=stock_allocation_allocation&action=toBluePush&relDocType=DBDYDLXJY&relDocId="
							+ row.id
							+ "&skey="
							+ row['skey_']
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900")
				} else {
					alert("请选中一条数据");
				}

			}
		}
		,{
			text : '借试用处理',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.DeliveryStatus == '1' || row.tostorage == '1'|| row.status == '3' || row.status == '2' || row.status == '5'|| row.status == '6') {
					return false;
				}
				return true;
			},
			action : function(row, rows, grid) {
				if (row) {

					showThickboxWin("?model=projectmanagent_borrow_borrow&action=borrowDispose&id="
							+ row.id
							+ "&skey="
							+ row['skey_']
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=700")
				} else {
					alert("请选中一条数据");
				}
			}
		}
		]
	});

});