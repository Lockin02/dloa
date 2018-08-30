var show_page = function(page) {
	$("#assetTempGrid").yxgrid("reload");
};
$(function() {
	$("#assetTempGrid").yxgrid({
		model : 'asset_assetcard_assetTemp',
//		param : {'isCreate': 0},
		action : 'adminJson',
		title : '����ȷ�Ͽ�Ƭ�б�',
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'assetName',
			display : '�ʲ�����',
			sortable : true
		}, {
			name : 'machineCode',
			display : '������',
			sortable : true
		}, {
			name : 'assetTypeName',
			display : '�ʲ����',
			sortable : true,
			process : function(v,row){
				if(row.assetTypeName == "�ֻ�" && (row.mobileBand != "" || row.mobileNetwork != "")){
					return v+" <img src='images/icon/msg.png' style='width:14px;height:14px;' title='�ֻ�Ƶ��: " +
					row.mobileBand+"���ֻ�����:"+row.mobileNetwork+"'/>";
				}
				return v;
			}
		}, {
			name : 'mobileBand',
			display : '�ֻ�Ƶ��',
			sortable : true,
			hide : true
		}, {
			name : 'mobileNetwork',
			display : '�ֻ�����',
			sortable : true,
			hide : true
		}, {
			name : 'buyDate',
			display : '��������',
			sortable : true
		}, {
			name : 'brand',
			display : 'Ʒ��',
			sortable : true
		}, {
			name : 'spec',
			display : '����ͺ�',
			sortable : true
		}, {
			name : 'deploy',
			display : '����',
			sortable : true
		}, {
			name : 'belongMan',
			display : '������',
			sortable : true
		}, {
			name : 'assetSourceName',
			display : '�ʲ���Դ����',
			sortable : true
		}, {
			name : 'productName',
			display : '������������',
			sortable : true
		}, {
			name : 'agencyName',
			display : '������������',
			sortable : true
		}],

		showcheckbox : false,
		isAddAction : false,
		isEditAction : false,
		isViewAction : false,
		isDelAction : false,
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
				window.open('?model=asset_assetcard_assetTemp&action=init&perm=view&id='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
			}
		}, {
			text : 'ȷ���ʲ�����',
			icon : 'add',
			showMenuFn : function(row) {
				if(row.isCreate=='0'){
					return true;
				}else{
					return false;
				}
			},
			action : function(row) {
				window.open('?model=asset_assetcard_assetTemp&action=totype&id='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
			}
		}],
		comboEx : [{
			text : '�Ƿ�ȷ��',
			key : 'isCreate',
			data : [{
				text : '��',
				value : '1'
			}, {
				text : '��',
				value : '0'
			}],
			value : '0'
		}],
		/**
		 * ��������
		 */
		searchitems : [{
			display : '�ʲ�����',
			name : 'assetName'
		}, {
//			display : 'ʹ����',
//			name : 'userName'
//		}, {
//			display : 'ʹ�ò���',
//			name : 'useOrgName'
//		}, {
			display : '��������',
			name : 'agencyName'
		}, {
			display : '������',
			name : 'belongMan'
		}, {
			display : '��������',
			name : 'orgName'
//		}, {
//			display : 'Ʒ��',
//			name : 'brand'
		}]
	});
});