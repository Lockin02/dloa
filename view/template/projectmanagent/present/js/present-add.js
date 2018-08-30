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

// 表单收缩
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

// 源单类型
$(function() {
	var SingleTypeT = $("#SingleTypeT").val();
	switch (SingleTypeT) {
		case "" :
			document.getElementById("SingleType").options.length = 0;
			document.getElementById("SingleType").options
					.add(new Option("无源单", "无源单"));
			document.getElementById("SingleType").options
					.add(new Option("商机", "商机"));
			document.getElementById("SingleType").options
					.add(new Option("合同", "合同"));
			singleSelect();
			break;
		case "chance" :
			document.getElementById("SingleType").options.length = 0;
			document.getElementById("SingleType").options
					.add(new Option("商机", "商机"));
			singleSelect();
			break;
		case "contract" :
			document.getElementById("SingleType").options.length = 0;
			document.getElementById("SingleType").options
					.add(new Option("合同", "合同"));
			singleSelect();
			break;
	}
});
// 获取源单下拉选择
function singleSelect() {
	var SingleType = $("#SingleType").val();
	switch (SingleType) {
		case "无源单" :
            $("#contractNum").yxcombogrid_allcontract('remove');
            $("#chanceCode").yxcombogrid_chance('remove');
			$("#single").html("<input type='text' class='readOnlyText' readonly='readonly'>");
			$("#SingleTypeT").val("");
			break;
		case "商机" :
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
							$("#areaPrincipal").val(data.areaPrincipal);// 区域负责人
					        $("#areaPrincipalId").val(data.areaPrincipalId);// 区域负责人Id
					        $("#areaName").val(data.areaName);// 归属区域
					        $("#areaCode").val(data.areaCode);// 归属区域编码
							setCustomAndAreaInfo(0);
//							getAreaMoneyByCustomer(data.customerId,data.customerName);
						}
					}
				}
			});
			break;
		case "合同" :
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
							$("#areaPrincipal").val(data.areaPrincipal);// 区域负责人
					        $("#areaPrincipalId").val(data.areaPrincipalId);// 区域负责人Id
					        $("#areaName").val(data.areaName);// 归属区域
					        $("#areaCode").val(data.areaCode);// 归属区域编码
							setCustomAndAreaInfo(0);
						}
					}
				}
			});
			break;
	}

}
$(function() {
//			// 选择合同源单
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
			// 选择客户
			$("#customerName").yxcombogrid_customer({
						hiddenId : 'customerId',
						gridOptions : {
							showcheckbox : false,
							// param :{"contid":$('#contractId').val()},
							event : {
								'row_dblclick' : function(e, row, data) {
									$("#customerNameId").val(data.id);
									setCustomAndAreaInfo(1);
//									$("#areaPrincipal").val(data.AreaLeader);// 区域负责人
//							        $("#areaPrincipalId").val(data.AreaLeaderId);// 区域负责人Id
//							        $("#areaName").val(data.AreaName);// 合同所属区域
//							        $("#areaCode").val(data.AreaId);// 合同所属区域
								}
							}
						}
					});
			// 归属区域
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
			// 渲染费用承担人
			$("#feeMan").yxselect_user({
				hiddenId: 'feeManId',
				event: {
					clearReturn: function() {
					}
				}
			});
		});

// 根据表单内的客户与区域信息带出相关的其他数据(客户类型,省份,板块以及归属公司) PMS2408 2017-01-20
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
	// 先清空数据
	clearCustomAndAreaInfo(isCustomerChange);
	var SingleTypeElementType = $("#SingleType")[0]['localName'];
	var SingleType = $("#SingleType").val();// 源单类型(新增页面)
	var SingleTypeText = $("#SingleType").text();// 源单类型(编辑页)
 	var chanceCode = ($("#chanceCode").val() == undefined)? '' : $("#chanceCode").val();// 商机号(新增页面)
	var chanceCodeText = ($("#singleCode").text() == undefined)? '' : $("#singleCode").text();// 商机号(编辑页)
	var orderCode = ($("#orderCode").val() == undefined)? '' : $("#orderCode").val();// 合同号
	var createId = ($("#createId").val() == undefined)? '' : $("#createId").val();// 申请人ID
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

			if((SingleTypeElementType == 'select' && SingleType != '无源单') || (SingleTypeElementType == 'div' && SingleTypeText != '无')){
				businessBelongArr = false;
				$("#businessBelongNameOpt").hide();
				$("#businessBelongNameRead").show();
				console.log(SingleTypeElementType);console.log(SingleTypeText);console.log(chanceCodeText);
				if((SingleTypeElementType == 'select' && SingleType == '商机') && chanceCode != ''){
					businessBelongArr = getCompanyInfo('',chanceCode,'');
				}else if((SingleTypeElementType == 'div' && SingleTypeText == '商机') && chanceCodeText != ''){
					businessBelongArr = getCompanyInfo('',chanceCodeText,'');
				}else if(((SingleTypeElementType == 'select' && SingleType == '合同') || (SingleTypeElementType == 'div' && SingleTypeText == '合同')) && orderCode != ''){
					businessBelongArr = getCompanyInfo('','',orderCode);
				}else if((SingleTypeElementType == 'div' && SingleTypeText == '合同') && chanceCodeText != ''){
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
				if(chkResultArr.areaInfo['businessBelong'].length == 1 && businessBelongArr){// 只有一个公司
					// $("#businessBelongNameRead").val(chkResultArr.areaInfo['businessBelong'][0]['businessBelongName']);
					// $("#businessBelongName").val(chkResultArr.areaInfo['businessBelong'][0]['businessBelongName']);
					// $("#businessBelong").val(chkResultArr.areaInfo['businessBelong'][0]['businessBelong']);
					$("#businessBelongNameRead").val(businessBelongArr.companyName);
					$("#businessBelongName").val(businessBelongArr.companyName);
					$("#businessBelong").val(businessBelongArr.company);
				}else{// 有多个公司
					$("#businessBelongName").val('');
					$("#businessBelong").val('');
					$("#businessBelongNameRead").val('');

					var optStr = "<option value=''> ...请选择... </option>";
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

// 根据对应条件获取相应的公司信息
function getCompanyInfo(createId,chanceCode,orderCode){
	var URL= '';var param = {};var type = '';
	// 根据传入的值定义对应的Url以及查询条件
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

	// 获取对应的数据
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
			case 1:// 根据申请人带出
				returnArr = {
					company : resultArr.Company,
					companyName : resultArr.companyName
				};
				break;
			case 2:// 根据商机原单带出
				if(resultArr.collection.length > 0){
					returnArr = {
						company : resultArr.collection[0]['businessBelong'],
						companyName : resultArr.collection[0]['businessBelongName']
					};
				}
				break;
			case 3:// 根据合同原单带出
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

// 商机或合同下推的时候,自动加载客户+省份+板块+公司 PMS2408 2017-01-23
$(function(){
	var chanceId = $("#chanceId").val();
	var orderId = $("#orderId").val();
	var editNum = $("#businessBelongNameRead").attr('data-editNum');
	if((chanceId != undefined && chanceId != '') || (orderId != undefined && orderId != '')){
		setCustomAndAreaInfo(0);
	}else if(editNum == undefined){// 新增页面默认初始归属公司根据申请人带出
		var createId = ($("#createId").val() == undefined)? '' : $("#createId").val();// 申请人ID
		var businessBelongArr = getCompanyInfo(createId,'','');
		if(businessBelongArr){
			$("#businessBelongNameRead").val(businessBelongArr.companyName);
			$("#businessBelongName").val(businessBelongArr.companyName);
			$("#businessBelong").val(businessBelongArr.company);
		}
	}

	// 添加客户信息与区域信息的连带清除触发事件 PMS2408
	$('.clear-trigger').click(function(){
		var clearObjName = $(this).prev("input").attr('id');
		switch(clearObjName){
			case 'customerName':// 清除客户信息
				$("#customTypeName").val('');
				$("#customTypeId").val('');
				$("#province").val('');
				$("#provinceId").val('');
				break;
			case 'areaName':// 清除区域信息
				$("#moduleName").val('');
				$("#module").val('');

				// 所属公司恢复申请人所属公司
				$("#businessBelongName").val('');
				$("#businessBelong").val('');
				$("#businessBelongNameRead").val('');
				$("#businessBelongNameOpt").html("");
				$("#businessBelongNameOpt").hide();
				$("#businessBelongNameRead").show();
				var createId = ($("#createId").val() == undefined)? '' : $("#createId").val();// 申请人ID
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

// 初始化对应的客户与区域信息
function clearCustomAndAreaInfo(isCustomerChange){
	if(isCustomerChange == 1){// 清空客户信息带出数据
		$("#customTypeName").val('');
		$("#customTypeId").val('');
		$("#province").val('');
		$("#provinceId").val('');
	}else{// 清空区域信息带出数据
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

// 编辑页面设置归属公司初始选项
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
			var SingleTypeText = $("#SingleType").text();// 源单类型(编辑页)
			var chkResultArr = eval('(' + chkResult + ")");
			if(chkResultArr.areaInfo['businessBelong'].length > 1 && (SingleTypeElementType == 'div' && SingleTypeText == '无')){
				var optStr = "<option value=''> ...请选择... </option>";
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
				+ '&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=900');
	}
}

// 反写id
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
			+ "' class='txtmiddle' name='present[presentequ][" + mycount
			+ "][productNo]' >";
	// 单选产品
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
			+ "<input type='button' name='' class='txt_btn_a' value='配置' onclick='License(\"licenseId"
			+ mycount + "\");'>";

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

/** ********************删除动态表单************************ */
function mydel(obj, mytable) {
	if (confirm('确定要删除该行？')) {
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

/** *****************隐藏计划******************************* */
function dis(name) {
	var temp = document.getElementById(name);
	if (temp.style.display == '')
		temp.style.display = "none";
	else if (temp.style.display == "none")
		temp.style.display = '';
}

$(function() {
	/**
	 * 验证信息
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