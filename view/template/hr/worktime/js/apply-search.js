function toSupport(){

	var userName = $.trim($("#userName").val());
	var jobName = $.trim($("#jobName").val());

	var workBeginYear = $.trim($("#workBeginYear").val());

	var workBeginMonth = $.trim($("#workBeginMonth").val());
	var userNo = $.trim($("#userNo").val());
	var deptName = $.trim($("#deptName").val());
	//主列表对象获取
	var listGrid= parent.$("#applyGrid").data('yxgrid');
	//设置值以及传输列表参数
	setVal(listGrid,'userNameS',userName);
	setVal(listGrid,'jobName',jobName);
	setVal(listGrid,'workBeginYear',workBeginYear);
	setVal(listGrid,'workBeginMonth',workBeginMonth);
	setVal(listGrid,'userNoS',userNo);
	setVal(listGrid,'deptName',deptName);

	//刷新列表
	listGrid.reload();
	closeFun();
}

function setVal(obj,thisKey,thisVal){
//	parent.$("#" + thisKey).val(thisVal);
	return obj.options.extParam[thisKey] = thisVal;
}

//$(function(){
//	//部门
////	$("#deptSearch").yxselect_dept({
////		hiddenId : 'feeDeptId'
////	});
//	//二级部门
//	$("#deptNameSSearch").yxselect_dept({
//		hiddenId : 'deptIdS'
//	});
//	//三级部门
//	$("#deptNameTSearch").yxselect_dept({
//		hiddenId : 'deptIdT'
//	});
//	//直属部门
//	$("#deptNameSearch").yxselect_dept({
//		hiddenId : 'deptId'
//	});
//		//职位选择
//		$("#jobNameSearch").yxcombogrid_position({
//			hiddenId : 'jobId',
//			width:350
//		});
//
//	$("#regionId").bind('change', function() {
//		$("#regionNameSearch").val($("#regionId").find("option:selected").text());
//	});
//	$("#companyName").bind('change', function() {
//		$("#companyNameSearch").val($("#companyName").find("option:selected").text());
//	});
//
//
//});


//清空
function toClear(){
	$(".toClear").val('');
}