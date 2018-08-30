$(function() {
			$("#confirmUserButton").click(function() {
						if (confirm("确认分配物料确认人员?")) {
							$("#form1").submit();
						}
					});
			$("#confirmButton").click(function() {
						var nameEmpty = false;
						var errorMsg = '';
						$("input[id^='inputProductName']").each(function() {
							if ($(this).val() == '') {
								nameEmpty = true;
								errorMsg = '采购物料名称必填。';
							}
						});

						$("input[id^='productName']").each(function() {
							if ($(this).val() == '' && !nameEmpty) {
								nameEmpty = true;
								errorMsg = '确认物料名称必填。';
							}
						});

						if(nameEmpty){
							alert(errorMsg);
							return false;
						}

						var isOk = false;
						$("input[id^='productId']").each(function() {
									if ($(this).val() != '') {
										isOk = true;
									}
								});

						if (!isOk) {
							alert("请至少选中一个物料确认.");
							return false;
						}

                        var rowAmountVa = 0;
                        var cmps = $(".amount");
                        cmps.each(function() {
                            rowAmountVa = accAdd(rowAmountVa, $(this).val());
                        });
                        var itemSum=$("#itemSum").val();
                        if(itemSum!=rowAmountVa){
                            alert("采购申请数量总和与原申请数量："+itemSum+"不相等");
                            return false;
                        }
						if (confirm("确认选择的物料？确认后无法修改。")) {
							$("#form1").submit();
						}
					});

			$("#backButton").click(function() {
				if ($("input[id^='isBack']:checked").size() == 0) {
					alert("请至少勾选一个需要打回的物料.");
					return false;
				}
				if ($.trim($("#backReason").val()) == "") {
					alert("请输入打回原因");
					return false;
				}
				if (confirm("确认打回选中的物料？")) {
					$("#form1")
							.attr("action",
									"?model=purchase_plan_basic&action=backBasicToApplyUser");
					$("#form1").submit();
				}
			});
			if ($("#purchaseType").text() != "计划内 ") {
				$("#hiddenA").hide();
				// $("#hiddenB").hide();
			}

			// 根据采购种类为“研发类”时来显示部分字段（采购分类、重大专项名称、募集资金项目、其它研发项目）
			// alert($("#purchCategory").text());
			if ($("#purchCategory").text() == "研发类") {
				$("#hiddenC").hide();
			} else {
				$("#hiddenD").hide();
				$("#hiddenE").hide();
			}
		});


function checkData(){
    var rowAmountVa = 0;
    var cmps = $("#amount");
    cmps.each(function() {
        rowAmountVa = accAdd(rowAmountVa, $(this).val());
    });
    alert(rowAmountVa);
    return false;
}