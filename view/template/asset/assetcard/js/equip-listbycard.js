// ��������/�޸ĺ�ص�ˢ�±��

var show_page = function(page) {
	$("#equipbycard").yxgrid('reload');
};

$(function() {
	$("#equipbycard").yxgrid({

		model : 'asset_assetcard_equip',
		height : '450px',
		param : {
			'assetCode' : $("#assetCode").val()
		},
		title : '�����豸��Ϣ',
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : '�豸���',
			name : 'equipCode',
			sortable : true,
			width : 160
		}, {
			display : '�豸����',
			name : 'equipName',
			sortable : true,
			// ���⴦���ֶκ���
			process : function(v, row) {
				return row.equipName;
			},
			width : 150
		}, {
			display : '�Ǽ�����',
			name : 'regDate',
			sortable : true,
			width : 70
		}, {
			display : '����ͺ�',
			name : 'spec',
			sortable : true
		}, {
			display : '������λ',
			name : 'unit',
			sortable : true,
			width : 70
		}, {
			display : '����',
			name : 'num',
			sortable : true,
			width : 70
		}, {
			display : '���',
			name : 'account',
			sortable : true,
			width : 70
		}, {
			display : '��ŵص�id',
			name : 'placeId',
			sortable : true,
			hide : true
		}, {
//			display : '��ŵص�',
//			name : 'place',
//			sortable : true
//		}, {
			display : '��ע',
			name : 'remark',
			sortable : true,
//			hide : true,
			width : 160
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
//		buttonsEx : [{
//		}, {
//			name : 'Review',
//			text : "����",
//			icon : 'view',
//			action : function() {
//				history.back();
//			}
//		}],
		toEditConfig : {
			formWidth : 900,
			/**
			 * ������Ĭ�ϸ߶�
			 */
			formHeight : 400
		},
		toViewConfig : {
			formWidth : 900,
			/**
			 * ������Ĭ�ϸ߶�
			 */
			formHeight : 380
		},

		// ��������
		searchitems : [{
			display : '�豸���',
			name : 'equipId'
		}, {
			display : '�豸����',
			name : 'equipName'
		}],

		sortorder : "ASC"

	});

});