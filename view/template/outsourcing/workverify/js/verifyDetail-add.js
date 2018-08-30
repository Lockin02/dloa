//缓存列表
var objGrid;
$(document).ready(function() {
	$("#verifyListGrid").yxeditgrid({
		objName : 'workVerify[rentList]',
		url : '?model=outsourcing_workverify_verifyDetail&action=listJson',
		param : {'managerAuditState':'1','serverAuditState':'1','areaAuditState':'0','officeIdArr':$("#officeIds").val()},
		type : 'view',
		dir : 'ASC',
		colModel : [{
					display : 'id',
					name : 'id',
					type : 'hidden'
				}, {
					display : '区域',
					name : 'officeName',
					type : 'txt',
					width :50
				},   {
					display : '省份',
					name : 'province',
					width :50
				}, {
					display : '类型',
					name : 'outsourcingName',
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
				}, {
					display : '外包公司',
					name : 'outsourceSupp',
					type : 'txt',
					width : 80
				},  {
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
				}, {
					display : '审核备注',
					name : 'managerAuditRemark',
					type : 'txt',
					align : 'left',
					width : 120,
					process : function(v,row){
						if(row.managerAuditRemark!=""&&row.serverAuditRemark!=''){
							return row.managerName+":<br/>"+row.managerAuditRemark+"<br/>"+row.serverManagerName+":<br/>"+row.serverAuditRemark;
						}else if(row.managerAuditRemark!=""&&row.serverAuditRemark==''){
							return row.managerName+":<br/>"+row.managerAuditRemark;
						}else if(row.managerAuditRemark==""&&row.serverAuditRemark!=''){
							return row.serverManagerName+":<br/>"+row.serverAuditRemark;
						}else{
							return '';
						}
					}
				},{
					display : '<a href="javascript:void(0);" onclick="auditBatchSet(1);">确认</a>',
					name : 'areaAuditStateName',
					width : 40,
					process : function(v,row){
						if(row.areaAuditState == 1){
								return "<span class='blue'>√</span>";
						}else{
							return '-';
						}
					},
					event : {
						click : function(){
							//拿行号
							var rowNum = $(this).data("rowNum");
							var areaAuditState=objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,'areaAuditState').val();
							//审核操作
							if(areaAuditState==0){
								auditAction(rowNum,'1');

							}else if(areaAuditState==1){
								auditAction(rowNum,'0');
							}
						}
					}
				}, {
					display : '审核意见',
					name : 'areaAuditRemark',
					align : 'left',
					process : function(v,row){
							return '<input type="text" class="txtmiddle" style="width:98%" id="areaAuditRemark'+ row.id +'"/>';
					}
				},{
					display : 'areaAuditState',
					name : 'areaAuditState',
					type : 'hidden'
				}]
	});

	objGrid = $("#verifyListGrid");

 });

 //批量设置审核结果
function auditBatchSet(assessResult){
		var rows = objGrid.yxeditgrid("getCmpByCol","areaAuditState");
		if(rows.length > 0){
			rows.each(function(){
					//审核操作
					auditAction($(this).data('rowNum'),assessResult);
			});
		}
}

//审核
function auditAction(rowNum,assessResult){
	//调用审核排斥方法
	auditExclude(rowNum,assessResult);
}

//审核排斥调用方法
function auditExclude(rowNum,assessResult){
		if(1 == assessResult*1){
			objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,'areaAuditStateName').html("<span class='blue'>√</span>");
			objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,'areaAuditState').val(assessResult);
		}else{
			objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,'areaAuditStateName').html('-');
			objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,'areaAuditState').val(assessResult);
		}
	}
//批量审核
function auditBatch(){
	//如果改变和查询方式，列表
	var auditObj = $("#auditType");
	var isNull = true;

	//判断审核方式
	if(auditObj.val() == "0"){
		var objArr = objGrid.yxeditgrid("getCmpByCol", "areaAuditState");

		if(objArr.length != 0){
			if(!confirm('确认要审核已选中的记录？')){
				return false;
			}
			objArr.each(function(i,n){
				if(this.value == "1"){
					isNull = false;

					//根据行数获取相应值
					var rowNum = $(this).data('rowNum');
					var id =  objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,'id').val();
					var areaAuditState =  objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,'areaAuditState').val();
					//审核
					auditLog(id,areaAuditState);
				}
			});
		}
	}else{
	}

	if(isNull == true){
		alert('没有需要确认审核的记录');
	}else{
		//重新加载列表
		show_page();
	}
}

//审核工作量
function auditLog(id,areaAuditState){
	var areaAuditRemark = $("#areaAuditRemark" + id).val();
	//获取任务并且渲染
	$.ajax({
		type : 'POST',
		url : "?model=outsourcing_workverify_verifyDetail&action=areaAudit",
		data : {
			id : id,
			areaAuditState : areaAuditState,
			areaAuditRemark : areaAuditRemark
		},
	    async: false,
		success : function(data) {
			if(data == '1'){

			}else{
				alert('审核失败');
			}
		}
	});
}

/**
 * 刷新列表
 */
function show_page(){
	//如果改变和查询方式，列表
	var auditObj = $("#auditType");
	if(auditObj.val() == "0"){
		objGrid.yxeditgrid('processData');
	}else{}
}
