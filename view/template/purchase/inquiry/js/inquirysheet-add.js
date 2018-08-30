//对金额的输入进行验证，并计算报价总金额
function countDetail(obj){
	if(isNaN(obj.value.replace(/,|\s/g,''))){
		alert('请输入数字');
		obj.value="";
	}
	if(obj.value<0){
		alert('请不要输入负数');
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

//设置每一条物料的含税单价
function setTaxPrice(obj,j){
	if(isNaN(obj.value.replace(/,|\s/g,''))){
		alert('请输入数字');
		obj.value="";
	}
	if(obj.value<0){
		alert('请不要输入负数');
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

//新增报价单后，设置供应商的报价总金额
function setParentQuote(){
		alert("保存成功!!");
		var quoteId=$("#quoteId").val();
		parent.document.getElementById(quoteId).value=document.getElementById("quotes").value;  //设置供应商添加页面总金额的值
		self.parent.tb_remove();
	}

//实现日期的联动
$(function(){
	$("#taxRate").bind("change",function(){
		var taxRate=$(this).val();
		//以下代码在1.4.2中报错
//		$.each($(':select[class^="taxRate"]'),function(i,n){
//			$(this).val(taxRate);
//		})
		$("select option[value='"+taxRate+"']").attr("selected", true);
;

	});
});

//实现税率的联动
$(function(){
	$("#arrivalDate").bind("focusout",function(){
		var arrivalDate=$(this).val();
		$.each($(':input[class^="txtshort deliveryDate"]'),function(i,n){
			$(this).val(arrivalDate);
		})
	});
});

//生成选择供应商单选
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

//对询价单的询价设备数量进行验证，最大不能超过采购任务的设备的最大可申请数量
function myload(){
	$(".amountAll").bind("change",function(){
		var thisVal = parseFloat( $(this).val() );
		var nextVal = parseFloat( $(this).next().val() );
		if(thisVal>nextVal){
			alert("请不要超过最大可询价数量 "+nextVal);
			$(this).attr("value",nextVal);
		}else
		if(thisVal<1){
			alert("请不要输入0或负数");
			$(this).attr("value",nextVal);
		}
		else if($(this).val()==""){
			alert("询价数量不能为空");
			$(this).attr("value",nextVal);
		}
	});
}

//添加询价单时，返回到执行中上的采购任务
function backToTask(){
	var type=$("#back").val();
	if(type==""){
		location="index1.php?model=purchase_task_basic&action=taskMyList";
	}else{
		location="index1.php?model=purchase_task_basic&action=executionList";
	}
}

//删除条目
function mydel(obj, mytable) {
	if (confirm('确定要删除该行？')) {
		var rowNo = obj.parentNode.parentNode.rowIndex*1;
		var mytable = document.getElementById(mytable);
		mytable.deleteRow(rowNo);
		var myrows = mytable.rows.length;
		for (i = 2; i < myrows; i++) {
			mytable.rows[i].childNodes[0].innerHTML = i-1;
		}
	}
}

//判断是否已选择供应商
  function checkSelect(){
	   	var suppName=$("#suppName").val();
	   	if(suppName==''){
	   		alert("请指定供应商");
	   		return false;
	   	}
   }

 //添加完报单后，再选择报价单，显示总报价
   function readQuote(){
     var quote=$("#quote").val();
	$("#quoteread").append(quote);
   }