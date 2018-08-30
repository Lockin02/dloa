$(document).ready(function() {
	$("#afterPositionName").yxcombogrid_jobs({
		hiddenId : 'afterPositionId',
		width : 280,
		gridOptions : {
			showcheckbox : false,
			param : {"deptId" : $("#deptId").val()}
		}
	});

	//如果是管理层转正，则隐藏工资和职位确认
	if($("#planstatus").val() == "1") {
		$("#salaryInfo").hide();
	}

	$("#summaryTable").yxeditgrid({
		objName : 'examine[summaryTable]',
		isAddOneRow : true,
		url : '?model=hr_permanent_linkcontent&action=listJson',
		type : 'view',
		param : {
			parentId : $("#parentId").val(),
			ownType : 1
		},
		colModel : [{
			display : '工作要点概述',
			name : 'workPoint'
		},{
			display : '输出成果',
			name : 'outPoint'
		},{
			display : '完成时间节点',
			name : 'finishTime'
		},{
			name : 'ownType',
			type : 'hidden',
			value : 1
		},{
			type : 'hidden',
			display : 'id',
			name : 'id'
		}]
	});

	$("#planTable").yxeditgrid({
		objName : 'examine[planTable]',
		url : '?model=hr_permanent_linkcontent&action=listJson',
		type : 'view',
		param : {
			parentId : $("#parentId").val(),
			ownType : 2
		},
		isAddOneRow : true,
		colModel : [{
			display : '工作要点概述',
			name : 'workPoint'
		},{
			display : '输出成果或检验标准',
			name : 'outPoint'
		},{
			display : '完成时间节点',
			name : 'finishTime'
		},{
			name : 'ownType',
			type : 'hidden',
			value : 2
		},{
			type : 'hidden',
			display : 'id',
			name : 'id'
		}]
	});

	$("#schemeTable").yxeditgrid({
		objName : 'examine[schemeTable]',
		url : '?model=hr_permanent_detail&action=listJson',
		param : {
			parentId : $("#parentId").val()
		},

		isAddOneRow : true,
		isAddAndDel : false,
		colModel : [{
			name : 'standardId',
			type : 'hidden'
		},{
			display : '考核项目',
			name : 'standard',
			type : 'hidden'
		},{
			display : '考核项目',
			name : 'standard',
			type : 'statictext'
		},{
			display : '考核权重',
			name : 'standardProportion',
			type : 'hidden'
		},{
			display : '考核要点',
			name : 'standardPoint',
			type : 'hidden'
		},{
			display : '考核内容',
			name : 'standardContent',
			type : 'hidden'
		},{
			display : '考核要点',
			name : 'standardPoint',
			type : 'statictext',
			align:'left'
		},{
			display : '考核内容',
			name : 'standardContent',
			type : 'statictext',
			align:'left'
		},{
			display : '自评',
			name : 'selfScore',
			type : 'hidden'
		},{
			display : '自评',
			name : 'selfScore',
			type : 'statictext'
		},{
			display : '导师评分',
			name : 'otherScore',
			type : 'statictext'
		},{
			display : '领导评分',
			name : 'leaderScore',
			type : 'statictext'
		},{
			display : '备注',
			name : 'comment',
			type : 'statictext'
		},{
			type : 'hidden',
			display : 'id',
			name : 'id'
		}]
	});

	$(window.parent.document.getElementById("sub")).bind("click" ,function() {
		setinfo();

		if ($(window.parent.document.getElementById("appendHtml")).html() == "") {    //判断是否为最后一个审批者,不为空则为最后一个
			alert("请填写转正相关信息信息。");
			return false;
		} else if ($(window.parent.document.getElementById("directorComment")).val() == "") {
			alert("请填写评语。");
			return false;
		} else if ($(window.parent.document.getElementById("permanentTypeCode")).val() == "undefined") {
			alert("请填写转正意见。");
			return false;
		} else if ($(window.parent.document.getElementById("beforeSalary")).val() == "") {
			alert("请填写转正前工资。");
			return false;
		} else if ($(window.parent.document.getElementById("afterSalary")).val() == "") {
			alert("请填写转正后工资。");
			return false;
		// } else if ($(window.parent.document.getElementById("levelCode")).val() == "") {
		// 	alert("请填写转正后职级。");
		// 	return false;
		// } else if ($(window.parent.document.getElementById("afterPositionName")).val() == "") {
		// 	alert("请填写转正后职位。");
		// 	return false;
		} else if ($(window.parent.document.getElementsByName("examine[permanentTypeCode]")).val() == "ZZLXTQZZ") {
			if ($(window.parent.document.getElementById("permanentDate")).val() == "") {
				alert("提前转正需要填写转正日期。");
				return false;
			}
		}
	});

	$("#directorComment").blur(function () {
		setinfo();
	});
	$("input:radio[name='examine[permanentTypeCode]']").blur(function () {
		setinfo();
	});
	$("#beforeSalary_v").blur(function () {
		setinfo();
	});
	$("#afterSalary_v").blur(function () {
		setinfo();
	});
	$("#levelCode").blur(function () {
		setinfo();
	});
	$("#afterPositionName").change(function () {
		setinfo();
	});
	$("#permanentDate").change(function () {
		setinfo();
	});
});

function setinfo(){
	var directorComment = $("#directorComment").val();    //总监意见
	var permanentTypeCode = $("input:radio[name='examine[permanentTypeCode]']:checked").val(); //转正类型
	var beforeSalary = $("#beforeSalary").val();  //之前工资
	var afterSalary = $("#afterSalary").val();  //之后工资
	var afterPositionName = $("#afterPositionName").val();  //职位名称
	var afterPositionId = $("#afterPositionId").val();  //职位ID
	var levelCode = $("#levelCode").val();  //职位级别
	var parentId = $("#parentId").val();

	var appendHtml=
	' <input type="hidden" id="directorComment" name="examine[directorComment]" value="'+
	directorComment
	+'"/>'+
	' <input type="hidden" id="permanentTypeCode" name="examine[permanentTypeCode]" value="'+
	permanentTypeCode
	+'"/>'+
	' <input type="hidden" id="beforeSalary" name="examine[beforeSalary]" value="'+
	beforeSalary
	+'"/>'+
	' <input type="hidden" id="afterSalary" name="examine[afterSalary]" value="'+
	afterSalary
	+'"/>'+
	' <input type="hidden" id="afterPositionName" name="examine[afterPositionName]" value="'+
	afterPositionName
	+'"/>'+
	'<input type="hidden" id="afterPositionId" name="examine[afterPositionId]" value="'+
	afterPositionId
	+'"/>'+
	'<input type="hidden" id="levelCode" name="examine[levelCode]" value="'+
	levelCode
	+'"/>'+
	'<input type="hidden" id="parentid" name="examine[id]" value="'+
	parentId
	+'"/>';
	if(permanentTypeCode == 'ZZLXTQZZ') {
		var permanentDate = $("#permanentDate").val();
		appendHtml+=
		' <input type="hidden" id="permanentDate" name="examine[permanentDate]" value="'+
		permanentDate
		+'"/>';
	}
	if($(window.parent.document.getElementById("appendHtml")).html() != "") {   //重新选择刚先把前一次追加的内容清空
		$(window.parent.document.getElementById("appendHtml")).html("");
	}
	$(window.parent.document.getElementById("appendHtml")).append(appendHtml);
}

function getRadio(){
	if($("#permanentDate").length > 0) {
		$("#permanentDate").remove();
	}
	var input = document.createElement("input");
	input.type = "text";
	input.onclick = function() { WdatePicker(); };
	input.id = "permanentDate";
	input.name = "examine[permanentDate]";
	input.readOnly = true;
	document.getElementById("ZZYJ").appendChild(input);
}

function closeRadio(){
	if($("#permanentDate").length > 0) {
		$("#permanentDate").remove();
		return;
	}
}