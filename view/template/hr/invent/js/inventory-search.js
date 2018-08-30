function toSupport(){

	var userNoSearch = $.trim($("#userNo").val());
	var userNameSearch = $.trim($("#userName").val());
	var companyNameSearch = $.trim($("#companyName").val());
	var deptSearch = $.trim($("#deptName").val());
	var positionNameSearch = $.trim($("#position").val());
	var entryDateBegin = $.trim($("#entryDateBegin").val());
	var entryDateEnd = $.trim($("#entryDateEnd").val());

	var inventoryDateBegin = $.trim($("#inventoryDateBegin").val());
	var inventoryDateEnd = $.trim($("#inventoryDateEnd").val());
	//主列表对象获取
	var listGrid= parent.$("#inventoryGrid").data('yxgrid');
	//设置值以及传输列表参数
	setVal(listGrid,'userNoM',userNoSearch);
	setVal(listGrid,'userNameM',userNameSearch);
	setVal(listGrid,'companyNameM',companyNameSearch);
	setVal(listGrid,'deptName',deptSearch);

	setVal(listGrid,'position',positionNameSearch);

	setVal(listGrid,'entryDateBegin',entryDateBegin);
	setVal(listGrid,'entryDateEnd',entryDateEnd);
	setVal(listGrid,'inventoryDateBegin',inventoryDateBegin);
	setVal(listGrid,'inventoryDateEnd',inventoryDateEnd);

	//刷新列表
	listGrid.reload();
	closeFun();
}

function setVal(obj,thisKey,thisVal){
//	parent.$("#" + thisKey).val(thisVal);
	return obj.options.extParam[thisKey] = thisVal;
}

$(function(){
	//部门
	$("#deptName").yxselect_dept({
		hiddenId : 'deptId'
	});


});


//清空
function toClear(){
	$(".toClear").val('');
}