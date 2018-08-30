//计算付款
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
	 			//币种初始化
			var currencyCodeObj = $("#currencyCode");
			if(currencyCodeObj.length > 0){
				// 金额币别
				$("#currency").yxcombogrid_currency({
					hiddenId : 'currencyCode',
					valueCol : 'currencyCode',
					isFocusoutCheck : false,
					gridOptions : {
						showcheckbox : false,
						event : {
							'row_dblclick' : function(e, row, data) {
								$("#rate").val(data.rate);
								// 根据汇率计算本币
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
		// 修改询价单时，获取供应商信息
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
						alert("请先填写该供应商的报价单");
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
								$("#suppName").val($(this).attr('title'));//供应商名称
								$("#suppId").val($(this).val());			//供应商ID
								$("#allMoney").val($("#quote"+j+"save").val());			//总金额
								$("#allMoneyView").val($("#quote"+j).val());			//总金额(显示)
								conversion();

								$('#suppAccount1').yxcombogrid_suppAccount("remove");
								$("#suppAccount1").val("");
								$("#suppBankName").val("");		//将银行的数据字典转换成中文后替换相应的值，并在页面显示
								$("#suppBank").val("");
								$("#isNeedPayapply").attr("checked",false);
								$("#payapply").hide();
								$("#payablesapply").hide();

								//供应商信息
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
								//报价供应商信息
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

								//如果供应商存银行信息，则默认带出第一个信息
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

								//如果供应商存有联系人信息，则默认带出第一个信息
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
										// 根据供应商ID及选中的开户银行，过滤出对应的银行帐号
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
												$("#suppBank").val(data.depositbank);				//银行的数据字典编码，隐藏域，用于保存数据时写入数据库
											}
										}
									}
								});
							}else{
								$(this).attr("checked",false);
								alert('该供应商为非合格供应商,不能下达订单');
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
			// 根据供应商ID及选中的开户银行，过滤出对应的银行帐号
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
					$("#suppBankName").val(data.depositbank_name);		//将银行的数据字典转换成中文后替换相应的值，并在页面显示
					$("#suppBank").val(data.depositbank);				//银行的数据字典编码，隐藏域，用于保存数据时写入数据库
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
 			$("#selectRemark").val('请输入选择该供应商的理由');
 			$("#selectRemark").attr("color",'#AAAAAA');
 			window.document.getElementById("selectRemark").style.color='#AAAAAA';
 		}

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
								$("#suppLevel" + i).val(data.suppGrade);
								var suppId=$("#suppId"+i).val();
								//保存供应商信息
								if(suppId==""){
									//若还没选择供应商，则新增供应商
									$.post("?model=purchase_contract_purchasecontract&action=addSupp",{
										supplierName:data.suppName,
										supplierId:data.id,
										supplierPro:data.products,
										parentId:$("#id").val(),
										parentCode:$("#hwapplyNumb").val()
										},function(id,status){
											var ids=$("#suppId"+i).val(id);    //返回订单供应商的ID

											var radio=$("<input type='radio' id='"+i+"' name='suppCheck'>").val(data.id).attr('title',data.suppName);
											$("#suppRadios").append(radio);
											radio.after("<span id='assignSupp"+i+"'>"+data.suppName+"</span>");
											radio.click(function(){
												j=$(this).attr('id');
												var suppLevel=$.trim($("#suppLevel"+j).val());
												if($("#quote"+j).val()==""){
													$(this).attr("checked",false);
													alert("请先填写该供应商的报价单");
												}else if(suppLevel=="A"||suppLevel=="B"||suppLevel=="C"){
													$("#suppName").val($(this).attr('title'));//供应商名称
													$("#suppId").val($(this).val());			//供应商ID
													$("#allMoney").val($("#quote"+j+"save").val());			//总金额
													$("#allMoneyView").val($("#quote"+j).val());			//总金额(显示)
													conversion();

													$('#suppAccount1').yxcombogrid_suppAccount("remove");
													$("#suppAccount1").val("");
													$("#suppBankName").val("");		//将银行的数据字典转换成中文后替换相应的值，并在页面显示
													$("#suppBank").val("");

													$("#isNeedPayapply").attr("checked",false);
													$("#payapply").hide();
													$("#payablesapply").hide();

													//供应商信息
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
													//如果供应商存银行信息，则默认带出第一个信息
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

												//如果供应商存有联系人信息，则默认带出第一个信息
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

													//报价供应商信息
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
															// 根据供应商ID及选中的开户银行，过滤出对应的银行帐号
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
																	$("#suppBank").val(data.depositbank);				//银行的数据字典编码，隐藏域，用于保存数据时写入数据库
																}
															}
														}
													});

												}else{
													$(this).attr("checked",false);
													alert('该供应商为非合格供应商,不能下达订单');
												}
											});
									});
								}else{
									//若已选择供应商，则跳转到编辑方法
									$.post("?model=purchase_contract_purchasecontract&action=suppAdd",{
										suppIds:suppId,
										supplierName:data.suppName,
										supplierId:data.id,
										supplierPro:data.products,
										parentId:$("#id").val()
										},function(id,status){
											var oldSuppId=$("#" + i).val();
											editSupp(oldSuppId,i);
											var ids=$("#suppId"+i).val(id);    //返回订单供应商的ID

											var radio=$("<input type='radio' id='"+i+"' name='suppCheck'>").val(data.id).attr('title',data.suppName);
											$("#suppRadios").append(radio);
											radio.after("<span id='assignSupp"+i+"'>"+data.suppName+"</span>");
											radio.click(function(){
												j=$(this).attr('id');
												var suppLevel=$.trim($("#suppLevel"+j).val());
												if($("#quote"+j).val()==""){
													$(this).attr("checked",false);
													alert("请先填写该供应商的报价单");
												}else if(suppLevel=="A"||suppLevel=="B"||suppLevel=="C"){
													$("#suppName").val($(this).attr('title'));//供应商名称
													$("#suppId").val($(this).val());			//供应商ID
													$("#allMoney").val($("#quote"+j+"save").val());			//总金额
													$("#allMoneyView").val($("#quote"+j).val());			//总金额(显示)


													$('#suppAccount1').yxcombogrid_suppAccount("remove");
													$("#suppAccount1").val("");
													$("#suppBankName").val("");		//将银行的数据字典转换成中文后替换相应的值，并在页面显示
													$("#suppBank").val("");

													$("#isNeedPayapply").attr("checked",false);
													$("#payapply").hide();
													$("#payablesapply").hide();

													//供应商信息
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
													//报价供应商信息
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

													//如果供应商存银行信息，则默认带出第一个信息
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

													//如果供应商存有联系人信息，则默认带出第一个信息
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
															// 根据供应商ID及选中的开户银行，过滤出对应的银行帐号
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
																	$("#suppBank").val(data.depositbank);				//银行的数据字典编码，隐藏域，用于保存数据时写入数据库
																}
															}
														}
													});
												}else{
													$(this).attr("checked",false);
													alert('该供应商为非合格供应商,不能下达订单');
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


/**跳到报单添加页面*/
function quote(index){
	var applyId=$("#id").val();
	var parentId=$("#suppId"+index).val();
	var supplierName=$("#supplierName"+index).val();
	var supplierId=$("#supplierId"+index).val();
	var quoteId= document.getElementById("quote"+index).id;
	var quote=$("#quote"+index).val();

	//跳转到报价单添加页面
	if(parentId!=""&&quote==""){
			 showThickboxWin("index1.php?model=purchase_contract_applysupp&action=toAdd&parentId="+parentId+"&applyId="+applyId+"&supplierName="+supplierName+"&supplierId="+supplierId
			 +"&quoteId="+quoteId+"&placeValuesBefore&TB_iframe=true&modal=false&height=380&width=930");
	}else if(quote!=""){
			showThickboxWin("index1.php?model=purchase_contract_applysupp&action=toEdit&parentId="+parentId+"&applyId="+applyId+"&supplierName="+supplierName+"&supplierId="+supplierId
			 +"&quoteId="+quoteId+"&quote="+quote+"&placeValuesBefore&TB_iframe=true&modal=false&height=380&width=930");
	}
	else{
	  alert("请先选择供应商！")
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
	if(confirm("确认要清空？确认后将删除供应商")){
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
//添加或修改询价单时，清空供应商信息
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

/***************** 盖章新增部分 ********************/
//盖章选择判断
function changeRadio(){
	//附件盖章验证
	if($("#uploadfileList").html() == "" || $("#uploadfileList").html() == "暂无任何附件"){
		alert('申请盖章前需要上传合同附件!');
		$("#isNeedStampNo").attr("checked",true);
		return false;
	}

	//显示必填项
	if($("#isNeedStampYes").attr("checked")){
		$("#radioSpan").show();
	}else{
		$("#radioSpan").hide();
	}
}

//在修改页面提交审批
function submitAudit(){
	document.getElementById('form1').action="index1.php?model=purchase_contract_purchasecontract&action=orderEdit&act=audit";
}
//提交表单验证
function checkSumbit(){
	var selectRemark = $.trim($("#selectRemark").val());
	if( selectRemark=='请输入选择该供应商的理由' || selectRemark=="" ){
		alert("请输入选择该供应商的理由");
		return false;
	}
	if($("#isNeedPayapply").attr('checked')){
		var payDate=$("#payDate").val();
		if(payDate==""){
			alert("请选择期望付款日期");
			return false;
		}
		var place=$.trim($("#place").val());
		if(place==""){
			alert("请输入汇入地点(省市)");
			return false;
		}
		var currency=$.trim($("#currency").val());
		if(currency==""){
			alert("请选择付款币种");
			return false;
		}
		var payRemark=$.trim($("#payRemark").val());
		if(payRemark==""){
			alert("请输入款项用途");
			return false;
		}
		var payDesc=$.trim($("#payDesc").val());
		if(payDesc==""){
			alert("请输入款项说明");
			return false;
		}
	}

    $("#billingType").removeAttr("disabled");

}

//显示付款申请信息
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

//删除行操作方法
function mydel(obj,mytable){
	if(confirm('确定要删除该行？')){
		var rowNo = obj.parentNode.parentNode.rowIndex*1;
		var mytable = document.getElementById(mytable);
   		mytable.deleteRow(rowNo - 2);
   		//重新对行号赋值
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
		alert('申请金额已超出可申请金额');
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

//根据汇率计算本币
function conversion() {
	var allMoneyCur = $("#allMoney").val() * $("#rate").val();
    $("#allMoneyCur").val(allMoneyCur);
    $("#allMoneyCur_v").val(moneyFormat2(allMoneyCur))
}


