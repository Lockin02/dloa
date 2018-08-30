
$(document).ready(function() {

	$("#wholeListInfo").yxeditgrid({
		objName : 'workVerify[wholeList]',
		url : '?model=outsourcing_workverify_verifyDetail&action=listJson',
		param : {
			suppVerifyId :$("#id").val(),
			outsourcingArr :'HTWBFS-01,HTWBFS-03'
		},
		type : 'view',
		dir : 'ASC',
		colModel : [ {
					display : '区域',
					name : 'officeName',
					type : 'txt',
					width :50
				},    {
					display : '省份',
					name : 'province',
					width :50
				}, {
					display : '类型',
					name : 'outsourcingName',
					width :50
				},{
					display : '项目名称',
					name : 'projectName',
					width : 120
				},  {
					display : '项目ID',
					name : 'projectId',
					type : 'txt',
					type:'hidden'
				},{
					display : '项目编号',
					name : 'projectCode',
					type : 'txt',
					width : 100
				},{
					display : '外包合同号',
					name : 'outsourceContractCode',
					type : 'txt',
					width : 80
				},  {
					display : '外包合同ID',
					name : 'outsourceContractId',
					type : 'txt',
					type:'hidden'
				},{
					display : '负责人',
					name : 'principal',
					type : 'txt',
					width : 50
				},{
					display : '总进度',
					name : 'scheduleTotal',
					type : 'txt',
					width : 50
				},{
					display : '本期进度',
					name : 'presentSchedule',
					width : 50
				},{
					display : '项目经理审批',
					name : 'managerAuditState',
					width : 50,
					process : function(v) {
						if (v == "1") {
							return "<span style='color:blue'>√</span>";
						} else {
							return "<span style='color:red'>-</span>";
						}
					}
				},{
					display : '服务经理审批',
					name : 'serverAuditState',
					width : 50,
					process : function(v) {
						if (v == "1") {
							return "<span style='color:blue'>√</span>";
						} else {
							return "<span style='color:red'>-</span>";
						}
					}
				},{
					display : '服务总监审批',
					name : 'areaAuditState',
					width : 50,
					process : function(v) {
						if (v == "1") {
							return "<span style='color:blue'>√</span>";
						} else {
							return "<span style='color:red'>-</span>";
						}
					}
				}]
	});

	$("#rentListInfo").yxeditgrid({
		objName : 'workVerify[rentList]',
		url : '?model=outsourcing_workverify_verifyDetail&action=listJson',
		param : {
			suppVerifyId :$("#id").val(),
			outsourcingArr :'HTWBFS-02'
		},
		type : 'view',
		dir : 'ASC',
		colModel : [ {
					display : '区域',
					name : 'officeName',
					type : 'txt',
					width :50
				},   {
					display : '省份',
					name : 'province',
					width :50
				}, {
					display : '项目名称',
					name : 'projectName',
					type : 'txt',
					width : 120
				},  {
					display : '项目编号',
					name : 'projectCode',
					type : 'txt',
					width : 100
				},{
					display : '外包合同号',
					name : 'outsourceContractCode',
					type : 'txt',
					width : 80
				},  {
					display : '负责人',
					name : 'principal',
					type : 'txt',
					width : 50
				},{
					display : '姓名',
					name : 'userName',
					type : 'txt',
					width :50,
					readonly:true,
					process : function(v,row){
							return "<a href='#' onclick='showModalWin(\"?model=engineering_worklog_esmworklog&action=getDetailList&projectId=" + row.projectId +"&createId="+row.userId+"&beginDate="+row.beginDate+"&endDate="+row.endDate+"\",\"1\",\"人员日志\")'>" + v + "</a>";
					}
				},{
					display : '本期开始',
					name : 'beginDate',
					width : 70
				},{
					display : '本期结束',
					name : 'endDate',
					width : 70
				},{
					display : '计价天数',
					name : 'feeDay',
					width : 50
				},{
					display : 'PM核对本期开始',
					name : 'beginDatePM',
					width : 70
				},{
					display : 'PM核对本期结束',
					name : 'endDatePM',
					width : 70
				},{
					display : 'PM核对计价天数',
					name : 'feeDayPM',
					width : 50
				},{
					display : '项目经理审批',
					name : 'managerAuditState',
					width : 50,
					process : function(v ,row) {
						if (v == "1") {
							return "<span style='color:blue'>√</span><br>" + row.managerName
									+ "<br>" + row.managerAuditDate;
						} else {
							return "<span style='color:red'>-</span>" + row.managerName
									+ "<br>" + row.managerAuditDate;
						}
					}
				},{
					display : '服务经理审批',
					name : 'serverAuditState',
					width : 50,
					process : function(v ,row) {
						if (v == "1") {
							return "<span style='color:blue'>√</span><br>" + row.serverManagerName
									+ "<br>" + row.serverAuditDate;
						} else {
							return "<span style='color:red'>-</span>" + row.serverManagerName
									+ "<br>" + row.serverAuditDate;
						}
					}
				},{
					display : '服务总监审批',
					name : 'areaAuditState',
					width : 50,
					process : function(v ,row) {
						if (v == "1") {
							return "<span style='color:blue'>√</span><br>" + row.areaManager
									+ "<br>" + row.areaAuditDate;
						} else {
							return "<span style='color:red'>-</span>" + row.areaManager
									+ "<br>" + row.areaAuditDate;
						}
					}
				}, {
					display : '审核备注',
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

