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

    //����
    $("#manageDept").yxselect_dept({
        hiddenId : 'manageDeptId'
    });
})

/**
 * ��ȡ���Ͽ����Ϣ
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
 * ��ȡ��;����
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
 * ��ȡ���µ���
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
 * ��У��
 */
function checkForm() {
    //��֤������
    if($("#manageDeptName").val() == ""){
        alert('�����Ų���Ϊ��');
        return false;
    }
    //������Ϣ��д
    if($("#productId").val() == ""){
        alert('���ϲ���Ϊ��');
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
				alert("�����������ÿ�澯��!")
				checkResult = false;
			}
		}
	});
	return checkResult;
}

/**
 * ������С�����
 */
function calMinAmount() {
	// if($("#minNum").val()!=""&&$("#"))
	$("#minAmount").val($("#minNum").val() * $("#price").val());
}

