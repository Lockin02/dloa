$(document).ready(function() {
	//加载周选择器
	$('#weekTimes').numberspinner({
	    editable: true,
	    min: 1000,
   	 	max: 9999,
	    onSpinUp : function(value,value2){
    		goWeek();
	    },
	    onSpinDown : function(value){
    		goWeek();
	    }
	});
});

//获取昨天
function goWeek(){
	var weekTimes = $('#weekTimes').val();
	var projectId = $("#projectId").val();
	if(projectId != '0'){
		$.ajax({
		    type: "POST",
		    url: "?model=engineering_cost_esmcostdetail&action=ajaxManageListYW",
		    data: {"weekTimes" : weekTimes , "projectId" : projectId },
		    async: false,
		    success: function(data){
		   		if(data){
					initPage(data);
		   	    }else{
					alert('加载失败');
		   	    }
			}
		});
	}
}

//进入确认费用页面
function confirmCost(worklogId){
	//如果是查看，则直接弹出查看页面
//	if($("#isView").val() == "1"){
		viewCost(worklogId);
//	}else{
//		var url = "?model=engineering_worklog_esmworklog&action=toConfirmNew&id=" + worklogId;
//		var height = 800;
//		var width = 1150;
//		window.open(url, "审核日志费用",
//		'top=0,left=0,menubar=0,toolbar=0,status=1,scrollbars=1,resizable=1,width='
//				+ width + ',height=' + height);
//	}
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

//暂且算是show_page吧
function show_page(){
	//调用刷新列表
	changeRange();
}

//变更日期时刷新列表
function changeRange(){
	var beginDate = $("#beginDate").val();
	var endDate = $("#endDate").val();
	var projectId = $("#projectId").val();

	var s = DateDiff(beginDate,endDate);
	if(s < 0) {
		alert("查询起始日期不能比查询结束日期晚！");
		return false;
	}

	$.ajax({
	    type: "POST",
	    url: "?model=engineering_cost_esmcostdetail&action=ajaxManageList",
	    data: {"beginDate" : beginDate , 'endDate' : endDate , "projectId" : projectId },
	    async: false,
	    success: function(data){
	   		if(data){
				initPage(data);
	   	    }else{
				alert('加载失败');
	   	    }
		}
	});
}

//初始化页面
function initPage(data){

	//加载表头
	var header = '<tr class="main_tr_header">' +
			'<th style="width:40px">序号</th>' +
			'<th style="width:70px">员工姓名</th>' +
			'<th style="width:60px">提交周报</th>' +
			'<th style="width:80px">全部费用</th>' +
			'<th style="width:80px">未审核费用</th>' +
			'<th style="width:80px">当前费用</th>'
		;

	var htmObj = eval("(" + data + ")");
	header = header + htmObj.tr + "</tr>";
	$("#thisHead").html(header);

	//加载表格内容
	var tbody = htmObj.list;
	$("#thisTbody").html(tbody);

	$("#beginDate").val(htmObj.beginDate);
	$("#endDate").val(htmObj.endDate);

	//期次设置
	if(htmObj.weekTimes){
		var weekTimesObj = $('#weekTimes');
		if( htmObj.weekTimes != weekTimesObj.val()){
			weekTimesObj.val(htmObj.weekTimes);
			weekTimesObj.next().val(htmObj.weekTimes);
		}
	}

	//格式化金额部分
	formateMoney();
}

//返回当前周
function returnDefualtWeek(){
	var defaultWeekTimesObj = $('#defaultWeekTimes');
	var weekTimesObj = $('#weekTimes');
	if(defaultWeekTimesObj.val() != weekTimesObj.val()){
		weekTimesObj.val(defaultWeekTimesObj.val());
		goWeek();
	}else{
		alert('已经是当前周');
	}
}

//查询未审核
function returnUnconfirm(){
	//查询包含有未审核费用的周
	var weekTimesObj = $('#weekTimes');

	$.ajax({
	    type: "POST",
	    url: "?model=engineering_cost_esmcostdetail&action=getUnconfirmWeek",
	    data: { "projectId" : $("#projectId").val() },
	    async: false,
	    success: function(data){
	   		if(data != "-1" && data != "0"){
				weekTimesObj.val(data);
				goWeek();
	   	    }else if(data == "-1"){
				alert('查询失败');
	   	    }else{
	   	    	alert('未查询到需要审核的费用');
	   	    }
		}
	});
}