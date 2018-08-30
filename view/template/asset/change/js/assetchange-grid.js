var show_page = function(page) {
	$("#changeGrid").yxgrid("reload");
};
$(function() {
	$("#changeGrid").yxgrid({
		model : 'asset_change_assetchange',
		param : {'assetId':$('#assetId').val()},
		isAddAction : false,
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,

		title : '�䶯��¼',

		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : '�ʲ�Id',
			name : 'acId',
			sortable : true,
			hide : true
		}, {
			name : 'businessCode',
			display : 'ҵ�񵥱��',
			sortable : true,
			process : function(v,row){
				if(row.businessId){
					switch (row.businessType) {
						case 'borrow' :
								return '<a href="#" onclick="javascript:window.open(\'?model=asset_daily_borrow&action=init&perm=view&id='+row.businessId+'\')">'+row.businessCode+'</a>';
							break;
						case 'allocation' :
								return '<a href="#" onclick="javascript:window.open(\'?model=asset_daily_allocation&action=init&perm=view&id='+row.businessId+'\')">'+row.businessCode+'</a>';
							break;
						case 'charge' :
								return '<a href="#" onclick="javascript:window.open(\'?model=asset_daily_charge&action=init&perm=view&id='+row.businessId+'\')">'+row.businessCode+'</a>';
							break;
						case 'return' :
								return '<a href="#" onclick="javascript:window.open(\'?model=asset_daily_return&action=init&perm=view&id='+row.businessId+'\')">'+row.businessCode+'</a>';
							break;
						case 'keep' :
								return '<a href="#" onclick="javascript:window.open(\'?model=asset_daily_keep&action=init&perm=view&id='+row.businessId+'\')">'+row.businessCode+'</a>';
							break;
						default :
							break;
					}
				}
			},
			width : 120
		}, {
			name : 'alterDate',
			display : '�䶯����',
			sortable : true,
			width : 70
		}, {
			name : 'assetTypeName',
			display : '�ʲ����',
			sortable : true,
			width : 70
		}, {
			name : 'assetCode',
			display : '��Ƭ���',
			sortable : true,
			width : 160
		}, {
			name : 'assetName',
			display : '�ʲ�����',
			sortable : true,
			width : 150
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
			sortable : true,
			width : 70
		}, {
			name : 'wirteDate',
			display : '��������',
			sortable : true,
			width : 70
		}, {
			name : 'useStatusId',
			display : 'ʹ��״̬id',
			sortable : true,
			hide : true
		}, {
			name : 'useStatusName',
			display : 'ʹ��״̬',
			sortable : true,
			width : 70
		}, {
			name : 'spec',
			display : '����ͺ�',
			sortable : true,
			hide : true
		}, {
			name : 'remark',
			display : '��ע',
			hide : true,
			sortable : true
		}, {
			name : 'subId',
			display : '�̶��ʲ���Ŀid',
			sortable : true,
			hide : true
		}, {
			name : 'subName',
			display : '�̶��ʲ���Ŀ����',
			sortable : true,
			hide : true
		}, {
			name : 'useOrgId',
			display : 'ʹ�ò���id',
			sortable : true,
			hide : true
		}, {
			name : 'useOrgName',
			display : 'ʹ�ò�������',
			sortable : true,
			width : 80
		}, {
			name : 'orgId',
			display : '��������id',
			sortable : true,
			hide : true
		}, {
			name : 'orgName',
			display : '������������',
			sortable : true,
			width : 80
		}, {
			name : 'origina',
			display : '����ԭֵ',
			hide : true,
			sortable : true
		}, {
			name : 'version',
			display : '�汾��',
			sortable : true,
			width : 50
		}],
		buttonsEx : [{
			name : 'Review',
			text : "����",
			icon : 'view',
			action : function() {
				history.back();
			}
		}],
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
				showThickboxWin('?model=asset_assetcard_assetcard&action=toViewDetail&perm=view&id='
						+ row.acId
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
			}
		}]
	});
});