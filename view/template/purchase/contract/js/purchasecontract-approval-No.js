// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$(".approvalNoGrid").yxgrid("reload");
};
$(function() {
			$(".approvalNoGrid").yxgrid({
						//�������url�����ô����url������ʹ��model��action�Զ���װ
//						 url :
						model : 'purchase_contract_purchasecontract',
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
									display : '���������',
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
									name : 'paymentType',
									datacode : 'fkfs',
									sortable : true
								}
								],

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
										showOpenWin("?model=purchase_contract_purchasecontract&action=toReadTab&id="+row.id+"&skey="+row['skey_']);
								}else{
									alert("��ѡ��һ������");
								}
							}

						},
						{
							text : '����',
							icon : 'edit',
							action : function(row,rows,grid){
								if(row){
									if(row.isTemp=="0"){//�ɹ���������
										location = "controller/purchase/contract/ewf_index.php?actTo=ewfExam&taskId="+row.taskId+"&spid="+row.spid+"&billId="+row.id+"&examCode=oa_purch_apply_basic&skey="+row['skey_'];
									}else{//�ɹ������������
										location = "controller/purchase/change/ewf_index.php?actTo=ewfExam&taskId="+row.taskId+"&spid="+row.spid+"&billId="+row.id+"&examCode=oa_purch_apply_basic&skey="+row['skey_'];
									}
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