var show_page = function(page) {
	$("#requireoutGrid").yxsubgrid("reload");
};
$(function() {
	$("#requireoutGrid").yxgrid({
		model : 'asset_require_requireout',
		action : 'jsonDetail',
		title : '�ʲ�ת������ϸ�б�',
		isToolBar : false,
		showcheckbox : false,
		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'requireCode',
			display : '������',
			sortable : true,
            process : function(v,row){
            	return "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=asset_require_requireout&action=toView&id=" + row.mainId
            		+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=850" + "\")'>" + v + "</a>";
            },
			width : 130
		}, {
			name : 'assetId',
			display : '�ʲ�id',
			sortable : true,
			hide : true
		}, {
			name : 'assetCode',
			display : '�ʲ����',
			sortable : true,
            process : function(v,row){
            	return "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=asset_assetcard_assetcard&action=init&perm=view&id=" + row.assetId
            		+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=850" + "\")'>" + v + "</a>";
            },
			width : 120
		}, {
			name : 'assetName',
			display : '�ʲ�����',
			sortable : true,
			width : 120
		}, {
			name : 'salvage',
			display : '�ʲ���ֵ',
			sortable : true,
			width : 80
		}, {
			name : 'productId',
			display : '����id',
			sortable : true,
			hide : true
		}, {
			name : 'productCode',
			display : '���ϱ��',
			sortable : true,
            process : function(v,row){
            	return "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=stock_productinfo_productinfo&action=view&id=" + row.productId 
            		+ "&tableName=oa_stock_product_info" 
            		+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=850" + "\")'>" + v + "</a>";
            },
			width : 80
		}, {
			name : 'productName',
			display : '��������',
			sortable : true,
			width : 120
		}, {
			name : 'spec',
			display : '�ͺ�',
			sortable : true,
			width : 120
		}, {
			name : 'number',
			display : '��������',
			sortable : true,
			width : 50
		}, {
			name : 'executedNum',
			display : '�������',
			sortable : true,
			width : 50
		}, {
			name : 'applyName',
			display : '������',
			sortable : true,
			width : 80
		}, {
			name : 'applyDeptName',
			display : '���벿��',
			sortable : true,
			width : 80
		}, {
			name : 'applyDate',
			display : '��������',
			sortable : true,
			width : 70
		}, {
			name : 'detaiRemark',
			display : '��ע',
			sortable : true,
			width : 200
		}],
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
				if (row) {
					showThickboxWin("?model=asset_require_requireout&action=toView&id="
							+ row.mainId
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=850");
				}
			}
		}, {
			text : '�ʲ���Ϣ',
			icon : 'view',
			action : function(row) {
				if (row) {
					showThickboxWin("?model=asset_assetcard_assetcard&action=init&perm=view&id="
							+ row.assetId
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=850");
				}
			}
		}, {
			text : '������Ϣ',
			icon : 'view',
			action : function(row) {
				if (row) {
					showThickboxWin("?model=stock_productinfo_productinfo&action=view&id="
							+ row.productId
							+ "&tableName=oa_stock_product_info"
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=850");
				}
			}
		}],
		searchitems : [{
			display : "������",
			name : 'requireCode'
		}, {
			display : "������",
			name : 'applyName'
		}, {
			display : "���벿��",
			name : 'applyDeptName'
		},{
			display : "�ʲ����",
			name : 'assetCode'
		},{
			display : "�ʲ�����",
			name : 'assetName'
		},{
			display : "���ϱ��",
			name : 'productCode'
		},{
			display : "��������",
			name : 'productName'
		}]
	});
});