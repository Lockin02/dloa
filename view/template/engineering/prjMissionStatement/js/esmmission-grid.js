var show_page = function(page) {
	$(".esmmissionGrid").yxgrid("reload");
};
$(function() {
			$(".esmmissionGrid").yxgrid({
						model : 'engineering_prjMissionStatement_esmmission',
						action : 'pageJson',
						title : '��Ŀ������',
						isToolBar : false,
						showcheckbox : false,
						//����Ϣ
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									name : 'name',
									display : '����������',
									sortable : true,
									width : '200'
								}, {
									name : 'startDate',
									display : '��Ŀ��ͬ��(��ʼ)',
									sortable : true
								}, {
									name : 'endDate',
									display : '��Ŀ��ͬ��(����)',
									sortable : true
								}, {
									name : 'status',
									display : '����״̬',
									sortable : true
								}, {
									name : 'executor',
									display : '������',
									sortable : true
								}, {
									name : 'executorTime',
//									display : '����ʱ��',
									display : '�����´�ʱ��',
									width : '150',
									sortable : true
								}, {
									name : 'projectName',
									display : '������Ŀ����',
									sortable : true,
									width : '300'
								}
						],
//						toAddConfig:{formWidth : 700,formHeight : 400},

						//��չ�Ҽ��˵�
						menusEx : [
						{
							text : '�鿴',
							icon : 'view',
							action : function(row,rows,grid) {
								if(row){
									showThickboxWin("?model=engineering_prjMissionStatement_esmmission&action=init"
										+ "&contractId="
										+ row.contractId
										+ "&id="
										+ row.id
										+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height="
										+ 400 + "&width=" + 800);
//									showOpenWin("?model=engineering_prjMissionStatement_esmmission&action=init&perm=view&contractId="+row.contractId+"&id="+row.id);
								}else{
									alert("��ѡ��һ������");
								}
							}
						}
//						,{
//							text : '�༭',
//							icon : 'edit',
//							showMenuFn : function(row){
//								if(row.status == '������' || row.status == 'δ����'){
//									return true;
//								}
//								return false;
//							},
//							action : function(row,rows,grid) {
//								if(row){
//									showThickboxWin("?model=engineering_prjMissionStatement_esmmission&action=init"
//										+ "&id="
//										+ row.id
//										+ "&placeValuesBefore&TB_iframe=true&modal=false&height="
//										+ 400 + "&width=" + 800);
////									showOpenWin("?model=engineering_prjMissionStatement_esmmission&action=init&id="+row.id+"&contractId="+row.contractId);
//								}else{
//									alert("��ѡ��һ������");
//								}
//							}
//						}
						,{
							text : '����',
							icon : 'add',
							showMenuFn : function(row){
								if(row.status == '������' || row.status == 'δ����'){
									return true;
								}
								return false;
							},
							action : function(row,rows,grid){
								if(row){
									if(row.status == '������' || row.status == 'δ����'){
									showThickboxWin("?model=engineering_prjMissionStatement_esmmission&action=toDealIssue"
										+ "&contractId="
										+ row.contractId
//										+ "&missionId="
//										+ row.id
										+ "&placeValuesBefore&TB_iframe=true&modal=false&height="
										+ 400 + "&width=" + 800);
//										showOpenWin("?model=engineering_prjMissionStatement_esmmission&action=toDealIssue&contractId="+row.contractId+"&missionId="+row.id);
									}
								}
							}
						}
//						,
//						{
//							text : '����',
//							icon : 'view',
//							showMenuFn : function(row){
//								if(row.status == '������'){
//									return true;
//								}
//								return false;
//							},
//							action : function(row,rows,grid){
//								if(row){
//									if(row.status == '������'){
//									showThickboxWin("?model=engineering_prjMissionStatement_esmmission&action=dealIssue"
//										+ "&contractId="
//										+ row.id
//										+ "&placeValuesBefore&TB_iframe=true&modal=false&height="
//										+ 400 + "&width=" + 700);
//									}
//								}
//							}
//
//
//						}
						],
						//��������
						searchitems : [
								{
									display : '����������',
									name : 'name'
								},
								{
									display : '������Ŀ����',
									name : 'projectName'
								}
								],
						// title : '�ͻ���Ϣ',
						//ҵ���������
//						boName : '��Ӧ����ϵ��',
						//Ĭ�������ֶ���
						sortname : "id",
						//Ĭ������˳��
						sortorder : "ASC",
						//��չ��ť
						buttonsEx : [],
						isViewAction : false,
						isEditAction : false,

						isAddAction : false,
						isDelAction : false

					});
		});