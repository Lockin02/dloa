
/** ************************动态表单******************************** */
function products_add() {
	var mycount = $("#rowNum").val() * 1 + 1;
	var productslist =  $("#productslist")[0] ;
	var i = productslist.rows.length;
	oTR = productslist.insertRow([i]);
	oTR.className = "TableData";
	oTR.align = "center";
	oTR.height = "30px";
	oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = i;
	oTL1 = oTR.insertCell([1]);
	oTL1.innerHTML = "<input type='text' class='txtlong' name='mailapply[productsdetail]["
			+ mycount + "][productName]' id='productName" + mycount
			+ "'/>";
	oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML = "<input type='text' class='txtmiddle' name='mailapply[productsdetail]["
			+ mycount + "][mailNum]' id='mailNum" + mycount
			+ "'/>";
	oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML = "<img src='images/closeDiv.gif' onclick='mydel(this,\"productslist\");' title='删除行'>";
	$("#rowNum").val( $("#rowNum").val()*1 + 1 );
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

// *******************隐藏计划********************************
function dis(name) {
	var temp = document.getElementById(name);
	if (temp.style.display == '')
		temp.style.display = "none";
	else if (temp.style.display == "none")
		temp.style.display = '';
}