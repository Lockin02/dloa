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
		}
	}
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
		alert("����ɹ�!!");
		var quoteId=$("#quoteId").val();
		parent.document.getElementById(quoteId).value=document.getElementById("quotes").value;  //���ù�Ӧ�����ҳ���ܽ���ֵ
		self.parent.tb_remove();
	}

//ʵ�����ڵ�����
$(function(){
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

//ʵ��˰�ʵ�����
$(function(){
	$("#arrivalDate").bind("focusout",function(){
		var arrivalDate=$(this).val();
		$.each($(':input[class^="txtshort deliveryDate"]'),function(i,n){
			$(this).val(arrivalDate);
		})
	});
});

//����ѡ��Ӧ�̵�ѡ
$(function() {
	for(var i=1;i<4;i++){
		var v=$("#supplierId"+i).val();
		var name=$("#supplierName"+i).val();
		if(v){
			var radio=$("<input type='radio' name='inquirysheet[suppId]'>").val(v).attr('title',name);
			$("#suppRadios").append(radio);
			radio.after(name);
			radio.click(function(){

				$("#suppName").val($(this).attr('title'));

			});

		}

	}
});

//��ѯ�۵���ѯ���豸����������֤������ܳ����ɹ�������豸��������������
function myload(){
	$(".amountAll").bind("change",function(){
		var thisVal = parseFloat( $(this).val() );
		var nextVal = parseFloat( $(this).next().val() );
		if(thisVal>nextVal){
			alert("�벻Ҫ��������ѯ������ "+nextVal);
			$(this).attr("value",nextVal);
		}else
		if(thisVal<1){
			alert("�벻Ҫ����0����");
			$(this).attr("value",nextVal);
		}
		else if($(this).val()==""){
			alert("ѯ����������Ϊ��");
			$(this).attr("value",nextVal);
		}
	});
}

//���ѯ�۵�ʱ�����ص�ִ�����ϵĲɹ�����
function backToTask(){
	var type=$("#back").val();
	if(type==""){
		location="index1.php?model=purchase_task_basic&action=taskMyList";
	}else{
		location="index1.php?model=purchase_task_basic&action=executionList";
	}
}

//ɾ����Ŀ
function mydel(obj, mytable) {
	if (confirm('ȷ��Ҫɾ�����У�')) {
		var rowNo = obj.parentNode.parentNode.rowIndex*1;
		var mytable = document.getElementById(mytable);
		mytable.deleteRow(rowNo);
		var myrows = mytable.rows.length;
		for (i = 2; i < myrows; i++) {
			mytable.rows[i].childNodes[0].innerHTML = i-1;
		}
	}
}

//�ж��Ƿ���ѡ��Ӧ��
  function checkSelect(){
	   	var suppName=$("#suppName").val();
	   	if(suppName==''){
	   		alert("��ָ����Ӧ��");
	   		return false;
	   	}
   }

 //����걨������ѡ�񱨼۵�����ʾ�ܱ���
   function readQuote(){
     var quote=$("#quote").val();
	$("#quoteread").append(quote);
   }