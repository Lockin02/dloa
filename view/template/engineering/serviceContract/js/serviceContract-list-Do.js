// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$(".serviceContractDoGrid").yxgrid("reload");
};
$(function() {
			$(".serviceContractDoGrid").yxgrid({
						//�������url�����ô����url������ʹ��model��action�Զ���װ
						model : 'engineering_serviceContract_serviceContract',
//						action : 'pageJson',
						action : 'pageJsonUnLimit',
						title : 'ִ���еķ����ͬ',
						isToolBar : false,
						showcheckbox : false,
						//����Ϣ
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									display : '��ͬ����',
									name : 'orderName',
									sortable : true,
									//���⴦���ֶκ���
									process : function(v,row) {
										return row.name;
									},
									width : '180'
								},{
									display : '������ͬ��',
									name : 'contractNo',
									sortable : true,
									width : '140'
								},{
									display : '��ͬǩԼ��',
									name : 'cusName',
									sortable : true,
									width : '120'
								}, {
									display : '��ͬ������',
									name : 'createName',
									sortable : true
								},{
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
								},  {
									display : '���״̬',
									name : 'ExaStatus',
//									datacode : 'SHZT',
									sortable : true
								},
									{
									display : '��ִͬ��״̬',
									name : 'status',
//									datacode : 'HTZT',
									sortable : true
								},
								{
									display : '������״̬',
									name : 'missionStatus',
									sortable : true
								}],
						param : {
							status : '��ִͬ����'
						},
						//��չ��ť
						buttonsEx : [],
						//��չ�Ҽ��˵�
						menusEx : [
						{
							text : '�鿴',
							icon : 'view',
							action : function(row,rows,grid) {
								if(row){
//									showThickboxWin("?model=engineering_serviceContract_serviceContract&action=init"
//										+ "&id="
//										+ row.id
//										+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height="
//										+ 400 + "&width=" + 700);
									showOpenWin('?model=engineering_serviceContract_serviceContract&action=init&perm=view&id='+row.id)
								}else{
									alert("��ѡ��һ������");
								}
							}

						},
						{
							text : '�´�������',
							icon : 'add',
							showMenuFn : function(row){
								if(row.ExaStatus == '���' && row.status == '��ִͬ����' && row.missionStatus == 'δ�´�' ){
									return true;
								}
								return false;
							},
							action : function(row,rows,grid){
								if(row){
//									if((row.ExaStatus == '�����' && row.status == '��ִͬ����')|(row.ExaStatus == '���' && row.status == '��ִͬ����')){
									if(row.ExaStatus == '���' && row.status == '��ִͬ����' && row.missionStatus == 'δ�´�' ){
										showOpenWin('?model=engineering_prjMissionStatement_esmmission&action=issueMissionStatement&contractId='+row.id );
//										showThickboxWin("?model=engineering_prjMissionStatement_esmmission&action=issueMissionStatement&contractId="
//										+ row.id
//										+ "&id="
//										+ row.id
//										+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height="
//										+ 500 + "&width=" + 800);
									}
								}
							}
						},
						{
							text : '�رպ�ͬ',
							icon : 'delete',
							action : function(row,rows,grid){
								if(row){
									if((row.ExaStatus == '�����' && row.status == '��ִͬ����')|(row.ExaStatus == '���' && row.status == '��ִͬ����')){
										if(confirm("ȷ��Ҫ�رշ����ͬ��" + row.name + "����")){
											location = '?model=engineering_serviceContract_serviceContract&action=putContractClose&contractId='+ row.id
										}
									}
								}
							}
						}],
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
						// title : '�ͻ���Ϣ',
						//ҵ���������
//						boName : '��Ӧ����ϵ��',
						//Ĭ�������ֶ���
						sortname : "createTime",
						//��ʾ�鿴��ť
						isViewAction : false
//						isAddAction : true,
//						isEditAction : false
					});

		});