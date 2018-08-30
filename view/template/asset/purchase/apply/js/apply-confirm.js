$(function() {
			$("#confirmUserButton").click(function() {
						if (confirm("ȷ�Ϸ�������ȷ����Ա?")) {
							$("#form1").submit();
						}
					});
			$("#confirmButton").click(function() {
						var nameEmpty = false;
						var errorMsg = '';
						$("input[id^='inputProductName']").each(function() {
							if ($(this).val() == '') {
								nameEmpty = true;
								errorMsg = '�ɹ��������Ʊ��';
							}
						});

						$("input[id^='productName']").each(function() {
							if ($(this).val() == '' && !nameEmpty) {
								nameEmpty = true;
								errorMsg = 'ȷ���������Ʊ��';
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
							alert("������ѡ��һ������ȷ��.");
							return false;
						}

                        var rowAmountVa = 0;
                        var cmps = $(".amount");
                        cmps.each(function() {
                            rowAmountVa = accAdd(rowAmountVa, $(this).val());
                        });
                        var itemSum=$("#itemSum").val();
                        if(itemSum!=rowAmountVa){
                            alert("�ɹ����������ܺ���ԭ����������"+itemSum+"�����");
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
			if ($("#purchaseType").text() != "�ƻ��� ") {
				$("#hiddenA").hide();
				// $("#hiddenB").hide();
			}

			// ���ݲɹ�����Ϊ���з��ࡱʱ����ʾ�����ֶΣ��ɹ����ࡢ�ش�ר�����ơ�ļ���ʽ���Ŀ�������з���Ŀ��
			// alert($("#purchCategory").text());
			if ($("#purchCategory").text() == "�з���") {
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