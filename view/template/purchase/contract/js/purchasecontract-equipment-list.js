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
						action : 'orderEquJson',
						title : '�ɹ������б�',
						isToolBar : false,
						showcheckbox : false,
						usepager : false, // �Ƿ��ҳ
						param:{'beginTime':$("#beginTime").val(),'endTime':$("#endTime").val(),'csuppId':$("#suppId").val(),'searchProductNumb':$("#productNumb").val(),'searchPproductName':$("#productName").val(),'sendUserId':$("#sendUserId").val()},

						//����Ϣ
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									display : '����',
									name : 'createTime',
									sortable : true
								}
								,{
									display : '��Ӧ��',
									name : 'suppName',
									sortable : true,
									width : '200'
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
									display : '���ϴ���',
									name : 'productNumb',
									sortable : true
								}
								, {
									display : '��������',
									name : 'productName',
									sortable : true,
									width : '200'
								},{
									display : '����ͺ�',
									name : 'pattem',
									sortable :��true
								},{
									display : 'ҵ��Ա',
									name : 'sendName',
									sortable : true
								},{
									display : '����',
									name : 'amountAll',
									sortable :��true,
									width : '60',
									process : function(v,row){
											return moneyFormat2(v);
									}
								},{
									display : '����',
									name : 'price',
									sortable :��true,
									process : function(v,row){
											return moneyFormat2(v,6,6);
									}
								},{
									display : '˰��',
									name : 'taxRate',
									sortable :��true,
									width : '60'
								},{
									display : '���',
									name : 'noTaxMoney',
									process : function(v,row){
											return moneyFormat2(v);
									}
								},{
									display : '��˰����',
									name : 'applyPrice',
									sortable :��true,
									process : function(v,row){
											return moneyFormat2(v,6,6);
									}
								},{
									display : '��˰�ϼ�',
									name : 'moneyAll',
									sortable :��true,
									process : function(v,row){
											return moneyFormat2(v);
									}
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
		buttonsEx : [ {
			name : 'Add',
			text : "�²�",
			icon : 'search',
			action : function(row, rows, idArr) {
				if (row) {
					if (idArr.length > 1) {
						alert('һ��ֻ�ܶ�һ����¼�����²�');
						return false;
					}

					$.ajax({
						type : "POST",
						url : "?model=common_search_searchSource&action=checkDown",
						data : {
							"objId" : row.id,
							"objType" : 'purchasecontract'
						},
						async : false,
						success : function(data) {
							if (data != "") {
								showModalWin("?model=common_search_searchSource&action=downList&objType=purchasecontract&orgObj="
										+ data + "&objId=" + row.id);
							} else {
								alert('û��������ĵ���');
							}
						}
					});
				} else {
					alert('����ѡ���¼');
				}
			}
		},
			{
				text : '����',
				icon : 'view',
				action : function(row, rows, grid) {
					location = "?model=purchase_contract_purchasecontract&action=toAllList"
				}
			}],
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
									display : 'ҵ��Ա',
									name : 'sendName'
								},
								{
									display : '���ϴ���',
									name : 'searchProductNumb'
								},
								{
									display : '��������',
									name : 'searchPproductName'
								},
								{
									display : '����ͺ�',
									name : 'searchPattem'
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