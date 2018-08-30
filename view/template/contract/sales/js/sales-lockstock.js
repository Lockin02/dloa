var stockId = "";// ȫ�ֱ����ֿ�id
$(function() {
	function getInventoryInfos(stockId, productIdArr) {
		$.ajax({
			type : 'POST',
			url : '?model=stock_inventoryinfo_inventoryinfo&action=getInventoryInfos',
			data : {
				stockId : stockId,
				productIds : productIdArr.toString()
			},
			success : function(data) {
				$("div[id^='actNum']").html(0);
				$("div[id^='exeNum']").html(0);
				if (data == 'false') {

				} else {
					var inventorys = eval("(" + data + ")");
					if (inventorys) {
						for (var i = 0; i < inventorys.length; i++) {
							var inventory = inventorys[i];
							var actNum = inventory.actNum;// ���
							var exeNum = inventory.exeNum;// ��ִ�п��
							$('#actNum' + i).html(actNum);
							$('#exeNum' + i).html(exeNum);
							$('#inventoryId' + i).val(inventory.id);// ���ÿ����Ϣid
						}
					}
				}

			}
		});
	}

	/**
	 * ��ȡ�豸��ĳ���ֿ��µ��Ѿ���������
	 */
	function getEqusStockLockNum(stockId, equIdArr) {
		$.ajax({
					type : 'POST',
					url : '?model=stock_lock_lock&action=getEqusStockLockNum',
					data : {
						stockId : stockId,
						equIds : equIdArr.toString()
					},
					success : function(data) {
						var lockNumArr = eval("(" + data + ")");
						$("div[id^='stockLockNum']").html(0);
						if (lockNumArr) {
							for (var i = 0; i < lockNumArr.length; i++) {
								var lockNum = lockNumArr[i].totalNum;
								$('#stockLockNum' + i).html(lockNum);
							}
						}

					}
				});
	}

	// ��ȡ��Ʒid
	var productIds = $("input[id^='productId']");
	var productIdArr = [];
	for (var i = 0; i < productIds.size(); i++) {
		productIdArr.push($(productIds[i]).val());
	}

	// ��ȡ�豸id
	var equIds = $("input[id^='equId']");
	var equIdArr = [];
	for (var i = 0; i < equIds.size(); i++) {
		equIdArr.push($(equIds[i]).val());
	}

	stockId = $('#stockId').val();
	getInventoryInfos(stockId, productIdArr);
	getEqusStockLockNum(stockId, equIdArr);
	$("#stockName").yxcombogrid_stock({
				hiddenId : 'stockId',
				gridOptions : {
					showcheckbox : false,
					event : {
						'row_dblclick' : function(e, row, data) {
							var stockId = data.id;
							$('#stockId').val(stockId);
							// ����ajax�����ȡ���²�Ʒ�Ŀ����Ϣ
							getInventoryInfos(stockId, productIdArr);
							getEqusStockLockNum(stockId, equIdArr);
						}
					}
				}
			});

});

var isCanSubmit = [];
var vaildMsg = [];

// �ύ������Ϣ
function lockSubmit() {

	for (var i = 0; i < isCanSubmit.length; i++) {
		if (isCanSubmit[i] == false) {
			alert(vaildMsg[i]);
			return;
		}
	}
	if (confirm("��ȷ������������")) {
		$("#lockForm").submit();
	}
}
// ������������Ƿ���ȷ
function checkLockNum(lockNumTxt, index) {
	var lockNum = parseInt($(lockNumTxt).val());
	var amount = parseInt($('#amount' + index).html());// ��ͬ�����������
	var exeNum = parseInt($('#exeNum' + index).html());
	var lockNumAll = parseInt($('#lockNum' + index).html());
	var hasLockNum = parseInt($('#stockLockNum' + index).html());// ����������(ע����ĳ���ֿ��)
	// ��������С��0���൱�ڽ����������������ܶ�������������

	if (lockNum < 0) {
		if (hasLockNum + lockNum < 0) {
			var msg = '�����������ܴ���������������';
			alert(msg);
			vaildMsg[index] = msg;
			isCanSubmit[index] = false;
		} else {
			isCanSubmit[index] = true;
		}
	} else {
		// �ж����������Ƿ���ں�ͬ������ȥ����������
		if (lockNum > (amount - lockNumAll)) {
			var msg = '�����������ܴ��ڿ����������������������ȥ��������������';
			alert(msg);
			vaildMsg[index] = msg;
			isCanSubmit[index] = false;
		} else {
			isCanSubmit[index] = true;
		}
		if (lockNum > exeNum) {
			var msg = '�����������ܴ��ڿ���ִ��������';
			alert(msg);
			vaildMsg[index] = msg;
			isCanSubmit[index] = false;
		} else {
			isCanSubmit[index] = true;
		}
	}
}
/**
 * ��ת��������¼ҳ��
 */
function toLockRecordsPage(equId, isWidthStock) {
	var url = "index1.php?model=stock_lock_lock&action=lockRecords&equId="
			+ equId;
	if (isWidthStock == true) {
		url += "&stockId=" + $('#stockId').val()
	}
//	alert( $('#id').val() )
	url += "&id=" + $('#id').val();
	this.location = url;
}
