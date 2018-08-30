/**
 * �����ʲ�������Ϣ�б�
 *
 * @linzx
 */
var show_page = function(page) {
	$("#datadictList").yxgrid("reload");
};
$(function() {
	$billNo = $("#billNo").val(),
	// alert($("#sellID").val()),
	$("#datadictList").yxgrid({
		model : 'asset_disposal_scrapitem',
		//����c���sellitem��sellCardJson()�����ӿ�Ƭ�����ȡҪ��ʾ���ֶ�
		action : 'scrapCardJson',
		param : {
			allocateID : $("#allocateID").val(),
			billNo : $("#billNo").val()
		},

		title : '�����ʲ�����',
		// isToolBar : true,
		showcheckbox : false,
		isViewAction : false,
		isEditAction : false,
		isAddAction : false,
		isDelAction : false,

		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : '���ϵ�Id',
			name : 'allocateID',
			sortable : true,
			hide : true
		},{
			//�ӿ�Ƭ���ȡ��
			display : '�ʲ����',
			name : 'assetTypeName',
			sortable : true,
			width : 70
		}, {
			display : '��Ƭ���',
			name : 'assetCode',
			sortable : true,
			width : 160
		},  {
			display : '�ʲ�����',
			name : 'assetName',
			sortable : true,
			width : 130
		}, {
			display : '�ʲ�Id',
			name : 'assetId',
			hide : true
		}, {
			display : '����ͺ�',
			name : 'spec',
			sortable : true
		}, {
			display : '��������',
			name : 'buyDate',
			sortable : true,
			width : 70
		}, {
			//�ӿ�Ƭ�����ȡ��
			display : '�ʲ�ԭֵ',
			name : 'origina',
			sortable : true,
			// �б��ʽ��ǧ��λ
			process : function(v) {
				return moneyFormat2(v);
			},
			width : 70
		}, {
			display : '��ֵ',
			name : 'salvage',
			sortable : true,
			// �б��ʽ��ǧ��λ
			process : function(v) {
				return moneyFormat2(v);
			},
			width : 70
		}, {
			display : '�����۾�',
			name : 'depreciation',
			sortable : true,
			// �б��ʽ��ǧ��λ
			process : function(v) {
				return moneyFormat2(v);
			},
			width : 70
		}, {
			//�ӿ�Ƭ�����ȡ��
			display : '����״̬',
			name : 'isDel',
			sortable : true,
			process : function(val) {
				if (val == "0") {
					return "δ����";
				}
				if (val == "1") {
					return "������";
				}
			},
			width : 70
		}, {
			display : '��ע',
			name : 'remark',
			sortable : true,
			width : 120
		}],
		buttonsEx : [{
			name : 'Review',
			text : "����",
			icon : 'view',
			action : function() {
				history.back();
			}
		}],
		// ��չ�Ҽ��˵�
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
				window
						.open('?model=asset_assetcard_assetcard&action=init&perm=view&id='
								+ row.assetId
								+ '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
			}
		}, {
			name : 'aduit',
			text : '����',
			icon : 'add',
			showMenuFn : function(row) {
				if ((row.isDel == "0")) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					// alert(row.sellID);
					// alert(row.assetCode);
					// alert(row.assetId);
					showThickboxWin("?model=asset_assetcard_clean&action=toAdd&billID="
							+ row.allocateID
							+ "&billType=scrap"
							+ "&billNo="
							+ $billNo
							+ "&assetTypeName="
							+ row.assetTypeName
							+ "&assetCode="
							+ row.assetCode
							+ "&assetId="
							+ row.assetId
							+ "&assetName="
							+ row.assetName
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=1050");
				}
			}
		}],

		searchitems : [{
			display : '��Ƭ���',
			name : 'assetCode'
		}, {
			display : '�ʲ�����',
			name : 'assetName'
		}],
		// Ĭ�������ֶ���
		sortname : "id",
		// Ĭ������˳�� ����
		sortorder : "DESC"

	});
});
