$(function() {

	// 供应商
	for (var i = 1; i <= 3; i++) {
		$("#supplierName" + i).yxcombogrid_supplier({
			hiddenId : 'supplierId' + i,
			gridOptions : {
				event : {
					'row_dblclick' : function(i) {
						return function(e, row, data) {
								$("#supplierName" + i).val("");
								$("#supplierId" + i).val("");
								$("#busiCode" + i).val("");
								$("#products" + i).val("");
							if(data.id==$("#supplierId1").val()||data.id==$("#supplierId2").val()||data.id==$("#supplierId3").val()){
								alert("该供应商已选择，请重新选择供应商！");
								return false;
							}else{
								$("#supplierName" + i).val(data.suppName);
								$("#supplierId" + i).val(data.id);
								$("#busiCode" + i).val(data.busiCode);
								$("#products" + i).val(data.products);
								var suppId=$("#suppId"+i).val();
								//保存供应商信息
								if(suppId==""){
									//若还没选择供应商，则新增供应商
									$.post("?model=purchase_inquiry_inquirysheet&action=addSupp",{
										supplierName:data.suppName,
										supplierId:data.id,
										supplierPro:data.products,
										parentId:$("#parentId").val()
										},function(id,status){
											var ids=$("#suppId"+i).val(id);    //返回询价单供应商的ID
									});
								}else{
									//若已选择供应商，则跳转到编辑方法
									$.post("?model=purchase_inquiry_inquirysheet&action=suppAdd",{
										suppIds:suppId,
										supplierName:data.suppName,
										supplierId:data.id,
										supplierPro:data.products,
										parentId:$("#parentId").val()
										},function(id,status){
									});
								}
							}

						}
					}(i)
				}
			}
		});
	}
});

//返回上一步，返回到询价单的基本信息添加页面,并可编辑
function backToPre(){
    var id=document.getElementById('parentId').value;
    var type=$("#back").val();
    location="?model=purchase_inquiry_inquirysheet&action=toEditAdd&id="+id+"&type="+type;
}

/**跳到报单添加页面*/
function quote(index){
	var inquiryId=$("#parentId").val();
	var parentId=$("#suppId"+index).val();
	var supplierName=$("#supplierName"+index).val();
	var supplierId=$("#supplierId"+index).val();
	var quoteId= document.getElementById("quote"+index).id;
	var quote=$("#quote"+index).val();

	//跳转到报价单添加页面
	if(parentId!=""&&quote==""){
			 showThickboxWin("index1.php?model=purchase_inquiry_inquirysupp&action=quotationInit&parentId="+parentId+"&inquiryId="+inquiryId+"&supplierName="+supplierName+"&supplierId="+supplierId
			 +"&quoteId="+quoteId+"&placeValuesBefore&TB_iframe=true&modal=false&height=380&width=930");
	}else if(quote!=""){
			showThickboxWin("index1.php?model=purchase_inquiry_inquirysupp&action=toQuoteEdit&parentId="+parentId+"&inquiryId="+inquiryId+"&supplierName="+supplierName+"&supplierId="+supplierId
			 +"&quoteId="+quoteId+"&quote="+quote+"&placeValuesBefore&TB_iframe=true&modal=false&height=380&width=930");
	}
	else{
	  alert("请先选择供应商！")
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
	if(parentId!=""&&quote==""){
			 showThickboxWin("index1.php?model=purchase_inquiry_inquirysupp&action=readQuotation&parentId="+parentId+"&inquiryId="+inquiryId+"&supplierName="+supplierName+"&supplierId="+supplierId
			 +"&quoteId="+quoteId+"&placeValuesBefore&TB_iframe=true&modal=false&height=380&width=930");
	}else if(quote!=""){
			showThickboxWin("index1.php?model=purchase_inquiry_inquirysupp&action=readQuotation&parentId="+parentId+"&inquiryId="+inquiryId+"&supplierName="+supplierName+"&supplierId="+supplierId
			 +"&quoteId="+quoteId+"&quote="+quote+"&placeValuesBefore&TB_iframe=true&modal=false&height=380&width=930");
	}
	else{
	  alert("没有相应的报价单！")
	}
}

//提交询价单
function subInquiry(){
	var inquiryId=$("#parentId").val();
	if(confirm("确定要提交吗？")){
		location = "?model=purchase_inquiry_inquirysheet&action=putInquirysheet&parentId="+inquiryId;
	}
}

//提交审批
function subForm(){
	var inquiryId=$("#parentId").val();
	var type=$("#back").val();
	if(type==""){
		location = 'controller/purchase/inquiry/ewf_index_task.php?actTo=ewfSelect&billId='+ inquiryId
							+ '&examCode=oa_purch_inquiry&formName=采购询价单审批';
	}else{
		location = 'controller/purchase/inquiry/ewf_index_task2.php?actTo=ewfSelect&billId='+ inquiryId
							+ '&examCode=oa_purch_inquiry&formName=采购询价单审批';
	}
}

//添加或修改询价单时，清空供应商信息
function delSupp(index){
	if(confirm("确认要清空？")){
		var suppId=$("#suppId"+index).val();
		$.post("?model=purchase_inquiry_inquirysupp&action=del&id="+suppId,{
			id:suppId
		},function(data,state){
		   if(state){
		      $("#supplierName"+index).val("");
		      $("#supplierId"+index).val("");
		      $("#products"+index).val("");
		      $("#quote"+index).val("");
		      $("#suppId"+index).val("");
		   }
		});
	}
}




