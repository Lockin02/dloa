// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$(".signGrid").yxgrid("reload");
};
$(function() {
			$(".signGrid").yxgrid({
						//�������url�����ô����url������ʹ��model��action�Զ���װ
//						 url :
						model : 'purchase_contract_purchasecontract',
//						action : 'pageJsonYes',
						title : '�ɹ�����ǩ���б�',
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
								},{
									display : 'ǩ��״̬',
									name : 'signState',
									sortable :��true,
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
									sortable :��true
								},{
									display : '�Ƶ���',
									name : 'createName',
									sortable : true
								},{
									display : 'Ԥ�����ʱ��',
									name : 'dateHope',
									hide : true
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
									sortable : true,
									hide:true
								}
								, {
									display : '���ʽ',
									name : 'paymentType',
									datacode : 'fkfs',
									sortable : true,
									hide:true
								}
								],
							param : {"ExaStatus" : "���,���"},
//							param : {"ExaStatus" : "���"},

							comboEx:[{
								text:'ǩ��״̬',
								key:'signState',
								data:[{
								   text:'δǩ��',
								   value:'0'
								},{
								   text:'��ǩ��',
								   value:'1'
								}]
							}],
				buttonsEx : [{
							name : 'expport',
							text : "����������Ϣ",
							icon : 'excel',
							action : function(row) {
								window.open("?model=purchase_contract_purchasecontract&action=toExporttFilter",
												"", "width=800,height=400");
							}
						}],
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
									showOpenWin("?model=purchase_contract_purchasecontract&action=init&perm=view&id="+row.id+"&skey="+row['skey_']);
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
						,{
							text : '����ǩ��',
							icon : 'edit',
						   showMenuFn:function(row){
						   		if(row.signState==0){
						   			return true;
						   		}
						   		return false;
						   },
							action : function(row,rows,grid){
								if(row){
									location="?model=purchase_contract_purchasecontract&action=toSign&id="+row.id
								}
							}
						},
						{
							text : '��������',
							icon : 'excel',
							action :function(row,rows,grid) {
								if(row){
									location="?model=purchase_contract_purchasecontract&action=exportPurchaseOrder&id="+row.id+"&skey="+row['skey_'];
								}else{
									alert("��ѡ��һ������");
								}
							}

						},
						{
							text : 'ǩ�ռ�¼',
							icon : 'view',
						   showMenuFn:function(row){
						   		if(row.signState==1){
						   			return true;
						   		}
						   		return false;
						   },
							action :function(row,rows,grid) {
								if(row){
									showOpenWin("?model=common_changeLog&action=toPurchaseSign&logObj=purchasesign&objId=" + row.id );
								}else{
									alert("��ѡ��һ������");
								}
							}

						},{
							text : '�����ϴ�',
							icon : 'add',
							action: function(row){
								     showThickboxWin ('?model=purchase_contract_purchasecontract&action=toUploadFile&id='
								                      + row.id
								                      + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=700');
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