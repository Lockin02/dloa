// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$("#flibraryFailGrid").yxgrid("reload");
};
$(function() {
			$("#flibraryFailGrid").yxgrid({
						//�������url�����ô����url������ʹ��model��action�Զ���װ
						// url :
						// '?model=customer_customer_customer&action=pageJson',
						model : 'supplierManage_formal_flibrary',

					    action : 'suppcontJson',
						title:'���ϸ�Ӧ�̿�',
						showcheckbox : false,	//ȡ����ʾcheckbox
						isToolBar : false,		//ȡ����ʾ�б��Ϸ��Ĺ�����
						param:{"suppGrade":"D"},


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
							},{
								display : '��ϵ��',
								name : 'linkman',
								sortable : true
							},  {
								display : 'ְλ',
								name : 'position'
							},{
								display : '��ϵ�绰',
								name : 'mobile1'
							},{
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

								}
//								,{
//									text : 'ɾ����Ӧ��',
//									icon : 'delete',
//									action : function(row) {
//										if(confirm('ȷ��ɾ����')){
//											$.ajax({
//												type : "POST",
//												url : "?model=supplierManage_formal_flibrary&action=delSupplier",
//												data : {
//													id : row.id
//												},
//												success : function(msg) {
//													if (msg == 1) {
//														alert('ɾ���ɹ���');
//														$(".flibraryGrid").yxgrid("reload");
//													}else if(msg ==0){
//														alert('ɾ��ʧ��!');
//													}else if(msg ==2){
//														alert('û��Ȩ�޽��в���!');
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