$(function() {
	$("#purchType").bind("change", function() {

		$("#addLine").show();
		$("#sourceName").yxcombogrid_contract("remove"); // 移除面板
		$("#sourceName").yxcombogrid_fillup("remove");
		$("#sourceName").yxcombogrid_rdProject("remove");
		$("#sourceName").yxcombogrid_order("remove");
		$("#sourceName").val("");
		$("#sourceId").val();
		$("#invbody").html("");
		var purchType = $("#purchType").val();
		if (purchType == "") { // 如果类型为空，则设置样式为只读，否则为txt
			$("#sourceName").removeClass("txt");
			$("#sourceName").addClass("readOnlyText");
		} else {
			$("#sourceName").removeClass("readOnlyText");
			$("#sourceName").addClass("txt");
		}

		if (purchType == "order") { // 订单下拉
			$("#addLine").hide();
			$("#sourceName").yxcombogrid_order({
				hiddenId : 'sourceId',
				gridOptions : {
					showcheckbox : false,
					param : {
						"ExaStatus" : "完成"
					},
					event : {
						'row_dblclick' : function(e, row, data) {
							$.post(
									"?model=purchase_external_external&action=addItemList",
									{
										parentId : data.id,
										purchType : "order" // "purchType"参数用来判断该采取哪种策略
									}, function(data) {
										$("#invbody").html("");
										$("#invbody").append(data);
//										$("input.amount").each(function() {
//													if ($(this).val() < 1) {
//														alert("请输入正确的数量,不能为空或者小于1");
//														$(this).attr("value"," ");
//														$(this).focus();
//													}
//												});
									});
						}
					}
				}
			});
			$("#sourceName").yxcombogrid_order("showCombo");
		}

		if (purchType == "contract_sales") { // 销售合同下拉
			$("#addLine").hide();
			$("#sourceName").yxcombogrid_contract({
				hiddenId : 'sourceId',
				gridOptions : {
					showcheckbox : false,
					param : {
						"contStatus" : "1"
					},
					event : {
						'row_dblclick' : function(e, row, data) {
							$.post(
									"?model=purchase_external_external&action=addItemList",
									{
										parentId : data.contNumber,
										purchType : "contract_sales"
									}, function(data) {
										$("#invbody").html("");
										$("#invbody").append(data);
										$("input.amount").each(function() {
													if ($(this).val() < 1) {
														alert("请输入正确的数量,不能为空或者小于1");
														$(this).attr("value"," ");
														$(this).focus();
													}
												});
									});
						}
					}
				}
			});
			$("#sourceName").yxcombogrid_contract("showCombo");
		}
		if (purchType == "stock") { // 补库单下拉
			$("#addLine").hide();
			$("#sourceName").yxcombogrid_fillup({
				hiddenId : 'sourceId',
				gridOptions : {
					showcheckbox : false,
					param : {
						"contStatus" : "1"
					},
					event : {
						'row_dblclick' : function(e, row, data) {
							$.post(
									"?model=purchase_external_external&action=addItemList",
									{
										parentId : data.id,
										purchType : "stock"
									}, function(data) {
										$("#invbody").html("");
										$("#invbody").append(data);
//										$("input.amount").each(function() {
//													if ($(this).val() < 1) {
//														alert("请输入正确的数量,不能为空或者小于1");
//														$(this).attr("value"," ");
//														$(this).focus();
//													}
//												});
									});
						}
					}
				}
			});
			$("#sourceName").yxcombogrid_fillup("showCombo");
		}
		if (purchType == "rdproject") { // 研发项目下拉
			$("#sourceName").yxcombogrid_rdProject({
				hiddenId : 'sourceId',
				gridOptions : {
					showcheckbox : false,
					param : {
						"contStatus" : "1"
					},
					event : {
						'row_dblclick' : function(e, row, data) {
							$.post(
									"?model=purchase_external_external&action=addItemList",
									{
										parentId : data.id,
										purchType : "rdproject"
									}, function(data) {
										$("#invbody").html("");
										$("#invbody").append(data);
										$("input.amount").each(function() {
													if ($(this).val() < 1) {
														alert("请输入正确的数量,不能为空或者小于1");
														$(this).attr("value"," ");
														$(this).focus();
													}
												});
									});
						}
					}
				}
			});
			$("#sourceName").yxcombogrid_rdProject("showCombo");
		}
	});

	//由其他页面下达采购计划时，获取物料信息
	if ($("#sourceId1").val() != '') {
		$("#addLine").hide();
		$("#purchType").remove();   //移除下拉选择框
		var types="";
		var purchTypes=$("#purchType1").val();
		if(purchTypes=="order"){				//转换显示名称
			 types="订单采购";
		}
		if(purchTypes=="stock"){
			types="补库采购";
		}
		//显示采购类型的中文名称
		var input=$("<input type='text' class='readOnlyText' name='' id='purchTypes'>").attr('value',types);
		//设置采购类型的英文值
		var hidden=$("<input type='hidden' name='basic[purchType]' id='purchType'>").val(purchTypes);
		$("#append").append(input);
		$("#append").append(hidden);
		var sourceId=$("#sourceId1").val();
		$("#purchTypes").addClass("readOnlyText");
		$("#sourceId").val($("#sourceId1").val());
		$("#sourceName").val($("#sourceName1").val());

		$.post("?model=purchase_external_external&action=addItemList", {
			parentId : $("#sourceId1").val(),
			purchType :$("#purchType1").val()      // "purchType"参数用来判断该采取哪种策略
		}, function(data) {
			$("#invbody").html("");
			$("#invbody").append(data);
			$("input.amount").each(function() {
				if ($(this).val() < 0) {
					alert("请输入正确的数量,不能为空或者小于1");
					$(this).attr("value", " ");
					$(this).focus();
				}
			});
		});
	}

});