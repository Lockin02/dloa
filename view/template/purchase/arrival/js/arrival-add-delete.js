

/**********************删除动态表单*************************/
function mydel(obj, mytable) {
	if (confirm('确定要删除该行？')) {
		var rowNo = obj.parentNode.parentNode.rowIndex;
		var mytable = document.getElementById(mytable);
		mytable.deleteRow(rowNo);
		var myrows = mytable.rows.length;
		for (i = 2; i < myrows; i++) {
			mytable.rows[i].childNodes[0].innerHTML = i-1;
		}
	}
}

/**********************条目列表*************************/
function dynamic_add(packinglist, countNumP) {
	mycount = document.getElementById(countNumP).value * 1 + 1;
	var packinglist = document.getElementById(packinglist);
	i = packinglist.rows.length;
	oTR = packinglist.insertRow([i]);
	var oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = i+1;
	var oTL1 = oTR.insertCell([1]);
	oTL1.innerHTML =  "<input type='text' class='txtshort' value='' id='productNumb"+mycount+"' name='arrival[equipment][" + mycount+"][sequence]' >";
	var oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML ="<input type='text' class='txtshort' value='' id='productName"+mycount+"' name='arrival[equipment][" + mycount+"][productName]' class='txt'>"+
						"<input type='hidden' value='' id='productId"+mycount+"' name='arrival[equipment][" + mycount+"][productId]'>";
	var oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML = "<input type='text' class='readOnlyTxtItem' id='productModel"+mycount+"' value='' name='arrival[equipment]["+mycount+"][pattem]' class='amount txt''>";
	var oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML = '<input type="text" class="readOnlyTxtItem" id="units'+mycount+'"  value="" name="arrival[equipment]['+mycount+'][units]" >';
	var oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML = '<input type="text" class="txtshort"  value="" name="arrival[equipment]['+mycount+'][batchNum]">';
	var oTL6 = oTR.insertCell([6]);
	oTL6.innerHTML =  '<input type="text" class="txtshort" onblur="checkNum(this);" value="" name="arrival[equipment]['+mycount+'][arrivalNum]">';
	var oTL7 = oTR.insertCell([7]);
	oTL7.innerHTML =  '<input type="text" class="txtshort" value="" name="arrival[equipment]['+mycount+'][month]"  onblur="checkMonth(this);">';
	var oTL8 = oTR.insertCell([8]);
	oTL8.innerHTML =  '<input type="text" class="txtshort" readonly="" onfocus="WdatePicker()" value="" name="arrival[equipment]['+mycount+'][arrivalDate]">';
	var oTL9 = oTR.insertCell([9]);
	oTL9.innerHTML =  '<select class="txtshort" name="arrival[equipment]['+mycount+'][qualityCode]">'+$("#qualityList").val()+'</select>';
	var oTL10 = oTR.insertCell([10]);
	oTL10.innerHTML =  '<select class="txtshort" name="arrival[equipment]['+mycount+'][checkType]">'+$("#checkTypes").val()+'</select>';
	var oTL11 = oTR.insertCell([11]);
	oTL11.innerHTML = '<img title="删除行" onclick="mydel(this , \'mytable\')" src="images/closeDiv.gif">';

	document.getElementById(countNumP).value = document
			.getElementById(countNumP).value
			* 1 + 1;

	$("#productNumb"+mycount).yxcombogrid_product({
    	hiddenId : 'productId'+ mycount,
		nameCol : 'productCode',
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(mycount){
					return function(e, row, data) {
						$("#productModel"+mycount).val(data.pattern);
						$("#productName"+mycount).val(data.productName);
						$("#units"+mycount).val(data.unitName);
					};
			  	}(mycount)
			}
		}
    });

	$("#productName"+mycount).yxcombogrid_product({
    	hiddenId : 'productId'+ mycount,
		nameCol : 'productName',
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(mycount){
					return function(e, row, data) {
						$("#productNumb"+mycount).val(data.productCode);
						$("#productModel"+mycount).val(data.pattern);
						$("#units"+mycount).val(data.unitName);
					};
			  	}(mycount)
			}
		}
    });
}

/** *****************隐藏计划******************************* */
function dis(name) {
	var temp = document.getElementById(name);
	if (temp.style.display == '')
		temp.style.display = "none";
	else if (temp.style.display == "none")
		temp.style.display = '';
}




//判断数量是否合法
function checkNum(obj){
		var thisVal = parseInt( $(obj).val() );;
		if(isNaN(obj.value.replace(/,|\s/g,''))){
			alert("请输入数字");
			obj.value="";
			$(obj).focus();
		}
		if(thisVal<1){
			alert("请输入正确的数量,不能为空或者小于1");
			$(obj).attr("value"," ");
			$(obj).focus();
		}
}//判断月份是否合法
function checkMonth(obj){
		var thisVal = parseInt( $(obj).val() );;
		if(isNaN(obj.value.replace(/,|\s/g,''))){
			alert("请输入数字");
			obj.value="";
			$(obj).focus();
		}
		if(thisVal<1||thisVal>12){
			alert("请输入正确的月份,从1-12");
			$(obj).attr("value"," ");
			$(obj).focus();
		}
}