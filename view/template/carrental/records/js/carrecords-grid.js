var show_page = function(page) {
	$("#carrecordsGrid").yxgrid("reload");
};
$(function() {
	$("#carrecordsGrid").yxgrid({
		model : 'carrental_records_carrecords',
		title : '�ó���¼',
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		customeCode : 'carrecordsGrid',
		//����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'projectName',
				display : '��Ŀ����',
				sortable : true,
				width : 200
			}, {
				name : 'projectCode',
				display : '��Ŀ���',
				sortable : true,
				width : 200
			}, {
				name : 'carNo',
				display : '���ƺ�',
				sortable : true,
				width : 80
			}, {
				name : 'carType',
				display : '�����ͺ�',
				sortable : true,
				width : 80
			}, {
				name : 'driver',
				display : '˾��',
				sortable : true
			}, {
				name : 'linkPhone',
				display : '��ϵ�绰',
				sortable : true
			}, {
				name : 'beginDate',
				display : '��ʼ����',
				sortable : true
			}, {
				name : 'endDate',
				display : '��������',
				sortable : true
			}, {
				name : 'createId',
				display : '������Id',
				sortable : true,
				hide : true
			}, {
				name : 'createName',
				display : '����������',
				sortable : true,
				hide : true
			}, {
				name : 'createTime',
				display : '����ʱ��',
				sortable : true,
				hide : true
			}, {
				name : 'updateId',
				display : '�޸���Id',
				sortable : true,
				hide : true
			}, {
				name : 'updateName',
				display : '�޸�������',
				sortable : true,
				hide : true
			}, {
				name : 'updateTime',
				display : '�޸�ʱ��',
				sortable : true,
				hide : true
			}
		],
		toAddConfig : {
			toAddFn : function(p) {
				showModalWin("?model=carrental_records_carrecords&action=toAdd&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=750");
			}
		},
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					showModalWin("?model=carrental_records_carrecords&action=toView&id="
							+ row.id
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1100");
				}
			}
		}, {
			text : '�༭',
			icon : 'edit',
			action : function(row, rows, grid) {
				if (row) {
					showModalWin("?model=carrental_records_carrecords&action=toEdit&id="
							+ row.id
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1100");
				}
			}
		}, {
			text : 'ɾ��',
			icon : 'delete',
			action : function(row, rows, grid) {
				if (confirm('ȷ��Ҫɾ����')) {
					$.ajax({
						type : "POST",
						url : "?model=carrental_records_carrecords&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('ɾ���ɹ�!');
								show_page();
							} else {
								alert('ɾ��ʧ��!');
							}
						}
					});
				}
			}
		}],
		searchitems :[
			{
				display : "���ƺ�",
				name : 'carNoSearch'
			},{
				display : "��Ŀ���",
				name : 'projectCodeSearch'
			}
		]
	});
});