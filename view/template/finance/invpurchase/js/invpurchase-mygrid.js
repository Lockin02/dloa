var show_page = function(page) {
	$("#invpurchaseGrid").yxsubgrid("reload");
};
$(function() {
	$("#invpurchaseGrid").yxsubgrid({
		model: 'finance_invpurchase_invpurchase',
		action : 'myPageJson',
		title: '�ҵĲɹ���Ʊ',
		isEditAction : false,
		isDelAction : false,
		isViewAction : false,
		showcheckbox :false,
		//����Ϣ
		colModel: [{
				display: 'id',
				name: 'id',
				sortable: true,
				hide: true,
				process : function(v,row){
					return v + "<input type='hidden' id='isBreak"+ row.id+"' value='unde'>";
				}
			},
			{
				name: 'objCode',
				display: '���ݱ��',
				sortable: true,
				width : 130,
				process : function(v,row){
					if(row.formType == "blue"){
						return v;
					}else{
						return "<span class='red'>"+ v +"</span>";
					}
				}
			},
			{
				name: 'objNo',
				display: '��Ʊ����',
				sortable: true
			},
			{
				name: 'supplierName',
				display: '��Ӧ������',
				sortable: true,
				width : 150
			},
			{
				name: 'invType',
				display: '��Ʊ����',
				sortable: true,
				width : 80,
				datacode : 'FPLX'
			},
			{
				name: 'taxRate',
				display: '˰��(%)',
				sortable: true,
				width : 60
			},
			{
				name: 'formAssessment',
				display: '����˰��',
				sortable: true,
				process : function(v){
					return moneyFormat2(v);
				},
				width : 80
			},
			{
				name: 'amount',
				display: '�ܽ��',
				sortable: true,
				process : function(v){
					return moneyFormat2(v);
				},
				width : 80
			},
			{
				name: 'formCount',
				display: '��˰�ϼ�',
				sortable: true,
				process : function(v){
					return moneyFormat2(v);
				},
				width : 80
			},
			{
				name: 'formDate',
				display: '��������',
				sortable: true,
				width : 80
			},
			{
				name: 'payDate',
				display: '��������',
				sortable: true,
				width : 80
			},{
				name : 'purcontCode',
				display : '�ɹ��������',
				width : 130,
				hide : true
			},
			{
				name: 'departments',
				display: '����',
				sortable: true,
				width : 80
			},
			{
				name: 'salesman',
				display: 'ҵ��Ա',
				sortable: true,
				width : 80
			},
			{
				name : 'businessBelongName',
				display : '������˾',
				sortable : true,
				width : 80
			},
			{
				name: 'ExaStatus',
				display: '���״̬',
				sortable: true,
				width : 60,
				process : function(v){
					if(v == 1){
						return '�����';
					}else{
						return 'δ���';
					}
				}
			},
			{
				name: 'exaMan',
				display: '�����',
				sortable: true,
				width : 80
			},
			{
				name: 'ExaDT',
				display: '�������',
				sortable: true,
				width : 80
			},
			{
				name: 'status',
				display: '����״̬',
				sortable: true,
				width : 60,
				process : function(v){
					if(v == 1){
						return '�ѹ���';
					}else{
						return 'δ����';
					}
				}
			},{
				name : 'createName',
				display : '������',
				width : 90,
				hide : true
			},
			{
				name: 'belongId',
				display: '����ԭ��Ʊid',
				hide: true
			}
		],

		// ���ӱ������
		subGridOptions : {
			url : '?model=finance_invpurchase_invpurdetail&action=pageJson',// ��ȡ�ӱ�����url
			// ���ݵ���̨�Ĳ�����������
			param : [
				{
					paramId : 'invPurId',// ���ݸ���̨�Ĳ�������
					colId : 'id'// ��ȡ���������ݵ�������
				}
			],
			// ��ʾ����
			colModel : [{
					name : 'productNo',
					display : '���ϱ��',
					width : 80
				},{
					name : 'productName',
					display : '��������',
					width : 140
				},{
				    name : 'number',
				    display : '����',
				    width : 50
				},{
					name : 'price',
					display : '����',
					process : function(v,row,parentRow){
						return moneyFormat2(v,6,6);
					}
				},{
					name : 'taxPrice',
					display : '��˰����',
					process : function(v){
						return moneyFormat2(v,6,6);
					}
				},{
				    name : 'assessment',
				    display : '˰��',
					process : function(v){
						return moneyFormat2(v);
					},
				    width : 70
				},{
				    name : 'amount',
				    display : '���',
					process : function(v){
						return moneyFormat2(v);
					},
				    width : 80
				},{
				    name : 'allCount',
				    display : '��˰�ϼ�',
					process : function(v){
						return moneyFormat2(v);
					},
				    width : 80
				},{
				    name : 'objCode',
				    display : 'Դ�����',
				    width : 120
				},{
				    name : 'contractCode',
				    display : '�������',
				    width : 120
				}
			]
		},
		toAddConfig : {
			toAddFn : function(p) {
				showModalWin("?model=finance_invpurchase_invpurchase&action=toAdd");
			}
		},
		menusEx : [
			{
				text: "�鿴",
				icon: 'view',
				action: function(row) {
					showOpenWin('?model=finance_invpurchase_invpurchase&action=init&perm=view&id=' + row.id + '&skey=' + row.skey_ );
				}
			},
			{
				text: "�޸�",
				icon: 'edit',
				showMenuFn : function(row){
					if(row.ExaStatus == 0){
						return true;
					}
					return false;
				},
				action: function(row) {
					showModalWin('?model=finance_invpurchase_invpurchase&action=init&id=' + row.id + '&skey=' + row.skey_ );
				}
			},
			{
				text: "ɾ��",
				icon: 'delete',
				showMenuFn : function(row){
					if(row.ExaStatus == 0){
						return true;
					}
					return false;
				},
				action: function(row) {
					if (window.confirm(("ȷ��Ҫɾ��?"))) {
						$.ajax({
							type : "POST",
							url : "?model=finance_invpurchase_invpurchase&action=ajaxdeletes",
							data : {
								id : row.id
							},
							success : function(msg) {
								if(msg * 1 == msg){
									if (msg == 1) {
										alert('ɾ���ɹ���');
										show_page(1);
									}else{
										alert("ɾ��ʧ��! ");
									}
								}else{
									alert(msg);
									show_page(1);
								}
							}
						});
					}
				}
			}
		],
		comboEx:
		[
			{
				text: "���״̬",
				key: 'ExaStatus',
				data: [{
					text : '�����',
					value : '1'
				},{
					text : 'δ���',
					value : '0'
				}]
			},{
				text: "����״̬",
				key: 'status',
				data: [{
					text : '�ѹ���',
					value : '1'
				},{
					text : 'δ����',
					value : '0'
				}]
			},{
				text: "��Ʊ����",
				key: 'invType',
				datacode : 'FPLX'
			}
		],
		searchitems:[
	        {
	            display:'��Ʊ����',
	            name:'objNo'
	        },
	        {
	            display:'��Ӧ������',
	            name:'supplierName'
	        },
	        {
	            display:'���ݱ��',
	            name:'objCodeSearch'
	        },
	        {
	            display:'Դ�����',
	            name:'objCodeSearchDetail'
	        },
	        {
	            display:'�ɹ��������',
	            name:'contractCodeSearch'
	        }
        ],
        sortname : 'updateTime'
	});
});