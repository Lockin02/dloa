//���㸶��
function countAll(){
	var invnumber =$('#coutNumb').val();
	var thisAmount = 0;
	var allAmount = 0;
	for(var i = 1;i<= invnumber ; i++ ){
		if($("#money"+ i).length == 0) continue;
		thisMoney = $("#money"+i).val()*1;
		if( thisMoney != 0 || thisMoney != ""){
			allAmount = accAdd(allAmount,thisMoney,2);
		}
	}
	$("#payMoney").val(allAmount);

	payAmountView = moneyFormat2(allAmount);
	$("#payMoney_v").val(moneyFormat2(payAmountView));
	$("#payMoneyView").val(moneyFormat2(payAmountView));
}


$(function() {

    $("#billingType").attr("disabled","disabled");
	 			//���ֳ�ʼ��
			var currencyCodeObj = $("#currencyCode");
			if(currencyCodeObj.length > 0){
				// ���ұ�
				$("#currency").yxcombogrid_currency({
					hiddenId : 'currencyCode',
					valueCol : 'currencyCode',
					isFocusoutCheck : false,
					gridOptions : {
						showcheckbox : false,
						event : {
							'row_dblclick' : function(e, row, data) {
								$("#rate").val(data.rate);
								// ���ݻ��ʼ��㱾��
								conversion();
							}
						}
					}
				});
			}

			var paymentCondition=$("#paymentCondition").val();
				if(paymentCondition=="YFK"){
					$("#payRatio").show();
				}else{
					$("#payRatio").hide();
				}
		// �޸�ѯ�۵�ʱ����ȡ��Ӧ����Ϣ
	var parentId = $("#id").val();
	$.post("?model=purchase_contract_applysupp&action=getSupp", {
		parentId : parentId
	}, function(data) {
//		alert(data)
        var o = eval("(" + data + ")");
		for (i = 1; i < 4; i++) {
			if (o[i - 1]) {
				$("#supplierName" + i).val(o[i - 1].suppName);
				$("#supplierId" + i).val(o[i - 1].suppId);
				$("#products"+i).val(o[i-1].suppTel);
				var quotes=moneyFormat2(o[i-1].quote);
				$("#quote"+i).val(quotes);
				$("#quote"+i+"save").val(o[i-1].quote);
				$("#suppId"+i).val(o[i-1].id);

				var radio=$("<input type='radio' id='"+i+"' name='suppCheck'>").val(o[i - 1].suppId).attr('title',o[i - 1].suppName);
				$("#suppRadios").append(radio);
				radio.after("<span id='assignSupp"+i+"'>"+o[i - 1].suppName+"</span>");
				var selectSuppId=$('#suppId').val();
				if(selectSuppId==o[i - 1].suppId){
					$("#"+i).attr("checked","checked");
				}
				radio.click(function(){
					j=$(this).attr('id');
					if($("#quote"+j).val()==""){
						$(this).attr("checked",false);
						alert("������д�ù�Ӧ�̵ı��۵�");
					}else{
							var suppLevel='';
							$.ajax({
								type : 'POST',
								url : '?model=supplierManage_formal_flibrary&action=getSuppInfo',
								data : {
									id:$(this).val()
								},
								async : false,
								success : function(data) {
									if (data) {
    									var o = eval("(" + data + ")");
										suppLevel=$.trim(o.suppGrade);
									}
								}
							});
							if(suppLevel=="A"||suppLevel=="B"||suppLevel=="C"){
								$("#suppName").val($(this).attr('title'));//��Ӧ������
								$("#suppId").val($(this).val());			//��Ӧ��ID
								$("#allMoney").val($("#quote"+j+"save").val());			//�ܽ��
								$("#allMoneyView").val($("#quote"+j).val());			//�ܽ��(��ʾ)
								conversion();

								$('#suppAccount1').yxcombogrid_suppAccount("remove");
								$("#suppAccount1").val("");
								$("#suppBankName").val("");		//�����е������ֵ�ת�������ĺ��滻��Ӧ��ֵ������ҳ����ʾ
								$("#suppBank").val("");
								$("#isNeedPayapply").attr("checked",false);
								$("#payapply").hide();
								$("#payablesapply").hide();

								//��Ӧ����Ϣ
								$.post(
										"?model=supplierManage_formal_flibrary&action=getSuppInfo",
										{
											id : $("#suppId").val()
										}, function(suppData) {
											if(suppData){
		    									var o = eval("(" + suppData + ")");
												$("#suppAddress").val(o.address);
												$("#suppTel").val(o.plane);
											}
										});
								//���۹�Ӧ����Ϣ
								$.post(
										"?model=purchase_contract_applysupp&action=getSuppInfo",
										{
											id : $("#suppId"+j).val()
										}, function(applySupp) {
											if(applySupp){
		    									var obj = eval("(" + applySupp + ")");
		    									$("#paymentCondition").val(obj.paymentCondition);
		    									$("#paymentConditionName").val(obj.paymentConditionName);
		    									$("#dateHope").val(obj.arrivalDate);
												if(obj.paymentCondition=="YFK"){
													$("#payRatio").show();
													$("#payapply").show();
													$("#payRatio").val(obj.payRatio);
												}else{
													$("#payRatio").val('');
													$("#payRatio").hide();
													$("#payapply").hide();
													$("#payablesapply").hide();
												}
                                                $("#billingType").removeAttr("disabled");
                                                $('select[name="contract[billingType]"] option').each(function() {
                                                    if(parseInt( $(this).attr("e1")) == parseInt(obj.taxRate) ){
                                                        $(this).attr("selected","selected'");
                                                    }
                                                });
                                                $("#billingType").attr("disabled","disabled");
		//    									$('select[name="contract[payRatio]"] option').each(function() {
		//											if( $(this).val() == obj.payRatio ){
		//												$(this).attr("selected","selected'");
		//											}
		//										});
											}
								});

								//�����Ӧ�̴�������Ϣ����Ĭ�ϴ�����һ����Ϣ
								$.post(
										"?model=supplierManage_formal_bankinfo&action=getBankInfo",
										{
											suppId : $("#suppId").val()
										}, function(bank) {
											if(bank){
		    									var o = eval("(" + bank + ")");
												$("#suppAccount1").val(o.accountNum);
												$("#suppBankName").val(o.bankName);
											}
										});

								//�����Ӧ�̴�����ϵ����Ϣ����Ĭ�ϴ�����һ����Ϣ
								$.post(
										"?model=supplierManage_formal_sfcontact&action=getLinkmanInfo",
										{
											suppId :$("#suppId").val()
										}, function(linkman) {
											if(linkman){
		    									var o = eval("(" + linkman + ")");
												$("#suppContactMan").val(o.name);
												$("#contactManPhone").val(o.mobile1);
											}else{
												$("#suppContactMan").val('');
												$("#contactManPhone").val('');
											}
									});

								$('#suppAccount1').yxcombogrid_suppAccount({
									isFocusoutCheck:false,
									gridOptions : {
										reload : true,
										showcheckbox : false,
										// ���ݹ�Ӧ��ID��ѡ�еĿ������У����˳���Ӧ�������ʺ�
										param : {
											suppId : $("#suppId").val()
										},
										event : {
											'row_dblclick' : function(e, row, data) {
												var getGrid = function() {
													return $("#suppAccount1").yxcombogrid_suppAccount("getGrid");
												}
												var getGridOptions = function() {
													return $("#suppAccount1").yxcombogrid_suppAccount("getGridOptions");
												}
												if (!$('#supplierId').val()) {
												} else {
													if (getGrid().reload) {
														getGridOptions().param = {
															suppId : $('#supplierId').val()
														};
														getGrid().reload();
													} else {
														getGridOptions().param = {
															suppId : $('#supplierId').val()
														}
													}
												}
												$("#suppAccount1").val(data.accountNum);
												$("#suppBankName").val(data.bankName);
												$("#suppBank").val(data.depositbank);				//���е������ֵ���룬���������ڱ�������ʱд�����ݿ�
											}
										}
									}
								});
							}else{
								$(this).attr("checked",false);
								alert('�ù�Ӧ��Ϊ�Ǻϸ�Ӧ��,�����´ﶩ��');
							}
					}
				});

			}
		}
	});

	$('[name="contract[signStatus]"]:radio').each(function() {
					if( $(this).val() == $("#signStatus").val() ){
						$(this).attr("checked","true");
					}
	});

	var isApplyPay=$("#isApplyPay").val();
	if(isApplyPay==1){
		$("#isNeedPayapply").attr('checked','checked');
		$("#payapply").show();
		$("#payablesapply").show();
		var orderId=$("#id").val();

		 $.ajax({
			    type: "POST",
			    url: "?model=purchase_contract_purchasecontract&action=getPayEditStr",
			    data: {
			    	id:$("#id").val()
			    	},
			    async: false,
			    success: function(data){
					if(data){
						$("#invbody").html(data);
						var invnumber = document.getElementById('invbody').rows.length;
						$('#coutNumb').val(invnumber);
					}
			}
		});
		countAll();

	}else if($("#id").val()!=''&&$("#paymentCondition").val()=='YFK'){
		$("#payapply").show();
	}


		$('#suppAccount1').yxcombogrid_suppAccount({
		isFocusoutCheck:false,
		gridOptions : {
			reload : true,
			showcheckbox : false,
			// ���ݹ�Ӧ��ID��ѡ�еĿ������У����˳���Ӧ�������ʺ�
			param : {
				suppId : $("#suppId").val()
			},
			event : {
				'row_dblclick' : function(e, row, data) {
					var getGrid = function() {
						return $("#suppAccount1").yxcombogrid_suppAccount("getGrid");
					}
					var getGridOptions = function() {
						return $("#suppAccount1").yxcombogrid_suppAccount("getGridOptions");
					}
					if (!$('#suppId').val()) {
					} else {
						if (getGrid().reload) {
							getGridOptions().param = {
								suppId : $('#suppId').val()
							};
							getGrid().reload();
						} else {
							getGridOptions().param = {
								suppId : $('#suppId').val()
							}
						}
					}
					$("#suppAccount1").val(data.accountNum);
					$("#suppBankName").val(data.depositbank_name);		//�����е������ֵ�ת�������ĺ��滻��Ӧ��ֵ������ҳ����ʾ
					$("#suppBank").val(data.depositbank);				//���е������ֵ���룬���������ڱ�������ʱд�����ݿ�
				}
			}
		}
	});


		validate({
				"suppName" : {
					required : true
				},
				"allMoneyView" : {
					required : true
				},
				"selectRemark" : {
					required : true
				},
				"currency" : {
					required : true
				}
 		});

 		var selectRemark=$("#selectRemark").val();
 		if (selectRemark==''){
 			$("#selectRemark").val('������ѡ��ù�Ӧ�̵�����');
 			$("#selectRemark").attr("color",'#AAAAAA');
 			window.document.getElementById("selectRemark").style.color='#AAAAAA';
 		}

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
								$("#suppLevel" + i).val(data.suppGrade);
								var suppId=$("#suppId"+i).val();
								//���湩Ӧ����Ϣ
								if(suppId==""){
									//����ûѡ��Ӧ�̣���������Ӧ��
									$.post("?model=purchase_contract_purchasecontract&action=addSupp",{
										supplierName:data.suppName,
										supplierId:data.id,
										supplierPro:data.products,
										parentId:$("#id").val(),
										parentCode:$("#hwapplyNumb").val()
										},function(id,status){
											var ids=$("#suppId"+i).val(id);    //���ض�����Ӧ�̵�ID

											var radio=$("<input type='radio' id='"+i+"' name='suppCheck'>").val(data.id).attr('title',data.suppName);
											$("#suppRadios").append(radio);
											radio.after("<span id='assignSupp"+i+"'>"+data.suppName+"</span>");
											radio.click(function(){
												j=$(this).attr('id');
												var suppLevel=$.trim($("#suppLevel"+j).val());
												if($("#quote"+j).val()==""){
													$(this).attr("checked",false);
													alert("������д�ù�Ӧ�̵ı��۵�");
												}else if(suppLevel=="A"||suppLevel=="B"||suppLevel=="C"){
													$("#suppName").val($(this).attr('title'));//��Ӧ������
													$("#suppId").val($(this).val());			//��Ӧ��ID
													$("#allMoney").val($("#quote"+j+"save").val());			//�ܽ��
													$("#allMoneyView").val($("#quote"+j).val());			//�ܽ��(��ʾ)
													conversion();

													$('#suppAccount1').yxcombogrid_suppAccount("remove");
													$("#suppAccount1").val("");
													$("#suppBankName").val("");		//�����е������ֵ�ת�������ĺ��滻��Ӧ��ֵ������ҳ����ʾ
													$("#suppBank").val("");

													$("#isNeedPayapply").attr("checked",false);
													$("#payapply").hide();
													$("#payablesapply").hide();

													//��Ӧ����Ϣ
													$.post(
															"?model=supplierManage_formal_flibrary&action=getSuppInfo",
															{
																id : $("#suppId").val()
															}, function(suppData) {
																if(suppData){
						        									var o = eval("(" + suppData + ")");
																	$("#suppAddress").val(o.address);
																	$("#suppTel").val(o.plane);
																}else{
																	$("#suppAddress").val("");
																	$("#suppTel").val("");
																}
															});
													//�����Ӧ�̴�������Ϣ����Ĭ�ϴ�����һ����Ϣ
													$.post(
															"?model=supplierManage_formal_bankinfo&action=getBankInfo",
															{
																suppId : $("#suppId").val()
															}, function(bank) {
																if(bank){
						        									var o = eval("(" + bank + ")");
																	$("#suppAccount1").val(o.accountNum);
																	$("#suppBankName").val(o.bankName);
																}
															});

												//�����Ӧ�̴�����ϵ����Ϣ����Ĭ�ϴ�����һ����Ϣ
												$.post(
														"?model=supplierManage_formal_sfcontact&action=getLinkmanInfo",
														{
															suppId :$("#suppId").val()
														}, function(linkman) {
															if(linkman){
					        									var o = eval("(" + linkman + ")");
																	$("#suppContactMan").val(o.name);
																	$("#contactManPhone").val(o.mobile1);
															}
														});

													//���۹�Ӧ����Ϣ
													$.post(
															"?model=purchase_contract_applysupp&action=getSuppInfo",
															{
																id : $("#suppId"+i).val()
															}, function(applySupp) {
																if(applySupp){
							    									var obj = eval("(" + applySupp + ")");
							    									$("#paymentCondition").val(obj.paymentCondition);
							    									$("#paymentConditionName").val(obj.paymentConditionName);
							    									$("#dateHope").val(obj.arrivalDate);
																	if(obj.paymentCondition=="YFK"){
																		$("#payRatio").show();
																		$("#payapply").show();
																		$("#payRatio").val(obj.payRatio);
																	}else{
																		$("#payRatio").val('');
																		$("#payRatio").hide();
																		$("#payapply").hide();
																		$("#payablesapply").hide();
																	}
							//    									$('select[name="contract[payRatio]"] option').each(function() {
							//											if( $(this).val() == obj.payRatio ){
							//												$(this).attr("selected","selected'");
							//											}
							//										});
																}
													});
													$('#suppAccount1').yxcombogrid_suppAccount({
														isFocusoutCheck:false,
														gridOptions : {
															reload : true,
															showcheckbox : false,
															// ���ݹ�Ӧ��ID��ѡ�еĿ������У����˳���Ӧ�������ʺ�
															param : {
																suppId : $("#suppId").val()
															},
															event : {
																'row_dblclick' : function(e, row, data) {
																	var getGrid = function() {
																		return $("#suppAccount1").yxcombogrid_suppAccount("getGrid");
																	}
																	var getGridOptions = function() {
																		return $("#suppAccount1").yxcombogrid_suppAccount("getGridOptions");
																	}
																	if (!$('#supplierId').val()) {
																	} else {
																		if (getGrid().reload) {
																			getGridOptions().param = {
																				suppId : $('#supplierId').val()
																			};
																			getGrid().reload();
																		} else {
																			getGridOptions().param = {
																				suppId : $('#supplierId').val()
																			}
																		}
																	}
																	$("#suppAccount1").val(data.accountNum);
																	$("#suppBankName").val(data.bankName);
																	$("#suppBank").val(data.depositbank);				//���е������ֵ���룬���������ڱ�������ʱд�����ݿ�
																}
															}
														}
													});

												}else{
													$(this).attr("checked",false);
													alert('�ù�Ӧ��Ϊ�Ǻϸ�Ӧ��,�����´ﶩ��');
												}
											});
									});
								}else{
									//����ѡ��Ӧ�̣�����ת���༭����
									$.post("?model=purchase_contract_purchasecontract&action=suppAdd",{
										suppIds:suppId,
										supplierName:data.suppName,
										supplierId:data.id,
										supplierPro:data.products,
										parentId:$("#id").val()
										},function(id,status){
											var oldSuppId=$("#" + i).val();
											editSupp(oldSuppId,i);
											var ids=$("#suppId"+i).val(id);    //���ض�����Ӧ�̵�ID

											var radio=$("<input type='radio' id='"+i+"' name='suppCheck'>").val(data.id).attr('title',data.suppName);
											$("#suppRadios").append(radio);
											radio.after("<span id='assignSupp"+i+"'>"+data.suppName+"</span>");
											radio.click(function(){
												j=$(this).attr('id');
												var suppLevel=$.trim($("#suppLevel"+j).val());
												if($("#quote"+j).val()==""){
													$(this).attr("checked",false);
													alert("������д�ù�Ӧ�̵ı��۵�");
												}else if(suppLevel=="A"||suppLevel=="B"||suppLevel=="C"){
													$("#suppName").val($(this).attr('title'));//��Ӧ������
													$("#suppId").val($(this).val());			//��Ӧ��ID
													$("#allMoney").val($("#quote"+j+"save").val());			//�ܽ��
													$("#allMoneyView").val($("#quote"+j).val());			//�ܽ��(��ʾ)


													$('#suppAccount1').yxcombogrid_suppAccount("remove");
													$("#suppAccount1").val("");
													$("#suppBankName").val("");		//�����е������ֵ�ת�������ĺ��滻��Ӧ��ֵ������ҳ����ʾ
													$("#suppBank").val("");

													$("#isNeedPayapply").attr("checked",false);
													$("#payapply").hide();
													$("#payablesapply").hide();

													//��Ӧ����Ϣ
													$.post(
															"?model=supplierManage_formal_flibrary&action=getSuppInfo",
															{
																id : $("#suppId").val()
															}, function(suppData) {
																if(suppData){
						        									var o = eval("(" + suppData + ")");
																	$("#suppAddress").val(o.address);
																	$("#suppTel").val(o.plane);
																}
															});
													//���۹�Ӧ����Ϣ
													$.post(
															"?model=purchase_contract_applysupp&action=getSuppInfo",
															{
																id : $("#suppId"+i).val()
															}, function(applySupp) {
																if(applySupp){
							    									var obj = eval("(" + applySupp + ")");
							    									$("#paymentCondition").val(obj.paymentCondition);
							    									$("#paymentConditionName").val(obj.paymentConditionName);
							    									$("#dateHope").val(obj.arrivalDate);
																	if(obj.paymentCondition=="YFK"){
																		$("#payRatio").show();
																		$("#payapply").show();
																		$("#payRatio").val(obj.payRatio);
																	}else{
																		$("#payRatio").val('');
																		$("#payRatio").hide();
																		$("#payapply").hide();
																		$("#payablesapply").hide();
																	}
							//    									$('select[name="contract[payRatio]"] option').each(function() {
							//											if( $(this).val() == obj.payRatio ){
							//												$(this).attr("selected","selected'");
							//											}
							//										});
																}
													});

													//�����Ӧ�̴�������Ϣ����Ĭ�ϴ�����һ����Ϣ
													$.post(
															"?model=supplierManage_formal_bankinfo&action=getBankInfo",
															{
																suppId : $("#suppId").val()
															}, function(bank) {
																if(bank){
						        									var o = eval("(" + bank + ")");
																	$("#suppAccount1").val(o.accountNum);
																	$("#suppBankName").val(o.bankName);
																}
															});

													//�����Ӧ�̴�����ϵ����Ϣ����Ĭ�ϴ�����һ����Ϣ
													$.post(
															"?model=supplierManage_formal_sfcontact&action=getLinkmanInfo",
															{
																suppId :$("#suppId").val()
															}, function(linkman) {
																if(linkman){
						        									var o = eval("(" + linkman + ")");
																	$("#suppContactMan").val(o.name);
																	$("#contactManPhone").val(o.mobile1);
																}
														});

													$('#suppAccount1').yxcombogrid_suppAccount({
														isFocusoutCheck:false,
														gridOptions : {
															reload : true,
															showcheckbox : false,
															// ���ݹ�Ӧ��ID��ѡ�еĿ������У����˳���Ӧ�������ʺ�
															param : {
																suppId : $("#suppId").val()
															},
															event : {
																'row_dblclick' : function(e, row, data) {
																	var getGrid = function() {
																		return $("#suppAccount1").yxcombogrid_suppAccount("getGrid");
																	}
																	var getGridOptions = function() {
																		return $("#suppAccount1").yxcombogrid_suppAccount("getGridOptions");
																	}
																	if (!$('#supplierId').val()) {
																	} else {
																		if (getGrid().reload) {
																			getGridOptions().param = {
																				suppId : $('#supplierId').val()
																			};
																			getGrid().reload();
																		} else {
																			getGridOptions().param = {
																				suppId : $('#supplierId').val()
																			}
																		}
																	}
																	$("#suppAccount1").val(data.accountNum);
																	$("#suppBankName").val(data.bankName);
																	$("#suppBank").val(data.depositbank);				//���е������ֵ���룬���������ڱ�������ʱд�����ݿ�
																}
															}
														}
													});
												}else{
													$(this).attr("checked",false);
													alert('�ù�Ӧ��Ϊ�Ǻϸ�Ӧ��,�����´ﶩ��');
												}
											});
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


/**�����������ҳ��*/
function quote(index){
	var applyId=$("#id").val();
	var parentId=$("#suppId"+index).val();
	var supplierName=$("#supplierName"+index).val();
	var supplierId=$("#supplierId"+index).val();
	var quoteId= document.getElementById("quote"+index).id;
	var quote=$("#quote"+index).val();

	//��ת�����۵����ҳ��
	if(parentId!=""&&quote==""){
			 showThickboxWin("index1.php?model=purchase_contract_applysupp&action=toAdd&parentId="+parentId+"&applyId="+applyId+"&supplierName="+supplierName+"&supplierId="+supplierId
			 +"&quoteId="+quoteId+"&placeValuesBefore&TB_iframe=true&modal=false&height=380&width=930");
	}else if(quote!=""){
			showThickboxWin("index1.php?model=purchase_contract_applysupp&action=toEdit&parentId="+parentId+"&applyId="+applyId+"&supplierName="+supplierName+"&supplierId="+supplierId
			 +"&quoteId="+quoteId+"&quote="+quote+"&placeValuesBefore&TB_iframe=true&modal=false&height=380&width=930");
	}
	else{
	  alert("����ѡ��Ӧ�̣�")
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
	if(confirm("ȷ��Ҫ��գ�ȷ�Ϻ�ɾ����Ӧ��")){
		var suppId=$("#suppId"+index).val();
		$.post("?model=purchase_contract_applysupp&action=del&id="+suppId,{
			id:suppId
		},function(data,state){
		   if(state){
	   		  var supplierId=$("#supplierId"+index).val();
	   		  if(supplierId== $("#suppId").val()){
					$("#suppId").val("");
					$("#suppName").val("");
					$("#allMoneyView").val("");
					$("#allMoney").val("");
					$("#suppAccount1").val("");
					$("#suppBankName").val("");
					$("#suppBank").val("");
					$("#suppTel").val("");
					$("#suppAddress").val("");
					$("#suppContactMan").val("");
					$("#contactManPhone").val("");
					$("#dateHope").val("");
					$("#paymentConditionName").val("");
					$("#paymentCondition").val("");
					$("#payRatio").val("");
					$('#suppAccount1').yxcombogrid_suppAccount("remove");
					$("#isNeedPayapply").attr("checked",false);
					$("#payapply").hide();
					$("#payablesapply").hide();
					conversion();
	   		  }
		      $("#supplierName"+index).val("");
		      $("#supplierId"+index).val("");
		      $("#suppLevel"+index).val("");
		      $("#products"+index).val("");
		      $("#quote"+index).val("");
		      $("#quote"+index+"save").val("");
		      $("#suppId"+index).val("");
		      $("#"+index).remove();
		      $("#assignSupp"+index).remove();
		   }
		});
	}
}
//��ӻ��޸�ѯ�۵�ʱ����չ�Ӧ����Ϣ
function editSupp(oldSuppId,index){
   		  if(oldSuppId== $("#suppId").val()){
				$("#suppId").val("");
				$("#suppName").val("");
				$("#allMoneyView").val("");
				$("#allMoney").val("");
				$("#suppAccount1").val("");
				$("#suppBankName").val("");
				$("#suppBank").val("");
				$("#suppTel").val("");
				$("#suppAddress").val("");
				$("#suppContactMan").val("");
				$("#contactManPhone").val("");
				$("#dateHope").val("");
				$("#paymentConditionName").val("");
				$("#paymentCondition").val("");
				$("#payRatio").val("");
				$('#suppAccount1').yxcombogrid_suppAccount("remove");
   		  }
	      $("#"+index).remove();
	      $("#assignSupp"+index).remove();
}

/***************** ������������ ********************/
//����ѡ���ж�
function changeRadio(){
	//����������֤
	if($("#uploadfileList").html() == "" || $("#uploadfileList").html() == "�����κθ���"){
		alert('�������ǰ��Ҫ�ϴ���ͬ����!');
		$("#isNeedStampNo").attr("checked",true);
		return false;
	}

	//��ʾ������
	if($("#isNeedStampYes").attr("checked")){
		$("#radioSpan").show();
	}else{
		$("#radioSpan").hide();
	}
}

//���޸�ҳ���ύ����
function submitAudit(){
	document.getElementById('form1').action="index1.php?model=purchase_contract_purchasecontract&action=orderEdit&act=audit";
}
//�ύ����֤
function checkSumbit(){
	var selectRemark = $.trim($("#selectRemark").val());
	if( selectRemark=='������ѡ��ù�Ӧ�̵�����' || selectRemark=="" ){
		alert("������ѡ��ù�Ӧ�̵�����");
		return false;
	}
	if($("#isNeedPayapply").attr('checked')){
		var payDate=$("#payDate").val();
		if(payDate==""){
			alert("��ѡ��������������");
			return false;
		}
		var place=$.trim($("#place").val());
		if(place==""){
			alert("���������ص�(ʡ��)");
			return false;
		}
		var currency=$.trim($("#currency").val());
		if(currency==""){
			alert("��ѡ�񸶿����");
			return false;
		}
		var payRemark=$.trim($("#payRemark").val());
		if(payRemark==""){
			alert("�����������;");
			return false;
		}
		var payDesc=$.trim($("#payDesc").val());
		if(payDesc==""){
			alert("���������˵��");
			return false;
		}
	}

    $("#billingType").removeAttr("disabled");

}

//��ʾ����������Ϣ
function showPayapplyInfo(thisObj){
	if(thisObj.checked == true){
		thisObj.value = 1;
		$("#payablesapply").show();
		var orderId=$("#id").val();
		var suppId=$("suppId").val();

		 $.ajax({
			    type: "POST",
			    url: "?model=purchase_contract_purchasecontract&action=getPayStr",
			    data: {
			    	suppId : $("#suppId").val(),
					id:$("#id").val()
			    	},
			    async: false,
			    success: function(data){
					if(data){
						$("#invbody").html(data);
						var invnumber = document.getElementById('invbody').rows.length;
						$('#coutNumb').val(invnumber);
					}
			}
		});
		countAll();

	}else{
		thisObj.value = 0;
		$("#payablesapply").hide();
		$("#invbody").html("");
	}
}

//ɾ���в�������
function mydel(obj,mytable){
	if(confirm('ȷ��Ҫɾ�����У�')){
		var rowNo = obj.parentNode.parentNode.rowIndex*1;
		var mytable = document.getElementById(mytable);
   		mytable.deleteRow(rowNo - 2);
   		//���¶��кŸ�ֵ
   		$.each($("tbody#invbody tr td:nth-child(2)"),function(i,n){
	   		$(this).html( i + 1 );
   		});
	}
	countAll();
}

function checkMax(thisI){
	if($("#objId" + thisI).val() == "" || $("#objId" + thisI).val() == 0 ){
		return false;
	}
	if($("#money"+ thisI).val()*1 > $("#oldMoney"+ thisI).val()*1){
		alert('�������ѳ�����������');
		if($("#orgMoney" + thisI ).length == 0){
			$("#money"+ thisI + "_v").val(moneyFormat2($("#oldMoney"+ thisI).val())) ;
			$("#money"+ thisI).val($("#oldMoney"+ thisI).val()) ;
		}else{
			$("#money"+ thisI + "_v").val(moneyFormat2($("#orgMoney"+ thisI).val())) ;
			$("#money"+ thisI).val($("#orgMoney"+ thisI).val()) ;
		}
		return false;
	}
}

//���ݻ��ʼ��㱾��
function conversion() {
	var allMoneyCur = $("#allMoney").val() * $("#rate").val();
    $("#allMoneyCur").val(allMoneyCur);
    $("#allMoneyCur_v").val(moneyFormat2(allMoneyCur))
}


