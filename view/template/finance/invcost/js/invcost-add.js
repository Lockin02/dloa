/** ********************条目列表************************ */
function dynamic_add(packinglist, countNumP) {
	mycount = document.getElementById(countNumP).value * 1 + 1;
	var packinglist = document.getElementById(packinglist);
	i = packinglist.rows.length;
	oTR = packinglist.insertRow([i]);
	var oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = i;
	var oTL1 = oTR.insertCell([1]);
	oTL1.innerHTML = "<input type='text' id='costCode" + mycount
			+ "' class='txtshort' name='invcost[invcostDetail][" + mycount
			+ "][costCode]'>";
	var oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML = "<input id='costName" + mycount
			+ "' type='text' class='txtmiddle' name='invcost[invcostDetail]["
			+ mycount + "][costName]'>";
	var oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML = "<input id='costType" + mycount
			+ "' type='text' class='txtshort' name='invcost[invcostDetail][" + mycount
			+ "][costType]' onblur='checkMoney()'/>";
	var oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML = "<input type='text' id='unit" + mycount
			+ "' class='txtshort' name='invcost[invcostDetail][" + mycount
			+ "][unit]'/>";
	var oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML = "<input type='text' id='number"
			+ mycount
			+ "'class='txtshort' name='invcost[invcostDetail]["
			+ mycount
			+ "][number]' onclick='countDetail();'/>";
	var oTL6 = oTR.insertCell([6]);
	oTL6.innerHTML = "<input type='text' id='price"
			+ mycount
			+ "' class='txtshort' name='invcost[invcostDetail]["
			+ mycount
			+ "][price]' onclick='countDetail();'/>";
	var oTL7 = oTR.insertCell([7]);
	oTL7.innerHTML = "<input type='text' id='amount"
			+ mycount
			+ "' class='txtshort' name='invcost[invcostDetail]["
			+ mycount
			+ "][amount]' onclick='countDetail();' onblur='countDetail();'/>";
	var oTL8 = oTR.insertCell([8]);
	oTL8.innerHTML = "<img src='images/closeDiv.gif' onclick='mydel(this,\""
			+ packinglist.id + "\")' title='删除行'>";

	document.getElementById(countNumP).value = document
			.getElementById(countNumP).value
			* 1 + 1;
	createFormatOnClick('number' + mycount, 'number' + mycount, 'price'
			+ mycount, 'amount' + mycount);
	createFormatOnClick('amount' + mycount, 'number' + mycount, 'price'
			+ mycount, 'amount' + mycount);
	createFormatOnClick('price' + mycount, 'number' + mycount, 'price'
			+ mycount, 'amount' + mycount);

	//数量绑定统计事件
    $("#number" + mycount + "_v").bind("blur",function(){
		countDetail();
    });

    //单价绑定统计事件
    $("#price" + mycount + "_v").bind("blur",function(){
		countDetail();
    });
}

/** ********************删除动态表单************************ */
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
	countDetail();
}

/** *****************隐藏计划******************************* */
function dis(name) {
	var temp = document.getElementById(name);
	if (temp.style.display == '')
		temp.style.display = "none";
	else if (temp.style.display == "none")
		temp.style.display = '';
}

/** *****************计算金额******************************* */
function countDetail() {
//	 if (isNaN(obj.value.replace(/,|\s/g, ''))) {
//	 alert('请输入数字')
//	 obj.value = "";
//	 }
	var detailRows = $('#invnumber').val();
	var amounts = 0;
	var allAmount = 0;
	for (var i = 1; i <= detailRows; i++) {
		var amounts = $('#amount' + i).val();
		if (amounts != undefined && amounts != "") {
			allAmount += amounts * 1;
		}
	}
	$('#allAmount').val(allAmount);
	$('#allAmount_v').val(moneyFormat2(allAmount));
}
