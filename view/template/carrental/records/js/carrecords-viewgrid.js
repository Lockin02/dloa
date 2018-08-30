var show_page = function(page) {
	$("#carrecordsGrid").yxgrid("reload");
};
$(function() {
	$("#carrecordsGrid").yxgrid({
		model : 'carrental_records_carrecords',
		param : {
			"carId" : $("#carId").val()
		},
		isDelAction : false,
		isAddAction : false,
		isEditAction : false,
		title : '�ó���¼',
		// ����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'projectName',
				display : '��Ŀ����',
				sortable : true,
				width : 180
			}, {
				name : 'projectCode',
				display : '��Ŀ���',
				sortable : true,
				width : 120
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
				name : 'useDate',
				display : '�ó�����',
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
			}],
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
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