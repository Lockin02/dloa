//$(function() {
//	$("#supplierName").yxcombogrid_supplier({
//				hiddenId : 'supplierId',
//				gridOptions : {
//					showcheckbox : false,
//					event : {
//						'row_dblclick' : function(e, row, data) {
//							//alert(data.Prov);
//						},
//						'row_click' : function() {
//							// alert(123)
//						},
//						'row_rclick' : function() {
//							// alert(222)
//						}
//					}
//				}
//			});
//});


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
		sumAllMoney();
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
	oTL1.innerHTML =  "<input type='text' class='txtshort' value='' id='productNumb"+mycount+"' name='contract[equs][" + mycount+"][productNumb]'>";
	var oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML ="<input type='text' class='txtshort' value='' id='productName"+mycount+"' name='contract[equs][" + mycount+"][productName]'>"+
						"<input type='hidden' class='productId' id='productId"+mycount+"' name='contract[equs][" + mycount+"][productId]'>";
	var oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML = "<input type='text' class='readOnlyTxtShort' id='productModel"+mycount+"' value='' name='contract[equs]["+mycount+"][pattem]'>";
	var oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML = '<input type="text" class="readOnlyTxtShort" id="unitName'+mycount+'"   value="" name="contract[equs]['+mycount+'][units]" >';
	var oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML = '<input type="text" class="amount txtshort" id="amountAll'+mycount+'"  value="" onblur="checkNum(this);sumAllMoney();" name="contract[equs]['+mycount+'][amountAll]">';
	var oTL6 = oTR.insertCell([6]);
	oTL6.innerHTML =  '<input type="text" class="txtshort" onfocus="WdatePicker()"  value="" name="contract[equs]['+mycount+'][dateHope]">';
	var oTL7 = oTR.insertCell([7]);
	oTL7.innerHTML =  '<input type="text" class="txtshort" id="dateIssued'+mycount+'" onfocus="WdatePicker()" value="" name="contract[equs]['+mycount+'][dateIssued]">';
	var oTL8 = oTR.insertCell([8]);
	oTL8.innerHTML =  '<input type="text" class="readOnlyTxtItem price" id="price'+mycount+'"  value="" name="contract[equs]['+mycount+'][price]" />';
	var oTL9 = oTR.insertCell([9]);
	oTL9.innerHTML =  '<input type="text" class="txtshort price" id="applyPrice'+mycount+'"  value="" name="contract[equs]['+mycount+'][applyPrice]" />';
		var oTL10 = oTR.insertCell([10]);
	oTL10.innerHTML =  '<select type="text" class="txtshort price" id="taxRate'+mycount+'" name="contract[equs]['+mycount+'][taxRate]" >'+$("#taxRate").val()+'</select>';
	var oTL11 = oTR.insertCell([11]);
	oTL11.innerHTML =  '<input type="text" class="readOnlyTxtShort" id="moneyAll'+mycount+'"  value="" name="contract[equs]['+mycount+'][moneyAll]" />';
	var oTL12 = oTR.insertCell([12]);
	oTL12.innerHTML =  '<select class="" id="purchType'+mycount+'"  value="" name="contract[equs]['+mycount+'][purchType]" >' +
			'<option value="HTLX-XSHT">销售合同采购</option>' +
			'<option value="HTLX-FWHT">服务合同采购</option>' +
			'<option value="HTLX-ZLHT">租赁合同采购</option>' +
			'<option value="HTLX-YFHT">研发合同采购</option>' +
			'<option value="stock">补库采购</option>' +
			'<option value="rdproject">研发采购</option>' +
			'<option value="assets">资产采购</option>' +
			'<option value="produce">生产采购</option>' +
			'</select';
    oTL13 = oTR.insertCell([13]);
    oTL13.innerHTML = "<input class='txtshort' type='text' name='contract[equs][" + mycount
            + "][applyDeptName]' id='applyDeptName" + mycount
            + "' size='10' title='双击选择部门' readonly>" +
            "<input type='hidden' value='' id='applyDeptId"+mycount+"' name='contract[equs][" + mycount  + "][applyDeptId]'/>";
    oTL14 = oTR.insertCell([14]);
    oTL14.innerHTML = "<input class='txtshort' type='text' name='contract[equs][" + mycount
    		+ "][sourceNumb]' id='sourceNumb" + mycount + "'>";
	var oTL15 = oTR.insertCell([15]);
	oTL15.innerHTML =  '<input type="text" class="txtshort" readonly="" value="" name="contract[equs]['+mycount+'][remark]">';
	var oTL16 = oTR.insertCell([16]);
	oTL16.innerHTML = '<img title="删除行" onclick="mydel(this , \'mytable\')" src="images/closeDiv.gif">';

	document.getElementById(countNumP).value = document
			.getElementById(countNumP).value
			* 1 + 1;
//	createFormatOnClick('moneyAll' + mycount, 'amountAll' + mycount, 'applyPrice'
//			+ mycount, 'moneyAll' + mycount);
	createFormatOnClick('moneyAll' + mycount);
	createFormatOnClick('price' + mycount);
//    $("#applyPrice"+mycount+"_v").bind('blur',function(){sumPrice(mycount);sumAllMoney();});


	$("#moneyAll"+mycount+"_v").bind('blur',function(){sumAllMoney();});
	// 千分位带计算
	createFormatOnClick('amountAll' + mycount, 'amountAll' + mycount, 'applyPrice'
			+ mycount, 'moneyAll' + mycount);
	$("#amountAll"+mycount+"_v").bind('blur',function(){sumAllMoney();});
	createFormatOnClick('applyPrice' + mycount, 'amountAll' + mycount, 'applyPrice'
			+ mycount, 'moneyAll' + mycount,6);
 	$("#applyPrice"+mycount + "_v").bind('blur',function(){sumPrice(mycount);sumAllMoney();});

	 $("#taxRate"+mycount).bind('change',function(){sumPrice(mycount);sumAllMoney();});

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
}