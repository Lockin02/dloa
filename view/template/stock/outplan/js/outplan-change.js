
$(function() {
	//从表仓库渲染
	temp = $('#rowNum').val();
	for(var i=1;i<=temp;i++){
	$("#stockName"+ i).yxcombogrid_stockinfo({
				hiddenId : 'stockId'+i,
				nameCol : 'stockName',
				gridOptions : {
					showcheckbox : false,
					event : {
						'row_dblclick' : function(i){
						return function(e, row, data) {
							$('#stockId' + i).val(data.id);
							$('#stockCode' + i).val(data.stockCode);
							$('#stockName' + i).val(data.stockName);
						}
					}(i)
				}
			}
		});
	}
	if($("#isNeedConfirm").val()=='1'){
		$("#overTimeRea").show();
	}else{
		$("#overTimeRea").hide();
	}
});
$(function(){
	//主表仓库渲染
	$("#stockName").yxcombogrid_stockinfo({
		hiddenId : 'stockId',
		nameCol : 'stockName',
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					$('#stockId').val(data.id);
					$('#stockCode').val(data.stockCode);
					$('#stockName').val(data.stockName);
					temp = $('#rowNum').val();
					for(var i=1;i<=temp;i++){
						if($('#stockName' + i).val() == ""){
							$('#stockId' + i).val(data.id);
							$('#stockCode' + i).val(data.stockCode);
							$('#stockName' + i).val(data.stockName);
						}
					}
				}
			}
		}
	});
});

$(function(){
	if( $('#pageAction').val() != 'feedback' ){
		$('tr.Feedback').hide();
	}
})


function changeDate(){
		//计算周次
		$.ajax({
			type : 'POST',
			url : '?model=stock_outplan_outplan&action=week',
			data : {
				date : $('#shipPlanDate').val()
			},
			success : function(data) {
				$('#week').val(data)
			}
		});
		//判断是否超期发货
		$.ajax({
			type : "POST",
			url : "?model=stock_outplan_outplan&action=checkShipPlanDate",
			async: false,
			data : {
				shipPlanDate :  $("#shipPlanDate").val(),
				contractId : $("#docId").val()
			},
			success : function(msg) {
				if (msg == 1) {
					$("#isNeedConfirm").val('1');
					$("#overTimeRea").show();
				}else {
					$("#isNeedConfirm").val('0');
					$("#overTimeReason").val('');
					$("#overTimeRea").hide();
				}
			}
		});
}


function checkThis( obj ){
	if( $('#number'+obj).val()*1 > $('#contRemain' + obj).val()*1 ){
		alert( '此次发货数量超过合同剩余数量！请重新输入！' );
		$('#number' + obj).val($('#contRemain' + obj).val());
		$('#number' + obj+ '_v').val($('#contRemain' + obj).val());
	}
	if( $('#number'+obj).val()*1 < $('#executedNum' + obj).val()*1 ){
		alert( '此次发货数量少于合同已出库数量！请重新输入！' );
		$('#number' + obj).val($('#contRemain' + obj).val());
		$('#number' + obj+ '_v').val($('#contRemain' + obj).val());
	}
}
function issuedFun(){
	document.getElementById('form1').action="?model=stock_outplan_outplan&action=edit&issued=true&msg=下达成功";
}


/***********************动态新增表单**************************/
//$(function() {
//	// 产品线数据
//	productLineArr = getData('CPX');
//	addDataToSelect(productLineArr, 'productLine0');
//	addDataToSelect(productLineArr, 'cProductLine0');
//
//});

/** ********************条目列表************************ */
function dynamic_add(packinglist, countNumP) {
	mycount = document.getElementById(countNumP).value * 1 + 1;
	var packinglist = document.getElementById(packinglist);
	i = packinglist.rows.length;
	oTR = packinglist.insertRow([i]);



	var oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = i;
//	var oTL1 = oTR.insertCell([1]);
//	oTL1.innerHTML = "<input type='text' id='productLineName" + mycount
//		+ "' class='readOnlyTxtItem' name='outplan[details][" + mycount
//		+ "][productLineName]'  /><input type='hidden' id='productLine" + mycount
//		+ "' class='readOnlyTxtItem' name='outplan[details][" + mycount
//		+ "][productLine]'  />";
	var oTL1 = oTR.insertCell([1]);
	oTL1.innerHTML = "<input type='text' id='productNo" + mycount
		+ "' class='txtshort' name='outplan[details][" + mycount
		+ "][productNo]'  />";
	var oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML = "<input type='hidden' id='productId" + mycount + "'  name='outplan[details]["
		+ mycount + "][productId]'/>" + "<input id='productName"
		+ mycount + "' type='text' class='txtmiddle' name='outplan[details]["
		+ mycount + "][productName]'/>" +
				"<input type='hidden' id='tablename" + mycount + "' name='outplan[details]["
				+ mycount +"][tablename]'/>";

	var productIdStr = '';
	$.each($(':input[id^="productId"]'), function(i, n) {
		if($(this).val() != ''){
			productIdStr += ',' + $(this).val();
		}
	})
	productIdStr=productIdStr.substr(1);
	// 单选产品
	$("#productNo" + mycount).yxcombogrid_contequ({
		hiddenId : 'productId' + mycount,
		focusoutCheckAction : 'getCountByNameInd',
		gridOptions : {
			showcheckbox : false,
			param:{
				'contproId':productIdStr,
				'conttblname':$('#docType').val(),
				'orderOrgId':$('#docId').val()
			},
			event : {
				'row_dblclick' : function(mycount){
						return function(e, row, data) {
							var temp = false;
						$.each($(':input[id^="productId"]'), function(i, n) {
							if($(this).val() != '' && $(this).val()==data.productId){
								alert('请勿重复选择物料！');
								temp = true;
							}
						})
						if( temp ){
							$("#productName" + mycount).val('')
							return;
						}
						var productLineName = '';
						if(data.productLine){
							productLineName = getDataByCode(data.productLine);
						}
						$("#productLine"+mycount).val(data.productLine);
						$("#productLineName"+mycount).val(productLineName);
						$("#tablename"+mycount).val(data.tablename);
						$("#productId"+mycount).val(data.productId);
						$("#productNo"+mycount).val(data.productNo);
						$("#productName"+mycount).val(data.productName);
						$("#productModel"+mycount).val(data.pattern);
//						if(data.isSell == 'on'){
//							$("#isSell"+mycount).html('否');
//						}else{
//							$("#isSell"+mycount).css({"color":"red"});
//							$("#isSell"+mycount).html('是');
//						}
						$('#inventory'+mycount).unbind().bind('click', function() {
							showOpenWin('index1.php?model=stock_inventoryinfo_inventoryinfo&action=toInTimeList&productId=' +
									+ data.productId
									+ '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=1000');
						});
						$("#unitName"+mycount).val(data.unitName);
						$("#orderOrgid"+mycount).val(data.orderOrgid);
						$("#orderId"+mycount).val(data.orderId);
						$("#orderCode"+mycount).val(data.orderCode);
						$("#orderName"+mycount).val(data.orderName);
						$("#backNum"+mycount).html(data.backNum);
						$("#contNum"+mycount).html(data.number);
						$("#planNum"+mycount).html(data.issuedShipNum);
						$('#contEquId'+mycount).val(data.id);
						$("#issuedShipNum"+mycount).val((data.issuedShipNum)*1);
						$("#contRemain"+mycount).val((data.number)*1-(data.issuedShipNum)*1);
						$("#number"+mycount).val((data.number)*1-(data.issuedShipNum)*1);
						$("#executedNum"+mycount).html(data.executedNum);
					};
			  	}(mycount)
			}
		}
	});
	// 单选产品
	$("#productName" + mycount).yxcombogrid_contequ({
		hiddenId : 'productId' + mycount,
		nameCol : 'productName',
		focusoutCheckAction : 'getCountByNameInd',
		gridOptions : {
			showcheckbox : false,
			param:{
				'contproId':productIdStr,
				'conttblname':$('#docType').val(),
				'orderOrgId':$('#docId').val()
			},
			event : {
				'row_dblclick' : function(mycount){
						return function(e, row, data) {
							var temp = false;
						$.each($(':input[id^="productId"]'), function(i, n) {
							if($(this).val() != '' && $(this).val()==data.productId){
								alert('请勿重复选择物料！');
								temp = true;
							}
						})
						if( temp ){
							$("#productName" + mycount).val('')
							return;
						}
						var productLineName = '';
						if(data.productLine){
							productLineName = getDataByCode(data.productLine);
						}
						$("#productLine"+mycount).val(data.productLine);
						$("#productLineName"+mycount).val(productLineName);
						$("#tablename"+mycount).val(data.tablename);
						$("#productId"+mycount).val(data.productId);
						$("#productNo"+mycount).val(data.productNo);
						$("#productName"+mycount).val(data.productName);
						$("#productModel"+mycount).val(data.pattern);
//						if(data.isSell == 'on'){
//							$("#isSell"+mycount).html('否');
//						}else{
//							$("#isSell"+mycount).css({"color":"red"});
//							$("#isSell"+mycount).html('是');
//						}
						$('#inventory'+mycount).unbind().bind('click', function() {
							showOpenWin('index1.php?model=stock_inventoryinfo_inventoryinfo&action=toInTimeList&productId=' +
									+ data.productId
									+ '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=1000');
						});
						$("#unitName"+mycount).val(data.unitName);
						$("#orderOrgid"+mycount).val(data.orderOrgid);
						$("#orderId"+mycount).val(data.orderId);
						$("#orderCode"+mycount).val(data.orderCode);
						$("#orderName"+mycount).val(data.orderName);
						$("#contNum"+mycount).html(data.number);
						$("#backNum"+mycount).html(data.backNum);
						$("#planNum"+mycount).html(data.issuedShipNum);
						$('#contEquId'+mycount).val(data.id);
						$("#issuedShipNum"+mycount).val((data.issuedShipNum)*1);
						$("#contRemain"+mycount).val((data.number)*1-(data.issuedShipNum)*1);
						$("#number"+mycount).val((data.number)*1-(data.issuedShipNum)*1);
						$("#executedNum"+mycount).html(data.executedNum);
					};
			  	}(mycount)
			}
		}
	});
	var oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML = "<input id='productModel" + mycount
			+ "' type='text' class='readOnlyTxtItem' readonly='readonly' name='outplan[details]["
			+ mycount + "][productModel]'>";
	var oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML = "<input id='unitName" + mycount
			+ "' type='text' class='readOnlyTxtShort' readonly='readonly' name='outplan[details]["
			+ mycount + "][unitName]'>";
	var oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML = "<input type='hidden' id='dstockId" + mycount + "' name='outplan[details][" + mycount + "][stockId]'/>"
			+ "<input type='hidden' id='dstockCode" + mycount + "' name='outplan[details][" + mycount + "][stockCode]'/>"
			+ "<input type='text' id='dstockName" + mycount
			+ "' class='stockName txtmiddle' name='outplan[details][" + mycount
			+ "][stockName]' readonly/>";
	// 仓库下拉
	$("#dstockName" + mycount).yxcombogrid_stockinfo({
		hiddenId : 'dstockId'+ mycount,
		nameCol : 'stockName',
		gridOptions :{
			showcheckbox : false,
			event : {
				'row_dblclick' : function(mycount){
					return function(e, row, data) {
						$('#dstockId' + mycount).val(data.id);
						$('#dstockCode' + mycount).val(data.stockCode);
						$('#dstockName' + mycount).val(data.stockName);
					}
				}(mycount)
			}
		}
	});

	$("#dstockId" + mycount).val($("#stockId").val());
	$("#dstockCode" + mycount).val($("#stockCode").val());
	$("#dstockName" + mycount).val($("#stockName").val());

	var oTL6 = oTR.insertCell([6]);
	oTL6.innerHTML = "<input id='ext1" + mycount
			+ "' type='hidden' class='txtshort'><font id='contNum" + mycount
			+ "' color=green>0</font>";
	var oTL7 = oTR.insertCell([7]);
	oTL7.innerHTML = "<font id='backNum" + mycount
			+ "' color=green>0</font>";
	var oTL8 = oTR.insertCell([8]);
	oTL8.innerHTML = "<input id='ext2" + mycount
			+ "' type='hidden' class='txtshort'><font id='planNum" + mycount
			+ "' color=green>0</font>";
	var oTL9 = oTR.insertCell([9]);
	oTL9.innerHTML = "<input type='text' name='outplan[details]["+ mycount + "][number]' id='number"
			+ mycount + "' class='txtshort' onblur='checkThis("+ mycount + ")'/>" +
					"<input type='hidden' name='outplan[details]["+ mycount + "][issuedShipNum]' id='issuedShipNum"
			+ mycount + "' class='txtshort'/>" +
					"<input type='hidden' name='outplan[details]["+ mycount + "][contEquId]' id='contEquId"
			+ mycount + "' class='txtshort'/>" +
					"<input type='hidden' id='contRemain"
			+ mycount + "' class='txtshort'/>";
	var oTL10 = oTR.insertCell([10]);
	oTL10.innerHTML = "<input id='ext4" + mycount
			+ "' type='hidden' class='txtshort'><font id='executedNum" + mycount
			+ "' color=green>0</font>";
//	var oTL11 = oTR.insertCell([11]);
//	oTL11.innerHTML = "<input id='ext5" + mycount
//			+ "' type='hidden' class='txtshort'><font id='isSell" + mycount
//			+ "'>否</font>";
	var oTL11 = oTR.insertCell([11]);
	oTL11.innerHTML = "<a href='#' id='inventory"+ mycount
			+"'><font color=blue >即时库存</font></a>";
	var oTL12 = oTR.insertCell([12]);
	oTL12.innerHTML = "<img src='images/closeDiv.gif' onclick='mydel(this,\""
			+ packinglist.id + "\")' title='删除行'>";

	document.getElementById(countNumP).value = document
			.getElementById(countNumP).value
			* 1 + 1;

}

//检查计划发货日期是否大于希望发货日期
function checkDate(){
	var result;
	if($("#isNeedConfirm").val()=='1'){
		if(confirm("计划发货日期大于希望发货日期，需要销售人员确认，是否确认下达发货计划?")){
			if($("#overTimeReason").val()==''){
				alert("超期原因不能为空！");
				result =  false;
				location.reload();
			}else{
				result = true;
			}
		}else{
				result =  false;
				location.reload();
		}
	}else{
		result =  true;
	}
	return result;
}


/** ********************删除动态表单************************ */
function mydel(obj, mytable) {
	if (confirm('确定要删除该行？')) {
		var rowNo = obj.parentNode.parentNode.rowIndex * 1 - 1;
		var thisRow=$(obj.parentNode.parentNode);
		thisRow.append("<input type='hidden' name='outplan[details]["+ rowNo +"][isDel]' value='1'>");
		thisRow.hide();
		var mytable = document.getElementById(mytable);
		var myrows = mytable.rows.length;
		var j=0;
		for (i = 1; i < myrows; i++) {
			if(mytable.rows[i].style.display == ''){
				mytable.rows[i].childNodes[0].innerHTML = ++j;
			}
		}
	}
}
/** ********************删除动态表单--借用转销售************************ */
function mydelBorrow(obj, mytable) {
	if (confirm('确定要删除该行？')) {
		var rowNo = obj.parentNode.parentNode.rowIndex * 1 - 1;
		var thisRow=$(obj.parentNode.parentNode);
		thisRow.append("<input type='hidden' name='outplan[bItem]["+ rowNo +"][isDel]' value='1'>");
		thisRow.hide();
		var mytable = document.getElementById(mytable);
		var myrows = mytable.rows.length;
		var j=0;
		for (i = 1; i < myrows; i++) {
			if(mytable.rows[i].style.display == ''){
				mytable.rows[i].childNodes[0].innerHTML = ++j;
			}
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
