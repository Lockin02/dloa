function toApp() {
	document.getElementById('form1').action = "index1.php?model=projectmanagent_present_present&action=add&act=app";
}

$(function() {
//	// ѡ���ͬԴ��
//	$("#orderCode").yxcombogrid_allcontract({
//				hiddenId : 'id',
//				width : 980,
//				height : 300,
//				autoHiddenName : {
//					"objCode" : "rObjCode"
//				},
//				searchName : 'contractCode',
//				gridOptions : {
//					showcheckbox : false,
//					param : {
//						'states' : '2,4',
//						'prinvipalId' : $("#createId").val()
//					},
//					event : {
//						'row_dblclick' : function(e, row, data) {
//							$("#orderCode").val(data.contractCode);
//							$("#orderName").val(data.contractName);
//							$("#orderId").val(data.id);
//							$("#customerName").val(data.customerName);
//							$("#customerNameId").val(data.customerId);
//							$("#limits").val(data.contractType);
//							// $("#customerLinkman").yxcombogrid('grid').param={}
//							// $("#customerLinkman").yxcombogrid('grid').reload;
//						}
//					}
//				}
//			});
	// ѡ��ͻ�
	$("#customerName").yxcombogrid_customer({
				hiddenId : 'customerId',
				gridOptions : {
					showcheckbox : false,
					// param :{"contid":$('#contractId').val()},
					event : {
						'row_dblclick' : function(e, row, data) {
							$("#customerNameId").val(data.id);
							setCustomAndAreaInfo();
//							$("#areaPrincipal").val(data.AreaLeader);// ��������
//					        $("#areaPrincipalId").val(data.AreaLeaderId);// ��������Id
//					        $("#areaName").val(data.AreaName);// ��ͬ��������
//					        $("#areaCode").val(data.AreaId);// ��ͬ��������
						}
					}
				}
			});
	// ��������
	$("#areaName").yxcombogrid_area({
				hiddenId : 'areaCode',
				gridOptions : {
					showcheckbox : false,
					event : {
						'row_dblclick' : function(e, row, data) {
							$("#areaPrincipal").val(data.areaPrincipal);
							$("#areaCode").val(data.id);
							$("#areaPrincipalId").val(data.areaPrincipalId);
							setCustomAndAreaInfo();
						}
					}
				}
			});
	// ��Ⱦ���óе���
	$("#feeMan").yxselect_user({
		hiddenId: 'feeManId',
		event: {
			clearReturn: function() {
			}
		}
	});

	setDefaultBusinessOpt();
});


// ���ݱ��ڵĿͻ���������Ϣ������ص���������(�ͻ�����,ʡ��,����Լ�������˾) PMS2408 2017-01-20
function setCustomAndAreaInfo(){
	var customerId = $("#customerNameId").val();
	var areaId = $("#areaCode").val();
	var chkResult = $.ajax({
		type: "POST",
		url: "?model=projectmanagent_present_present&action=getCustomAndAreaInfo",
		data: {
			customerId: customerId,
			areaId: areaId
		},
		async: false
	}).responseText;
	// ���������
	clearCustomAndAreaInfo();
	var SingleType = $("#SingleType").text();// Դ������
	if(chkResult != 'false'){
		var chkResultArr = eval('(' + chkResult + ")");
		if(chkResultArr.customerInfo.length != 0){
			$("#customTypeName").val(chkResultArr.customerInfo['TypeOneName']);
			$("#customTypeId").val(chkResultArr.customerInfo['TypeOne']);
			$("#province").val(chkResultArr.customerInfo['Prov']);
			$("#provinceId").val(chkResultArr.customerInfo['ProvId']);
		}

		if(chkResultArr.areaInfo.length != 0){
			if(chkResultArr.areaInfo['module'].length != 0){
				$("#moduleName").val(chkResultArr.areaInfo['module']['moduleName']);
				$("#module").val(chkResultArr.areaInfo['module']['module']);
			}

			var createId = ($("#salesNameId").val() == undefined)? '' : $("#salesNameId").val();// ������ID
			if(chkResultArr.areaInfo['businessBelong'].length != 0 && (SingleType != '��ͬ' && SingleType != '�̻�')){
				var businessBelongArr = getCompanyInfo(createId,'','');
				if(chkResultArr.areaInfo['businessBelong'].length == 1 && businessBelongArr){// ֻ��һ����˾
					// $("#businessBelongNameRead").val(chkResultArr.areaInfo['businessBelong'][0]['businessBelongName']);
					// $("#businessBelongName").val(chkResultArr.areaInfo['businessBelong'][0]['businessBelongName']);
					// $("#businessBelong").val(chkResultArr.areaInfo['businessBelong'][0]['businessBelong']);
					$("#businessBelongNameRead").val(businessBelongArr.companyName);
					$("#businessBelongName").val(businessBelongArr.companyName);
					$("#businessBelong").val(businessBelongArr.company);
				}else{// �ж����˾
					$("#businessBelongName").val('');
					$("#businessBelong").val('');
					$("#businessBelongNameRead").val('');

					var optStr = "<option value=''> ...��ѡ��... </option>";
					var businessArr = chkResultArr.areaInfo['businessBelong'];
					$.each(businessArr,function(){
						if(businessBelongArr && this.businessBelong == businessBelongArr.company){
							optStr += "<option value='"+this.businessBelong+"' selected> "+this.businessBelongName+" </option>";
							$("#businessBelongName").val(this.businessBelongName);
							$("#businessBelong").val(this.businessBelong);
							$("#businessBelongNameRead").val(this.businessBelongName);
						}else{
							optStr += "<option value='"+this.businessBelong+"'> "+this.businessBelongName+" </option>";
						}
					});
					$("#businessBelongNameOpt").html(optStr);
					$("#businessBelongNameOpt").change(function(){
						var businessBelongName = $('#businessBelongNameOpt option:selected').text();
						var businessBelong = $('#businessBelongNameOpt option:selected').val();
						$("#businessBelongName").val(businessBelongName);
						$("#businessBelong").val(businessBelong);
						$("#businessBelongNameRead").val(businessBelongName);
					});
					$("#businessBelongNameOpt").show();
					$("#businessBelongNameRead").hide();
				}
			}else if(SingleType != '��ͬ' && SingleType != '�̻�'){
				var businessBelongArr = getCompanyInfo(createId,'','');
				if(businessBelongArr){
					$("#businessBelongNameRead").val(businessBelongArr.companyName);
					$("#businessBelongName").val(businessBelongArr.companyName);
					$("#businessBelong").val(businessBelongArr.company);
				}
			}else if(SingleType == '��ͬ' || SingleType == '�̻�'){
				var codeNum = $("#singleCode").text();// �̻���or��ͬ��
				if(SingleType == '�̻�' && codeNum != ''){
					businessBelongArr = getCompanyInfo('',codeNum,'');
				}else if(SingleType == '��ͬ' && codeNum != ''){
					businessBelongArr = getCompanyInfo('','',codeNum);
				}

				if(businessBelongArr){
					$("#businessBelongNameRead").val(businessBelongArr.companyName);
					$("#businessBelongName").val(businessBelongArr.companyName);
					$("#businessBelong").val(businessBelongArr.company);
				}
			}
		}

		// console.log(chkResultArr.areaInfo);
	}
}

// ��ʼ����Ӧ�Ŀͻ���������Ϣ
function clearCustomAndAreaInfo(){
	$("#customTypeName").val('');
	$("#customTypeId").val('');
	$("#province").val('');
	$("#provinceId").val('');
	$("#moduleName").val('');
	$("#module").val('');

	$("#businessBelongName").val('');
	$("#businessBelong").val('');
	$("#businessBelongNameRead").val('');
	$("#businessBelongNameOpt").html("");
	$("#businessBelongNameOpt").hide();
	$("#businessBelongNameRead").show();
}

// �༭ҳ�����ù�����˾��ʼѡ��
function setDefaultBusinessOpt(){
	var customerId = $("#customerNameId").val();
	var areaId = $("#areaCode").val();
	var editNum = $("#businessBelongNameRead").attr('data-editNum');
	var SingleType = $("#SingleType").text();// Դ������
	if(editNum == '0' && (SingleType != '��ͬ' && SingleType != '�̻�')){
		$("#businessBelongNameRead").attr('data-editNum','1');
		var chkResult = $.ajax({
			type: "POST",
			url: "?model=projectmanagent_present_present&action=getCustomAndAreaInfo",
			data: {
				customerId: customerId,
				areaId: areaId
			},
			async: false
		}).responseText;
		if(chkResult != 'false'){
			var chkResultArr = eval('(' + chkResult + ")");
			if(chkResultArr.areaInfo['businessBelong'].length > 1){
				var optStr = "<option value=''> ...��ѡ��... </option>";
				var businessArr = chkResultArr.areaInfo['businessBelong'];
				var dftBusinessBelong = $("#businessBelong").val();
				$.each(businessArr,function(){
					if(this.businessBelong == dftBusinessBelong){
						optStr += "<option value='"+this.businessBelong+"' selected> "+this.businessBelongName+" </option>";
					}else{
						optStr += "<option value='"+this.businessBelong+"'> "+this.businessBelongName+" </option>";
					}
				});
				$("#businessBelongNameOpt").html(optStr);
				$("#businessBelongNameOpt").change(function(){
					var businessBelongName = $('#businessBelongNameOpt option:selected').text();
					var businessBelong = $('#businessBelongNameOpt option:selected').val();
					$("#businessBelongName").val(businessBelongName);
					$("#businessBelong").val(businessBelong);
					$("#businessBelongNameRead").val(businessBelongName);
				});
				$("#businessBelongNameOpt").show();
				$("#businessBelongNameRead").hide();
			}
		}
	}
}

// ���ݶ�Ӧ������ȡ��Ӧ�Ĺ�˾��Ϣ
function getCompanyInfo(createId,chanceCode,orderCode){
	var URL= '';var param = {};var type = '';
	// ���ݴ����ֵ�����Ӧ��Url�Լ���ѯ����
	if(createId != ''){
		type = 1;
		URL = "?model=deptuser_user_user&action=ajaxGetUserInfo";
		param = {
			userId : createId
		};
	}else if(chanceCode != ''){
		type = 2;
		URL = "?model=projectmanagent_chance_chance&action=pageJson";
		param = {
			chanceCode : chanceCode,
			prinvipalId : $("#createId").val(),
			staClose: 3,
			staSJ:4
		};
	}else if(orderCode != ''){
		type = 3;
		URL = "?model=contract_contract_contract&action=ajaxGetContract";
		param = {
			contractCode : orderCode
		};
	}

	// ��ȡ��Ӧ������
	var resultStr = $.ajax({
		type: "POST",
		url: URL,
		data: param,
		async: false
	}).responseText;
	var resultArr = (resultStr == 'null')? '' : eval('(' + resultStr + ")");
	if(resultArr){
		var returnArr = {};
		switch (type){
			case 1:// ���������˴���
				returnArr = {
					company : resultArr.Company,
					companyName : resultArr.companyName
				};
				break;
			case 2:// �����̻�ԭ������
				if(resultArr.collection.length > 0){
					returnArr = {
						company : resultArr.collection[0]['businessBelong'],
						companyName : resultArr.collection[0]['businessBelongName']
					};
				}
				break;
			case 3:// ���ݺ�ͬԭ������
				returnArr = {
					company : resultArr.businessBelong,
					companyName : resultArr.businessBelongName
				};
				break;
		}
		return returnArr;
	}else{
		return false;
	}
}

/**
 * license
 *
 * @type
 */

function License(licenseId) {
	var licenseVal = $("#" + licenseId).val();
	if (licenseVal == "") {
		// ���Ϊ��,�򲻴�ֵ
		showThickboxWin('?model=yxlicense_license_tempKey&action=toSelect'
				+ '&focusId='
				+ licenseId
				+ '&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=900');
	} else {
		// ��Ϊ����ֵ
		showThickboxWin('?model=yxlicense_license_tempKey&action=toSelect'
				+ '&focusId='
				+ licenseId
				+ '&licenseId='
				+ licenseVal
				+ '&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=900');
	}
}

// ��дid
function setLicenseId(licenseId, thisVal) {
	$('#' + licenseId).val(thisVal);
}

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

$(function() {
	temp = $('#productNumber').val();
	for (var i = 1; i <= temp; i++) {
		$("#productName" + i).yxcombogrid_productName({
					hiddenId : 'productId' + i,
					gridOptions : {
						showcheckbox : false,
						event : {
							'row_dblclick' : function(i) {
								return function(e, row, data) {
									$("#productNo" + i).val(data.productCode);
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
	oTL1.innerHTML = "<input type='text' id='productNo" + mycount
			+ "' class='txtmiddle' name='present[presentequTemp][" + mycount
			+ "][productNo]' >";
	// ��ѡ��Ʒ
	$("#productNo" + mycount).yxcombogrid_product({
				hiddenId : 'productId' + mycount,
				gridOptions : {
					showcheckbox : false,
					event : {
						'row_dblclick' : function(mycount) {
							return function(e, row, data) {
								$("#productName" + mycount)
										.val(data.productName);
								$("#productModel" + mycount).val(data.pattern);
								$("#unitName" + mycount).val(data.unitName);
								$("#warrantyPeriod" + mycount)
										.val(data.warranty);
							};
						}(mycount)
					}
				}
			});
	var oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML = "<input type='hidden' id='productId" + mycount
			+ "'  name='present[presentequTemp][" + mycount + "][productId]'/>"
			+ "<input id='productName" + mycount
			+ "' type='text' class='txt' name='present[presentequTemp][" + mycount
			+ "][productName]' />";
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
								$("#warrantyPeriod" + mycount)
										.val(data.warranty);
							};
						}(mycount)
					}
				}
			});
	var oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML = "<input id='productModel" + mycount
			+ "' type='text' class='txtmiddle' name='present[presentequTemp]["
			+ mycount + "][productModel]' readonly>";

	var oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML = "<input class='txtshort' type='text' name='present[presentequTemp]["
			+ mycount + "][number]' id='number" + mycount + "'  />";
	var oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML = "<input class='txtshort' type='text' name='present[presentequTemp]["
			+ mycount + "][unitName]' id='unitName" + mycount + "' >"

	var oTL6 = oTR.insertCell([6]);
	oTL6.innerHTML = "<input class='txtshort' type='text' name='present[presentequTemp]["
			+ mycount + "][price]' id='price" + mycount + "' />";

	var oTL7 = oTR.insertCell([7]);
	oTL7.innerHTML = "<input class='txtshort' type='text' name='present[presentequTemp]["
			+ mycount + "][money]' id='money" + mycount + "' />";

	var oTL8 = oTR.insertCell([8]);
	oTL8.innerHTML = "<input type='text' class='txtshort' name='present[presentequTemp]["
			+ mycount
			+ "][warrantyPeriod]' id='warrantyPeriod"
			+ mycount
			+ "'>";

	var oTL9 = oTR.insertCell([9]);
	oTL9.innerHTML = "<input type='hidden' id='licenseId"
			+ mycount
			+ "' name='present[presentequTemp]["
			+ mycount
			+ "][License]'/>"
			+ "<input type='button' name='' class='txt_btn_a' value='����' onclick='License(\"licenseId"
			+ mycount + "\");'>";

	var oTL10 = oTR.insertCell([10]);
	oTL10.innerHTML = "<img src='images/closeDiv.gif' onclick='mydelC(this,\""
			+ packinglist.id + "\")' title='ɾ����'>";

	document.getElementById(countNumP).value = document
			.getElementById(countNumP).value
			* 1 + 1;
	// ǧ��λ������
	createFormatOnClick('number' + mycount, 'number' + mycount, 'price'
					+ mycount, 'money' + mycount);
	createFormatOnClick('price' + mycount, 'number' + mycount, 'price'
					+ mycount, 'money' + mycount);
	createFormatOnClick('money' + mycount, 'number' + mycount, 'price'
					+ mycount, 'money' + mycount);

}
/**********************ɾ����̬��*************************/
function mydel(obj, mytable, array) {
	if (confirm('ȷ��Ҫɾ�����У�')) {
		var rowNo = obj.parentNode.parentNode.rowIndex * 1 - 1;
		var $row=$(obj.parentNode.parentNode);
		$row.append("<input type='hiden' name='present["+ array +"]["+ rowNo +"][isDel]' value='1'>");
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
function mydelC(obj, mytable) {
	if (confirm('ȷ��Ҫɾ�����У�')) {
		var rowNo = obj.parentNode.parentNode.rowIndex * 1 - 1;
		var mytable = document.getElementById(mytable);
		mytable.deleteRow(rowNo -1);
		var myrows = mytable.rows.length;
		for (i = 0; i < myrows; i++) {
			mytable.rows[i].childNodes[0].innerHTML = i+1;
		}
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

/*******************���ؼƻ�********************************/
function dis(name) {
	var temp = document.getElementById(name);
	if (temp.style.display == '')
		temp.style.display = "none";
	else if (temp.style.display == "none")
		temp.style.display = '';
}
