var objTypeArr = [];// 业务类型数组

$(function() {

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

	//新增页面加载关联关系
	if($("#id").length == 0){
		objTypeArr = getData('YFRK');
		addDataToSelect(objTypeArr, 'objType1');
	}
});

function add() {

	var mycount = $("#coutNumb").val() * 1 + 1;
	var mytable = $("#mytbody")[0];
	var i = mytable.rows.length;
	oTR = mytable.insertRow([i]);
	var oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = i;

	var oTL1 = oTR.insertCell([1]);
	oTL1.innerHTML ="<select class='selectmiddel' name='payablesapply[detail]["
			+ mycount
			+ "][objType]' id='objType"+ mycount +"' onchange='initGrid(this.value,"+ mycount +")'><option value=''>请选择类型</option></select>";
	addDataToSelect(objTypeArr, 'objType' + mycount);

	var oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML = "<input type='hidden' id='objId" + mycount
			+ "'  name='payablesapply[detail][" + mycount
			+ "][objId]'/><input type='text' id='objCode" + mycount
			+ "' class='txt' name='payablesapply[detail][" + mycount
			+ "][objCode]'>";

	var oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML = "<input class='txtmiddle' id='money" + mycount
			+ "' type='text' name='payablesapply[detail]["
			+ mycount + "][money]'/><input id='oldMoney" + mycount
			+ "' type='hidden'/>";

    createFormatOnClick('money'+mycount);
    //单价绑定统计事件
    $("#money" + mycount + "_v" ).bind("blur",function(){
    	checkMax(mycount);
		countAll();
    });

	var oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML =
	"<input type='text' id='purchaseMoney" + mycount + "' name='payablesapply[detail][" + mycount
			+ "][purchaseMoney]' class='readOnlyTxt' readonly='readonly'/>";

	createFormatOnClick('purchaseMoney'+mycount);

	var oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML =

    "<input type='text' id='payed" + mycount + "' name='payablesapply[detail][" + mycount
			+ "][payedMoney]' class='readOnlyTxt' readonly='readonly'/>";

	createFormatOnClick('payed'+mycount);

	var oTL6 = oTR.insertCell([6]);
	oTL6.innerHTML =

    "<input type='text' id='handInvoiceMoney" + mycount + "' name='payablesapply[detail][" + mycount
			+ "][handInvoiceMoney]' class='readOnlyTxt' readonly='readonly'/>";

	createFormatOnClick('handInvoiceMoney'+mycount);

	var oTL7 = oTR.insertCell([7]);
	oTL7.innerHTML = "<img src='images/closeDiv.gif' onclick='mydel(this,\"mytbody\");' title='删除行'>";

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

	$("#payMoney").val(allAmount);
	$("#payMoney_v").val(moneyFormat2(allAmount));
}

function audit(thisType){
	if(thisType == 'audit'){
		document.getElementById('form1').action="?model=finance_payablesapply_payablesapply&action=add&act=audit";
	}else{
		document.getElementById('form1').action="?model=finance_payablesapply_payablesapply&action=add";
	}

}


function auditEdit(thisType){
	if(thisType == 'audit'){
		document.getElementById('form1').action="?model=finance_payablesapply_payablesapply&action=edit&act=audit";
	}else{
		document.getElementById('form1').action="?model=finance_payablesapply_payablesapply&action=edit";
	}
}