// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$(".serviceContractCloseGrid").yxgrid("reload");
};
$(function() {
			$(".serviceContractCloseGrid").yxgrid({
						//�������url�����ô����url������ʹ��model��action�Զ���װ
//						 url :
						model : 'engineering_serviceContract_serviceContract',
//						action : 'pageJson',
						action : 'pageJsonUnLimit',
						title : '�ѹرյķ����ͬ',
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
									}
								},{
									display : '������ͬ��',
									name : 'contractNo',
									sortable : true
								},{
									display : '��ͬǩԼ��',
									name : 'cusName',
									sortable : true
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
								},{
									display : '��ִͬ��״̬',
									name : 'status',
//									datacode : 'HTZT',
									sortable : true
								}],
						param : {
							status : '��ɺ�ͬ'
						},
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
//						isEditAction : false,
//						isDelAction : true,
//						param : { ExaStatus : 'WCHT'}
					});

		});