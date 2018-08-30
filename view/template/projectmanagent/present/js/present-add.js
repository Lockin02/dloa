function toApp(type) {
	if(type == 'edit'){
		document.getElementById('form1').action = "index1.php?model=projectmanagent_present_present&action=edit&act=app";
	}else{
		document.getElementById('form1').action = "index1.php?model=projectmanagent_present_present&action=add&act=app";
	}
}

function toSave(type){
	if(type == 'edit'){
		document.getElementById('form1').action = "index1.php?model=projectmanagent_present_present&action=edit";
	}else{
		document.getElementById('form1').action = "index1.php?model=projectmanagent_present_present&action=add";
	}
}

// ������
function hideList(listId) {
	var temp = document.getElementById(listId);
	var tempH = document.getElementById(listId + "H");
	if (temp.style.display == '') {
		temp.style.display = "none";
		if (tempH != null) {
			tempH.style.display = "";
		}
	} else if (temp.style.display == "none") {
		temp.style.display = '';
		if (tempH != null) {
			tempH.style.display = 'none';
		}
	}
}

// Դ������
$(function() {
	var SingleTypeT = $("#SingleTypeT").val();
	switch (SingleTypeT) {
		case "" :
			document.getElementById("SingleType").options.length = 0;
			document.getElementById("SingleType").options
					.add(new Option("��Դ��", "��Դ��"));
			document.getElementById("SingleType").options
					.add(new Option("�̻�", "�̻�"));
			document.getElementById("SingleType").options
					.add(new Option("��ͬ", "��ͬ"));
			singleSelect();
			break;
		case "chance" :
			document.getElementById("SingleType").options.length = 0;
			document.getElementById("SingleType").options
					.add(new Option("�̻�", "�̻�"));
			singleSelect();
			break;
		case "contract" :
			document.getElementById("SingleType").options.length = 0;
			document.getElementById("SingleType").options
					.add(new Option("��ͬ", "��ͬ"));
			singleSelect();
			break;
	}
});
// ��ȡԴ������ѡ��
function singleSelect() {
	var SingleType = $("#SingleType").val();
	switch (SingleType) {
		case "��Դ��" :
            $("#contractNum").yxcombogrid_allcontract('remove');
            $("#chanceCode").yxcombogrid_chance('remove');
			$("#single").html("<input type='text' class='readOnlyText' readonly='readonly'>");
			$("#SingleTypeT").val("");
			break;
		case "�̻�" :
			var chanceCode = $("#chanceCodeT").val();
			var chanceId = $("#chanceIdT").val();
		    $("#contractNum").yxcombogrid_allcontract('remove');
			$("#single").html("<input type='text' class='txt' name='present[orderCode]' id='chanceCode' >"
							+ "<input type='hidden' class='txt' name='present[chanceId]' id='chanceId'>"
							+ "<input type='hidden' class='txt' name='present[orderName]' id='chanceName'>");
			if (chanceCode != '') {
				$("#chanceCode").val(chanceCode);
			}
			if (chanceId != '') {
				$("#chanceId").val(chanceId);
			}
			$("#SingleTypeT").val("chance");
			$("#chanceCode").yxcombogrid_chance({
				nameCol : 'chanceCode',
				hiddenId : 'chanceId',
				isShowButton : false,
				gridOptions : {
					param : {'prinvipalId' : $("#createId").val(),'staClose' : '3','staSJ' : '4'},
					showcheckbox : false,
					event : {
						'row_dblclick' : function(e, row, data) {
							$("#chanceCode").val(data.chanceCode);
							$("#chanceName").val(data.chanceName);
							$("#customerName").val(data.customerName);
							$("#customerNameId").val(data.customerId);
							$("#areaPrincipal").val(data.areaPrincipal);// ��������
					        $("#areaPrincipalId").val(data.areaPrincipalId);// ��������Id
					        $("#areaName").val(data.areaName);// ��������
					        $("#areaCode").val(data.areaCode);// �����������
							setCustomAndAreaInfo(0);
//							getAreaMoneyByCustomer(data.customerId,data.customerName);
						}
					}
				}
			});
			break;
		case "��ͬ" :
			var contractCode = $("#contractCodeT").val();
			var contractName = $("#contractNameT").val();
			var contractId = $("#contractIdT").val();
		    $("#chanceCode").yxcombogrid_chance('remove');
			$("#single")
					.html("<input type='text' class='txt' name='present[orderCode]' id='orderCode'>"
							 + "<input type='hidden' class='txt' name='present[orderName]' id='orderName'>"
							+ "<input type='hidden' class='txt' name='present[orderId]' id='orderId'>");
			if (contractCode != '') {
				$("#orderCode").val(contractCode);
			}
			if (contractName != '') {
				$("#orderName").val(contractName);
			}
			if (contractId != '') {
				$("#orderId").val(contractId);
			}
			$("#SingleTypeT").val("order");
			$("#orderCode").yxcombogrid_allcontract({
				hiddenId : 'id',
				searchName : 'contractCode',
				isShowButton : false,
				gridOptions : {
					param : {'isTemp' : '0' ,'prinvipalOrCreateId' : $("#createId").val(),'states' : '2,4'},
					showcheckbox : false,
					event : {
						'row_dblclick' : function(e, row, data) {
							$("#orderCode").val(data.contractCode);
							$("#orderName").val(data.contractName);
							$("#orderId").val(data.id);
							$("#customerName").val(data.customerName);
							$("#customerNameId").val(data.customerId);
//							getAreaMoneyByCustomer(data.customerId,data.customerName);
							$("#contractType").val(data.contractType);
							$("#areaPrincipal").val(data.areaPrincipal);// ��������
					        $("#areaPrincipalId").val(data.areaPrincipalId);// ��������Id
					        $("#areaName").val(data.areaName);// ��������
					        $("#areaCode").val(data.areaCode);// �����������
							setCustomAndAreaInfo(0);
						}
					}
				}
			});
			break;
	}

}
$(function() {
//			// ѡ���ͬԴ��
//			$("#orderCode").yxcombogrid_allcontract({
//						hiddenId : 'id',
//						width : 980,
//						height : 300,
//						autoHiddenName : {
//							"objCode" : "rObjCode"
//						},
//						searchName : 'contractCode',
//						gridOptions : {
//							showcheckbox : false,
//							param : {
//								'states' : '2,4',
//								'prinvipalId' : $("#createId").val()
//							},
//							event : {
//								'row_dblclick' : function(e, row, data) {
//									$("#orderCode").val(data.contractCode);
//									$("#orderName").val(data.contractName);
//									$("#orderId").val(data.id);
//									$("#customerName").val(data.customerName);
//									$("#customerNameId").val(data.customerId);
//									$("#limits").val(data.contractType);
//									// $("#customerLinkman").yxcombogrid('grid').param={}
//									// $("#customerLinkman").yxcombogrid('grid').reload;
//								}
//							}
//						}
//					});
			// ѡ��ͻ�
			$("#customerName").yxcombogrid_customer({
						hiddenId : 'customerId',
						gridOptions : {
							showcheckbox : false,
							// param :{"contid":$('#contractId').val()},
							event : {
								'row_dblclick' : function(e, row, data) {
									$("#customerNameId").val(data.id);
									setCustomAndAreaInfo(1);
//									$("#areaPrincipal").val(data.AreaLeader);// ��������
//							        $("#areaPrincipalId").val(data.AreaLeaderId);// ��������Id
//							        $("#areaName").val(data.AreaName);// ��ͬ��������
//							        $("#areaCode").val(data.AreaId);// ��ͬ��������
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
		});

// ���ݱ��ڵĿͻ���������Ϣ������ص���������(�ͻ�����,ʡ��,����Լ�������˾) PMS2408 2017-01-20
function setCustomAndAreaInfo(isCustomerChange){
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
	clearCustomAndAreaInfo(isCustomerChange);
	var SingleTypeElementType = $("#SingleType")[0]['localName'];
	var SingleType = $("#SingleType").val();// Դ������(����ҳ��)
	var SingleTypeText = $("#SingleType").text();// Դ������(�༭ҳ)
 	var chanceCode = ($("#chanceCode").val() == undefined)? '' : $("#chanceCode").val();// �̻���(����ҳ��)
	var chanceCodeText = ($("#singleCode").text() == undefined)? '' : $("#singleCode").text();// �̻���(�༭ҳ)
	var orderCode = ($("#orderCode").val() == undefined)? '' : $("#orderCode").val();// ��ͬ��
	var createId = ($("#createId").val() == undefined)? '' : $("#createId").val();// ������ID
	var businessBelongArr = getCompanyInfo(createId,'','');
	if(chkResult != 'false'){
		var chkResultArr = eval('(' + chkResult + ")");
		if(chkResultArr.customerInfo.length != 0 && (isCustomerChange == 1 || isCustomerChange == 0)){
			$("#customTypeName").val(chkResultArr.customerInfo['TypeOneName']);
			$("#customTypeId").val(chkResultArr.customerInfo['TypeOne']);
			$("#province").val(chkResultArr.customerInfo['Prov']);
			$("#provinceId").val(chkResultArr.customerInfo['ProvId']);
		}

		if(chkResultArr.areaInfo.length != 0 && isCustomerChange != 1){
			if(chkResultArr.areaInfo['module'].length != 0){
				$("#moduleName").val(chkResultArr.areaInfo['module']['moduleName']);
				$("#module").val(chkResultArr.areaInfo['module']['module']);
			}

			if((SingleTypeElementType == 'select' && SingleType != '��Դ��') || (SingleTypeElementType == 'div' && SingleTypeText != '��')){
				businessBelongArr = false;
				$("#businessBelongNameOpt").hide();
				$("#businessBelongNameRead").show();
				console.log(SingleTypeElementType);console.log(SingleTypeText);console.log(chanceCodeText);
				if((SingleTypeElementType == 'select' && SingleType == '�̻�') && chanceCode != ''){
					businessBelongArr = getCompanyInfo('',chanceCode,'');
				}else if((SingleTypeElementType == 'div' && SingleTypeText == '�̻�') && chanceCodeText != ''){
					businessBelongArr = getCompanyInfo('',chanceCodeText,'');
				}else if(((SingleTypeElementType == 'select' && SingleType == '��ͬ') || (SingleTypeElementType == 'div' && SingleTypeText == '��ͬ')) && orderCode != ''){
					businessBelongArr = getCompanyInfo('','',orderCode);
				}else if((SingleTypeElementType == 'div' && SingleTypeText == '��ͬ') && chanceCodeText != ''){
					businessBelongArr = getCompanyInfo('','',chanceCodeText);
				}
				if(businessBelongArr){
					$("#businessBelongName").val(businessBelongArr.companyName);
					$("#businessBelong").val(businessBelongArr.company);
					$("#businessBelongNameRead").val(businessBelongArr.companyName);
				}else{
					$("#businessBelongName").val('');
					$("#businessBelong").val('');
					$("#businessBelongNameRead").val('');
				}
			}else if(chkResultArr.areaInfo['businessBelong'].length != 0){
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
			}else if(businessBelongArr){
				$("#businessBelongNameRead").val(businessBelongArr.companyName);
				$("#businessBelongName").val(businessBelongArr.companyName);
				$("#businessBelong").val(businessBelongArr.company);
			}
		}else if(businessBelongArr && isCustomerChange != 1){
			$("#businessBelongNameRead").val(businessBelongArr.companyName);
			$("#businessBelongName").val(businessBelongArr.companyName);
			$("#businessBelong").val(businessBelongArr.company);
		}

		// console.log(chkResultArr.areaInfo);
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

// �̻����ͬ���Ƶ�ʱ��,�Զ����ؿͻ�+ʡ��+���+��˾ PMS2408 2017-01-23
$(function(){
	var chanceId = $("#chanceId").val();
	var orderId = $("#orderId").val();
	var editNum = $("#businessBelongNameRead").attr('data-editNum');
	if((chanceId != undefined && chanceId != '') || (orderId != undefined && orderId != '')){
		setCustomAndAreaInfo(0);
	}else if(editNum == undefined){// ����ҳ��Ĭ�ϳ�ʼ������˾���������˴���
		var createId = ($("#createId").val() == undefined)? '' : $("#createId").val();// ������ID
		var businessBelongArr = getCompanyInfo(createId,'','');
		if(businessBelongArr){
			$("#businessBelongNameRead").val(businessBelongArr.companyName);
			$("#businessBelongName").val(businessBelongArr.companyName);
			$("#businessBelong").val(businessBelongArr.company);
		}
	}

	// ��ӿͻ���Ϣ��������Ϣ��������������¼� PMS2408
	$('.clear-trigger').click(function(){
		var clearObjName = $(this).prev("input").attr('id');
		switch(clearObjName){
			case 'customerName':// ����ͻ���Ϣ
				$("#customTypeName").val('');
				$("#customTypeId").val('');
				$("#province").val('');
				$("#provinceId").val('');
				break;
			case 'areaName':// ���������Ϣ
				$("#moduleName").val('');
				$("#module").val('');

				// ������˾�ָ�������������˾
				$("#businessBelongName").val('');
				$("#businessBelong").val('');
				$("#businessBelongNameRead").val('');
				$("#businessBelongNameOpt").html("");
				$("#businessBelongNameOpt").hide();
				$("#businessBelongNameRead").show();
				var createId = ($("#createId").val() == undefined)? '' : $("#createId").val();// ������ID
				var businessBelongArr = getCompanyInfo(createId,'','');
				if(businessBelongArr){
					$("#businessBelongNameRead").val(businessBelongArr.companyName);
					$("#businessBelongName").val(businessBelongArr.companyName);
					$("#businessBelong").val(businessBelongArr.company);
				}
				break;
		}
	})
});

// ��ʼ����Ӧ�Ŀͻ���������Ϣ
function clearCustomAndAreaInfo(isCustomerChange){
	if(isCustomerChange == 1){// ��տͻ���Ϣ��������
		$("#customTypeName").val('');
		$("#customTypeId").val('');
		$("#province").val('');
		$("#provinceId").val('');
	}else{// ���������Ϣ��������
		$("#moduleName").val('');
		$("#module").val('');

		$("#businessBelongName").val('');
		$("#businessBelong").val('');
		$("#businessBelongNameRead").val('');
		$("#businessBelongNameOpt").html("");
		$("#businessBelongNameOpt").hide();
		$("#businessBelongNameRead").show();
	}
}

// �༭ҳ�����ù�����˾��ʼѡ��
function setDefaultBusinessOpt(){
	var customerId = $("#customerNameId").val();
	var areaId = $("#areaCode").val();
	var editNum = $("#businessBelongNameRead").attr('data-editNum');
	if(editNum == '0'){
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
			var SingleTypeElementType = $("#SingleType")[0]['localName'];
			var SingleTypeText = $("#SingleType").text();// Դ������(�༭ҳ)
			var chkResultArr = eval('(' + chkResult + ")");
			if(chkResultArr.areaInfo['businessBelong'].length > 1 && (SingleTypeElementType == 'div' && SingleTypeText == '��')){
				var optStr = "<option value=''> ...��ѡ��... </option>";
				var businessArr = chkResultArr.areaInfo['businessBelong'];
				var dftBusinessBelong = $("#businessBelong").val();
				$.each(businessArr,function(){
					if(this.businessBelong == dftBusinessBelong){
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
		}
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
			+ "' class='txtmiddle' name='present[presentequ][" + mycount
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
			+ "'  name='present[presentequ][" + mycount + "][productId]'/>"
			+ "<input id='productName" + mycount
			+ "' type='text' class='txt' name='present[presentequ][" + mycount
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
			+ "' type='text' class='txtmiddle' name='present[presentequ]["
			+ mycount + "][productModel]' readonly>";

	var oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML = "<input class='txtshort' type='text' name='present[presentequ]["
			+ mycount + "][number]' id='number" + mycount + "'  />";
	var oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML = "<input class='txtshort' type='text' name='present[presentequ]["
			+ mycount + "][unitName]' id='unitName" + mycount + "' >"

	var oTL6 = oTR.insertCell([6]);
	oTL6.innerHTML = "<input class='txtshort' type='text' name='present[presentequ]["
			+ mycount + "][price]' id='price" + mycount + "' />";

	var oTL7 = oTR.insertCell([7]);
	oTL7.innerHTML = "<input class='txtshort' type='text' name='present[presentequ]["
			+ mycount + "][money]' id='money" + mycount + "' />";

	var oTL8 = oTR.insertCell([8]);
	oTL8.innerHTML = "<input type='text' class='txtshort' name='present[presentequ]["
			+ mycount
			+ "][warrantyPeriod]' id='warrantyPeriod"
			+ mycount
			+ "'>";

	var oTL9 = oTR.insertCell([9]);
	oTL9.innerHTML = "<input type='hidden' id='licenseId"
			+ mycount
			+ "' name='present[presentequ]["
			+ mycount
			+ "][License]'/>"
			+ "<input type='button' name='' class='txt_btn_a' value='����' onclick='License(\"licenseId"
			+ mycount + "\");'>";

	var oTL10 = oTR.insertCell([10]);
	oTL10.innerHTML = "<img src='images/closeDiv.gif' onclick='mydel(this,\""
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

/** ********************ɾ����̬��************************ */
function mydel(obj, mytable) {
	if (confirm('ȷ��Ҫɾ�����У�')) {
		var rowNo = obj.parentNode.parentNode.rowIndex * 1 - 1;
		var mytable = document.getElementById(mytable);
		mytable.deleteRow(rowNo);
		var myrows = mytable.rows.length;
		for (i = 1; i < myrows; i++) {
			// alert(mytable.rows[i].childNodes[0].innerHTML);
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

$(function() {
	/**
	 * ��֤��Ϣ
	 */
	validate({
		"customerName" :{
			required : true
		},
		"areaName" :{
		    required : true
		},
		"feeMan" :{
		    required : true
		}
	});

	setDefaultBusinessOpt();
});