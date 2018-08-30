var show_page = function(page) {
	$("#carrecordsGrid").yxsubgrid("reload");
};
$(function() {
	$("#carrecordsGrid").yxsubgrid({
		model : 'carrental_records_carrecords',
		title : '�ó���¼',
		param : {'projectId' : $("#projectId").val()},
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		isAddAction : false,
		showcheckbox : false,
		//����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'projectCode',
				display : '��Ŀ���',
				sortable : true,
				width : 160
			}, {
				name : 'projectName',
				display : '��Ŀ����',
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
				sortable : true,
				width : 100
			}, {
				name : 'createId',
				display : '������Id',
				sortable : true,
				hide : true
			}, {
				name : 'createName',
				display : '������',
				sortable : true
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
				width : 130
			}
		],
		subGridOptions : {
			url : '?model=carrental_records_carrecordsdetail&action=pageJson',
			param : [{
					paramId : 'recordsId',
					colId : 'id'
				}
			],
			colModel : [{
					name : 'useDate',
					display : 'ʹ������',
					width : 80
				}, {
					name : 'beginNum',
					display : '��ʼ������',
					width : 80
				}, {
					name : 'endNum',
					display : '����������',
					width : 80
				}, {
					name : 'mileage',
					display : '�����',
					width : 80
				}, {
					name : 'useHours',
					display : 'ʹ��ʱ��',
					width : 80
				}, {
					name : 'useReson',
					display : '��;'
				}, {
					name : 'travelFee',
					display : '�˳���',
					width : 80
				}, {
					name : 'fuelFee',
					display : '�ͷ�',
					width : 80
				}, {
					name : 'roadFee',
					display : '·�ŷ�',
					width : 80
				}, {
					name : 'effectiveLog',
					display : '��ЧLOG'
				}
			]
		},
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					showModalWin("?model=carrental_records_carrecords&action=toView&id=" + row.id , 1);
				}
			}
		}],
		searchitems : {
			display : "�����ֶ�",
			name : 'XXX'
		}
	});
});