function toSupport(){

	var userNoSearch = $.trim($("#userNoSearch").val());
	var userNameSearch = $.trim($("#userNameSearch").val());
	var deptNameSearch = $.trim($("#deptNameSearch").val());

	var projectNameSearch = $.trim($("#projectNameSearch").val());
	var projectContent= $.trim($("#projectContent").val());
	var beginDateSearch= $.trim($("#beginDateSearch").val());
	var closeDateSearch= $.trim($("#closeDateSearch").val());
	var projectManagerSearch= $.trim($("#projectManagerSearch").val());
	var projectPlace= $.trim($("#projectPlace").val());
	//主列表对象获取
	var listGrid= parent.$("#projectGrid").data('yxgrid');
	//设置值以及传输列表参数
	setVal(listGrid,'userNoSearch',userNoSearch);
	setVal(listGrid,'userNameSearch',userNameSearch);
	setVal(listGrid,'deptNameSearch',deptNameSearch);

	setVal(listGrid,'projectNameSearch',projectNameSearch);
	setVal(listGrid,'projectContent',projectContent);
	setVal(listGrid,'beginDateSearch',beginDateSearch);
	setVal(listGrid,'closeDateSearch',closeDateSearch);
	setVal(listGrid,'projectManagerSearch',projectManagerSearch);
	setVal(listGrid,'projectPlace',projectPlace);

	//刷新列表
	listGrid.reload();
	closeFun();
}

function setVal(obj,thisKey,thisVal){
//	parent.$("#" + thisKey).val(thisVal);
	return obj.options.extParam[thisKey] = thisVal;
}

$(function(){
	//直属部门
	$("#deptNameSearch").yxselect_dept({
		hiddenId : 'deptId'
	});
		//职位选择
		$("#jobNameSearch").yxcombogrid_position({
			hiddenId : 'jobId',
			isShowButton : false,
			width:350
		})


});


//清空
function toClear(){
	$(".toClear").val('');
}