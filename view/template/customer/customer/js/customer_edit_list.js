// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$(".customerGrid").yxegrid('reload');
};
$(function() {
			$(".customerGrid").yxegrid({
						// �������url�����ô����url������ʹ��model��action�Զ���װ
						// url :
						// '?model=customer_customer_customer&action=pageJson',
						usepager : true,
						pageSize : 10,
						model : 'customer_customer_customer',
						objName : 'customer',
						// action : 'pageJson',
						// ����Ϣ
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									display : '�ͻ�����',
									name : 'Name',
									sortable : true,
									sortname : 'c.Name',
									editor : {
										defVal : 'Ĭ�Ͽͻ�����'
									}
								}, {
									display : '��������',
									name : 'AreaLeader',
									sortable : true
								}, {
									display : '���۹���ʦ',
									editor : {
										type : 'text'
									},
									name : 'SellMan',
									sortable : true
								}, {
									display : '�ͻ�����',
									name : 'TypeOne',
									hiddenName : 'TypeOneName',// �����ύ������ֵ
									datacode : 'KHLX',// �����ֵ����
									editor : {
										type : 'combo',
										// Ĭ��ѡ���һ��
										defValIndex : 1

									},
									sortable : true
								}, {
									display : 'ʡ��',
									name : 'Prov',
									datacode : 'PROVINCE',
									sortable : true
								}],
						// ��������
						searchitems : [{
									display : '�ͻ�����',
									name : 'customerType'
								}, {
									display : '�ͻ�����',
									name : 'Name',
									isdefault : true
								}],
						// title : '�ͻ���Ϣ',
						// ҵ���������
						boName : '�ͻ�',
						// Ĭ�������ֶ���
						sortname : "id",
						// Ĭ������˳��
						sortorder : "ASC"
					});

		});