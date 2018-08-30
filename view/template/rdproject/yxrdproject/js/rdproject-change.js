/*******************************************************************************
 * 直接提交审批*
 *
 * @param {}
 *            obj
 * @param {}
 *            mytable
 */
function comitToApp() {
	document.getElementById('form1').action = "index1.php?model=rdproject_yxrdproject_rdproject&action=add&act=app";
}

// 汇率计算
$(function conversion() {
	var currency = $("#currency").val();

	if (currency != '人民币' && currency != '') {
		document.getElementById("currencyRate").style.display = "";
		$("#cur").html("(" + currency + ")");
		$("#cur1").html("(" + currency + ")");
		$("#orderTempMoney_v").attr('class', "readOnlyTxtNormal");
		$("#orderMoney_v").attr('class', "readOnlyTxtNormal");

		var tempMoney = $("#orderTempMoneyCur").val();
		var Money = $("#orderMoneyCur").val();
		var rate = $("#rate").val();
		$("#orderTempMoney_v").val(moneyFormat2(tempMoney * rate));
		$("#orderTempMoney").val(tempMoney * rate);
		$("#orderMoney_v").val(moneyFormat2(Money * rate));
		$("#orderMoney").val(Money * rate);
	}else if(currency == '人民币'){
        $("#orderMoney_v").attr('class',"txt");
        $("#orderMoney_v").attr('readOnly',false);
        $("#orderTempMoney_v").attr('class',"txt");
        $("#orderTempMoney_v").attr('readOnly',false);
        $("#currencyRate").hide();
	}
});
// 选择金额币别
$(function() {
	$("#currency").yxcombogrid_currency({
		hiddenId : 'id',
		isFocusoutCheck : false,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#rate").val(data.rate);
					conversion();
				}
			}
		}
	});
	createFormatOnClick("orderTempMoney_c");
	createFormatOnClick("orderMoney_c");
});

/**
 * license
 *
 * @type
 */

function License(licenseId, actType) {
	var licenseVal = $("#" + licenseId).val();
	if (licenseVal == "") {
		// 如果为空,则不传值
		showThickboxWin('?model=yxlicense_license_tempKey&action=toSelect'
				+ '&focusId='
				+ licenseId
				+ '&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=900');
	} else {
		// 不为空则传值
		showThickboxWin('?model=yxlicense_license_tempKey&action=toSelect'
				+ '&focusId='
				+ licenseId
				+ '&licenseId='
				+ licenseVal
				+ '&actType='
				+ actType
				+ '&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=900');
	}
}

// 反写id
function setLicenseId(licenseId, thisVal) {
	$('#' + licenseId).val(thisVal);
}

// 组织机构选择
$(function() {
	$("#orderPrincipal").yxselect_user({
		hiddenId : 'orderPrincipalId'
	});
	$("#sciencePrincipal").yxselect_user({
		hiddenId : 'sciencePrincipalId'
	});
});

/** ********************删除动态表单************************ */
function mydel(obj, mytable,orderArray) {
	if (confirm('确定要删除该行？')) {
		var rowNo = obj.parentNode.parentNode.rowIndex * 1 - 1;
		var $row=$(obj.parentNode.parentNode);

		$row.append("<input type='text' name='rdproject["+ orderArray +"]["+ rowNo +"][isDel]' value='1'>");
		$row.hide();

		var mytable = document.getElementById(mytable);
		var myrows = mytable.rows.length;

		for (i = 1; i < myrows; i++) {
			mytable.rows[i].childNodes[0].innerHTML = i;
		}
	}
}

/** *****************隐藏计划******************************* */
function dis(name) {
	var temp = document.getElementById(name);
	if (temp.style.display == '')
		temp.style.display = "none";
	else if (temp.style.display == "none")
		temp.style.display = '';
}

// **********培训计划***********************

function train_add(mytra, countNum) {
	mycount = document.getElementById(countNum).value * 1 + 1;
	var mytra = document.getElementById(mytra);
	i = mytra.rows.length;
	oTR = mytra.insertRow([i]);
	oTR.className = "TableData";
	oTR.align = "center";
	oTR.height = "30px";
	oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = i;
	oTL1 = oTR.insertCell([1]);
	oTL1.innerHTML = "<input class='txtshort' type='text' name='rdproject[trainingplan]["
			+ mycount
			+ "][beginDT]' id='TraDT"
			+ mycount
			+ "' size='10' onfocus='WdatePicker()'>";
	oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML = "<input class='txtshort' type='text' name='rdproject[trainingplan]["
			+ mycount
			+ "][endDT]' id='TraEndDT"
			+ mycount
			+ "' size='10' onfocus='WdatePicker()'>";
	oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML = "<input class='txtshort' type='text' name='rdproject[trainingplan]["
			+ mycount + "][traNum]' value='' size='8' maxlength='40'/>";
	oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML = "<textarea name='rdproject[trainingplan][" + mycount
			+ "][adress]' rows='3' cols='15' style='width: 100%'></textarea>";
	oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML = "<textarea name='rdproject[trainingplan][" + mycount
			+ "][content]' rows='3' style='width: 100%'></textarea>";
	oTL6 = oTR.insertCell([6]);
	oTL6.innerHTML = "<textarea name='rdproject[trainingplan][" + mycount
			+ "][trainer]' rows='3' style='width: 100%'></textarea>";
	oTL7 = oTR.insertCell([7]);
	oTL7.innerHTML = "<img src='images/closeDiv.gif' onclick='mydel(this,\""
			+ mytra.id + "\")' title='删除行'>";
	document.getElementById(countNum).value = document.getElementById(countNum).value
			* 1 + 1;
}
/** **************************配置清单/服务内容************************************************ */

// function dynamic_add(mytra, countNum) {
// mycount = document.getElementById(countNum).value * 1 + 1;
// var mytra = document.getElementById(mytra);
// i = mytra.rows.length;
// oTR = mytra.insertRow([i]);
// oTR.className = "TableData";
// oTR.align = "center";
// oTR.height = "30px";
// oTL0 = oTR.insertCell([0]);
// oTL0.innerHTML = i;
// oTL1 = oTR.insertCell([1]);
// oTL1.innerHTML = "<input type='text' size='55'
// name='rdproject[rdprojectequ]["+ mycount +"][serviceItem]' id='serviceItem"+
// mycount +"'>";
// oTL2 = oTR.insertCell([2]);
// oTL2.innerHTML = "<input type='text' class='txt'
// name='rdproject[rdprojectequ]["+ mycount +"][number]' id='number"+ mycount
// +"'>";
// oTL3 = oTR.insertCell([3]);
// oTL3.innerHTML = "<input type='text' size='45'
// name='rdproject[rdprojectequ]["+ mycount +"][remark]' id='remark"+ mycount
// +"'> ";
// oTL4 = oTR.insertCell([4]);
// oTL4.innerHTML = "<input type='hidden' id='LicenseId" + mycount + "'
// name='rdproject[rdprojectequ][" + mycount + "][license]'/>" +
// "<input type='button' name='' class='txt_btn_a' value='配置'
// onclick='License(\"LicenseId" + mycount + "\");'>";
// oTL5 = oTR.insertCell([5]);
// oTL5.innerHTML = "<img src='images/closeDiv.gif' onclick='mydel(this,\""+
// invbody.id + "\")' title='删除行'>";
// document.getElementById(countNum).value =
// document.getElementById(countNum).value
// * 1 + 1;
// }
/** *********************************************************************************************************************************** */

/** ******************************************************************************************** */

$(function() {
	temp = $('#productNumber').val();
	for (var i = 1; i <= temp; i++) {
		$("#productNo" + i).yxcombogrid_product({
			hiddenId : 'productId' + i,
			gridOptions : {
				showcheckbox : false,
				event : {
					'row_dblclick' : function(i) {
						return function(e, row, data) {
							$("#productName" + i).val(data.productName);
							$("#productModel" + i).val(data.pattern);
							$("#unitName" + i).val(data.unitName);
							$("#warrantyPeriod" + i).val(data.warranty);
						}
					}(i)
				}
			}
		});
	}
});

/** ********************产品信息************************ */
function dynamic_add(packinglist, countNumP) {
	deliveryDate = $('#deliveryDate').val();
	mycount = document.getElementById(countNumP).value * 1 + 1;
	var packinglist = document.getElementById(packinglist);
	i = packinglist.rows.length;
	oTR = packinglist.insertRow([i]);

	var oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = i;
	var oTL1 = oTR.insertCell([1]);
	oTL1.innerHTML = "<input type='text' id='productNo" + mycount
			+ "' class='txtmiddle' name='rdproject[rdprojectequ][" + mycount
			+ "][productNo]' >" + "<input type='hidden' id='unitName" + mycount
			+ "' name='rdproject[rdprojectequ][" + mycount + "][unitName]'>";

	// 单选产品
	$("#productNo" + mycount).yxcombogrid_product({
		hiddenId : 'productId' + mycount,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(mycount) {
					return function(e, row, data) {
						$("#productName" + mycount).val(data.productName);
						$("#productModel" + mycount).val(data.pattern);
						$("#unitName" + mycount).val(data.unitName);
						$("#warrantyPeriod" + mycount).val(data.warranty);
					};
				}(mycount)
			}
		}
	});
	var oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML = "<input type='hidden' id='productId" + mycount
			+ "'  name='rdproject[rdprojectequ][" + mycount + "][productId]'/>"
			+ "<input id='productName" + mycount
			+ "' type='text' class='txtmiddle' name='rdproject[rdprojectequ]["
			+ mycount + "][productName]' />";
	$("#productName" + mycount).yxcombogrid_productName({
		hiddenId : 'productId' + mycount,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(mycount) {
					return function(e, row, data) {
						$("#productNo" + mycount).val(data.productCode);
						$("#productModel" + mycount).val(data.pattern);
						$("#unitName" + mycount).val(data.unitName);
						$("#warrantyPeriod" + mycount).val(data.warranty);
					};
				}(mycount)
			}
		}
	});
	var oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML = "<input id='productModel"
			+ mycount
			+ "' type='text' class='readOnlyTxtItem' name='rdproject[rdprojectequ]["
			+ mycount + "][productModel]' readonly>";

	var oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML = "<input class='txtshort' type='text' name='rdproject[rdprojectequ]["
			+ mycount
			+ "][number]' id='number"
			+ mycount
			+ "' onblur='FloatMul(\"number"
			+ mycount
			+ "\",\"price"
			+ mycount
			+ "\",\"money" + mycount + "\")' />";
	var oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML = "<input class='txtshort' type='text' name='rdproject[rdprojectequ]["
			+ mycount
			+ "][price]' id='price"
			+ mycount
			+ "' onblur='FloatMul(\"number"
			+ mycount
			+ "\",\"price"
			+ mycount
			+ "\",\"money" + mycount + "\")' />";

	var oTL6 = oTR.insertCell([6]);
	oTL6.innerHTML = "<input class='txtshort' type='text' name='rdproject[rdprojectequ]["
			+ mycount + "][money]' id='money" + mycount + "' />";

	var oTL7 = oTR.insertCell([7]);
	oTL7.innerHTML = "<input type='text' class='txtshort' name='rdproject[rdprojectequ]["
			+ mycount
			+ "][warrantyPeriod]' id='warrantyPeriod"
			+ mycount
			+ "'>";

	var oTL8 = oTR.insertCell([8]);
	oTL8.innerHTML = "<input type='hidden' id='LicenseId"
			+ mycount
			+ "' name='rdproject[rdprojectequ]["
			+ mycount
			+ "][license]'/>"
			+ "<input type='button' name='' class='txt_btn_a' value='配置' onclick='License(\"LicenseId"
			+ mycount + "\");'>";
    var oTL9 = oTR.insertCell([9]);
	oTL9.innerHTML = "<input type='checkbox' name='rdproject[rdprojectequ]["
			+ mycount + "][isSell]' checked='checked'>";
	var oTL10 = oTR.insertCell([10]);
	oTL10.innerHTML = "<img src='images/closeDiv.gif' onclick='mydel(this,\""
			+ packinglist.id + "\")' title='删除行'>";

	document.getElementById(countNumP).value = document
			.getElementById(countNumP).value
			* 1 + 1;
	// 千分位带计算
	createFormatOnClick('number' + mycount, 'number' + mycount, 'price'
			+ mycount, 'money' + mycount);
	createFormatOnClick('price' + mycount, 'number' + mycount, 'price'
			+ mycount, 'money' + mycount);
	createFormatOnClick('money' + mycount, 'number' + mycount, 'price'
			+ mycount, 'money' + mycount);

}

/** **************************************************************************************************************************** */

// 日期联动
$(function() {
	$('#deliveryDate').bind('focusout', function() {
		var thisDate = $(this).val();
		deliveryDate = $('#deliveryDate').val();
		$.each($(':input[id^="projArraDate"]'), function(i, n) {
			$(this).val(thisDate);
		})
	});

});

// 选择省份
$(function() {

	$("#customerProvince").yxcombogrid_province({
		hiddenId : 'customerProvinceId',
		gridOptions : {
			showcheckbox : false
		}
	});
});
// 所属区域
$(function() {

	$("#district").yxcombogrid_province({
		hiddenId : 'districtId',
		gridOptions : {
			showcheckbox : false
		}
	});
});

function reloadCombo() {
	$("#customerLinkman").yxcombogrid('grid').reload;
}
// 客户联系人
function reloadCombo() {
	$("#customerLinkman").yxcombogrid('grid').reload;

}

$(function() {

	$("#provincecity").yxcombogrid_province({
		hiddenId : 'provinceId',
		gridOptions : {
			showcheckbox : false
		}
	});
});

function reloadCombo() {
	// alert( $("#customerLinkman").yxcombogrid('grid').param );
	$("#linkMan").yxcombogrid('grid').reload;
}
// 客户联系人
function reloadCombo() {
	// alert( $("#customerLinkman").yxcombogrid('grid').param );
	$("#linkMan").yxcombogrid('grid').reload;

}

$(function() {

	$("#provincecity").yxcombogrid_province({
		hiddenId : 'provinceId',
		gridOptions : {
			showcheckbox : false
		}
	});
});

$(function() {

	$("#linkMan").yxcombogrid_linkman({
		hiddenId : 'linkManId',
		gridOptions : {
			reload : true,
			showcheckbox : false,
			// param : param,
			event : {
				'row_dblclick' : function(e, row, data) {
					// $("#customerId").val(data.customerId);
					$("#linkManPhone").val(data.mobile);
					//					$("#customerEmail").val(data.email);
				}
			}
		}
	});

});