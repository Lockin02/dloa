$(function() {
			$("#confirmUserButton").click(function() {
						if (confirm("确认分配物料确认人员?")) {
							$("#form1").submit();
						}
					});
			$("#confirmButton").click(function() {
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
		});