$(document).ready(function() {
	addConfig();
	$("#purchUserName").yxselect_user({
		hiddenId: 'purchUserCode',
		formCode: 'productPurchUser'
	});
	/**
	 * /** ��֤��Ϣ
	 */
	validate({
		productCode: {
			required: true,
			custom: ['blank']
		},
		productName: {
			required: true
		},
		proType: {
			required: true
		},
		pattern: {
			required: true
		},
		priCost: {
			required: true
		},
		businessBelongName: {
			required: true
		},
		ext2: {
			required: true,
			custom: ['blank']
		},
		purchPeriod: {
			required: true,
			custom: ['onlyNumber']
		},
		warranty: {
			required: true,
			custom: ['onlyNumber']
		}
	}, {
		disableButton: true
	});

	$("#productCode").ajaxCheck({
		url: "?model=stock_productinfo_productinfo&action=checkRepeat",
		alertText: "* �ñ����Ѵ���",
		alertTextOk: "* �ñ������"
	});

	$("#ext2").ajaxCheck({
		url: "?model=stock_productinfo_productinfo&action=checkRepeat",
		alertText: "* ��K3�����Ѵ���",
		alertTextOk: "* ��K3�������"
	});

	// ��Ʒѡ��
	$("#relGoodsName").yxcombogrid_goods({
		hiddenId: "relGoodsId",
		gridOptions: {
			showcheckbox: false,
			event: {
				'row_dblclick': function(e, row, data) {
					// ��Ʒ����
					$("#relGoodsProName").yxcombogrid_goodsproperties('remove').yxcombogrid_goodsproperties({
						hiddenId: "relGoodsPro",
						gridOptions: {
							param: {mainId: data.id}
						}
					}).val('');
					$("#relGoodsPro").val('');
				}
			}
		},
		event: {
			'clear': function() {
				$("#relGoodsProName").val('').yxcombogrid_goodsproperties('remove');
				$("#relGoodsPro").val('');
			}
		}
	});

	//�ֹ�˾
	$("#businessBelongName").yxcombogrid_branch({
		hiddenId: 'businessBelong',
		width: 400
	});
	
	$("#submitSave").click(function(){
		if(chkDuplicate()){
			$("#form1").submit();
		}
	});
	$("#productName").blur(function(){
		var chk = chkDuplicate();
	});
	$("#pattern").blur(function(){
		var chk = chkDuplicate();
	});
});

/**
 * ��̬�����������
 */
function addConfig() {
	var mycount = parseInt($("#configCount").val());
	var itemtable = document.getElementById("itemConfigTable");
	var i = itemtable.rows.length;
	var oTR = itemtable.insertRow([i]);
	oTR.align = "center";
	var oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = '<img align="absmiddle" src="images/removeline.png" onclick="delConfigItem(this);" title="ɾ����"/>';
	var oTL1 = oTR.insertCell([1]);
	oTL1.innerHTML = mycount + 1;
	var oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML = '<input type="text" class="txtshort" id="cConfigCode'
	+ mycount + '" name="productinfo[configItem][' + mycount
	+ '][configCode]" /><input type="hidden" id="cConfigId' + mycount
	+ '" value="" name="productinfo[configItem][' + mycount
	+ '][configId]" /><input type="hidden" id="cConfigType' + mycount
	+ '" value="proconfig" name="productinfo[configItem][' + mycount
	+ '][configType]" />';
	var oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML = '<input type="text" class="txt"  id="cConfigName'
	+ mycount + '" name="productinfo[configItem][' + mycount
	+ '][configName]" />';
	var oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML = '<input type="text" class="txtshort" name="productinfo[configItem]['
	+ mycount + '][configNum]" />';
	var oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML = '<input type="text" class="txt"  name="productinfo[configItem]['
	+ mycount + '][explains]" />';
	document.getElementById("configCount").value = document
		.getElementById("configCount").value * 1 + 1;
	reloadConfigProduct();
	reloadConfigCount();
}

/**
 * ��̬����������
 */
function addAccess() {
	var mycount = parseInt($("#accessCount").val());
	var itemtable = document.getElementById("itemAccessTable");
	var i = itemtable.rows.length;
	var oTR = itemtable.insertRow([i]);
	oTR.align = "center";
	var oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = '<img align="absmiddle" src="images/removeline.png" onclick="delAccessItem(this);" title="ɾ����"/>';
	var oTL1 = oTR.insertCell([1]);
	oTL1.innerHTML = mycount + 1;
	var oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML = '<input type="text" class="txtshort" id="aConfigCode'
	+ mycount + '" name="productinfo[accessItem][' + mycount
	+ '][configCode]" /><input type="hidden" id="aConfigId' + mycount
	+ '" value="" name="productinfo[accessItem][' + mycount
	+ '][configId]" /><input type="hidden" id="aConfigType' + mycount
	+ '" value="proaccess" name="productinfo[accessItem][' + mycount
	+ '][configType]" />';
	var oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML = '<input type="text" class="txt"  id="aConfigName'
	+ mycount + '" name="productinfo[accessItem][' + mycount
	+ '][configName]" />';
	var oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML = '<input type="text" class="readOnlyTxtItem"  id="aConfigPattern'
	+ mycount
	+ '" name="productinfo[accessItem]['
	+ mycount
	+ '][configPattern]" />';
	var oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML = '<input type="text" class="txtshort"  name="productinfo[accessItem]['
	+ mycount + '][configNum]" />';
	var oTL6 = oTR.insertCell([6]);
	oTL6.innerHTML = '<input type="text" class="txt"  name="productinfo[accessItem]['
	+ mycount + '][explains]" />';
	document.getElementById("accessCount").value = document
		.getElementById("accessCount").value * 1 + 1;
	reloadAccessProduct();
	reloadAccessCount();
}

/**
 * ��Ⱦ����������Ϣcombogrid
 */
function reloadConfigProduct() {
	for (var i = 0; i < $('#configCount').val(); i++) {// �����ϱ���
		$("#cConfigCode" + i).yxcombogrid_product("remove").yxcombogrid_product({
			hiddenId: 'cConfigId' + i,
			nameCol: 'productCode',
			isDown : true,
			isFocusoutCheck : false,//�ر�У��
			gridOptions: {
				showcheckbox: false,
				event: {
					'row_dblclick': function(i) {
						return function(e, row, data) {
							$('#cConfigName' + i).val(data.productName);
						}
					}(i)
				}
			}
		});
		$("#cConfigName" + i).yxcombogrid_product("remove").yxcombogrid_product({
			hiddenId: 'cConfigId' + i,
			nameCol: 'productName',
			isDown : true,
			isFocusoutCheck : false,//�ر�У��
			gridOptions: {
				showcheckbox: false,
				event: {
					'row_dblclick': function(i) {
						return function(e, row, data) {
							$('#cConfigCode' + i).val(data.productCode);
						}
					}(i)
				}
			}
		});
	}
}

/**
 * ��Ⱦ��������嵥combogrid
 */
function reloadAccessProduct() {
	for (var i = 0; i < $('#accessCount').val(); i++) {// �����ϱ���
		$("#aConfigCode" + i).yxcombogrid_product("remove").yxcombogrid_product({
			hiddenId: 'aConfigId' + i,
			nameCol: 'productCode',
			gridOptions: {
				showcheckbox: false,
				event: {
					'row_dblclick': function(i) {
						return function(e, row, data) {
							$('#aConfigName' + i).val(data.productName);
							$("#aConfigPattern" + i).val(data.pattern);
						}
					}(i)
				}
			}
		});
		$("#aConfigName" + i).yxcombogrid_product("remove").yxcombogrid_product({
			hiddenId: 'aConfigId' + i,
			nameCol: 'productName',
			gridOptions: {
				showcheckbox: false,
				event: {
					'row_dblclick': function(i) {
						return function(e, row, data) {
							$('#aConfigCode' + i).val(data.productCode);
							$("#aConfigPattern" + i).val(data.pattern);
						}
					}(i)
				}
			}
		});
	}
}

/**
 * ����������Ϣ���к�
 */
function reloadConfigCount() {
	var i = 1;
	$("#itemConfigBody").children("tr").each(function() {
		if ($(this).css("display") != "none") {
			$(this).children("td").eq(1).text(i);
			i++;
		}
	});
}

/**
 * ��������嵥���к�
 */
function reloadAccessCount() {
	var i = 1;
	$("#itemAccessBody").children("tr").each(function() {
		if ($(this).css("display") != "none") {
			$(this).children("td").eq(1).text(i);
			i++;
		}
	});
}

function delConfigItem(obj) {
	if (confirm('ȷ��Ҫɾ�����У�')) {
		var rowNo = obj.parentNode.parentNode.rowIndex - 2;
		$(obj).parent().parent().hide();
		$(obj).parent().append(
			'<input type="hidden" name="productinfo[configItem][' + rowNo
			+ '][isDelTag]" value="1" id="isDelTag' + rowNo
			+ '" />');
	}
	reloadConfigCount();
}

function delAccessItem(obj) {
	if (confirm('ȷ��Ҫɾ�����У�')) {
		var rowNo = obj.parentNode.parentNode.rowIndex - 2;
		$(obj).parent().parent().hide();
		$(obj).parent().append(
			'<input type="hidden" name="productinfo[accessItem][' + rowNo
			+ '][isDelTag]" value="1" id="isDelTag' + rowNo
			+ '" />');
	}
	reloadAccessCount();
}
/**
 * ������չ��Ϣչʾ������
 */
function extControl() {
	if (document.getElementById("extinfo").style.display == "none") {
		$("#extImg").attr("src", "images/icon/info_up.gif");
		$("#extinfo").show();
	} else {
		$("#extImg").attr("src", "images/icon/info_right.gif");
		$("#extinfo").hide();
	}
}
/**
 * ������չ��Ϣչʾ������
 */
function baseControl() {
	if (document.getElementById("baseinfo").style.display == "none") {
		$("#baseImg").attr("src", "images/icon/info_up.gif");
		// document.getElementById("extInfo").style.display=="block";
		$("#baseinfo").show();
	} else {
		$("#baseImg").attr("src", "images/icon/info_right.gif");
		$("#baseinfo").hide();
	}
}
/**
 * �ύ����֤
 */
function checkSubmit() {
    var priCostObj = $("#priCost");
    var priCost = $.trim(priCostObj.val());
	if (priCost == '') {
		alert('���ϳɱ�����Ϊ��');
		return false;
	}
	if (parseInt(priCost)== 0) {
        priCostObj.val('');
		alert('���ϳɱ�����Ϊ0');
		return false;
	}
    // ���С��0���ж��Ƿ��ǳ�����͵�����
    if (parseInt(priCostObj) < 0) {
        if ($("#properties").val() != 'WLSXCJ') {
            alert('�ǳ�����ϲ������븺��');
            return false;
        }
    }
}