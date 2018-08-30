$(document).ready(function() {
	$("#TO_NAME").yxselect_user({
		mode : 'check',
		hiddenId : 'defaultUserId',
		formCode : 'defaultUserName'
	});
	$("#ADDNAMES").yxselect_user({
		mode : 'check',
		hiddenId : 'ccUserId',
		formCode : 'ccUserName'
	});
	validate( {
		"flightNumber" : {
			required : true
		},
		"flightTime" : {
			required : true
		},
		"arrivalTime" : {
			required : true
		},
		"actualCost_v" : {
			required : true
		},
		"feeChange_v" : {
			required : true
		},
		"fullFare_v" : {
			required : true
		},
		"constructionCost_v" : {
			required : true
		},
		"fuelCcharge_v" : {
			required : true
		},
		"changeReason" : {
			required : true
		},
		"auditDate" : {
			required : true
		}
	});
});

//计算实际订票价格
function calActCost() {
	var fullFare = $("#fullFare").val();
	var constructionCost = $("#constructionCost").val();
	var serviceCharge = $("#serviceCharge").val();
	var fuelCcharge = $("#fuelCcharge").val();
	var all = accAdd(accAdd(fullFare, constructionCost, 2), accAdd(serviceCharge, fuelCcharge, 2), 2);
	setMoney("actualCost",all);

	$("#costDiff").val(accSub(all,$("#beforeCost").val(),2));
}