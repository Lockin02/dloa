// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$(".suppfileGrid").yxgrid("reload");
};
$(function() {
	$(".suppfileGrid").yxgrid({
		// �������url�����ô����url������ʹ��model��action�Զ���װ
		url : '?model=file_uploadfile_management&action=pageJson&objTable='
				+ $("#objTable").val() + '&objId=' + $("#objId").val(),
		showcheckbox : false,
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
					process: function(v){
						return v + ' <font color="green">kb</font>';
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
			isToolBar : false,
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
		isDelAction : false,
		isEditAction : false
	});

});