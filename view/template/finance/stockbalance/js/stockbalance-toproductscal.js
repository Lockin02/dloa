/**
 * 选择核算对象
 * @param {} thisVal
 */
function showInput(thisVal){
	$("#checkType").val(thisVal);
	$("#productNo").yxcombogrid_product("remove");
	$("#productNo").attr("disabled","disabled");
	$("#productNo").val("");
	$("#productId").val("");
	$("#productNoBegin").yxcombogrid_product("remove");
	$("#productNoBegin").attr("disabled","disabled");
	$("#productNoBegin").val("");
	$("#productIdBegin").val("");
	$("#productNoEnd").yxcombogrid_product("remove");
	$("#productNoEnd").attr("disabled","disabled");
	$("#productNoEnd").val("");
	$("#productIdEnd").val("");
	if( thisVal == 2 ){
		$("#productNo").attr("disabled","");
		$("#productNo").yxcombogrid_product({
			hiddenId : 'productId',
			nameCol : 'productCode',
			height : 300,
			width : 720,
			gridOptions : {
				param : {"properties":"WLSXZZ"},
				showcheckbox : false
			}
		});
	}else if(thisVal == 3){
		$("#productNoBegin").attr("disabled","");
		$("#productNoBegin").yxcombogrid_product({
			hiddenId : 'productIdBegin',
			nameCol : 'productCode',
			height : 300,
			width : 720,
			gridOptions : {
				param : {"properties":"WLSXZZ"},
				showcheckbox : false
			}
		});
		$("#productNoEnd").attr("disabled","");
		$("#productNoEnd").yxcombogrid_product({
			hiddenId : 'productIdEnd',
			nameCol : 'productCode',
			height : 300,
			width : 720,
			gridOptions : {
				param : {"properties":"WLSXZZ"},
				showcheckbox : false
			}
		});
	}
}

function materialsCal(){
	if($("#checkType").val() == 2){
		if($("#productId").val() == "" ){
			alert('指定物料编码时，请选择对应物料编码');
			return false;
		}
	}
	if($("#checkType").val() == 3){
		if($("#productIdBegin").val() == "" ){
			alert('指定物料编码范围时，请选择对应物料编码');
			return false;
		}
		if($("#productIdEnd").val() == "" ){
			alert('指定物料编码范围时，请选择对应物料编码');
			return false;
		}
	}
	if(confirm("确定要进行核算吗?")){
		$("#loading").show();
		$.ajax({
			type : "POST",
			url : "?model=finance_stockbalance_stockbalance&action=productsCal",
			data : {
				"thisYear" : $("#thisYear").val(),
				"thisMonth" : $("#thisMonth").val(),
				"productId" : $("#productId").val(),
				"productNo" : $("#productNo").val(),
				"productNoBegin" : $("#productNoBegin").val(),
				"productNoEnd" : $("#productNoEnd").val(),
				"checkType" : $("#checkType").val()
			},
			success : function(msg) {
				if (msg == 1) {
					alert('核算成功！');
					$("#loading").hide();
					$("#toResult").show();
				}else{
					alert("核算失败! ");
					$("#loading").hide();
				}
			}
		});
	}
}

function calDetal(){
//	var url = '?model=finance_stockbalance_stockbalance&action=allCalDetail&thisYear='
	var url = '?model=finance_stockbalance_stockbalance&action=calResultList&thisYear='
				+ $("#thisYear").val() + '&thisMonth=' + $("#thisMonth").val()
				+ '&properties=WLSXZZ'+ '&checkType=' + $("#checkType").val()
				+ '&productId='+ $("#productId").val()
				+ '&productNoBegin='+ $("#productNoBegin").val()
				+ '&productNoEnd='+ $("#productNoEnd").val()
				;
	showOpenWin(url);
}

// 更新合同/赠送物料成本
function materialsCostAct(){
	if($("#checkType").val() == 2){
		if($("#productId").val() == "" ){
			alert('指定物料编码时，请选择对应物料编码');
			return false;
		}
	}
	if($("#checkType").val() == 3){
		if($("#productIdBegin").val() == "" ){
			alert('指定物料编码范围时，请选择对应物料编码');
			return false;
		}
		if($("#productIdEnd").val() == "" ){
			alert('指定物料编码范围时，请选择对应物料编码');
			return false;
		}
	}
	if(confirm("确定要进行更新吗?")){
		$("#loading").show();
		$.ajax({
			type : "POST",
			url : "?model=finance_stockbalance_stockbalance&action=productsCostAct",
			data : {
				"thisYear" : $("#thisYear").val(),
				"thisMonth" : $("#thisMonth").val(),
				"productId" : $("#productId").val(),
				"productNo" : $("#productNo").val(),
				"productNoBegin" : $("#productNoBegin").val(),
				"productNoEnd" : $("#productNoEnd").val(),
				"checkType" : $("#checkType").val()
			},
			success : function(msg) {
				if (msg == 1) {
					alert('更新成功！');
					$("#loading").hide();
				}else{
					alert("更新失败! ");
					$("#loading").hide();
				}
			}
		});
	}
}

//更新物料成本
function productInfoCost(){
	if($("#checkType").val() == 2){
		if($("#productId").val() == "" ){
			alert('指定物料编码时，请选择对应物料编码');
			return false;
		}
	}
	if($("#checkType").val() == 3){
		if($("#productIdBegin").val() == "" ){
			alert('指定物料编码范围时，请选择对应物料编码');
			return false;
		}
		if($("#productIdEnd").val() == "" ){
			alert('指定物料编码范围时，请选择对应物料编码');
			return false;
		}
	}
	if(confirm("确定要进行更新吗?")){
		$("#loading").show();
		$.ajax({
			type : "POST",
			url : "?model=finance_stockbalance_stockbalance&action=productInfoCost",
			data : {
				"thisYear" : $("#thisYear").val(),
				"thisMonth" : $("#thisMonth").val(),
				"productId" : $("#productId").val(),
				"productNo" : $("#productNo").val(),
				"productNoBegin" : $("#productNoBegin").val(),
				"productNoEnd" : $("#productNoEnd").val(),
				"checkType" : $("#checkType").val()
			},
			success : function(msg) {
				if (msg == 1) {
					alert('更新成功！');
					$("#loading").hide();
				}else{
					alert("更新失败! ");
					$("#loading").hide();
				}
			}
		});
	}
}