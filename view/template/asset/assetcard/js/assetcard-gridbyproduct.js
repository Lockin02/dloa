var show_page = function(page) {
	$("#assetcardGrid").yxgrid("reload");
};
function isRelated( assetId ){
	var equNum = 1;
	 $.ajax({
		type : 'POST',
		url : '?model=asset_assetcard_assetcard&action=isRelated',
		data : {
			id : assetId
		},
	    async: false,
		success : function(data) {
			equNum = data;
			return false;
		}
	})
	return equNum;
}
$(function() {
	$("#assetcardGrid").yxgrid({
		model : 'asset_assetcard_assetcard',
		title : '�̶��ʲ���Ƭ',
		param : {'productId':$('#productId').val()},
		customCode : 'assetcardGrid',
		showcheckbox : false,
		isEditAction : false,
		isViewAction : false,
		isDelAction : false,
		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'assetTypeName',
			display : '�ʲ����',
			sortable : true
		}, {
			name : 'assetCode',
			display : '��Ƭ���',
			sortable : true
		}, {
			name : 'assetName',
			display : '�ʲ�����',
			sortable : true
		}, {
			name : 'englishName',
			display : 'Ӣ������',
			hide : true,
			sortable : true
		}, {
			name : 'assetTypeId',
			display : '�ʲ����id',
			sortable : true,
			hide : true
		}, {
			name : 'unit',
			display : '������λ',
			hide : true,
			sortable : true
		}, {
			name : 'buyDate',
			display : '��������',
			sortable : true
		}, {
			name : 'userId',
			display : 'ʹ����id',
			sortable : true,
			hide : true
		}, {
			name : 'userName',
			display : 'ʹ����',
			sortable : true
		}, {
			name : 'useStatusCode',
			display : 'ʹ��״̬����',
			sortable : true
		}, {
			name : 'useStatusName',
			display : 'ʹ��״̬',
			sortable : true
		}, {
			name : 'changeTypeCode',
			display : '�䶯��ʽ����',
			sortable : true,
			hide : true
		}, {
			name : 'changeTypeName',
			display : '�䶯��ʽ',
			sortable : true
		}, {
			name : 'useOrgId',
			display : 'ʹ�ò���id',
			sortable : true,
			hide : true
		}, {
			name : 'useOrgName',
			display : 'ʹ�ò�������',
			sortable : true
		}, {
			name : 'orgId',
			display : '��������id',
			sortable : true,
			hide : true
		}, {
			name : 'orgName',
			display : '������������',
			sortable : true
		}, {
			name : 'useProId',
			display : 'ʹ����ĿId',
			sortable : true
		}, {
			name : 'useProName',
			display : 'ʹ����Ŀ',
			sortable : true
		}, {
			name : 'spec',
			display : '����ͺ�',
			sortable : true,
			hide : true
		}, {
			name : 'deprName',
			display : '�۾ɷ�ʽ����',
			sortable : true,
			hide : true
		}, {
			name : 'subName',
			display : '�̶��ʲ���Ŀ����',
			sortable : true,
			hide : true
		}, {
			name : 'depSubName',
			display : '�ۼ��۾ɿ�Ŀ����',
			sortable : true,
			hide : true
		}, {
			name : 'origina',
			display : '����ԭֵ',
			hide : true,
			sortable : true
		}, {
			name : 'buyDepr',
			display : '�����ۼ��۾�',
			hide : true,
			sortable : true
		}, {
			name : 'beginTime',
			display : '��ʼʹ������',
			sortable : true,
			hide : true
		}, {
			name : 'estimateDay',
			display : 'Ԥ��ʹ���ڼ���',
			sortable : true,
			hide : true
		}, {
			name : 'alreadyDay',
			display : '��ʹ���ڼ���',
			sortable : true,
			hide : true
		}, {
			name : 'depreciation',
			display : '�ۼ��۾�',
			hide : true,
			sortable : true
		}, {
			name : 'salvage',
			display : 'Ԥ�ƾ���ֵ',
			hide : true,
			sortable : true
		}, {
			name : 'netValue',
			display : '��ֵ',
			hide : true,
			sortable : true
		}, {
			name : 'version',
			display : '�汾��',
			sortable : true
		}, {
			name : 'isDel',
			display : '�Ƿ�����',
			hide : true,
			sortable : true,
				process : function(val) {
						if (val == "0") {
							return "δ����";
						}
						if (val == "1") {
							return "������";
						}
					}
		}, {
		    name : 'isScrap',
			display : '����״̬',
			hide : true,
			sortable : true,
				process : function(val) {
						if (val == "0") {
							return "δ����";
						}
						if (val == "1") {
							return "�ѱ���";
						}
					}
		}],
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
				window.open('?model=asset_assetcard_assetcard&action=init&perm=view&id='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
			}
		}],
		/**
		 * ��������
		 */
		searchitems : [{
			display : '��Ƭ���',
			name : 'assetCode'
		}, {
			display : '�ʲ�����',
			name : 'assetName'
		}]
	});
});