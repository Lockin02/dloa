// *****************删除和序号排列******************************

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




// **********采购合同的设备***********************

function equ_add(mytra, countNum, basicNumb) {

    mycount = document.getElementById(countNum).value * 1 + 1;

    var mytra = document.getElementById(mytra);

    i = mytra.rows.length;

    //这是额外添加的。用于获取采购合同的业务对象编号
    //applyNumb = document.getElementById(applyNumb).value;
   // basicNumb = document.getElementById(basicNumb).value;

    oTR = mytra.insertRow([i]);

    oTR.className = "TableData";

    oTR.align = "center";

    oTR.height = "30px";

    oTL0 = oTR.insertCell([0]);

    oTL0.innerHTML = i;

    oTL1 = oTR.insertCell([1]);

    oTL1.innerHTML = "<input class='txtshort' type='text' name='contract[temp][" + mycount

            + "][productNumb]' id='productNumb" + mycount + "'>";

    oTL2 = oTR.insertCell([2]);

    oTL2.innerHTML = "<input class='txtshort' type='text' name='contract[temp][" + mycount

            + "][productName]' id='productName" + mycount

            + "' size='10' >" +
			"<input type='hidden' id='productId"+mycount+"' name='contract[temp][" + mycount+"][productId]'>";

    oTL3 = oTR.insertCell([3]);

    oTL3.innerHTML =  "<input type='text' class='readOnlyTxtShort' value='' name='contract[temp][" + mycount

    		+ "][pattem]' id='pattem" + mycount + "'>";

    oTL4 = oTR.insertCell([4]);

    oTL4.innerHTML =   "<input type='text' value='' class='readOnlyTxtShort' name='contract[temp][" + mycount

    		+ "][units]' id='units" + mycount + "'>";

    oTL5 = oTR.insertCell([5]);

    oTL5.innerHTML = "<input class='amount txtmin' type='text' name='contract[temp][" + mycount

    		+ "][amountAll]' id='amountAll" + mycount + "'  onblur='sumAllMoney();'>";

	oTL6 = oTR.insertCell([6]);

    oTL6.innerHTML = "<label>0</label>"+
			"<input type='hidden' id='amountIssued"+mycount+"' value='0' name='contract[temp][" + mycount+"][amountIssued]'>";

    oTL7 = oTR.insertCell([7]);

    oTL7.innerHTML = "<input class='txtshort' type='text' name='contract[temp][" + mycount

    		+ "][dateHope]' id='dateHope" + mycount + "' onfocus='WdatePicker()' readonly>";

    oTL8 = oTR.insertCell([8]);

    oTL8.innerHTML = "<input class='txtshort' type='text' name='contract[temp][" + mycount

    		+ "][dateIssued]' id='dateIssued" + mycount + "' onfocus='WdatePicker()' readonly>";

    var oTL9 = oTR.insertCell([9]);
	oTL9.innerHTML =  '<input type="text" class="readOnlyTxtItem price" id="price'+mycount+'"  value="" name="contract[temp]['+mycount+'][price]" />';
	 var oTL10 = oTR.insertCell([10]);

    oTL10.innerHTML = '<input type="text" class="txtshort price" id="applyPrice'+mycount+'"  value="" name="contract[temp]['+mycount+'][applyPrice]" />';
		var oTL11 = oTR.insertCell([11]);
	oTL11.innerHTML =  '<select type="text" class="txtshort price" id="taxRate'+mycount+'" name="contract[temp]['+mycount+'][taxRate]" >'+$("#taxRate").val()+'</select>';
	var oTL12 = oTR.insertCell([12]);
	oTL12.innerHTML =  '<input type="text" class="txtshort" id="moneyAll'+mycount+'"  value="" name="contract[temp]['+mycount+'][moneyAll]" />';
	var oTL13 = oTR.insertCell([13]);
	oTL13.innerHTML =  '<select class="" id="purchType'+mycount+'"  value="" name="contract[temp]['+mycount+'][purchType]" >' +
			'<option value="HTLX-XSHT">销售合同采购</option>' +
			'<option value="HTLX-FWHT">服务合同采购</option>' +
			'<option value="HTLX-ZLHT">租赁合同采购</option>' +
			'<option value="HTLX-YFHT">研发合同采购</option>' +
			'<option value="stock">补库采购</option>' +
			'<option value="rdproject">研发采购</option>' +
			'<option value="assets">资产采购</option>' +
			'<option value="produce">生产采购</option>' +
			'</select';

    oTL14 = oTR.insertCell([14]);

    oTL14.innerHTML = "<input class='txtshort' type='text' name='contract[temp][" + mycount

            + "][applyDeptName]' id='applyDeptName" + mycount

            + "' size='10' title='双击选择部门'>" +

            "<input type='hidden' value='' id='applyDeptId"+mycount+"' name='contract[temp][" + mycount  + "][applyDeptId]'";

    oTL15 = oTR.insertCell([15]);

    oTL15.innerHTML = "<input class='txtshort' type='text' name='contract[temp][" + mycount

    		+ "][sourceNumb]' id='sourceNumb" + mycount + "'>";
    oTL16 = oTR.insertCell([16]);

    oTL16.innerHTML = "<input class='txtshort' type='text' name='contract[temp][" + mycount

    		+ "][rObjCode]' id='rObjCode" + mycount + "'>";

    oTL17 = oTR.insertCell([17]);

    oTL17.innerHTML = "<input name='contract[temp][" + mycount

            + "][remark]' class='txtshort all'></input><img src='images/closeDiv.gif' onclick='mydel(this,\""

            + mytra.id + "\")' title='删除行'>";

    document.getElementById(countNum).value = document.getElementById(countNum).value

            * 1 + 1;
//	createFormatOnClick('moneyAll' + mycount, 'amountAll' + mycount, 'applyPrice'
//			+ mycount, 'moneyAll' + mycount);
	createFormatOnClick('moneyAll' + mycount);
	createFormatOnClick('price' + mycount);
	// 千分位带计算
	createFormatOnClick('amountAll' + mycount, 'amountAll' + mycount, 'applyPrice'
			+ mycount, 'moneyAll' + mycount);
	createFormatOnClick('applyPrice' + mycount, 'amountAll' + mycount, 'applyPrice'
			+ mycount, 'moneyAll' + mycount);

    $("#applyPrice"+mycount+"_v").bind('blur',function(){sumPrice(mycount);sumAllMoney();});

    $("#amountAll"+mycount+"_v").bind('blur',function(){sumAllMoney();});
	 $("#moneyAll"+mycount+"_v").bind('blur',function(){sumAllMoney();});

	 $("#taxRate"+mycount).bind('change',function(){sumPrice(mycount);sumAllMoney();});


	$("#productNumb"+mycount).yxcombogrid_product({
    	hiddenId : 'productId'+ mycount,
		closeCheck : true,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(mycount){
					return function(e, row, data) {
						$("#productNumb"+mycount).val(data.productCode);
						$("#productName"+mycount).val(data.productName);
						$("#pattem"+mycount).val(data.pattern);
						$("#units"+mycount).val(data.unitName);
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
			event : {
				'row_dblclick' : function(mycount){
					return function(e, row, data) {
						$("#productNumb"+mycount).val(data.productCode);
						$("#productName"+mycount).val(data.productName);
						$("#pattem"+mycount).val(data.pattern);
						$("#units"+mycount).val(data.unitName);
					};
			  	}(mycount)
			}
		}
    });
	$("#applyDeptName"+mycount).yxselect_dept({
				hiddenId : 'applyDeptId'+mycount
			});
}