function dis(name) {
	var temp = document.getElementById(name);
	if (temp.style.display == '')
		temp.style.display = "none";
	else if (temp.style.display == "none")
		temp.style.display = '';
}
function GetQueryString(name) {
   var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)","i");
   var r = window.location.search.substr(1).match(reg);
   if (r!=null) return (r[2]); return null;
}
$(function() {
	var suppNumb=$("#suppNumb").val();
	switch(suppNumb){
		case '0':$(".supHtml1").hide();$(".supHtml2").hide();$(".supHtml3").hide();$("#inquiryTable").hide();$("#mytable").hide();break;
		case '1':$(".supHtml2").hide();$(".supHtml3").hide();break;
		case '2':$(".supHtml3").hide();break;
		case '3':;break;
	}
	// �鿴ѯ�۵�ʱ����ȡ��Ӧ����Ϣ
	var parentId = $("#id").val();
	var gdbtable=GetQueryString("gdbtable");
	$.post("?gdbtable="+gdbtable+"&model=purchase_contract_applysupp&action=getSupp", {
		parentId : parentId
	}, function(data) {
        var o = eval("(" + data + ")");
		for (i = 1; i < 4; i++) {
			if (o[i - 1]) {
				var quotes=moneyFormat2(o[i-1].quote);
				$("#supplier"+ i).append('<font color="blue"> <a target="_blank" title="����鿴��Ӧ����Ϣ" onclick="viewSupplier('+o[i - 1].suppId+')"  title="�鿴��Ӧ����Ϣ">'+o[i - 1].suppName+'</a></font>');
				$("#product"+i).append(o[i-1].suppTel);
				$("#quoteAll"+i).append(quotes);
				$("#arrivalDate" + i).append(o[i - 1].arrivalDate);
				$("#supplierName" + i).val(o[i - 1].suppName);
				$("#supplierId"+i).val(o[i-1].suppId);
				$("#products"+i).val(o[i-1].suppTel);
				$("#paymentCondition"+i).append(o[i-1].paymentConditionName+"  "+o[i-1].payRatio);
				$("#remark"+i).append(o[i-1].remark);
				$("#quote"+i).val(o[i-1].quote);
				$("#suppId"+i).val(o[i-1].id);
				var v=$("#supplierId"+i).val();
				var name=$("#supplierName"+i).val();
				if(o[i - 1].sid != ''){
				  $("#ss"+i).append('<font color="blue"> <a target="_blank" title="����鿴��Ӧ��������Ϣ" onclick="ssView('+o[i - 1].sid+')"  title="�鿴��Ӧ��������Ϣ">������Ϣ</a></font>');
				}else{
				  $("#ss"+i).append('-');
				}

			}
		}

		var quoteArr=new Array();
		for(j=1;j<4;j++){    //û�й�Ӧ�������ز鿴������ϸͼƬ
			if($("#suppId"+j).val()==""){
				$("#supp"+j).hide();
			}else{
				quoteArr[j-1]=$("#quote"+j).val();
			}
		}
		Array.prototype.min = function(){   //��Сֵ
		 return Math.min.apply({},this)
		}
		for(j=1;j<4;j++){//��С�ܽ��ı��
			if($("#quote"+j).val()==quoteArr.min()&&quoteArr.min()!='infinity'){
				$("#quoteAll"+j).html("<font color='red'>"+moneyFormat2(quoteArr.min())+"</font>");
				var suppId=$("#supplierId"+j).val();
				//ָ����Ӧ��radio����С�ܽ�Ӧ�̱��
				$("#p"+j).remove();
				$("#assignSuppId"+suppId).after("<font color='red'>"+$("#supplierName"+j).val()+"</font>")
			}
		}
	});


	if($('#isApplyPay').val()==1){
		$(".payapply").show();
	}
	if($('#isAudit').val()==1){
		$("#closeButn").hide();
		$("#auditView").hide();
		dis('otherinfo');
	}



});

/**�����������ҳ��*/
function quoteRead(index){
	var inquiryId=$("#parentId").val();
	var parentId=$("#suppId"+index).val();
	var supplierName=$("#supplierName"+index).val();
	var supplierId=$("#supplierId"+index).val();
	var quoteId= document.getElementById("quote"+index).id;
	var quote=$("#quote"+index).val();
	//��ת�����۵����ҳ��
	if(parentId!=""){
			showThickboxWin("index1.php?model=purchase_contract_applysupp&action=toView&parentId="+parentId+"&inquiryId="+inquiryId+"&supplierName="+supplierName+"&supplierId="+supplierId
			 +"&quoteId="+quoteId+"&quote="+quote+"&placeValuesBefore&TB_iframe=true&modal=false&height=380&width=930");
	}
	else{
	  alert("û����Ӧ�ı��۵���")
	}
}


/**
 * �鿴��Ӧ����Ϣ
 */
function viewSupplier(suppId) {
	var skey = "";
	$.ajax({
				type : "POST",
				url : "?model=supplierManage_formal_flibrary&action=md5RowAjax",
				data : {
					"id" : suppId
				},
				async : false,
				success : function(data) {
					skey = data;
				}
			});
	showModalWin("?model=supplierManage_formal_flibrary&action=toRead&id="
			+ suppId
			+ "&skey="
			+ skey
			+ "&perm=view&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1100");
}
function ssView(sid) {
	var skey = "";
	$.ajax({
				type : "POST",
				url : "?model=supplierManage_assessment_supasses&action=md5RowAjax",
				data : {
					"id" : sid
				},
				async : false,
				success : function(data) {
					skey = data;
				}
			});
	showModalWin("?model=supplierManage_assessment_supasses&action=toView&id="
			+ sid
			+ "&skey="
			+ skey
			+ "&perm=view&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1100");
}
//�鿴������Ϣ
function viewProduct(id){
	var gdbtable=GetQueryString("gdbtable");
	showThickboxWin("?model=stock_productinfo_productinfo&action=view&id="
		+ id + "&gdbtable=" + gdbtable
		+ "&productId=$rs[productId]&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=850");
}




