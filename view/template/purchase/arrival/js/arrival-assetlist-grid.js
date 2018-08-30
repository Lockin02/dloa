var show_page = function(page) {
	$("#arrivalAssetGrid").yxsubgrid("reload");
};
$(function() {
	$("#arrivalAssetGrid")
			.yxsubgrid(
					{
						model : 'purchase_arrival_arrival',
						title : '�ʲ�����֪ͨ��',
						// action:'myPageJson',
						param : {
							'arrivalType' : 'asset'
						},
						isEditAction : false,
						isEditAction : false,
						isViewAction : false,
						isDelAction : false,
						isAddAction : false,
						showcheckbox : false,
						// ����Ϣ
						colModel : [ {
							display : 'id',
							name : 'id',
							sortable : true,
							hide : true
						}, {
							name : 'arrivalCode',
							display : '���ϵ���',
							sortable : true,
							width : 180
						}, {
							name : 'state',
							display : '����֪ͨ��״̬',
							sortable : true,
							process : function(v, row) {
								if (row.state == '0') {
									return "δִ��";
								} else if (row.state == '4'){
									return "����ִ��";
								}else if (row.state == '2'){
									return "��ִ��";
								}
							}
						}, {
							name : 'purchaseId',
							display : '����id',
							hide : true
						}, {
							name : 'purchaseCode',
							display : '�ɹ��������',
							sortable : true,
							width : 200
						}, {
							name : 'supplierName',
							display : '��Ӧ������',
							sortable : true,
							width : 200
						}, {
							name : 'supplierId',
							display : '��Ӧ��id',
							hide : true
						}, {
							name : 'purchManId',
							display : '�ɹ�ԱID',
							hide : true
						}, {
							name : 'purchManName',
							display : '�ɹ�Ա',
							sortable : true,
							width : 120
						}, {
							name : 'purchMode',
							display : '�ɹ���ʽ',
							hide : true,
							datacode : 'cgfs'
						}, {
							name : 'stockId',
							display : '���ϲֿ�Id',
							hide : true
						}, {
							name : 'stockName',
							display : '���ϲֿ�����',
							sortable : true,
							width : 120
						} ],
						// ���ӱ������
						subGridOptions : {
							url : '?model=purchase_arrival_equipment&action=pageJson',
							param : [ {
								paramId : 'arrivalId',
								colId : 'id'
							} ],
							colModel : [ {
								name : 'sequence',
								display : '���ϱ��'
							}, {
								name : 'productName',
								width : 200,
								display : '��������'
							}, {
								name : 'batchNum',
								display : "���κ�"
							}, {
								name : 'arrivalDate',
								display : "��������"
							}, {
								name : 'month',
								display : "�·�"
							}, {
								name : 'oldArrivalNum',
								display : "��������"
							}, {
								name : 'storageNum',
								display : "���������"
							} , {
								name : 'deliveredNum',
								display : "��������"
							} ]
						},
						// ��չ�Ҽ��˵�
						menusEx : [
								{
									name : 'view',
									text : '�鿴',
									icon : 'view',
									action : function(row, rows, grid) {
										if (row) {
											showThickboxWin("?model=purchase_arrival_arrival&action=init&perm=view&id="
													+ row.id
													+ "&skey="
													+ row['skey_']
													+ "&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=900");
										} else {
											alert("��ѡ��һ������");
										}
									}
								},
								{
									name : 'edit',
									text : '�������յ�',
									icon : 'edit',
									showMenuFn : function(row) {
										if (row.state == 0||row.state == 4) {
											return true;
										}
										return false;
									},
									action : function(row, rows, grid) {
										if (row) {
											showThickboxWin("?model=asset_purchase_receive_receive&action=toArrivalPush&arrivalId="
													+ row.id
													+ "&skey="
													+ row['skey_']
													+ "&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=900");
										} else {
											alert("��ѡ��һ������");
										}
									}
								},
								{
									name : 'edit',
									text : '�������ϵ�',
									icon : 'edit',
									showMenuFn : function(row) {
//										if (row.state == 0) {
											return true;
//										}
//										return false;
									},
									action : function(row, rows, grid) {
										if (row) {
											showThickboxWin("?model=purchase_delivered_delivered&action=toPushByArrival&arrivalId="
													+ row.id
													+ "&skey="
													+ row['skey_']
													+ "&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=900");
										} else {
											alert("��ѡ��һ������");
										}
									}
//								},
//								{
//									name : 'edit',
//									text : '����ȷ��',
//									icon : 'edit',
//									showMenuFn : function(row) {
//										if (row.state == 0) {
//											return true;
//										}
//										return false;
//									},
//									action : function(row, rows, grid) {
//										if (row) {
//											showThickboxWin("?model=purchase_arrival_arrival&action=toConfAsset&id="
//													+ row.id
//													+ "&skey="
//													+ row['skey_']
//													+ "&placeValuesBefore&TB_iframe=true&modal=false&height=700&width=900");
//										} else {
//											alert("��ѡ��һ������");
//										}
//									}
								},
									{
									    text:'�ر�',
									    icon:'delete',
										showMenuFn : function(row) {
											if (row.state != 2) {
												return true;
											}
											return false;
										},
									    action:function(row,rows,grid){
									    	if(row){
									    		if(window.confirm("ȷ��Ҫ�ر�?")){
									    		     $.ajax({
									    		         type:"POST",
									    		         url:"?model=purchase_arrival_arrival&action=changStateClose",
									    		         data:{
									    		         	id:row.id
									    		         },
									    		         success:function(msg){
									    		            if(msg==1){
									    		                alert('�رճɹ�!');
									    		                show_page();
									    		            }
									    		         }
									    		     });
									    		}
									    	}
									    }
									} ],

						comboEx : [ {
							text : '����֪ͨ��״̬',
							key : 'state',
							data : [ {
								text : 'δִ��',
								value : '0'
							}, {
								text : '��ִ��',
								value : '2'
							} ]
						} ],
						searchitems : [ {
							display : '���ϵ���',
							name : 'arrivalCode'
						}, {
							display : '�ɹ�Ա',
							name : 'purchManName'
						}, {
							display : '��Ӧ��',
							name : 'supplierName'
						}, {
							display : '��������',
							name : 'productName'
						}, {
							display : '���ϱ��',
							name : 'sequence'
						} ],
						// Ĭ������˳��
						sortorder : "DESC",
						sortname : "updateTime"
					});
});