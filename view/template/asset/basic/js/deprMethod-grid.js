var show_page = function(page) {
	$("#deprMethodGrid").yxgrid("reload");
};
$(function() {
	$("#deprMethodGrid").yxgrid({
		model : 'asset_basic_deprMethod',
		title : '�۾ɷ�ʽ',
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'code',
			display : '����',
			width : 110,
			sortable : true
		}, {
			name : 'name',
			display : '�۾�����',
			width : 110,
			sortable : true
		}, {
			name : 'describes',
			display : '��ʽ��������',
			sortable : true,
			width : 500
//		}, {
//			name : 'expression',
//			display : '��ʽ�ֶ�����',
//			sortable : true,
//			width : 300
//		}, {
//			name : 'remark',
//			display : '˵��',
//			sortable : true,
//			width : 200
		}],
//		//		 ��չ��ť
//		buttonsEx : [{
//			name : 'import',
//			text : '����',
//			icon : 'excel',
//			action : function(row, rows, grid) {
//				showThickboxWin("?model=asset_basic_deprMethod&action=toImport"
//						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=350&width=550");
//			}
//		}],
		toViewConfig : {
			/**
			 * �鿴��Ĭ�Ͽ��
			 */
			formWidth : 700,
			/**
			 * �鿴��Ĭ�ϸ߶�
			 */
			formHeight : 300
		}
	});
});