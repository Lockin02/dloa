// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$(".flibrarymyGrid").yxgrid("reload");
};
$(function() {
			$(".flibrarymyGrid").yxgrid({
						//�������url�����ô����url������ʹ��model��action�Զ���װ
						// url :
						// '?model=customer_customer_customer&action=pageJson',
						model : 'supplierManage_formal_flibrary',
						action : 'mypageJson',
						title : "�Ҹ���Ĺ�Ӧ��",
						isToolBar : false,
						showcheckbox : false,
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
								}, {
									display : '��Ӧ������',
									name : 'suppName',
									sortable : true,
									//���⴦���ֶκ���
									process : function(v,row) {
										return row.suppName;
									}
								},{
									display : 'ҵ����',
									name : 'busiCode',
									sortable : true
								},{
									display : '��Ӫ��Ʒ',
									name : 'products',
									sortable : true
								}, {
									display : '��ַ',
									name : 'address',
									sortable : true
								}, {
									display : '�칫�绰',
									name : 'plane',
									sortable : true
								}, {
									display : '����',
									name : 'fax',
									sortable : true
								}, {
									display : 'ע����',
									name : 'createName',
									sortable : true
								},{
									display : 'ע����Id',
									name : 'createId',
									sortable : true,
									hide : true
								}, {
									display : '��ϵ��',
									name : 'name',
									sortable : true
								}, {
									display : '״̬',
									name : 'status',
									sortable : true
								}],
						//��չ��ť
						buttonsEx : [{
									name : 'aduit',
									text : '���为����',
									icon : 'edit',
									action : function(row,rows,grid) {
										if(row){
											showThickboxWin("?model=supplierManage_formal_flibrary&action=toAduitP&id="+row.id+"&skey="+row['skey_']+"&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=700");
										}else{
										   alert("��ѡ��һ������");
										}
									}
								}
								,{
									name : 'stoc',
									text : '���ù�Ӧ��',
									icon : 'edit',
									action : function(row,rows,grid) {
										if(row && row.status=='����'){
											if( confirm("ȷ��������Ӧ�̡�"+ row.suppName +"����") ){
												location="?model=supplierManage_formal_flibrary&action=stoc&id="+row.id+"&skey="+row['skey_'];
											}
										}else{
											alert("��ѡ��һ�����ݲ���ѡ�е�����״ֻ̬���ǽ���");
										}
									}
								},
									{
									name : 'ctos',
									text : '���ù�Ӧ��',
									icon : 'edit',
									action : function(row,rows,grid) {
										if(row && row.status=='����'){
											if( confirm("ȷ�����ù�Ӧ�̡�"+ row.suppName +"����") ){
												location="?model=supplierManage_formal_flibrary&action=ctos&id="+row.id+"&skey="+row['skey_'];
											}
										}else{
											alert("��ѡ��һ�����ݲ���ѡ�е�����״ֻ̬��������");
										}
									}

								}],
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
									text : '���为����',
									icon : 'edit',
									action : function(row,rows,grid) {
										if(row){
											showThickboxWin("?model=supplierManage_formal_flibrary&action=toAduitP&id="+row.id+"&skey="+row['skey_']+"&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=700");
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

								},{
									text : 'ɾ����Ӧ��',
									icon : 'delete',
						//			showMenuFn : function(row) {
						//				if (row.ExaStatus == '���') {
						//					return true;
						//				}
						//				return false;
						//			},
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
														$(".flibrarymyGrid").yxgrid("reload");
													}else{
														alert('ɾ��ʧ��!');
													}
												}
											});
										}
									}
								}],
						//��������
						searchitems : [{
									display : '��Ӧ������',
									name : 'suppName'
								},{
									display : '��Ӫ��Ʒ',
									name : 'mainProduct'
								}],
						// title : '�ͻ���Ϣ',
						//ҵ���������
						boName : '��Ӧ������',
						//Ĭ�������ֶ���
						sortname : "updateTime",
						//Ĭ������˳��
						sortorder : "DESC",
						//��ʾ�鿴��ť
						isViewAction : true,
						//������Ӱ�ť
						isAddAction : false,
						//����ɾ����ť
						isDelAction : false,
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
															+ 600 + "&width=" + 800);
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