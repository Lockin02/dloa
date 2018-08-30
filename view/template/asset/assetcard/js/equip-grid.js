// ��������/�޸ĺ�ص�ˢ�±��

var show_page = function(page) {
	$("#equipGrid").yxgrid('reload');
};

$(function() {
	$("#equipGrid").yxgrid({
		model : 'asset_assetcard_equip',
		param : {
			'assetId' : $("#assetId").val()
		},
		title : '�����豸��Ϣ',
		isAddAction : false,
		isDelAction : false,
		isEditAction : false,
		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : '�豸���',
			name : 'equipId',
			sortable : true
		}, {
			display : '�豸����',
			name : 'equipName',
			sortable : true,
			// ���⴦���ֶκ���
			process : function(v, row) {
				return row.equipName;
			}
		}, {
			display : '�Ǽ�����',
			name : 'regDate',
			sortable : true
		}, {
			display : '����ͺ�',
			name : 'spec',
			sortable : true
		}, {
			display : '������λ',
			name : 'unit',
			sortable : true
		}, {
			display : '����',
			name : 'num',
			sortable : true
		}, {
			display : '���',
			name : 'account',
			sortable : true
		}, {
			display : '��ŵص�id',
			name : 'placeId',
			sortable : true,
			hide : true
		}, {
			display : '��ŵص�',
			name : 'place',
			sortable : true
		}, {
			display : '��ע',
			name : 'remark',
			sortable : true
		}, {
			name : 'createName',
			display : '������',
			sortable : true,
			hide : true
		}, {
			name : 'createId',
			display : '������id',
			sortable : true,
			hide : true
		}, {
			name : 'createTime',
			display : '��������',
			sortable : true,
			hide : true
		}, {
			name : 'updateName',
			display : '¼����',
			sortable : true,
			hide : true
		}, {
			name : 'updateId',
			display : '�޸���id',
			sortable : true,
			hide : true
		}, {
			name : 'updateTime',
			display : '�޸�����',
			sortable : true,
			hide : true
		}],

		toViewConfig : {
			formWidth : 900,
			/**
			 * ������Ĭ�ϸ߶�
			 */
			formHeight : 380
		},



		sortorder : "ASC"

	});

});