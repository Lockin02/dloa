// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$(".approvalNoGrid").yxgrid("reload");
};
$(function() {
			$(".approvalNoGrid").yxgrid({
						//�������url�����ô����url������ʹ��model��action�Զ���װ
//						 url :
						model : 'purchase_change_contractchange',
						action : 'pageJsonNo',
						title : '�������Ĳɹ�����',
						formHeight : 600,
						isToolBar : false,
						showcheckbox : false,

						//����Ϣ
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									display : '�������',
									name : 'hwapplyNumb',
									sortable : true,
									width : '200'
								},{
									display : '����������',
									name : 'createName',
									sortable : true
								},{
									display : 'Ԥ�����ʱ��',
									name : 'dateHope',
									sortable : true
								},{
									display : '����״̬',
									name : 'ExaStatus',
									sortable :��true
								}
								,{
									display : '��Ӧ������',
									name : 'suppName',
									sortable : true
								}
								,{
									display : '��Ʊ����',
									name : 'billingType',
									datacode : 'FPLX',
									sortable : true
								},{
									display : '�汾��',
									name : 'version',
									width : 70,
									sortable : true
								}
								, {
									display : '��������',
									name : 'paymetType',
									datacode : 'fkfs',
									sortable : true
								}
								],
						//��չ��ť
						param : {
							ExaStatus : '��������'
						},
						//��չ�Ҽ��˵�
						menusEx : [
						{
							text : '�鿴',
							icon : 'view',
							action :function(row,rows,grid) {
								if(row){
//									showThickboxWin("?model=purchase_contract_purchasecontract&action=init"
//										+ "&id="
//										+ row.id
//										+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height="
//										+ 500 + "&width=" + 700);
									showOpenWin("?model=purchase_contract_purchasecontract&action=init&perm=view&id="+row.id+"&skey="+row['skey_']);
//									showOpenWin("?model=purchase_contract_purchasecontract&action=toTabView&id="+row.id+"&applyNumb="+row.applyNumb);
								}else{
									alert("��ѡ��һ������");
								}
							}

						},
//						{
//							text : '�鿴��ʷ�汾',
//							icon : 'view',
//							action : function(row,rows,grid){
//								if(row){
//									location = "?model=purchase_change_contractchange&action=toViewHistory&id=" + row.id;
//								}
//							}
//						},
						{
							text : '����',
							icon : 'edit',
							showMenuFn : function(row){
								if(row.ExaStatus == '��������'){
									return true;
								}
								return false;
							},
							action : function(row,rows,grid){
								if(row){
									location = "controller/purchase/change/ewf_index.php?actTo=ewfExam&taskId="+row.taskId+"&spid="+row.spid+"&billId="+row.id+"&examCode=oa_purch_apply_basic&skey="+row['skey_'];
//									parent.location = "controller/purchase/change/ewf_index.php?actTo=ewfSelect&billId=" +row.id + "&examCode=oa_purch_apply_basic_version&formName=�ɹ���ͬ����";
								}
							}
						}
						],
						//��������
						searchitems : [
								{
									display : '�������',
									name : 'hwapplyNumb'
								},
								{
									display : '��Ӧ������',
									name : 'suppName'
								}
								],
						// title : '�ͻ���Ϣ',
						//ҵ���������
//						boName : '��Ӧ����ϵ��',
						//Ĭ�������ֶ���
						sortname : "id",
						//Ĭ������˳��
						sortorder : "ASC",
						//��ʾ�鿴��ť
						isViewAction : false,
						isAddAction : false,
						isEditAction : false,
						isDelAction : false
					});

		});