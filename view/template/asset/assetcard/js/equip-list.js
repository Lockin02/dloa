// ��������/�޸ĺ�ص�ˢ�±��

var show_page = function(page) {
	$("#equipGrid").yxgrid('reload');
};

$(function() {
	var del;
	if($("#sysLimit").val() == 0){
		del = false;
	}
	else{
		del = true;
	}
	$("#equipGrid").yxgrid({
		model : 'asset_assetcard_equip',
		param : {
			'assetId' : $("#assetId").val()
		},
		title : '�����豸��Ϣ',
		isDelAction : del,
		isAddAction : false,
		isEditAction : false,
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
			width : 80
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
            //�б��ʽ��ǧ��λ
             process : function(v){
				return moneyFormat2(v);
				},
			width : 70
		}, {
			display : '��ŵص�id',
			name : 'placeId',
			sortable : true,
			hide : true
		}, {
			display : '��ŵص�',
			name : 'place',
			sortable : true,
			hide : true
		}, {
			display : '��ע',
			name : 'remark',
			sortable : true,
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
		buttonsEx : [{
			text : '����',
			icon : 'add',
			name : 'Add',
			action : function(row) {
				showThickboxWin('?model=asset_assetcard_equip&action=toAdd&assetCode='
						+ $("#assetCode").val()
						+ "&assetId="
						+ $("#assetId").val()
						+ '&isFromCustomer=1&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
			}

		}, {
			name : 'Review',
			text : "����",
			icon : 'view',
			action : function() {
				history.back();
			}
		}],
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
           // Ĭ�������ֶ���
			sortname : "id",
           // Ĭ������˳��
			sortorder : "DESC"

	});

});