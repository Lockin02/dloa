/** ********************条目列表************************ */
function dynamic_add(packinglist, countNumP) {
	mycount = document.getElementById(countNumP).value * 1 + 1;
	var packinglist = document.getElementById(packinglist);
	i = packinglist.rows.length;
	oTR = packinglist.insertRow([i]);



	var oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = i;
	var oTL1 = oTR.insertCell([1]);
	oTL1.innerHTML = "<input type='text' id='productNo" + mycount
			+ "' class='txtmiddle' name='pickingapply[pickingapplyDetail][" + mycount
			+ "][productNo]' readonly />";
	var oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML = "<input type='hidden' id='productId" + mycount + "'  name='pickingapply[pickingapplyDetail][" + mycount + "][productId]'/>" +
			"<input id='productName" + mycount + "' type='text' class='txt' name='pickingapply[pickingapplyDetail][" + mycount + "][productName]' readonly/>";



	// 单选产品
	$("#productName" + mycount).yxcombogrid_product({
		hiddenId : 'productId' + mycount,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(mycount){
						return function(e, row, data) {
						$("#productNo"+mycount).val(data.sequence);
						$("#productModel"+mycount).val(data.pattern);
					};
			  	}(mycount)
			}
		}
	});



	var oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML = "<input id='productModel" + mycount
			+ "' type='text' class='txtmiddle' name='pickingapply[pickingapplyDetail]["
			+ mycount + "][productModel]' readonly>";
	var oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML = "<input type='hidden' id='dstockId" + mycount + "' name='pickingapply[pickingapplyDetail][" + mycount + "][stockId]'/>"
			+ "<input type='text' id='dstockName" + mycount
			+ "' class='txtmiddle' name='pickingapply[pickingapplyDetail][" + mycount
			+ "][stockName]'/>";
	// 仓库
	$("#dstockName" + mycount).yxcombogrid_stock({
		hiddenId : 'dstockId' + mycount,
		gridOptions : {
			showcheckbox : false
		}
	});

	$("#dstockId" + mycount).val($("#stockId").val());
	$("#dstockName" + mycount).val($("#stockName").val());

	var oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML = "<input id='number" + mycount
			+ "' type='text' class='txtshort' name='pickingapply[pickingapplyDetail]["
			+ mycount + "][number]'>";
	var oTL6 = oTR.insertCell([6]);
	oTL6.innerHTML = "<img src='images/closeDiv.gif' onclick='mydel(this,\""
			+ packinglist.id + "\")' title='删除行'>";

	document.getElementById(countNumP).value = document
			.getElementById(countNumP).value
			* 1 + 1;

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
}

/** *****************隐藏计划******************************* */
function dis(name) {
	var temp = document.getElementById(name);
	if (temp.style.display == '')
		temp.style.display = "none";
	else if (temp.style.display == "none")
		temp.style.display = '';
}
