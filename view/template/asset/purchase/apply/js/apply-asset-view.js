$(function() {
			$("#confirmUserButton").click(function() {
						if (confirm("ȷ�Ϸ�������ȷ����Ա?")) {
							$("#form1").submit();
						}
					});
			$("#confirmButton").click(function() {
						var isOk = true;
						$("input[id^='productId']").each(function() {
									if ($(this).val() == "") {
										isOk = false;
									}
								});
						if (isOk) {
							if (confirm("ȷ��ѡ������ϣ�ȷ�Ϻ��޷��޸ġ�")) {
								$("#form1").submit();
							}
						} else {
							alert("��Ϊÿ���ɹ��嵥ѡ�����ϡ�");
						}
					});

			$("#backButton").click(function() {
				if ($("input[id^='isBack']:checked").size() == 0) {
					alert("�����ٹ�ѡһ����Ҫ��ص�����.");
					return false;
				}
				if ($.trim($("#backReason").val()) =="") {
					alert("��������ԭ��");
					return false;
				}
				if (confirm("ȷ�ϴ��ѡ�е����ϣ�")) {
					$("#form1")
							.attr("action",
									"?model=asset_purchase_apply_apply&action=backBasicToApplyUser");
					$("#form1").submit();
				}
			});
		});