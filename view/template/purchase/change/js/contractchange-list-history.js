// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$(".contracthistory").yxgrid("reload");
};
$(function() {
			$(".contracthistory").yxgrid({
						//�������url�����ô����url������ʹ��model��action�Զ���װ
//						 url : "?model=purchase_change_contractchange&action=pageJsonHistory" ,
						model : 'purchase_change_contractchange',
						action : 'pageJsonHistory',

						param : {
							'applyNumb':$('#applyNumb').val()
						},

						title : '�ɹ���ͬ����б�',
						isToolBar : false,
						showcheckbox : false,

						//����Ϣ
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}
								, {
									display : '��ͬ���',
									name : 'applyNumb',
									sortable : true,
									width : 180
								}
								,{
									display : 'Ԥ�����ʱ��',
									name : 'dateHope',
									sortable : true
								}, {
									display : '��Ӧ������',
									name : 'suppName',
									sortable : true
								}
								,{
									display : '��������',
									name : 'paymetType',
									datacode : 'fkfs',
									sortable : true,
									width : 60
								}
								,{
									display : '��Ʊ����',
									name : 'billingType',
									datacode : 'FPLX',
									sortable : true,
									width : 80
								},{
									display : '�汾��',
									name : "version",
									sortable : true
								}
								,  {
									display : '���״̬',
									name : 'ExaStatus',
									sortable : true,
									width : 80
								},{
									display : '��ע',
									name : 'remark',
									sortable : true,
									width : 160
								}],
						//��չ�Ҽ��˵�
						menusEx : [
						{
							text : '�鿴',
							icon : 'view',
							action :function(row,rows,grid) {
								if(row){
//									showThickboxWin("?model=purchase_change_contractchange&action=init"
//										+ "&id="
//										+ row.id
//										+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height="
//										+ 700 + "&width=" + 900);
									showOpenWin("?model=purchase_change_contractchange&action=init&perm=view&id="+row.id);
								}else{
									alert("��ѡ��һ������");
								}
							}

						}
						],
						//��������
						searchitems : [
								{
									display : '��ͬ���',
									name : 'applyNumb'
								},
								{
									display : '������ͬ��',
									name : 'hwapplyNumb'
								}
								],
						// title : '�ͻ���Ϣ',
						//ҵ���������
//						boName : '��Ӧ����ϵ��',
						//Ĭ�������ֶ���
						sortname : "id",
						//Ĭ������˳��
						sortorder : "DESC",
						//��ʾ�鿴��ť
						isViewAction : false,
//						isAddAction : true,
						isEditAction : false,
						isDelAction : false
					});

		});