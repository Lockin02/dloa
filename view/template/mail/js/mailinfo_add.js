

/** ************************��̬��******************************** */
function products_add(productslist, countNum) {
	var mycount = $("#rowNum").val()*1 + 1;
	var productslist = document.getElementById(productslist);
	i = productslist.rows.length;
	oTR = productslist.insertRow([i]);
	oTR.className = "TableHeader";
	oTR.align = "center";
	oTR.height = "30px";
	oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = i;
	oTL1 = oTR.insertCell([1]);
	oTL1.innerHTML = "<input type='text' class='txtlong' name='mailinfo[productsdetail]["
			+ mycount + "][productName]' id='productName" + mycount
			+ "'/>";
	oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML = "<input type='text' class='txtmiddle' name='mailinfo[productsdetail]["
			+ mycount + "][mailNum]' id='mailNum" + mycount
			+ "'/>";
	oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML = "<img src='images/closeDiv.gif' onclick='mydel(this,\""
			+ productslist.id + "\")' title='ɾ����'>";
	$("#rowNum").val(mycount);
}

/** ********************ɾ����̬��************************ */
function mydel(obj, mytable) {
	if (confirm('ȷ��Ҫɾ�����У�')) {
		var rowNo = obj.parentNode.parentNode.rowIndex * 1 - 1;
		var mytable = document.getElementById(mytable);
		mytable.deleteRow(rowNo);
		var myrows = mytable.rows.length;
		for (i = 1; i < myrows; i++) {
			mytable.rows[i].childNodes[0].innerHTML = i;
		}
	}
}

// *******************���ؼƻ�********************************
function dis(name) {
	var temp = document.getElementById(name);
	if (temp.style.display == '')
		temp.style.display = "none";
	else if (temp.style.display == "none")
		temp.style.display = '';
}