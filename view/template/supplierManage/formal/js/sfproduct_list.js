// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$(".sfproductGrid").yxgrid("reload");
};
$(function() {
			$(".sfproductGrid").yxgrid({
						//�������url�����ô����url������ʹ��model��action�Զ���װ
						 url :
						 '?model=supplierManage_formal_sfproduct&action=pageJson&parentId='+$("#parentId").val(),
						//����Ϣ
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									display : '��Ʒ����',
									name : 'productName',
									sortable : true
								}
//								,{
//									display : '��Ʒ����',
//									name : 'busiCode',
//									sortable : true
//								},{
//									display : '��Ʒ�ͺ�',
//									name : 'email',
//									sortable : true
//								}, {
//									display : '��Ʒ���',
//									name : 'plane',
//									sortable : true
//								}, {
//									display : '����',
//									name : 'fax',
//									sortable : true
//								}
								],

						//��������
						searchitems : [{
									display : '��Ʒ����',
									name : 'productName'
								}],
						// title : '�ͻ���Ϣ',
						//ҵ���������
						boName : '��Ӧ����ϵ��',
						//Ĭ�������ֶ���
						sortname : "productName",
						//Ĭ������˳��
						sortorder : "ASC",
						isViewAction :false,
						isAddAction : false,
						isEditAction : false,
						isDelAction : false,
						isRightMenu : false,
						isToolBar : false
					});

		});