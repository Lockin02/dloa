// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$(".stcontactGrid").yxgrid("reload");
};
$(function() {
			$(".stcontactGrid").yxgrid({
						//�������url�����ô����url������ʹ��model��action�Զ���װ
//						 url :
//						 '?model=supplierManage_formal_sfcontact&action=pageJson&parentId='+$("#parentId").val(),
						model : 'supplierManage_temporary_stcontact',
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
									display : '��ϵ������',
									name : 'name',
									sortable : true
//									//���⴦���ֶκ���
//									process : function(v,row) {
//										return row.name;
//									}
								},{
									display : 'ҵ����',
									name : 'busiCode',
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
						menusEx : [
						{
							text : '�鿴',
							icon : 'view',
							action :function(row,rows,grid) {
								if(row){
									showThickboxWin("?model=supplierManage_temporary_stcontact&action=init"
										+ "&id="
										+ row.id
										+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height="
										+ 300 + "&width=" + 640);
								}else{
									alert("��ѡ��һ������");
								}
							}

						},
						{
							text : '�༭',
							icon : 'edit',
							action : function(row,rows,grid) {
								if(row){
									showThickboxWin("?model=supplierManage_temporary_stcontact&action=init"
										+ "&id="
										+ row.id
										+ "&placeValuesBefore&TB_iframe=true&modal=false&height="
										+ 400 + "&width=" + 640);
								}else{
									alert("��ѡ��һ������");
								}
							}
						}

						],
						//��ʾ�鿴��ť
						isViewAction : false,
						isAddAction : true,
						isEditAction : false,
						isDelAction : true,
						//�鿴��չ��Ϣ
						toViewConfig : {
							action : 'toRead',
							formWidth : 400,
							formHeight : 340
						},
						toAddConfig : {
									text : '����',
									/**
									 * Ĭ�ϵ��������ť�����¼�
									 */

									toAddFn : function(p) {
										showThickboxWin("?model=supplierManage_temporary_stcontact&action=tordAdd" +
												"&parentId=" + $("#parentId").val() +"&parentCode="+$("#parentCode").val()+
												"&placeValuesBefore&TB_iframe=true&modal=false&height=340&width=500");

									},
									/**
									 * ���������õĺ�̨����
									 */
									action : 'tordAdd',
									plusUrl : '?model=supplierManage_formal_sfcontact'
						},
						toEditConfig : {
							formWidth : 400,
							formHeight : 320,
							action : 'toEdEdit'
						},
						//��������
						searchitems : [{
									display : '��ϵ������',
									name : 'name'
								}],
						 title : '��Ӧ����ϵ����Ϣ',
						//ҵ���������
//						boName : '��Ӧ����ϵ��',
						//Ĭ�������ֶ���
						sortname : "updateTime",
						//Ĭ������˳��
						sortorder : "DESC"
					});

		});