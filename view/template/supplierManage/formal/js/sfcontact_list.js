// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$(".sfcontactGrid").yxgrid("reload");
};
$(function() {
			$(".sfcontactGrid").yxgrid({
						//�������url�����ô����url������ʹ��model��action�Զ���װ
//						 url :
//						 '?model=supplierManage_formal_sfcontact&action=pageJson&parentId='+$("#parentId").val(),
						 model : 'supplierManage_formal_sfcontact',
						action : 'pageJson&parentId='+$("#parentId").val(),
//						isToolBar : false,
						//��ʾ�鿴��ť
						isViewAction : true,
						//������Ӱ�ť
						isAddAction : false,
						//����ɾ����ť
						isDelAction : false,
						showcheckbox:false,
						//����Ϣ
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									display : '��ϵ������',
									name : 'name',
									sortable : true,
									//���⴦���ֶκ���
									process : function(v,row) {
										return row.name;
									}
								},{
									display : 'ְλ',
									name : 'position',
									sortable : true
								},{
									display : '��Ӧ�̱��',
									name : 'busiCode',
									sortable : true,
									hide : true
								},{
									display : '��ϵ�绰',
									name : 'mobile1',
									sortable : true
								},{
									display : '�����ַ',
									name : 'email',
									sortable : true
								}, {
									display : '����',
									name : 'plane',
									sortable : true
								}, {
									display : '����',
									name : 'fax',
									sortable : true
								}],
						//��չ��ť
						buttonsEx : [],
						//��չ�Ҽ��˵�
						menusEx : [],
						//��������
						searchitems : [{
									display : '��ϵ������',
									name : 'name'
								}],
						// title : '�ͻ���Ϣ',
						//ҵ���������
						boName : '��Ӧ����ϵ��',
						//Ĭ�������ֶ���
						sortname : "updateTime",
						//Ĭ������˳��
						sortorder : "DESC",
						//��ʾ�鿴��ť
						isViewAction : true,
						isAddAction : false,
						isEditAction : false,
						isDelAction : false,
						//�鿴��չ��Ϣ
						toViewConfig : {
							action : 'toRead',
							formWidth : 500,
							formHeight : 340
						}

					});

		});