$(function(){
	$("#checkAll").attr('checked',true);
});

//����ѡ����Ⱦ
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

//������Ϣѡ��
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


//��ת���б�ҳ��
function toList(){
	if($("#checkType").val() == 2){
		if($("#productId").val() == "" ){
			alert('ָ�����ϱ���ʱ����ѡ���Ӧ���ϱ���');
			return false;
		}
	}
	if($("#checkType").val() == 3){
		if($("#productIdBegin").val() == "" ){
			alert('ָ�����ϱ��뷶Χʱ����ѡ���Ӧ���ϱ���');
			return false;
		}
		if($("#productIdEnd").val() == "" ){
			alert('ָ�����ϱ��뷶Χʱ����ѡ���Ӧ���ϱ���');
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