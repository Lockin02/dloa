var stockId = "";// 全局变量仓库id
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
							var actNum = inventory.actNum;// 库存
							var exeNum = inventory.exeNum;// 可执行库存
							$('#actNum' + i).html(actNum);
							$('#exeNum' + i).html(exeNum);
							$('#inventoryId' + i).val(inventory.id);// 设置库存信息id
						}
					}
				}

			}
		});
	}

	/**
	 * 获取设备在某个仓库下的已经锁定数量
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

	// 获取产品id
	var productIds = $("input[id^='productId']");
	var productIdArr = [];
	for (var i = 0; i < productIds.size(); i++) {
		productIdArr.push($(productIds[i]).val());
	}

	// 获取设备id
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
							// 发送ajax请求获取更新产品的库存信息
							getInventoryInfos(stockId, productIdArr);
							getEqusStockLockNum(stockId, equIdArr);
						}
					}
				}
			});

});

var isCanSubmit = [];
var vaildMsg = [];

// 提交锁定信息
function lockSubmit() {

	for (var i = 0; i < isCanSubmit.length; i++) {
		if (isCanSubmit[i] == false) {
			alert(vaildMsg[i]);
			return;
		}
	}
	if (confirm("请确认锁定数量？")) {
		$("#lockForm").submit();
	}
}
// 检查锁定数量是否正确
function checkLockNum(lockNumTxt, index) {
	var lockNum = parseInt($(lockNumTxt).val());
	var amount = parseInt($('#amount' + index).html());// 合同数量（变更后）
	var exeNum = parseInt($('#exeNum' + index).html());
	var lockNumAll = parseInt($('#lockNum' + index).html());
	var hasLockNum = parseInt($('#stockLockNum' + index).html());// 已锁定数量(注意是某个仓库的)
	// 锁定数量小于0，相当于解锁，解锁数量不能多于已锁定数量

	if (lockNum < 0) {
		if (hasLockNum + lockNum < 0) {
			var msg = '解锁数量不能大于已锁定数量！';
			alert(msg);
			vaildMsg[index] = msg;
			isCanSubmit[index] = false;
		} else {
			isCanSubmit[index] = true;
		}
	} else {
		// 判断锁定数据是否大于合同数量减去已锁定数量
		if (lockNum > (amount - lockNumAll)) {
			var msg = '锁定数量不能大于可锁定数量（变更后数量减去已锁定总数）！';
			alert(msg);
			vaildMsg[index] = msg;
			isCanSubmit[index] = false;
		} else {
			isCanSubmit[index] = true;
		}
		if (lockNum > exeNum) {
			var msg = '锁定数量不能大于库存可执行数量！';
			alert(msg);
			vaildMsg[index] = msg;
			isCanSubmit[index] = false;
		} else {
			isCanSubmit[index] = true;
		}
	}
}
/**
 * 跳转到锁定记录页面
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
