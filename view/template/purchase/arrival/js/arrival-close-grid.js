var show_page = function(page) {
	$("#arrivalCloseGrid").yxsubgrid("reload");
};
$(function() {
			$("#arrivalCloseGrid").yxsubgrid({
				model : 'purchase_arrival_arrival',
               	title : '��ִ������֪ͨ��',
               	isToolBar:false,
               	showcheckbox:false,
               	param:{'state':"2"},
						//����Ϣ
					colModel : [{
     								display : 'id',
     								name : 'id',
     								sortable : true,
     								hide : true
						        },{
                					name : 'arrivalCode',
                  					display : '���ϵ���',
                  					sortable : true,
                  					width:180
                              },{
                    					name : 'purchaseId',
                  					display : '����id',
                  					hide : true
                              },{
                    					name : 'purchaseCode',
                  					display : '�ɹ��������',
                  					sortable : true
                              },{
                    					name : 'arrivalType',
                  					display : '��������',
                  					sortable : true,
                  					datacode:'ARRIVALTYPE'
                              },{
                    					name : 'supplierName',
                  					display : '��Ӧ������',
                  					sortable : true,
      								width:150

                              },{
                    					name : 'supplierId',
                  					display : '��Ӧ��id',
                  					hide : true
                              },{
                    					name : 'purchManId',
                  					display : '�ɹ�ԱID',
                  					hide : true
                              },{
                    					name : 'purchManName',
                  					display : '�ɹ�Ա',
                  					sortable : true
                              },{
                    					name : 'purchMode',
                  					display : '�ɹ���ʽ',
                  					hide : true,
                  					datacode:'cgfs'
                              },{
                    					name : 'stockId',
                  					display : '���ϲֿ�Id',
                  					hide : true
                              },{
                    					name : 'stockName',
                  					display : '���ϲֿ�����',
                  					sortable : true
                              },{
                    					name : 'state',
                  					display : '����֪ͨ��״̬',
                  					sortable : true,
									process : function(v, row) {
										if (row.state == '0') {
											return "δִ��";
										} else {
											return "��ִ��";
										}
									}
                              }],
							// ���ӱ������
							subGridOptions : {
								url : '?model=purchase_arrival_equipment&action=pageJson',
								param : [{
											paramId : 'arrivalId',
											colId : 'id'
										}],
								colModel : [{
											name : 'sequence',
											display : '���ϱ��'
										}, {
											name : 'productName',
											width : 200,
											display : '��������'
										},{
											name : 'batchNum',
											display : "���κ�"
										},{
											name : 'arrivalDate',
											display : "��������"
										},{
											name : 'month',
											display : "�·�"
										}, {
											name : 'arrivalNum',
											display : "��������"
										},{
											name : 'storageNum',
											display : "���������"
										},{
                                            name : 'qualityPassNum',
                                            display : "�ʼ�ϸ�����"
                                        },{
                                            name : 'qualitedRate',
                                            display : "�ʼ�ϸ���",
                                            process : function(v,row){
                                                if(v!=""){
                                                    return "<a href='javascript:void(0)' onclick='showOpenWin(\"?model=produce_quality_qualitytask&action=toTaskReportTab&sourceId=" + row.id  +"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\")'>" + v + "</a>";
                                                }else{
                                                    return v;
                                                }
                                            }
                                        }]
							},
						//��չ�Ҽ��˵�
						menusEx : [
						{
							text : '�鿴',
							icon : 'view',
							action :function(row,rows,grid) {
								if(row){
									showThickboxWin("?model=purchase_arrival_arrival&action=init&perm=view&type=close&id="+row.id+"&skey="+row['skey_']+"&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=800");
								}else{
									alert("��ѡ��һ������");
								}
							}

						}
						],
							searchitems : [{
								display : '���ϵ���',
								name : 'arrivalCode'
							}, {
								display : '�ɹ�Ա',
								name : 'purchManName'
							},{
								display : '��Ӧ��',
								name : 'supplierName'
							},{
								display : '��������',
								name : 'productName'
							},{
								display : '���ϱ��',
								name : 'sequence'
							}],
							// Ĭ������˳��
							sortorder : "DESC",
							sortname:"updateTime"
 		});
 });