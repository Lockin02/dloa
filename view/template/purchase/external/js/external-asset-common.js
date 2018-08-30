

var cglbdata = getData("CGWLLB");
/** ********************资产采购直接填写物料************************ */
function dynamic_add_simple(packinglist, countNumP) {

    var purchType = $("#purchType").val();
    var sendTime = $("#sendTime").val();
    var dateHope = $("#dateHope").val();
    mycount = document.getElementById(countNumP).value * 1 + 1;
    var packinglist = document.getElementById(packinglist);
    i = packinglist.rows.length;
    oTR = packinglist.insertRow([i]);
    var oTL0 = oTR.insertCell([0]);
    oTL0.innerHTML = i + 1;

    var oTL1 = oTR.insertCell([1]);
    oTL1.innerHTML = "<select id='productCategoryCode" + mycount
        + "' name='basic[equipment][" + mycount
        + "][productCategoryCode]'><select>";

    addDataToSelect(cglbdata, 'productCategoryCode' + mycount);

    var oTL2 = oTR.insertCell([2]);
    oTL2.innerHTML = "<input type='text' class='readOnlyTxtItem' value='' id='productNumb"
        + mycount
        + "' name='basic[equipment]["
        + mycount
        + "][productNumb]' />";



    var oTL3 = oTR.insertCell([3]);
    oTL3.innerHTML = "<input type='text' class='readOnlyTxtItem' value='' id='productName"
        + mycount
        + "' name='basic[equipment]["
        + mycount
        + "][productName]' /><input type='hidden' class='txt' value='' id='productId"
        + mycount
        + "' name='basic[equipment]["
        + mycount
        + "][productId]' /><input type='hidden' class='readOnlyTxtItem' value='' id='pattem"
        + mycount
        + "' name='basic[equipment]["
        + mycount
        + "][pattem]' /><input type='hidden' class='readOnlyTxtItem' value='' id='unitName"
        + mycount
        + "' name='basic[equipment]["
        + mycount
        + "][unitName]' />";

    processProductCmp(mycount);
//
//	var oTL4 = oTR.insertCell([4]);
//	oTL4.innerHTML = "<input type='text' class='readOnlyTxtItem' value='' id='pattem"
//			+ mycount
//			+ "' name='basic[equipment]["
//			+ mycount
//			+ "][pattem]' />";


    var oTL4 = oTR.insertCell([4]);
    oTL4.innerHTML = "<input type='text' class='txt' value='' id='inputProductName"
        + mycount
        + "' name='basic[equipment]["
        + mycount
        + "][inputProductName]' />";
    var oTL5= oTR.insertCell([5]);
    oTL5.innerHTML =  '<select class="txtshort" name="basic[equipment]['+mycount+'][qualityCode]">'+$("#qualityList").val()+'</select>';
    var oTL6 = oTR.insertCell([6]);
    oTL6.innerHTML = '<input type="text" class="amount txtshort" onblur="checkNum(this);" value="" name="basic[equipment]['
        + mycount
        + '][amountAll]">'
        + '<input type="hidden" value="" name="amountAll"><input type="hidden" class="readOnlyTxtShort" value="'
        + sendTime + '" name="basic[equipment][' + mycount
        + '][dateIssued]" class="txt">';
//	var oTL7 = oTR.insertCell([7]);
//	oTL7.innerHTML = '<input type="hidden" class="readOnlyTxtShort" value="'
//			+ sendTime + '" name="basic[equipment][' + mycount
//			+ '][dateIssued]" class="txt">';
    var oTL7 = oTR.insertCell([7]);
    oTL7.innerHTML = '<input type="text" class="txtshort" readonly="" onfocus="WdatePicker()" value="'
        + dateHope
        + '" name="basic[equipment]['
        + mycount
        + '][dateHope]" class="txt">';
    var oTL8 = oTR.insertCell([8]);
    oTL8.innerHTML = '<input type="text" class="txt"  name="basic[equipment]['
        + mycount
        + '][remark]"/>'
        + '<input type="hidden" value="'
        + purchType
        + '" name="basic[equipment]['
        + mycount
        + '][purchType]">'
        + '<input id="equObjAssId" type="hidden" value="" name="basic[equipment]['
        + mycount + '][equObjAssId]">';
    var oTL9 = oTR.insertCell([9]);
    oTL9.innerHTML = '<img title="删除行" onclick="mydel(this , \'mytable\')" src="images/closeDiv.gif">';

    document.getElementById(countNumP).value = document
        .getElementById(countNumP).value
        * 1 + 1;

}

/** ********************资产采购直接填写物料************************ */
function dynamic_add_confirm(packinglist, countNumP) {

    var purchType = $("#purchType").val();
    mycount = document.getElementById(countNumP).value * 1 + 1;
    var packinglist = document.getElementById(packinglist);
    i = packinglist.rows.length;
    oTR = packinglist.insertRow([i]);
    var oTL0 = oTR.insertCell([0]);
    oTL0.innerHTML = i + 1;

    var oTL1 = oTR.insertCell([1]);
    oTL1.innerHTML = '<input type="text" class=" readOnlyTxtShort "value="" id="productCategoryName'+mycount+'" name="apply[equipment]['
        + mycount
        + '][productCategoryName]">';

    var oTL2 = oTR.insertCell([2]);
    oTL2.innerHTML = "<input type='text' class='txt' value='' id='inputProductName"
        + mycount
        + "' name='apply[equipment]["
        + mycount
        + "][inputProductName]' /><input type='hidden' class='txt' value='' id='itemId"
        + mycount
        + "' name='apply[equipment]["
        + mycount
        + "][itemId]' />";


    processInputProductCmp(mycount);
    var oTL3 = oTR.insertCell([3]);
    oTL3.innerHTML = "<input type='text' class='txtshort' value='' id='productNumb"
        + mycount
        + "' name='apply[equipment]["
        + mycount
        + "][productNumb]' /><input type='hidden' class='txt' value='' id='productId"
        + mycount
        + "' name='apply[equipment]["
        + mycount
        + "][productId]' />";
    var oTL4 = oTR.insertCell([4]);
    oTL4.innerHTML = "<input type='text' class='txt' value='' id='productName"
        + mycount
        + "' name='apply[equipment]["
        + mycount
        + "][productName]' />";

    processProductCmp(mycount);
    var oTL5 = oTR.insertCell([5]);
    oTL5.innerHTML = "<input type='text' class='readOnlyTxtItem' value='' id='pattem"
        + mycount
        + "' name='apply[equipment]["
        + mycount
        + "][pattem]' />";
    var oTL6 = oTR.insertCell([6]);
    oTL6.innerHTML = '<input type="text" class=" readOnlyTxtShort "value="" name="apply[equipment]['
        + mycount
        + '][unitName]">';
//	var oTL7 = oTR.insertCell([7]);
//	oTL7.innerHTML = '<input type="hidden" class="readOnlyTxtShort" value="'
//			+ sendTime + '" name="basic[equipment][' + mycount
//			+ '][dateIssued]" class="txt">';
    var oTL7 = oTR.insertCell([7]);
    oTL7.innerHTML = '<input type="text" class="amount txtshort" value="" idatr=""  onblur="checkNumForNew(this);"   id="applyAmount'
        + mycount
        + '" name="apply[equipment]['
        + mycount
        + '][applyAmount]" class="txt">';
    var oTL8 = oTR.insertCell([8]);
    oTL8.innerHTML = '<input type="text" class="txtshort" readonly="" onfocus="WdatePicker()" value="" name="apply[equipment]['
        + mycount
        + '][dateHope]" class="txt">';
    var oTL9 = oTR.insertCell([9]);
    oTL9.innerHTML = '<input type="text" class="txt"  name="apply[equipment]['
        + mycount
        + '][remark]"/>';
    var oTL10 = oTR.insertCell([10]);
    oTL10.innerHTML = '<img title="删除行" onclick="mydel(this , \'mytable\')" src="images/closeDiv.gif">';

    document.getElementById(countNumP).value = document
        .getElementById(countNumP).value
        * 1 + 1;

}


// add by chengl
$(function() {
			// 固定资产或者研发采购，初始化物料类别
			$("select[id^=productCategoryCode]").each(function() {
						addDataToSelect(cglbdata, this.id);
						$(this).val($(this).attr('val'));
					})

		})