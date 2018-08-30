$(function(){
	$("#checkAll").attr('checked',true);
});

//部门选择渲染
function showDept(){
	if($("#isGroupByDept").attr('checked') == true){
		$("#deptName").yxselect_dept({
			hiddenId : 'deptId',
			mode : 'check'
		});
		$("#isGroupByDeptTip").show();
	}else{
		$("#deptName").yxselect_dept('remove');
		$("#isGroupByDeptTip").hide();
	}
}

//物料信息选择
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
				param : {"properties":"WLSXWG"},
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
				param : {"properties":"WLSXWG"},
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
				param : {"properties":"WLSXWG"},
				showcheckbox : false
			}
		});
	}
}


//跳转到列表页面
function toList(){
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
	if($("#isGroupByDept").attr('checked') == true){
		location='?model=finance_stockbalance_stockbalance&action=productInCalList&isGroupByDept=1'
			+ '&checkType=' + $("#checkType").val()
			+ '&productId='+ $("#productId").val()
			+ '&productNoBegin='+ $("#productNoBegin").val()
			+ '&productNoEnd='+ $("#productNoEnd").val()
			+ '&deptId='+ $("#deptId").val()
		;
	}else
		location='?model=finance_stockbalance_stockbalance&action=productInCalList'
			+ '&checkType=' + $("#checkType").val()
			+ '&productId='+ $("#productId").val()
			+ '&productNoBegin='+ $("#productNoBegin").val()
			+ '&productNoEnd='+ $("#productNoEnd").val()
		;
}