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
	//���б�����ȡ
	var listGrid= parent.$("#inventoryGrid").data('yxgrid');
	//����ֵ�Լ������б����
	setVal(listGrid,'userNoM',userNoSearch);
	setVal(listGrid,'userNameM',userNameSearch);
	setVal(listGrid,'companyNameM',companyNameSearch);
	setVal(listGrid,'deptName',deptSearch);

	setVal(listGrid,'position',positionNameSearch);

	setVal(listGrid,'entryDateBegin',entryDateBegin);
	setVal(listGrid,'entryDateEnd',entryDateEnd);
	setVal(listGrid,'inventoryDateBegin',inventoryDateBegin);
	setVal(listGrid,'inventoryDateEnd',inventoryDateEnd);

	//ˢ���б�
	listGrid.reload();
	closeFun();
}

function setVal(obj,thisKey,thisVal){
//	parent.$("#" + thisKey).val(thisVal);
	return obj.options.extParam[thisKey] = thisVal;
}

$(function(){
	//����
	$("#deptName").yxselect_dept({
		hiddenId : 'deptId'
	});


});


//���
function toClear(){
	$(".toClear").val('');
}