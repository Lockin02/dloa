/** �ʲ�������Ϣ�б�
 *  @linzx
 * */

var show_page = function(page) {
	$("#datadictList").yxgrid("reload");
};

$(function() {

	$("#datadictList").yxgrid({
		model : 'asset_basic_directory',
		title : '�ʲ�����',
		isToolBar : true,
		//isViewAction : false,
		isEditAction : false,
		//isAddAction : false,
		//showcheckbox : false,
		sortname : 'id',
		sortorder : 'ASC',

		//���Ӱ�ť
		//		buttonsEx : [{
		//			name : 'Add',
		//			// hide : true,
		//			text : "��ʼ��",
		//			icon : 'edit',
		//
		//			action : function(row) {
		//				location='?model=asset_basic_directory'
		//			}
		//		}],

		// ��չ�Ҽ��˵�

		menusEx : [{
			text : '�༭',
			icon : 'edit',
			action : function(row) {
				showThickboxWin('?model=asset_basic_directory&action=toEdit&id='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=1000');
			}
		}],
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : '�ʲ�������',
			name : 'code',
			sortable : true
		}, {
			display : '�ʲ����',
			name : 'name',
			sortable : true
		}, {
//			display : '�̶��ʲ���Ŀ',
//			name : 'assetSubject',
//			sortable : true
//		}, {
			display : 'ʹ������',
			name : 'limitYears',
			sortable : true
		}, {
			display : '����ֵ��',
			name : 'salvage',
			sortable : true
		}, {
			display : '������λ',
			name : 'unit',
			sortable : true
		}, {
//			display : 'Ԥ���۾ɷ���Id',
//			name : 'deprId',
//			sortable : true,
//			hide : true
//		}, {
			display : 'Ԥ���۾ɷ���',
			name : 'depr',
			sortable : true

		}, {
//			display : '�۾ɿ�ĿId',
//			name : 'subId',
//			sortable : true,
//			hide : true
//		}, {
//			display : '�۾ɿ�Ŀ',
//			name : 'subName',
//			sortable : true,
//			hide : true
//		}, {
			display : '�۾�״̬',
			name : 'isDepr',
			sortable : true,
			width : 170,
			process : function(val) {
				if (val == "1") {
					return "��ʹ��״̬�����Ƿ����۾�";
				}
				if (val == "2") {
					return "����ʹ��״̬���һ�����۾�";
				}
				if (val == "3") {
					return "����ʹ��״̬���һ�������۾�";
				}
			}
		}],
		toAddConfig : {
			formWidth : 1000,
			formHeight : 300
		},
		toViewConfig : {
			formWidth : 1000,
			formHeight : 300
		},

		//		 ��չ��ť
		buttonsEx : [{
			name : 'import',
			text : '����',
			icon : 'excel',
			action : function(row, rows, grid) {
				showThickboxWin("?model=asset_basic_directory&action=toImport"
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=200&width=550");
			}
		}],
		/**
		 * ɾ����������
		 */
		toDelConfig : {
			text : 'ɾ��',
			/**
			 * Ĭ�ϵ��ɾ����ť�����¼�
			 */
			toDelFn : function(p, g) {
				var rowIds = g.getCheckedRowIds();
				var rowObj = g.getFirstSelectedRow();
				var key = "";
				if (rowObj) {
					var rowData = rowObj.data('data');
					if (rowData['skey_']) {
						key = rowData['skey_'];
					}
				}
				if (rowIds[0]) {
					if (window.confirm("ȷ��Ҫɾ��?")) {
						$.ajax({
							type : "POST",
							url : "?model=" + p.model + "&action="
									+ p.toDelConfig.action
									+ p.toDelConfig.plusUrl,
							data : {
								id : g.getCheckedRowIds().toString(),
								skey : key
							// ת������,������ʽ
							},
							success : function(msg) {
								if (msg == 1) {
									if (window.show_page != "undefined") {
										show_page();
									} else {
										g.reload();
									}
									alert('ɾ���ɹ���');
								} else if (msg == 2) {
									alert('�Ƿ�����');
								} else if (msg != '') {
									alert('�÷����ѱ����ã�������ɾ����');
								} else {
									alert('ɾ��ʧ�ܣ��ö�������Ѿ�������!');
								}
							}
						});
					}
				} else {
					alert('��ѡ��һ�м�¼��');
				}
			},
			/**
			 * ɾ��Ĭ�ϵ��õĺ�̨����
			 */
			action : 'ajaxdeletes',
			/**
			 * ׷�ӵ�url
			 */
			plusUrl : ''
		},

		searchitems : [{
			display : '�ʲ����',
			name : 'name'
		}, {
			display : '�ʲ�������',
			name : 'code'
		}, {
			display : '�۾ɿ�Ŀ',
			name : 'subName'
		}, {
			display : '�̶��ʲ���Ŀ',
			name : 'assetSubject'
		}],
		// ҵ���������
		//	boName : 'ȫ��',
		// Ĭ�������ֶ���
		sortname : "id",
		// Ĭ������˳�� ����
		sortorder : "DESC"

	});
});
