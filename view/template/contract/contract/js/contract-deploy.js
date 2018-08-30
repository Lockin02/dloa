//��Ʒ�鿴����
function showGoods(thisVal, goodsName) {

	var url = "?model=goods_goods_properties&action=toChooseView"
			+ "&cacheId=" + thisVal
			+ "&goodsName=" + goodsName
		;

	window.open(url, "", "width=900,height=500,top=200,left=200");
}

//license�鿴����
function showLicense(thisVal) {
	if (thisVal == 0 || thisVal == '' || thisVal == 'undefined') {
		alert('�������޼�����Ϣ��');
		return false;
	}
	var url = "?model=yxlicense_license_tempKey&action=toViewRecord" + "&id=" + thisVal;

	var sheight = screen.height - 200;
	var swidth = screen.width - 70;
	var winoption = "dialogHeight:" + sheight + "px;dialogWidth:" + swidth + "px,scrollbars=yes, resizable=yes";

	window.open(url, '', winoption);
}

// ��������
function setData(data, componentId, rowNum) {
	if(componentId!="") {
		$("#" + componentId).productInfoGrid('setData', data, rowNum);
	}

	// PMS2313 ����²�Ʒʱ������ҳ���ʵʱ��ִͬ�������ֶ��������²�Ʒ��ִ������
	var exeDeptCode = '';
	if($('#defaultExeDeptId').val() != undefined){
		// ֻ���¸��е�ִ�����򣬲�Ӱ��֮ǰ��Ʒ��ִ������
		var exeDeptObj = $("#" + componentId).productInfoGrid("getCmpByRowAndCol",rowNum,"exeDeptId");
		exeDeptCode = ($('#defaultExeDeptId').val() == '')? $("#exeDeptCode").val() : $('#defaultExeDeptId').val();
		var productLineName = ($('#defaultExeDeptName').val() == '')? $("#exeDeptName").val() : $('#defaultExeDeptName').val();
		// �������в�Ʒ��ִ������
		if(exeDeptObj.length > 0){
			exeDeptObj.each(function(){
				$(this).find("option:[value='"+ exeDeptCode + "']").attr("selected",true);
			});
			$("#" + componentId).productInfoGrid("getCmpByRowAndCol", rowNum, 'exeDeptName').val(productLineName);
		}
	}
}

// ˢ�²�Ʒ����
function reloadCache(cacheId, componentId, rowNum) {
	if(componentId!="") {
		$("#" + componentId).productInfoGrid('reloadCache', cacheId, rowNum);
	}
}

//�ص������Ʒ��Ϣ �� ����
function getCacheInfo(cacheId, rowNum) {
	$.ajax({
		type: "POST",
		url: "?model=goods_goods_goodscache&action=getCacheConfig",
		data: {"id": cacheId},
		async: false,
		success: function(data) {
			if (data != "") {
				$("#productInfo table tr[rowNum=" + rowNum + "]").after(data);
			}
		}
	});
}

//�ص������Ʒ��Ϣ - ����/�����
function getCacheInfoChange(cacheId, beforeCacheId, rowNum) {
	$.ajax({
		type: "POST",
		url: "?model=goods_goods_goodscache&action=getCacheChange",
		data: {id: cacheId, beforeId: beforeCacheId},
		async: false,
		success: function(data) {
			if (data != "") {
				$("#productInfo table tr[rowNum=" + rowNum + "]").after(data);
			}
		}
	});
}

//����ҳ��ʱ��Ⱦ��Ʒ������Ϣ
function initCacheInfo() {
	//���������
	var thisGrid = $("#productInfo");

	var colObj;
	try {
		colObj = thisGrid.productInfoGrid("getCmpByCol", "deploy");
	} catch (e) {
		colObj = thisGrid.yxeditgrid("getCmpByCol", "deploy");
	}
	colObj.each(function(i) {
		//�ж��Ƿ��б��ǰֵ
		var beforeDeployObj = $("#productInfo_cmp_beforeDeploy" + i);
		if (beforeDeployObj.length == 1) {
			if (beforeDeployObj.val()) {
				getCacheInfoChange(this.value, beforeDeployObj.val(), i);
			} else {
				getCacheInfo(this.value, i);
			}
		} else {
			getCacheInfo(this.value, i);
		}
	});
    //���β�Ʒ���ú�������� add By Bingo 2015.8.29
    $("div[id^='goodsDiv']").each(function(){
    	$(this).attr("style","border:0px solid;");
    })
}

function getInventoryInfos(docType, productId) {
	var num = 0;
	$.ajax({
		type: 'POST',
		url: '?model=stock_inventoryinfo_inventoryinfo&action=getExeNumFromDefault',
		data: {
			docType: docType,
			id: productId
		},
		async: false,
		success: function(data) {
			num = data;
		}
	});
	return num;
}

$(function() {
	if ($("#isSame").val() == 0) {
		$("#isSameNo").attr('checked', true);
		//��ʾ��������տ������һ��ԭ��
		$("#isHide").show();
		$("#isHideText").show();
		$("#isHide2").show();
		$("#isHideText2").show();
		$("#diffReason").show();
	} else {
		//������������տ������һ��ԭ��
		$("#isHide").hide();
		$("#isHideText").hide();
		$("#isHide2").hide();
		$("#isHideText2").hide();
		$("#diffReason").hide();
	}
});
/**
 * �Ƿ���ֽ�ʺ�ͬһ���ж�
 */
function changeIsSame() {
	if ($("#isSameNo").attr("checked")) {
		//������������տ������һ��ԭ��
		$("#isHide").show();
		$("#isHideText").show();
		$("#acceptTerms").addClass("validate[required]");
		$("#isHide2").show();
		$("#isHideText2").show();
		$("#payTerms").addClass("validate[required]");
		$("#diffReason").show();
		$("#diffReasonVal").addClass("validate[required]");
	} else {
		//��ʾ��������տ������һ��ԭ��
		$("#isHide").hide();
		$("#acceptTerms").val('').removeClass("validate[required]");	//����ǰ��������
		$("#isHideText").hide();
		$("#isHide2").hide();
		$("#payTerms").val('').removeClass("validate[required]");	//����ǰ��������
		$("#isHideText2").hide();
		$("#diffReasonVal").val('').removeClass("validate[required]");	//����ǰ��������
		$("#diffReason").hide();
	}
}

/**
 * ֽ�ʺ�ͬ�ж�
 */
function changepaperContract(obj) {
	var sltedVal = $(obj).find("option:selected").val();
    if (sltedVal =='��') {
        $("#paperReason").show();
        $("#paperContractRemark").addClass("validate[required]");
    } else if(sltedVal =='��'){
        $("#paperReason").hide();
        $("#paperContractRemark").val('').removeClass("validate[required]");	//����ǰ��������
    }
}
/**
 * �����ļ��ж�
 */
function changeCheckFile(obj) {
	if (obj.value=='��') {
		$("#checkFileView1").show();
		$("#checkFileView2").show();
	} else{
		$("#checkFileView1").hide();
		$("#checkFileView2").hide();
	}
}

// ���ú�ִͬ�в���
function initExeDept(data, g) {
	if (data) {
		for (var i = 0; i < data.length; i++) {
			initExeDeptByRow(g, i);
		}
	}
}

// ���ò�Ʒִ�����򼰲�Ʒ��- ��
function initExeDeptByRow(g, i) {
//	var proExeDeptId = g.getCmpByRowAndCol(i, 'proExeDeptId').val();
//	if (proExeDeptId != "") {
//		var proExeDeptName = g.getCmpByRowAndCol(i, 'proExeDeptName').val();
//		var exeDeptName = g.getCmpByRowAndCol(i, 'exeDeptName').val();
//		var proExeDeptIdArr = proExeDeptId.split(',');
//		var proExeDeptIdNameArr = proExeDeptName.split(',');
//		var optionStr = "<option value=''></option>";
//
//		for (var j = 0; j < proExeDeptIdArr.length; j++) {
//			if (exeDeptName == proExeDeptIdNameArr[j] || proExeDeptIdArr.length == 1) {
//				optionStr += "<option value='" + proExeDeptIdArr[j] + "' selected='selected'>" +
//				proExeDeptIdNameArr[j] + "</option>";
//			} else {
//				optionStr += "<option value='" + proExeDeptIdArr[j] + "'>" +
//				proExeDeptIdNameArr[j] + "</option>";
//			}
//		}
//		g.getCmpByRowAndCol(i, 'exeDeptId').append(optionStr);
//	}
	// ִ������
	var productInfoObj = $("#productInfo");
	var productLineName = productInfoObj.productInfoGrid("getCmpByRowAndCol", i, 'exeDeptName').val();
	exeDeptIdArr = getData('GCSCX');
    addDataToSelect(exeDeptIdArr, 'productInfo_cmp_exeDeptId' + i);
	productInfoObj.productInfoGrid("getCmpByRowAndCol", i, 'exeDeptId')
		.find("option:[text='"+ productLineName + "']").attr("selected",true);

	// ��Ʒ��
//	var exeDeptCode = g.getCmpByRowAndCol(i, 'newExeDeptCode').val();
//	if (exeDeptCode != "") {
//		var exeDeptName = g.getCmpByRowAndCol(i, 'newExeDeptName').val();
//		var newProLineName = g.getCmpByRowAndCol(i, 'newProLineName').val();
//		var exeDeptCodeArr = exeDeptCode.split(',');
//		var exeDeptNameArr = exeDeptName.split(',');
//		var optionStr = "";
//
//		for (var j = 0; j < exeDeptCodeArr.length; j++) {
//			if (newProLineName == exeDeptNameArr[j] || exeDeptCodeArr.length == 1) {
//				optionStr += "<option value='" + exeDeptCodeArr[j] + "' selected='selected'>" +
//				exeDeptNameArr[j] + "</option>";
//			} else {
//				optionStr += "<option value='" + exeDeptCodeArr[j] + "'>" +
//				exeDeptNameArr[j] + "</option>";
//			}
//		}
//		g.getCmpByRowAndCol(i, 'newProLineCode').append(optionStr);
//	}
}

// ��鵱ǰ������Ƿ������ύ��������, ������Ϊ������汾����, ����������Ϣû��֤�ɹ�ȴ�ύ��
function browserChk(){
	var hasCustomerTypeClearBtn = $("#customerTypeWrap").children(".clear-trigger").length;
	var userAgent = window.navigator.userAgent;
	var DEFAULT_VERSION = 10.0;
	var ua = navigator.userAgent.toLowerCase();
	var isIE = ua.indexOf("msie")>-1;
	var safariVersion;
	if(isIE) {
		safariVersion = ua.match(/msie ([\d.]+)/)[1];
	}

	// ͨ��������Լ��Ƿ���ڿͻ����͵������ť���жϴ�������Ƿ��ʺ��ύ��
	// if((safariVersion != undefined && safariVersion <= DEFAULT_VERSION)){
	// 	alert("��"+hasCustomerTypeClearBtn+"��-> "+safariVersion);
	// }
	if((safariVersion != undefined && safariVersion <= DEFAULT_VERSION) || hasCustomerTypeClearBtn <= 0){
		alert("�ύʧ��,������汾����,��ʹ��IE10���ϵ����������360����ģʽ����!");
		return false;
	}else{
		return true;
	}
}