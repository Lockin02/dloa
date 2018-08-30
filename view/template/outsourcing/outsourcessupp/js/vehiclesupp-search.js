$(document).ready(function() {
	validate({
		"carAmountLower" : {
			required : false,
			custom : ['onlyNumber']
		},
		"carAmountCeiling" : {
			required : false,
			custom : ['onlyNumber']
		},
		"driverAmountLower" : {
			required : false,
			custom : ['onlyNumber']
		},
		"driverAmountCeiling" : {
			required : false,
			custom : ['onlyNumber']
		}
	});
});

//查询
function toSearch(){

	var provinceId = $.trim($("#province").val());
	var cityId = $.trim($("#city").val());
	var suppCategory = $.trim($("#suppCategory").val());

	var carAmountLower = $.trim($("#carAmountLower").val());
	var carAmountCeiling = $.trim($("#carAmountCeiling").val());
	var driverAmountLower = $.trim($("#driverAmountLower").val());
	var driverAmountCeiling = $.trim($("#driverAmountCeiling").val());
	var invoiceCode = $.trim($("#invoiceCode").val());

	var isEquipDriver = $.trim($("#isEquipDriver").val());
	var isDriveTest = $.trim($("#isDriveTest").val());

	//主列表对象获取
	var listGrid= parent.$("#vehiclesuppGrid").data('yxgrid');

	//设置值以及传输列表参数
	setVal(listGrid,'provinceId' ,provinceId);
	setVal(listGrid,'cityId' ,cityId);
	setVal(listGrid,'suppCategory' ,suppCategory);

	setVal(listGrid,'carAmountLower' ,carAmountLower);
	setVal(listGrid,'carAmountCeiling' ,carAmountCeiling);
	setVal(listGrid,'driverAmountLower' ,driverAmountLower);
	setVal(listGrid,'driverAmountCeiling' ,driverAmountCeiling);
	setVal(listGrid,'invoiceCode' ,invoiceCode);

	setVal(listGrid,'isEquipDriver' ,isEquipDriver);
	setVal(listGrid,'isDriveTest' ,isDriveTest);

	//刷新列表
	listGrid.reload();
	closeFun();
}

function setVal(obj,thisKey,thisVal){
	return obj.options.extParam[thisKey] = thisVal;
}

//重置
function toReset(){
	$("#province").val('');
	$("#city").val('');
	$("#suppCategory").val('');

	$("#carAmountLower").val('');
	$("#carAmountCeiling").val('');
	$("#driverAmountLower").val('');
	$("#driverAmountCeiling").val('');
	$("#invoiceCode").val('');

	$("#isEquipDriver").val('');
	$("#isDriveTest").val('');
}