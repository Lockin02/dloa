// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$("#managEndGrid").yxsubgrid("reload");
};

//�鿴������Ϣ
function viewOrder(id,skey) {
	showOpenWin("?model=purchase_contract_purchasecontract&action=toTabRead&id="+id+"&skey="+skey);
}

//�鿴�����¼
function viewPay(id,hwapplyNumb,suppId,suppName,skey) {
	showOpenWin("?model=finance_payables_payables&action=toHistory&obj[objId]="+id+"&obj[objCode]="+hwapplyNumb+"&obj[objType]=YFRK-01&obj[supplierId]="+suppId+"&obj[supplierName]="+suppName+"&skey="+skey);
}

//�鿴��Ʊ��¼
function viewInvoice(id,hwapplyNumb,suppId,suppName,skey) {
	showOpenWin("?model=finance_invpurchase_invpurchase&action=toHistory&obj[objId]="+id+"&obj[objCode]="+hwapplyNumb+"&obj[objType]=YFRK-01&obj[supplierId]="+suppId+"&obj[supplierName]="+suppName+"&skey="+skey);
}

$(function() {
			$("#managEndGrid").yxsubgrid({
						//�������url�����ô����url������ʹ��model��action�Զ���װ
//						 url :
						model : 'purchase_contract_purchasecontract',
						action : 'managEndPageJson',
						title : 'ִ����ϵĶ���',
						isToolBar : false,
						showcheckbox : false,
						param:{"stateArr":"5,6"},

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
									width : '180',
									process : function(v, row) {
										var skey=row['skey_'];
										if(row.state==6){
											var vStr="<font color=red>" +v+"</font>";
										}else{
											var vStr=v;
										}
										return "<a href='#' title='�鿴������ϸ��Ϣ' onclick='viewOrder(\""
												+ row.id
												+"\",\""
												+skey
												+ "\")' >"
												+ vStr
												+ "</a>";
									}
								}
								,{
									display : '�ɹ�����',
									name : 'purchType',
									sortable : true,
									width:110
								},{
									display : '�ɹ�������',
									name : 'sendName',
									sortable : true,
									width:100
								},{
									display : '����״̬',
									name : 'stateC',
									sortable : true,
									hide:true,
									width:60
								},{
									display : '�������',
									name : 'allMoney',
									sortable :��true,
									process : function(v,row){
											return moneyFormat2(v);
									}
								},{
									display : '�Ѹ����',
									name : 'payed',
									process : function(v, row) {
										if(v>0){
											var skey=row['skey_'];
											var payed=parseFloat(v);
											var allMoney=parseFloat(row.allMoney);
											var amountIssued=parseInt(row.amountIssued);
											var shallPay=parseFloat(row.shallPay);
											if(payed>allMoney){
												var vStr="<font color=red>" +moneyFormat2(parseFloat(v))+"</font>";
											}else if(amountIssued==0&&row.paymentCondition=="YFK"&&payed>parseFloat(row.YFPay)){//�������Ϊ0�Ҹ�������ΪԤ����ʱ�����Ѹ�������ж�
												var vStr="<font color=red>" +moneyFormat2(parseFloat(v))+"</font>";
											}else if(amountIssued==0&&row.paymentCondition=="HDFK"&&payed>0){//�������Ϊ0�Ҹ�������Ϊ��������ʱ�����Ѹ�������ж�
												var vStr="<font color=red>" +moneyFormat2(parseFloat(v))+"</font>";
											}else if(amountIssued>0&&payed>(shallPay+parseFloat(row.YFPay))){//����Ӧ���Ľ�����Ѹ������бȽ�
												var vStr="<font color=red>" +moneyFormat2(parseFloat(v))+"</font>";
											}else{
												var vStr=moneyFormat2(parseFloat(v));
											}
											if(row.viewType==1){
												return "<a href='#' title='�鿴������Ϣ' onclick='viewPay(\""
														+ row.id
														+"\",\""
														+row.hwapplyNumb
														+"\",\""
														+row.suppId
														+"\",\""
														+row.suppName
														+"\",\""
														+skey
														+ "\")' >"
														+ vStr
														+ "</a>";
											}else{
												return vStr;
											}
										}else{
											return "0.00";
										}
									}
								},{
									display : '��Ʊ���',
									name : 'handInvoiceMoney',
									process : function(v, row) {
										if(v>0){
											if(parseFloat(v)>parseFloat(row.allMoney)){//�����Ʊ�����ڶ��������Ϊ��ɫ
												var vStr="<font color=red>" +moneyFormat2(parseFloat(v))+"</font>";
											}else{
												var vStr=moneyFormat2(parseFloat(v));
											}
											var skey=row['skey_'];
											if(row.viewType==1){
												return "<a href='#' title='�鿴��Ʊ��Ϣ' onclick='viewInvoice(\""
														+ row.id
														+"\",\""
														+row.hwapplyNumb
														+"\",\""
														+row.suppId
														+"\",\""
														+row.suppName
														+"\",\""
														+skey
														+ "\")' >"
														+ vStr
														+ "</a>";
											}else{
												return vStr;
											}
										}else{
											return "0.00";
										}
									}
								},{
									display : '����������',
									name : 'amountAll',
									width : 60,
									process : function(v,row){
											return moneyFormat2(v);
									}
								},{
									display : '���������',
									name : 'amountIssued',
									width : 60,
									process : function(v, row) {
										var v=parseInt(v);
										var amountAll=parseInt(row.amountAll);
										if(v>amountAll){
											return "<font color=red>" +moneyFormat2(v)+"</font>";
										}else{
											return moneyFormat2(v);
										}
									}
								}
								,{
									display : '����ʱ��',
									name : 'payFormDate',
									sortable :��true,
									width:80
								},{
									display : '���ʱ��',
									name : 'auditDate',
									sortable : true,
									width:80
								},{
									display : '��Ʊʱ��',
									name : 'formDate',
									sortable :��true,
									width:80
								},{
						            name : 'isNeedStamp',
						            display : '���������',
						            sortable : true,
						            width : 60,
						            process : function(v,row){
										if(v=="0"){
											return "��";
										}else if( v== "1"){
											return "��";
										}
									}
						        },{
						            name : 'isStamp',
						            display : '�Ƿ��Ѹ���',
						            sortable : true,
						            width : 60,
						            process : function(v,row){
										if(v=="0"){
											return "��";
										}else if( v== "1"){
											return "��";
										}
						            }
						        },{
						            name : 'stampType',
						            display : '��������',
						            sortable : true,
						            width : 80
						        },{
									display : '��������',
									name : 'orderTime',
									sortable : true,
									width:80
								},{
									display : '��Ӧ������',
									name : 'suppName',
									sortable : true,
									width : '180'
								}
								],
							// ���ӱ������
							subGridOptions : {
								url : '?model=purchase_contract_equipment&action=managPageJson',
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
											width : 60,
											process : function(v,row){
													return moneyFormat2(v);
											}
										}, {
											name : 'amountIssued',
											display : "���������",
											width : 60,
											process : function(v,row){
													return moneyFormat2(v);
											}
										},{
											name : 'applyPrice',
											display : "��˰����",
											process : function(v,row){
													return moneyFormat2(v,6);
											}
										},{
											name : 'moneyAll',
											display : "���",
											process : function(v,row){
													return moneyFormat2(v);
											}
										},{
											name : 'applyDeptName',
											display : "���벿��"
										},{
											name : 'sourceNumb',
											display : "Դ�����",
											width : 170
										}]
							},

							comboEx:[{
								text:'�ɹ�����',
								key:'purchType',
								data:[{
								   text:'���ۺ�ͬ�ɹ�',
								   value:'HTLX-XSHT'
								},{
								   text:'�����ͬ�ɹ�',
								   value:'HTLX-FWHT'
								},{
								   text:'���޺�ͬ�ɹ�',
								   value:'HTLX-ZLHT'
								},{
								   text:'�з���ͬ�ɹ�',
								   value:'HTLX-YFHT'
								},{
								   text:'�����òɹ�',
								   value:'oa_borrow_borrow'
								},{
								   text:'���Ͳɹ�',
								   value:'oa_present_present'
								},{
								   text:'�ʲ��ɹ�',
								   value:'assets'
								},{
								   text:'����ɹ�',
								   value:'stock'
								},{
								   text:'�����ɹ�',
								   value:'produce'
								},{
								   text:'�з��ɹ�',
								   value:'rdproject'
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
						}, {
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
						//��չ�Ҽ��˵�
						menusEx : [
						{
							text : '�鿴',
							icon : 'view',
							action :function(row,rows,grid) {
								if(row){
									showOpenWin("?model=purchase_contract_purchasecontract&action=toTabRead&id="+row.id+"&skey="+row['skey_']);
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
						},{
							text : '��ֹԭ��',
							icon : 'view',
						   showMenuFn:function(row){
						   		if(row.state=="6"){
						   			return true;
						   		}
						   		return false;
						   },
							action : function(row,rows,grid){
								if(row){
									showThickboxWin("?model=purchase_contract_purchasecontract&action=toCloseRead&id="+row.id+"&skey="+row['skey_']
											+"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=800");
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
									display : '�ɹ�������',
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