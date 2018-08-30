var show_page = function(page) {
	$("#arrivalGrid").yxgrid("reload");
};
$(function() {
	$("#arrivalGrid").yxgrid({
		model : 'purchase_arrival_arrival',
		action:'stockPageDetailJson',
		title : '待入库收料通知单',
		showcheckbox : false,
		isEditAction : false,
		isViewAction : false,
		isDelAction : false,
		isAddAction : false,
		param : {
			'stateArr' : "0,4","arrivalType":"ARRIVALTYPE1","isCanInstockEqu":"1"
		},
		// 列信息
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'arrivalCode',
					display : '收料单号',
					sortable : true,
					width : 80
				}, {
					name : 'state',
					display : '收料通知单状态',
					sortable : true,
					process : function(v, row) {
						if (row.state == '0') {
							return "未执行";
						} else if (row.state == '4'){
							return "部分执行";
						}else if (row.state == '2'){
							return "已执行";
						}
					},
					hide : true
				}, {
					name : 'arrivalDate',
					display : '收料日期',
					sortable : true,
					width : 80
				}, {
					name : 'sequence',
					display : '物料编码',
					sortable : true,
					width : 80
				}, {
					name : 'productName',
					display : '物料名称',
					sortable : true,
					width : 130
				}, {
					name : 'pattem',
					display : '规格型号',
					sortable : true,
					width : 110
				}, {
					name : 'arrivalNum',
					display : '收料数量',
					sortable : true,
					width : 70
				}, {
					name : 'storageNum',
					display : "已入库数量",
					sortable : true,
					width : 70
				}, {
					name : 'qualityName',
					display : "采购属性",
					width:'60'
				}, {
					name : 'qualityPassNum',
					display : "质检合格数量",
					width:'80'
				}, {
					name : 'completionTime',
					display : "质检完成时间",
					width:'120'
				},{
					name : 'purchaseCode',
					display : '采购订单编号',
					sortable : true,
					width : 150
				}, {
					name : 'arrivalType',
					display : '收料类型',
					sortable : true,
					datacode : 'ARRIVALTYPE',
					width : 80,
					hide : true
				}, {
					name : 'supplierName',
					display : '供应商名称',
					sortable : true,
					width : 150
				}, {
					name : 'purchManName',
					display : '采购员',
					sortable : true,
					width : 70
				}, {
					name : 'purchMode',
					display : '采购方式',
					hide : true,
					datacode : 'cgfs'
				},{
					name : 'stockName',
					display : '收料仓库名称',
					sortable : true
				}],
		// 扩展右键菜单
		menusEx : [{
			name : 'view',
			text : '查看',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=purchase_arrival_arrival&action=init&perm=view&id="
							+ row.arrivalId
							+ "&skey="
							+ row['skey_']
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=550&width=900");
				} else {
					alert("请选中一条数据");
				}
			}
		}, {
			name : 'bluepush',
			text : '下推入库单',
			icon : 'business',
			action : function(row, rows, grid) {
				if (row) {
					// alert()
					showModalWin("index1.php?model=stock_instock_stockin&action=toBluePush&docType=RKPURCHASE&relDocType=RSLTZD&relDocId="
							+ row.arrivalId + "&relDocCode=" + row.arrivalCode);
				} else {
					alert("请选中一条数据");
				}
			}
		}, {
			name : 'bluepush',
			text : '下推9月前入库单',
			icon : 'business',
			showMenuFn : function(row) {
				if ($("#importLimit").val() == "1") {
					return true;
				} else {
					return false;
				}

			},
			action : function(row, rows, grid) {
				if (row) {
					// alert()
					showModalWin("index1.php?model=stock_instock_stockin&action=toPreSepPush&docType=RKPURCHASE&relDocType=RSLTZD&relDocId="
							+ row.arrivalId + "&relDocCode=" + row.arrivalCode);
				} else {
					alert("请选中一条数据");
				}
			}
		},
			{
			    text:'关闭',
			    icon:'delete',
			    action:function(row,rows,grid){
			    	if(row){
			    		if(window.confirm("确认要关闭?")){
			    		     $.ajax({
			    		         type:"POST",
			    		         url:"?model=purchase_arrival_arrival&action=changStateClose",
			    		         data:{
			    		         	id:row.arrivalId
			    		         },
			    		         success:function(msg){
			    		            if(msg==1){
			    		                alert('关闭成功!');
			    		                show_page();
			    		            }
			    		         }
			    		     });
			    		}
			    	}
			    }
			}],
		searchitems : [{
			display : '收料单号',
			name : 'arrivalCode'
		}, {
			display : '采购员',
			name : 'purchManName'
		}, {
			display : '供应商',
			name : 'supplierName'
		},{
			display : '物料名称',
			name : 'productName'
			},{
			display : '物料编号',
			name : 'sequence'
			},{
			display : '采购订单编号',
			name : 'purchaseCodeSearch'
		}],
		// 默认搜索顺序
		sortorder : "DESC",
		sortname : "updateTime"
	});
});