
function toApp(){
	document.getElementById('form1').action = "index1.php?model=projectmanagent_order_order&action=add&act=app";

}
function toSave(){
	document.getElementById('form1').action = "index1.php?model=projectmanagent_order_order&action=add";

}
/**
 * license
 * @type
 */
function License(licenseId){
	var licenseVal = $("#" + licenseId ) .val();
	if( licenseVal == ""){
		//���Ϊ��,�򲻴�ֵ
		showThickboxWin('?model=yxlicense_license_tempKey&action=toSelect'
		    + '&focusId=' + licenseId
			+ '&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=900');
	}else{
		//��Ϊ����ֵ
		showThickboxWin('?model=yxlicense_license_tempKey&action=toSelect'
		    + '&focusId=' + licenseId
			+ '&licenseId=' + licenseVal
			+ '&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=900');
	}
}

//��дid
function setLicenseId(licenseId,thisVal){
	$('#' + licenseId ).val(thisVal);
}


// ������
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
////�������Ψһ����֤
//function check_code(code) {
//	if (code != '') {
//		$.get('index1.php', {
//			model : 'projectmanagent_order_order',
//			action : 'checkRepeat',
//            ajaxOrderCode : code
//		}, function(data) {
//			if (data != '') {
//				$('#_orderCode').html('�Ѵ��ڵı�ţ�');
//
//			} else {
//				$('#_orderCode').html('��ſ��ã�');
//
//			}
//		})
//	}
//	return false;
//}

// ��������
$(function() {
	$('#deliveryDate').bind('focusout', function() {
		var thisDate = $(this).val();
        deliveryDate = $('#deliveryDate').val();
		$.each($(':input[id^="projArraDate"]'), function(i, n) {
			$(this).val(thisDate);
		})
	});

});

//ѡ��ʡ��
$(function() {

	$("#customerProvince").yxcombogrid_province({
		hiddenId : 'customerProvinceId',
		gridOptions : {
			showcheckbox : false
		}
	});
});
//��������
$(function() {

	$("#district").yxcombogrid_province({
		hiddenId : 'districtId',
		gridOptions : {
			showcheckbox : false
		}
	});
});
//ѡ��ͻ�
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
   //��Ʊ����
	invoiceTypeArr = getData('FPLX');
	    addDataToSelect(invoiceTypeArr, 'invoiceType');
		addDataToSelect(invoiceTypeArr, 'invoiceListType1');


});


function reloadCombo() {
	// alert( $("#customerLinkman").yxcombogrid('grid').param );
	$("#customerLinkman").yxcombogrid('grid').reload;
}
//�ͻ���ϵ��
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

$(function() {
	$("#customerName").yxcombogrid_customer({
		hiddenId : 'customerId',
		gridOptions : {
			showcheckbox : false,
			// param :{"contid":$('#contractId').val()},
			event : {
				'row_dblclick' : function(e, row, data) {
					var getGrid = function() {
						return $("#customerLinkman")
								.yxcombogrid_linkman("getGrid");
					}
					var getGridOptions = function() {
						return $("#customerLinkman")
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
					$("#areaPrincipal").val(data.AreaLeader);//��������
					$("#areaPrincipalId_v").val(data.AreaLeaderId);//��������Id
					$("#areaName").val(data.AreaName);//��ͬ��������
					$("#areaCode").val(data.AreaId);//��ͬ��������
					$("#address").val(data.Address);//�ͻ���ַ
					// $("#customerLinkman").yxcombogrid('grid').param={}
					// $("#customerLinkman").yxcombogrid('grid').reload;
				}
			}
		}
	});
	// customerId = $("#customerId").val()
	// $("#customerId").val(customerId)
	$("#customerLinkman").yxcombogrid_linkman({
		hiddenId : 'customerLinkmanId',
		gridOptions : {
			reload : true,
			showcheckbox : false,
			// param : param,
			event : {
				'row_dblclick' : function(e, row, data) {
					// alert( $('#customerId').val() );
					// unset($('#customerId'));
					$("#customerName").val(data.customerName);
					$("#customerId").val(data.customerId);
					$("#customerTel").val(data.mobile);
					$("#customerEmail").val(data.email);
				}
			}
		}
	});
	$("#linkman1").yxcombogrid_linkman({
		gridOptions : {
			reload : true,
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e,row,data){

//						alert( "linkman" + mycount );
						$("#linkmanId1").val(data.id);
						$("#telephone1").val(data.phone);
						$("#Email1").val(data.email);

			  	}
			}
		}
	});


});


/**
 *
 * @param {} mycount
 * ��Ⱦ��ϵ�������б�
 *
 */
	function reloadLinkman( linkman ){
		var getGrid = function() {
			return $("#" + linkman)
					.yxcombogrid_linkman("getGrid");
		}
		var getGridOptions = function() {
			return $("#" + linkman)
					.yxcombogrid_linkman("getGridOptions");
		}
		if( !$('#customerId').val() ){
		}else{
			if (getGrid().reload) {
				getGridOptions().param = {
					customerId : $('#customerId').val()
				};
				getGrid().reload();
			} else {
				getGridOptions().param = {
					customerId : $('#customerId').val()
				}
			}
		}
	}

// ************************�ͻ���ϵ��*************************
function link_add(mypay, countNum) {

	mycount = document.getElementById(countNum).value * 1 + 1;

	var mypay = document.getElementById(mypay);
	i = mypay.rows.length;
	oTR = mypay.insertRow([i]);
//	oTR.id = "linkDetail" + mycount;
	oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = i;
	oTL1 = oTR.insertCell([1]);
	oTL1.innerHTML = "<input class='txt' type='hidden' name='order[linkman][" + mycount
			+ "][linkmanId]' id='linkmanId" + mycount + "'/>"
			+ "<input class='txt' type='text' name='order[linkman][" + mycount
			+ "][linkman]' id='linkman" + mycount + "' onclick=\"reloadLinkman('linkman"+mycount+"\');\"/>";

	/**
	 * �ͻ���ϵ��
	 */
	$("#linkman" + mycount).yxcombogrid_linkman({
		gridOptions : {
			reload : true,
			showcheckbox : false,
			event : {
				'row_dblclick' : function(mycount){
						return function(e, row, data) {
//						alert( "linkman" + mycount );
						$("#linkmanId"+mycount).val(data.id);
						$("#telephone"+mycount).val(data.phone);
						$("#Email"+mycount).val(data.email);
					};
			  	}(mycount)
			}
		}
	});


	oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML = "<input class='txt' type='text' name='order[linkman][" + mycount
			+ "][telephone]' id='telephone" + mycount + "'/>";
	oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML = "<input class='txt' type='text' name='order[linkman][" + mycount
			+ "][Email]' id='Email" + mycount + "'/>";
	oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML = "<input class='txtlong' type='text' name='order[linkman][" + mycount
			+ "][remark]' id='remark" + mycount + "'/>";
	oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML = "<img src='images/closeDiv.gif' onclick='mydel(this,\""
			+ mypay.id + "\")' title='ɾ����'>";
	document.getElementById(countNum).value = document.getElementById(countNum).value
			* 1 + 1;
}



/***********************************************************************************************/
/*
 * ˫���鿴��Ʒ�嵥 ���� ������Ϣ
 */
function conInfo(productId){
	    var proId = $("#"+productId).val();
	    if (proId == ''){
	        alert("����ѡ�����ϡ�");
	    }else {
	    	 showThickboxWin('?model=stock_productinfo_configuration&action=viewConfig&productId='
                      + proId
                      +'&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=600');
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
							myedit(document.getElementById("Del"+i),"myequ");
							$("#productName" + i).val(data.productName);
							$("#productModel" + i).val(data.pattern);
							$("#unitName" + i).val(data.unitName);
							$("#warrantyPeriod" + i).val(data.warranty);
							var isDelId = $("#isDel" + i).val();
							var productId = $("#productId" + i).val();
							if(isDelId != productId){
	                            $("#myequ").append("<input type='hidden' name='order[orderequ]["+ i +"][isEdit]' value='1'>");
							}
							$("#number" + i).val(1);
							var encrypt = data.encrypt;
							if(encrypt == 'on'){
								alert("�����������ü��ܱ������ԣ�����д��������");
								License('licenseId' + i);
							}
							var allocation = data.allocation;
								$("#isCon" + i).val("isCon_" + i);
								       $.get('index1.php', {
											model : 'projectmanagent_order_order',
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
												recount("myequ");
												myedit("myequ","productNumber");
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
							myedit(document.getElementById("Del"+i),"myequ");
							$("#productNo" + i).val(data.productCode);
							$("#productModel" + i).val(data.pattern);
							$("#unitName" + i).val(data.unitName);
                            $("#warrantyPeriod" + i).val(data.warranty);
                            var isDelId = $("#isDel" + i).val();
							var productId = $("#productId" + i).val();
							if(isDelId != productId){
	                            $("#myequ").append("<input type='hidden' name='order[orderequ]["+ i +"][isEdit]' value='1'>");
							}
							$("#number" + i).val(1);
							var encrypt = data.encrypt;
							if(encrypt == 'on'){
								alert("�����������ü��ܱ������ԣ�����д��������");
								License('licenseId' + i);
							}
							var allocation = data.allocation;
								$("#isCon" + i).val("isCon_" + i);
								       $.get('index1.php', {
											model : 'projectmanagent_order_order',
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
												recount("myequ");
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
/** ********************��Ʒ��Ϣ************************ */
function dynamic_add(packinglist, countNumP) {
	deliveryDate = $('#deliveryDate').val();
   // ��ȡ��ǰ���� ,���������
	var rowNums = $("tr[name^='equTab_']").length * 1 + $("tr[id^='equTab_']").length * 1;
	// ��ȡ��ǰ����ֵ,������id����
	mycount = $('#' + countNumP).val() * 50 + 1;
	// ���������
	var packinglist = document.getElementById(packinglist);
	// ������
	oTR = packinglist.insertRow([rowNums + 1]);
	oTR.id = "equTab_" + mycount;
	// ��ǰ�к�
	i = rowNums + 1;
	var oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = i;
	var oTL1 = oTR.insertCell([1]);
	oTL1.innerHTML = "<input type='text' id='productNo" + mycount + "' class='txtshort' name='order[orderequ][" + mycount + "][productNo]' >"+
	                 "<input type='hidden' id='isAdd"+mycount+"' name='order[orderequ]["+mycount+"][isAdd]' value='1'>";
	 // ��ѡ��Ʒ
	$("#productNo" + mycount).yxcombogrid_product({
		hiddenId : 'productId' + mycount,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(mycount){
						return function(e, row, data) {
							myeditT(document.getElementById("Del"+mycount),"myequ");
						$("#productName"+mycount).val(data.productName);
						$("#productModel"+mycount).val(data.pattern);
						$("#warrantyPeriod" + mycount).val(data.warranty);
						$("#number" + mycount + "_v").val(1);
						$("#number" + mycount).val(1);
						var encrypt = data.encrypt;
							if(encrypt == 'on'){
								alert("�����������ü��ܱ������ԣ�����д��������");
								License('licenseId' + mycount);
							}
					    var allocation = data.allocation;
					    var Num = $("tr[name^='equTab_']").length * 1 + $("tr[id^='equTab_']").length * 1;
								$("#isCon" + mycount).val("isCon_" + mycount);
								       $.get('index1.php', {
											model : 'projectmanagent_order_order',
											action : 'ajaxorder',
											id : data.id,
											trId : "isCon_" + mycount,
											Num : Num,
											isEdit : "1",
											dataType : "html"
										}, function(pro) {
											if (pro) {
												$("#equTab_"+mycount).after(pro);
												var rowNums = $("tr[name^='equTab_']").length * 1 + $("tr[id^='equTab_']").length * 1;
												document.getElementById(countNumP).value = document.getElementById(countNumP).value * 1 + 1;
												recount("myequ");
											} else {
											}
										})
					};
			  	}(mycount)
			}
		}
	});

	var oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML = "<input type='hidden' id='productId" + mycount + "'  name='order[orderequ][" + mycount + "][productId]'/>" +
			"<input id='productName" + mycount + "' type='text' class='txt' name='order[orderequ][" + mycount + "][productName]' />"+
			"&nbsp<img src='images/icon/icon105.gif' onclick='conInfo(\"productId"+ mycount +"\");' title='�鿴������Ϣ'/>";
	  // ��ѡ��Ʒ
	$("#productName" + mycount).yxcombogrid_productName({
		hiddenId : 'productId' + mycount,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(mycount){
						return function(e, row, data) {
							myeditT(document.getElementById("Del"+mycount),"myequ");
						$("#productNo"+mycount).val(data.productCode);
						$("#productModel"+mycount).val(data.pattern);
						$("#unitName" + mycount).val(data.unitName);
						$("#warrantyPeriod" + mycount).val(data.warranty);
						$("#number" + mycount + "_v").val(1);
						$("#number" + mycount).val(1);
						var encrypt = data.encrypt;
							if(encrypt == 'on'){
								alert("�����������ü��ܱ������ԣ�����д��������");
								License('licenseId' + mycount);
							}
					    var allocation = data.allocation;
					    var Num = $("tr[name^='equTab_']").length * 1 + $("tr[id^='equTab_']").length * 1;
								$("#isCon" + mycount).val("isCon_" + mycount);
								       $.get('index1.php', {
											model : 'projectmanagent_order_order',
											action : 'ajaxorder',
											id : data.id,
											trId : "isCon_" + mycount,
											Num : Num,
											isEdit : "1",
											dataType : "html"
										}, function(pro) {
											if (pro) {
												$("#equTab_"+mycount).after(pro);
												var rowNums = $("tr[name^='equTab_']").length * 1 + $("tr[id^='equTab_']").length * 1;
												document.getElementById(countNumP).value = document.getElementById(countNumP).value * 1 + 1;
												recount("myequ");
											} else {
											}
										})
					};
			  	}(mycount)
			}
		}
	});
	var oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML = "<input id='productModel" + mycount
			+ "' type='text' class='txtshort' name='order[orderequ]["
			+ mycount + "][productModel]' readonly>";

    var oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML = "<input class='txtshort' type='text' name='order[orderequ][" + mycount
			+ "][number]' id='number" + mycount
			+ "' />";
	var oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML = "<input class='txtshort' type='text' name='order[orderequ][" + mycount
			+ "][price]' id='price" + mycount
			+ "'  />";
	var oTL6 = oTR.insertCell([6]);
	oTL6.innerHTML = "<input class='txtshort' type='text' name='order[orderequ][" + mycount
			+ "][money]' id='money" + mycount
			+ "' />";

	var oTL7 = oTR.insertCell([7]);
	oTL7.innerHTML = "<input  id='projArraDate" + mycount + "'type='text'  class='txtshort' name='order[orderequ]["
	                  + mycount + "][projArraDate]' value='"+ deliveryDate +"' onfocus='WdatePicker()'>"

	var oTL8 = oTR.insertCell([8]);
	oTL8.innerHTML = "<input type='text' class='txtshort' name='order[orderequ][" + mycount + "][warrantyPeriod]' id='warrantyPeriod" + mycount + "'>" ;

	var oTL9 = oTR.insertCell([9]);
	oTL9.innerHTML = "<input type='hidden' id='licenseId" + mycount + "' name='order[orderequ][" + mycount + "][license]' value=''/><input type='button' name='' class='txt_btn_a' value='����' onclick='License(\"licenseId" + mycount + "\");'>";
	var oTL10 = oTR.insertCell([10]);
	oTL10.innerHTML = "<input type='checkbox' name='order[orderequ][" + mycount + "][isSell]' checked='checked'>"+
	                  "<input type='hidden' name='order[orderequ][" + mycount + "][isCon]'id='isCon" + mycount + "'>"+
	                  "<input type='hidden' name='order[orderequ][" + mycount + "][isConfig]' id='isConfig" + mycount + "'>";
	var oTL11 = oTR.insertCell([11]);
	oTL11.innerHTML = "<img src='images/closeDiv.gif' onclick='mydelT(this,\"" + packinglist.id + "\")' title='ɾ����' id='Del"+mycount+"'>";


	document.getElementById(countNumP).value = document.getElementById(countNumP).value * 1 + 1;
//ǧ��λ������
    createFormatOnClick('number'+mycount,'number' +mycount ,'price' +mycount ,'money'+ mycount);
    createFormatOnClick('price'+mycount,'number' +mycount ,'price'+mycount,'money'+ mycount);
    createFormatOnClick('money'+mycount,'number' +mycount ,'price'+mycount,'money'+ mycount);
}

/** ********************ɾ����̬��************************ */
function myedit(obj,mytable) {
		var rowNo = obj.parentNode.parentNode.rowIndex * 1 - 1;
		var $row=$(obj.parentNode.parentNode);
			rowLength = $("tr[parentRowId="+$row.find("input[id^='isCon']").val()+"]").size();
			  for(rowL=rowNo+1;rowL<=rowNo+rowLength;rowL++){
			     $("tr[parentRowId="+$row.find("input[id^='isCon']").val()+"]").append("<input type='text' name='order[orderequ]["+ rowL +"][isDel]' value='1'>");
		         $("tr[parentRowId="+$row.find("input[id^='isCon']").val()+"]").hide();
			  }
		var mytable = document.getElementById(mytable);
		var myrows = mytable.rows.length;

		for (i = 1; i < myrows; i++) {
			mytable.rows[i].childNodes[0].innerHTML = i;
		}
}
function myeditT(obj,mytable) {
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
function mydel(obj, mytable) {
	if (confirm('ȷ��Ҫɾ�����У�')) {
		var rowNo = obj.parentNode.parentNode.rowIndex * 1 - 1;
		var $row=$(obj.parentNode.parentNode);

		if($row.find("input[id^='isConfig']").val() == ''){
			rowLength = $("tr[parentRowId="+$row.find("input[id^='isCon']").val()+"]").size();
			  for(rowL=rowNo+1;rowL<=rowNo+rowLength;rowL++){
			     $("tr[parentRowId="+$row.find("input[id^='isCon']").val()+"]").append("<input type='text' name='order[orderequ]["+ rowL +"][isDel]' value='1'>");
		         $("tr[parentRowId="+$row.find("input[id^='isCon']").val()+"]").hide();
			  }
			}
		$row.append("<input type='text' name='order[orderequ]["+ rowNo +"][isDel]' value='1'>");
		$row.hide();

		var mytable = document.getElementById(mytable);
		var myrows = mytable.rows.length;

		for (i = 1; i < myrows; i++) {
			mytable.rows[i].childNodes[0].innerHTML = i;
		}
	}
}
function mydelT(obj, mytable) {
	if (confirm('ȷ��Ҫɾ�����У�')) {
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
/**
 * ���¼��������
 *
 * @param {}
 * name
 */
function recount(mytable) {
	var mytable = document.getElementById(mytable);

	var myrows = mytable.rows.length;
	for (i = 0; i < myrows; i++) {
		mytable.rows[i].childNodes[0].innerHTML = i ;
	}
}
/** *****************���ؼƻ�******************************* */
function dis(name) {
	var temp = document.getElementById(name);
	if (temp.style.display == '')
		temp.style.display = "none";
	else if (temp.style.display == "none")
		temp.style.display = '';
}
/******************��ͬ �������� ---  ��ͬ��������***************************************/
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