
$(document).ready(function() {

	$("#wholeListInfo").yxeditgrid({
		objName : 'workVerify[wholeList]',
		url : '?model=outsourcing_workverify_verifyDetail&action=listJson',
		param : {
			parentId :$("#id").val(),
			outsourcingArr :'HTWBFS-01,HTWBFS-03'
		},
		type : 'view',
		dir : 'ASC',
		colModel : [ {
					display : '����',
					name : 'officeName',
					type : 'txt',
					width :50
				},    {
					display : 'ʡ��',
					name : 'province',
					width :50
				}, {
					display : '����',
					name : 'outsourcingName',
					width :50
				},{
					display : '��Ŀ����',
					name : 'projectName',
					width : 120
				},  {
					display : '��ĿID',
					name : 'projectId',
					type : 'txt',
					type:'hidden'
				},{
					display : '��Ŀ���',
					name : 'projectCode',
					type : 'txt',
					width : 100
				},{
					display : '�����ͬ��',
					name : 'outsourceContractCode',
					type : 'txt',
					width : 80
				},  {
					display : '�����ͬID',
					name : 'outsourceContractId',
					type : 'txt',
					type:'hidden'
				},{
					display : '�����˾',
					name : 'outsourceSupp',
					type : 'txt',
					width : 80
				},  {
					display : '�����˾ID',
					name : 'outsourceSuppId',
					type : 'txt',
					type:'hidden'
				}, {
					display : '������',
					name : 'principal',
					type : 'txt',
					width : 50
				},{
					display : '�ܽ���',
					name : 'scheduleTotal',
					type : 'txt',
					width : 50
				},{
					display : '���ڽ���',
					name : 'presentSchedule',
					width : 50
				}, {
					display : '�ܽ��',
					name : 'feeTotal',
					width : 50
				},{
					display : '��˱�ע',
					name : 'managerAuditRemark',
					type : 'txt',
					align : 'left',
					width : 120,
					process : function(v,row){
						var remarkStr='';
						if(row.managerAuditRemark!=""){
							remarkStr+=row.managerName+":<br/>"+row.managerAuditRemark+"<br/>";
						}
						if(row.serverAuditRemark!=''){
							remarkStr+=row.serverManagerName+":<br/>"+row.serverAuditRemark+"<br/>";
						}
						if(row.areaAuditRemark!=""){
							remarkStr+=row.areaManager+":<br/>"+row.areaAuditRemark;
						}
						return remarkStr;
					}
				}]
	});

	$("#rentListInfo").yxeditgrid({
		objName : 'workVerify[rentList]',
		url : '?model=outsourcing_workverify_verifyDetail&action=listJson',
		param : {
			parentId :$("#id").val(),
			outsourcingArr :'HTWBFS-02'
		},
		type : 'view',
		dir : 'ASC',
		colModel : [ {
					display : '����',
					name : 'officeName',
					type : 'txt',
					width :50
				},   {
					display : 'ʡ��',
					name : 'province',
					width :50
				}, {
					display : '��Ŀ����',
					name : 'projectName',
					type : 'txt',
					width : 120
				},  {
					display : '��Ŀ���',
					name : 'projectCode',
					type : 'txt',
					width : 100
				},{
					display : '�����ͬ��',
					name : 'outsourceContractCode',
					type : 'txt',
					width : 80
				}, {
					display : '�����˾',
					name : 'outsourceSupp',
					type : 'txt',
					width : 80
				},  {
					display : '������',
					name : 'principal',
					type : 'txt',
					width : 50
				},{
					display : '����',
					name : 'userName',
					type : 'txt',
					width :50,
					readonly:true,
					process : function(v,row){
							return "<a href='#' onclick='showModalWin(\"?model=engineering_worklog_esmworklog&action=getDetailList&projectId=" + row.projectId +"&createId="+row.userId+"&beginDate="+row.beginDate+"&endDate="+row.endDate+"\",\"1\",\"��Ա��־\")'>" + v + "</a>";
					}
				},{
					display : '���ڿ�ʼ',
					name : 'beginDate',
					width : 70
				},{
					display : '���ڽ���',
					name : 'endDate',
					width : 70
				},{
					display : '�Ƽ�����',
					name : 'feeDay',
					width : 50
				}, {
					display : '����',
					name : 'confirmFee',
					width : 50
				}, {
					display : '�ܽ��',
					name : 'feeTotal',
					width : 50
				}, {
					display : '��˱�ע',
					name : 'managerAuditRemark',
					type : 'txt',
					align : 'left',
					width : 120,
					process : function(v,row){
						var remarkStr='';
						if(row.managerAuditRemark!=""){
							remarkStr+=row.managerName+":<br/>"+row.managerAuditRemark+"<br/>";
						}
						if(row.serverAuditRemark!=''){
							remarkStr+=row.serverManagerName+":<br/>"+row.serverAuditRemark+"<br/>";
						}
						if(row.areaAuditRemark!=""){
							remarkStr+=row.areaManager+":<br/>"+row.areaAuditRemark;
						}
						return remarkStr;
					}
				}]
	});

 });

