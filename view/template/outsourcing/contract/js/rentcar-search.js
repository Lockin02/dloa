function toSupport(){
	var createDateSta = $.trim($("#createDateSta").val());
	var createDateEnd = $.trim($("#createDateEnd").val());

	var orderCode = $.trim($("#orderCode").val());

	var contractNatureCode = $.trim($("#contractNatureCode").val());
	var contractTypeCode = $.trim($("#contractTypeCode").val());
	var orderName = $.trim($("#orderName").val());
	var signCompany = $.trim($("#signCompany").val());
	var companyProvinceCode = $.trim($("#companyProvinceCode").val());
	var ownCompany = $.trim($("#ownCompany").val());
	var signDateSta = $.trim($("#signDateSta").val());
	var signDateEnd = $.trim($("#signDateEnd").val());
	var createName = $.trim($("#createName").val());
	var ExaStatus = $.trim($("#ExaStatus").val());
	var signedStatus = $.trim($("#signedStatus").val());
	var isNeedStamp = $.trim($("#isNeedStamp").val());
	var isStamp = $.trim($("#isStamp").val());
	//主列表对象获取
	var listGrid= parent.$("#rentcarGrid").data('yxgrid');
	//设置值以及传输列表参数
	setVal(listGrid,'createDateSta',createDateSta);
	setVal(listGrid,'createDateEnd',createDateEnd);
	setVal(listGrid,'orderCode',orderCode);
	setVal(listGrid,'contractNatureCode',contractNatureCode);
	setVal(listGrid,'contractTypeCode',contractTypeCode);
	setVal(listGrid,'orderName',orderName);
	setVal(listGrid,'signCompany',signCompany);
	setVal(listGrid,'companyProvinceCode',companyProvinceCode);
	setVal(listGrid,'ownCompanyArr',ownCompany);
	setVal(listGrid,'signDateSta',signDateSta);
	setVal(listGrid,'signDateEnd',signDateEnd);
	setVal(listGrid,'createName',createName);
	setVal(listGrid,'ExaStatus',ExaStatus);
	setVal(listGrid,'signedStatus',signedStatus);
	setVal(listGrid,'isNeedStamp',isNeedStamp);
	setVal(listGrid,'isStamp',isStamp);

	//刷新列表
	listGrid.reload();
	closeFun();
}

function setVal(obj,thisKey,thisVal){
//	parent.$("#" + thisKey).val(thisVal);
	return obj.options.extParam[thisKey] = thisVal;
}
//清空
function toClear(){
	$(".txt").val('');
	$(".txtshort").val('');
	$(".select").val('');
}