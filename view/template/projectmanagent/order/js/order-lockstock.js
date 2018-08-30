var stockId = "";// ȫ�ֱ����ֿ�id
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

							var actNum = inventory.actNum;// ���
							var exeNum = inventory.exeNum;// ��ִ�п��
							$("[id^='actNum'][proId=" + proId + "]")
									.html(actNum);
							$("[id^='exeNum'][proId=" + proId + "]")
									.html(exeNum);
							$("[id^='inventoryId'][proId=" + proId + "]")
									.val(inventory.id);
							// $('#inventoryId' + i).val(inventory.id);//
							// ���ÿ����Ϣid
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
	 * ��ȡ�豸��ĳ���ֿ��µ��Ѿ���������
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

	// ��ȡ��Ʒid
	var productIds = $("input[id^='productId']");
	var productIdArr = [];
	for (var i = 0; i < productIds.size(); i++) {
		productIdArr.push($(productIds[i]).val());

	}
	getInventoryInfos(stockId, productIdArr);

	// ��ȡ�豸id
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
							// ����ajax�����ȡ���²�Ʒ�Ŀ����Ϣ
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
// �ύ������Ϣ
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
		alert('�����豸��治�㣬��ȷ������������')
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
		alert('�ֿⲻ��Ϊ��');
		return false;
	}else{
		if (confirm("��ȷ������/����������")) {
			$("#lockForm").submit();
		}
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
			var msg = '�����������ܴ��ڿ����������������豸������ȥ��������������';
			alert(msg);
			vaildMsg[index] = msg;
			isCanSubmit[index] = false;
			return;
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
