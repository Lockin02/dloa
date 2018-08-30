/** 
 *  ���Ͽ�Ƭ���б�
 * */
var show_page = function(page) {
	$("#cardStockList").yxgrid("reload");
};
$(function() {
	$("#cardStockList").yxgrid({
		model : 'asset_assetcard_assetcard',
		title : '�����ʲ�����',
	    isViewAction : false,
	    isEditAction : false,
	    isAddAction : false,
	    isDelAction : false,
	    param : {
		    useStatusCode : 'SYZT-YBF',
		    isSell : '0'
	    },

		colModel : [{
                display : '�ʲ�Id',
                name : 'id',
                sortable : true,
                hide : true
            },{
                display : '��Ƭ���',
                name : 'assetCode',
                sortable : true
            },{
                display : '�ʲ�����',
                name : 'assetName',
                sortable : true
            },{
                display : '����ͺ�',
                name : 'spec',
                sortable : true
            },{
                display : '��������',
                name : 'buyDate',
                sortable : true
            },{
                display : '�ʲ�ԭֵ',
                name : 'origina',
                sortable : true,
                //�б��ʽ��ǧ��λ
                process : function(v){
					return moneyFormat2(v);
				}
            },{
				display : '��ֵ',
				name : 'salvage',
				sortable : true,
				// �б��ʽ��ǧ��λ
				process : function(v) {
					return moneyFormat2(v);
				}
			},{
				display : '��ֵ',
				name : 'netValue',
				sortable : true,
				// �б��ʽ��ǧ��λ
				process : function(v) {
					return moneyFormat2(v);
				}
			},{
				display : '�����۾�',
				name : 'depreciation',
				sortable : true,
				// �б��ʽ��ǧ��λ
				process : function(v) {
						return moneyFormat2(v);
				}
			},{
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
			},{
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
			},{
                display : '��ע',
                name : 'remark',
                sortable : true
            }],
        buttonsEx : [{
			name : 'Add',
			text : "ȷ��ѡ��",
			icon : 'add',
			action: function(row,rows,idArr) {
				if(row){
					showThickboxWin("?model=asset_disposal_sell&action=toScrapAssetList&assetIdArr="
							+ idArr
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=1050");
				}else{
					alert('��ѡ��һ������');
				}
			}
        }],
		// ��չ�Ҽ��˵�
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
				window.open('?model=asset_assetcard_assetcard&action=init&perm=view&id='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
			}
		},{
			name : 'aduit',
			text : '��д���۵�',
			icon : 'add',
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=asset_disposal_sell&action=toScrapAssetList&assetIdArr="
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
