

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
}
$(function (){
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
});
// ѡ����ұ�
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
	$("#orderPrincipal").yxselect_user({
				hiddenId : 'orderPrincipalId'
			});
    $("#sectionPrincipal").yxselect_user({
				hiddenId : 'sectionPrincipalId'
			});
	$("#sciencePrincipal").yxselect_user({
				hiddenId : 'sciencePrincipalId'
			});
});

/** ********************ɾ����̬��************************ */
function mydel(obj, mytable, orderArray) {
	if (confirm('ȷ��Ҫɾ�����У�')) {
		var rowNo = obj.parentNode.parentNode.rowIndex * 1 - 1;
		var $row=$(obj.parentNode.parentNode);
		$row.append("<input type='hiden' name='serviceContract["+ orderArray +"]["+ rowNo +"][isDel]' value='1'>");
		$row.hide();

		var mytable = document.getElementById(mytable);
//		mytable.deleteRow(rowNo);
		var myrows = mytable.rows.length;

		for (i = 1; i < myrows; i++) {
//			alert(mytable.rows[i].childNodes[0].innerHTML);
			mytable.rows[i].childNodes[0].innerHTML = i;
		}
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




// **********��ѵ�ƻ�***********************

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
			+ mytra.id + "\")' title='ɾ����'>";
	document.getElementById(countNum).value = document.getElementById(countNum).value
			* 1 + 1;
}
/****************************�����嵥/��������*************************************************/

function serviceList_add(mytra, countNum) {
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
     oTL1.innerHTML = "<input type='text' size='55' name='serviceContract[servicelist]["+ mycount +"][serviceItem]' id='serviceItem"+ mycount +"'>";
     oTL2 = oTR.insertCell([2]);
     oTL2.innerHTML = "<input type='text' class='txt' name='serviceContract[servicelist]["+ mycount +"][serviceNo]' id='serviceNo"+ mycount +"'>";
     oTL3 = oTR.insertCell([3]);
     oTL3.innerHTML = "<input type='text' size='45' name='serviceContract[servicelist]["+ mycount +"][serviceRemark]' id='serviceRemark"+ mycount +"'> ";

     oTL4 = oTR.insertCell([4]);
     oTL4.innerHTML = "<img src='images/closeDiv.gif' onclick='mydel(this,\""+ List.id + "\")' title='ɾ����'>";
	document.getElementById(countNum).value = document.getElementById(countNum).value
			* 1 + 1;
}

/*****************************************************************************************************************/
//
//$(function() {
//	 var flag = $("#check").val();
//     if (flag == "��"){
//       $("#orderCode").attr("disabled",true).unFormValidator(true);
//        alert(1)
//     }
//
//
//});
/***********************************************************************************************/

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

/** ********************��Ʒ��Ϣ************************ */
function dynamic_add(packinglist, countNumP) {
	deliveryDate = $('#deliveryDate').val();
	mycount = document.getElementById(countNumP).value * 1 + 1;
	var packinglist = document.getElementById(packinglist);
	i = packinglist.rows.length;
	oTR = packinglist.insertRow([i]);


	var oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = i;
	var oTL1 = oTR.insertCell([1]);
	oTL1.innerHTML = "<input type='text' id='productNo" + mycount + "' class='txtmiddle' name='serviceContract[serviceequ][" + mycount + "][productNo]' readonly>";

	  // ��ѡ��Ʒ
	$("#productNo" + mycount).yxcombogrid_product({
		hiddenId : 'productId' + mycount,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(mycount){
						return function(e, row, data) {
						$("#productName"+mycount).val(data.productName);
						$("#productModel"+mycount).val(data.pattern);
						$("#unitName" + mycount).val(data.unitName);
						$("#warrantyPeriod" + mycount).val(data.warranty);
					};
			  	}(mycount)
			}
		}
	});
	var oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML = "<input type='hidden' id='productId" + mycount + "'  name='serviceContract[serviceequ][" + mycount + "][productId]'/>" +
			         "<input id='productName" + mycount + "' type='text' class='txt' name='serviceContract[serviceequ][" + mycount + "][productName]' readonly/>";
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
					};
			  	}(mycount)
			}
		}
	});
	var oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML = "<input id='productModel" + mycount + "' type='text' class='txtmiddle' name='serviceContract[serviceequ][" + mycount + "][productModel]' readonly>";

    var oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML = "<input class='txtshort' type='text' name='serviceContract[serviceequ][" + mycount + "][number]' id='number" + mycount + "' onblur='FloatMul(\"number" + mycount + "\",\"price" + mycount + "\",\"money" + mycount
			+ "\")' />";
	var oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML = "<input class='txtshort' type='text' name='serviceContract[serviceequ][" + mycount + "][price]' id='price" + mycount + "' onblur='FloatMul(\"number" + mycount + "\",\"price" + mycount + "\",\"money" + mycount + "\")' />";

	var oTL6 = oTR.insertCell([6]);
	oTL6.innerHTML = "<input class='txtshort' type='text' name='serviceContract[serviceequ][" + mycount + "][money]' id='money" + mycount
			+ "' />";

	var oTL7 = oTR.insertCell([7]);
	oTL7.innerHTML = "<input type='text' class='txtshort' name='serviceContract[serviceequ][" + mycount + "][warrantyPeriod]' id='warrantyPeriod" + mycount + "'>" ;

	var oTL8 = oTR.insertCell([8]);
	oTL8.innerHTML = "<input type='hidden' id='LicenseId" + mycount + "' name='serviceContract[serviceequ][" + mycount + "][license]'/>" +
			         "<input type='button' name='' class='txt_btn_a' value='����' onclick='License(\"LicenseId" + mycount + "\");'>";


	var oTL9 = oTR.insertCell([9]);
	oTL9.innerHTML = "<img src='images/closeDiv.gif' onclick='mydel(this,\"" + packinglist.id + "\")' title='ɾ����'>";


	document.getElementById(countNumP).value = document.getElementById(countNumP).value * 1 + 1;
//ǧ��λ������
    createFormatOnClick('number'+mycount,'number' +mycount ,'price' +mycount ,'money'+ mycount);
    createFormatOnClick('price'+mycount,'number' +mycount ,'price'+mycount,'money'+ mycount);
    createFormatOnClick('money'+mycount,'number' +mycount ,'price'+mycount,'money'+ mycount);
}

/*******************************************************************************************************************************/

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

function reloadCombo() {
	// alert( $("#customerLinkman").yxcombogrid('grid').param );
	$("#linkMan").yxcombogrid('grid').reload;
}
//�ͻ���ϵ��
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

//����ǩ�� Ϊ���۵��ж���������
// $(function(){
//   var objCode = $("#objCode").val();
//   var Ovalue = $("#signinType").val();
//   var com = $.ajax({
//		    type : 'POST',
//		    url : "?model=engineering_project_esmproject&action=existEsmproject",
//					    data:{
//					       objCode : objCode
//					    },
//					    async: false,
//					    success : function(data){
//					        if(data == 1){
//					           return 1;
//					        }else{
//					           return 0;
//					        }
// 					    }
//					}).responseText;
//					if(com == 1){
//                       $("#signinType").attr('disabled',true);
//           $("#signinType").attr('title',"�˷����ͬ�ѹ���������Ŀ�޷�ǩ��Ϊ��������");
//			}
// })
/** ****************��ͬ �������� --- ��ͬ��������************************************** */
$(function() {
	$("#areaName").yxcombogrid_area({
		hiddenId : 'areaCode',
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#areaPrincipal").val(data.areaPrincipal);
					$("#areaCode").val(data.id);
					$("#areaPrincipalId_v").val(data.areaPrincipalId);
				}
			}
		}
	});

});