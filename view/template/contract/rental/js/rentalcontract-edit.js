//����ύ���͡�Ϊ���ύ��ˡ�����һ��ʶ�����app
function comitToApp(){

	document.getElementById('form1').action = "index1.php?model=contract_rental_rentalcontract&action=add&act=app";
}

//���ʼ���
function conversion(){
   var currency = $("#currency").val();

   if(currency != '�����' && currency != ''){
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
   }else if(currency == '�����'){
        $("#orderMoney_v").attr('class',"txt");
        $("#orderMoney_v").attr('readOnly',false);
        $("#orderTempMoney_v").attr('class',"txt");
        $("#orderTempMoney_v").attr('readOnly',false);
        $("#currencyRate").hide();
	}
};
// ѡ����ұ�
$(function() {
	conversion();
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

// ��֯����ѡ��
$(function() {
	$("#hiresName").yxselect_user({
				hiddenId : 'hiresId'
			});
    $("#scienceMan").yxselect_user({
				hiddenId : 'scienceManId'
			});
});

// ���⿪ʼ����ֹ��������
$(function() {
	//���⿪ʼ����
	$('#beginTime').bind('focusout', function() {
		var thisDate = $(this).val();
		beginTime = $('#beginTime').val();
		$.each($(':input[id^="proBeginTime"]'), function(i, n) {
			$(this).val(thisDate);
		})
	});
	//�����ֹ����
	$('#closeTime').bind('focusout', function() {
		var thisDate = $(this).val();
		beginTime = $('#closeTime').val();
		$.each($(':input[id^="proEndTime"]'), function(i, n) {
			$(this).val(thisDate);
		})
	});

});


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


$(function(){
	//�ͻ�����
	customerTypeArr = getData('KHLX');
		addDataToSelect(customerTypeArr, 'customerType');
		addDataToSelect(customerTypeArr, 'customerListTypeArr1');
   //��Ʊ����
	invoiceTypeArr = getData('FPLX');
	    addDataToSelect(invoiceTypeArr, 'invoiceType');
		addDataToSelect(invoiceTypeArr, 'invoiceListType1');

     //��ͬ����
	orderNatureCodeArr = getData('ZLHTSX');
	    addDataToSelect(orderNatureCodeArr, 'orderNature');
//		addDataToSelect(orderNatureCodeArr, 'invoiceListType1');
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
	$("#tenant").yxcombogrid_customer({
		hiddenId : 'tenantId',
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
					$("#customerProvince_v").val(data.Prov);
					$("#customerId").val(data.id);
					$("#province").val(data.ProvId);//����ʡ��Id
				    $("#province").trigger("change");
				    $("#provinceName").val(data.Prov);//����ʡ��
					$("#city").val(data.CityId);//����ID
					$("#cityName").val(data.City);//��������
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
	oTR.id = "linkDetail" + mycount;
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
	oTL3.innerHTML = "<input class='txt' type='text' name='rentalcontract[linkman][" + mycount
			+ "][Email]' id='Email" + mycount + "'/>";
	oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML = "<input class='txtlong' type='text' name='order[linkman][" + mycount
			+ "][remark]' id='Lremark" + mycount + "'/>";
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
function conInfo(productId,proRemarkNum){
	    var proId = $("#"+productId).val();
	    var proRemarkId = "remark" + proRemarkNum;
	    var orderId = $("#id").val();
	    if (proId == ''){
	        alert("����ѡ�����ϡ�");
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
							$("#number" + i).val(1);

							var encrypt = data.encrypt;
							if(encrypt == 'on'){
								alert("�����������ü��ܱ������ԣ�����д��������");
								License('LicenseId' + i);
							}
							var allocation = data.allocation;
								$("#isCon" + i).val("isCon_" + i);
								       $.get('index1.php', {
											model : 'contract_rental_rentalcontract',
											action : 'ajaxorder',
											id : data.id,
											trId : "isCon_" + i,
											Num : $("productNumber").val(),
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
                            $("#number" + i).val(1);

							var encrypt = data.encrypt;
							if(encrypt == 'on'){
								alert("�����������ü��ܱ������ԣ�����д��������");
								License('LicenseId' + i);
							}
							var allocation = data.allocation;
								$("#isCon" + i).val("isCon_" + i);
								       $.get('index1.php', {
											model : 'contract_rental_rentalcontract',
											action : 'ajaxorder',
											id : data.id,
											trId : "isCon_" + i,
											Num : $("#productNumber").val(),
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
/** ********************��Ʒ��Ϣ************************ */
function dynamic_add(packinglist, countNumP) {
	deliveryDate = $('#deliveryDate').val();
	beginTime = $('#beginTime').val();
	endTime = $('#closeTime').val();

   // ��ȡ��ǰ���� ,���������
	var rowNums = $("tr[name^='equTab_']").length * 1 + $("tr[id^='equTab_']").length * 1;
	// ��ȡ��ǰ����ֵ,������id����
	mycount = $('#' + countNumP).val() * 50 + 1;
	// ����������
	var packinglist = document.getElementById(packinglist);
	// ������
	oTR = packinglist.insertRow([rowNums]);
	oTR.id = "equTab_" + mycount;
	// ��ǰ�к�
	i = rowNums + 1;
	var oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = i;
	var oTL1 = oTR.insertCell([1]);
	oTL1.innerHTML = "<input type='text' id='productNo" + mycount + "' class='txtmiddle' name='rentalcontract[rentalcontractequ][" + mycount + "][productNo]' >";
	// ��ѡ��Ʒ
	$("#productNo" + mycount).yxcombogrid_product({
		hiddenId : 'productId' + mycount,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(mycount){
						return function(e, row, data) {
							myedit(document.getElementById("Del"+mycount),"invbody");
						$("#productName"+mycount).val(data.productName);
						$("#productModel"+mycount).val(data.pattern);
						$("#unitName" + mycount).val(data.unitName);
						$("#warrantyPeriod" + mycount).val(data.warranty);
						$("#number" + mycount + "_v").val(1);
                        $("#number" + mycount).val(1);
						var encrypt = data.encrypt;
							if(encrypt == 'on'){
								alert("�����������ü��ܱ������ԣ�����д��������");
								License('LicenseId' + mycount);
							}
					    var allocation = data.allocation;
								 $("#isCon" + mycount).val("isCon_" + mycount);
								       $.get('index1.php', {
											model : 'contract_rental_rentalcontract',
											action : 'ajaxorder',
											id : data.id,
											trId : "isCon_" + mycount,
											Num : mycount,
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
	oTL2.innerHTML = "<input type='hidden' id='productId" + mycount + "'  name='rentalcontract[rentalcontractequ][" + mycount + "][productId]'/>" +
			"<input id='productName" + mycount + "' type='text' class='txt' name='rentalcontract[rentalcontractequ][" + mycount + "][productName]' />"+
			"&nbsp<img src='images/icon/icon105.gif' onclick='conInfo(\"productId"+ mycount +"\",\""+mycount+"\");' title='�鿴������Ϣ'/>";;
	$("#productName" + mycount).yxcombogrid_productName({
		hiddenId : 'productId' + mycount,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(mycount){
						return function(e, row, data) {
							myedit(document.getElementById("Del"+mycount),"invbody");
						$("#productNo"+mycount).val(data.productCode);
						$("#productModel"+mycount).val(data.pattern);
						$("#unitName" + mycount).val(data.unitName);
						$("#warrantyPeriod" + mycount).val(data.warranty);
						$("#number" + mycount + "_v").val(1);
                        $("#number" + mycount).val(1);
						var encrypt = data.encrypt;
							if(encrypt == 'on'){
								alert("�����������ü��ܱ������ԣ�����д��������");
								License('LicenseId' + mycount);
							}
					    var allocation = data.allocation;
								 $("#isCon" + mycount).val("isCon_" + mycount);
								       $.get('index1.php', {
											model : 'contract_rental_rentalcontract',
											action : 'ajaxorder',
											id : data.id,
											trId : "isCon_" + mycount,
											Num : mycount,
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
	oTL3.innerHTML = "<input id='productModel" + mycount + "' type='text' class='txtmiddle' name='rentalcontract[rentalcontractequ][" + mycount + "][productModel]' readonly>";
    var oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML = "<input class='txtshort' type='text' name='rentalcontract[rentalcontractequ][" + mycount + "][number]' id='number" + mycount + "' />";
	var oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML = "<input class='txtshort' type='text' name='rentalcontract[rentalcontractequ][" + mycount + "][price]' id='price" + mycount + "' />";
	var oTL6 = oTR.insertCell([6]);
	oTL6.innerHTML = "<input class='txtshort' type='text' name='rentalcontract[rentalcontractequ][" + mycount + "][money]' id='money" + mycount + "' />";
	var oTL7 = oTR.insertCell([7]);
	oTL7.innerHTML = "<input type='text' class='txtshort' name='rentalcontract[rentalcontractequ][" + mycount + "][warrantyPeriod]' id='warrantyPeriod" + mycount + "'>" ;
	var oTL8 = oTR.insertCell([8]);
	oTL8.innerHTML = "<input type='text' class='txtshort' name='rentalcontract[rentalcontractequ]["+ mycount +"][proBeginTime]' id='proBeginTime"+mycount+"' value='" + beginTime + "' onfocus='WdatePicker()'/>";
    var oTL9 = oTR.insertCell([9]);
    oTL9.innerHTML = "<input type='text' class='txtshort' name='rentalcontract[rentalcontractequ]["+mycount+"][proEndTime]' id='proEndTime"+mycount+"' value='" + endTime + "' onfocus='WdatePicker()'/>";
	var oTL10 = oTR.insertCell([10]);
	oTL10.innerHTML = "<input type='hidden' id='LicenseId" + mycount + "' name='rentalcontract[rentalcontractequ][" + mycount + "][license]'/>" +
			          "<input type='button' name='' class='txt_btn_a' value='����' onclick='License(\"LicenseId" + mycount + "\");'>"+
			          "<input type='hidden' name='rentalcontract[rentalcontractequ][" + mycount + "][isCon]'id='isCon" + mycount + "'>"+
	                  "<input type='hidden' name='rentalcontract[rentalcontractequ][" + mycount + "][isConfig]' id='isConfig" + mycount + "'>"+
	                  "<input type='hidden' name='rentalcontract[rentalcontractequ][" + mycount + "][remark]' id='remark"+mycount+"'>";
    var oTL11 = oTR.insertCell([11]);
	oTL11.innerHTML = "<input type='checkbox' name='rentalcontract[rentalcontractequ][" + mycount + "][isSell]' checked='checked'>";
	var oTL12 = oTR.insertCell([12]);
	oTL12.innerHTML = "<img src='images/closeDiv.gif' onclick='mydel(this,\"" + packinglist.id + "\")' title='ɾ����' id='Del"+mycount+"'>";


	document.getElementById(countNumP).value = document.getElementById(countNumP).value * 1 + 1;
//ǧ��λ������
    createFormatOnClick('number'+mycount,'number' +mycount ,'price' +mycount ,'money'+ mycount);
    createFormatOnClick('price'+mycount,'number' +mycount ,'price'+mycount,'money'+ mycount);
    createFormatOnClick('money'+mycount,'number' +mycount ,'price'+mycount,'money'+ mycount);

}



/**************************************************************�Զ����嵥*******************************************************************/

function pre_add(mycustom, countNum) {
	mycount = document.getElementById(countNum).value * 1 + 1;
	var mycustom = document.getElementById(mycustom);
	i = mycustom.rows.length;
	oTR = mycustom.insertRow([i]);
	oTR.className = "TableData";
	oTR.align = "center";
	oTR.height = "30px";
	j = i+1;
	oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = j;
	var oTL1 = oTR.insertCell([1]);
	oTL1.innerHTML = "<input class='txtshort' type='text' name='rentalcontract[customizelist][" + mycount
			+ "][productCode]' id='PequID" + mycount
			+ "' value='' size='10' maxlength='40'/>";
	var oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML = "<input class='txtshort' type='text' name='rentalcontract[customizelist][" + mycount
			+ "][productName]' id='PequName" + mycount
			+ "' value='' size='15' maxlength='20'/>";
	var oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML = "<input class='txtshort' type='text' name='rentalcontract[customizelist][" + mycount
			+ "][productModel]' id='PreModel" + mycount
			+ "' value='' size='10' maxlength='40'/>";
	var oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML = "<input class='txtshort' type='text' name='rentalcontract[customizelist][" + mycount
			+ "][number]' id='PreAmount" + mycount + "' />";
	var oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML = "<input class='txtshort' type='text' name='rentalcontract[customizelist][" + mycount
			+ "][price]' id='PrePrice" + mycount + "' />";
	var oTL6 = oTR.insertCell([6]);
	oTL6.innerHTML = "<input class='txtshort' type='text' name='rentalcontract[customizelist][" + mycount
			+ "][money]' id='CountMoney" + mycount + "' />";
	var oTL7 = oTR.insertCell([7]);
	oTL7.innerHTML = "<input class='txtshort' type='text' name='rentalcontract[customizelist][" + mycount
			+ "][projArraDT]' id='PreDeliveryDT" + mycount
			+ "' size='10' onfocus='WdatePicker()'>";
	var oTL8 = oTR.insertCell([8]);
	oTL8.innerHTML = "<input class='txt' type='text' name='rentalcontract[customizelist][" + mycount
			+ "][remark]' id='PRemark" + mycount
			+ "' value='' size='18' maxlength='100'/>";
	var oTL9 = oTR.insertCell([9]);
	oTL9.innerHTML = "<input type='hidden' id='cuslicenseId1" + mycount + "' name='rentalcontract[customizelist][" + mycount + "][license]'/>" +
			         "<input type='button' name='' class='txt_btn_a' value='����' onclick='License(\"cuslicenseId1" + mycount + "\");'>";

	var oTL10 = oTR.insertCell([10]);
	oTL10.innerHTML = "<input type='checkbox' name='rentalcontract[customizelist][" + mycount
			+ "][isSell]' id='customizelistSell" + mycount
			+ "' checked='checked' />";
	var oTL11 = oTR.insertCell([11]);
	oTL11.innerHTML = "<img src='images/closeDiv.gif' onclick='mydel(this,\""
			+ mycustom.id + "\")' title='ɾ����'>";
	document.getElementById(countNum).value = document.getElementById(countNum).value
			* 1 + 1;

	//ǧ��λ������
    createFormatOnClick('PreAmount'+mycount,'PreAmount' +mycount ,'PrePrice' +mycount ,'CountMoney'+ mycount);
	createFormatOnClick('PrePrice'+mycount,'PreAmount'+mycount,'PrePrice'+mycount,'CountMoney'+mycount);
	createFormatOnClick('CountMoney'+mycount,'PreAmount'+mycount,'PrePrice'+mycount,'CountMoney'+mycount);
}
/*************************��ѵ�ƻ�***********************************/

function train_add(mytra, countNum) {
	mycount = document.getElementById(countNum).value * 1 + 1;
	var mytra = document.getElementById(mytra);
	i = mytra.rows.length;
	oTR = mytra.insertRow([i]);
	oTR.className = "TableData";
	oTR.align = "center";
	oTR.height = "30px";
	j = i + 1;
	oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = j;
	oTL1 = oTR.insertCell([1]);
	oTL1.innerHTML = "<input class='txtshort' type='text' name='rentalcontract[trainingplan][" + mycount
			+ "][beginDT]' id='TraDT" + mycount
			+ "' size='10' onfocus='WdatePicker()'>";
	oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML = "<input class='txtshort' type='text' name='rentalcontract[trainingplan][" + mycount
			+ "][endDT]' id='TraEndDT" + mycount
			+ "' size='10' onfocus='WdatePicker()'>";
	oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML = "<input class='txtshort' type='text' name='rentalcontract[trainingplan][" + mycount
			+ "][traNum]' value='' size='8' maxlength='40'/>";
	oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML = "<textarea name='rentalcontract[trainingplan][" + mycount
			+ "][adress]' rows='3' cols='15' style='width: 100%'></textarea>";
	oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML = "<textarea name='rentalcontract[trainingplan][" + mycount
			+ "][content]' rows='3' style='width: 100%'></textarea>";
	oTL6 = oTR.insertCell([6]);
	oTL6.innerHTML = "<textarea name='rentalcontract[trainingplan][" + mycount
			+ "][trainer]' rows='3' style='width: 100%'></textarea>";
	oTL7 = oTR.insertCell([7]);
	oTL7.innerHTML = "<img src='images/closeDiv.gif' onclick='mydel(this,\""
			+ mytra.id + "\")' title='ɾ����'>";
	document.getElementById(countNum).value = document.getElementById(countNum).value
			* 1 + 1;
}

/** ********************ɾ����̬����************************ */
function myedit(obj, mytable) {
		var rowSize = $("#" + mytable).children().length;
		var rowNo = obj.parentNode.parentNode.rowIndex * 1 - 2;
		var mytable = document.getElementById(mytable);
		var objA = obj.parentNode.parentNode;
		if($(objA).find("input[id^='isConfig']").val() == ''){
		   $("tr[parentRowId="+$(objA).find("input[id^='isCon']").val()+"]").remove();
		}
		var myrows = rowSize - 1;
		for (i = 0; i < myrows; i++) {
//			mytable.rows[i].childNodes[0].innerHTML = i + 1;
		}
}
function mydel(obj, mytable) {
	if (confirm('ȷ��Ҫɾ�����У�')) {
		var rowSize = $("#" + mytable).children().length;
		var rowNo = obj.parentNode.parentNode.rowIndex * 1 - 2;
		var mytable = document.getElementById(mytable);
		var objA = obj.parentNode.parentNode;
		if($(objA).find("input[id^='isConfig']").val() == ''){
			var cong = $("tr[parentRowId="+$(objA).find("input[id^='isCon']").val()+"]").size();
		   $("tr[parentRowId="+$(objA).find("input[id^='isCon']").val()+"]").remove();
		}
		mytable.deleteRow(rowNo);
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
 * ���¼��������
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



function ajaxCode(){
	if ($('#orderCode').val() == '') {
//		$('#icon').html('����Ϊ�գ�');
	} else if ($('#orderCode').val() != '') {
		var param = {
			model : 'contract_rental_rentalcontract',
			action : 'ajaxCode',
			ajaxOrderCode : $('#orderCode').val()
		};
		if ($("#orderId").val() != '') {
			param.id = $("#orderId").val();
		}
		$.get('index1.php', param, function(data) {
					if (data == '1') {
						$('#icon').html('��ͬ���Ѵ��ڣ�');
						$("#orderCode").focus();
					} else {
						$('#icon').html('��');
					}
				})
	}
}

function ajaxTempCode(){
	if ($('#orderTempCode').val() == '') {
//		$('#icon1').html('����Ϊ�գ�');
//		$("#orderTempCode").focus();
	} else if ($('#orderTempCode').val() != '') {
		var param = {
			model : 'contract_rental_rentalcontract',
			action : 'ajaxTempCode',
			ajaxOrderTempCode : $('#orderTempCode').val()
		};
		if ($("#orderId").val() != '') {
			param.id = $("#orderId").val();
		}
		$.get('index1.php', param, function(data) {
					if (data == '1') {
						$('#icon1').html('��ͬ���Ѵ��ڣ�');
						$("#orderTempCode").focus();
					} else {
						$('#icon1').html('��');
					}
				})
	}
}

















