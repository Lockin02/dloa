// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$("#supplinkmanGrid").reload();
};
$(function() {
			$(".supplinkmanGrid").yxgrid({
						//�������url�����ô����url������ʹ��model��action�Զ���װ
						// url :
						// '?model=customer_customer_customer&action=pageJson',
						model : 'supplierManage_register_supplinkman',
						// action : 'pageJson',
						//����Ϣ
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									display : '����',
									name : 'name',
									sortable : true,
									//���⴦���ֶκ���
									process : function(v,row) {
										return row.name;
									}
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
						buttonsEx : [{
									name : 'goon',
									text : "��һ��",
									icon : 'add',
									action : function(){
										location="?model=supplierManage_register_suppProducts&action=toAdd";
									}
								}],
						//��չ�Ҽ��˵�
						menusEx : [{
									text : '�ύ����',
						action : function(row) {
							}
							}],
						//��������
						searchitems : [{
									display : '����',
									name : 'name'
								}],
						// title : '�ͻ���Ϣ',
						//ҵ���������
						boName : '��Ӧ����ϵ��',
						//Ĭ�������ֶ���
						sortname : "id",
						//Ĭ������˳��
						sortorder : "ASC",
						//�����չ��Ϣ
						toAddConfig : {
			// �����Ϣ�ڴ���չ
						// action:123
						}
					});

		});