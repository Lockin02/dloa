// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$(".rdcontactGrid").yxgrid("reload");
};
$(function() {
			$(".rdcontactGrid").yxgrid({
						tittle : '��ϵ���б�',
						//�������url�����ô����url������ʹ��model��action�Զ���װ
						 //url : '',
						// '?model=customer_customer_customer&action=pageJson',
						model : 'supplierManage_temporary_stcontact',
//						action : 'getById',
						action : 'pageJson&parentId=' + $("#parentId").val(),
						isToolBar : false,
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
										+300 + "&width=" + 640);
								}else{
									alert("��ѡ��һ������");
								}
							}

						}],
						//��������
						searchitems : [{
									display : '��ϵ������',
									name : 'name'
								}],
						// title : '�ͻ���Ϣ',
						//ҵ���������
						boName : '��Ӧ����ϵ��',
						//Ĭ�������ֶ���
						sortname : "name",
						//Ĭ������˳��
						sortorder : "ASC",
						//��ʾ�鿴��ť
						isViewAction : true,
						isEditAction : false,
						isDelAction : false,
						isAddAction : false,
						//�鿴��չ��Ϣ
						toViewConfig : {
							action : 'toRead'
						}
					});

		});