// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$(".sfproductGrid").yxgrid("reload");
};
$(function() {
			$(".sfproductGrid").yxgrid({
						//�������url�����ô����url������ʹ��model��action�Զ���װ
//						 url :
//						 '?model=supplierManage_formal_sfproduct&action=pageJson&parentId='+$("#parentId").val(),
						 model : 'supplierManage_formal_sfproduct',
						action : 'pageJson&parentId='+$("#parentId").val()+"&parentCode="+$("#parentCode").val(),
						//����Ϣ
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								},{
									display : 'parentId',
									name : 'parentId',
									sortable : true,
									hide : true
								},{
									display : 'parentCode',
									name : 'parentCode',
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
						//��չ��ť
						buttonsEx : [],
						//��չ�Ҽ��˵�
						menusEx : [],
						//��������
						searchitems : [{
									display : '��Ʒ����',
									name : 'productName'
								}],
						// title : '�ͻ���Ϣ',
						//ҵ���������
						boName : '��Ʒ����',
						//Ĭ�������ֶ���
						sortname : "productName",
						//Ĭ������˳��
						sortorder : "ASC",
						isViewAction :false,
						isAddAction : true,
						isEditAction : false,
						isDelAction : true,
						toViewConfig : {
							action : 'toRead',
							formWidth : 400,
							formHeight : 400
						},
						toAddConfig : {
									text : '����',
									/**
									 * Ĭ�ϵ��������ť�����¼�
									 */

									toAddFn : function(p) {
										showThickboxWin("?model=supplierManage_formal_sfproduct&action=toAdd" +
												"&parentId=" + $("#parentId").val() +"&parentCode="+$("#parentCode").val()+
												"&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=600");

									},
									/**
									 * ���������õĺ�̨����
									 */
									action : 'toAdd',
									plusUrl : '?model=supplierManage_formal_sfproduct'
						}
					});

		});