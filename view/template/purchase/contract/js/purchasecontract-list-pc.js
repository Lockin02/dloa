// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$(".pcGrid").yxgrid("reload");
};
$(function() {
			$(".pcGrid").yxgrid({
						//�������url�����ô����url������ʹ��model��action�Զ���װ
//						 url :
						model : 'purchase_contract_purchasecontract',
						action : 'pageJson',
						title : '�ɹ���ͬ',
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
									display : '��ͬ���',
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
								}
								, {
									display : '��������',
									name : 'paymetType',
									datacode : 'fkfs',
									sortable : true
								}
								],
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
//									showOpenWin("?model=purchase_contract_purchasecontract&action=init&perm=view&id="+row.id);
									showOpenWin("?model=purchase_contract_purchasecontract&action=toTabView&id="+row.id+"&applyNumb="+row.applyNumb);
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
									name : 'seachApplyNumb'
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
						sortname : "updateTime",
						//Ĭ������˳��
						sortorder : "DESC",
						//��ʾ�鿴��ť
						isViewAction : false,
						isAddAction : false,
						isEditAction : false,
						isDelAction : false
					});

		});