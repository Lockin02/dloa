var show_page = function(page) {
	$("#shipmentsGrid").yxsubgrid("reload");
};
$(function() {
	$("#shipmentsGrid")
			.yxsubgrid(
					{
						model : 'projectmanagent_present_present',
						action : 'shipmentsPageJson',
						customCode : 'presentShipmentsGrid',
						param : {
							'ExaStatusArr' : "���,���������",
							'lExaStatusArr' : "���,���������",
							"DeliveryStatus2" : "WFH,BFFH"
						},
						title : '��������',
						// ��ť
						isViewAction : false,
						isAddAction : false,
						isEditAction : false,
						isDelAction : false,
						showcheckbox : false,

						// buttonsEx : [{
						// name : 'export',
						// text : "�������ݵ���",
						// icon : 'excel',
						// action : function(row) {
						// window.open("?model=contract_common_allcontract&action=preExportExcel"
						// +
						// "&1width=200,height=200,top=200,left=200,resizable=yes")
						// }
						// }],
						// ����Ϣ
						colModel : [
								{
									name : 'status2',
									display : '�´�״̬',
									sortable : false,
									width : '20',
									align : 'center',
									// hide : aaa,
									process : function(v, row) {
										if (row.makeStatus == 'YXD') {
											return "<img src='images/icon/icon073.gif' />";
										} else {
											return "<img src='images/icon/green.gif' />";
										}
									}
								},
								{
									name : 'rate',
									display : '����',
									sortable : false,
									process : function(v, row) {
										return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=stock_outplan_contractrate&action=updateRate&docId='
												+ row.id
												+ "&docType=oa_present_present"
												+ "&objCode="
												+ row.objCode
												+ "&skey="
												+ row['skey_']
												+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">��ע : '
												+ "<font color='gray'>"
												+ v
												+ "</font>" + '</a>';
									}
								}, {
									display : '��������״̬',
									name : 'lExaStatus',
									sortable : true,
									hide : true
								}, {
									display : '����������Id',
									name : 'lid',
									sortable : true,
									hide : true
								}, {
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									name : 'ExaDT',
									display : '����ʱ��',
									width : 70,
									sortable : true
								}, {
									name : 'deliveryDate',
									display : '��������',
									width : 80,
									sortable : true
								}, {
									name : 'customerName',
									display : '�ͻ�����',
									width : 150,
									sortable : true
								}
//								, {
//									name : 'orderCode',
//									display : 'Դ�����',
//									width : 170,
//									sortable : true
//								}, {
//									name : 'orderName',
//									display : 'Դ������',
//									width : 170,
//									hide : true,
//									sortable : true
//								}
								, {
									name : 'Code',
									display : '���',
									width : 120,
									sortable : true,
									process : function(v, row) {
										if( v=='' ){
											v='��'
										}
										if (row.changeTips == 1) {
											return "<font color = '#FF0000'>"
													+ '<a href="javascript:void(0)" onclick="javascript:showOpenWin(\'?model=stock_outplan_outplan&action=viewByPresent&id='
													+ row.id
													+ "&objType=oa_present_present"
													+ "&linkId="
													+ row.linkId
													+ "&skey="
													+ row['skey_']
													+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
													+ "<font color = '#FF0000'>" + v + "</font>"
													+ '</a>';
										} else {
											return '<a href="javascript:void(0)" onclick="javascript:showOpenWin(\'?model=stock_outplan_outplan&action=viewByPresent&id='
													+ row.id
													+ "&objType=oa_present_present"
													+ "&linkId="
													+ row.linkId
													+ "&skey="
													+ row['skey_']
													+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
													+ "<font color = '#4169E1'>"
													+ v
													+ "</font>"
													+ '</a>';
										}
									}
								}, {
									name : 'dealStatus',
									display : '����״̬',
									sortable : true,
									process : function(v) {
										if (v == '0') {
											return "δ����";
										} else if (v == '1') {
											return "�Ѵ���";
										} else if (v == '2') {
											return "���δ����";
										} else if (v == '3') {
											return "�ѹر�";
										}
									},
									width : 50
								}, {
									name : 'DeliveryStatus',
									display : '����״̬',
									process : function(v) {
										if (v == 'WFH') {
											return "δ����";
										} else if (v == 'YFH') {
											return "�ѷ���";
										} else if (v == 'BFFH') {
											return "���ַ���";
										} else if (v == 'TZFH') {
											return "ֹͣ����";
										}
									},
									width : '60',
									sortable : true
								}, {
									name : 'makeStatus',
									display : '�´�״̬',
									sortable : true,
									process : function(v) {
										if (v == 'WXD') {
											return "δ�´�";
										} else if (v == 'BFXD') {
											return "�����´�";
										} else if (v == 'YXD') {
											return "���´�";
										}
									},
									width : 60,
									sortable : true
								}, {
									name : 'salesName',
									display : '������',
									width : 80,
									sortable : true
								}, {
									name : 'reason',
									display : '��������',
									hide : true,
									sortable : true
								}, {
									name : 'remark',
									display : '��ע',
									hide : true,
									sortable : true
								}, {
									name : 'ExaStatus',
									display : '����״̬',
									width : 60,
									sortable : true
								}, {
									name : 'objCode',
									display : 'ҵ����',
									width : 120
								}, {
									name : 'rObjCode',
									display : 'Դ��ҵ����',
									width : 120
								} ],
						// ���ӱ������
						subGridOptions : {
							subgridcheck : true,
							url : '?model=common_contract_allsource&action=equJson',// ��ȡ�ӱ�����url
							// ���ݵ���̨�Ĳ�����������
							param : [ {
								'docType' : 'oa_present_present'
							}, {
								paramId : 'presentId',// ���ݸ���̨�Ĳ�������
								colId : 'id'// ��ȡ���������ݵ�������
							} ],
							// ��ʾ����
							afterProcess : function(data, rowDate, $tr) {
								if(data.number<=data.executedNum){
									$tr.find("td").css("background-color", "#A1A1A1");
								}
							},
							// ��ʾ����
							colModel : [{
								name : 'productNo',
								display : '���ϱ��',
								process : function( v,data,rowData,$row ){
									if( data.changeTips==1 ){
										return '<img title="����༭�Ĳ�Ʒ" src="images/changeedit.gif" />' + v;
									}else if( data.changeTips==2 ){
										return '<img title="��������Ĳ�Ʒ" src="images/new.gif" />' + v;
									}else{
										return v;
									}
								},
								width : 95
							}, {
								name : 'productName',
								width : 200,
								display : '��������',
								process : function(v,row){
							    	if(row.changeTips == 1 || row.changeTips == 2){
							    		return "<font color = 'red'>"+ v + "</font>"
							    	}else
							    		return v;
								}
							}, {
								name : 'productModel',
								display : '����ͺ�'
			//						,width : 40
							}, {
								name : 'number',
								display : '����',
								width : 40
							}, {
//								name : 'lockNum',
//								display : '��������',
//								width : 50,
//								process : function(v) {
//									if (v == '') {
//										return 0;
//									} else
//										return v;
//								}
//							}, {
								name : 'exeNum',
								display : '�������',
								width : 50,
								process : function(v) {
									if (v == '') {
										return 0;
									} else
										return v;
								}
							}, {
								name : 'lockedNum',
								display : '������',
								width : 50,
								process : function(v) {
									if (v == '') {
										return 0;
									} else
										return v;
								}
							}, {
								name : 'issuedShipNum',
								display : '���´﷢������',
								width : 90,
								process : function(v) {
									if (v == '') {
										return 0;
									} else
										return v;
								}
							}, {
								name : 'executedNum',
								display : '�ѷ�������',
								width : 60
							}, {
								name : 'issuedPurNum',
								display : '���´�ɹ�����',
								width : 90,
								process : function(v) {
									if (v == '') {
										return 0;
									} else
										return v;
								}
							}, {
								name : 'issuedProNum',
								display : '���´���������',
								width : 90,
								process : function(v) {
									if (v == '') {
										return 0;
									} else
										return v;
								}
							}, {
								name : 'backNum',
								display : '�˿�����',
								width : 60
							}, {
								name : 'arrivalPeriod',
								display : '��׼������',
								width : 80,
								process : function(v) {
									if (v == null) {
										return '0';
									} else {
										return v;
									}
								}
							} ]
						},
						comboEx : [ {
							text : '����״̬',
							key : 'DeliveryStatus',
							data : [ {
								text : 'δ����',
								value : 'WFH'
							}, {
								text : '�ѷ���',
								value : 'YFH'
							}, {
								text : '���ַ���',
								value : 'BFFH'
							}, {
								text : 'ֹͣ����',
								value : 'TZFH'
							} ]
						} ],

						menusEx : [
								{
									text : '�鿴��ϸ',
									icon : 'view',
									action : function(row) {
										showOpenWin('?model=stock_outplan_outplan&action=viewByPresent&id='
												+ row.id
												+��"&linkId="
												+ row.linkId
												+ "&objType=oa_present_present"
												+ "&skey=" + row['skey_']);
									}
								},
								{
									text : '�������',
									icon : 'lock',
									showMenuFn : function(row) {
										if (row.DeliveryStatus != 'YFH'
												&& row.ExaStatus == '���'
												&& row.DeliveryStatus != 'TZFH') {
											return true;
										}
										return false;
									},
									action : function(row) {
										var objCode = row.objCode;
										showOpenWin('?model=stock_lock_lock&action=toLokStock&id='
												+ row.id
												+ "&objCode="
												+ objCode
												+ "&objType=oa_present_present&skey="
												+ row['skey_']);
									}
								},
//								{
//									text : '�������',
//									icon : 'view',
//									showMenuFn : function(row) {
//										if (row.lExaStatus != '') {
//											return true;
//										}
//										return false;
//									},
//									action : function(row) {
//
//										showThickboxWin('controller/contract/contract/readview.php?itemtype=oa_present_equ_link&pid='
//												+ row.lid
//												+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
//									}
//								},
								{
									text : '�´﷢���ƻ�',
									icon : 'add',
									showMenuFn : function(row) {
										if ((row.dealStatus == 1 || row.dealStatus == 3)
												&& row.makeStatus != 'YXD'
												&& row.ExaStatus == '���'
												&& (row.DeliveryStatus == 'WFH' || row.DeliveryStatus == 'BFFH')) {
											return true;
										}
										return false;
									},
									action : function(row, rows, rowIds, g) {
										var idArr = g
												.getSubSelectRowCheckIds(rows);
										showModalWin("?model=stock_outplan_outplan&action=toAdd&id="
												+ row.id
												+ "&skey="
												+ row['skey_']
												+ "&equIds="
												+ idArr
												+ "&docType=oa_present_present"
												+ "&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=1100");
									}
								},
								{
									text : '�´�ɹ�����',
									icon : 'edit',
									showMenuFn : function(row) {
										if ((row.dealStatus == 1 || row.dealStatus == 3)
												&& row.ExaStatus == '���' && (row.DeliveryStatus == 'WFH' || row.DeliveryStatus == 'BFFH')) {
											return true;
										}
										return false;
									},
									action : function(row,rows, rowIds, g) {
										var idArr = g.getSubSelectRowCheckIds(rows);
										if (row.orderCode == '')
											var codeValue = row.orderTempCode;
										else
											var codeValue = row.orderCode;
										showModalWin('?model=purchase_external_external&action=purchasePlan&orderId='
												+ row.id
												+ "&orderCode="
												+ row.Code
												+ "&orderName="
												+ "&purchType=oa_present_present"
												+ "&skey="
												+ row['skey_']
												+ "&equIdArr="
												+ idArr
												+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=1150');
									}
								},
								{
									text : '�´���������',
									icon : 'add',
									showMenuFn : function(row) {
										if ((row.dealStatus == 1 || row.dealStatus == 3)
												&& row.ExaStatus == '���' && (row.DeliveryStatus == 'WFH' || row.DeliveryStatus == 'BFFH')) {
											return true;
										}
										return false;
									},
									action : function(row, rows, rowIds, g) {
										var eqIdArr = g
												.getSubSelectRowCheckIds(rows);
										showOpenWin("?model=produce_apply_produceapply&action=toApply&relDocId="
												+ row.id
												+ "&equIds="
												+ eqIdArr
												+ "&relDocType=PRESENT"
												+ "&skey=" + row['skey_']);
									}
								},
								{
									text : "�ر�����",
									icon : 'delete',
									showMenuFn : function(row) {
										if (row.DeliveryStatus != 'TZFH') {
											return true;
										}
										return false;
									},
									action : function(row) {
										$
												.ajax({
													type : 'POST',
													url : '?model=contract_common_allcontract&action=closeCont&skey='
															+ row['skey_'],
													data : {
														id : row.id,
														type : 'oa_present_present'
													},
													// async: false,
													success : function(data) {
														alert("�رճɹ�");
														show_page();
														return false;
													}
												});
									}
								// }, {
								// text : "�ָ�����",
								// icon : 'add',
								// showMenuFn : function(row) {
								// if (row.DeliveryStatus == 11) {
								// return true;
								// }
								// return false;
								// },
								// action : function(row) {
								// if (confirm('ȷ��Ҫ�ָ�������')) {
								// $.ajax({
								// type : 'POST',
								// url :
								// '?model=contract_common_allcontract&action=recoverCont&skey='
								// + row['skey_'],
								// data : {
								// id : row.id,
								// type : 'oa_present_present'
								// },
								// // async: false,
								// success : function(data) {
								// alert("�ָ��ɹ�");
								// show_page();
								// return false;
								// }
								// });
								// }
								// }
								}
//									,{
//			text : "�رշ�������",
//			icon : 'delete',
//			showMenuFn : function(row) {
//				if (row.DeliveryStatus != 'TZFH') {
//					return true;
//				}
//				return false;
//			},
//			action : function(row, rows, rowIds, g) {
//				var idArr = g.getSubSelectRowCheckIds(rows);
//				showOpenWin("?model=stock_outplan_outplan&action=toCloseOutMat&id="
//						+ row.id + "&equIds=" + idArr
//						+ "&docType=oa_present_present" + "&skey="
//						+ row['skey_']);
//			}
//		}
		],
						/**
						 * ��������
						 */
						searchitems : [ {
							display : '���',
							name : 'Code'
						}, {
					display : 'Դ����',
					name : 'orderCode'
				}, {
							display : 'ҵ����',
							name : 'objCode'
						}, {
							display : 'Դ��ҵ����',
							name : 'rObjCode'
				},{
					display : '������',
					name : 'createName'
						} ],
						sortname : 'ExaDT',
						sortorder : 'DESC'
					});
});