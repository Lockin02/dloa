// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$(".stproductGrid").yxgrid("reload");
};
$(function() {
			$(".stproductGrid").yxgrid({
						//�������url�����ô����url������ʹ��model��action�Զ���װ
						 url :
						 '?model=supplierManage_temporary_stproduct&action=pageJson&parentId='+$("#parentId").val(),
						//����Ϣ
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									display : '��Ʒ����/����',
									name : 'productName'
								}
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