// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$(".serviceContractMyAppGrid").yxgrid("reload");
};
$(function() {
			$(".serviceContractMyAppGrid").yxgrid({
						//�������url�����ô����url������ʹ��model��action�Զ���װ
//						 url :
						model : 'engineering_serviceContract_serviceContract',
//						action : 'toUnDoContractList&contractID='+$("#contractID").val()+"&systemCode="+$("#systemCode").val(),
						action : 'pageJson',
//						action : 'toMyApplicationTab',
						title : '������ķ����ͬ',
						isToolBar : false,
						showcheckbox : false,
						//����Ϣ
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								},
								{
									display : '��ͬǩ������',
									name : 'signDate',
									sortable : true,
									hide : true
								},
								{
									display : '��ͬ����',
									name : 'orderName',
									sortable : true,
									//���⴦���ֶκ���
									process : function(v,row) {
										return row.name;
									},
									width : '170'
								},{
									display : '������ͬ��',
									name : 'contractNo',
									sortable : true,
									width : '110'
								},{
									display : '��ͬǩԼ��',
									name : 'cusName',
									sortable : true,
									process : function(v,row){
										return row.cusName;
									},
									width : '110'
								}
//								, {
//									display : '����������',
//									name : 'createName',
//									sortable : true
//								}
								,{
									display : '���۸�����',
									name : 'salesLeader',
									sortable :��true
								}
								,{
									display : '����������',
									name : 'techdirector',
									sortable : true
								}
								,{
									display : '���Ÿ�����',
									name : 'depHeads',
									sortable : true
								}
								,
								{
									display : 'createId',
									name : 'createId',
									sortable : true,
									hide : true
								},
								{
									display : '���״̬',
									name : 'ExaStatus',
//									datacode : 'SHZT',
									sortable : true,
									width : '80'
								},{
									display : 'ִ��״̬',
									name : 'status',
//									datacode : 'SHZT',
									sortable : true,
									width : '80'
								},{
									display : '������״̬',
									name : 'missionStatus',
									sortable : true,
									width : '60'
								}],
//						param : {
//							status : '��ͬδ����'
//						},
						//��չ��ť
						buttonsEx : [],
						//��չ�Ҽ��˵�
						menusEx : [
						{
							text : '�鿴',
							icon : 'view',
							action :function(row,rows,grid) {
								if(row){
//									showThickboxWin("?model=engineering_serviceContract_serviceContract&action=init"
//										+ "&id="
//										+ row.id
//										+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height="
//										+ 400 + "&width=" + 700);
									showOpenWin("?model=engineering_serviceContract_serviceContract&action=init&perm=view&id="+row.id);
								}else{
									alert("��ѡ��һ������");
								}
							}

						},
						{
							text : '�༭',
							icon : 'edit',
							showMenuFn : function(row){
								if(row.ExaStatus == 'δ���' | row.ExaStatus == '���'){
									return true;
								}
								return false;
							},
							action : function(row,rows,grid) {
								if(row){
//									showThickboxWin("?model=engineering_serviceContract_serviceContract&action=init"
//										+ "&id="
//										+ row.id
//										+ "&placeValuesBefore&TB_iframe=true&modal=false&height="
//										+ 400 + "&width=" + 825);
									showOpenWin("?model=engineering_serviceContract_serviceContract&action=init&id="+row.id)
								}else{
									alert("��ѡ��һ������");
								}
							}
						},
						{
							text : '�ύ���',
							icon : 'add',
							showMenuFn : function(row){
								if(row.ExaStatus == 'δ���' && row.status == '��ͬδ����' || row.ExaStatus == '���'){
									return true;
								}
								return false;
							},
							action : function(row,rows,grid){
								if(row){
									if(row.ExaStatus == 'δ���' && row.status == '��ͬδ����' || row.ExaStatus == '���'){
										if(confirm("ȷ��Ҫ�������ͬ��" + row.name + "���ύ�����")){
											location = 'controller/engineering/serviceContract/ewf_index.php?actTo=ewfSelect&billId='+row.id+'&examCode=oa_contract_service&formName=�����ͬ����'
										}
									}
								}else{
									alert("��ѡ��δ��ˡ����ǡ�δ������״̬�ĺ�ͬ�����ύ");
								}

							}
						},
						{
							text : '����',
							icon : 'add',
							showMenuFn : function(row){
								if((row.ExaStatus == '�����' && row.status == '��ͬδ����')|(row.ExaStatus == '���' && row.status == '��ͬδ����')){
									return true;
								}
								return false;
							},
							action : function(row,rows,grid){
								if(row){
									if((row.ExaStatus == '�����' && row.status == '��ͬδ����')|(row.ExaStatus == '���' && row.status == '��ͬδ����')){
										if(confirm("ȷ��Ҫ���������ͬ��" + row.name + "����")){
											location = '?model=engineering_serviceContract_serviceContract&action=putContractStart&contractId='+ row.id
										}
									}
								}
							}
						},
						{
							text : 'ɾ��',
							icon : 'delete',
							showMenuFn : function(row){
								if(row.ExaStatus == 'δ���' && row.status == '��ͬδ����' || row.ExaStatus == '���'){
									return true;
								}
								return false;
							}
							,
							action : function(row){
								showThickboxWin('?model=engineering_serviceContract_serviceContract&action=deletesInfo&id='
										+row.id+
										"&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600"
								);
							}

						}
						],
						//��������
						searchitems : [
								{
									display : '��ͬ����',
									name : 'name'
								},{
									display : '��ͬ���',
									name : 'orderCodeOrTempSearch'
								}
								],
//						 title : '��ͬ��Ϣ',
						//ҵ���������
						boName : '�����ͬ',
						sortname : "createTime",
						//��ʾ�鿴��ť
						isViewAction : false,
						isAddAction : true,
						isEditAction : false,
						isDelAction : false
//						//�鿴��չ��Ϣ
//						toViewConfig : {
//							action : 'toRead',
//							formWidth : 400,
//							formHeight : 340
//						},
						//�����漰������������ת���⣬��2010��12��27��ע��
						//�ڵ����Ĵ��ڶ������������ύ�����������������ύ������ת��Ĵ�������ʱ�޷����ݴ���
//						toAddConfig : {
//									text : '�½�',
//									icon : '',
//									/**
//									 * Ĭ�ϵ��������ť�����¼�
//									 */
//
//									toAddFn : function(p) {
//										showOpenWin("?model=engineering_serviceContract_serviceContract&action=toAddContract2");
//
//									}
//						}

					});

		});