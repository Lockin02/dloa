

var cglbdata = getData("CGWLLB");

/**
 * 研发采购
 *
 * @param {}
 *            packinglist
 * @param {}
 *            countNumP
 */
function dynamic_add_rd(packinglist, countNumP) {

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
	var $checkbox = $("<input type='checkbox' checked=true value='1' id='isProduce"
			+ mycount
			+ "' name='basic[equipment]["
			+ mycount
			+ "][isProduce]' />");
	$(oTL2).append($checkbox);

	$checkbox.click(function(mycount) {
				return function() {
					var checked = $(this).attr("checked");

					if (!checked) {
						$("#productName" + mycount).attr("readonly", false);
					} else {
						var productId = $("#productId" + mycount).val();
						if (!productId) {
							$("#productName" + mycount).val("");
						}
						$("#productName" + mycount).attr("readonly", true);
					}
				}
			}(mycount));
	// var oTL3 = oTR.insertCell([3]);
	// var $checkbox = $('<input type="checkbox" id="isCheck' + mycount + '"
	// >');
	// $checkbox.click(function(mycount) {
	// return function() {
	// var checked = $(this).attr("checked");
	// if (checked) {
	// removeProductCmp(mycount);
	// } else {
	// processProductCmp(mycount);
	// }
	// }
	// }(mycount));
	// $(oTL3).append($checkbox);
	var oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML = "<input type='text' class='txtshort' value='' id='productNumb"
			+ mycount
			+ "' name='basic[equipment]["
			+ mycount
			+ "][productNumb]' >";
	var oTL4 = oTR.insertCell([4]);
	var $productName = $("<input type='text' class='txt' value='' id='productName"
			+ mycount
			+ "' name='basic[equipment]["
			+ mycount
			+ "][productName]' />"
			+ "<input type='hidden' class='productId' value='' id='productId"
			+ mycount
			+ "' name='basic[equipment]["
			+ mycount
			+ "][productId]'/>");
	$(oTL4).append($productName);
	$productName.change(function(mycount) {
				return function() {
					$("#productNumb" + mycount).val("");
					$("#productId" + mycount).val("");
				}
			}(mycount));
	var oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML = '<input type="text" class="readOnlyTxtItem" id="pattem'
			+ mycount + '"  value="" name="basic[equipment][' + mycount
			+ '][pattem]" readonly/>';
	var oTL6 = oTR.insertCell([6]);
	oTL6.innerHTML = '<input type="text" class="readOnlyTxtItem" id="unitName'
			+ mycount + '"  value="" name="basic[equipment][' + mycount
			+ '][unitName]" readonly/>';

	// var oTL3 = oTR.insertCell([3]);
	// oTL3.innerHTML = "<input type='text' class='txt' value=''
	// id='inputProductName" + mycount + "' name='basic[equipment][" + mycount
	// + "][inputProductName]' />";
var oTL7= oTR.insertCell([7]);
	oTL7.innerHTML =  '<select class="txtshort" name="basic[equipment]['+mycount+'][qualityCode]">'+$("#qualityList").val()+'</select>';
	var oTL8 = oTR.insertCell([8]);
	oTL8.innerHTML = '<input type="text" class="amount txtshort" onblur="checkNum(this);" value="" name="basic[equipment]['
			+ mycount
			+ '][amountAll]">'
			+ '<input type="hidden" value="" name="amountAll">';
	var oTL9 = oTR.insertCell([9]);
	oTL9.innerHTML = '<input type="text" class="readOnlyTxtShort" value="'
			+ sendTime + '" name="basic[equipment][' + mycount
			+ '][dateIssued]" class="txt">';
	var oTL10 = oTR.insertCell([10]);
	oTL10.innerHTML = '<input type="text" class="txtshort" readonly="" onfocus="WdatePicker()" value="'
			+ dateHope
			+ '" name="basic[equipment]['
			+ mycount
			+ '][dateHope]" class="txt">';
	var oTL11 = oTR.insertCell([11]);
	oTL11.innerHTML = '<input type="text" class="txt"  name="basic[equipment]['
			+ mycount
			+ '][remark]"/>'
			+ '<input type="hidden" value="'
			+ purchType
			+ '" name="basic[equipment]['
			+ mycount
			+ '][purchType]">'
			+ '<input id="equObjAssId" type="hidden" value="" name="basic[equipment]['
			+ mycount + '][equObjAssId]">';
	var oTL12 = oTR.insertCell([12]);
	oTL12.innerHTML = '<img title="删除行" onclick="mydel(this , \'mytable\')" src="images/closeDiv.gif">';

	document.getElementById(countNumP).value = document
			.getElementById(countNumP).value
			* 1 + 1;
	processProductCmp(mycount);

}

// add by chengl
$(function() {
			// 固定资产或者研发采购，初始化物料类别
			$("select[id^=productCategoryCode]").each(function() {
						addDataToSelect(cglbdata, this.id);
						$(this).val($(this).attr('val'));
					})

		})