var show_page = function(page) {
	$("#changeList").yxgrid("reload");

};

$(function() {
	$("#changeList").yxgrid({
		model : 'asset_basic_change',
		param : {
			isDel : "0"
		},
		isEditAction : false,
		isDelAction : false,
		title : '�䶯��ʽ',
		isToolBar : true,
		showcheckbox : false,
		sortname : 'id',
		sortorder : 'ASC',
		menusEx : [{

			text : '�༭',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.isSysType == "1") {
					return false;
				} else {
					return true;
				}

			},
			action : function(row) {

				showThickboxWin('?model=asset_basic_change&action=init&id='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=700');
			}
		}, {
			text : 'ɾ��',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.isSysType == "1") {
					return false;
				} else {
					return true;
				}

			},
			action : function(row) {
				if (window.confirm(("ȷ��ɾ����"))) {
					$.ajax({
						type : "POST",
						url : "?model=asset_basic_change&action=deletes&id="
								+ row.id,
						success : function(msg) {
							if (msg == 1) {
								alert('ɾ���ɹ�')
								$("#changeList").yxgrid("reload");
							} else {
								alert('ɾ��ʧ�ܣ��ö�������Ѿ�������!')
							}
						}
					});
				}
			}
		}],

		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : '����',
			name : 'code',
			sortable : true,
			align : 'center'
		}, {
			display : '�䶯��ʽ����',
			name : 'name',
			width : 170,
			sortable : true

		}, {
//			name : 'vouchers',
//			display : 'ƾ֤��',
//			width : 70,
//			sortable : true,
//			hide : true
//		}, {
//			name : 'subName',
//			display : '�Է���Ŀ����',
//			width : 170,
//			sortable : true,
//			hide : true
//		}, {
//			name : 'subcode',
//			display : '�Է���Ŀ����',
//			width : 170,
//			sortable : true,
//			hide : true
//		}, {
			display : '����',
			name : 'type',
			sortable : true,
			width : 70,
			process : function(val) {
				if (val == "0") {
					return "����";
				} else {
					return "����";
				}
			}

		}, {
			name : 'digest',
			display : 'ժҪ',
			width : 170,
			sortable : true

		}, {
			name : 'isSysType',
			display : '¼�뷽ʽ',
			width : 85,
			sortable : true,
			process : function(val) {
				if (val == "1") {
					return "ϵͳ";
				} else {
					return "�ֶ�";
				}
			}
		}, {
//			name : 'isDel',
//			display : '�Ƿ�ɾ��',
//			width : 70,
//			sortable : true,
//			hide : true
//		}, {
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
			display : '�޸���',
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
		//		 ��չ��ť
		buttonsEx : [{
			name : 'import',
			text : '����',
			icon : 'excel',
			action : function(row, rows, grid) {
				showThickboxWin("?model=asset_basic_change&action=toImport"
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=200&width=550");
			}
		}],
		toAddConfig : {
			formWidth : 700,
			formHeight : 400
		},
		toViewConfig : {
			formWidth : 700,
			formHeight : 400
		},

		// Ĭ������˳��
		sortorder : "ASC"
			// ��չ��ť

	});
});