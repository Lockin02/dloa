$(function(){
	//���ƿ�Ʊ���
	if( $('#remainMoney').val()*1 <= 0 ){
		document.getElementById( "addButton" ).disabled = "none";
		document.getElementById( "addButton" ).title = "��Ʊ����Ѵ�����";
	}

	if($('#objType').val() != "" ){
		$('#showContract').show();
	}
});

function show_page(){
	this.location.reload();
}

//¼���ʼ���Ϣ
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

//�鿴�ʼ���Ϣ
function viewMailInfo(thisId,thisCode,skey){
	showThickboxWin("?model=mail_mailinfo&action=viewByDoc&docId="
		+ thisId
		+ '&docType=YJSQDLX-FPYJ'
		+ '&skey=' + skey
		+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
}


/**
 * ��Ʊ��ʷ
 * @param thisVal
 * 1.��Ʊ��ʷ
 * 2.������Ϣ
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
 * ajax��ʽɾ����Ϣ����
 *
 * @return {Boolean}
 */
function deleteThickbox() {
	var checkIDS = checkOne();
	var ids = checkIDS.substring(0, checkIDS.length - 1);
	if (checkIDS.length == 0) {
		alert("��ʾ: ��ѡ��һ����Ϣ.");
		return false;
	}

	idArray = ids.split(",");
	var isCarried = 0;
	for(var i in idArray){

		if(!isNaN(idArray[i])){
			if($("#isMail" +idArray[i]).val() == 1 ){
				alert('��ѡ��¼�д������ʼĵĿ�Ʊ��¼�����ܽ���ɾ��������');
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
					alert('��ѡ��¼�д��������ɺ��ַ�Ʊ�Ŀ�Ʊ��¼������ɾ�����ַ�Ʊ�ٽ���ɾ��������');
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
		   	   		alert('��Ʊ�Ѿ�����ת,���ܽ���ɾ������');
		   	   		return false;
					break;
	   	   		}
			}
		}
	}

	if (window.confirm("ȷ��Ҫɾ����")) {
		$.ajax({
			type : "POST",
			url : "?model=finance_invoice_invoice&action=ajaxdeletes",
			data : {
				id : ids
			},
			success : function(msg) {
				if (msg == 1) {
					alert('ɾ���ɹ���');
					show_page(1);
				}
			}
		});
	}
}