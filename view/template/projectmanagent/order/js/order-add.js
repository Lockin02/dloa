
function toAppance(){
	document.getElementById('form1').action = "index1.php?model=projectmanagent_order_order&action=add&action=add&act=app";

}
function toSave(){
	document.getElementById('form1').action = "index1.php?model=projectmanagent_order_order&action=add&action=add";

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
//�������Ψһ����֤
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


/***********************************************************************************************/

$(function() {
	temp = $('#productNumber').val();
	for(var i=1;i<=temp;i++){
	$("#productName" + i).yxcombogrid_product({
		hiddenId : 'productId'+i,
		gridOptions : {
			showcheckbox : false,
				event : {
					'row_dblclick' : function(i){
						return function(e, row, data) {
							$("#productNo" + i).val(data.sequence);
							$("#productModel" + i).val(data.pattern);
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
	oTL1.innerHTML = "<input type='text' id='productNo" + mycount
			+ "' class='txtmiddle' name='order[orderequ][" + mycount
			+ "][productNo]' readonly>";
	var oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML = "<input type='hidden' id='productId" + mycount + "'  name='order[orderequ][" + mycount + "][productId]'/>" +
			"<input id='productName" + mycount + "' type='text' class='txt' name='order[orderequ][" + mycount + "][productName]' readonly/>";



	// ��ѡ��Ʒ
	$("#productName" + mycount).yxcombogrid_product({
		hiddenId : 'productId' + mycount,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(mycount){
						return function(e, row, data) {
						$("#productNo"+mycount).val(data.sequence);
						$("#productModel"+mycount).val(data.pattern);
					};
			  	}(mycount)
			}
		}
	});

	var oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML = "<input id='productModel" + mycount
			+ "' type='text' class='txtmiddle' name='order[orderequ]["
			+ mycount + "][productModel]' readonly>";

    var oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML = "<input class='txtshort' type='text' name='order[orderequ][" + mycount
			+ "][number]' id='number" + mycount
			+ "' onblur='FloatMul(\"number" + mycount + "\",\"price"
			+ mycount + "\",\"money" + mycount
			+ "\")' />";
	var oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML = "<input class='txtshort' type='text' name='order[orderequ][" + mycount
			+ "][price]' id='price" + mycount
			+ "' onblur='FloatMul(\"number" + mycount + "\",\"price"
			+ mycount + "\",\"money" + mycount
			+ "\")' />";
	  //�������ͳ���¼�
    $("#price" + mycount).bind("blur",function(){
		countAll();
    });
	var oTL6 = oTR.insertCell([6]);
	oTL6.innerHTML = "<input class='txtshort' type='text' name='order[orderequ][" + mycount
			+ "][money]' id='money" + mycount
			+ "' />";

	var oTL7 = oTR.insertCell([7]);
	oTL7.innerHTML = "<input  id='projArraDate" + mycount + "'type='text'  class='txtshort' name='order[orderequ]["
	                  + mycount + "][projArraDate]' value='"+ deliveryDate +"' onfocus='WdatePicker()'>"

	var oTL8 = oTR.insertCell([8]);
	oTL8.innerHTML = "<select class='txtshort' name='order[orderequ]["
			+ mycount
			+ "][warrantyPeriod]' id='warrantyPeriod"
			+ mycount
			+ "'>"
			+ "<option value='����'>����</option>"
			+ "<option value='һ��'>һ��</option>"
			+ "<option value='����'>����</option>"
			+ "<option value='����'>����</option>"
			+ "</select>";
	var oTL9 = oTR.insertCell([9]);
	oTL9.innerHTML = "<input type='checkbox' name='order[orderequ]["
			+ mycount
			+ "][isSell]' checked='checked'>";

	var oTL10 = oTR.insertCell([10]);
	oTL10.innerHTML = "<img src='images/closeDiv.gif' onclick='mydel(this,\""
			+ packinglist.id + "\")' title='ɾ����'>";


	document.getElementById(countNumP).value = document.getElementById(countNumP).value * 1 + 1;
    createFormatOnClick('price'+mycount);
    createFormatOnClick('money'+mycount);

}
/************************�Զ����嵥***********************************/

function pre_add(mycustom, countNum) {
	mycount = document.getElementById(countNum).value * 1 + 1;
	var mycustom = document.getElementById(mycustom);
	i = mycustom.rows.length;
	oTR = mycustom.insertRow([i]);
	oTR.className = "TableData";
	oTR.align = "center";
	oTR.height = "30px";
	oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = i;
	var oTL1 = oTR.insertCell([1]);
	oTL1.innerHTML = "<input class='txtshort' type='text' name='order[customizelist][" + mycount
			+ "][productCode]' id='PequID" + mycount
			+ "' value='' size='10' maxlength='40'/>";
	var oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML = "<input class='txtshort' type='text' name='order[customizelist][" + mycount
			+ "][productName]' id='PequName" + mycount
			+ "' value='' size='15' maxlength='20'/>";
	var oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML = "<input class='txtshort' type='text' name='order[customizelist][" + mycount
			+ "][productModel]' id='PreModel" + mycount
			+ "' value='' size='10' maxlength='40'/>";
	var oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML = "<input class='txtshort' type='text' name='order[customizelist][" + mycount
			+ "][amount]' id='PreAmount" + mycount
			+ "' onblur='FloatMul(\"PreAmount" + mycount + "\",\"PrePrice"
			+ mycount + "\",\"CountMoney" + mycount
			+ "\")' size='8' maxlength='40'/>";
	var oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML = "<input class='txtshort' type='text' name='order[customizelist][" + mycount
			+ "][price]' id='PrePrice" + mycount
			+ "' onblur='FloatMul(\"PreAmount" + mycount + "\",\"PrePrice"
			+ mycount + "\",\"CountMoney" + mycount
			+ "\")' size='8' maxlength='40'/>";
	var oTL6 = oTR.insertCell([6]);
	oTL6.innerHTML = "<input class='txtshort' type='text' name='order[customizelist][" + mycount
			+ "][money]' id='CountMoney" + mycount
			+ "' size='8' maxlength='40'/>";
	var oTL7 = oTR.insertCell([7]);
	oTL7.innerHTML = "<input class='txtshort' type='text' name='order[customizelist][" + mycount
			+ "][projArraDT]' id='PreDeliveryDT" + mycount
			+ "' size='10' onfocus='WdatePicker()'>";
	var oTL8 = oTR.insertCell([8]);
	oTL8.innerHTML = "<input class='txt' type='text' name='order[customizelist][" + mycount
			+ "][remark]' id='PRemark" + mycount
			+ "' value='' size='18' maxlength='100'/>";
	var oTL9 = oTR.insertCell([9]);
	oTL9.innerHTML = "<input type='checkbox' name='order[customizelist][" + mycount
			+ "][isSell]' id='customizelistSell" + mycount
			+ "' checked='checked' />";
	var oTL10 = oTR.insertCell([10]);
	oTL10.innerHTML = "<img src='images/closeDiv.gif' onclick='mydel(this,\""
			+ mycustom.id + "\")' title='ɾ����'>";
	document.getElementById(countNum).value = document.getElementById(countNum).value
			* 1 + 1;
	createFormatOnClick('PrePrice'+mycount,'PreAmount'+mycount,'PrePrice'+mycount,'CountMoney'+mycount);
	createFormatOnClick('CountMoney'+mycount,'PreAmount'+mycount,'PrePrice'+mycount,'CountMoney'+mycount);
}

/**********************��Ʊ�ƻ�********************************/

function inv_add(myinv, countNum) {
	mycount = document.getElementById(countNum).value * 1 + 1;
	var myinv = document.getElementById(myinv);
	i = myinv.rows.length;
	oTR = myinv.insertRow([i]);
	oTR.className = "TableData";
	oTR.align = "center";
	oTR.height = "30px";
	oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = i;
	oTL1 = oTR.insertCell([1]);
	oTL1.innerHTML = "<input class='txtshort' type='text' name='order[invoice][" + i
			+ "][money]' id='InvMoney" + mycount
			+ "' size='10' maxlength='40'/>";
	oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML = "<input class='txtshort' type='text' name='order[invoice][" + i
			+ "][softM]' id='InvSoftM" + mycount
			+ "' size='10' maxlength='40'/>";
	oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML = "<select class='txtmiddle' name='order[invoice]["
			+ i
			+ "][iType]' id='invoiceListType"+ mycount +"'></select>";
	oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML = "<input class='txtshort' type='text' name='order[invoice][" + i
			+ "][invDT]' id='InvDT" + mycount
			+ "' size='12' onfocus='WdatePicker()'>";
	oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML = "<input class='txtlong' type='text' name='order[invoice][" + i
			+ "][remark]' id='InvRemark" + mycount
			+ "' size='60' maxlength='100'/>";
	oTL6 = oTR.insertCell([6]);
	oTL6.innerHTML = "<img src='images/closeDiv.gif' onclick='mydel(this,\""
			+ myinv.id + "\")' title='ɾ����'>";
	document.getElementById(countNum).value = document.getElementById(countNum).value
			* 1 + 1;
	addDataToSelect(invoiceTypeArr, 'invoiceListType' + mycount);

	createFormatOnClick('InvMoney'+mycount);
	createFormatOnClick('InvSoftM'+mycount);
}

/************************�տ�ƻ�**************************************/

function pay_add(mypay, countNum) {
	mycount = document.getElementById(countNum).value * 1 + 1;
	var mypay = document.getElementById(mypay);
	i = mypay.rows.length;
	oTR = mypay.insertRow([i]);
	oTR.className = "TableData";
	oTR.align = "center";
	oTR.height = "30px";
	oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = i;
	oTL1 = oTR.insertCell([1]);
	oTL1.innerHTML = "<input class='txtshort' type='text' name='order[receiptplan][" + mycount
			+ "][money]' id='PayMoney" + mycount
			+ "' value='' size='10' maxlength='40'/>";
	oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML = "<input class='txtshort' type='text' name='order[receiptplan][" + mycount
			+ "][payDT]' id='PayDT" + mycount
			+ "' size='12' onfocus='WdatePicker()'>";
	oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML = "<select class='txtshort' name='order[receiptplan]["
			+ mycount
			+ "][pType]'><option value='���'>���</option><option value='�ֽ�'>�ֽ�</option><option value='���л�Ʊ'>���л�Ʊ</option><option value='��ҵ��Ʊ'>��ҵ��Ʊ</option></select>";
	oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML = "<input class='txtlong' type='text' name='order[receiptplan][" + mycount
			+ "][collectionTerms]' id='collectionTerms" + mycount
			+ "' size='70' />";
	oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML = "<img src='images/closeDiv.gif' onclick='mydel(this,\""
			+ mypay.id + "\")' title='ɾ����'>";
	document.getElementById(countNum).value = document.getElementById(countNum).value
			* 1 + 1;

	createFormatOnClick('PayMoney'+mycount);
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
	oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = i;
	oTL1 = oTR.insertCell([1]);
	oTL1.innerHTML = "<input class='txtshort' type='text' name='order[trainingplan][" + mycount
			+ "][beginDT]' id='TraDT" + mycount
			+ "' size='10' onfocus='WdatePicker()'>";
	oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML = "<input class='txtshort' type='text' name='order[trainingplan][" + mycount
			+ "][endDT]' id='TraEndDT" + mycount
			+ "' size='10' onfocus='WdatePicker()'>";
	oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML = "<input class='txtshort' type='text' name='order[trainingplan][" + mycount
			+ "][traNum]' value='' size='8' maxlength='40'/>";
	oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML = "<textarea name='order[trainingplan][" + mycount
			+ "][adress]' rows='3' cols='15' style='width: 100%'></textarea>";
	oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML = "<textarea name='order[trainingplan][" + mycount
			+ "][content]' rows='3' style='width: 100%'></textarea>";
	oTL6 = oTR.insertCell([6]);
	oTL6.innerHTML = "<textarea name='order[trainingplan][" + mycount
			+ "][trainer]' rows='3' style='width: 100%'></textarea>";
	oTL7 = oTR.insertCell([7]);
	oTL7.innerHTML = "<img src='images/closeDiv.gif' onclick='mydel(this,\""
			+ mytra.id + "\")' title='ɾ����'>";
	document.getElementById(countNum).value = document.getElementById(countNum).value
			* 1 + 1;
}

/** ********************ɾ����̬��************************ */
function mydel(obj, mytable) {
	if (confirm('ȷ��Ҫɾ�����У�')) {
		var rowNo = obj.parentNode.parentNode.rowIndex * 1 - 1;
		var mytable = document.getElementById(mytable);
		mytable.deleteRow(rowNo);
		var myrows = mytable.rows.length;
		for (i = 1; i < myrows; i++) {
//			alert(mytable.rows[i].childNodes[0].innerHTML);
			mytable.rows[i].childNodes[0].innerHTML = i;
		}
	}
	countAll();
}

/** *****************���ؼƻ�******************************* */
function dis(name) {
	var temp = document.getElementById(name);
	if (temp.style.display == '')
		temp.style.display = "none";
	else if (temp.style.display == "none")
		temp.style.display = '';
}
