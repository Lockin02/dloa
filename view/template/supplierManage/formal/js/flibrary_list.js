// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$(".flibraryGrid").yxgrid("reload");
};
$(function() {
			$(".flibraryGrid").yxgrid({
						//�������url�����ô����url������ʹ��model��action�Զ���װ
						// url :
						// '?model=customer_customer_customer&action=pageJson',
						model : 'supplierManage_formal_flibrary',

						action : 'suppcontJson',
						title:'��Ӧ��������Ϣ--��Ӫ��',
						showcheckbox : false,	//ȡ����ʾcheckbox
//						isToolBar : false,		//ȡ����ʾ�б��Ϸ��Ĺ�����


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
								display : '��Ӧ�̵ȼ�',
								name : 'suppGrade',
								sortable : true,
								width:"80"
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
								}
							}, {
								display : '��ַ',
								name : 'address',
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
						//��չ��ť
						buttonsEx : [
//							{
//									name : 'aduit',
//									text : '���为����',
//									icon : 'edit',
//									action : function(row,rows,grid) {
//										if(row){
//											showThickboxWin("?model=supplierManage_formal_flibrary&action=toAduitP&id="+row.id+"&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=700");
//										}else{
//										   alert("��ѡ��һ������");
//										}
//									}
//								},
									{
									name : 'add',
									text : '����',
									icon : 'add',
									action : function(row,rows,grid) {
											window.open("?model=supplierManage_formal_flibrary&action=toAdd&flag=1&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=1000");

									}
								}
								,{
									name : 'inport',
									text : '������¹�Ӧ��',
									icon : 'excel',
									action : function(row,rows,grid) {
										$.ajax({
											type : 'POST',
											url : '?model=supplierManage_formal_flibrary&action=getLimits',
											data : {
												'limitName' : '���빩Ӧ��'
											},
											async : false,
											success : function(data) {
												if (data == 1) {
													showThickboxWin("?model=supplierManage_formal_flibrary&action=toExcel"
							         					 + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
												}else{
													alert('û��Ȩ�޲���������ϵ����Ա��ͨȨ��');
												}
											}
										});
									}
								}
								,{
									name : 'outport',
									text : '������Ӧ��',
									icon : 'excel',
									action : function(row,rows,grid) {
										$.ajax({
											type : 'POST',
											url : '?model=supplierManage_formal_flibrary&action=getLimits',
											data : {
												'limitName' : '������Ӧ��'
											},
											async : false,
											success : function(data) {
												if (data == 1) {
													url = "?model=supplierManage_formal_flibrary&action=excelOutport";
													window.open(url,"", "width=200,height=200,top=200,left=200");
												}else{
													alert('û��Ȩ�޲���������ϵ����Ա��ͨȨ��');
												}
											}
										});
									}
								}
//								,{
//									name : 'stoc',
//									text : '���ù�Ӧ��',
//									icon : 'edit',
//									action : function(row,rows,grid) {
//										if(row && row.status=='����'){
//											if( confirm("ȷ��������Ӧ�̡�"+ row.suppName +"����") ){
//												location="?model=supplierManage_formal_flibrary&action=stoc&id="+row.id;
//											}
//										}else{
//											alert("��ѡ��һ�����ݲ���ѡ�е�����״ֻ̬���ǽ���");
//										}
//									}
//								},
//									{
//									name : 'ctos',
//									text : '���ù�Ӧ��',
//									icon : 'edit',
//									action : function(row,rows,grid) {
//										if(row && row.status=='����'){
//											if( confirm("ȷ�����ù�Ӧ�̡�"+ row.suppName +"����") ){
//												location="?model=supplierManage_formal_flibrary&action=ctos&id="+row.id;
//											}
//										}else{
//											alert("��ѡ��һ�����ݲ���ѡ�е�����״ֻ̬��������");
//										}
//									}
//
//								}
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
											+row.objCode+"&suppGrade="+row.suppGrade+"&skey="+row['skey_']+"&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900");
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

								},{
									name : 'stoc',
									text : '���ù�Ӧ��',
									icon : 'edit',
									showMenuFn : function(row) {
										if (row.status == '����') {
											return true;
										}
										return false;
									},
									action : function(row,rows,grid) {
											if( confirm("ȷ��������Ӧ�̡�"+ row.suppName +"����") ){
												location="?model=supplierManage_formal_flibrary&action=stoc&id="+row.id+"&skey="+row['skey_'];
											}
										}
								},
									{
									name : 'ctos',
									text : '���ù�Ӧ��',
									icon : 'edit',
									showMenuFn : function(row) {
										if (row.status == '����') {
											return true;
										}
										return false;
									},
									action : function(row,rows,grid) {
											if( confirm("ȷ�����ù�Ӧ�̡�"+ row.suppName +"����") ){
												location="?model=supplierManage_formal_flibrary&action=ctos&id="+row.id+"&skey="+row['skey_'];
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
			},

						//�޸���չ��Ϣ
						toEditConfig : {
											text : '�༭',
											/**
											 * Ĭ�ϵ���༭��ť�����¼�
											 */
											toEditFn : function(p, g) {
												var c = p.toEditConfig;
												var w = c.formWidth ? c.formWidth : p.formWidth;
												var h = c.formHeight ? c.formHeight : p.formHeight;
												var rowObj = g.getSelectedRow();
												if (rowObj) {
													showThickboxWin("?model="
															+ p.model
															+ "&action="
															+ c.action
															+ c.plusUrl
															+ "&id="
															+ rowObj.data('data').id
															+"&objCode="
															+ rowObj.data('data').objCode
															+ "&placeValuesBefore&TB_iframe=true&modal=false&height="
															+600 + "&width=" + 1000);
												} else {
													alert('��ѡ��һ�м�¼��');
												}
											},
											/**
											 * ���ر�Ĭ�ϵ��õĺ�̨����
											 */
											action : 'toEdit'

										}
					});

		});