
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
	oTL1.innerHTML =  "<input type='text' class='txtshort' value='' id='productNumb"+mycount+"' name='contract[equs][" + mycount+"][productNumb]'>";
	var oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML ="<input type='text' class='txtshort' value='' id='productName"+mycount+"' name='contract[equs][" + mycount+"][productName]'>"+
						"<input type='hidden' class='productId' id='productId"+mycount+"' name='contract[equs][" + mycount+"][productId]'>";
	var oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML = "<input type='text' class='readOnlyTxtShort' id='productModel"+mycount+"' value='' name='contract[equs]["+mycount+"][pattem]'>";
	var oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML = '<input type="text" class="readOnlyTxtShort" id="unitName'+mycount+'"   value="" name="contract[equs]['+mycount+'][units]" >';
	var oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML = '<input type="text" class="amount txtshort" id="amountAll'+mycount+'"  value="" onblur="checkNum(this);" name="contract[equs]['+mycount+'][amountAll]">';
	var oTL6 = oTR.insertCell([6]);
	oTL6.innerHTML =  '<select class="" id="purchType'+mycount+'"  value="" name="contract[equs]['+mycount+'][purchType]" >' +
			'<option value="HTLX-XSHT">���ۺ�ͬ�ɹ�</option>' +
			'<option value="HTLX-FWHT">�����ͬ�ɹ�</option>' +
			'<option value="HTLX-ZLHT">���޺�ͬ�ɹ�</option>' +
			'<option value="HTLX-YFHT">�з���ͬ�ɹ�</option>' +
			'<option value="stock">����ɹ�</option>' +
			'<option value="rdproject">�з��ɹ�</option>' +
			'<option value="assets">�ʲ��ɹ�</option>' +
			'<option value="produce">�����ɹ�</option>' +
			'</select';
    oTL7 = oTR.insertCell([7]);
    oTL7.innerHTML = "<input class='txtshort' type='text' name='contract[equs][" + mycount
            + "][applyDeptName]' id='applyDeptName" + mycount
            + "' size='10' title='˫��ѡ����' readonly>" +
            "<input type='hidden' value='' id='applyDeptId"+mycount+"' name='contract[equs][" + mycount  + "][applyDeptId]'/>";
    oTL8 = oTR.insertCell([8]);
    oTL8.innerHTML = "<input class='txtshort' type='text' name='contract[equs][" + mycount
    		+ "][sourceNumb]' id='sourceNumb" + mycount + "'>";
	var oTL9 = oTR.insertCell([9]);
	oTL9.innerHTML =  '<input type="text" class="txtshort" readonly="" value="" name="contract[equs]['+mycount+'][remark]">';
	var oTL10 = oTR.insertCell([10]);
	oTL10.innerHTML = '<img title="ɾ����" onclick="mydel(this , \'mytable\')" src="images/closeDiv.gif">';

	document.getElementById(countNumP).value = document
			.getElementById(countNumP).value
			* 1 + 1;
//	createFormatOnClick('moneyAll' + mycount, 'amountAll' + mycount, 'applyPrice'
//			+ mycount, 'moneyAll' + mycount);
	createFormatOnClick('moneyAll' + mycount);
	createFormatOnClick('price' + mycount);
//    $("#applyPrice"+mycount+"_v").bind('blur',function(){sumPrice(mycount);sumAllMoney();});



	$("#productNumb"+mycount).yxcombogrid_product({
    	hiddenId : 'productId'+ mycount,
		nameCol : 'productCode',
		closeCheck : true,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(mycount){
					return function(e, row, data) {
						$("#productName"+mycount).val(data.productName);
						$("#productModel"+mycount).val(data.pattern);
						$("#unitName"+mycount).val(data.unitName);
					};
			  	}(mycount)
			}
		}
    });

	$("#productName"+mycount).yxcombogrid_product({
    	hiddenId : 'productId'+ mycount,
		nameCol : 'productName',
		closeCheck : true,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(mycount){
					return function(e, row, data) {
						$("#productNumb"+mycount).val(data.productCode);
						$("#productModel"+mycount).val(data.pattern);
						$("#unitName"+mycount).val(data.unitName);
					};
			  	}(mycount)
			}
		}
    });
	$("#applyDeptName"+mycount).yxselect_dept({
				hiddenId : 'applyDeptId'+mycount
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
}