var show_page=function(){
   $("#invoiceapplyGrid").yxgrid("reload");
};

$(function(){
    $("#invoiceapplyGrid").yxgrid({
    	model:'finance_invoiceapply_invoiceapply',
    	title:'���еĿ�Ʊ����',
    	isViewAction:false,
    	isEditAction:false,
    	isDelAction:false,
    	showcheckbox: false,
		isOpButton : false,
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				display : '���뵥��',
				name : 'applyNo',
				sortable : true,
				width:135,
				process : function(v,row){
					if(row.isOffSite == '1'){
						return "<span class='green' title='��ؿ�Ʊ����'>" + v + "</span>";
					}else{
						return v;
					}
				}
			},{
				display : 'Դ������',
                name : 'objTypeName',
				sortable : true,
                width:70
			},{
				display : 'Դ�����',
				name : 'objCode',
				width:140
			},
			{
				display : '�ͻ���λ',
				name : 'customerName',
				width:150
			},
			{
				display : '������˾',
				name : 'businessBelongName',
				width:70
			},
			{
				display : '������',
				sortable : true,
				name : 'createName',
				width:80
			},
			{
				display : '��������',
				name : 'invoiceTypeName',
				sortable : true,
				width:80
			},
            {
                display : '�ұ�',
                name : 'currency',
                sortable : true,
                width:60,
                process: function (v) {
                    return v == '�����' ? v : '<span class="red">'+ v +'</span>';
                }
            },
			{
				display : '������',
				name : 'invoiceMoney',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				},
				width:90
			},
			{
				display : '�ѿ����',
				name : 'payedAmount',
				sortable : true,
				process : function(v,row){
					if(v*1 > row.invoiceMoney*1){
						return "<span class='red'>" + moneyFormat2(v) + "</span>";
					}else{
						return moneyFormat2(v);
					}
				},
				width:90
			},
			{
				display : '��ͬ���',
				name : 'contAmount',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				},
				width:90
			},
			{
				display : '����״̬',
				sortable : true,
				name : 'ExaStatus',
                width:60
			},
			{
				display : '��������',
				sortable : true,
				name : 'applyDate',
                width:70
			},
			{
				display : 'ҵ����',
				sortable : true,
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
			toAddFn : function() {
				showModalWin("?model=finance_invoiceapply_invoiceapply&action=toAddIndep");
			}
		},
		comboEx:
		[
			{
				text: "����״̬",
				key: 'ExaStatus',
				type : 'workFlow',
				value : '���'
			},{
				text: "��Ʊ״̬",
				key: 'moneyStatus',
				data : [{
						text : '���',
						value : 'done'
					}, {
						text : 'δ���',
						value : 'undo'
					}
				],
				value : 'undo'
			}
		],

		//��չ�Ҽ��˵�
		menusEx : [
		{
			text : '�鿴',
			icon : 'view',
			action : function(row){
				showModalWin('?model=finance_invoiceapply_invoiceapply&action=init'
				+ '&id=' + row.id + '&skey=' + row['skey_']
				+ '&perm=view');
			}
		},{
			text : '�༭',
			icon : 'edit',
			showMenuFn : function(row){
				if(row.ExaStatus == '���ύ'){
					return true;
				}
				return false;
			},
			action : function(row){
				showModalWin('?model=finance_invoiceapply_invoiceapply&action=init'
				+ '&id=' + row.id + '&skey=' + row['skey_'] );
			}
		},{
			text : '�ύ����',
			icon : 'edit',
			showMenuFn : function(row){
				if(row.ExaStatus == '���ύ'){
					return true;
				}
				return false;
			},
			action : function(row){
				if(row.isOffSite == '1'){
					showThickboxWin('controller/finance/invoiceapply/ewf_index.php?actTo=ewfSelect&billId='
		            	+ row.id + "&formName=��ؿ�Ʊ����"
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600');
				}else{
					showThickboxWin('controller/finance/invoiceapply/ewf_index.php?actTo=ewfSelect&billId='
		            	+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600');
				}
			}
		},{
			text : 'ɾ��',
			icon : 'delete',
			showMenuFn : function(row){
				if(row.ExaStatus == '���ύ' || row.ExaStatus == '���'){
					if(row.payedAmount * 1 == 0){
						return true;
					}
				}
				return false;
			},
			action : function(row) {
				if(row.payedAmount * 1 != 0){
					alert('�Ѿ���Ʊ�����ܽ���ɾ������');
					return false;
				}
				if (window.confirm(("ȷ��Ҫɾ��?"))) {
					$.ajax({
						type : "POST",
						url : "?model=finance_invoiceapply_invoiceapply&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('ɾ���ɹ���');
								$("#invoiceapplyGrid").yxgrid("reload");
							}else{
								alert("ɾ��ʧ��! ");
							}
						}
					});
				}
			}
		},{
			text : '��Ʊ�Ǽ�',
			icon : 'add',
			showMenuFn : function(row){
				if(row.ExaStatus == '���'){
					return true;
				}
				return false;
			},
			action : function(row){
				location="?model=finance_invoiceapply_invoiceapply&action=toregister&id="
				+ row.id + '&skey=' + row['skey_'] ;
			}

		},{
			text : '�������',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.ExaStatus != '���ύ') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showThickboxWin('controller/common/readview.php?itemtype=oa_finance_invoiceapply&pid='
					+ row.id
					+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
			}
		},{
			text : '��ӡ',
			icon : 'print',
			action : function(row) {
				showModalWin('?model=finance_invoiceapply_invoiceapply&action=printInvoiceApply'
				+ '&id=' + row.id + '&skey=' + row['skey_'] );
			}
		}],
        buttonsEx : [
	        {
				name : 'close',
				text : "��Ʊ���Ԥ��",
				icon : 'view',
				action : function() {
					showOpenWin('?model=finance_invoice_invoice&action=toInvoicePerview');
				}
	        }
        ],
		searchitems:[
	        {
	            display:'�ͻ���λ',
	            name:'customerNameSearch'
	        },
	        {
	            display:'Դ�����',
	            name:'objCodeSearch'
	        },
	        {
	            display:'ҵ����',
	            name:'rObjCodeSearch'
	        },
	        {
	            display:'������',
	            name:'createName'
	        },
	        {
	            display:'��Ʊ���뵥��',
	            name:'applyNo'
	        },
	        {
	            display:'��������',
	            name:'applyDateSearch'
	        }
        ],
		sortname : 'c.updateTime'
    });
});