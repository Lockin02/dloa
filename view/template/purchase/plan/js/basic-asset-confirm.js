$(function() {
			$("#confirmUserButton").click(function() {
						if (confirm("ȷ�Ϸ�������ȷ����Ա?")) {
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
							alert("������ѡ��һ������ȷ��.");
							return false;
						}
						if (confirm("ȷ��ѡ������ϣ�ȷ�Ϻ��޷��޸ġ�")) {
							$("#form1").submit();
						}
					});

			$("#backButton").click(function() {
				if ($("input[id^='isBack']:checked").size() == 0) {
					alert("�����ٹ�ѡһ����Ҫ��ص�����.");
					return false;
				}
				if ($.trim($("#backReason").val()) == "") {
					alert("��������ԭ��");
					return false;
				}
				if (confirm("ȷ�ϴ��ѡ�е����ϣ�")) {
					$("#form1")
							.attr("action",
									"?model=purchase_plan_basic&action=backBasicToApplyUser");
					$("#form1").submit();
				}
			});
		});