$(document).ready(function() {
	addConfig();
	$("#purchUserName").yxselect_user({
		hiddenId: 'purchUserCode',
		formCode: 'productPurchUser'
	});
	/**
	 * /** 验证信息
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
		alertText: "* 该编码已存在",
		alertTextOk: "* 该编码可用"
	});

	$("#ext2").ajaxCheck({
		url: "?model=stock_productinfo_productinfo&action=checkRepeat",
		alertText: "* 该K3编码已存在",
		alertTextOk: "* 该K3编码可用"
	});

	// 产品选择
	$("#relGoodsName").yxcombogrid_goods({
		hiddenId: "relGoodsId",
		gridOptions: {
			showcheckbox: false,
			event: {
				'row_dblclick': function(e, row, data) {
					// 产品属性
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

	//分公司
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
 * 动态添加配置数据
 */
function addConfig() {
	var mycount = parseInt($("#configCount").val());
	var itemtable = document.getElementById("itemConfigTable");
	var i = itemtable.rows.length;
	var oTR = itemtable.insertRow([i]);
	oTR.align = "center";
	var oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = '<img align="absmiddle" src="images/removeline.png" onclick="delConfigItem(this);" title="删除行"/>';
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
 * 动态添加配件数据
 */
function addAccess() {
	var mycount = parseInt($("#accessCount").val());
	var itemtable = document.getElementById("itemAccessTable");
	var i = itemtable.rows.length;
	var oTR = itemtable.insertRow([i]);
	oTR.align = "center";
	var oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = '<img align="absmiddle" src="images/removeline.png" onclick="delAccessItem(this);" title="删除行"/>';
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
 * 渲染物料配置信息combogrid
 */
function reloadConfigProduct() {
	for (var i = 0; i < $('#configCount').val(); i++) {// 绑定物料编码
		$("#cConfigCode" + i).yxcombogrid_product("remove").yxcombogrid_product({
			hiddenId: 'cConfigId' + i,
			nameCol: 'productCode',
			isDown : true,
			isFocusoutCheck : false,//关闭校验
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
			isFocusoutCheck : false,//关闭校验
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
 * 渲染物料配件清单combogrid
 */
function reloadAccessProduct() {
	for (var i = 0; i < $('#accessCount').val(); i++) {// 绑定物料编码
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
 * 重新配置信息序列号
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
 * 重新配件清单序列号
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
	if (confirm('确定要删除该行？')) {
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
	if (confirm('确定要删除该行？')) {
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
 * 控制扩展信息展示与隐藏
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
 * 控制扩展信息展示与隐藏
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
 * 提交表单验证
 */
function checkSubmit() {
    var priCostObj = $("#priCost");
    var priCost = $.trim(priCostObj.val());
	if (priCost == '') {
		alert('物料成本不能为空');
		return false;
	}
	if (parseInt(priCost)== 0) {
        priCostObj.val('');
		alert('物料成本不能为0');
		return false;
	}
    // 如果小于0，判定是否是冲减类型的物料
    if (parseInt(priCostObj) < 0) {
        if ($("#properties").val() != 'WLSXCJ') {
            alert('非冲减物料不能输入负数');
            return false;
        }
    }
}