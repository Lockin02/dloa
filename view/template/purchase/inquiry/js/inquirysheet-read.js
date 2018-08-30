$(document).ready(function() {
	var suppNumb=$("#suppNumb").val();
	switch(suppNumb){
		case '0':$(".supHtml1").hide();$(".supHtml2").hide();$(".supHtml3").hide();break;
		case '1':$(".supHtml2").hide();$(".supHtml3").hide();break;
		case '2':$(".supHtml3").hide();break;
		case '3':;break;
	}
	// 查看询价单时，获取供应商信息
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
				//生成选择供应商单选
				if(v){
				var radio=$("<input type='radio' id='assignSuppId"+v+"' name='inquirysheet[suppId]'>").val(v).attr('title',name);
					$("#suppRadios").append(radio);
						radio.after("<span id='p"+i+"'>"+name+"</span>");
						radio.click(function(){
						$("#suppName").val($(this).attr('title'));
//						alert($("#form1").html());
						var parentId=$("#parentId").val();    //采购询价id
						var suppName=	$("#suppName").val(); //供应商名称
						var suppId=$(this).val();			//供应商ID
						var amaldarId=$("#amaldarId").val();  //指定人ID
						var amaldarName=$("#amaldarName").val();  //指定人名称
						var amaldarDate=$("#amaldarDate").val();  //指定日期
						var amaldarRemark=$("#amaldarRemark").val();  //备注
						var appendHtml=' <input type="hidden" id="parentId" name="inquirysheet[id]" value="'+parentId+'"/>'+' <input type="hidden" id="suppId" name="inquirysheet[suppId]" value="'+suppId+'"/>'+' <input type="hidden" id="suppName" name="inquirysheet[suppName]" value="'+suppName+'"/>'+
											' <input type="hidden" id="amaldarName" name="inquirysheet[amaldarName]" value="'+amaldarName+'"/>'+' <input type="hidden" id="amaldarId" name="inquirysheet[amaldarId]" value="'+amaldarId+'"/>'+
											'<input type="hidden" id="amaldarDate" name="inquirysheet[amaldarDate]" value="'+amaldarDate+'"/>'+'<input type="hidden" id="amaldarRemark" name="inquirysheet[amaldarRemark]" value="'+amaldarRemark+'"/>';
						if($(window.parent.document.getElementById("appendHtml")).html()!=""){   //重新选择刚先把前一次追加的内容清空
							$(window.parent.document.getElementById("appendHtml")).html("");
						}
						$(window.parent.document.getElementById("appendHtml")).append(appendHtml);
//						$("#suppId").val(v);
					});
				}
			}
		}

		$(window.parent.document.getElementById("sub")).bind("click",function(){    //审批提交时，判断是否指定了供应商
			var result=$(window.parent.document.getElementById('form1')).find("input[name='result'][checked]").val();
			var isLast=$("#isLast").val();
			if(isLast!=""){    //判断是否为最后一个审批者,不为空则为最后一个
				if(result=="ok"&&!($(window.parent.document.getElementById("suppId")).length>0)){
					alert("请指定一个供应商。");
					return false;
				}
			}
		});
		var quoteArr=new Array();
		for(j=1;j<4;j++){    //没有供应商则隐藏查看报价详细图片
			if($("#suppId"+j).val()==""){
				$("#supp"+j).hide();
			}else{
				quoteArr[j-1]=$("#quote"+j).val();
			}
		}
		Array.prototype.min = function(){   //最小值
		 return Math.min.apply({},this)
		}
		for(j=1;j<4;j++){//最小总金额的标红
			if($("#quote"+j).val()==quoteArr.min()&&quoteArr.min()!='infinity'){
				$("#quoteAll"+j).html("<font color='red'>"+moneyFormat2(quoteArr.min())+"</font>");
				var suppId=$("#supplierId"+j).val();
				//指定供应商radio，最小总金额供应商标红
				$("#p"+j).remove();
				$("#assignSuppId"+suppId).after("<font color='red'>"+$("#supplierName"+j).val()+"</font>")
			}
		}
	});
});


	/*审批询价单时，添加备注到父窗口*/
	function addRemark(){
		var amaldarRemark=$("#amaldarRemark").val();  //备注
		if($(window.parent.document.getElementById("amaldarRemark")).length>0){
			$(window.parent.document.getElementById("amaldarRemark")).val(amaldarRemark);
		}else{
			var appendRemarkHtml='<input type="hidden" id="amaldarRemark" name="inquirysheet[amaldarRemark]" value="'+amaldarRemark+'"/>';
			$(window.parent.document.getElementById("appendHtml")).append(appendRemarkHtml);
		}

	}


/**跳到报单添加页面*/
function quoteRead(index){
	var inquiryId=$("#parentId").val();
	var parentId=$("#suppId"+index).val();
	var supplierName=$("#supplierName"+index).val();
	var supplierId=$("#supplierId"+index).val();
	var quoteId= document.getElementById("quote"+index).id;
	var quote=$("#quote"+index).val();
	//跳转到报价单添加页面
	if(parentId!=""){
			showThickboxWin("index1.php?model=purchase_inquiry_inquirysupp&action=readQuotation&parentId="+parentId+"&inquiryId="+inquiryId+"&supplierName="+supplierName+"&supplierId="+supplierId
			 +"&quoteId="+quoteId+"&quote="+quote+"&placeValuesBefore&TB_iframe=true&modal=false&height=380&width=930");
	}
	else{
	  alert("没有相应的报价单！")
	}
}