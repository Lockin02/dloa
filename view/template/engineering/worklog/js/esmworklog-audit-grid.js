//任务数组
var activityArr;
//人员数组
var memberArr;
//缓存列表
var objGrid;
//员工属性数组
var personTypeArr;

$(function() {
	var projectId = $("#projectId").val();
	//获取任务并且渲染
	$.ajax({
		type : 'POST',
		url : "?model=engineering_activity_esmactivity&action=listJson",
		data : {
			projectId : projectId,
			isLeaf : 1,
			dir : 'ASC'
		},
	    async: false,
		success : function(data) {
			activityArr = eval("(" + data + ")");
		}
	});

	//如果存在任务
	if(activityArr){
		var optionStr = '';
		var activityId = $("#activityIdHidden").val();
		for(var i = 0;i < activityArr.length ; i++){
			if(activityId == activityArr[i].id){
				optionStr += "<option value='"+ activityArr[i].id  +"' selected='selected'>" + activityArr[i].activityName + "</option>";
			}else
				optionStr += "<option value='"+ activityArr[i].id  +"'>" + activityArr[i].activityName + "</option>";
		}
		$("#activityId").append(optionStr);
	}

	//获取项目成员
	$.ajax({
		type : 'POST',
		url : "?model=engineering_member_esmmember&action=listJson",
		data : {
			projectId : projectId,
			dir : 'ASC'
		},
	    async: false,
		success : function(data) {
			memberArr = eval("(" + data + ")");
		}
	});

	//如果存在任务
	if(memberArr){
		var optionStr = '';
		var memberIdArr = [];
		for(var i = 0;i < memberArr.length ; i++){
			optionStr += "<option value='"+ memberArr[i].memberId  +"'>" + memberArr[i].memberName + "</option>";
			memberIdArr.push(memberArr[i].memberId);
		}
		$("#memberId").append(optionStr);
		$("#memberIds").val(memberIdArr.toString());
	}

	objGrid = $("#esmworklogGrid");

	//初始化日志
	initWorklog();
});

//初始化待审核日志
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

	//参数判断
	var projectId = $("#projectId").val();
	var assessResult = $("#assessResult").val();
	var activityId = $("#activityId").val();
	var memberId = $("#memberId").val();
	var personType = $("#personType").val();
	var memberIds = $("#memberIds").val();
	var pageSize = $("#pageSize").val();
	var paramObj = {
		projectId : projectId,
		activityId : activityId,
		assessResults : assessResult,
		createId : memberId,
		personType : personType,
		memberIdsSearch : memberIds,
		pageSize : pageSize
	};
	if(auditObj.val() == "0"){
		//显示审核状态
		$("#assessResult").show();
		$("#assessResultText").show();
		//常规审核
		auditNormalList(paramObj);
	}else{
		//隐藏审核状态
		$("#assessResult").hide();
		$("#assessResultText").hide();
		//按成员统计审核
		auditForPersonList(paramObj);
	}
}

//常规审核列表
function auditNormalList(paramObj){
	if(objGrid.children().length != 0){
		//工作日志
		objGrid.yxeditgrid("setParam",paramObj).yxeditgrid("processData");
	}else{
		//工作日志
		$("#esmworklogGrid").yxeditgrid({
			url : '?model=engineering_worklog_esmworklog&action=pageJsonWorkLog',
			type : 'view',
			param : paramObj,
			colModel : [{
					display : 'id',
					name : 'id',
					type : 'hidden'
				},{
					display : '任务名称',
					name : 'activityName',
					width : 120,
					align : 'left'
				},{
					display : '执行日期',
					name : 'executionDate',
					width : 70,
					process : function(v,row){
						return "<a href='javascript:void(0);' onclick='viewCost("+ row.id +")'>" + v + "</a>";
					}
				}, {
					display : '填报人',
					name : 'createName',
					width : 80
				}, {
					display : '工作量',
					name : 'workloadDay',
					width : 60,
					process : function(v,row){
						return v + " " + row.workloadUnitName;
					},
					align : 'right'
				}, {
					display : '单位',
					name : 'workloadUnitName',
					width : 40,
					type : 'hidden'
				}, {
					display : '任务进展',
					name : 'thisActivityProcess',
					width : 60,
					process : function(v){
						return v + " %";
					},
					align : 'right'
				}, {
					display : '项目进展',
					name : 'thisProjectProcess',
					width : 60,
					process : function(v){
						return v + " %";
					},
					align : 'right'
				}, {
					display : '人工投入',
					name : 'inWorkRate',
					width : 70,
					process : function(v){
						return v + " %";
					},
					align : 'right'
				}, {
					display : '费用',
					name : 'costMoney',
					width : 60,
					process : function(v,row){
						if(v * 1 == 0 || v == ''){
							return v;
						}else{
							return "<span class='blue'>" + moneyFormat2(v) + "</span>";
						}
					},
					align : 'right'
				}, {
					display : '工作状态',
					width : 50,
					datacode : 'GXRYZT',
					name : 'workStatus'
				}, {
					display : '工作描述',
					name : 'description',
					align : 'left'
				}, {
					display : '<a href="javascript:void(0);" onclick="auditBatchSet(1);">优</a>',
					name : 'excellent',
					width : 40,
					process : function(v,row){
						return showAudit('1',row);
					},
					event : {
						click : function(){
							//拿行号
							var rowNum = $(this).data("rowNum");
							//审核操作
							auditAction(rowNum,'1');
						}
					}
				}, {
					display : '<a href="javascript:void(0);" onclick="auditBatchSet(2);">良</a>',
					name : 'good',
					width : 40,
					process : function(v,row){
						return showAudit('2',row);
					},
					event : {
						click : function(){
							//拿行号
							var rowNum = $(this).data("rowNum");
							//审核操作
							auditAction(rowNum,'2');
						}
					}
				}, {
					display : '<a href="javascript:void(0);" onclick="auditBatchSet(3);">中</a>',
					name : 'medium',
					width : 40,
					process : function(v,row){
						return showAudit('3',row);
					},
					event : {
						click : function(){
							//拿行号
							var rowNum = $(this).data("rowNum");
							//审核操作
							auditAction(rowNum,'3');
						}
					}
				}, {
					display : '<a href="javascript:void(0);" onclick="auditBatchSet(4);">差</a>',
					name : 'poor',
					width : 40,
					process : function(v,row){
						return showAudit('4',row);
					},
					event : {
						click : function(){
							//拿行号
							var rowNum = $(this).data("rowNum");
							//审核操作
							auditAction(rowNum,'4');
						}
					}
				}, {
					display : '<a href="javascript:void(0);" onclick="auditBatchSet(5);">打回</a>',
					name : 'back',
					width : 40,
					process : function(v,row){
						return showAudit('5',row);
					},
					event : {
						click : function(){
							//拿行号
							var rowNum = $(this).data("rowNum");
							//审核操作
							auditAction(rowNum,'5');
						}
					}
				},{
					display : '审核建议',
					name : 'feedBack',
					align : 'left',
                    width : 150,
					process : function(v,row){
						if(row.assessResultName != ""){
							return v;
						}else{
							return '<input type="text" class="txtmiddle" style="width:98%" id="feedBack'+ row.id +'"/>';
						}
					}
				}, {
					display : 'assessResult',
					name : 'assessResult',
					type : 'hidden'
				},{
					display : 'assessResultName',
					name : 'assessResultName',
					type : 'hidden'
				},{
					display : 'isAudit',
					name : 'isAudit',
					type : 'hidden'
				},{
					display : 'confirmId',
					name : 'confirmId',
					type : 'hidden'
				}
			]
		});
	}
}

//按成员统计
function auditForPersonList(paramObj){
	if(objGrid.children().length == 0){
		//获取统计的数据
		var tbStr ='<table class="main_table">' +
				'<thead>' +
					'<tr class="main_tr_header">' +
						'<th width="3%">序号</th>' +
						'<th width="3%"></th>' +
						'<th>填报期间</th>' +
						'<th width="6%">填报次数</th>' +
						'<th width="12%">任务名称</th>' +
						'<th width="7%">填报人</th>' +
						'<th width="5%">工作量</th>' +
						'<th width="5%">单位</th>' +
						'<th width="5%">任务进展</th>' +
						'<th width="5%">项目进展</th>' +
						'<th width="5%">费用</th>' +
						'<th width="7%">人工投入占比</th>' +
						'<th width="4%"><a href="javascript:void(0);" onclick="auditBatchSet(1);">优</a></th>' +
						'<th width="4%"><a href="javascript:void(0);" onclick="auditBatchSet(2);">良</a></th>' +
						'<th width="4%"><a href="javascript:void(0);" onclick="auditBatchSet(3);">中</a></th>' +
						'<th width="4%"><a href="javascript:void(0);" onclick="auditBatchSet(4);">差</a></th>' +
						'<th width="4%"><a href="javascript:void(0);" onclick="auditBatchSet(5);">打回</a></th>' +
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
		url : "?model=engineering_worklog_esmworklog&action=auditJsonForPerson",
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
					'<td><a href="javascript:void(0)" onclick="changeShow(this,' + i + ',\''+ logArr[i].createId +'\',\''+ logArr[i].activityId +'\');">+</a></td>' +
					'<td>' + logArr[i].minDate + '~' + logArr[i].maxDate + '</td>' +
					'<td>' + logArr[i].dataNum + '</td>' +
					'<td>' + logArr[i].activityName + '</td>' +
					'<td>' + logArr[i].createName + '</td>' +
					'<td>' + logArr[i].workloadDay + '</td>' +
					'<td>' + logArr[i].workloadUnitName + '</td>' +
					'<td>' + logArr[i].thisActivityProcess + ' %</td>' +
					'<td>' + logArr[i].thisProjectProcess + ' %</td>' +
					'<td>' + moneyFormat2(logArr[i].costMoney) + ' </td>' +
					'<td>' + logArr[i].inWorkRateProcess + ' %</td>' +
						'<input type="hidden" id="createId'+ i +'" value="'+ logArr[i].createId +'"/>' +
						'<input type="hidden" id="activityId'+ i +'" value="'+ logArr[i].activityId +'"/>' +
						'<input type="hidden" id="isAudit'+ i +'" value="0"/>' +
						'<input type="hidden" id="assessResult'+ i +'" value="0"/>' +
					'</td>' +
					'<td id="excellent'+ i +'" onclick="auditAction('+ i +',1);"> - </td>' +
					'<td id="good'+ i +'" onclick="auditAction('+ i +',2);"> - </td>' +
					'<td id="medium'+ i +'" onclick="auditAction('+ i +',3);"> - </td>' +
					'<td id="poor'+ i +'" onclick="auditAction('+ i +',4);"> - </td>' +
					'<td id="back'+ i +'" onclick="auditAction('+ i +',5);"> - </td>' +
				'</tr>';
		}
		//数据载入
		$("#myTbody").empty().append(tbodyStr);
	}else{
		//数据载入
		$("#myTbody").empty();
	}
}

//更换显示内容
function changeShow(thisObj,rowNum,createId,activityId){
	thisObj = $(thisObj);
	if(thisObj.text() == '+'){
		thisObj.text('-');
		loadDetail(rowNum,createId,activityId);
	}else{
		thisObj.text('+');
		$("#tb_" + rowNum).hide();
	}
}

//读取明细表
function loadDetail(rowNum,createId,activityId){
	var tbObj = $("#tb_" + rowNum);
	if(tbObj.length == 1){
		tbObj.show();
		return false;
	}
	//参数判断
	var projectId = $("#projectId").val();
	var assessResult = $("#assessResult").val();
	var personType = $("#personType").val();
	var memberIds = $("#memberIds").val();
	var paramObj = {
		projectId : projectId,
		activityId : activityId,
		assessResults : assessResult,
		createId : createId,
		personType : personType,
		memberIdsSearch : memberIds
	};

	//获取任务并且渲染
	var logArr;
	$.ajax({
		type : 'POST',
		url : "?model=engineering_worklog_esmworklog&action=listJson",
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
						'<th width="12%">填报日期</th>' +
						'<th width="8%">工作状态</th>' +
						'<th width="8%">工作量</th>' +
						'<th width="8%">单位</th>' +
						'<th width="8%">任务进展</th>' +
						'<th width="8%">项目进展</th>' +
						'<th width="8%">费用</th>' +
						'<th width="8%">人工投入占比</th>' +
						'<th>工作内容</th>' +
					'</tr>' +
				'</thead>' +
				'<tbody>';
		var j,trClass;
		for(var i = 0;i< logArr.length ; ++i){
			j = i + 1;
			trClass = i%2 == 0 ? 'tr_odd' : 'tr_even';
			tbStr += '<tr class="'+trClass+'">' +
					'<td>'+ j +'</td>' +
					'<td><a href="javascript:void(0);" onclick="viewCost(' + logArr[i].id + ')">' + logArr[i].executionDate + '</a></td>' +
					'<td>' + logArr[i].workStatus + ' </td>' +
					'<td>' + logArr[i].workloadDay + '</td>' +
					'<td>' + logArr[i].workloadUnitName + '</td>' +
					'<td>' + logArr[i].thisActivityProcess + ' %</td>' +
					'<td>' + logArr[i].thisProjectProcess + ' %</td>' +
					'<td>' + moneyFormat2(logArr[i].costMoney) + ' </td>' +
					'<td>' + logArr[i].inWorkRate + ' %</td>' +
					'<td style="text-align:left;">' + logArr[i].description + '</td>' +
				'</tr>';
		}
		tbStr += '</tbody></table></td></tr>';
		//表头载入
		$("#tr" + rowNum).after(tbStr);
	}
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
		//参数判断
		var projectId = $("#projectId").val();
		var assessResult = $("#assessResult").val();
		var activityId = $("#activityId").val();
		var memberId = $("#memberId").val();
		var personType = $("#personType").val();
		var memberIds = $("#memberIds").val();
		var pageSize = $("#pageSize").val();
		var paramObj = {
			projectId : projectId,
			activityId : activityId,
			assessResults : assessResult,
			createId : memberId,
			personType : personType,
			memberIdsSearch : memberIds,
			pageSize :pageSize
		};
		//数据加载
		reloadPersonData(paramObj);
	}
}

//审核日志
function auditLog(id,assessResult){
	var feedBack = $("#feedBack" + id).val();
	var action = assessResult*1 == 5 ? 'backLog' : 'auditLog';
	//获取任务并且渲染
	$.ajax({
		type : 'POST',
		url : "?model=engineering_worklog_esmworklog&action=" + action,
		data : {
			id : id,
			assessResult : assessResult,
			feedBack : feedBack
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

//显示审核结果
function showAudit(assessResult,row){
	if(row.assessResultName != ""){
		if(row.assessResult == assessResult){
			return "<span class='blue'>√</span>";
		}
	}else{
		return '-';
	}
}

//审核日志事件
function auditAction(rowNum,assessResult){
	var assessResultNow = objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,'assessResult').val();//当前系统选中
	if(assessResultNow != assessResult){
		//调用审核排斥方法
		auditExclude(rowNum,assessResult);
	}else{
		var confirmId = objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,'confirmId').val();//当前系统选中
		if(confirmId == ''){
			//取消选中
			cancelAction(rowNum,assessResult);
		}
	}
}

//取消审核动作
function cancelAction(rowNum,assessResult){
	objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,keyValue(assessResult)).html("-");
	objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,'assessResult').val(0);
	objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,'isAudit').val('');
}

//审核排斥调用方法
function auditExclude(rowNum,assessResult){
	//如果改变和查询方式，列表
	var auditObj = $("#auditType");
	if(auditObj.val() == "0"){
		for(var i = 1;i <= 5 ; i++){
			if(i == assessResult*1){
				objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,keyValue(i)).html("<span class='blue'>√</span>");
				objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,'assessResult').val(assessResult);
				objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,'isAudit').val(1);
			}else{
				objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,keyValue(i)).html('-');
			}
		}
	}else{
		for(var i = 1;i <= 5 ; i++){
			if(i == assessResult*1){
				$("#isAudit" + rowNum).val(1);
				$("#assessResult" + rowNum).val(assessResult);
				$("#" + keyValue(i) + rowNum).html("<span class='blue'>√</span>");
			}else{
				$("#" + keyValue(i) + rowNum).html('-');
			}
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
		var objArr = objGrid.yxeditgrid("getCmpByCol", "isAudit");

		if(objArr.length != 0){
			if(!confirm('确认要审核已选中的日志吗？')){
				return false;
			}
			objArr.each(function(i,n){
				if(this.value == "1"){
					isNull = false;

					//根据行数获取相应值
					var rowNum = $(this).data('rowNum');
					var id =  objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,'id').val();
					var assessResult =  objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,'assessResult').val();
					//审核
					auditLog(id,assessResult);
				}
			});
		}
	}else{
		var objArr = $("input[id^='isAudit']");
		if(objArr.length != 0){
			if(!confirm('确认要审核已选中的日志吗？')){
				return false;
			}
			objArr.each(function(i,n){
				if(this.value == "1"){
					isNull = false;

					//根据行数获取相应值
					var assessResult = $("#assessResult" + i).val();
					var createId = $("#createId" + i).val();
					var activityId = $("#activityId" + i).val();
					//审核
					auditLogForPerson(createId,activityId,assessResult);
				}
			});
		}
	}

	if(isNull == true){
		alert('没有需要确认审核的日志');
	}else{
		//重新加载列表
		show_page();
	}
}

//批量设置审核结果
function auditBatchSet(assessResult){
	//如果改变和查询方式，列表
	var auditObj = $("#auditType");
	if(auditObj.val() == "0"){
		var rows = objGrid.yxeditgrid("getCmpByCol","assessResultName");
		if(rows.length > 0){
			rows.each(function(){
				if(this.value == ""){
					//审核操作
					auditAction($(this).data('rowNum'),assessResult);
				}
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

//审核日志
function auditLogForPerson(createId,activityId,assessResult){
	//参数判断
	var projectId = $("#projectId").val();
	var paramObj = {
		projectId : projectId,
		activityId : activityId,
		assessResults : assessResult,
		createId : createId,
		confirmStatus : 0
	};
	var action = assessResult*1 == 5 ? 'backLogForPerson' : 'auditLogForPerson';
	//获取任务并且渲染
	$.ajax({
		type : 'POST',
		url : "?model=engineering_worklog_esmworklog&action=" + action,
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

//进入查看费用页面
function viewCost(worklogId){
	var url = "?model=engineering_worklog_esmworklog&action=toView&id=" + worklogId;
	var height = 800;
	var width = 1150;
	window.open(url, "查看日志信息",
	'top=0,left=0,menubar=0,toolbar=0,status=1,scrollbars=1,resizable=1,width='
			+ width + ',height=' + height);
}