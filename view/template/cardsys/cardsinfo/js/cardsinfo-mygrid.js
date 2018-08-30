var show_page = function(page) {
	$("#cardsinfoGrid").yxgrid("reload");
};
$(function() {
	$("#cardsinfoGrid").yxgrid({
		model : 'cardsys_cardsinfo_cardsinfo',
		action : 'myPageJson',
		title : '���Կ���Ϣ',
		showcheckbox : false,
		//����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'cardNo',
				display : '����',
				sortable : true,
				process : function(v,row){
					return "<a href='#' onclick='showThickboxWin(\"?model=cardsys_cardsinfo_cardsinfo&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>" + v + "</a>";
				}
			}, {
				name : 'pinNo',
				display : '����',
				width : 80
			}, {
				name : 'openerId',
				display : '����Ա��Id',
				sortable : true,
				hide : true
			}, {
				name : 'openerName',
				display : '����Ա��',
				sortable : true
			}, {
				name : 'idCardNo',
				display : '���֤',
				sortable : true,
				width : 150
			}, {
				name : 'cardTypeName',
				display : '����',
				sortable : true,
				width : 70
			}, {
				name : 'location',
				display : '������',
				sortable : true,
				width : 70
			}, {
				name : 'packageType',
				display : '�ײ�',
				sortable : true
			}, {
				name : 'operators',
				display : '��Ӫ��',
				sortable : true,
				hide : true
			}, {
				name : 'netType',
				display : '��������',
				sortable : true,
				datacode : 'WLLX',
				hide : true
			}, {
				name : 'ratesOf',
				display : '�ʷ�����',
				sortable : true,
				width : 150
			}, {
				name : 'openDate',
				display : '��������',
				sortable : true,
				width : 80
			}, {
				name : 'closeDate',
				display : '��������',
				sortable : true,
				width : 80
			}, {
				name : 'allMoney',
				display : '�ۼ�����',
				sortable : true,
				process : function(v) {
					return moneyFormat2(v);
				}
			}, {
				name : 'cityName',
				display : '������(��)',
				width : 80,
				hide : true
			}, {
				name : 'cardType',
				display : '����',
				width : 80,
				hide : true
			}, {
				name : 'status',
				display : '״̬',
				width : 80,
				datacode : 'CSKZT',
				hide : true
			}, {
				name : 'projectCode',
				display : '��Ŀ���',
				sortable : true,
				hide : true
			}, {
				name : 'projectName',
				display : '��Ŀ����',
				sortable : true,
				width : 180,
				hide : true
			}, {
				name : 'ownerId',
				display : '�ֿ�Ա��Id',
				sortable : true,
				hide : true
			}, {
				name : 'ownerName',
				display : '�ֿ���',
				sortable : true,
				hide : true
			}, {
				name : 'openMoney',
				display : '�������',
				sortable : true,
				process : function(v) {
					return moneyFormat2(v);
				},
				hide : true
			}, {
				name : 'initMoney',
				display : '��ʼ���',
				sortable : true,
				process : function(v) {
					return moneyFormat2(v);
				},
				hide : true
			}
		],
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		menusEx : [{
				text : 'ɾ��',
				icon : 'delete',
				action : function(rowData, rows, rowIds, g) {
					if (rowData.allMoney*1 == 0) {
						if(confirm('ȷ��Ҫɾ�����Կ���')){
							$.ajax({
								type : "POST",
								url : "?model=cardsys_cardsinfo_cardsinfo&action=ajaxdeletes",
								data : {
									id : rowData.id
								},
								success : function(msg) {
									if (msg == 1) {
										alert('ɾ���ɹ���');
										show_page(1);
									}else{
										alert("ɾ��ʧ��! ");
									}
								}
							});
						}
					} else {
						alert("�����Ѿ���ʹ�ã�����ɾ����");
					}
				}
			}
		],
		isDelAction : false,
		searchitems : [{
				display : "������",
				name : 'openerNameSearch'
			}, {
				display : "����",
				name : 'cardNoSearch'
			}
		]
	});
});