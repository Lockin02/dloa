// license����
// var licenseTypeArr = [];
var productLineArr = [];// ��Ʒ������
var invoiceTypeArr = [];//��Ʊ����
var licensetypeStore = null;// license����store
var licensetypeRecords = [];// license���ͼ�¼����
/*
 * ��̬���license����
 *
 * function addLicenseType(licenseTypeId, licenseTypeArr) { for (var i = 0, l =
 * licenseTypeArr.length; i < l; i++) { $("#" + licenseTypeId).append("<option
 * value='" + licenseTypeArr[i].id + "'>" + licenseTypeArr[i].typeName + "</option>"); } }
 */


	// ��Ʒ������
$(function(){
	productLineArr = getData('CPX');
	if (!$("#contNumber").val()) {
		addDataToSelect(productLineArr, 'productLine1');
		addDataToSelect(productLineArr, 'lProductLine1');
	}

	//��Ʊ����
	invoiceTypeArr = getData('FPLX');
	if (!$("#contNumber").val()) {
		addDataToSelect(invoiceTypeArr, 'invoiceType');
		addDataToSelect(invoiceTypeArr, 'invoiceListType1');
	}

	//�ͻ�����
	customerTypeArr = getData('KHLX');
	if (!$("#contNumber").val()) {
		addDataToSelect(customerTypeArr, 'customerType');
		addDataToSelect(customerTypeArr, 'customerListTypeArr1');
	}
});


// **************��Ʊ�ƻ�******************

function inv_add(myinv, countNum) {
	mycount = document.getElementById(countNum).value * 1 + 1;
	var myinv = document.getElementById(myinv);
	i = myinv.rows.length;
	oTR = myinv.insertRow([i]);
	oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = i;
	oTL1 = oTR.insertCell([1]);
	oTL1.innerHTML = "<input class='txtshort' type='text' name='sales[invoice][" + i
			+ "][money]' id='InvMoney" + mycount
			+ "'/>";
	oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML = "<input class='txtshort' type='text' name='sales[invoice][" + i
			+ "][softM]' id='InvSoftM" + mycount
			+ "'/>";
	oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML = "<select class='txtmiddle' name='sales[invoice]["
			+ i
			+ "][iType]' id='invoiceListType"+ mycount +"'></select>";
	oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML = "<input class='txtshort' type='text' name='sales[invoice][" + i
			+ "][invDT]' id='InvDT" + mycount
			+ "' onfocus='WdatePicker()'>";
	oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML = "<input class='txtlong' type='text' name='sales[invoice][" + i
			+ "][remark]' id='InvRemark" + mycount
			+ "' />";
	oTL6 = oTR.insertCell([6]);
	oTL6.innerHTML = "<img src='images/closeDiv.gif' onclick='equdel(this,\""
			+ myinv.id + "\")' title='ɾ����'>";
	document.getElementById(countNum).value = document.getElementById(countNum).value
			* 1 + 1;
	addDataToSelect(invoiceTypeArr, 'invoiceListType' + mycount);

	createFormatOnClick('InvMoney'+mycount);
	createFormatOnClick('InvSoftM'+mycount);
}

// **********************�Զ����嵥******************
function pre_add(mycustom, countNum) {
	mycount = document.getElementById(countNum).value * 1 + 1;
	var mycustom = document.getElementById(mycustom);
	i = mycustom.rows.length;
	oTR = mycustom.insertRow([i]);
	oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = i;
	oTL1 = oTR.insertCell([1]);
	oTL1.innerHTML = "<input class='txtshort' type='text' name='sales[customizelist][" + mycount
			+ "][productLine]' id='cproductLine" + mycount
			+ "'/>";
	oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML = "<input class='txtshort' type='text' name='sales[customizelist][" + mycount
			+ "][productnumber]' id='PequID" + mycount
			+ "'/>";
	oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML = "<input class='txtshort' type='text' name='sales[customizelist][" + mycount
			+ "][name]' id='PequName" + mycount
			+ "'/>";
	oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML = "<input class='txtshort' type='text' name='sales[customizelist][" + mycount
			+ "][prodectmodel]' id='PreModel" + mycount
			+ "'/>";
	oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML = "<input class='txtshort' type='text' name='sales[customizelist][" + mycount
			+ "][amount]' id='PreAmount" + mycount
			+ "' onblur='FloatMul(\"PreAmount" + mycount + "\",\"PrePrice"
			+ mycount + "\",\"CountMoney" + mycount
			+ "\")'/>";
	oTL6 = oTR.insertCell([6]);
	oTL6.innerHTML = "<input class='txtshort' type='text' name='sales[customizelist][" + mycount
			+ "][price]' id='PrePrice" + mycount
			+ "' onblur='FloatMul(\"PreAmount" + mycount + "\",\"PrePrice"
			+ mycount + "\",\"CountMoney" + mycount
			+ "\")'/>";
	oTL7 = oTR.insertCell([7]);
	oTL7.innerHTML = "<input class='txtshort' type='text' name='sales[customizelist][" + mycount
			+ "][countMoney]' id='CountMoney" + mycount
			+ "'/>";
	oTL8 = oTR.insertCell([8]);
	oTL8.innerHTML = "<input class='txtshort' type='text' name='sales[customizelist][" + mycount
			+ "][projArraDT]' id='PreDeliveryDT" + mycount
			+ "' onfocus='WdatePicker()'>";
	oTL9 = oTR.insertCell([9]);
	oTL9.innerHTML = "<input class='txt' type='text' name='sales[customizelist][" + mycount
			+ "][remark]' id='PRemark" + mycount
			+ "'/>";
	oTL10 = oTR.insertCell([10]);
	oTL10.innerHTML = "<input type='checkbox' name='sales[customizelist][" + mycount
			+ "][isSell]' id='customizelistSell" + mycount
			+ "' checked='checked' />";
	oTL11 = oTR.insertCell([11]);
	oTL11.innerHTML = "<img src='images/closeDiv.gif' onclick='equdel(this,\""
			+ mycustom.id + "\")' title='ɾ����'>";
	document.getElementById(countNum).value = document.getElementById(countNum).value
			* 1 + 1;
	createFormatOnClick('PrePrice'+mycount,'PreAmount'+mycount,'PrePrice'+mycount,'CountMoney'+mycount);
	createFormatOnClick('CountMoney'+mycount,'PreAmount'+mycount,'PrePrice'+mycount,'CountMoney'+mycount);
}
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
	oTL1.innerHTML = "<input class='txt' type='hidden' name='sales[linkman][" + mycount
			+ "][linkmanId]' id='linkmanId" + mycount + "'/>"
			+ "<input class='txt' type='text' name='sales[linkman][" + mycount
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
	oTL2.innerHTML = "<input class='txt' type='text' name='sales[linkman][" + mycount
			+ "][telephone]' id='telephone" + mycount + "'/>";
	oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML = "<input class='txt' type='text' name='sales[linkman][" + mycount
			+ "][Email]' id='Email" + mycount + "'/>";
	oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML = "<input class='txtlong' type='text' name='sales[linkman][" + mycount
			+ "][remark]' id='remark" + mycount + "'/>";
	oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML = "<img src='images/closeDiv.gif' onclick='mydel(this,\""
			+ mypay.id + "\")' title='ɾ����'>";
	document.getElementById(countNum).value = document.getElementById(countNum).value
			* 1 + 1;
}


// ************************�տ�ƻ�*************************
function pay_add(mypay, countNum) {
	mycount = document.getElementById(countNum).value * 1 + 1;
	var mypay = document.getElementById(mypay);
	i = mypay.rows.length;
	oTR = mypay.insertRow([i]);
	oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = i;
	oTL1 = oTR.insertCell([1]);
	oTL1.innerHTML = "<input class='txtshort' type='text' name='sales[receiptplan][" + mycount
			+ "][money]' id='PayMoney" + mycount
			+ "'/>";
	oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML = "<input class='txtshort' type='text' name='sales[receiptplan][" + mycount
			+ "][payDT]' id='PayDT" + mycount
			+ "' onfocus='WdatePicker()'>";
	oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML = "<select class='txtshort' name='sales[receiptplan]["
			+ mycount
			+ "][pType]'><option value='���'>���</option><option value='�ֽ�'>�ֽ�</option><option value='���л�Ʊ'>���л�Ʊ</option><option value='��ҵ��Ʊ'>��ҵ��Ʊ</option></select>";
	oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML = "<input class='txtlong' type='text' name='sales[receiptplan][" + mycount
			+ "][collectionTerms]' id='collectionTerms" + mycount
			+ "'/>";
	oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML = "<img src='images/closeDiv.gif' onclick='equdel(this,\""
			+ mypay.id + "\")' title='ɾ����'>";
	document.getElementById(countNum).value = document.getElementById(countNum).value
			* 1 + 1;

	createFormatOnClick('PayMoney'+mycount);
}

// **********��ѵ�ƻ�***********************

function train_add(mytra, countNum) {
	mycount = document.getElementById(countNum).value * 1 + 1;
	var mytra = document.getElementById(mytra);
	i = mytra.rows.length;
	oTR = mytra.insertRow([i]);
	oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = i;
	oTL1 = oTR.insertCell([1]);
	oTL1.innerHTML = "<input class='txtshort' type='text' name='sales[trainingplan][" + mycount
			+ "][beginDT]' id='TraDT" + mycount
			+ "' onfocus='WdatePicker()'>";
	oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML = "<input class='txtshort' type='text' name='sales[trainingplan][" + mycount
			+ "][endDT]' id='TraEndDT" + mycount
			+ "' onfocus='WdatePicker()'>";
	oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML = "<input class='txtshort' type='text' name='sales[trainingplan][" + mycount
			+ "][traNum]'/>";
	oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML = "<textarea name='sales[trainingplan][" + mycount
			+ "][adress]' rows='3' cols='15' style='width: 100%'></textarea>";
	oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML = "<textarea name='sales[trainingplan][" + mycount
			+ "][content]' rows='3' style='width: 100%'></textarea>";
	oTL6 = oTR.insertCell([6]);
	oTL6.innerHTML = "<textarea name='sales[trainingplan][" + mycount
			+ "][trainerDemand]' rows='3' style='width: 100%'></textarea>";
	oTL7 = oTR.insertCell([7]);
	oTL7.innerHTML = "<img src='images/closeDiv.gif' onclick='equdel(this,\""
			+ mytra.id + "\")' title='ɾ����'>";
	document.getElementById(countNum).value = document.getElementById(countNum).value
			* 1 + 1;
}

/** **********************************������Ϣ**************************** */
function license_add(mylicense, countNum) {
	mycount = document.getElementById(countNum).value * 1 + 1;
	var mylicense = document.getElementById(mylicense);
	i = mylicense.rows.length;
	oTR = mylicense.insertRow([i]);
	oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = i;
	oTL1 = oTR.insertCell([1]);
	oTL1.innerHTML = "<select class='txtshort' name='sales[licenselist][" + mycount
			+ "][productLine]' id='lproductLine" + mycount + "'></select>";
	addDataToSelect(productLineArr, 'lproductLine' + mycount);
	oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML = "<select class='txtshort' name='sales[licenselist][" + mycount
			+ "][softdogType]' id='softdogType" + mycount
			+ "'><option value='HASP'>HASP</option></select>";
	oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML = "<input class='txtshort' type='text' name='sales[licenselist][" + mycount
			+ "][amount]' id='softdogAmount" + mycount
			+ "'/>";
	oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML = "<input class='txtmiddle' type='text' value='' id='licenseType" + mycount
			+ "' name='sales[licenselist][" + mycount
			+ "][licenseType]'/><input type='hidden' value='' id='licenseinput"
			+ mycount + "' name='sales[licenselist][" + mycount
			+ "][licenseTypeIds]'/>";

	oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML = "<input class='txtshort' type='hidden' name='sales[licenselist]["
			+ mycount
			+ "][nodeId]' id='licenseNodeId"
			+ mycount
			+ "'><textarea name='sales[licenselist]["
			+ mycount
			+ "][nodeName]' onclick='openDia("
			+ mycount
			+ ")' id='licenseNodeName"
			+ mycount
			+ "' title='ѡ�������Ϣ'></textarea>";
	oTL6 = oTR.insertCell([6]);
	oTL6.innerHTML = "<select class='txtshort' name='sales[licenselist]["
			+ mycount
			+ "][validity]' id='validity"
			+ mycount
			+ "'><option value='����'>����</option><option value='һ��'>һ��</option><option value='����'>����</option></select>";
	oTL7 = oTR.insertCell([7]);
	oTL7.innerHTML = "<input class='txt' type='text' name='sales[licenselist][" + mycount
			+ "][remark]' id='licenseRemark" + mycount
			+ "'/>";
	oTL8 = oTR.insertCell([8]);
	oTL8.innerHTML = "<input  type='checkbox' name='sales[licenselist][" + mycount
			+ "][isSell]' id='licenseisSell" + mycount
			+ "' checked='checked' />";
	oTL9 = oTR.insertCell([9]);
	oTL9.innerHTML = "<img src='images/closeDiv.gif' onclick='mydel(this,\""
			+ mylicense.id + "\")' title='ɾ����'>";

	document.getElementById(countNum).value = document.getElementById(countNum).value
			* 1 + 1;

$(function() {
	$("#licenseType" + mycount).yxcombogrid_licenseType({
		hiddenId : 'licenseinput' + mycount,
		gridOptions : {
			showcheckbox : true,
			event : {
				'after_row_check' : function(mycount){
						return function(e, checkbox, row, data){
					}
				}(mycount)
			}
		}
	});
});
}

// *****************��Ʒ�嵥������ʾ��1******************************
function soft_add(myequ, countNum) {
	deliveryDate = $('#deliveryDate').val();
	mycount = document.getElementById(countNum).value * 1 + 1;
	var myequ = document.getElementById(myequ);
	i = myequ.rows.length;
	j = i + 1;
	oTR = myequ.insertRow([i]);
	oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = j ;
    oTL1 = oTR.insertCell([1]);
	oTL1.innerHTML = "<select class='txtshort' name='sales[equipment]["
			+ mycount
			+ "][productLine]' id='productLine"
			+ mycount
			+ "'></select>";
	addDataToSelect(productLineArr, 'productLine' + mycount);
    oTL2 = oTR.insertCell([2]);
    oTL2.innerHTML = "<input class='txtshort' type='text' name='sales[equipment]["
			+ mycount
			+ "][productNumber]' id='EquId"
			+ mycount
			+ "' />";
    oTL3 = oTR.insertCell([3]);
    oTL3.innerHTML = "<input  type='text' class='txtmiddle' name='sales[equipment]["
			+ mycount
			+ "][productName]' id='EquName"
			+ mycount
			+ "'/>"
			+ "<input type='hidden' name='sales[equipment]["
			+ mycount
			+ "][productId]' id='ProductId"
			+ mycount
			+ "'>";
    oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML = "<input class='txtshort' type='text' name='sales[equipment]["
			+ mycount
			+ "][productModel]' id='EquModel"
			+ mycount
			+ "'/>";
	oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML = "<input class='txtshort' type='text' name='sales[equipment]["
			+ mycount
			+ "][amount]' id='EquAmount"
			+ mycount
			+ "' onblur='FloatMul(\"EquAmount"
			+ mycount
			+ "\",\"EquPrice"
			+ mycount
			+ "\",\"EquAllMoney"
			+ mycount
			+ "\",2)'/>";
	oTL6 = oTR.insertCell([6]);
	oTL6.innerHTML = "<input class='txtshort' type='text' name='sales[equipment]["
			+ mycount
			+ "][price]' id='EquPrice"
			+ mycount
			+ "' onblur='FloatMul(\"EquAmount"
			+ mycount
			+ "\",\"EquPrice"
			+ mycount
			+ "\",\"EquAllMoney"
			+ mycount
			+ "\",2)'/>";
	oTL7 = oTR.insertCell([7]);
	oTL7.innerHTML = "<input class='txtshort' type='text' name='sales[equipment]["
			+ mycount
			+ "][countMoney]' id='EquAllMoney"
			+ mycount
			+ "'/>";
	oTL8 = oTR.insertCell([8]);
	oTL8.innerHTML = "<input class='txtshort' type='text' name='sales[equipment]["
			+ mycount
			+ "][projArraDate]' id='EquDeliveryDT"
			+ mycount
			+ "' value='"+ deliveryDate +"' onfocus='WdatePicker()'/>";
	oTL9 = oTR.insertCell([9]);
	oTL9.innerHTML = "<select class='txtshort' name='sales[equipment]["
			+ mycount
			+ "][warrantyPeriod]' id='warrantyPeriod"
			+ mycount
			+ "'>"
			+ "<option value='����'>����</option>"
			+ "<option value='һ��'>һ��</option>"
			+ "<option value='����'>����</option>"
			+ "<option value='����'>����</option>"
			+ "</select>";
	oTL10 = oTR.insertCell([10]);
	oTL10.innerHTML = "<input type='checkbox' name='sales[equipment]["
			+ mycount
			+ "][isSell]' checked='checked'>";
	oTL11 = oTR.insertCell([11]);
    oTL11.innerHTML = "<img src='images/closeDiv.gif' onclick='equdel(this,\""
			+ myequ.id + "\")' title='ɾ����'>";

	document.getElementById(countNum).value = document.getElementById(countNum).value
			* 1 + 1;
//	addDataToSelect(productLineArr, 'productLine' + mycount);

	createFormatOnClick('EquPrice'+mycount,'EquAmount'+mycount,'EquPrice'+mycount,'EquAllMoney'+mycount);
	createFormatOnClick('EquAllMoney'+mycount,'EquAmount'+mycount,'EquPrice'+mycount,'EquAllMoney'+mycount);

	var countNum = $('#EquNum').val();
		// ��ѡ��Ʒ
	$("#EquName" + mycount).yxcombogrid_product({
		hiddenId : 'ProductId' + mycount,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(mycount){
						return function(e, row, data) {
						$("#EquId"+mycount).val(data.sequence);
						$("#EquModel"+mycount).val(data.pattern);
					};
			  	}(mycount)
			}
		}
	});
}

// *****************ɾ�����������******************************
function mydel(obj, mytable) {
	if (confirm('ȷ��Ҫɾ�����У�')) {
		var rowNo = obj.parentNode.parentNode.rowIndex;
		var mytable = document.getElementById(mytable);
		mytable.deleteRow(rowNo);
		var myrows = mytable.rows.length;
		for (i = 1; i < myrows; i++) {
			mytable.rows[i].childNodes[0].innerHTML = i;
		}
	}
}

// *****************ɾ�����������******************************
function equdel(obj, mytable) {
	if (confirm('ȷ��Ҫɾ�����У�')) {
		var tableRows = $('#'+mytable + " tr ");
		var thisNo = obj.parentNode.parentNode.rowIndex;
		var mark = thisNo - 2;
		$.each(tableRows,function(i,n){
			if(mark == i){
				$(this).remove();
			}
		});
		$.each($('#'+mytable + ">tr"),function(i,n){
			$(this).children(":first").html(i + 1);
		});

//		var rowNo = obj.parentNode.parentNode.rowIndex;
//		var mytable = document.getElementById(mytable);
//		mytable.deleteRow(rowNo);
//		var myrows = mytable.rows.length;
//		for (i = 0; i < myrows; i++) {
//			mytable.rows[i].childNodes[0].innerHTML = i + 1 ;
//		}
	}
}

// *******************���ؼƻ�********************************
function dis(name) {
	var temp = document.getElementById(name);
	if (temp.style.display == '')
		temp.style.display = "none";
	else if (temp.style.display == "none")
		temp.style.display = '';
}

//************************��Ʊ���͵������ֵ��滻******************************
$(function(){
	invoeceType = getData('FPLX');
	addDataToSelect(invoeceType,'invoeceType');
});

//*****************************��Ʒ��***********************************
$(function(){
	productLine = getData('CPX');
	addDataToSelect(productLine,'productLine');
});

