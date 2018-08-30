/**�����б�**/

var show_page=function(page){
   $("#invoiceGrid").yxsubgrid("reload");
};

$(function(){
        $("#invoiceGrid").yxsubgrid({
        	model:'finance_invoice_invoice',
        	title:'��Ʊ����',
        	isAddAction:false,
        	isEditAction : false,
        	isDelAction : false,

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
					display : '��Ʊ��',
					name : 'invoiceNo',
					sortable : true
				}, {
					display : '���ݱ��',
					name : 'invoiceCode',
					sortable : true,
					width : 140,
					process : function(v,row){
						if(row.isRed == 0){
							return v;
						}else{
							return "<span class='red'>" + v + "</span>";
						}
					},
					hide : true
				},
				{
					display : '����״̬',
					name : 'status',
					width:70,
					process : function(v){
						if(v == 1){
							return '�Ѵ���';
						}else{
							return 'δ����';
						}
					}
				},
				{
					display : '��Ʊ����',
					name : 'invoiceType',
					datacode:'FPLX',
					hide : true,
					width:80
				},{
					display : '�������',
					name : 'objCode',
					width:125,
					hide : true
				},{
					display : '��������',
					name : 'objType',
					datacode : 'KPRK',
					width:70,
					hide : true
				},
				{
					display : '��Ʊ��λ',
					name :'invoiceUnitName',
					width:150
				},
				{
					display : '��Ʊ����',
					name : 'invoiceTime',
					sortable:true,
					width:80
				},
				{
					display : '�ܽ��',
					name : 'invoiceMoney',
					process : function(v,row){
						if(row.isRed == 0){
							return moneyFormat2(v);
						}else{
							return '-' + moneyFormat2(v);
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
							return '-' + moneyFormat2(v);
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
							return '-' + moneyFormat2(v);
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
							return '-' + moneyFormat2(v);
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
							return '-' + moneyFormat2(v);
						}
					},
					width:80
				},{
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
				},{
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
					display : 'ҵ��Ա',
					name : 'salesman'
				},
				{
					display : '��Ʊ��',
					name : 'createName',
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
				}
			],
			// ���ӱ������
			subGridOptions : {
				url : '?model=finance_invoice_invoiceDetail&action=pageJson',// ��ȡ�ӱ�����url
				// ���ݵ���̨�Ĳ�����������
				param : [
					{
						paramId : 'invoiceId',// ���ݸ���̨�Ĳ�������
						colId : 'id'// ��ȡ���������ݵ�������
					}
				],
				// ��ʾ����
				colModel : [{
						name : 'productName',
						display : '��������',
						width : 140
					},{
						name : 'productModel',
						display : '��Ʒ����',
						width : 120,
						datacode : 'CWCPLX'
					}, {
					    name : 'amount',
					    display : '����',
					    width : 70
					},{
						name : 'softMoney',
						display : '������',
						process : function(v){
							return moneyFormat2(v);
						}
					},{
						name : 'hardMoney',
						display : 'Ӳ�����',
						process : function(v){
							return moneyFormat2(v);
						}
					},{
					    name : 'repairMoney',
					    display : 'ά�޽��',
						process : function(v){
							return moneyFormat2(v);
						}
					},{
					    name : 'serviceMoney',
					    display : '������',
						process : function(v){
							return moneyFormat2(v);
						}
					},{
					    name : 'psType',
					    display : '��Ʒ/��������',
					    datacode : 'CPFWLX'
					}
				]
			},
			toViewConfig : {
				formWidth : 900,
				formHeight : 500
			},
	        buttonsEx : [{
				name : 'edit',
				text : "��������",
				icon : 'edit',
				action : function(row,rows,idArr) {
					if(row){
						idStr = idArr.toString();
						showThickboxWin("?model=finance_invoice_invoice"
							+ "&action=batchDeal"
							+ "&ids="
							+ idStr
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500"
							+ "&width=900");
					}else{
						alert('����ѡ���¼');
					}
				}
			}],
	        comboEx : [
        		{
					text : '����״̬',
					key : 'status',
					value : '0',
					data : [{
						text : 'δ����',
						value : 0
					},{
						text : '�Ѵ���',
						value : 1
					}]
	        	}
	        ],
			searchitems:[
		        {
		            display:'��Ʊ��',
		            name:'invoiceNo'
		        },
		        {
		            display:'�������',
		            name:'objCodeSearch'
		        },
		        {
		            display:'��Ʊ��λ',
		            name:'invoiceUnitNameSearch'
		        },
		        {
		            display:'ҵ��Ա',
		            name:'salesman'
		        }
	        ],
	        sortname : 'invoiceTime',
			sortorder:'DESC'
        });
});