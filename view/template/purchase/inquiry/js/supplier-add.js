$(function() {

	// ��Ӧ��
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
								alert("�ù�Ӧ����ѡ��������ѡ��Ӧ�̣�");
								return false;
							}else{
								$("#supplierName" + i).val(data.suppName);
								$("#supplierId" + i).val(data.id);
								$("#busiCode" + i).val(data.busiCode);
								$("#products" + i).val(data.products);
								var suppId=$("#suppId"+i).val();
								//���湩Ӧ����Ϣ
								if(suppId==""){
									//����ûѡ��Ӧ�̣���������Ӧ��
									$.post("?model=purchase_inquiry_inquirysheet&action=addSupp",{
										supplierName:data.suppName,
										supplierId:data.id,
										supplierPro:data.products,
										parentId:$("#parentId").val()
										},function(id,status){
											var ids=$("#suppId"+i).val(id);    //����ѯ�۵���Ӧ�̵�ID
									});
								}else{
									//����ѡ��Ӧ�̣�����ת���༭����
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

//������һ�������ص�ѯ�۵��Ļ�����Ϣ���ҳ��,���ɱ༭
function backToPre(){
    var id=document.getElementById('parentId').value;
    var type=$("#back").val();
    location="?model=purchase_inquiry_inquirysheet&action=toEditAdd&id="+id+"&type="+type;
}

/**�����������ҳ��*/
function quote(index){
	var inquiryId=$("#parentId").val();
	var parentId=$("#suppId"+index).val();
	var supplierName=$("#supplierName"+index).val();
	var supplierId=$("#supplierId"+index).val();
	var quoteId= document.getElementById("quote"+index).id;
	var quote=$("#quote"+index).val();

	//��ת�����۵����ҳ��
	if(parentId!=""&&quote==""){
			 showThickboxWin("index1.php?model=purchase_inquiry_inquirysupp&action=quotationInit&parentId="+parentId+"&inquiryId="+inquiryId+"&supplierName="+supplierName+"&supplierId="+supplierId
			 +"&quoteId="+quoteId+"&placeValuesBefore&TB_iframe=true&modal=false&height=380&width=930");
	}else if(quote!=""){
			showThickboxWin("index1.php?model=purchase_inquiry_inquirysupp&action=toQuoteEdit&parentId="+parentId+"&inquiryId="+inquiryId+"&supplierName="+supplierName+"&supplierId="+supplierId
			 +"&quoteId="+quoteId+"&quote="+quote+"&placeValuesBefore&TB_iframe=true&modal=false&height=380&width=930");
	}
	else{
	  alert("����ѡ��Ӧ�̣�")
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
	if(parentId!=""&&quote==""){
			 showThickboxWin("index1.php?model=purchase_inquiry_inquirysupp&action=readQuotation&parentId="+parentId+"&inquiryId="+inquiryId+"&supplierName="+supplierName+"&supplierId="+supplierId
			 +"&quoteId="+quoteId+"&placeValuesBefore&TB_iframe=true&modal=false&height=380&width=930");
	}else if(quote!=""){
			showThickboxWin("index1.php?model=purchase_inquiry_inquirysupp&action=readQuotation&parentId="+parentId+"&inquiryId="+inquiryId+"&supplierName="+supplierName+"&supplierId="+supplierId
			 +"&quoteId="+quoteId+"&quote="+quote+"&placeValuesBefore&TB_iframe=true&modal=false&height=380&width=930");
	}
	else{
	  alert("û����Ӧ�ı��۵���")
	}
}

//�ύѯ�۵�
function subInquiry(){
	var inquiryId=$("#parentId").val();
	if(confirm("ȷ��Ҫ�ύ��")){
		location = "?model=purchase_inquiry_inquirysheet&action=putInquirysheet&parentId="+inquiryId;
	}
}

//�ύ����
function subForm(){
	var inquiryId=$("#parentId").val();
	var type=$("#back").val();
	if(type==""){
		location = 'controller/purchase/inquiry/ewf_index_task.php?actTo=ewfSelect&billId='+ inquiryId
							+ '&examCode=oa_purch_inquiry&formName=�ɹ�ѯ�۵�����';
	}else{
		location = 'controller/purchase/inquiry/ewf_index_task2.php?actTo=ewfSelect&billId='+ inquiryId
							+ '&examCode=oa_purch_inquiry&formName=�ɹ�ѯ�۵�����';
	}
}

//��ӻ��޸�ѯ�۵�ʱ����չ�Ӧ����Ϣ
function delSupp(index){
	if(confirm("ȷ��Ҫ��գ�")){
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




