// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$(".suppfileGrid").yxgrid("reload");
};
$(function() {
	$(".suppfileGrid").yxgrid({
		// �������url�����ô����url������ʹ��model��action�Զ���װ
		url : '?model=file_uploadfile_management&action=pageJson&objTable='
				+ $("#objTable").val() + '&objId=' + $("#objId").val(),
		// model : '?model=file_uploadfile_management',
		// action : 'pageJson&objTable=' + $('#objTable').val() + '&objId=' +
		// $('#objId').val(),
		showcheckbox : true,
		title : '������Ϣ',
		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : '�ϴ���',
			name : 'createName',
			sortable : true,
			// ���⴦���ֶκ���
			process : function(v, row) {
				return row.createName;
			}
		}, {
			display : '�ϴ�ʱ��',
			name : 'createTime',
			sortable : true,
			width : '150'
		}, {
			display : '�ļ���',
			name : 'originalName',
			sortable : true,
			width : '150'
		}, {
			display : '���ļ���',
			name : 'newName',
			sortable : true,
			width : '150'
		}, {
			display : '�ļ���С',
			name : 'tFileSize',
			sortable : true,
			process : function(v) {
				return v + ' <font color="green">kb</font>';
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
				if (rowIds[0]) {
					if (window.confirm("ȷ��Ҫɾ��?")) {
						$.ajax({
							type : "POST",
							url : "?model=file_uploadfile_management&action="
									+ p.toDelConfig.action
									+ p.toDelConfig.plusUrl,
							data : {
								id : g.getCheckedRowIds().toString()
							// ת������,������ʽ
							},
							success : function(msg) {
								if (msg == 1) {
									g.reload();
									alert('ɾ���ɹ���');
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

		// ��չ��ť
		buttonsEx : [{
			name : 'onload',
			text : '�ϴ�',
			icon : 'add',
			action : function() {
				showThickboxWin("?model=file_uploadfile_management&action=editInfo&objId="
						+ $('#objId').val()
						+ "&objTable="
						+ $('#objTable').val()
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=600");
			}
		}],
		// ��չ�Ҽ��˵�
		menusEx : [{
			text : '����',
			icon : 'edit',
			action : function(row, rows, grid) {
				if (row) {
					location = "?model=file_uploadfile_management&action=toDownFile&newName="
							+ row.newName
							+ "&originalName="
							+ row.originalName
							+ "&inDocument=" + row.inDocument;
				} else {
					alert("��ѡ��һ������");
				}
			}
		}, {
			text : '�鿴',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=file_uploadfile_management&action=readInfo&id="
							+ row.id
							+ "&objCode="
							+ row.objCode
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=600");
				} else {
					alert("��ѡ��һ������");
				}
			}
		}],
		// title : '�ͻ���Ϣ',
		/**
		 * �Ƿ���ʾ������
		 *
		 * @type Boolean
		 */
		isToolBar : true,
		// ҵ���������
		boName : '����',
		// Ĭ�������ֶ���
		sortname : "createName",
		// Ĭ������˳��
		sortorder : "ASC",
		// ��ʾ�鿴��ť
		isViewAction : false,
		// ������Ӱ�ť
		isAddAction : false,
		// ����ɾ����ť
		isDelAction : true,
		isEditAction : false

	});

});