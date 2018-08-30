$(document).ready(function(){
	// ��Ŀ�������
	$("#projectName").yxcombogrid_project({
		hiddenId : 'projectId',
		nameCol : 'projectName',
		width : 600,
		isFocusoutCheck : false,
		isShowButton : false,
		gridOptions : {
			isShowButton : true,
			showcheckbox : false,
			action : 'myProjectListPageJson',
			colModel : [{
					display : '��Ŀ����',
					name : 'projectName',
					process : function(v,row){
						if(row.isCanEdit == 1){
							return "<span style='color:blue'>" + v + "</span>";
						}else{
							return v;
						}
					}
				}, {
					display : '��Ŀ���',
					name : 'projectCode',
					process : function(v,row){
						if(row.isCanEdit == 1){
							return "<span style='color:blue'>" + v + "</span>";
						}else{
							return v;
						}
					}
				}, {
					display : '�������´�',
					name : 'officeName'
				}, {
					display : '��Ŀ����',
					name : 'managerName'
				}, {
					display : 'Ԥ�ƽ�������',
					name : 'planEndDate',
					width : 80
				}
			],
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#projectCode").val(data.projectCode);
					$("#planEndDate").val(data.planEndDate);

					//������ĿĬ��ֵ
					$("#worklogItem").yxeditgrid('setColValue','projectCode',data.projectCode);
					$("#worklogItem").yxeditgrid('setColValue','projectName',data.projectName);
					$("#worklogItem").yxeditgrid('setColValue','projectId',data.id);

					//�����ó���¼��ĿĬ��
					$("#importTable").yxeditgrid('setColValue','projectCode',data.projectCode);
					$("#importTable").yxeditgrid('setColValue','projectName',data.projectName);
					$("#importTable").yxeditgrid('setColValue','projectId',data.id);


					//���ò��Կ�ʹ�ü�¼��ĿĬ��
					$("#importCardTable").yxeditgrid('setColValue','projectCode',data.projectCode);
					$("#importCardTable").yxeditgrid('setColValue','projectName',data.projectName);
					$("#importCardTable").yxeditgrid('setColValue','projectId',data.id);
				}
			}
		}
	});

});