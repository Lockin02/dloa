// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$(".licenseGrid").yxegrid('reload');
};
$(function() {
			$(".licenseGrid").yxegrid({
						// �������url�����ô����url������ʹ��model��action�Զ���װ
						// url :
						// '?model=customer_customer_customer&action=pageJson',
						model : 'engineering_servicelicense_servicelicense',
//						objName : 'servicelicense',
						action : 'pageJson',
						// ����Ϣ
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									display : '���������',
									name : 'softdogType',
									sortable : true,
									sortname : 'c.softdogType',
									editor : {
										defVal : '���������һ'
									}
								}, {
									display : '����',
									name : 'amount',
									sortable : true
								}, {
									display : 'Licenseֵ',
									editor : {
										type : 'text'
									},
									name : 'licenseTypeIds',
									sortable : true
								}
//								, {
//									display : '�ͻ�����',
//									name : 'TypeOne',
//									hiddenName : 'TypeOneName',// �����ύ������ֵ
//									datacode : 'KHLX',// �����ֵ����
//									editor : {
//										type : 'combo',
//										// Ĭ��ѡ���һ��
//										defValIndex : 1
//
//									},
//									sortable : true
//								}
								, {
									display : 'License����',
									name : 'licenseType',
									sortable : true
								}],
						//��չ��ť
						buttonsEx : [
							{
								name : 'submit',
								text : '�ύ��ѡLicense',
								icon : 'add',
								action : function(row,rows,grid){

								}
							}
						],
						// ��������
						searchitems : [{
									display : '���������',
									name : 'customerType'
								}, {
									display : 'License����',
									name : 'licenseType',
									isdefault : true
								}],
						// title : '�ͻ���Ϣ',
						// ҵ���������
						boName : 'License',
						// Ĭ�������ֶ���
						sortname : "id",
						// Ĭ������˳��
						sortorder : "ASC"
					});

		});