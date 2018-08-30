//缓存列表
var objGrid;
$(document).ready(function() {

	objGrid = $("#verifyListGrid");
	//初始化列表
	initWorklog();

 });

 //初始化待审核列表
function initWorklog(thisType,thisVal){
	//如果改变和查询方式，列表
	var auditObj = $("#auditType");
	if(thisType){
		if(thisVal == auditObj.val()){
			return false;
		}else{
			auditObj.val(thisVal);
			objGrid.empty();
		}
	}

	var paramObj = {
		managerAuditState : '1',
		serverAuditState:'0',
		suppState : '1',
		provinceArr : $("#provinceStr").val()
	};
	if(auditObj.val() == "0"){
		//常规审核
		auditNormalList(paramObj);
	}else{
		//按项目统计审核
		auditForPersonList(paramObj);
	}
}

 //常规审核列表
function auditNormalList(paramObj){
	if(objGrid.children().length != 0){
		objGrid.yxeditgrid("setParam",paramObj).yxeditgrid("processData");
	}else{
	$("#verifyListGrid").yxeditgrid({
		objName : 'workVerify[rentList]',
		url : '?model=outsourcing_workverify_verifyDetail&action=listJson',
		param : {'managerAuditState':'1','serverAuditState':'0','provinceArr':$("#provinceStr").val()},
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
					process : function(v,row) {
						if (v == "1") {
							return "<span style='color:blue'>√("+row.managerName+")</span>";
						} else {
							return "<span style='color:red'>-</span>";
						}
					}
				}, {
					display : '项目经理审核意见',
					name : 'managerAuditRemark',
					type : 'txt',
					align : 'left',
					width : 120
				},{
					display : '服务总监审核意见',
					name : 'areaAuditRemark',
					type : 'txt',
					align : 'left',
					width : 120
				},{
					display : '<a href="javascript:void(0);" onclick="auditBatchSet(1);">确认</a>',
					name : 'serverAuditStateName',
					width : 40,
					process : function(v,row){
						if(row.serverAuditState == 1){
								return "<span class='blue'>√</span>";
						}else{
							return '-';
						}
					},
					event : {
						click : function(){
							//拿行号
							var rowNum = $(this).data("rowNum");
							var serverAuditState=objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,'serverAuditState').val();
							//审核操作
							if(serverAuditState==0){
								auditAction(rowNum,'1');

							}else if(serverAuditState==1){
								auditAction(rowNum,'0');
							}
						}
					}
				}, {
					display : '审核意见',
					name : 'serverAuditRemark',
					align : 'left',
					process : function(v,row){
							return '<input type="text" class="txtmiddle" style="width:98%" id="serverAuditRemark'+ row.id +'"/>';
					}
				},{
					display : '打回',
					width : 50,
					process : function(v,row){
							return '<a href="javascript:void(0);" style="color:red;">打回</a>';
					},
					event : {
						click : function(){
							//拿行号
							var rowNum = $(this).data("rowNum");
							backAction(rowNum);
						}
					}
				},{
					display : 'serverAuditState',
					name : 'serverAuditState',
					type : 'hidden'
				}]
	});}
}

//按项目统计
function auditForPersonList(paramObj){
	if(objGrid.children().length == 0){
		//获取统计的数据
		var tbStr ='<table class="main_table">' +
				'<thead>' +
					'<tr class="main_tr_header">' +
						'<th width="5%">序号</th>' +
						'<th width="5%"></th>' +
						'<th width="20%">确认周期</th>' +
						'<th width="10%">区域</th>' +
						'<th width="10%">省份</th>' +
						'<th width="20%">项目名称</th>' +
						'<th width="20%">项目编号</th>' +
						'<th width="10%"><a href="javascript:void(0);" onclick="auditBatchSet(1);">确认</a></th>' +
					'</tr>' +
				'</thead>' +
				'<tbody id="myTbody"></tbody>' +
			'</table>';

		//表头载入
		objGrid.append(tbStr);
	}

	//数据加载
	reloadPersonData(paramObj);
}

//加载列表数据
function reloadPersonData(paramObj){
	//获取任务并且渲染
	var logArr;
	$.ajax({
		type : 'POST',
		url : "?model=outsourcing_workverify_verifyDetail&action=auditJsonForManager",
		data : paramObj,
	    async: false,
		success : function(data) {
			logArr = eval("(" + data + ")");
		}
	});

	if(logArr.length > 0){
		var tbodyStr = "";
		var j,trClass;
		for(var i = 0;i< logArr.length ; ++i){
			j = i + 1;
			trClass = i%2 == 0 ? 'tr_odd' : 'tr_even';
			tbodyStr += '<tr class="'+trClass+'" id="tr'+ i +'">' +
					'<td>'+ j +'</td>' +
					'<td><a href="javascript:void(0)" onclick="changeShow(this,' + i + ',\''+ logArr[i].parentId +'\',\''+ logArr[i].projectId +'\');">+</a></td>' +
					'<td>' + logArr[i].parentBeginDate + '~' + logArr[i].parentEndDate + '</td>' +
					'<td>' + logArr[i].officeName + '</td>' +
					'<td>' + logArr[i].province + '</td>' +
					'<td>' + logArr[i].projectName + '</td>' +
					'<td>' + logArr[i].projectCode +  '' +
						'<input type="hidden" id="parentId'+ i +'" value="'+ logArr[i].parentId +'"/>' +
						'<input type="hidden" id="projectId'+ i +'" value="'+ logArr[i].projectId +'"/>' +
						'<input type="hidden" id="serverAuditState'+ i +'" value="0"/>' +
						'<input type="hidden" id="assessResult'+ i +'" value="0"/>' +
					'</td>' +
					'<td id="serverAuditStateName'+ i +'" onclick="auditAction('+ i +',1);"> - </td>' +
				'</tr>';
		}
		//数据载入
		$("#myTbody").empty().append(tbodyStr);
	}else{
		$("#myTbody").empty();
	}
}

//更换显示内容
function changeShow(thisObj,rowNum,parentId,projectId){
	thisObj = $(thisObj);
	if(thisObj.text() == '+'){
		thisObj.text('-');
		loadDetail(rowNum,parentId,projectId);
	}else{
		thisObj.text('+');
		$("#tb_" + rowNum).hide();
	}
}

//读取明细表
function loadDetail(rowNum,parentId,projectId){
	var tbObj = $("#tb_" + rowNum);
	if(tbObj.length == 1){
		tbObj.show();
		return false;
	}
	//参数判断
	var paramObj = {
		projectId : projectId,
		parentId : parentId,
		managerAuditState : '1',
		serverAuditState : '0',
		suppState : '1'
	};

	//获取任务并且渲染
	var logArr;
	$.ajax({
		type : 'POST',
		url : "?model=outsourcing_workverify_verifyDetail&action=listJson",
		data : paramObj,
	    async: false,
		success : function(data) {
			logArr = eval("(" + data + ")");
		}
	});

	if(logArr.length > 0){
		//获取统计的数据
		var tbStr ='<tr id="tb_'+ rowNum +'"><td></td><td colspan="99"><table class="main_table">' +
				'<thead>' +
					'<tr class="main_tr_header">' +
						'<th width="3%">序号</th>' +
						'<th width="5%">类型</th>' +
						'<th width="8%">外包合同号</th>' +
						'<th width="8%">外包公司</th>' +
						'<th width="7%">负责人</th>' +
						'<th width="7%">总进度</th>' +
						'<th width="7%">本期进度</th>' +
						'<th width="5%">姓名</th>' +
						'<th width="8%">本期开始</th>' +
						'<th width="8%">本期结束</th>' +
						'<th  width="5%">计价天数</th>' +
						'<th width="8%">PM核对本期开始</th>' +
						'<th width="8%">PM核对本期结束</th>' +
						'<th  width="5%">PM核对计价天数</th>' +
						'<th  width="5%">项目经理审核</th>' +
						'<th>项目经理审核意见</th>' +
					'</tr>' +
				'</thead>' +
				'<tbody>';
		var j,trClass;
		for(var i = 0;i< logArr.length ; ++i){
			j = i + 1;
			trClass = i%2 == 0 ? 'tr_odd' : 'tr_even';
			tbStr += '<tr class="'+trClass+'">' +
					'<td>'+ j +'</td>' +
					'<td>' + logArr[i].outsourcingName + '</td>' +
					'<td>' + logArr[i].outsourceContractCode + '</td>' +
					'<td>' + logArr[i].outsourceSupp + '</td>' +
					'<td>' + logArr[i].principal + '</td>' +
					'<td>' + logArr[i].scheduleTotal + '</td>' +
					'<td>' + logArr[i].presentSchedule + '</td>' +
					'<td> <a href="#" onclick="showModalWin(\'?model=engineering_worklog_esmworklog&action=getDetailList&projectId=' + logArr[i].projectId +'&createId='+logArr[i].userId+'&beginDate='+logArr[i].beginDate+'&endDate='+logArr[i].endDate+'\',\'1\',\'人员日志\')">'+ logArr[i].userName + '</a></td>' +
					'<td>' + logArr[i].beginDate + '</td>' +
					'<td>' + logArr[i].endDate + '</td>' +
					'<td>' + logArr[i].feeDay + '</td>' +
					'<td>' + logArr[i].beginDatePM + '</td>' +
					'<td>' + logArr[i].endDatePM + '</td>' +
					'<td>' + logArr[i].feeDayPM + '</td>' +
					'<td>√('+ logArr[i].managerName +')</td>' +
					'<td style="text-align:left;">' + logArr[i].managerAuditRemark + '</td>' +
				'</tr>';
		}

		tbStr += '</tbody></table></td></tr>';

		//表头载入
		$("#tr" + rowNum).after(tbStr);
	}
}

 //批量设置审核结果
function auditBatchSet(assessResult){
	//如果改变和查询方式，列表
	var auditObj = $("#auditType");
	if(auditObj.val() == "0"){
		var rows = objGrid.yxeditgrid("getCmpByCol","serverAuditState");
		if(rows.length > 0){
			rows.each(function(){
					//审核操作
					auditAction($(this).data('rowNum'),assessResult);
			});
		}
	}else{
		var rows = $("tr[id^='tr']");
		if(rows.length){
			rows.each(function(i,n){
				//审核操作
				auditAction(i,assessResult);
			});
		}
	}
}

//审核
function auditAction(rowNum,assessResult){
	//调用审核排斥方法
	auditExclude(rowNum,assessResult);
}

//审核排斥调用方法
function auditExclude(rowNum,assessResult){
	//如果改变和查询方式，列表
	var auditObj = $("#auditType");
	if(auditObj.val() == "0"){
		if(1 == assessResult*1){
			objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,'serverAuditStateName').html("<span class='blue'>√</span>");
			objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,'serverAuditState').val(assessResult);
		}else{
			objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,'serverAuditStateName').html('-');
			objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,'serverAuditState').val(assessResult);
		}
	}else{
			if(1 == assessResult*1){
				$("#assessResult" + rowNum).val(1);
				$("#serverAuditState" + rowNum).val(assessResult);
				$("#" +"serverAuditStateName" + rowNum).html("<span class='blue'>√</span>");
			}else{
				$("#" + "serverAuditStateName"+ rowNum).html('-');
			}

	}
}
//批量审核
function auditBatch(){
	//如果改变和查询方式，列表
	var auditObj = $("#auditType");
	var isNull = true;

	//判断审核方式
	if(auditObj.val() == "0"){
		var objArr = objGrid.yxeditgrid("getCmpByCol", "serverAuditState");

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
					var serverAuditState =  objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,'serverAuditState').val();
					//审核
					auditLog(id,serverAuditState);
				}
			});
		}
	}else{
		var objArr = $("input[id^='serverAuditState']");
		if(objArr.length != 0){
			if(!confirm('确认要审核已选中的记录吗？')){
				return false;
			}
			objArr.each(function(i,n){
				if(this.value == "1"){
					isNull = false;

					//根据行数获取相应值
					var serverAuditState = $("#serverAuditState" + i).val();
					var projectId = $("#projectId" + i).val();
					var parentId = $("#parentId" + i).val();
					//审核
					auditLogForManager(projectId,parentId,serverAuditState);
				}
			});
		}
	}

	if(isNull == true){
		alert('没有需要确认审核的记录');
	}else{
		//重新加载列表
		show_page();
	}
}

//审核工作量
function auditLog(id,serverAuditState){
	var serverAuditRemark = $("#serverAuditRemark" + id).val();
	//获取任务并且渲染
	$.ajax({
		type : 'POST',
		url : "?model=outsourcing_workverify_verifyDetail&action=serverAudit",
		data : {
			id : id,
			serverAuditState : serverAuditState,
			serverAuditRemark : serverAuditRemark
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
	}else{
		var paramObj = {
			managerAuditState : '1',
			serverAuditState : '0',
			suppState : '1',
			provinceArr : $("#provinceStr").val()
		};
		//数据加载
		reloadPersonData(paramObj);}
}

//按项目审核
function auditLogForManager(projectId,parentId,serverAuditState){
	//参数判断
	var paramObj = {
		projectId : projectId,
		parentId : parentId,
		managerAuditState : '1'
	};

	//获取任务并且渲染
	$.ajax({
		type : 'POST',
		url : "?model=outsourcing_workverify_verifyDetail&action=auditAllForSever",
		data : paramObj,
	    async: false,
		success : function(data) {
			if(data == '1'){

			}else{
				alert('审核失败');
			}
		}
	});
}

//打回操作
function backAction(rowNum){
	var id = objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,"id").val();
	var feeDay = objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,"feeDay").val();
	var serverAuditRemark = $("#serverAuditRemark" + id).val();
	if(serverAuditRemark==''){
		alert("请填写审核意见");
		return false;
	}
	if(confirm('确认打回？')){
		//打回
		$.ajax({
			type : 'POST',
			url : "?model=outsourcing_workverify_verifyDetail&action=serverAuditBack",
			data : {
				id : id,
				managerAuditState : '0',
				serverAuditRemark : serverAuditRemark
			},
		    async: false,
			success : function(data) {
				if(data == '1'){
					//重新加载列表
					show_page();
				}else{
					alert('打回失败');
				}
			}
		});
	}
}
