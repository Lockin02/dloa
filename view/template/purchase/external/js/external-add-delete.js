


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
function mydelProduce(obj, mytable) {
	if (confirm('ȷ��Ҫɾ�����У�')) {
		var rowNo = obj.parentNode.parentNode.rowIndex;
		var mytable = document.getElementById(mytable);
		mytable.deleteRow(rowNo);
		var myrows = mytable.rows.length;
		for (i = 2; i < myrows; i++) {
			mytable.rows[i].childNodes[1].innerHTML = i-1;
		}
	}
}






/**********************��Ŀ�б�*************************/
function dynamic_add(packinglist, countNumP) {

	var purchType=$("#purchType").val();
	var sendTime=$("#sendTime").val();
	var dateHope=$("#dateHope").val();
//	if(purchType==""){
//		alert("����ѡ��ɹ��ƻ����ͣ�");
//		return false;
//	}
	mycount = document.getElementById(countNumP).value * 1 + 1;
	var packinglist = document.getElementById(packinglist);
	i = packinglist.rows.length;
	oTR = packinglist.insertRow([i]);
	var oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = i+1;
	var oTL1 = oTR.insertCell([1]);
	oTL1.innerHTML =  "<input type='text' class='txtshort' value='' id='productNumb"+mycount+"' name='basic[equipment][" + mycount+"][productNumb]' >";
	var oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML ="<input type='text' class='txtshort' value='' id='productName"+mycount+"' name='basic[equipment][" + mycount+"][productName]' />"+
						"<input type='hidden' class='productId' value='' id='productId"+mycount+"' name='basic[equipment][" + mycount+"][productId]'/>";
	var oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML = '<input type="text" class="readOnlyTxtItem" id="pattem'+mycount+'"  value="" name="basic[equipment]['+mycount+'][pattem]" readonly/>';
	var oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML = '<input type="text" class="readOnlyTxtItem" id="unitName'+mycount+'"  value="" name="basic[equipment]['+mycount+'][unitName]" readonly/>';
	var oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML = '<input type="text" class="amount txtshort" onblur="checkNum(this);" value="" name="basic[equipment]['+mycount+'][amountAll]">'+
						'<input type="hidden" value="" name="amountAll">';
	var oTL6 = oTR.insertCell([6]);
	oTL6.innerHTML = '<input type="text" class="readOnlyTxtShort" value="'+sendTime+'" name="basic[equipment]['+mycount+'][dateIssued]" class="txt">';
	var oTL7 = oTR.insertCell([7]);
	oTL7.innerHTML = '<input type="text" class="txtshort" readonly="" onfocus="WdatePicker()" value="'+dateHope+'" name="basic[equipment]['+mycount+'][dateHope]" class="txt">';
	var oTL8 = oTR.insertCell([8]);
	oTL8.innerHTML = '<input type="text" class="txt"  name="basic[equipment]['+mycount+'][remark]"/>'+
						 '<input type="hidden" value="'+purchType+'" name="basic[equipment]['+mycount+'][purchType]">'+
						 '<input id="equObjAssId" type="hidden" value="" name="basic[equipment]['+mycount+'][equObjAssId]">';
	var oTL9 = oTR.insertCell([9]);
	oTL9.innerHTML = '<img title="ɾ����" onclick="mydel(this , \'mytable\')" src="images/closeDiv.gif">';

	document.getElementById(countNumP).value = document
			.getElementById(countNumP).value
			* 1 + 1;

	$("#productNumb"+mycount).yxcombogrid_product({
    	hiddenId : 'productId'+ mycount,
		closeCheck : true,
		gridOptions : {
			showcheckbox : false,
//			param:{'notStatTypeArr':'TJCP,TJBCP'},
			event : {
				'row_dblclick' : function(mycount){
					return function(e, row, data) {
//						$("#productNumb"+mycount).val(data.productCode);
						$("#unitName"+mycount).val(data.unitName);
						$("#pattem"+mycount).val(data.pattern);
						$("#productName"+mycount).val(data.productName);
					};
			  	}(mycount)
			}
		}
    });

	$("#productName"+mycount).yxcombogrid_product({
    	hiddenId : 'productId'+ mycount,
    	nameCol:'productName',
		closeCheck : true,
		gridOptions : {
			showcheckbox : false,
//			param:{'notStatTypeArr':'TJCP,TJBCP'},
			event : {
				'row_dblclick' : function(mycount){
					return function(e, row, data) {
						$("#productNumb"+mycount).val(data.productCode);
						$("#unitName"+mycount).val(data.unitName);
						$("#pattem"+mycount).val(data.pattern);
					};
			  	}(mycount)
			}
		}
    });
}
function dynamic_add_contract(packinglist, countNumP) {

	var purchType=$("#purchType").val();
	var sendTime=$("#sendTime").val();
	var dateHope=$("#dateHope").val();
	mycount = document.getElementById(countNumP).value * 1 + 1;
	var packinglist = document.getElementById(packinglist);
	i = packinglist.rows.length;
	oTR = packinglist.insertRow([i]);
	var oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = i+1;
	var oTL1 = oTR.insertCell([1]);
	oTL1.innerHTML =  "<input type='text' class='txtshort' value='' id='productNumb"+mycount+"' name='basic[equipment][" + mycount+"][productNumb]' >";
	var oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML ="<input type='text' class='txtmiddle' value='' id='productName"+mycount+"' name='basic[equipment][" + mycount+"][productName]' />"+
						"<input type='hidden' class='productId' value='' id='productId"+mycount+"' name='basic[equipment][" + mycount+"][productId]'/>";
	var oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML = '<input type="text" class="readOnlyTxtItem" id="pattem'+mycount+'"  value="" name="basic[equipment]['+mycount+'][pattem]" readonly/>';
	var oTL4= oTR.insertCell([4]);
	oTL4.innerHTML =  '<select class="txtshort" name="basic[equipment]['+mycount+'][qualityCode]">'+$("#qualityList").val()+'</select>';
	var oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML = '<select name="basic[equipment]['+mycount+'][testType]" id="testType'+mycount+'" class="txtshort"><option value="0">ȫ��</option><option value="1">���</option><option value="2">���</option></select>';
	var oTL6 = oTR.insertCell([6]);
	oTL6.innerHTML = '<font color=green id="exeNum'+mycount+'">��</font>';
	var oTL7 = oTR.insertCell([7]);
	oTL7.innerHTML ='<font color=green >��</font>';
	var oTL8 = oTR.insertCell([8]);
	oTL8.innerHTML = '<font color=green >0</font>';
	var oTL9 = oTR.insertCell([9]);
	oTL9.innerHTML = '<font color=green >0</font>';
	var oTL10 = oTR.insertCell([10]);
	oTL10.innerHTML = '<input type="text" class="txtshort" id="amountAll'+mycount+'" name="basic[equipment]['+mycount+'][amountAll]">';
	var oTL11 = oTR.insertCell([11]);
	oTL11.innerHTML = '<input type="text" class="readOnlyTxtShort" id="unitName'+mycount+'" name="basic[equipment]['+mycount+'][unitName]">';
	var oTL12 = oTR.insertCell([12]);
	oTL12.innerHTML = '<input type="text" class="readOnlyTxtShort" id="dateIssued'+mycount+'" name="basic[equipment]['+mycount+'][dateIssued]"  readonly >';
	$('#dateIssued'+mycount).val($('#sendTime').val());
	var oTL13 = oTR.insertCell([13]);
	oTL13.innerHTML = '<input type="text" class="txtshort" id="dateHope'+mycount+'" name="basic[equipment]['+mycount+'][dateHope]" onfocus="WdatePicker()" readonly >';
	var oTL14 = oTR.insertCell([14]);
	oTL14.innerHTML = '<input type="text" class="txt"  name="basic[equipment]['+mycount+'][remark]"/>'+
						 '<input type="hidden" name="basic[equipment]['+mycount+'][applyEquId]">'+
						 '<input type="hidden" name="basic[equipment]['+mycount+'][uniqueCode]">'+
						 '<input type="hidden" name="basic[equipment]['+mycount+'][equObjAssId]">'+
						 '<input type="hidden" id="purchType' + mycount + '" name="basic[equipment]['+mycount+'][purchType]"/> ';
	$('#purchType'+mycount).val($('#purchType1').val());

	var oTL15 = oTR.insertCell([15]);
	oTL15.innerHTML = '<img title="ɾ����" onclick="mydel(this , \'mytable\')" src="images/closeDiv.gif">';


	document.getElementById(countNumP).value = document
			.getElementById(countNumP).value
			* 1 + 1;

	$("#productNumb"+mycount).yxcombogrid_product({
    	hiddenId : 'productId'+ mycount,
		closeCheck : true,
		gridOptions : {
			showcheckbox : false,
//			param:{'notStatTypeArr':'TJCP,TJBCP'},
			event : {
				'row_dblclick' : function(mycount){
					return function(e, row, data) {
//						$("#productNumb"+mycount).val(data.productCode);
						$("#unitName"+mycount).val(data.unitName);
						$("#pattem"+mycount).val(data.pattern);
						$("#productName"+mycount).val(data.productName);
						$.ajax({
							type : "POST",
							url : "?model=stock_inventoryinfo_inventoryinfo&action=getExeNum",
							data : {
								id : data.id
							},
							success : function(msg) {
								msg=msg*1;
								$('#exeNum'+mycount).html(msg);
							}
						});
					};
			  	}(mycount)
			}
		}
    });

	$("#productName"+mycount).yxcombogrid_product({
    	hiddenId : 'productId'+ mycount,
    	nameCol:'productName',
		closeCheck : true,
		gridOptions : {
			showcheckbox : false,
//			param:{'notStatTypeArr':'TJCP,TJBCP'},
			event : {
				'row_dblclick' : function(mycount){
					return function(e, row, data) {
						$("#productNumb"+mycount).val(data.productCode);
						$("#unitName"+mycount).val(data.unitName);
						$("#pattem"+mycount).val(data.pattern);
						$.ajax({
							type : "POST",
							url : "?model=stock_inventoryinfo_inventoryinfo&action=getExeNum",
							data : {
								id : data.id
							},
							success : function(msg) {
								msg=msg*1;
								$('#exeNum'+mycount).html(msg);
							}
						});
					};
			  	}(mycount)
			}
		}
    });
}
//�º�ͬ�ɹ�����
function dynamic_add_newContract(packinglist, countNumP) {

	var purchType=$("#purchType").val();
	var sendTime=$("#sendTime").val();
	var dateHope=$("#dateHope").val();
	mycount = document.getElementById(countNumP).value * 1 + 1;
	var packinglist = document.getElementById(packinglist);
	i = packinglist.rows.length;
	oTR = packinglist.insertRow([i]);
	var oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = i+1;
	var oTL1 = oTR.insertCell([1]);
	oTL1.innerHTML =  "<input type='text' class='txtshort' value='' id='productNumb"+mycount+"' name='basic[equipment][" + mycount+"][productNumb]' >";
	var oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML ="<input type='text' class='txt' value='' id='productName"+mycount+"' name='basic[equipment][" + mycount+"][productName]' />"+
						"<input type='hidden' class='productId' value='' id='productId"+mycount+"' name='basic[equipment][" + mycount+"][productId]'/>";
	var oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML = '<input type="text" class="readOnlyTxt" id="pattem'+mycount+'"  value="" name="basic[equipment]['+mycount+'][pattem]" readonly/>';
	var oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML = '<input type="text" class="readOnlyTxtShort" id="unitName'+mycount+'" name="basic[equipment]['+mycount+'][unitName]">';
	var oTL5= oTR.insertCell([5]);
	oTL5.innerHTML =  '<select class="txtshort" name="basic[equipment]['+mycount+'][qualityCode]">'+$("#qualityList").val()+'</select>';
	var oTL6 = oTR.insertCell([6]);
	oTL6.innerHTML = '<select name="basic[equipment]['+mycount+'][testType]" id="testType'+mycount+'" class="txtshort"><option value="0">ȫ��</option><option value="1">���</option><option value="2">���</option></select>';
	var oTL7 = oTR.insertCell([7]);
	oTL7.innerHTML = '<font color=green id="exeNum'+mycount+'">��</font>';
	var oTL8 = oTR.insertCell([8]);
	oTL8.innerHTML ='<font color=green >��</font>';
	var oTL9 = oTR.insertCell([9]);
	oTL9.innerHTML = '<font color=green >0</font>';
	var oTL10 = oTR.insertCell([10]);
	oTL10.innerHTML = '<font color=green >0</font>';
	var oTL11 = oTR.insertCell([11]);
	oTL11.innerHTML = '<input type="text" class="txtshort" id="amountAll'+mycount+'" name="basic[equipment]['+mycount+'][amountAll]">';
	var oTL12 = oTR.insertCell([12]);
	oTL12.innerHTML = '<input type="text" class="readOnlyTxtShort" id="dateIssued'+mycount+'" name="basic[equipment]['+mycount+'][dateIssued]"  readonly >';
	$('#dateIssued'+mycount).val($('#sendTime').val());
	var oTL13 = oTR.insertCell([13]);
	oTL13.innerHTML = '<input type="text" class="txtshort" id="dateHope'+mycount+'" name="basic[equipment]['+mycount+'][dateHope]" onfocus="WdatePicker()" readonly >';
	var oTL14 = oTR.insertCell([14]);
	oTL14.innerHTML = '<input type="text" class="txt"  name="basic[equipment]['+mycount+'][remark]"/>'+
						 '<input type="hidden" name="basic[equipment]['+mycount+'][applyEquId]">'+
						 '<input type="hidden" name="basic[equipment]['+mycount+'][uniqueCode]">'+
						 '<input type="hidden" name="basic[equipment]['+mycount+'][equObjAssId]">'+
						 '<input type="hidden" id="purchType' + mycount + '" name="basic[equipment]['+mycount+'][purchType]"/> ';
	$('#purchType'+mycount).val($('#purchType1').val());

	var oTL15 = oTR.insertCell([15]);
	oTL15.innerHTML = '<img title="ɾ����" onclick="mydel(this , \'mytable\')" src="images/closeDiv.gif">';


	document.getElementById(countNumP).value = document
			.getElementById(countNumP).value
			* 1 + 1;

	$("#productNumb"+mycount).yxcombogrid_product({
    	hiddenId : 'productId'+ mycount,
		closeCheck : true,
		gridOptions : {
			showcheckbox : false,
			param:{'notStatTypeArr':'TJCP,TJBCP'},
			event : {
				'row_dblclick' : function(mycount){
					return function(e, row, data) {
//						$("#productNumb"+mycount).val(data.productCode);
						$("#unitName"+mycount).val(data.unitName);
						$("#pattem"+mycount).val(data.pattern);
						$("#productName"+mycount).val(data.productName);
						$.ajax({
							type : "POST",
							url : "?model=stock_inventoryinfo_inventoryinfo&action=getExeNum",
							data : {
								id : data.id
							},
							success : function(msg) {
								msg=msg*1;
								$('#exeNum'+mycount).html(msg);
							}
						});
					};
			  	}(mycount)
			}
		}
    });

	$("#productName"+mycount).yxcombogrid_product({
    	hiddenId : 'productId'+ mycount,
    	nameCol:'productName',
		closeCheck : true,
		gridOptions : {
			showcheckbox : false,
//			param:{'notStatTypeArr':'TJCP,TJBCP'},
			event : {
				'row_dblclick' : function(mycount){
					return function(e, row, data) {
						$("#productNumb"+mycount).val(data.productCode);
						$("#unitName"+mycount).val(data.unitName);
						$("#pattem"+mycount).val(data.pattern);
						$.ajax({
							type : "POST",
							url : "?model=stock_inventoryinfo_inventoryinfo&action=getExeNum",
							data : {
								id : data.id
							},
							success : function(msg) {
								msg=msg*1;
								$('#exeNum'+mycount).html(msg);
							}
						});
					};
			  	}(mycount)
			}
		}
    });
}

/*��̬�����(������������)*/
function dynamic_type_add(packinglist, countNumP) {

	var purchType=$("#purchType").val();
	var sendTime=$("#sendTime").val();
	var dateHope=$("#dateHope").val();
	var batchNumb=$.trim($("#batchNumb").val());
	if(batchNumb==""){
		alert("�����������κ�");
		return false;
	}
	mycount = document.getElementById(countNumP).value * 1 + 1;
	var packinglist = document.getElementById(packinglist);
	i = packinglist.rows.length;
	oTR = packinglist.insertRow([i]);
	var oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = '<img title="ɾ����" onclick="mydelProduce(this ,\'mytable\')" src="images/closeDiv.gif">';
	var oTL1 = oTR.insertCell([1]);
	oTL1.innerHTML = i+1;
	var oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML =  "<input type='text' class='txtshort' value='' id='productNumb"+mycount+"' name='basic[equipment][" + mycount+"][productNumb]' >";
	var oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML ="<input type='text' class='txtmiddle' value='' id='productName"+mycount+"' name='basic[equipment][" + mycount+"][productName]' />"+
						"<input type='hidden' class='productId' value='' id='productId"+mycount+"' name='basic[equipment][" + mycount+"][productId]'/>";
	var oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML ="<input type='text' class='readOnlyTxtShort' value='' id='productTypeName"+mycount+"' name='basic[equipment][" + mycount+"][productTypeName]' />"+
						"<input type='hidden' value='' id='productTypeId"+mycount+"' name='basic[equipment][" + mycount+"][productTypeId]'/>";
	var oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML = '<input type="text" class="readOnlyTxtShort" id="pattem'+mycount+'"  value="" name="basic[equipment]['+mycount+'][pattem]" readonly/>';
	var oTL6 = oTR.insertCell([6]);
	oTL6.innerHTML = '<input type="text" class="readOnlyTxtShort" id="unitName'+mycount+'"  value="" name="basic[equipment]['+mycount+'][unitName]" readonly/>';
	var oTL7 = oTR.insertCell([7]);
	oTL7.innerHTML = '<input type="text" class="readOnlyTxtShort" id="leastPackNum'+mycount+'"  value="" name="basic[equipment]['+mycount+'][leastPackNum]" readonly/>';
	var oTL8 = oTR.insertCell([8]);
	oTL8.innerHTML = '<input type="text" class="readOnlyTxtShort" id="leastOrderNum'+mycount+'"  value="" name="basic[equipment]['+mycount+'][leastOrderNum]" readonly/>';
	var oTL9= oTR.insertCell([9]);
	oTL9.innerHTML =  '<select class="txtshort" name="basic[equipment]['+mycount+'][qualityCode]">'+$("#qualityList").val()+'</select>';
	var oTL10 = oTR.insertCell([10]);
	oTL10.innerHTML = '<input type="text" class="readOnlyTxtShort" id="stockNum'+mycount+'"  value="" readonly/>';
	var oTL11= oTR.insertCell([11]);
	oTL11.innerHTML = '<input type="text" class="amount txtshort" id="amountAll'+mycount+'" onblur="checkNum(this);" value="" name="basic[equipment]['+mycount+'][amountAll]">'+
						'<input type="hidden" value="" name="amountAll">';
	var oTL12 = oTR.insertCell([12]);
	oTL12.innerHTML = '<input type="text" class="readOnlyTxtShort"  value="'+sendTime+'" name="basic[equipment]['+mycount+'][dateIssued]" class="txt">';
	var oTL13 = oTR.insertCell([13]);
	oTL13.innerHTML = '<input type="text" class="txtshort" readonly="" onfocus="WdatePicker()" value="'+dateHope+'" name="basic[equipment]['+mycount+'][dateHope]" class="txt">';
	var oTL14 = oTR.insertCell([14]);
	oTL14.innerHTML = '<input type="text" class="txtshort"  name="basic[equipment]['+mycount+'][remark]"/>'+
						 '<input type="hidden" value="'+purchType+'" name="basic[equipment]['+mycount+'][purchType]">'+
						 '<input id="equObjAssId" type="hidden" value="" name="basic[equipment]['+mycount+'][equObjAssId]">';

	document.getElementById(countNumP).value = document
			.getElementById(countNumP).value
			* 1 + 1;

	$("#productNumb"+mycount).yxcombogrid_product({
    	hiddenId : 'productId'+ mycount,
		closeCheck : true,
		gridOptions : {
			showcheckbox : false,
//			param:{'notStatTypeArr':'TJCP,TJBCP'},
			event : {
				'row_dblclick' : function(mycount){
					return function(e, row, data) {
//						$("#productNumb"+mycount).val(data.productCode);
						$("#unitName"+mycount).val(data.unitName);
						$("#pattem"+mycount).val(data.pattern);
						$("#productName"+mycount).val(data.productName);
						$("#productTypeId"+mycount).val(data.proTypeId);
						$("#productTypeName"+mycount).val(data.proType);
						$("#leastPackNum"+mycount).val(data.leastPackNum);
						$("#leastOrderNum"+mycount).val(data.leastOrderNum);
						$("#amountAll"+mycount).val(data.leastOrderNum);
						$.ajax({
							type : "POST",
							url : "?model=purchase_external_external&action=ajaxGetStockNum",
							data : {productId : data.id},
							success : function(msg) {
								$("#stockNum"+mycount).val(msg);
							}
						});

					};
			  	}(mycount)
			}
		}
    });

	$("#productName"+mycount).yxcombogrid_product({
    	hiddenId : 'productId'+ mycount,
    	nameCol:'productName',
		closeCheck : true,
		gridOptions : {
			showcheckbox : false,
//			param:{'notStatTypeArr':'TJCP,TJBCP'},
			event : {
				'row_dblclick' : function(mycount){
					return function(e, row, data) {
						$("#productNumb"+mycount).val(data.productCode);
						$("#unitName"+mycount).val(data.unitName);
						$("#pattem"+mycount).val(data.pattern);
						$("#productTypeId"+mycount).val(data.proTypeId);
						$("#productTypeName"+mycount).val(data.proType);
						$("#leastPackNum"+mycount).val(data.leastPackNum);
						$("#leastOrderNum"+mycount).val(data.leastOrderNum);
						$("#amountAll"+mycount).val(data.leastOrderNum);
						$.ajax({
							type : "POST",
							url : "?model=purchase_external_external&action=ajaxGetStockNum",
							data : {productId : data.id},
							success : function(msg) {
								$("#stockNum"+mycount).val(msg);
							}
						});
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

//�ж��´�ɹ��ƻ��Ĳɹ������Ƿ�Ϸ�
function addPlan(obj){
		var thisVal = parseInt( $(obj).val() );
		var nextVal = parseInt( $(obj).next().val() );
		if(isNaN(obj.value.replace(/,|\s/g,''))){
			alert("����������");
			$(obj).attr("value",nextVal);
		}
		else if(thisVal>nextVal){
//			if(!confirm("ȷ������ԭ�ƻ�����"+nextVal+"?")){
//				$(obj).attr("value",nextVal);
//			}
			alert("���ɳ��������´�����"+nextVal);
			$(obj).attr("value",nextVal);
		}else if(thisVal<0){
			alert("��������ȷ������,����Ϊ�ջ���С��1");
			$(obj).attr("value"," ");
//			$(obj).focus();
		}
}

//�ж��´�ɹ��ƻ��Ĳɹ������Ƿ�Ϸ�
function checkNum(obj){
		var thisVal = parseInt( $(obj).val() );;
		if(isNaN(obj.value.replace(/,|\s/g,''))){
			alert("����������");
			obj.value="";
			$(obj).focus();
		}
		if(thisVal<0){
			alert("��������ȷ������,����Ϊ�ջ���С��1");
			$(obj).attr("value"," ");
			$(obj).focus();
		}
}

//�ж��´�ɹ��ƻ��Ĳɹ������Ƿ�Ϸ�
function checkNumForNew(obj){
    var thisVal = parseInt( $(obj).val() );;
    if(isNaN(obj.value.replace(/,|\s/g,''))){
        alert("����������");
        obj.value="";
        $(obj).focus();
    }
    if(thisVal<0){
        alert("��������ȷ������,����Ϊ�ջ���С��1");
        $(obj).attr("value"," ");
        $(obj).focus();
    }
    var itemId=$(obj).attr("idatr");
    if(itemId>0){
        var applyAmountAll=$("#applyAmount"+itemId).val();
        var amountSum = 0;
        var cmps = $("input[idatr='"+itemId+"']");
        cmps.each(function() {
            amountSum = accAdd(amountSum, $(this).val());
        });
        if(amountSum>applyAmountAll){
            alert("�����ϲɹ���������ԭ������������"+applyAmountAll);
            $(obj).attr("value"," ");
        }

    }
}

// ����
function saveSubmit(){
	$('#form1').attr("action","index1.php?model=purchase_external_external&action=add");
}

function saveSubmitEdit(){
	$('#form1').attr("action","?model=purchase_external_external&action=edit");
}

//��Ӳɹ�����ʱ�ύ����
function submitAudit(){
	$("#ismail").val("1");
	document.getElementById('form1').action = "index1.php?model=purchase_external_external&action=toSubmitAudit";
}

//�޸Ĳɹ�����ʱ�ύ����
function submitAuditByEdit(){
	document.getElementById('form1').action = "index1.php?model=purchase_external_external&action=toSubmitAuditByEdit";
}


//�ύʱ�������������Ƿ�����
function checkAllData(){
	var booble=true;
	$("input.amount").each(function(){
		if ($(this).val()==""||$(this).val()==0) {
			alert("����������,����Ϊ�ջ���С��1");
			$(this).focus();
			booble=false;
			return false;
		}
	});
	$("input[id^='inputProductName']").each(function(){
		if ($(this).val().trim()=="") {
			if($(this).parent().parent().children().next().next().children().val()){
				return true;
			}
			alert("������Ϣ����������ѡ������");
			$(this).focus();
			booble=false;
			return false;
		}
	});
	if(!$("input.amount").length>0){   //�ж��Ƿ�ѡ��������
		alert("�ɹ������嵥Ϊ��,��ѡ����Ҫ�ɹ������ϡ�");
		booble=false;
	}

	// ������Ϣ��ͨ����֤��,����´������Ƿ���Ч
	var allEqu = {};
	var ids = '';
	var Amount = $("input:[id^='list_amountAll']");
	var equId= $("input:[id^='list_applyEquId']");
	if(equId.length > 0){
		$.each(equId,function(i,item){
			allEqu[$(item).val()] = $(Amount[i]).val();
			ids += $(item).val()+",";
		});
	}

	if(booble){
		var responseText = $.ajax({
			url: "?model=purchase_external_external&action=ajaxChkAmountAllOld",
			data : {chkData : allEqu,equIds : ids},
			type : "POST",
			async : false
		}).responseText;
		var res = eval("("+responseText+")");
		if(res.msg != "ok"){
			if(typeof(res.result) == "string"){
				alert(res.result);
			}else{
				var resObj = res.result;
				var errorStr = "�´�ʧ��! ";
				$.each(resObj,function(i,item){
					errorStr += "����"+item.productNo+"���´�ɹ�����Ϊ"+item.issuedPurNum+", ";
				});
				errorStr += "��ȷ�Ϻ����ԡ�";
				alert(errorStr);
			}
			booble=false;
			return false;
		}
	}

	return booble;
}


/**
 * ��ʼ����Ʒѡ�����
 *
 * @param {}
 *            mycount
 */
function processProductCmp(mycount) {
	var $productNumb = $("#productNumb" + mycount);
	var $productName = $("#productName" + mycount);
	$productNumb.yxcombogrid_product("remove");
	$productName.yxcombogrid_product("remove");
	// $productNumb.show();
	// $productName.show();
	$productNumb.yxcombogrid_product({
		hiddenId : 'productId' + mycount,
		closeCheck : true,
		gridOptions : {
			showcheckbox : false,
//					param : {
//						'notStatTypeArr' : 'TJCP,TJBCP'
//					},
			event : {
				'row_dblclick' : function(mycount) {
					return function(e, row, data) {
						// $("#productNumb"+mycount).val(data.productCode);
						$("#unitName" + mycount).val(data.unitName);
						$("#pattem" + mycount).val(data.pattern);
						$("#productName" + mycount)
								.val(data.productName);
					};
				}(mycount)
			}
		}
	});

	$productName.yxcombogrid_product({
		hiddenId : 'productId' + mycount,
		nameCol : 'productName',
		focusoutCheckAction : false,
		closeCheck : true,
		gridOptions : {
			showcheckbox : false,
//					param : {
//						'notStatTypeArr' : 'TJCP,TJBCP'
//					},
			event : {
				'row_dblclick' : function(mycount) {
					return function(e, row, data) {
						$("#productNumb" + mycount)
								.val(data.productCode);
						$("#unitName" + mycount).val(data.unitName);
						$("#pattem" + mycount).val(data.pattern);
					};
				}(mycount)
			}
		}
	});
}

/**
 * ��ʼ����Ʒѡ�����
 *
 * @param {}
 *            mycount
 */
function processInputProductCmp(mycount) {
    var $inputProductName = $("#inputProductName" + mycount);
    $inputProductName.yxcombogrid_inputproduct("remove");
    $inputProductName.yxcombogrid_inputproduct({
        hiddenId : 'itemId' + mycount,
        closeCheck : false,
        isFocusoutCheck: false,
        gridOptions : {
            showcheckbox : false,
					param : {
						'idArr' : $("#itemIds").val()
					},
            event : {
                'row_dblclick' : function(mycount) {
                    return function(e, row, data) {
                        $("#productCategoryName" + mycount).val(data.productCategoryName);
                        $("#applyAmount" + mycount).attr("idatr",data.id);
                        if(  $("#applyAmount" + mycount).val()>0){
                            var applyAmountAll=$("#applyAmount"+data.id).val();
                            var amountSum = 0;
                            var cmps = $("input[idatr='"+data.id+"']");
                            cmps.each(function() {
                                amountSum = accAdd(amountSum, $(this).val());
                            });
                            if(amountSum>applyAmountAll){
                                alert("�����ϲɹ���������ԭ������������"+applyAmountAll);
                                $("#applyAmount" + mycount).val("");
                            }
                        }
                    };
                }(mycount)
            }
        }
    });
}

/**
 * �ݻٲ�Ʒ���
 *
 * @param {}
 *            mycount
 */
function removeProductCmp(mycount) {
	var $productNumb = $("#productNumb" + mycount);
	var $productName = $("#productName" + mycount);
	$productNumb.yxcombogrid_product("remove");
	// $productNumb.hide();
	$productName.yxcombogrid_product("remove");
}

