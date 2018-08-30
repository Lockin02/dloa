$(function() {
	$("#purchType").bind("change", function() {

		$("#addLine").show();
		$("#sourceName").yxcombogrid_contract("remove"); // �Ƴ����
		$("#sourceName").yxcombogrid_fillup("remove");
		$("#sourceName").yxcombogrid_rdProject("remove");
		$("#sourceName").yxcombogrid_order("remove");
		$("#sourceName").val("");
		$("#sourceId").val();
		$("#invbody").html("");
		var purchType = $("#purchType").val();
		if (purchType == "") { // �������Ϊ�գ���������ʽΪֻ��������Ϊtxt
			$("#sourceName").removeClass("txt");
			$("#sourceName").addClass("readOnlyText");
		} else {
			$("#sourceName").removeClass("readOnlyText");
			$("#sourceName").addClass("txt");
		}

		if (purchType == "order") { // ��������
			$("#addLine").hide();
			$("#sourceName").yxcombogrid_order({
				hiddenId : 'sourceId',
				gridOptions : {
					showcheckbox : false,
					param : {
						"ExaStatus" : "���"
					},
					event : {
						'row_dblclick' : function(e, row, data) {
							$.post(
									"?model=purchase_external_external&action=addItemList",
									{
										parentId : data.id,
										purchType : "order" // "purchType"���������жϸò�ȡ���ֲ���
									}, function(data) {
										$("#invbody").html("");
										$("#invbody").append(data);
//										$("input.amount").each(function() {
//													if ($(this).val() < 1) {
//														alert("��������ȷ������,����Ϊ�ջ���С��1");
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

		if (purchType == "contract_sales") { // ���ۺ�ͬ����
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
														alert("��������ȷ������,����Ϊ�ջ���С��1");
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
		if (purchType == "stock") { // ���ⵥ����
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
//														alert("��������ȷ������,����Ϊ�ջ���С��1");
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
		if (purchType == "rdproject") { // �з���Ŀ����
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
														alert("��������ȷ������,����Ϊ�ջ���С��1");
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

	//������ҳ���´�ɹ��ƻ�ʱ����ȡ������Ϣ
	if ($("#sourceId1").val() != '') {
		$("#addLine").hide();
		$("#purchType").remove();   //�Ƴ�����ѡ���
		var types="";
		var purchTypes=$("#purchType1").val();
		if(purchTypes=="order"){				//ת����ʾ����
			 types="�����ɹ�";
		}
		if(purchTypes=="stock"){
			types="����ɹ�";
		}
		//��ʾ�ɹ����͵���������
		var input=$("<input type='text' class='readOnlyText' name='' id='purchTypes'>").attr('value',types);
		//���òɹ����͵�Ӣ��ֵ
		var hidden=$("<input type='hidden' name='basic[purchType]' id='purchType'>").val(purchTypes);
		$("#append").append(input);
		$("#append").append(hidden);
		var sourceId=$("#sourceId1").val();
		$("#purchTypes").addClass("readOnlyText");
		$("#sourceId").val($("#sourceId1").val());
		$("#sourceName").val($("#sourceName1").val());

		$.post("?model=purchase_external_external&action=addItemList", {
			parentId : $("#sourceId1").val(),
			purchType :$("#purchType1").val()      // "purchType"���������жϸò�ȡ���ֲ���
		}, function(data) {
			$("#invbody").html("");
			$("#invbody").append(data);
			$("input.amount").each(function() {
				if ($(this).val() < 0) {
					alert("��������ȷ������,����Ϊ�ջ���С��1");
					$(this).attr("value", " ");
					$(this).focus();
				}
			});
		});
	}

});