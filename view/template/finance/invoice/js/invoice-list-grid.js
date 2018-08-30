var show_page=function(page){
   $("#invoiceGrid").yxgrid("reload");
};

$(function(){
    $("#invoiceGrid").yxgrid({
    	model:'finance_invoice_invoice',
    	title:'开票管理',
    	isToolBar:true,
    	isEditAction : false,
    	isDelAction : false,
    	showcheckbox:false,
		customCode : 'invoiceGrid',
		isOpButton : false,
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			},{
				display : '申请单号',
				name : 'applyNo',
				hide : true
			}, {
				display : '发票号码',
				name : 'invoiceNo',
				sortable : true,
				process : function(v,row){
					if(row.isRed == 0){
						return "<a href='javascript:void(0);' onclick='showOpenWin(\"?model=finance_invoice_invoice&action=init&perm=view&id=" + row.id + '&skey=' + row.skey_ + "\",1,800,1100,"+row.id+")'>" + v + "</a>";
					}else{
						return "<a href='javascript:void(0);' style='color:red' onclick='showOpenWin(\"?model=finance_invoice_invoice&action=init&perm=view&id=" + row.id + '&skey=' + row.skey_ + "\",1,800,1100,"+row.id+")'>" + v + "</a>";
					}
				}
			}, {
				display : '单据编号',
				name : 'invoiceCode',
				sortable : true,
				width : 130,
				process : function(v,row){
					if(row.isRed == 0){
						return v;
					}else{
						return "<span class='red'>" + v + "</span>";
					}
				}
			},{
				display : '源单类型',
				name : 'objType',
				datacode : 'KPRK',
				width:70
			},{
				display : '源单编号',
				name : 'objCode',
				width:125,
				process : function(v,row){
					if(row.objType == "" || row.objId == '0'){
						return v;
					}
					return "<a href='javascript:void(0);' onclick='showModalWin(\"?model=finance_invoice_invoice&action=toViewObj&objId=" + row.objId + '&objType=' + row.objType + "\")'>" + v + "</a>";
				}
			},
			{
				display : '归属公司',
				name :'businessBelongName',
				width:70
			},
			{
				display : '开票单位',
				name :'invoiceUnitName',
				width:130
			},
			{
				display : '开票单位id',
				name :'invoiceUnitId',
				hide : true
			},
			{
				display : '合同单位id',
				name :'contractUnitId',
				hide : true
			},
			{
				display : '合同单位',
				name :'contractUnitName',
				hide : true
			},
			{
				display : '开票日期',
				name : 'invoiceTime',
				sortable:true,
				width:80
			},
			{
				display : '开票类型',
				name : 'invoiceTypeName',
				width:80
			},
			{
				display : '部门',
				name : 'deptName',
				width:85
			},
			{
				display : '主管',
				name : 'managerName',
				width:85
			},
			{
				display : '业务员',
				name : 'salesman',
				width:85
			},
			{
				display : '总金额',
				name : 'invoiceMoney',
				process : function(v,row){
					if(row.isRed == 0){
						return moneyFormat2(v);
					}else{
						if(v*1 != 0){
							return '<span class="red">-' + moneyFormat2(v) + "</span>";
						}else{
							return moneyFormat2(v);
						}
					}
				},
				width:80
			},
			{
				display : '软件金额',
				name : 'softMoney',
				process : function(v,row){
					if(row.isRed == 0){
						return moneyFormat2(v);
					}else{
						if(v*1 != 0){
							return '<span class="red">-' + moneyFormat2(v) + "</span>";
						}else{
							return moneyFormat2(v);
						}
					}
				},
				width:80
			},
			{
				display : '硬件金额',
				name : 'hardMoney',
				process : function(v,row){
					if(row.isRed == 0){
						return moneyFormat2(v);
					}else{
						if(v*1 != 0){
							return '<span class="red">-' + moneyFormat2(v) + "</span>";
						}else{
							return moneyFormat2(v);
						}
					}
				},
				width:80
			},
			{
				display : '维修金额',
				name : 'repairMoney',
				process : function(v,row){
					if(row.isRed == 0){
						return moneyFormat2(v);
					}else{
						if(v*1 != 0){
							return '<span class="red">-' + moneyFormat2(v) + "</span>";
						}else{
							return moneyFormat2(v);
						}
					}
				},
				width:80
			},
			{
				display : '服务金额',
				name : 'serviceMoney',
				process : function(v,row){
					if(row.isRed == 0){
						return moneyFormat2(v);
					}else{
						if(v*1 != 0){
							return '<span class="red">-' + moneyFormat2(v) + "</span>";
						}else{
							return moneyFormat2(v);
						}
					}
				},
				width:80
			},
			{
				display : '设备租赁金额',
				name : 'equRentalMoney',
				process : function(v,row){
					if(row.isRed == 0){
						return moneyFormat2(v);
					}else{
						if(v*1 != 0){
							return '<span class="red">-' + moneyFormat2(v) + "</span>";
						}else{
							return moneyFormat2(v);
						}
					}
				},
				width:80
			},
			{
				display : '场地租赁金额',
				name : 'spaceRentalMoney',
				process : function(v,row){
					if(row.isRed == 0){
						return moneyFormat2(v);
					}else{
						if(v*1 != 0){
							return '<span class="red">-' + moneyFormat2(v) + "</span>";
						}else{
							return moneyFormat2(v);
						}
					}
				},
				width:80
			},
			{
				display : '其他金额',
				name : 'otherMoney',
				process : function(v,row){
					if(row.isRed == 0){
						return moneyFormat2(v);
					}else{
						if(v*1 != 0){
							return '<span class="red">-' + moneyFormat2(v) + "</span>";
						}else{
							return moneyFormat2(v);
						}
					}
				},
				width:80
			},
			{
				display : '开票人',
				name : 'createName',
				width:85,
				process : function(v,row){
					return v + "<input type='hidden' id='hasRed"+ row.id +"' value='unde'/>";
				}
			},
			{
				display : '是否红字',
				name : 'isRed',
				width:80,
				hide : true,
				process : function(v){
					if(v == 1){
						return '是';
					}else{
						return '否';
					}
				}
			},
			{
				display : '已邮寄',
				name : 'isMail',
				width:60,
				process : function(v){
					if(v == 1){
						return '是';
					}else{
						return '否';
					}
				}
			},
			{
				display : '业务编号',
				name : 'rObjCode',
				width:120
			},
			{
				display : '租赁开始日期',
				name : 'rentBeginDate',
				width:80
			},
			{
				display : '租赁结束日期',
				name : 'rentEndDate',
				width:80
			},
			{
				display : '租赁天数',
				name : 'rentDays',
				width:60
			}
		],
		toAddConfig : {
			toAddFn : function(p) {
				showOpenWin("?model=finance_invoice_invoice&action=toAdd",1,800,1100,'toAdd');
			}
		},
		toViewConfig : {
			toViewFn : function(p,g) {
				var rowObj = g.getSelectedRow();
				var row = rowObj.data('data');
				showOpenWin("?model=finance_invoice_invoice&action=init&perm=view&id=" + row.id + "&skey="+ row.skey_ ,1,800,1100,row.id);
			}
		},
        buttonsEx : [{
			name : 'Add',
			text : "导入",
			icon : 'excel',
			action : function(row) {
				showThickboxWin("?model=finance_invoice_invoice&action=toExcel"
			          + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
			}
		}],
		menusEx :[{
				text: "编辑",
				icon: 'edit',
				showMenuFn : function(row){
					return true;
				},
				action: function(row) {
					hasRed = $("#hasRed" + row.id);
					if( hasRed.val() == 'unde' ){
						$.ajax({
						    type: "POST",
						    url: "?model=finance_invoice_invoice&action=hasRedInvoice",
						    data: {"id" : row.id},
						    async: false,
						    success: function(data){
						   	   if(data == 1){
						   	   		hasRed.val(1);
								}else{
						   	   		hasRed.val(0);
								}
							}
						});
					}
					if(hasRed.val() == 1){
						alert('已存在红字发票,不能对发票进行编辑');
					}else{
						$.ajax({
						    type: "POST",
						    url: "?model=finance_carriedforward_carriedforward&action=invoiceIsCarried",
						    data: {"id" : row.id},
						    async: false,
						    success: function(data){
						   	   if(data == 1){
						   	   		alert('发票已经被结转,不能进行编辑操作');
								}else{
									showOpenWin("?model=finance_invoice_invoice&action=init&id=" + row.id + "&skey="+ row.skey_ ,1,800,1100,row.id);
								}
							}
						});
					}
				}
			},{
				text: "录入邮寄信息",
				icon: 'add',
				showMenuFn : function(row){
					if(row.isMail == 0 && row.isRed == 0){
						return true;
					}
					return false;
				},
				action: function(row) {
					showThickboxWin("?model=mail_mailinfo"
						+ "&action=addByPush"
						+ "&docId=" + row.id
						+ "&docCode=" + row.invoiceNo
						+ "&salesman=" + row.salesman
						+ "&salesmanId=" + row.salesmanId
						+ '&docType=YJSQDLX-FPYJ'
						+ "&skey=" + row['skey_']
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500"
						+ "&width=900");
				}
			},{
				text: "生成红字发票",
				icon: 'add',
				showMenuFn : function(row){
					if(row.isRed == 0){
						return true;
					}
					return false;
				},
				action: function(row) {
					hasRed = $("#hasRed" + row.id);
					if( hasRed.val() == 'unde' ){
						$.ajax({
						    type: "POST",
						    url: "?model=finance_invoice_invoice&action=hasRedInvoice",
						    data: {"id" : row.id},
						    async: false,
						    success: function(data){
						   	   if(data == 1){
						   	   		hasRed.val(1);
								}else{
						   	   		hasRed.val(0);
								}
							}
						});
					}
					if(hasRed.val() == 1){
						alert('已存在红字发票,不能对在生成红字发票');
					}else{
						showOpenWin("?model=finance_invoice_invoice&action=toAddRedInvoice&id=" + row.id + "&skey="+ row.skey_ ,1,700,1100,row.id);
					}
				}
			},
			{
				name : 'view',
				text : "邮寄信息",
				icon : 'view',
				showMenuFn : function(row){
					if(row.isMail == 1){
						return true;
					}
					return false;
				},
				action : function(row, rows, grid) {
					showThickboxWin("?model=mail_mailinfo&action=viewByDoc&docId="
						+ row.id
						+ '&docType=YJSQDLX-FPYJ'
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
				}
			},{
				text : '删除',
				icon : 'delete',
				action : function(row) {
					hasRed = $("#hasRed" + row.id);
					if( hasRed.val() == 'unde' ){
						$.ajax({
						    type: "POST",
						    url: "?model=finance_invoice_invoice&action=hasRedInvoice",
						    data: {"id" : row.id},
						    async: false,
						    success: function(data){
						   	   if(data == 1){
						   	   		hasRed.val(1);
								}else{
						   	   		hasRed.val(0);
								}
							}
						});
					}
					if(hasRed.val() == 1){
						alert('已存在红字发票,不能对发票进行删除操作');
					}else{
						$.ajax({
						    type: "POST",
						    url: "?model=finance_carriedforward_carriedforward&action=invoiceIsCarried",
						    data: {"id" : row.id},
						    async: false,
						    success: function(data){
						   	   if(data == 1){
						   	   		alert('发票已经被结转,请先取消发票的结转再尝试删除');
								}else{
									if(row.isMail == 0){
										var confirmMsg = '确定要删除?'
									}else{
										var confirmMsg = '发票已经录入邮寄记录,确定要删除?'
									}
									if (window.confirm(confirmMsg)) {
										if(row.applyNo != "" ){
											$.ajax({
												type : "POST",
												url : "?model=finance_invoice_invoice&action=ajaxDelForApply",
												data : {
													"id" : row.id,
													"applyId" : row.applyId
												},
												success : function(msg) {
													if (msg == 1) {
														alert('删除成功！');
														$("#invoiceGrid").yxgrid("reload");
													}else{
														alert('删除失败');
													}
												}
											});
										}else{
											$.ajax({
												type : "POST",
												url : "?model=finance_invoice_invoice&action=ajaxdeletes",
												data : {
													id : row.id
												},
												success : function(msg) {
													if (msg == 1) {
														alert('删除成功！');
														$("#invoiceGrid").yxgrid("reload");
													}else{
														alert('删除失败');
													}
												}
											});
										}
									}
								}
							}
						});
					}
				}
			}
		],
		searchitems:[
	        {
	            display:'发票号',
	            name:'invoiceNo'
	        },
	        {
	            display:'源单编号',
	            name:'objCodeSearch'
	        },
	        {
	            display:'开票单位',
	            name:'invoiceUnitNameSearch'
	        },
	        {
	            display:'业务员',
	            name:'salesman'
	        },
	        {
	            display:'开票金额',
	            name:'invoiceMoney'
	        },
	        {
	            display:'开票日期',
	            name:'invoiceTimeSearch'
	        }
        ],
        comboEx : [
    		{
				text : '开票类型',
				key : 'invoiceType',
				datacode : 'XSFP'
        	}
        ],
		sortorder:'DESC'
    });
});