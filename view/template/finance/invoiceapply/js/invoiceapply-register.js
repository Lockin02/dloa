$(function(){
	//限制开票金额
	if( $('#remainMoney').val()*1 <= 0 ){
		document.getElementById( "addButton" ).disabled = "none";
		document.getElementById( "addButton" ).title = "开票金额已达上限";
	}

	if($('#objType').val() != "" ){
		$('#showContract').show();
	}
});

function show_page(){
	this.location.reload();
}

//录入邮寄信息
function addMailInfo(thisId,thisCode,skey){
	showThickboxWin("?model=mail_mailinfo"
		+ "&action=addByPush"
		+ "&docId=" + thisId
		+ "&docCode=" + thisCode
		+ '&docType=YJSQDLX-FPYJ'
		+ '&skey=' + skey
		+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500"
		+ "&width=900");
}

//查看邮寄信息
function viewMailInfo(thisId,thisCode,skey){
	showThickboxWin("?model=mail_mailinfo&action=viewByDoc&docId="
		+ thisId
		+ '&docType=YJSQDLX-FPYJ'
		+ '&skey=' + skey
		+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
}


/**
 * 开票历史
 * @param thisVal
 * 1.开票历史
 * 2.单据信息
 */
function clickFun(thisVal){
	var url = "";
	if(thisVal == 1){
		url = '?model=finance_invoice_invoice&action=invoiceHistory'
            + '&obj[objId]=' + $("#objId").val()
            + '&obj[objCode]=' + $("#objCode").val()
            + '&obj[objType]=' + $("#objType").val()
            + '&skey=' + $("#objSkey").val()
		;
	}else{
		url = '?model=finance_invoiceapply_invoiceapply&action=toViewObj'
            + '&invoiceapply[objId]=' + $("#objId").val()
            + '&invoiceapply[objCode]=' + $("#objCode").val()
            + '&invoiceapply[objType]=' + $("#objType").val()
            + '&invoiceapply[skey]=' + $("#objSkey").val()
		;
	}
	showOpenWin(url);
}

/**
 * ajax方式删除信息操作
 *
 * @return {Boolean}
 */
function deleteThickbox() {
	var checkIDS = checkOne();
	var ids = checkIDS.substring(0, checkIDS.length - 1);
	if (checkIDS.length == 0) {
		alert("提示: 请选择一条信息.");
		return false;
	}

	idArray = ids.split(",");
	var isCarried = 0;
	for(var i in idArray){

		if(!isNaN(idArray[i])){
			if($("#isMail" +idArray[i]).val() == 1 ){
				alert('所选记录中存在已邮寄的开票记录，不能进行删除操作！');
				return false;
				break;
			}else{
				var hasRed = 0;
				$.ajax({
				    type: "POST",
				    url: "?model=finance_invoice_invoice&action=hasRedInvoice",
				    data: {"id" : idArray[i]},
				    async: false,
				    success: function(data){
			   	   		hasRed = data;
					}
				});

				if(hasRed == 1){
					alert('所选记录中存在已生成红字发票的开票记录，请先删除红字发票再进行删除操作！');
					return false;
					break;
				}

				$.ajax({
				    type: "POST",
				    url: "?model=finance_carriedforward_carriedforward&action=invoiceIsCarried",
				    data: {"id" : idArray[i]},
				    async: false,
				    success: function(data){
				   	   if(data == 1){
				   	   		isCarried = 1;
						}else{
							isCarried = 0;
						}
					}
				});
				if( isCarried == 1) {
		   	   		alert('发票已经被结转,不能进行删除操作');
		   	   		return false;
					break;
	   	   		}
			}
		}
	}

	if (window.confirm("确认要删除？")) {
		$.ajax({
			type : "POST",
			url : "?model=finance_invoice_invoice&action=ajaxdeletes",
			data : {
				id : ids
			},
			success : function(msg) {
				if (msg == 1) {
					alert('删除成功！');
					show_page(1);
				}
			}
		});
	}
}