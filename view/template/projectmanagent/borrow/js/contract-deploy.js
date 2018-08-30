//产品查看方法
function showGoods(thisVal, goodsName) {

	var url = "?model=goods_goods_properties&action=toChooseView"
			+ "&cacheId=" + thisVal
			+ "&goodsName=" + goodsName
		;

	window.open(url, "", "width=900,height=500,top=200,left=200");
}

//license查看方法
function showLicense(thisVal) {
	if (thisVal == 0 || thisVal == '' || thisVal == 'undefined') {
		alert('该物料无加密信息！');
		return false;
	}
	var url = "?model=yxlicense_license_tempKey&action=toViewRecord" + "&id=" + thisVal;

	var sheight = screen.height - 200;
	var swidth = screen.width - 70;
	var winoption = "dialogHeight:" + sheight + "px;dialogWidth:" + swidth + "px,scrollbars=yes, resizable=yes";

	window.open(url, '', winoption);
}

// 设置数据
function setData(data, componentId, rowNum) {
	if(componentId!="") {
		$("#" + componentId).productInfoGrid('setData', data, rowNum);
	}
}

// 刷新产品配置
function reloadCache(cacheId, componentId, rowNum) {
	if(componentId!="") {
		$("#" + componentId).productInfoGrid('reloadCache', cacheId, rowNum);
	}
}

//回调插入产品信息 － 单条
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

//回调插入产品信息 - 单边/带变更
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

//加载页面时渲染产品配置信息
function initCacheInfo() {
	//缓存表格对象
	var thisGrid = $("#productInfo");

	var colObj;
	try {
		colObj = thisGrid.productInfoGrid("getCmpByCol", "deploy");
	} catch (e) {
		colObj = thisGrid.yxeditgrid("getCmpByCol", "deploy");
	}
	colObj.each(function(i) {
		//判断是否有变更前值
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
    //屏蔽产品配置横向滚动条 add By Bingo 2015.8.29
//    $("div[id^='goodsDiv']").each(function(){
//    	$(this).attr("style","border:0px solid;");
//    })
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
		//显示验收条款，收款条款，不一致原因
		$("#isHide").show();
		$("#isHideText").show();
		$("#isHide2").show();
		$("#isHideText2").show();
		$("#diffReason").show();
	} else {
		//隐藏验收条款，收款条款，不一致原因
		$("#isHide").hide();
		$("#isHideText").hide();
		$("#isHide2").hide();
		$("#isHideText2").hide();
		$("#diffReason").hide();
	}
});
/**
 * 是否与纸质合同一致判断
 */
function changeIsSame() {
	if ($("#isSameNo").attr("checked")) {
		//隐藏验收条款，收款条款，不一致原因
		$("#isHide").show();
		$("#isHideText").show();
		$("#acceptTerms").addClass("validate[required]");
		$("#isHide2").show();
		$("#isHideText2").show();
		$("#payTerms").addClass("validate[required]");
		$("#diffReason").show();
		$("#diffReasonVal").addClass("validate[required]");
	} else {
		//显示验收条款，收款条款，不一致原因
		$("#isHide").hide();
		$("#acceptTerms").val('').removeClass("validate[required]");	//隐藏前清空输入框
		$("#isHideText").hide();
		$("#isHide2").hide();
		$("#payTerms").val('').removeClass("validate[required]");	//隐藏前清空输入框
		$("#isHideText2").hide();
		$("#diffReasonVal").val('').removeClass("validate[required]");	//隐藏前清空输入框
		$("#diffReason").hide();
	}
}

// 设置合同执行部门
function initExeDept(data, g) {
	if (data) {
		for (var i = 0; i < data.length; i++) {
			initExeDeptByRow(g, i);
		}
	}
}

// 设置产品执行区域及产品线- 行
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
	// 执行区域
	var productInfoObj = $("#productInfo");
	var productLineName = productInfoObj.productInfoGrid("getCmpByRowAndCol", i, 'exeDeptName').val();
	exeDeptIdArr = getData('GCSCX');
    addDataToSelect(exeDeptIdArr, 'productInfo_cmp_exeDeptId' + i);
	productInfoObj.productInfoGrid("getCmpByRowAndCol", i, 'exeDeptId')
		.find("option:[text='"+ productLineName + "']").attr("selected",true);

	// 产品线
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