// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$("#flibraryOtherGrid").yxgrid("reload");
};
$(function() {
			$("#flibraryOtherGrid").yxgrid({
						//�������url�����ô����url������ʹ��model��action�Զ���װ
						// url :
						// '?model=customer_customer_customer&action=pageJson',
						model : 'supplierManage_formal_flibrary',

						 action : 'suppcontJson',
						title:'������Ӧ�̿�',
						showcheckbox : false,	//ȡ����ʾcheckbox
						isToolBar : false,		//ȡ����ʾ�б��Ϸ��Ĺ�����
						param:{"noSuppGrade":"A,B,C,D"},

						//����Ϣ
						colModel : [{
								display : 'id',
								name : 'id',
								sortable : true,
								hide : true
							}, {
								display : 'manageUserId',
								name : 'manageUserId',
								sortable : true,
								hide : true
							},{
								display : '��Ӧ�̱��',
								name : 'busiCode',
								sortable : true
							},{
								display : '��Ӧ������',
								name : 'suppName',
								sortable : true,
								//���⴦���ֶκ���
								process : function(v,row){
									return "<a href='#' onclick='showThickboxWin(\"?model=supplierManage_formal_flibrary&action=toRead&id="+row.id+"&objCode="
											+row.objCode+"&skey="+row['skey_']+"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\")'>" + row.suppName+ "</a>";
								},
								width:"200"
							},{
								display : '��Ӧ�����',
								name : 'suppCategoryName'
							},{
								display : '��Ӫ��Ʒ',
								name : 'products',
								sortable : true,
								//���⴦���ֶκ���
								process : function(v,row) {
									return row.products;
								},
								width:"200"
							}, {
								display : '��ַ',
								name : 'address',
								width:"200",
								hide : true
							}, {
								display : '��ϵ��',
								name : 'linkman',
								sortable : true
							},  {
								display : 'ְλ',
								name : 'position'
							},{
								display : '��ϵ�绰',
								name : 'mobile1'
							},  {
								display : 'ע����',
								name : 'createName',
								sortable : true
							}, {
								display : 'ע��ʱ��',
								name : 'createTime',
								sortable : true,
								width:120
							}
						],
						//��չ�Ҽ��˵�
						menusEx : [{
									text : '�鿴',
									icon : 'view',
									action : function(row,rows,grid) {
										if(row){
											showThickboxWin("?model=supplierManage_formal_flibrary&action=toRead&id="+row.id+"&objCode="
											+row.objCode+"&skey="+row['skey_']+"&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900");
										}else{
										   alert("��ѡ��һ������");
										}
									}

								},{
									text : '�༭',
									icon : 'edit',
									action : function(row,rows,grid) {
										if(row){
											showThickboxWin("?model=supplierManage_formal_flibrary&action=toEdit&id="+row.id+"&objCode="
											+row.objCode+"&skey="+row['skey_']+"&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900");
										}else{
										   alert("��ѡ��һ������");
										}
									}

								},{
									text : '���为����',
									icon : 'edit',
									action : function(row,rows,grid) {
										if(row){
											showThickboxWin("?model=supplierManage_formal_flibrary&action=toAduitP&id="
											+row.id+"&skey="+row['skey_']+"&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=700");
										}else{
										   alert("��ѡ��һ������");
										}
									}

								}
								,{
									text : '�����¹�Ӧ������',
									icon : 'add',
									action : function(row) {
										if(row){
											$.ajax({
												type : "POST",
												url : "?model=supplierManage_assessment_supasses&action=isAsses",
												data : {
													suppId : row.id,
								    				supassType:"xgyspg"
												},
												success : function(msg) {
													if (msg == 1) {
														location="?model=supplierManage_assessment_supasses&action=toQuarterAss&assesType=xgyspg&suppId="+row.id;
													}else {
														alert('�ù�Ӧ���ѽ����¹�Ӧ������!');
													}
												}
											});
										}
									}
								}
								,{
									text : 'ɾ����Ӧ��',
									icon : 'delete',
									action : function(row) {
										if(confirm('ȷ��ɾ����')){
											$.ajax({
												type : "POST",
												url : "?model=supplierManage_formal_flibrary&action=delSupplier",
												data : {
													id : row.id
												},
												success : function(msg) {
													if (msg == 1) {
														alert('ɾ���ɹ���');
														$(".flibraryGrid").yxgrid("reload");
													}else if(msg ==0){
														alert('ɾ��ʧ��!');
													}else if(msg ==2){
														alert('û��Ȩ�޽��в���!');
													}
												}
											});
										}
									}
								}
//								,{
//									text : '�´���������',
//									icon : 'add',
//									action : function(row) {
//										if(row){
//											$.ajax({
//												type : "POST",
//												url : "?model=supplierManage_assessment_task&action=isTask",
//												data : {
//													suppId : row.id,
//								    				supassType:"xgyspg"
//												},
//												success : function(msg) {
//													if (msg == 1) {
//														showThickboxWin("?model=supplierManage_assessment_task&action=toAddBySupp&assesType=xgyspg&suppId="+row.id
//																		+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height="
//																		+ 400 + "&width=" + 800);
//													}else {
//														alert('�ù�Ӧ�����´��¹�Ӧ����������!');
//													}
//												}
//											});
//										}
//									}
//								}
		],
						//��������
						searchitems : [{
									display : '��Ӧ�̱��',
									name : 'busiCode'
								},{
									display : '��Ӧ������',
									name : 'suppName'
								},{
                            display : '������',
                            name : 'usedName'
                        }],
						// title : '�ͻ���Ϣ',
						//ҵ���������
						boName : '��Ӧ������',
						//Ĭ�������ֶ���
						sortname : "updateTime",
						//Ĭ������˳��
						sortorder : "DESC",
						//��ʾ�鿴��ť
						isViewAction : false,
						//������Ӱ�ť
						isAddAction : false,
						//����ɾ����ť
						isDelAction : false,
						isEditAction : false,
						//�鿴��չ��Ϣ
						toViewConfig : {
											text : '�鿴',
											/**
											 * Ĭ�ϵ���鿴��ť�����¼�
											 */
											toViewFn : function(p, g) {
												var c = p.toViewConfig;
												var w = c.formWidth ? c.formWidth : p.formWidth;
												var h = c.formHeight ? c.formHeight : p.formHeight;
												var rowObj = g.getSelectedRow();
												if (rowObj) {
													showThickboxWin("?model="
															+ p.model
															+ "&action="
															+ p.toViewConfig.action
															+ c.plusUrl
															+ "&id="
															+ rowObj.data('data').id
															+"&objCode="
															+ rowObj.data('data').objCode
															+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height="
															+ 600 + "&width=" + 800);
												} else {
													alert('��ѡ��һ�м�¼��');
												}
											},
											/**
											 * ���ر�Ĭ�ϵ��õĺ�̨����
											 */
											action : 'toRead'
			}
					});

		});