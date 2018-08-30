function CheckForm() {
				if ($('#formalNo').val() == "" && $('#temporaryNo').val() == "") {
					alert("必须填写一个合同号！");
					$('#formalNo').focus();
					return (false);
				}
				if ($('#contName').val() == "") {
					alert("合同名称需要填写！");
					$('#contName').focus();
					return (false);
				}
				if ($('#money').val() == "") {
					alert("合同金额需要填写！");
					$('#money').focus();
					return (false);
				}
				if ($('#principalName').val() == "") {
					alert("合同负责人需要填写！");
					$('#principalName').focus();
					return (false);
				}
				if ($('#deliveryDate').val() == "") {
					alert("交货日期需要填写！");
					$('#deliveryDate').focus();
					return (false);
				}
				isCorret = checkMoney($("#money").val());
				if (!isCorret) {
					alert("输入金额有误");
					$("#money").focus();
					return false;
				}
				phoneNum = $('#customerTel').val();
				if (!checkPhone(phoneNum)) {
					return false;
				}

				mailNumber = $('#customerEmail').val();
				if (mailNumber != "") {
					thisMail = ismail(mailNumber);
					if (!thisMail) {
						return false;
					}
				}

				deliveryDate = $('#deliveryDate').val();

				equNumber = $('#EquNum').val();
				for (i = 1; i <= equNumber; ++i) {
					if (!$('#EquId' + i)[0]) continue;
					else {
						equAmount = $('#EquAmount' + i).val();
						if (equAmount != "") {
							if (parseInt(equAmount) != equAmount) {
								alert('请输入正确的数量');
								$('#EquAmount' + i).focus();
								return false;
							}
						}
						equPrice = $('#EquPrice' + i).val();
						if (equPrice != "") {
							if (!checkMoney(equPrice)) {
								alert('请输入正确的单价');
								$('#EquPrice' + i).focus();
								return false;
							}
						}
						equAllMoney = $('#EquAllMoney' + i).val();
						if (equAllMoney != "") {
							if (!checkMoney(equAllMoney)) {
								alert('请输入正确的单价');
								$('#EquAllMoney' + i).focus();
								return false;
							}
						}
						equDeliveryDT = $('#EquDeliveryDT' + i).val();
						if ($('#EquId' + i).val() != "" && 　equDeliveryDT == "") {
							$('#EquDeliveryDT' + i).val(deliveryDate);
						}
					}
				}

				licenseNumber = $('#licenseNum').val();
				for (i = 1; i <= licenseNumber; i++) {
					if (!$('#softdogAmount' + i)[0]) continue;
					else {
						softdogAmount = $('#softdogAmount' + i).val();
						if (softdogAmount != "") {
							if (parseInt(softdogAmount) != softdogAmount) {
								alert('请输入正确的数量');
								$('#softdogAmount' + i).focus();
								return false;
							}
						}
					}
				}

				preNum = $('#PreNum').val();
				for (i = 1; i <= preNum; i++) {
					if (!$('#PequID' + i)[0]) continue;
					else {
						preAmount = $('#PreAmount' + i).val();
						if (preAmount != "") {
							if (parseInt(preAmount) != preAmount) {
								alert('请输入正确的数量');
								$('#PreAmount' + i).focus();
								return false;
							}
						}
						prePrice = $('#PrePrice' + i).val();
						if (prePrice != "") {
							if (!checkMoney(prePrice)) {
								alert('请输入正确的单价');
								$('#PrePrice' + i).focus();
								return false;
							}
						}
						countMoney = $('#CountMoney' + i).val();
						if (countMoney != "") {
							if (!checkMoney(countMoney)) {
								alert('请输入正确的单价');
								$('#CountMoney' + i).focus();
								return false;
							}
						}
						preDeliveryDT = $('#PreDeliveryDT' + i).val();
						if ($('#PequName' + i).val() != "" && 　preDeliveryDT == "") {
							$('#PreDeliveryDT' + i).val(deliveryDate);
						}
					}
				}

				payNumber = $('#PayNum').val();
				for (i = 1; i <= payNumber; i++) {
					if (!$('#PayMoney' + i)[0]) continue;
					else {
						payMoney = $('#PayMoney' + i).val();
						if (payMoney != "") {
							if (!checkMoney(payMoney)) {
								alert('请输入正确的收款金额');
								$('#PayMoney' + i).focus();
								return false;
							}
						}
						payDT = $('#PayDT' + i).val();
						if ($('#PayMoney' + i).val() != "" && 　payDT == "") {
							$('#PayDT' + i).val(deliveryDate);
						}
					}
				}

				return true;
			}

			function check_code(code) {
				if (code == '') {
					$('#_contNumber').html('合同号不能为空！');
				} else {
					var rand = Math.random() * 100000;
					$.get('index1.php', {
						model: 'contract_sales_sales',
						action: 'checkRepeat',
						equC: code,
						rand: rand
					},
					function(data) {
						if (data != '') {
							$('#_contNumber').html('已存在的合同号！');
							contnumber = false;
						} else {
							$('#_contNumber').html('合同号可用！');
							contnumber = true;
						}
					})
				}
				return false;
			}

			function saveContract() {
				document.getElementById('form1').action = "?model=contract_sales_sales&action=edit&act=save";
			}

			function openDia(count) {
				var licensetypeid = "licenseinput" + count;
				var licensetype = $("#" + licensetypeid).val();
				if (!licensetype) {
					alert("请先选择license类型！");
				} else {
					var hideid = "licenseNodeId" + count;
					var textid = "licenseNodeName" + count;

					var hidevalue = $("#" + hideid).val();

					//var thisurl = '?model=product_licensecheck_licensecheck&action=setInHtml&contractLicenseId=-1&hidevalue='+hidevalue+'&hideid='+hideid+'&textid='+textid;
					var thisurl = '?model=product_licensetype_licensetype&action=viewPages&ids=' + licensetype + '&hidevalue=' + hidevalue + '&hideid=' + hideid + '&textid=' + textid;
					window.open(thisurl);
				}
			}