// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$("#supplierGrid").reload();
};
$(function() {
			$(".supplierGrid").yxgrid({
						//�������url�����ô����url������ʹ��model��action�Զ���װ
						// url :
						// '?model=customer_customer_customer&action=pageJson',
						model : 'supplierManage_register_register',
						// action : 'pageJson',
						//����Ϣ
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									display : '��Ӧ������',
									name : 'suppName',
									sortable : true,
									//���⴦���ֶκ���
									process : function(v,row) {
										return row.suppName;
									}
								}, {
									display : 'ҵ����',
									name : 'basiCode',
									sortable : true
								}, {
									display : '��Ҫ��Ʒ',
									name : 'products',
									sortable : true
								}, {
									display : '��ַ',
									name : 'address',
									sortable : true
								}, {
									display : '����',
									name : 'fax',
									sortable : true
								}, {
									display : '���״̬',
									name : 'ExaStatus',
									sortable : true
								}, {
									display : '������Ч����',
									name : 'effectDate',
									sortable : true
								}, {
									display : '����ʧЧ����',
									name : 'failureDate',
									sortable : true
								}],
						//��չ��ť
//						buttonsEx : [{
//									name : 'Add',
//									text : "��չ��ť1",
//									icon : 'add'
//								}, {
//									separator : true
//								}, {
//									name : 'Delete',
//									text : "��չ��ť2",
//									icon : 'delete'
//								}],
						//��չ�Ҽ��˵�
						menusEx : [{
									text : '�ύ����',
						action : function(row) {
							}
							}],
						//��������
						searchitems : [{
									display : '�ͻ�����',
									name : 'customerType'
								}, {
									display : '�ͻ�����',
									name : 'Name',
									isdefault : true
								}],
						// title : '�ͻ���Ϣ',
						//ҵ���������
						boName : '��Ӧ��',
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