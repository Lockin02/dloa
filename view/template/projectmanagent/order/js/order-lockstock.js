var stockId = "";// 全局变量仓库id
$(function() {
	function getInventoryInfos(stockId, productId) {
		$.ajax({
			type : 'POST',
			url : '?model=stock_inventoryinfo_inventoryinfo&action=getInventoryInfos',
			data : {
				stockId : stockId,
				productIds : productId.toString()
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
							var proId = inventorys[i].productId;

							var actNum = inventory.actNum;// 库存
							var exeNum = inventory.exeNum;// 可执行库存
							$("[id^='actNum'][proId=" + proId + "]")
									.html(actNum);
							$("[id^='exeNum'][proId=" + proId + "]")
									.html(exeNum);
							$("[id^='inventoryId'][proId=" + proId + "]")
									.val(inventory.id);
							// $('#inventoryId' + i).val(inventory.id);//
							// 设置库存信息id
						}
					}
				}
				var rowNum = $('#rowNum').val();
				for (var i=0; i<rowNum; i++){
					if( $('#actNum'+i).html()*1 < $('#lockNumber'+i).val()*1 ){
						$('#lockNumber'+i).css({"color":"red"})
					}else{
						$('#lockNumber'+i).css({"color":"black"})
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
						equIds : equIdArr.toString(),
						docType : $('#objType').val()
					},
					success : function(data) {
						var lockNumArr = eval("(" + data + ")");
						$("div[id^='stockLockNum']").html(0);
						if (lockNumArr) {
							for (var i = 0; i < lockNumArr.length; i++) {
								var lockNum = lockNumArr[i].totalNum;
								var equId = lockNumArr[i].objEquId;
								$("[id^='stockLockNum'][equId=" + equId + "]")
										.html(lockNum);
								// $('#stockLockNum' + i).html(lockNum);
							}
						}

					}
				});
	}
	stockId = $('#stockId').val();

	// 获取产品id
	var productIds = $("input[id^='productId']");
	var productIdArr = [];
	for (var i = 0; i < productIds.size(); i++) {
		productIdArr.push($(productIds[i]).val());

	}
	getInventoryInfos(stockId, productIdArr);

	// 获取设备id
	var equIds = $("input[id^='equId']");
	var equIdArr = [];
	for (var i = 0; i < equIds.size(); i++) {
		equIdArr.push($(equIds[i]).val());
	}

	// getInventoryInfos(stockId, productIdArr);
	getEqusStockLockNum(stockId, equIdArr);
	$("#stockName").yxcombogrid_stockinfo({
				hiddenId : 'stockId',
				nameCol : 'stockName',
				//isFocusoutCheck : false,
				gridOptions : {
					showcheckbox : false,
					event : {
						'row_dblclick' : function(e, row, data) {
							var stockId = data.id;
							// $('#stockId').val(stockId);
							// $('#stockName').val(data.stockName);
							// 发送ajax请求获取更新产品的库存信息
							getInventoryInfos(stockId, productIdArr);
							getEqusStockLockNum(stockId, equIdArr);
						}
					}
				}
			});


});

var isCanSubmit = [];
var isCanSubmitA = [];
var vaildMsg = [];
// 提交锁定信息
function lockSubmit() {
	var flag = true;
	var rowNum = $('#rowNum').val();
	for (var i=0; i<rowNum; i++){
		if( $('#actNum'+i).html()*1 < $('#lockNumber'+i).val()*1 ){
			$('#lockNumber'+i).css({"color":"red"})
			flag = false;
		}
	}
	if(!flag){
		alert('部分设备库存不足，请确认锁定数量。')
		return false;
	}

	for (var i = 0; i < isCanSubmit.length; i++) {
		if (isCanSubmit[i] == false) {
			alert(vaildMsg[i]);
			return;
		}
		// if (isCanSubmitA[i] == false) {
		// alert(vaildMsg[i]);
		// return;
		// }
	}
	if( $('#stockName').val()=='' ){
		alert('仓库不能为空');
		return false;
	}else{
		if (confirm("请确认锁定/解锁数量？")) {
			$("#lockForm").submit();
		}
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
			var msg = '锁定数量不能大于可锁定数量（订单设备数量减去已锁定总数）！';
			alert(msg);
			vaildMsg[index] = msg;
			isCanSubmit[index] = false;
			return;
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
	// showThickboxWin('index1.php?model=stock_lock_lock&action=lockRecords&equId='+
	// equId
	// +'&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=900');
	var url = "index1.php?model=stock_lock_lock&action=lockRecords&equId="
			+ equId + "&objType=" + $('#objType').val() + "&objCode="+$('#objCode').val()+"&skey="
			+ $('#skey').val();
	+'&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700';
	if (isWidthStock == true) {
		url += "&stockId=" + $('#stockId').val()
	}
	// alert( $('#id').val() )
	url += "&id=" + $('#id').val();
	this.location = url;
}
