// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$("#equListGrid").yxgrid("reload");
};

//�鿴������Ϣ
function viewOrder(id,skey) {
	showOpenWin("?model=purchase_contract_purchasecontract&action=toTabRead&id="+id+"&skey="+skey);
}
$(function() {
			$("#equListGrid").yxgrid({
						//�������url�����ô����url������ʹ��model��action�Զ���װ
//						 url :
						model : 'purchase_contract_purchasecontract',
						action : 'executEquJson',
						title : '��ִ�ж���������Ϣ',
						isToolBar : false,
						showcheckbox : false,
						param:{"stateArr":"4,7","isInStock":"isInStock"},

						//����Ϣ
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									display : '��������',
									name : 'createTime',
									sortable : true,
									width:80
								}, {
									display : '���ݱ��',
									name : 'hwapplyNumb',
									sortable : true,
									width : '150',
									process : function(v, row) {
										var skey=row['skey_'];
											return "<a href='#' title='�鿴������ϸ��Ϣ' onclick='viewOrder(\""
													+ row.id
													+"\",\""
													+skey
													+ "\")' >"
													+ v
													+ "</a>";
									}
								}
								,{
									display : '��Ӧ��',
									name : 'suppName',
									sortable : true,
									width : '200'
								}
								, {
									display : '��������',
									name : 'productName',
									sortable : true,
									width : '200',
									process : function(v,row){
											if((DateDiff(row.today,row.dateHope)<2||DateDiff(row.today,row.dateHope)==2)&&DateDiff(row.today,row.dateHope)>0){
												return "<font color=red>"+v+"</font>";;
											}else{
												return v;
											}
									}
								},{
									display : '��������',
									name : 'amountAll',
									sortable :��true,
									width : '65',
									process : function(v,row){
										if(parseInt(row.amountAll)!=parseInt(row.amountIssued)){
											return "<font color=blue>"+moneyFormat2(v)+"</font>";
										}else{
											return moneyFormat2(v);
										}
									}
								},{
									display : '�������',
									name : 'amountIssued',
									sortable :��true,
									width : '65',
									process : function(v,row){
											return moneyFormat2(v);
									}
								},{
									display : '��������',
									name : 'arrivalNum',
									sortable :��true,
									width : '65',
									process : function(v,row){
											return moneyFormat2(v);
									}
								},{
									display : '�ʼ췽ʽ',
									name : 'checkTypeName',
									sortable :��false,
									width : '60',
									process : function(v,row){
											if(v=="ȫ��"||v=="���"){
												return "<font color=red>"+v+"</font>";;
											}else{
												return v;
											}
									}
								},{
									display : 'Ԥ�Ƶ���ʱ��',
									name : 'dateHope',
									sortable : true
								},{
									display : '�ɹ�����',
									name : 'purchType',
									sortable : true
								},{
									display : '��˰����',
									name : 'applyPrice',
									sortable :��true,
									process : function(v,row){
											return moneyFormat2(v,6,6);
									}
								}
								,{
									display : '���ϴ���',
									name : 'productNumb',
									width : '65',
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
//										+ 400 + "&width=" + 700);
									showOpenWin("?model=purchase_contract_purchasecontract&action=toTabRead&perm=view&id="+row.id+"&skey="+row['skey_']);
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
									display : '���ݱ��',
									name : 'hwapplyNumb'
								},
								{
									display : '��������',
									name : 'orderTime'
								},
								{
									display : '��Ӧ��',
									name : 'suppName'
								},
								{
									display : '���ϴ���',
									name : 'searchProductNumb'
								},
								{
									display : '��������',
									name : 'searchPproductName'
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