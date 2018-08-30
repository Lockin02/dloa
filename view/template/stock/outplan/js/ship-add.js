// ************************客户联系人*************************
function dynamic_add(mypay, countNum) {

	mycount = document.getElementById(countNum).value * 1 + 1;

	var mypay = document.getElementById(mypay);
	i = mypay.rows.length;
	j = i +1 ;
	oTR = mypay.insertRow([i]);
	oTR.id = "product_" + j;
	oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = j;
	oTL1 = oTR.insertCell([1]);
	oTL1.innerHTML = "<input class='txtmiddle' type='text' name='ship[productsdetail][" + mycount
			+ "][productNo]' id='productNo" + mycount + "'/>";
	/**
	 * 产品
	 */
	$("#productNo" + mycount).yxcombogrid_product({
		hiddenId : 'productId' + mycount,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(mycount){
						return function(e, row, data) {
						$("#productNo"+mycount).val(data.productCode);
						$("#productName"+mycount).val(data.productName);
						$("#productModel"+mycount).val(data.pattern);
					};
			  	}(mycount)
			}
		}
	});


	oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML = "<input class='txtmiddle' type='hidden' name='ship[productsdetail][" + mycount
			+ "][productId]' id='productId" + mycount + "'/>"
			+ "<input class='txtmiddle' type='text' name='ship[productsdetail][" + mycount
			+ "][productName]' id='productName" + mycount + "'/>";
		/**
	 * 产品
	 */
	$("#productName" + mycount).yxcombogrid_product({
		hiddenId : 'productId' + mycount,
		nameCol : 'productName',
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(mycount){
						return function(e, row, data) {
						$("#productNo"+mycount).val(data.productCode);
						$("#productName"+mycount).val(data.productName);
						$("#productModel"+mycount).val(data.pattern);
					};
			  	}(mycount)
			}
		}
	});
	oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML = "<input class='txtmiddle' type='text' name='ship[productsdetail][" + mycount
			+ "][productModel]' id='productModel" + mycount + "'/>";
	oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML = "<input class='txtshort formatMoney' type='text' name='ship[productsdetail][" + mycount
			+ "][number]' id='number" + mycount + "'/>";
	oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML = "<input class='txtlong' type='text' name='ship[productsdetail][" + mycount
			+ "][remark]' id='remark" + mycount + "'/>";
	oTL6 = oTR.insertCell([6]);
	oTL6.innerHTML = "<img src='images/closeDiv.gif' onclick='mydel(this,\""
			+ mypay.id + "\")' title='删除行'>";
	document.getElementById(countNum).value = document.getElementById(countNum).value
			* 1 + 1;
    createFormatOnClick('number'+mycount,'number' +mycount ,'number' +mycount,'money'+ mycount);
}
//TODO:动态删除表单
/** ********************删除动态表单************************ */
function mydel(obj, mytable) {
	if (confirm('确定要删除该行？')) {

		var rowSize = $("#"+mytable).children().length ;

		var rowNo = obj.parentNode.parentNode.rowIndex * 1 -2  ;

		var mytable = document.getElementById(mytable);
		mytable.deleteRow(rowNo);

		var myrows = rowSize - 1;
		for (i = 0; i < myrows; i++) {
			mytable.rows[i].childNodes[0].innerHTML = i + 1;
		}
	}
}

