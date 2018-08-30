var objTypeArr = [];// 业务类型数组

$(function() {
	objTypeArr = getData('YFRK');

	// 单选客户
	$("#supplierName").yxcombogrid_supplier({
		hiddenId : 'supplierId',
		gridOptions : {
			showcheckbox : false
		}
	});

	$("#salesman").yxselect_user({
		hiddenId : 'salesmanId'
	});
	$("#deptName").yxselect_dept({
		hiddenId : 'deptId'
	});
});

function add() {
	var customerId = $('#incomeUnitId').val();

	var mycount = $("#coutNumb").val() * 1 + 1;
	var mytable = $("#mytbody")[0];
	var i = mytable.rows.length;
	oTR = mytable.insertRow([i]);
	oTR.align = "center";
	oTR.height = "28px";
	var oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = i;

	var oTL1 = oTR.insertCell([1]);
	oTL1.innerHTML ="<select class='selectmiddel' name='payables[detail]["
			+ mycount
			+ "][objType]' id='objType"+ mycount +"'' onchange='initGrid("+ mycount +")'><option value=''>请选择类型</option></select>";
	addDataToSelect(objTypeArr, 'objType' + mycount);

	var oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML = "<input type='hidden' id='objId" + mycount
			+ "'  name='payables[detail][" + mycount
			+ "][objId]'/><input type='text' id='objCode" + mycount
			+ "' class='txt' name='payables[detail][" + mycount
			+ "][objCode]'>";

	var oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML = "<input class='txtmiddle' id='money" + mycount
			+ "' type='text' name='payables[detail]["
			+ mycount + "][money]'/>";

    createFormatOnClick('money'+mycount);
    //单价绑定统计事件
    $("#money" + mycount + "_v" ).bind("blur",function(){
		countAll();
    });

	var oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML =
	"<input type='text' name='payables[detail][" + mycount
			+ "][payContent]' class='txtlong'/>";

//	var oTL5 = oTR.insertCell([5]);
//	oTL5.innerHTML =
//
//    "<input type='text' id='objId" + mycount
//			+ "'  name='payables[detail][" + mycount
//			+ "][orgFormNo]' class='readOnlyTxt' readonly='readonly'/>";

	var oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML = "<img src='images/closeDiv.gif' onclick='mydel(this,\"mytbody\");' title='删除行'>";

	$("#coutNumb").val($("#coutNumb").val() * 1 + 1);
}

function mydel(obj, mytable) {
	if (confirm('确定要删除该行？')) {
		var rowNo = obj.parentNode.parentNode.rowIndex * 1 - 1;
		var mytable = document.getElementById(mytable);
		mytable.deleteRow(rowNo);
		var myrows = mytable.rows.length;
		for (i = 1; i < myrows; i++) {
			mytable.rows[i].childNodes[0].innerHTML = i;
		}
	}
	countAll();
}

//计算总金额
function countAll(){
	var invnumber = $('#coutNumb').val();
	var thisAmount = 0;
	var allAmount = 0;
	for(var i = 1;i <= invnumber;i++){
		thisAmount = $('#money' + i).val() * 1;
		if(!isNaN(thisAmount)){
			allAmount += thisAmount;
		}
	}

	$("#amount").val(allAmount);
	$("#amount_v").val(moneyFormat2(allAmount));
}