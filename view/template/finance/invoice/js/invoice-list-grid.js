var show_page=function(page){
   $("#invoiceGrid").yxgrid("reload");
};

$(function(){
    $("#invoiceGrid").yxgrid({
    	model:'finance_invoice_invoice',
    	title:'��Ʊ����',
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
				display : '���뵥��',
				name : 'applyNo',
				hide : true
			}, {
				display : '��Ʊ����',
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
				display : '���ݱ��',
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
				display : 'Դ������',
				name : 'objType',
				datacode : 'KPRK',
				width:70
			},{
				display : 'Դ�����',
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
				display : '������˾',
				name :'businessBelongName',
				width:70
			},
			{
				display : '��Ʊ��λ',
				name :'invoiceUnitName',
				width:130
			},
			{
				display : '��Ʊ��λid',
				name :'invoiceUnitId',
				hide : true
			},
			{
				display : '��ͬ��λid',
				name :'contractUnitId',
				hide : true
			},
			{
				display : '��ͬ��λ',
				name :'contractUnitName',
				hide : true
			},
			{
				display : '��Ʊ����',
				name : 'invoiceTime',
				sortable:true,
				width:80
			},
			{
				display : '��Ʊ����',
				name : 'invoiceTypeName',
				width:80
			},
			{
				display : '����',
				name : 'deptName',
				width:85
			},
			{
				display : '����',
				name : 'managerName',
				width:85
			},
			{
				display : 'ҵ��Ա',
				name : 'salesman',
				width:85
			},
			{
				display : '�ܽ��',
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
				display : '������',
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
				display : 'Ӳ�����',
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
				display : 'ά�޽��',
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
				display : '������',
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
				display : '�豸���޽��',
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
				display : '�������޽��',
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
				display : '�������',
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
				display : '��Ʊ��',
				name : 'createName',
				width:85,
				process : function(v,row){
					return v + "<input type='hidden' id='hasRed"+ row.id +"' value='unde'/>";
				}
			},
			{
				display : '�Ƿ����',
				name : 'isRed',
				width:80,
				hide : true,
				process : function(v){
					if(v == 1){
						return '��';
					}else{
						return '��';
					}
				}
			},
			{
				display : '���ʼ�',
				name : 'isMail',
				width:60,
				process : function(v){
					if(v == 1){
						return '��';
					}else{
						return '��';
					}
				}
			},
			{
				display : 'ҵ����',
				name : 'rObjCode',
				width:120
			},
			{
				display : '���޿�ʼ����',
				name : 'rentBeginDate',
				width:80
			},
			{
				display : '���޽�������',
				name : 'rentEndDate',
				width:80
			},
			{
				display : '��������',
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
			text : "����",
			icon : 'excel',
			action : function(row) {
				showThickboxWin("?model=finance_invoice_invoice&action=toExcel"
			          + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
			}
		}],
		menusEx :[{
				text: "�༭",
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
						alert('�Ѵ��ں��ַ�Ʊ,���ܶԷ�Ʊ���б༭');
					}else{
						$.ajax({
						    type: "POST",
						    url: "?model=finance_carriedforward_carriedforward&action=invoiceIsCarried",
						    data: {"id" : row.id},
						    async: false,
						    success: function(data){
						   	   if(data == 1){
						   	   		alert('��Ʊ�Ѿ�����ת,���ܽ��б༭����');
								}else{
									showOpenWin("?model=finance_invoice_invoice&action=init&id=" + row.id + "&skey="+ row.skey_ ,1,800,1100,row.id);
								}
							}
						});
					}
				}
			},{
				text: "¼���ʼ���Ϣ",
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
				text: "���ɺ��ַ�Ʊ",
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
						alert('�Ѵ��ں��ַ�Ʊ,���ܶ������ɺ��ַ�Ʊ');
					}else{
						showOpenWin("?model=finance_invoice_invoice&action=toAddRedInvoice&id=" + row.id + "&skey="+ row.skey_ ,1,700,1100,row.id);
					}
				}
			},
			{
				name : 'view',
				text : "�ʼ���Ϣ",
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
				text : 'ɾ��',
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
						alert('�Ѵ��ں��ַ�Ʊ,���ܶԷ�Ʊ����ɾ������');
					}else{
						$.ajax({
						    type: "POST",
						    url: "?model=finance_carriedforward_carriedforward&action=invoiceIsCarried",
						    data: {"id" : row.id},
						    async: false,
						    success: function(data){
						   	   if(data == 1){
						   	   		alert('��Ʊ�Ѿ�����ת,����ȡ����Ʊ�Ľ�ת�ٳ���ɾ��');
								}else{
									if(row.isMail == 0){
										var confirmMsg = 'ȷ��Ҫɾ��?'
									}else{
										var confirmMsg = '��Ʊ�Ѿ�¼���ʼļ�¼,ȷ��Ҫɾ��?'
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
														alert('ɾ���ɹ���');
														$("#invoiceGrid").yxgrid("reload");
													}else{
														alert('ɾ��ʧ��');
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
														alert('ɾ���ɹ���');
														$("#invoiceGrid").yxgrid("reload");
													}else{
														alert('ɾ��ʧ��');
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
	            display:'��Ʊ��',
	            name:'invoiceNo'
	        },
	        {
	            display:'Դ�����',
	            name:'objCodeSearch'
	        },
	        {
	            display:'��Ʊ��λ',
	            name:'invoiceUnitNameSearch'
	        },
	        {
	            display:'ҵ��Ա',
	            name:'salesman'
	        },
	        {
	            display:'��Ʊ���',
	            name:'invoiceMoney'
	        },
	        {
	            display:'��Ʊ����',
	            name:'invoiceTimeSearch'
	        }
        ],
        comboEx : [
    		{
				text : '��Ʊ����',
				key : 'invoiceType',
				datacode : 'XSFP'
        	}
        ],
		sortorder:'DESC'
    });
});