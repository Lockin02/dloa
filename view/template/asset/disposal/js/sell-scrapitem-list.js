/** �����ʲ�������Ϣ�б�
 *  @linzx
 * */
var show_page = function(page) {
	$("#datadictList").yxgrid("reload");
};
$(function() {
	$("#datadictList").yxgrid({
		model : 'asset_disposal_scrapitem',
		//����c���scrapitem��scrapCardJson()�����ӿ�Ƭ�����ȡҪ��ʾ���ֶ�
		action : 'scrapCardJson',
		param:{allocateID:$("#allocateID").val(),billNo:$("#billNo").val()},
		title : '�����ʲ�����',
		//isToolBar : true,
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
            },
            {
                display : '���ϵ�Id',
                name : 'allocateID',
                sortable : true,
                hide : true
            },
//            {
//                display : '���ϵ����',
//                name : 'billNo',
//                sortable : true
//            },
            {
                display : '��Ƭ���',
                name : 'assetCode',
                sortable : true
            },
            {
                display : '�ʲ�����',
                name : 'assetName',
                sortable : true
            },
            {
				display : '�ʲ�Id',
				name : 'assetId',
                hide : true
			},
            {
                display : '����ͺ�',
                name : 'spec',
                sortable : true
            },
            {
                display : '��������',
                name : 'buyDate',
                sortable : true
            },
            {
                display : '�ʲ�ԭֵ',
                name : 'origina',
                sortable : true,
                //�б��ʽ��ǧ��λ
                process : function(v){
					return moneyFormat2(v);
				}
            },
            {
				display : '��ֵ',
				name : 'salvage',
				sortable : true,
				// �б��ʽ��ǧ��λ
				process : function(v) {
					return moneyFormat2(v);
				}
			},
			{
				display : '��ֵ',
				name : 'netValue',
				sortable : true,
				// �б��ʽ��ǧ��λ
				process : function(v) {
					return moneyFormat2(v);
				}
			},
			{
				display : '�����۾�',
				name : 'depreciation',
				sortable : true,
				// �б��ʽ��ǧ��λ
				process : function(v) {
						return moneyFormat2(v);
				}
			},
			//���ϴӱ���ĳ���״̬��ԭ������
//			{
//				display : '����״̬',
//				name : 'sellStatus',
//				sortable : true
//			},
			//�ʲ���Ƭ����ĳ���״̬
			{
				display : '����״̬',
				name : 'isSell',
				sortable : true,
				process : function(val) {
						if (val == "0") {
							return "δ����";
						}
						if (val == "1") {
							return "�ѳ���";
						}
					}
			},
			{
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
					}
			},
      		{
                display : '��ע',
                name : 'remark',
                sortable : true
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
				window.open('?model=asset_assetcard_assetcard&action=init&perm=view&id='
						+ row.assetId
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
			}
		},{
			name : 'aduit',
			text : '��д���۵�',
			icon : 'add',
			    showMenuFn:function(row){
			    	if((row.isSell=="0"&&row.isDel=="0")){
			    	   return true;
			    	}
			    	return false;
			    },
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=asset_disposal_sell&action=toScrapAsset&allocateID="
							+ row.allocateID
							+"&assetCode="
							+row.assetCode
							+"&assetId="
							+row.assetId
							+"&scrapitemId="
							+row.id
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=1050");
				}
			}
		}],

		searchitems : [{
			display : '��Ƭ���',
			name : 'assetCode'
		},{
			display : '�ʲ�����',
			name : 'assetName'
		}],
		// Ĭ�������ֶ���
			sortname : "id",
		// Ĭ������˳�� ����
			sortorder : "DESC"

	});
});
