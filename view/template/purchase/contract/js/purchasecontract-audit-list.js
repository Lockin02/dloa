// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$("#listGrid").yxsubgrid("reload");
};

//�鿴������Ϣ
function viewOrder(id,skey) {
	showOpenWin("?model=purchase_contract_purchasecontract&action=toReadTab&id="+id+"&skey="+skey);
}
$(function() {
			$("#listGrid").yxsubgrid({
						//�������url�����ô����url������ʹ��model��action�Զ���װ
//						 url :
						model : 'purchase_contract_purchasecontract',
//						action : 'pageJsonYes',
						title : '�����еĲɹ�����',
						isToolBar : false,
						showcheckbox : false,
						param : {"ExaStatus" : "��������"},

						//����Ϣ
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								},{
									display : '��������',
									name : 'orderTime',
									sortable : true,
									width:80
								}, {
									display : '�������',
									name : 'hwapplyNumb',
									sortable : true,
									width : '200',
									process : function(v, row) {
										var skey=row['skey_'];
											return "<a href='#' title='�鿴������ϸ��Ϣ' onclick='viewOrder(\""
													+ row.id
													+"\",\""
													+skey
													+ "\")' >"
													+v
													+ "</a>";
									}
								}
								,{
									display : '��Ӧ������',
									name : 'suppName',
									sortable : true,
									width : '200'
								}
								,{
									display : 'ҵ��Ա',
									name : 'sendName',
									sortable : true
								},{
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
								},{
									display : 'Ԥ�����ʱ��',
									name : 'dateHope',
									sortable :��true
								},{
									display : '����״̬',
									name : 'ExaStatus',
									sortable :��true
								}
								],
						// ���ӱ������
						subGridOptions : {
							url : '?model=purchase_contract_equipment&action=pageJson',
							param : [{
										paramId : 'basicId',
										colId : 'id'
									}],
							colModel : [ {
											name : 'productNumb',
											display : '���ϱ��'
										}, {
											name : 'productName',
											width : 200,
											display : '��������'
										},{
											name : 'amountAll',
											display : "��������",
											width : 60
										},{
											name : 'applyPrice',
											display : "��˰����",
											process : function(v,row){
													return moneyFormat2(v);
											}
										},{
											name : 'moneyAll',
											display : "���",
											process : function(v,row){
													return moneyFormat2(v);
											}
										},{
											name : 'dateHope',
											display : "��������ʱ��"
										},{
											name : 'applyDeptName',
											display : "���벿��"
										},{
											name : 'sourceNumb',
											display : "Դ�����",
											width : 170
										}]
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
//										+ 400 + "&width=" + 700);
									showOpenWin("?model=purchase_contract_purchasecontract&action=toReadTab&perm=view&id="+row.id+"&skey="+row['skey_']);
								}else{
									alert("��ѡ��һ������");
								}
							}

						}
						,{
							text : '�������',
							icon : 'view',
							action : function(row,rows,grid){
								if(row){
									showThickboxWin("controller/common/readview.php?itemtype=oa_purch_apply_basic&pid="
											+row.id
											+"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=600");
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
									display : '��������',
									name : 'orderTime'
								},
								{
									display : '��Ӧ������',
									name : 'suppName'
								},
								{
									display : 'ҵ��Ա',
									name : 'sendName'
								},
								{
									display : '���ϱ��',
									name : 'productNumb'
								},
								{
									display : '��������',
									name : 'productName'
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