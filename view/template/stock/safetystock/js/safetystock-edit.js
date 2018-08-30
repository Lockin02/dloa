$(document).ready(function() {
	$("#productCode").yxcombogrid_product({
		nameCol : 'productCode',
		hiddenId : 'productId',
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#productName").val(data.productName);
					$("#unitName").val(data.unitName);
					$("#pattern ").val(data.pattern);
					$("#purchUserCode").val(data.purchUserCode);
					$("#purchUserName").val(data.purchUserName);
					$("#prepareDay").val(data.purchPeriod);
					$("#moq").val(data.leastOrderNum);
					$("#actNum").val(getProActNum(data.id));
					$("#loadNum").val(getEqusOnway(data.id));
					$("#price").val(getLastPrice(data.id));
				}
			}
		}
	});
	$("#productName").yxcombogrid_product({
		nameCol : 'productName',
		hiddenId : 'productId',
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#productCode").val(data.productCode);
					$("#unitName").val(data.unitName);
					$("#pattern ").val(data.pattern);
					$("#purchUserCode").val(data.purchUserCode);
					$("#purchUserName").val(data.purchUserName);
					$("#prepareDay").val(data.purchPeriod);
					$("#moq").val(data.leastOrderNum);
					$("#actNum").val(getProActNum(data.id));
					$("#loadNum").val(getEqusOnway(data.id));
					$("#price").val(getLastPrice(data.id));
				}
			}
		}
	});

    //部门
    $("#manageDept").yxselect_dept({
        hiddenId : 'manageDeptId'
    });
})

/**
 * 获取物料库存信息
 * 
 * @param productId
 */
function getProActNum(productId) {
	var actNum = 0;
	$.ajax({
		type : "POST",
		async : false,
		url : "?model=stock_safetystock_safetystock&action=getProActNum",
		data : {
			productId : productId
		},
		success : function(result) {
			actNum = result;
		}
	});
	return actNum;
}

/**
 * 获取在途数量
 */
function getEqusOnway(productId) {
	var actNum = 0;
	$.ajax({
		type : "POST",
		async : false,
		url : "?model=stock_safetystock_safetystock&action=getEqusOnway",
		data : {
			productId : productId
		},
		success : function(result) {
			actNum = result;
		}
	});
	return actNum;
}

/**
 * 获取最新单价
 */
function getLastPrice(productId) {
	var lastPrice = 0;
	$.ajax({
		type : "POST",
		async : false,
		url : "?model=stock_safetystock_safetystock&action=getLastPrice",
		data : {
			productId : productId
		},
		success : function(result) {
			lastPrice = result;
		}
	});
	return lastPrice;
}

/**
 * 表单校验
 */
function checkForm() {
    //验证管理部门
    if($("#manageDeptName").val() == ""){
        alert('管理部门不能为空');
        return false;
    }
    //物料信息填写
    if($("#productId").val() == ""){
        alert('物料不能为空');
        return false;
    }
	var checkResult = true;
	$.ajax({
		type : "POST",
		async : false,
		url : "?model=stock_safetystock_safetystock&action=checkRepeat",
		data : {
			productId : $("#productId").val(),
			id : $("#id").val()
		},
		success : function(result) {
			if (result == 1) {
				alert("此物料已配置库存警告!")
				checkResult = false;
			}
		}
	});
	return checkResult;
}

/**
 * 计算最小库存金额
 */
function calMinAmount() {
	// if($("#minNum").val()!=""&&$("#"))
	$("#minAmount").val($("#minNum").val() * $("#price").val());
}

