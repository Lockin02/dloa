// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$(".approvalYesGrid").yxgrid("reload");
};
$(function() {
			$(".approvalYesGrid").yxgrid({
						//�������url�����ô����url������ʹ��model��action�Զ���װ
//						 url :
						model : 'purchase_contract_purchasecontract',
						action : 'pageJsonYes',
						title : '����˵Ĳɹ�����',
						isToolBar : false,
						showcheckbox : false,

						//����Ϣ
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								},  {
									display : '��������',
									name : 'isTemp',
									sortable : true,
									process : function(v, row) {
										if (row.isTemp == '0') {
											return "�ɹ���������";
										} else {
											return "�ɹ������������";
										}
									}
								},{
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
									datacode : 'FPLX',		//�����ֵ����
									sortable : true
								}
								, {
									display : '���ʽ',
									name : 'paymentType',
									datacode : 'fkfs',
									sortable : true
								}
								],
							param : {"ExaStatus" : "���,���"},
//							param : {"ExaStatus" : "���"},

						comboEx:[{
							text:'��������',
							key:'isTemp',
							data:[{
							   text:'�ɹ���������',
							   value:'0'
							},{
							   text:'�ɹ������������',
							   value:'1'
							}]
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
//									showThickboxWin("?model=purchase_contract_purchasecontract&action=init"
//										+ "&id="
//										+ row.id
//										+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height="
//										+ 400 + "&width=" + 700);
									showOpenWin("?model=purchase_contract_purchasecontract&action=toReadTab&id="+row.id+"&skey="+row['skey_']);
								}else{
									alert("��ѡ��һ������");
								}
							}

						}
//						,{
//							text : '�������',
//							icon : 'view',
//							action : function(row,rows,grid){
//								if(row){
//									showThickboxWin("controller/common/readview.php?itemtype=oa_purchase_apply_basic&pid="
//											+row.id
//											+"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=600");
//								}
//							}
//						}
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
						sortname : "updateTime",
						//Ĭ������˳��
						sortorder : "DESC",
						//��ʾ�鿴��ť
						isViewAction : false,
						isAddAction : true,
						isEditAction : false,
						isDelAction : false
					});

		});