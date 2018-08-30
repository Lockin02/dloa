/**
 * ѡ��������
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
	if(confirm("ȷ��Ҫ���к�����?")){
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
					alert('����ɹ���');
					$("#loading").hide();
					$("#toResult").show();
				}else{
					alert("����ʧ��! ");
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

// ���º�ͬ/�������ϳɱ�
function materialsCostAct(){
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
	if(confirm("ȷ��Ҫ���и�����?")){
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
					alert('���³ɹ���');
					$("#loading").hide();
				}else{
					alert("����ʧ��! ");
					$("#loading").hide();
				}
			}
		});
	}
}

//�������ϳɱ�
function productInfoCost(){
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
	if(confirm("ȷ��Ҫ���и�����?")){
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
					alert('���³ɹ���');
					$("#loading").hide();
				}else{
					alert("����ʧ��! ");
					$("#loading").hide();
				}
			}
		});
	}
}