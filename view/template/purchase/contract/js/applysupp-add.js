//ʵ�����ڵ�����
$(function(){


			var paymentCondition=$("#paymentCondition").val();
			if(paymentCondition=="YFK"){
				$("#payRatio").show();
			}else{
				$("#payRatio").hide();
			}

			for(i=5;i<101;i++){
				$("#payRatio").append('<option id="payRatio'+i+'" value="'+i+'%">'+i+'%</option>');
				i=i+4;
			}

			$("#paymentCondition").bind("change",function(){
				var paymentCondition=$("#paymentCondition").val();
				if(paymentCondition=="YFK"){
					$("#payRatio").show();
				}else{
					$("#payRatio").val("");
					$("#payRatio").hide();
				}
			});
	$("#taxRate").bind("change",function(){
		var taxRate=$(this).val();
		//���´�����1.4.2�б���
//		$.each($(':select[class^="taxRate"]'),function(i,n){
//			$(this).val(taxRate);
//		})
		$("select option[value='"+taxRate+"']").attr("selected", true);
;

	});
});

//�Խ������������֤�������㱨���ܽ��
function countDetail(obj){
	if(isNaN(obj.value.replace(/,|\s/g,''))){
		alert('����������');
		obj.value="";
	}
	if(obj.value<0){
		alert('�벻Ҫ���븺��');
		obj.value="";
	}
	var detailRows=$("#pronumber").val();
	var quote=0;
	for(var i=1;i<=detailRows;i++){
		thisAmount=$('#amount'+i).val();
		thisPrice=$('#price'+i+"_v").val();
		if(thisAmount!=undefined&&thisAmount!=""&thisPrice!=undefined&&thisPrice!=""){
			quote=accAdd(quote,accMul(thisAmount,thisPrice,6),6);
			quote = accAdd(Number(quote),0.000001,6);
		}
	}

	quote = accMul(quote,1,2);
	$("#quote").val(quote);
	var quotes=moneyFormat2(quote);
	$("#quotes").val(quotes);
}

//����ÿһ�����ϵĺ�˰����
function setTaxPrice(obj,j){
	if(isNaN(obj.value.replace(/,|\s/g,''))){
		alert('����������');
		obj.value="";
	}
	if(obj.value<0){
		alert('�벻Ҫ���븺��');
		obj.value="";
	}
	var taxPrice=$("#"+j).val();
	$.each($(':input[class="txtshort  price'+j+'"]'),function(i,n){
			$(this).val(taxPrice);
	});
	var taxPriceView=$("#"+j+"_v").val();
	$.each($(':input[class="txtshort formatMoneySix priceView'+j+'"]'),function(i,n){
			$(this).val(taxPriceView);
	});
}

//�������۵������ù�Ӧ�̵ı����ܽ��
function setParentQuote(){
		var quoteId=$("#quoteId").val();
		parent.document.getElementById(quoteId).value=document.getElementById("quotes").value;  //���ù�Ӧ�����ҳ���ܽ���ֵ
		parent.document.getElementById(quoteId+'save').value=document.getElementById("quote").value;  //���ù�Ӧ�����ҳ���ܽ���ֵ
		if(parent.document.getElementById('suppId').value==document.getElementById("suppId").value){
			parent.document.getElementById('allMoney').value=document.getElementById("quote").value;
			parent.document.getElementById('allMoneyView').value=document.getElementById("quotes").value;
			parent.document.getElementById('dateHope').value=document.getElementById("arrivalDate").value;
			var paymentCondition=document.getElementById("paymentCondition").value;
			if(paymentCondition=='YFK'){
              //  if(parent.document.getElementById('payRatio').value!=document.getElementById("payRatio").value&&parent.document.getElementById('isNeedPayapply').checked==true){
                if(parent.document.getElementById('isNeedPayapply').checked==true){
                    parent.document.getElementById('isNeedPayapply').checked=false;
                    parent.window.showPayapplyInfo(parent.document.getElementById('isNeedPayapply'));
                }
				parent.document.getElementById('paymentCondition').value=document.getElementById("paymentCondition").value;
				parent.document.getElementById('payRatio').value=document.getElementById("payRatio").value;
				parent.document.getElementById('paymentConditionName').value=getDataByCode(document.getElementById("paymentCondition").value);
				parent.document.getElementById('payapply').style.display='inline';
				parent.document.getElementById('payRatio').style.display='inline';
			}else{
				parent.document.getElementById('paymentCondition').value=document.getElementById("paymentCondition").value;
				parent.document.getElementById('payRatio').value='none';
				parent.document.getElementById('paymentConditionName').value=getDataByCode(document.getElementById("paymentCondition").value);
				parent.document.getElementById('payapply').style.display='none';
				parent.document.getElementById('isNeedPayapply').checked=false;
				parent.document.getElementById('payablesapply').style.display='none';
				parent.document.getElementById('payRatio').style.display='none';
			}
		}
		//���ݻ��ʼ��㱾��
		window.parent.frames.conversion();
		alert("����ɹ�!!");
		self.parent.tb_remove();
	}



//ʵ��˰�ʵ�����
$(function(){
	$("#arrivalDate").bind("focusout",function(){
		var arrivalDate=$(this).val();
		$.each($(':input[class^="txtshort deliveryDate"]'),function(i,n){
			$(this).val(arrivalDate);
		})
	});
});


 //����걨������ѡ�񱨼۵�����ʾ�ܱ���
   function readQuote(){
     var quote=$("#quote").val();
	$("#quoteread").append(quote);
   }

   function checkSumbit(){
			var arrivalDate = $("#arrivalDate").val();
			if( arrivalDate==null || arrivalDate=="" ){
				alert("��ѡ�񽻻�����");
				return false;
			}else{
				setParentQuote();
			}
		}