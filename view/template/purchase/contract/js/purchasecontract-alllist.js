// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$("#listGrid").yxsubgrid("reload");
};
$(function() {
			$("#listGrid").yxsubgrid({
						//�������url�����ô����url������ʹ��model��action�Զ���װ
//						 url :
						model : 'purchase_contract_purchasecontract',
//						action : 'pageJsonYes',
						title : '�ɹ������б�',
						isToolBar : false,
						showcheckbox : false,
						param:{"stateArr":"4,5,6,7"},

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
								}
								,{
									display : '��Ӧ������',
									name : 'suppName',
									sortable : true,
									width : '200'
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
								},{
									display : 'ǩ��״̬',
									name : 'signState',
									sortable :��true,
									hide:true,
									process:function(v){
										if(v==0){
											return "δǩ��";
										}else{
											return "��ǩ��";
										}
									}
								},{
									display : 'ǩ��ʱ��',
									name : 'signTime',
									hide:true,
									sortable :��true
								},{
									display : 'ҵ��Ա',
									name : 'sendName',
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
							param : {"ExaStatus" : "���,���"},
//							param : {"ExaStatus" : "���"},
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
										name : 'pattem',
										display : "����ͺ�"
									},{
										name : 'units',
										display : "��λ"
									},{
										name : 'applyPrice',
										display : "��˰����"
									}, {
										name : 'amountAll',
										display : "��������"
									},{
										name : 'amountIssued',
										display : "���������"
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
		}],
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