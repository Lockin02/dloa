// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$(".detailsGrid").yxegrid('reload');
};
$(function() {
			$(".detailsGrid").yxegrid({
						// �������url�����ô����url������ʹ��model��action�Զ���װ
						// url :
						// '?model=customer_customer_customer&action=pageJson',
						model : 'engineering_serviceContract_serviceContract',
//						objName : 'serviceContract',
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
						searchitems : [
								{
									display : '��ͬ����',
									name : 'orderName'
								},{
									display : '��ͬ���',
									name : 'orderCodeOrTempSearch'
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