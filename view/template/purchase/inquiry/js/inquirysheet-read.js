$(document).ready(function() {
	var suppNumb=$("#suppNumb").val();
	switch(suppNumb){
		case '0':$(".supHtml1").hide();$(".supHtml2").hide();$(".supHtml3").hide();break;
		case '1':$(".supHtml2").hide();$(".supHtml3").hide();break;
		case '2':$(".supHtml3").hide();break;
		case '3':;break;
	}
	// �鿴ѯ�۵�ʱ����ȡ��Ӧ����Ϣ
	var parentId = $("#parentId").val();
	$.post("?model=purchase_inquiry_inquirysupp&action=getSupp", {
		parentId : parentId
	}, function(data) {
//		alert(data)
        var o = eval("(" + data + ")");
		for (i = 1; i < 4; i++) {
			if (o[i - 1]) {
				var quotes=moneyFormat2(o[i-1].quote);
				$("#supplier"+ i).append(o[i - 1].suppName);
				$("#product"+i).append(o[i-1].suppTel);
				$("#quoteAll"+i).append(quotes);
				$("#supplierName" + i).val(o[i - 1].suppName);
				$("#supplierId"+i).val(o[i-1].suppId);
				$("#products"+i).val(o[i-1].suppTel);
				$("#paymentCondition"+i).append(o[i-1].paymentConditionName+"  "+o[i-1].payRatio);
				$("#remark"+i).append(o[i-1].remark);
				$("#quote"+i).val(o[i-1].quote);
				$("#suppId"+i).val(o[i-1].id);
				var v=$("#supplierId"+i).val();
				var name=$("#supplierName"+i).val();
				//����ѡ��Ӧ�̵�ѡ
				if(v){
				var radio=$("<input type='radio' id='assignSuppId"+v+"' name='inquirysheet[suppId]'>").val(v).attr('title',name);
					$("#suppRadios").append(radio);
						radio.after("<span id='p"+i+"'>"+name+"</span>");
						radio.click(function(){
						$("#suppName").val($(this).attr('title'));
//						alert($("#form1").html());
						var parentId=$("#parentId").val();    //�ɹ�ѯ��id
						var suppName=	$("#suppName").val(); //��Ӧ������
						var suppId=$(this).val();			//��Ӧ��ID
						var amaldarId=$("#amaldarId").val();  //ָ����ID
						var amaldarName=$("#amaldarName").val();  //ָ��������
						var amaldarDate=$("#amaldarDate").val();  //ָ������
						var amaldarRemark=$("#amaldarRemark").val();  //��ע
						var appendHtml=' <input type="hidden" id="parentId" name="inquirysheet[id]" value="'+parentId+'"/>'+' <input type="hidden" id="suppId" name="inquirysheet[suppId]" value="'+suppId+'"/>'+' <input type="hidden" id="suppName" name="inquirysheet[suppName]" value="'+suppName+'"/>'+
											' <input type="hidden" id="amaldarName" name="inquirysheet[amaldarName]" value="'+amaldarName+'"/>'+' <input type="hidden" id="amaldarId" name="inquirysheet[amaldarId]" value="'+amaldarId+'"/>'+
											'<input type="hidden" id="amaldarDate" name="inquirysheet[amaldarDate]" value="'+amaldarDate+'"/>'+'<input type="hidden" id="amaldarRemark" name="inquirysheet[amaldarRemark]" value="'+amaldarRemark+'"/>';
						if($(window.parent.document.getElementById("appendHtml")).html()!=""){   //����ѡ����Ȱ�ǰһ��׷�ӵ��������
							$(window.parent.document.getElementById("appendHtml")).html("");
						}
						$(window.parent.document.getElementById("appendHtml")).append(appendHtml);
//						$("#suppId").val(v);
					});
				}
			}
		}

		$(window.parent.document.getElementById("sub")).bind("click",function(){    //�����ύʱ���ж��Ƿ�ָ���˹�Ӧ��
			var result=$(window.parent.document.getElementById('form1')).find("input[name='result'][checked]").val();
			var isLast=$("#isLast").val();
			if(isLast!=""){    //�ж��Ƿ�Ϊ���һ��������,��Ϊ����Ϊ���һ��
				if(result=="ok"&&!($(window.parent.document.getElementById("suppId")).length>0)){
					alert("��ָ��һ����Ӧ�̡�");
					return false;
				}
			}
		});
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
});


	/*����ѯ�۵�ʱ����ӱ�ע��������*/
	function addRemark(){
		var amaldarRemark=$("#amaldarRemark").val();  //��ע
		if($(window.parent.document.getElementById("amaldarRemark")).length>0){
			$(window.parent.document.getElementById("amaldarRemark")).val(amaldarRemark);
		}else{
			var appendRemarkHtml='<input type="hidden" id="amaldarRemark" name="inquirysheet[amaldarRemark]" value="'+amaldarRemark+'"/>';
			$(window.parent.document.getElementById("appendHtml")).append(appendRemarkHtml);
		}

	}


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
			showThickboxWin("index1.php?model=purchase_inquiry_inquirysupp&action=readQuotation&parentId="+parentId+"&inquiryId="+inquiryId+"&supplierName="+supplierName+"&supplierId="+supplierId
			 +"&quoteId="+quoteId+"&quote="+quote+"&placeValuesBefore&TB_iframe=true&modal=false&height=380&width=930");
	}
	else{
	  alert("û����Ӧ�ı��۵���")
	}
}