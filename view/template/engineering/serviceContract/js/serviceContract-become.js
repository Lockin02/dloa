


//汇率计算
function conversion(){
   var currency = $("#currency").val();
   if(currency != '人民币' && currency != ''){
   	document.getElementById("currencyRate").style.display="";
         $("#cur").html("("+currency+")");
         $("#cur1").html("("+currency+")");
         $("#orderTempMoney_v").attr('class',"readOnlyTxtNormal");
         $("#orderMoney_v").attr('class',"readOnlyTxtNormal");

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
}
$(function (){
   var currency = $("#currency").val();
   if(currency != '人民币' && currency != ''){
   	document.getElementById("currencyRate").style.display="";
         $("#cur").html("("+currency+")");
         $("#cur1").html("("+currency+")");
         $("#orderTempMoney_v").attr('class',"readOnlyTxtNormal");
         $("#orderMoney_v").attr('class',"readOnlyTxtNormal");

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
 * @type
 */

function License(licenseId){
	var licenseVal = $("#" + licenseId ) .val();
	if( licenseVal == ""){
		//如果为空,则不传值
		showThickboxWin('?model=yxlicense_license_tempKey&action=toSelect'
		    + '&focusId=' + licenseId
			+ '&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=900');
	}else{
		//不为空则传值
		showThickboxWin('?model=yxlicense_license_tempKey&action=toSelect'
		    + '&focusId=' + licenseId
			+ '&licenseId=' + licenseVal
			+ '&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=900');
	}
}

//反写id
function setLicenseId(licenseId,thisVal){
	$('#' + licenseId ).val(thisVal);
}


/** ********************删除动态表单************************ */
function myedit(obj, mytable) {
		var rowNo = obj.parentNode.parentNode.rowIndex * 1 - 1;
		var $row=$(obj.parentNode.parentNode);

			rowLength = $("tr[parentRowId="+$row.find("input[id^='isCon']").val()+"]").size();
			  for(rowL=rowNo+1;rowL<=rowNo+rowLength;rowL++){
			     $("tr[parentRowId="+$row.find("input[id^='isCon']").val()+"]").append("<input type='hidden' name='serviceContract[serviceequ]["+ rowL +"][isDel]' value='1'>");
		         $("tr[parentRowId="+$row.find("input[id^='isCon']").val()+"]").hide();
			  }
		var mytable = document.getElementById(mytable);
		var myrows = mytable.rows.length;

		for (i = 1; i < myrows; i++) {
			mytable.rows[i].childNodes[0].innerHTML = i;
		}
}
function myeditT(obj, mytable) {
		var rowNo = obj.parentNode.parentNode.rowIndex * 1 - 1;
		var $row=$(obj.parentNode.parentNode);

			rowLength = $("tr[parentRowId="+$row.find("input[id^='isCon']").val()+"]").size();
			  for(rowL=rowNo+1;rowL<=rowNo+rowLength;rowL++){
		         $("tr[parentRowId="+$row.find("input[id^='isCon']").val()+"]").remove();
			  }
		var mytable = document.getElementById(mytable);
		var myrows = mytable.rows.length;

		for (i = 1; i < myrows; i++) {
			mytable.rows[i].childNodes[0].innerHTML = i;
		}
}
function mydelC(obj, mytable) {
	if (confirm('确定要删除该行？')) {
		var rowNo = obj.parentNode.parentNode.rowIndex * 1 - 1;
		var $row=$(obj.parentNode.parentNode);

		if($row.find("input[id^='isConfig']").val() == ''){
			rowLength = $("tr[parentRowId="+$row.find("input[id^='isCon']").val()+"]").size();
			  for(rowL=rowNo+1;rowL<=rowNo+rowLength;rowL++){
			     $("tr[parentRowId="+$row.find("input[id^='isCon']").val()+"]").append("<input type='text' name='serviceContract[serviceequ]["+ rowL +"][isDel]' value='1'>");
		         $("tr[parentRowId="+$row.find("input[id^='isCon']").val()+"]").hide();
			  }
			}
		$row.append("<input type='text' name='serviceContract[serviceequ]["+ rowNo +"][isDel]' value='1'>");
		$row.hide();

		var mytable = document.getElementById(mytable);
		var myrows = mytable.rows.length;

		for (i = 1; i < myrows; i++) {
			mytable.rows[i].childNodes[0].innerHTML = i;
		}
	}
}
function mydelT(obj, mytable) {
	if (confirm('确定要删除该行？')) {
		var rowNo = obj.parentNode.parentNode.rowIndex * 1 - 1;
		var $row=$(obj.parentNode.parentNode);

		if($row.find("input[id^='isConfig']").val() == ''){
			rowLength = $("tr[parentRowId="+$row.find("input[id^='isCon']").val()+"]").size();
			  for(rowL=rowNo+1;rowL<=rowNo+rowLength;rowL++){
		         $("tr[parentRowId="+$row.find("input[id^='isCon']").val()+"]").remove();
			  }
			}
		$row.remove();

		var mytable = document.getElementById(mytable);
		var myrows = mytable.rows.length;

		for (i = 1; i < myrows; i++) {
			mytable.rows[i].childNodes[0].innerHTML = i;
		}
	}
}
function mydel(obj, mytable) {
	if (confirm('确定要删除该行？')) {
		var rowNo = obj.parentNode.parentNode.rowIndex * 1 - 1;
		var mytable = document.getElementById(mytable);
		mytable.deleteRow(rowNo-1);
		var i = 1;
	   $(mytable).children("tr").each(function() {
				if ($(this).css("display") != "none") {
					$(this).children("td").eq(0).text(i);
					i++;

				}
			})
	}
}
/**
 * 重新计算列序号
 * @param {}
 * name
 */
function recount(mytable) {
	var mytable = document.getElementById(mytable);

	var myrows = mytable.rows.length;
	for (i = 0; i < myrows; i++) {
		mytable.rows[i].childNodes[0].innerHTML = i + 1;
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
	j = i+1;
	oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = j;
	oTL1 = oTR.insertCell([1]);
	oTL1.innerHTML = "<input class='txtshort' type='text' name='serviceContract[trainingplan][" + mycount
			+ "][beginDT]' id='TraDT" + mycount
			+ "' size='10' onfocus='WdatePicker()'>";
	oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML = "<input class='txtshort' type='text' name='serviceContract[trainingplan][" + mycount
			+ "][endDT]' id='TraEndDT" + mycount
			+ "' size='10' onfocus='WdatePicker()'>";
	oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML = "<input class='txtshort' type='text' name='serviceContract[trainingplan][" + mycount
			+ "][traNum]' value='' size='8' maxlength='40'/>";
	oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML = "<textarea name='serviceContract[trainingplan][" + mycount
			+ "][adress]' rows='3' cols='15' style='width: 100%'></textarea>";
	oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML = "<textarea name='serviceContract[trainingplan][" + mycount
			+ "][content]' rows='3' style='width: 100%'></textarea>";
	oTL6 = oTR.insertCell([6]);
	oTL6.innerHTML = "<textarea name='serviceContract[trainingplan][" + mycount
			+ "][trainer]' rows='3' style='width: 100%'></textarea>";
	oTL7 = oTR.insertCell([7]);
	oTL7.innerHTML = "<img src='images/closeDiv.gif' onclick='mydel(this,\""
			+ mytra.id + "\")' title='删除行'>";
	document.getElementById(countNum).value = document.getElementById(countNum).value
			* 1 + 1;
}
/****************************配置清单/服务内容*************************************************/

function serviceList_add(mytra, countNum) {
	mycount = document.getElementById(countNum).value * 1 + 1;
	var mytra = document.getElementById(mytra);
	i = mytra.rows.length;
	oTR = mytra.insertRow([i]);
	oTR.className = "TableData";
	oTR.align = "center";
	oTR.height = "30px";
	j = i+1;
	oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = j;
	 oTL1 = oTR.insertCell([1]);
     oTL1.innerHTML = "<input type='text' size='55' name='serviceContract[servicelist]["+ mycount +"][serviceItem]' id='serviceItem"+ mycount +"'>";
     oTL2 = oTR.insertCell([2]);
     oTL2.innerHTML = "<input type='text' class='txt' name='serviceContract[servicelist]["+ mycount +"][serviceNo]' id='serviceNo"+ mycount +"'>";
     oTL3 = oTR.insertCell([3]);
     oTL3.innerHTML = "<input type='text' size='45' name='serviceContract[servicelist]["+ mycount +"][serviceRemark]' id='serviceRemark"+ mycount +"'> ";
     oTL4 = oTR.insertCell([4]);
     oTL4.innerHTML = "<img src='images/closeDiv.gif' onclick='mydel(this,\""+ mytra.id + "\")' title='删除行'>";
	document.getElementById(countNum).value = document.getElementById(countNum).value
			* 1 + 1;
}

/*****************************************************************************************************************/

/*****************************************************************************************************************/
/*
 * 双击查看产品清单 物料 配置信息
 */
function conInfo(productId,proRemarkNum){
	    var proId = $("#"+productId).val();
	    var proRemarkId = "remark" + proRemarkNum;
	    var orderId = $("#id").val();
	    if (proId == ''){
	        alert("【请选择物料】");
	    }else {
	    	showThickboxWin('?model=projectmanagent_order_order&action=proinfotab&productId='
				+ proId
				+ "&orderId="
				+ orderId
				+ "&proRemarkId="
				+ proRemarkId
				+ '&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=600');
	    }

	}
$(function() {
	temp = $('#productNumber').val();
	for(var i=1;i<=temp;i++){
	$("#productNo" + i).yxcombogrid_product({
		hiddenId : 'productId'+i,
		gridOptions : {
			showcheckbox : false,
				event : {
					'row_dblclick' : function(i){
						return function(e, row, data) {
							myedit(document.getElementById("Del"+i),"invbody");
							$("#productName" + i).val(data.productName);
							$("#productModel" + i).val(data.pattern);
							$("#unitName" + i).val(data.unitName);
							$("#warrantyPeriod" + i).val(data.warranty);
							var isDelId = $("#isDel" + i).val();
							var productId = $("#productId" + i).val();
							if(isDelId != productId){
	                            $("#invbody").append("<input type='text' name='serviceContract[serviceequ]["+ i +"][isEdit]' value='1'>");
							}
							$("#number" + i).val(1);

							var encrypt = data.encrypt;
							if(encrypt == 'on'){
								alert("此物料已设置加密必填属性，请填写加密配置");
								License('LicenseId' + i);
							}
							var allocation = data.allocation;
								$("#isCon" + i).val("isCon_" + i);
								       $.get('index1.php', {
											model : 'engineering_serviceContract_serviceContract',
											action : 'ajaxorder',
											id : data.id,
											trId : "isCon_" + i,
											Num : $("#productNumber").val(),
											isEdit : "1",
											dataType : "html"
										}, function(pro) {
											if (pro) {
												$("#equTab_"+i).after(pro);
												var rowNums = $("tr[name^='equTab_']").length * 1 + $("tr[id^='equTab_']").length * 1;
												document.getElementById("productNumber").value = document.getElementById("productNumber").value * 1 + 1;
												recount("invbody");
											} else {
											}
										})
						}
					}(i)
				}
			}
		});
	}
});
$(function() {
	temp = $('#productNumber').val();
	for(var i=1;i<=temp;i++){
	$("#productName" + i).yxcombogrid_productName({
		hiddenId : 'productId'+i,
		gridOptions : {
			showcheckbox : false,
				event : {
					'row_dblclick' : function(i){
						return function(e, row, data) {
							myedit(document.getElementById("Del"+i),"invbody");
							$("#productNo" + i).val(data.productCode);
							$("#productModel" + i).val(data.pattern);
							$("#unitName" + i).val(data.unitName);
                            $("#warrantyPeriod" + i).val(data.warranty);
                            var isDelId = $("#isDel" + i).val();
							var productId = $("#productId" + i).val();
							if(isDelId != productId){
	                            $("#invbody").append("<input type='hidden' name='serviceContract[serviceequ]["+ i +"][isEdit]' value='1'>");
							}
                            $("#number" + i).val(1);

							var encrypt = data.encrypt;
							if(encrypt == 'on'){
								alert("此物料已设置加密必填属性，请填写加密配置");
								License('LicenseId' + i);
							}
							var allocation = data.allocation;
								$("#isCon" + i).val("isCon_" + i);
								       $.get('index1.php', {
											model : 'engineering_serviceContract_serviceContract',
											action : 'ajaxorder',
											id : data.id,
											trId : "isCon_" + i,
											Num : $("#productNumber").val(),
											isEdit : "1",
											dataType : "html"
										}, function(pro) {
											if (pro) {
												$("#equTab_"+i).after(pro);
												var rowNums = $("tr[name^='equTab_']").length * 1 + $("tr[id^='equTab_']").length * 1;
												document.getElementById("productNumber").value = document.getElementById("productNumber").value * 1 + 1;
												recount("invbody");
											} else {
											}
										})
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
	// 获取当前行数 ,用于行序号
	var rowNums = $("tr[name^='equTab_']").length * 1 + $("tr[id^='equTab_']").length * 1;
	// 获取当前隐藏值,用于做id增长
	mycount = $('#' + countNumP).val() * 50 + 1;
	// 缓存插入表格
	var packinglist = document.getElementById(packinglist);
	// 插入行
	oTR = packinglist.insertRow([rowNums]);
	oTR.id = "equTab_" + mycount;
	// 当前行号
	i = rowNums + 1;
	var oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = i;
	var oTL1 = oTR.insertCell([1]);
	oTL1.innerHTML = "<input type='text' id='productNo" + mycount + "' class='txtmiddle' name='serviceContract[serviceequ][" + mycount + "][productNo]' />"+
	                 "<input type='hidden' id='isAdd"+mycount+"' name='serviceContract[serviceequ]["+mycount+"][isAdd]' value='1'>";
	  // 单选产品
	$("#productNo" + mycount).yxcombogrid_product({
		hiddenId : 'productId' + mycount,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(mycount){
						return function(e, row, data) {
							myeditT(document.getElementById("Del"+mycount),"invbody");
						$("#productName"+mycount).val(data.productName);
						$("#productModel"+mycount).val(data.pattern);
						$("#unitName" +mycount).val(data.unitName);
						$("#warrantyPeriod" + mycount).val(data.warranty);
						$("#number" + mycount + "_v").val(1);
                        $("#number" + mycount).val(1);
						var encrypt = data.encrypt;
							if(encrypt == 'on'){
								alert("此物料已设置加密必填属性，请填写加密配置");
								License('LicenseId' + mycount);
							}
					    var allocation = data.allocation;
								 $("#isCon" + mycount).val("isCon_" + mycount);
								       $.get('index1.php', {
											model : 'engineering_serviceContract_serviceContract',
											action : 'ajaxorder',
											id : data.id,
											trId : "isCon_" + mycount,
											Num : mycount,
											isEdit : "1",
											dataType : "html"
										}, function(pro) {
											if (pro) {
												$("#equTab_"+mycount).after(pro);
												var rowNums = $("tr[name^='equTab_']").length * 1 + $("tr[id^='equTab_']").length * 1;
												document.getElementById(countNumP).value = document.getElementById(countNumP).value * 1 + 1;
												recount("invbody");
											} else {
											}
										})
					};
			  	}(mycount)
			}
		}
	});
	var oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML = "<input type='hidden' id='productId" + mycount + "'  name='serviceContract[serviceequ][" + mycount + "][productId]'/>" +
			         "<input id='productName" + mycount + "' type='text' class='txt' name='serviceContract[serviceequ][" + mycount + "][productName]' />"+
			         "&nbsp<img src='images/icon/icon105.gif' onclick='conInfo(\"productId"+ mycount +"\",\""+mycount+"\");' title='查看配置信息'/>";;
	$("#productName" + mycount).yxcombogrid_productName({
		hiddenId : 'productId' + mycount,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(mycount){
						return function(e, row, data) {
							myeditT(document.getElementById("Del"+mycount),"invbody");
						$("#productNo"+mycount).val(data.productCode);
						$("#productModel"+mycount).val(data.pattern);
						$("#unitName" + mycount).val(data.unitName);
						$("#warrantyPeriod" + mycount).val(data.warranty);
						$("#number" + mycount + "_v").val(1);
                        $("#number" + mycount).val(1);
						var encrypt = data.encrypt;
							if(encrypt == 'on'){
								alert("此物料已设置加密必填属性，请填写加密配置");
								License('LicenseId' + mycount);
							}
					    var allocation = data.allocation;
								 $("#isCon" + mycount).val("isCon_" + mycount);
								       $.get('index1.php', {
											model : 'engineering_serviceContract_serviceContract',
											action : 'ajaxorder',
											id : data.id,
											trId : "isCon_" + mycount,
											Num : mycount,
											isEdit : "1",
											dataType : "html"
										}, function(pro) {
											if (pro) {
												$("#equTab_"+mycount).after(pro);
												var rowNums = $("tr[name^='equTab_']").length * 1 + $("tr[id^='equTab_']").length * 1;
												document.getElementById(countNumP).value = document.getElementById(countNumP).value * 1 + 1;
												recount("invbody");
											} else {
											}
										})
					};
			  	}(mycount)
			}
		}
	});
	var oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML = "<input id='productModel" + mycount + "' type='text' class='txtmiddle' name='serviceContract[serviceequ][" + mycount + "][productModel]' readonly>";
    var oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML = "<input class='txtshort' type='text' name='serviceContract[serviceequ][" + mycount + "][number]' id='number" + mycount + "'/>";
	var oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML = "<input class='txtshort' type='text' name='serviceContract[serviceequ][" + mycount + "][price]' id='price" + mycount + "' />";
	var oTL6 = oTR.insertCell([6]);
	oTL6.innerHTML = "<input class='txtshort' type='text' name='serviceContract[serviceequ][" + mycount + "][money]' id='money" + mycount
			+ "' />";
	var oTL7 = oTR.insertCell([7]);
	oTL7.innerHTML = "<input type='text' class='txtshort' name='serviceContract[serviceequ][" + mycount + "][warrantyPeriod]' id='warrantyPeriod" + mycount + "'>";
	var oTL8 = oTR.insertCell([8]);
	oTL8.innerHTML = "<input type='hidden' id='LicenseId" + mycount + "' name='serviceContract[serviceequ][" + mycount + "][license]'/>" +
			         "<input type='button' name='' class='txt_btn_a' value='配置' onclick='License(\"LicenseId" + mycount + "\");'>";
    var oTL9 = oTR.insertCell([9]);
	oTL9.innerHTML = "<input type='checkbox' name='serviceContract[serviceequ][" + mycount + "][isSell]' checked='checked'>"+
	                  "<input type='hidden' name='serviceContract[serviceequ][" + mycount + "][isCon]'id='isCon" + mycount + "'>"+
	                  "<input type='hidden' name='serviceContract[serviceequ][" + mycount + "][isConfig]' id='isConfig" + mycount + "'>"+
	                  "<input type='hidden' name='serviceContract[serviceequ][" + mycount + "][remark]' id='remark"+mycount+"'>";
	var oTL10 = oTR.insertCell([10]);
	oTL10.innerHTML = "<img src='images/closeDiv.gif' onclick='mydel(this,\"" + packinglist.id + "\")' title='删除行' id='Del"+mycount+"'>";

	document.getElementById(countNumP).value = document.getElementById(countNumP).value * 1 + 1;
//千分位带计算
    createFormatOnClick('number'+mycount,'number' +mycount ,'price' +mycount ,'money'+ mycount);
    createFormatOnClick('price'+mycount,'number' +mycount ,'price'+mycount,'money'+ mycount);
    createFormatOnClick('money'+mycount,'number' +mycount ,'price'+mycount,'money'+ mycount);

}



/**************************************************************自定义清单*******************************************************************/

function pre_add(mycustom, countNum) {
	mycount = document.getElementById(countNum).value * 1 + 1;
	var mycustom = document.getElementById(mycustom);
	i = mycustom.rows.length;
	oTR = mycustom.insertRow([i]);
	oTR.className = "TableData";
	oTR.align = "center";
	oTR.height = "30px";
	j = i+1
	oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = j;
	var oTL1 = oTR.insertCell([1]);
	oTL1.innerHTML = "<input class='txtshort' type='text' name='serviceContract[customizelist][" + mycount
			+ "][productCode]' id='PequID" + mycount
			+ "' value='' size='10' maxlength='40'/>";
	var oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML = "<input class='txtshort' type='text' name='serviceContract[customizelist][" + mycount
			+ "][productName]' id='PequName" + mycount
			+ "' value='' size='15' maxlength='20'/>";
	var oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML = "<input class='txtshort' type='text' name='serviceContract[customizelist][" + mycount
			+ "][productModel]' id='PreModel" + mycount
			+ "' value='' size='10' maxlength='40'/>";
	var oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML = "<input class='txtshort' type='text' name='serviceContract[customizelist][" + mycount
			+ "][number]' id='PreAmount" + mycount + "' />";
	var oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML = "<input class='txtshort' type='text' name='serviceContract[customizelist][" + mycount
			+ "][price]' id='PrePrice" + mycount + "' />";
	var oTL6 = oTR.insertCell([6]);
	oTL6.innerHTML = "<input class='txtshort' type='text' name='serviceContract[customizelist][" + mycount
			+ "][money]' id='CountMoney" + mycount + "' />";
	var oTL7 = oTR.insertCell([7]);
	oTL7.innerHTML = "<input class='txtshort' type='text' name='serviceContract[customizelist][" + mycount
			+ "][projArraDT]' id='PreDeliveryDT" + mycount
			+ "' size='10' onfocus='WdatePicker()'>";
	var oTL8 = oTR.insertCell([8]);
	oTL8.innerHTML = "<input class='txt' type='text' name='serviceContract[customizelist][" + mycount
			+ "][remark]' id='PRemark" + mycount
			+ "' value='' size='18' maxlength='100'/>";

    var oTL9 = oTR.insertCell([9]);
	oTL9.innerHTML = "<input type='hidden' id='cuslicenseId" + mycount + "' name='serviceContract[customizelist][" + mycount + "][license]'/>" +
			         "<input type='button' name='' class='txt_btn_a' value='配置' onclick='License(\"cuslicenseId" + mycount + "\");'>";
	var oTL10= oTR.insertCell([10]);
	oTL10.innerHTML = "<input type='checkbox' name='serviceContract[customizelist][" + mycount
			+ "][isSell]' id='customizelistSell" + mycount
			+ "' checked='checked' />";
	var oTL11 = oTR.insertCell([11]);
	oTL11.innerHTML = "<img src='images/closeDiv.gif' onclick='mydel(this,\""
			+ mycustom.id + "\")' title='删除行'>";
	document.getElementById(countNum).value = document.getElementById(countNum).value
			* 1 + 1;

	//千分位带计算
    createFormatOnClick('PreAmount'+mycount,'PreAmount' +mycount ,'PrePrice' +mycount ,'CountMoney'+ mycount);
	createFormatOnClick('PrePrice'+mycount,'PreAmount'+mycount,'PrePrice'+mycount,'CountMoney'+mycount);
	createFormatOnClick('CountMoney'+mycount,'PreAmount'+mycount,'PrePrice'+mycount,'CountMoney'+mycount);
}
/*******************************************************************************************************************************/

// 金额相加
function countAll(){
	var invnumber = $('#productNumber').val();
	var incomeMoney = $('#money').val();
	var thisAmount = 0;
	var allAmount = 0;
	for(var i = 1;i <= invnumber;i++){
		thisAmount = $('#money' + i).val() * 1;
		if(!isNaN(thisAmount)){
			allAmount += thisAmount;
		}
	}

	$('#orderMoney').val(allAmount);

}


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

//选择省份
$(function() {

	$("#customerProvince").yxcombogrid_province({
		hiddenId : 'customerProvinceId',
		gridOptions : {
			showcheckbox : false
		}
	});
});
//所属区域
$(function() {

	$("#district").yxcombogrid_province({
		hiddenId : 'districtId',
		gridOptions : {
			showcheckbox : false
		}
	});
});
//选择客户
//$(function() {
//
//	$("#customerName").yxcombogrid_customer({
//		hiddenId : 'customerId',
//		gridOptions : {
//			showcheckbox : false
//		}
//	});
//});
$(function(){

//	//客户类型
//	customerTypeArr = getData('KHLX');
//		addDataToSelect(customerTypeArr, 'customerType');
//		addDataToSelect(customerTypeArr, 'customerListTypeArr1');
//   //开票类型
//	invoiceTypeArr = getData('FPLX');
//	    addDataToSelect(invoiceTypeArr, 'invoiceType');
//		addDataToSelect(invoiceTypeArr, 'invoiceListType1');


});


function reloadCombo() {
	// alert( $("#customerLinkman").yxcombogrid('grid').param );
	$("#customerLinkman").yxcombogrid('grid').reload;
}
//客户联系人
function reloadCombo() {
	// alert( $("#customerLinkman").yxcombogrid('grid').param );
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
//客户联系人
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
	$("#cusName").yxcombogrid_customer({
		hiddenId : 'cusNameId',
		gridOptions : {
			showcheckbox : false,
			// param :{"contid":$('#contractId').val()},
			event : {
				'row_dblclick' : function(e, row, data) {
					var getGrid = function() {
						return $("#linkMan")
								.yxcombogrid_linkman("getGrid");
					}
					var getGridOptions = function() {
						return $("#linkMan")
								.yxcombogrid_linkman("getGridOptions");
					}
					if (getGrid().reload) {
						getGridOptions().param = {
							customerId : data.id
						};
						getGrid().reload();
					} else {
						getGridOptions().param = {
							customerId : data.id
						}
					}

					$("#customerType").val(data.TypeOne);
					$("#customerProvince").val(data.Prov);
					$("#customerId").val(data.id);
					$("#province").val(data.ProvId);//所属省份Id
				    $("#province").trigger("change");
				    $("#provinceName").val(data.Prov);//所属省份
					$("#city").val(data.CityId);//城市ID
					$("#cityName").val(data.City);//城市名称
					$("#areaPrincipal").val(data.AreaLeader);//区域负责人
					$("#areaPrincipalId_v").val(data.AreaLeaderId);//区域负责人Id
					$("#areaName").val(data.AreaName);//合同所属区域
					$("#areaCode").val(data.AreaId);//合同所属区域
					$("#address").val(data.Address);//客户地址
					// $("#customerLinkman").yxcombogrid('grid').param={}
					// $("#customerLinkman").yxcombogrid('grid').reload;
				}
			}
		}
	});

		$("#linkMan").yxcombogrid_linkman({
		hiddenId : 'linkManId',
		gridOptions : {
			reload : true,
			showcheckbox : false,
			// param : param,
			event : {
				'row_dblclick' : function(e, row, data) {
					// alert( $('#customerId').val() );
					// unset($('#customerId'));
//					$("#customerName").val(data.customerName);
//					$("#customerId").val(data.customerId);
					$("#linkManPhone").val(data.mobile);
//					$("#customerEmail").val(data.email);
				}
			}
		}
	});

	});
/******************合同 区域负责人 ---  合同归属区域***************************************/
$(function() {
	$("#areaName").yxcombogrid_area({
		hiddenId : 'areaPrincipalId',
		gridOptions : {
			showcheckbox : false,
				event : {
					'row_dblclick' :function(e, row, data) {
							$("#areaPrincipal").val(data.areaPrincipal);
							$("#areaCode").val(data.id);
							$("#areaPrincipalId_v").val(data.areaPrincipalId);
					}}}
		});
});

function ajaxCode(){
	if ($('#orderCode').val() == '') {
		$('#icon').html('不能为空！');
		$("#orderCode").focus();
	} else if ($('#orderCode').val() != '') {
		var param = {
			model : 'engineering_serviceContract_serviceContract',
			action : 'ajaxCode',
			ajaxOrderCode : $('#orderCode').val()
		};
		if ($("#contractID").val() != '') {
			param.id = $("#contractID").val();
		}
		$.get('index1.php', param, function(data) {
					if (data == '1') {
						$('#icon').html('合同号已存在！');
						$("#orderCode").focus();
					} else {
						$('#icon').html('√');
					}
				})
	}
}