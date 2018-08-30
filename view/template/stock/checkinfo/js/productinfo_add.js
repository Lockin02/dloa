

/** ************************动态表单******************************** */
function products_add(productslist, countNum) {
	var stockId = $('#stockId').val();
	if (stockId == '') {
		alert('请先选择仓库！');
		return;
	}
	var mycount = $("#rowNum").val() + 1;
	var productslist = document.getElementById(productslist);
	i = productslist.rows.length;
	oTR = productslist.insertRow([i]);
	oTR.className = "tr_odd";
	var oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = i;
	var oTL1 = oTR.insertCell([1]);
	oTL1.innerHTML = "<input type='text' class='readOnlyTxt' name='stockcheckinstock[productsdetail]["
			+ mycount + "][typecode]' id='typecode" + mycount
			+ "' value='' size='15' maxlength='30' readonly/>";
	var oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML = "<input type='text' name='stockcheckinstock[productsdetail]["
			+ mycount + "][proType]' id='proType" + mycount
			+ "' value='' size='15' maxlength='40'  class='readOnlyTxt' readonly/>";

	var oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML = "<input type='text' name='stockcheckinstock[productsdetail]["
			+ mycount + "][sequence]' id='sequence" + mycount
			+ "' value=''  class='readOnlyTxt'  readonly/>";
	var oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML = "<input type='text' name='stockcheckinstock[productsdetail]["
			+ mycount + "][productName]' id='productName" + mycount
			+ "' value='' class='txt'  onclick=\"reload(\'productName" + mycount + "\')\"/><input type='hidden' name='stockcheckinstock[productsdetail]["
			+ mycount + "][productId]' id='productId" + mycount
			+ "' value='' />";
	$("#productName"+ mycount).yxcombogrid_inventory({
			gridOptions : {
				showcheckbox : false,
				model : 'stock_inventoryinfo_inventoryinfo',
				action : 'getPdinfoByStockId',
				param :{"stockId":stockId},
				event : {
					'row_dblclick' : function(e, row, data) {
					//var record = this.getSelectionModel().getSelected();
						$("#proTypeId" + mycount).val(data.proTypeId);
						$("#proType" + mycount).val(data.proType);
						$("#sequence" + mycount).val(data.productCode);
						$("#productId" + mycount).val(data.productId);
					},
					'row_click' : function() {
						// alert(123)
					},
					'row_rclick' : function() {
						// alert(222)
					}
				}
			}
		});
	var oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML = "<input type='text' name='stockcheckinstock[productsdetail]["
			+ mycount + "][adjust]' id='adjust" + mycount
			+ "' value='' class='txtshort' onblur='countDetail(this)'/>";
	var oTL6 = oTR.insertCell([6]);
	oTL6.innerHTML = "<img src='images/closeDiv.gif' onclick='mydel(this,\""
			+ productslist.id + "\")' title='删除行'>";
	$("#rowNum").val(mycount + 1);
}

/** ********************删除动态表单************************ */
function mydel(obj, mytable) {
	if (confirm('确定要删除该行？')) {
		var rowNo = obj.parentNode.parentNode.rowIndex;
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

function countDetail(obj){
	if(isNaN(obj.value.replace(/,|\s/g,''))){
		alert('请输入数字');
		obj.value="";
	}
	if(obj.value<1){
		alert('请输入大于0的数字');
		obj.value="";
	}
}