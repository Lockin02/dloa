var show_page = function(page) {
	$("#taskdetailGrid").yxgrid("reload");
};
$(function() {
	$("#taskdetailGrid").yxgrid({
		model : 'engineering_resources_taskdetail',
		title : '��Ŀ�豸����',
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		isAddAction : false,
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'taskType',
			display : '��������',
			sortable : true
		}, {
			name : 'resourceCode',
			display : '��Դ����',
			sortable : true
		}, {
			name : 'resourceName',
			display : '��Դ����',
			sortable : true
		}, {
			name : 'resourceTypeCode',
			display : '��Դ����',
			sortable : true
		}, {
			name : 'resourceTypeName',
			display : '��Դ��������',
			sortable : true
		}, {
			name : 'number',
			display : '����',
			sortable : true
		}, {
			name : 'unit',
			display : '��λ',
			sortable : true
		}, {
			name : 'price',
			display : '����',
			sortable : true
		}, {
			name : 'amount',
			display : '�ɱ����',
			sortable : true
		}, {
			name : 'planBeginDate',
			display : 'Ԥ�ƽ������',
			sortable : true
		}, {
			name : 'planEndDate',
			display : 'Ԥ�ƹ黹����',
			sortable : true
		}, {
			name : 'beignTime',
			display : '��ʼʹ��ʱ��',
			sortable : true
		}, {
			name : 'endTime',
			display : '����ʹ��ʱ��',
			sortable : true
		}, {
			name : 'useDays',
			display : 'ʹ������',
			sortable : true
		}, {
			name : 'projectCode',
			display : '��Ŀ���',
			sortable : true
		}, {
			name : 'projectName',
			display : '��Ŀ����',
			sortable : true
		}, {
			name : 'activityCode',
			display : '����',
			sortable : true
		}, {
			name : 'activityName',
			display : '�����',
			sortable : true
		}, {
			name : 'workContent',
			display : '��������',
			sortable : true
		}, {
			name : 'remark',
			display : '��ע˵��',
			sortable : true
		}, {
			name : 'createId',
			display : '������Id',
			sortable : true
		}, {
			name : 'createName',
			display : '����������',
			sortable : true
		}, {
			name : 'createTime',
			display : '����ʱ��',
			sortable : true
		}, {
			name : 'updateId',
			display : '�޸���Id',
			sortable : true
		}, {
			name : 'updateName',
			display : '�޸�������',
			sortable : true
		}, {
			name : 'updateTime',
			display : '�޸�ʱ��',
			sortable : true
		}, {
			name : 'area',
			display : '����',
			sortable : true
		}, {
			name : 'areaPrincipal',
			display : '���ظ�����',
			sortable : true
		}, {
			name : 'areaPrincipalId',
			display : '�Ƿ��������',
			sortable : true
		}, {
			name : 'isMeet',
			display : '�������',
			sortable : true
		}, {
			name : 'makeProgress',
			display : '��չ',
			sortable : true
		}, {
			name : 'forDays',
			display : 'Ԥ�Ƴﱸ����',
			sortable : true
		}],
		// ���ӱ������
		subGridOptions : {
			url : '?model=engineering_resources_NULL&action=pageItemJson',
			param : [{
				paramId : 'mainId',
				colId : 'id'
			}],
			colModel : [{
				name : 'XXX',
				display : '�ӱ��ֶ�'
			}]
		},

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
			display : "�����ֶ�",
			name : 'XXX'
		}]
	});
});