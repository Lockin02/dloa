var show_page = function(page) {
	$("#cardsinfoGrid").yxgrid("reload");
};
$(function() {
	$("#cardsinfoGrid").yxgrid({
		model : 'cardsys_cardsinfo_cardsinfo',
		title : '���Կ���Ϣ',
		param : {'projectId' : $("#projectId").val()},
		//����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'operators',
				display : '��Ӫ��',
				sortable : true
			}, {
				name : 'netType',
				display : '��������',
				sortable : true,
				datacode : 'WLLX'
			}, {
				name : 'packageType',
				display : '�ײ�����',
				sortable : true
			}, {
				name : 'ratesOf',
				display : '�ʷ����',
				sortable : true
			}, {
				name : 'carNo',
				display : '����',
				sortable : true
			}, {
				name : 'pinNo',
				display : 'pin��',
				width : 80
			}, {
				name : 'cityName',
				display : '������(��)',
				width : 80
			}, {
				name : 'cardType',
				display : '����',
				width : 80
			}, {
				name : 'status',
				display : '״̬',
				width : 80,
				datacode : 'CSKZT'
			}, {
				name : 'projectCode',
				display : '��Ŀ���',
				sortable : true,
				hide : true
			}, {
				name : 'projectName',
				display : '��Ŀ����',
				sortable : true,
				width : 180
			}, {
				name : 'ownerId',
				display : '�ֿ�Ա��Id',
				sortable : true,
				hide : true
			}, {
				name : 'ownerName',
				display : '�ֿ���',
				sortable : true
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
				name : 'openDate',
				display : '��������',
				sortable : true
			}, {
				name : 'openMoney',
				display : '�������',
				sortable : true,
				process : function(v) {
					return moneyFormat2(v);
				}
			}, {
				name : 'initMoney',
				display : '��ʼ���',
				sortable : true,
				process : function(v) {
					return moneyFormat2(v);
				}
			}
		],
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		/**
		 * ��չ��ť
		 */
//		buttonsEx : [{
//			text : "�ͷŲ��Կ�",
//			icon : 'edit',
//			name : 'releaseCar',
//			action : function() {
//				showThickboxWin("?model=cardsys_cardsinfo_cardsinfo&action=toReleaseCar&&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800")
//			}
//		}],
		menusEx : [{
			text : 'ɾ��',
			icon : 'delete',
			action : function(rowData, rows, rowIds, g) {
				$.ajax({
					type : "POST",
					url : "?model=cardsys_cardrecords_cardrecords&action=hasRecords",
					data : {
						cardId : rowData.id
					},
				    async: false,
					success : function(msg) {
						if (msg == 1) {
							alert("�����Ѿ���ʹ�ã�����ɾ����");
						}else{
							g.options.toDelConfig.toDelFn(g.options, g);
						}
					}
				});
			}
		}],
		isDelAction : false,
		searchitems : [{
				display : "�ֿ���",
				name : 'ownerNameSearch'
			}, {
				display : "����",
				name : 'carNoSearch'
			}
		]
	});
});