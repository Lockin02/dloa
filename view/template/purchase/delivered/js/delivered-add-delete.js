

/**********************ɾ����̬��*************************/
function mydel(obj, mytable) {
	if (confirm('ȷ��Ҫɾ�����У�')) {
		var rowNo = obj.parentNode.parentNode.rowIndex;
		var mytable = document.getElementById(mytable);
		mytable.deleteRow(rowNo);
		var myrows = mytable.rows.length;
		for (i = 2; i < myrows; i++) {
			mytable.rows[i].childNodes[0].innerHTML = i-1;
		}
	}
}

/**********************��Ŀ�б�*************************/
function dynamic_add(packinglist, countNumP) {
	mycount = document.getElementById(countNumP).value * 1 + 1;
	var packinglist = document.getElementById(packinglist);
	i = packinglist.rows.length;
	oTR = packinglist.insertRow([i]);
	var oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = i+1;
	var oTL1 = oTR.insertCell([1]);
	oTL1.innerHTML =  "<input type='text' class='txtshort' value='' id='productNumb"+mycount+"' name='delivered[equipment][" + mycount+"][productNumb]'>";
	var oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML ="<input type='text' class='txtshort' value='' id='productName"+mycount+"' name='delivered[equipment][" + mycount+"][productName]'  >"+
						"<input type='hidden' value='' id='productId"+mycount+"' name='delivered[equipment][" + mycount+"][productId]'>";
	var oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML = "<input type='text' class='readOnlyTxtItem' id='productModel"+mycount+"' value='' name='delivered[equipment]["+mycount+"][pattem]' readonly>";
	var oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML = '<input type="text" class="readOnlyTxtItem" id="units'+mycount+'"  value="" name="delivered[equipment]['+mycount+'][units]"  readonly>' ;
	var oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML = '<input type="text" class="txtshort"   value="" name="delivered[equipment]['+mycount+'][batchNum]" >';
	var oTL6 = oTR.insertCell([6]);
	oTL6.innerHTML =  '<input type="text" class="txtshort" onblur="checkNum(this);" value="" name="delivered[equipment]['+mycount+'][deliveredNum]">';
	var oTL7 = oTR.insertCell([7]);
	oTL7.innerHTML = '<img title="ɾ����" onclick="mydel(this , \'mytable\')" src="images/closeDiv.gif">';

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

/** *****************���ؼƻ�******************************* */
function dis(name) {
	var temp = document.getElementById(name);
	if (temp.style.display == '')
		temp.style.display = "none";
	else if (temp.style.display == "none")
		temp.style.display = '';
}




//�ж������Ƿ�Ϸ�
function checkNum(obj){
		var thisVal = parseInt( $(obj).val() );;
		if(isNaN(obj.value.replace(/,|\s/g,''))){
			alert("����������");
			obj.value="";
			$(obj).focus();
		}
		if(thisVal<1){
			alert("��������ȷ������,����Ϊ�ջ���С��1");
			$(obj).attr("value"," ");
			$(obj).focus();
		}
		if(parseInt($(obj).next().val())<thisVal){
			alert("�������ܴ�����������-�������");
			$(obj).val(parseInt($(obj).next().val()));
			$(obj).focus();
		}
}