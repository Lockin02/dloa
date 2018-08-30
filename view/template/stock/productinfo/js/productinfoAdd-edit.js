$(function() {
	if($("#methodType").val()=='confirm'){
		$("#submitStatus").val('确  认');
	}
	$("#submitSave").click(function(){
		$("#form1").attr("action","?model=stock_productinfo_productinfoAdd&action=edit");
	});
	$("#submitStatus").click(function(){
		if($(this).val()=='确  认')
			$("#form1").attr("action","?model=stock_productinfo_productinfoAdd&action=changeConfirm");
		else
			$("#form1").attr("action","?model=stock_productinfo_productinfoAdd&action=editSubmit");
		
	});
	$("#purchUserName").yxselect_user({
		hiddenId : 'purchUserCode',
		formCode : 'productPurchUser'
	});
	reloadItemProduct();

	if ($("#encrypt1").val() != "") {
		$("#encrypt").attr("checked", 'checked');
	}
	// 工程可用设置
	if ($("#esmCanUse1").val() == "1") {
		$("#esmCanUse").attr("checked", 'checked');
	}
	if ($("#isRelated").val() == 1) {
		$("#productCode").attr('readonly', true);
		$("#unitName").attr('readonly', true);
		$("#productName").attr('readonly', true);
		$("#pattern").attr('readonly', true);
		$('#msg').html("【提示:该物料已经被业务对象关联,无法修改物料名称,物料编码,单位,型号/版本号.】");
	}

	// $.formValidator.initConfig({
	// theme : "Default",
	// submitOnce : true,
	// formID : "form1",
	// onError : function(msg, obj, errorlist) {
	// alert(msg);
	// }
	// });
	//
	// /** 验证物料编码 * */
	// $("#productCode").formValidator({
	// onShow : "请输入物料编码",
	// onFocus : "物料编码不能为空",
	// onCorrect : "物料编码有效"
	// }).inputValidator({
	// min : 1,
	// max : 50,
	// onError : "物料编码不能为空，请重新输入"
	// }).ajaxValidator({
	// type : "get",
	// url : "index1.php",
	// data :
	// "model=stock_productinfo_productinfo&action=checkProInfo&id="
	// + $("#id").val(),
	// datatype : "json",
	// success : function(data) {
	// if (data == "1") {
	// return true;
	// } else {
	// return false;
	// }
	// },
	// buttons : $("#submitSave"),
	// error : function() {
	// alert("服务器没有返回数据，可能服务器忙，请重试");
	// },
	// onError : "该物料编码已重复，请更换",
	// onwait : "正在对物料编码进行合法性校验，请稍候..."
	// });
	// /** 验证物料名称 * */
	// $("#productName").formValidator({
	// onShow : "请输入物料名称",
	// onFocus : "物料名称不能为空",
	// onCorrect : "物料名称有效"
	// }).inputValidator({
	// min : 1,
	// max : 100,
	// onError : "物料名称不能为空，请输入"
	// });
	//
	// /** 验证物料类型 * */
	// $("#proTypeId").formValidator({
	// onShow : "请选择物料类型",
	// onFocus : "物料类型不能为空",
	// onCorrect : "物料类型有效"
	//
	// }).inputValidator({
	// min : 1,
	// max : 50,
	// onError : "物料类型不能为空，请重新输入"
	// });
	//
	// /** 到货周期 * */
	// $("#arrivalPeriod").formValidator({
	// onShow : "请输入...",
	// onCorrect : "格式正确"
	// }).regexValidator({
	// regExp : "num1",
	// dataType : "enum",
	// onError : "到货周期格式不正确"
	// });
	//
	// /** 保修期 * */
	// $("#warranty").formValidator({
	// onShow : "请输入...",
	// onCorrect : "格式正确"
	// }).regexValidator({
	// regExp : "num1",
	// dataType : "enum",
	// onError : "保修期格式不正确"
	// });
	/**
	 * /** 验证信息
	 */
	validate({

		"productCode" : {
			required : true

		},
		"productName" : {
			required : true
		},
		"proType" : {
			required : true

		},
		"pattern" : {
			required : true

		},
		"purchPeriod" : {
			required : true,
			custom : [ 'onlyNumber' ]

		},
		"priCost" : {
			required : true,
			custom : [ 'money' ]
		},
		"ext2" : {
			required : true

		},
		"warranty" : {
			required : true,
			custom : [ 'onlyNumber' ]

		}
	}, {
		disableButton : true
	});
	$("#productCode")
			.ajaxCheck(
					{
						url : "?model=stock_productinfo_productinfo&action=checkRepeat&id="
								+ $("#id").val(),
						alertText : "* 该编码已存在",
						alertTextOk : "* 该编码可用"
					});

    $("#ext2").ajaxCheck({
		url : "?model=stock_productinfo_productinfo&action=checkRepeat&id="+ $("#id").val(),
		alertText : "* 该K3编码已存在",
		alertTextOk : "* 该K3编码可用"
	});});

/**
 * 动态添加配置数据
 */
function addConfig() {
	var mycount = parseInt($("#configCount").val());
	var itemtable = document.getElementById("itemConfigTable");
	var i = itemtable.rows.length;
	var oTR = itemtable.insertRow([ i ]);
	oTR.align = "center";
	var oTL0 = oTR.insertCell([ 0 ]);
	oTL0.innerHTML = '<img align="absmiddle" src="images/removeline.png"  onclick="delConfigItem(this);" title="删除行">';
	var oTL1 = oTR.insertCell([ 1 ]);
	oTL1.innerHTML = mycount + 1;
	var oTL2 = oTR.insertCell([ 2 ]);
	oTL2.innerHTML = '<input type="text" class="txt"  id="cConfigName'
			+ mycount + '"  name="productinfoAdd[configItem][' + mycount
			+ '][configName]" /><input type="hidden" id="cConfigId' + mycount
			+ '" class="txt" value="0" name="productinfoAdd[configItem]['
			+ mycount + '][configId]" /><input type="hidden" id="cConfigType'
			+ mycount
			+ '" class="txt" value="proconfig" name="productinfoAdd[configItem]['
			+ mycount + '][configType]" />';
	var oTL3 = oTR.insertCell([ 3 ]);
	oTL3.innerHTML = '<input type="text" class="txtshort" name="productinfoAdd[configItem]['
			+ mycount + '][configNum]" />';
	var oTL4 = oTR.insertCell([ 4 ]);
	oTL4.innerHTML = '<input type="text" class="txt"  name="productinfoAdd[configItem]['
			+ mycount + '][explains]" />';
	document.getElementById("configCount").value = document
			.getElementById("configCount").value * 1 + 1;

	reloadConfigCount();
}

/**
 * 动态添加配件数据
 */
function addAccess() {
	var mycount = parseInt($("#accessCount").val());
	var itemtable = document.getElementById("itemAccessTable");
	var i = itemtable.rows.length;
	var oTR = itemtable.insertRow([ i ]);
	oTR.align = "center";
	var oTL0 = oTR.insertCell([ 0 ]);
	oTL0.innerHTML = '<img align="absmiddle" src="images/removeline.png"  onclick="delAccessItem(this);" title="删除行">';
	var oTL1 = oTR.insertCell([ 1 ]);
	oTL1.innerHTML = mycount + 1;
	var oTL2 = oTR.insertCell([ 2 ]);
	oTL2.innerHTML = '<input type="text" class="txtshort" id="aConfigCode'
			+ mycount + '" name="productinfoAdd[accessItem][' + mycount
			+ '][configCode]" /><input type="hidden" id="aConfigId' + mycount
			+ '" value="" name="productinfoAdd[accessItem][' + mycount
			+ '][configId]" /><input type="hidden" id="aConfigType' + mycount
			+ '" value="proaccess" name="productinfoAdd[accessItem][' + mycount
			+ '][configType]" />';
	var oTL3 = oTR.insertCell([ 3 ]);
	oTL3.innerHTML = '<input type="text" class="txt"  id="aConfigName'
			+ mycount + '" name="productinfoAdd[accessItem][' + mycount
			+ '][configName]" />';
	var oTL4 = oTR.insertCell([ 4 ]);
	oTL4.innerHTML = '<input type="text" class="readOnlyTxtItem"  id="aConfigPattern'
			+ mycount
			+ '" name="productinfoAdd[accessItem]['
			+ mycount
			+ '][configPattern]" />';
	var oTL5 = oTR.insertCell([ 5 ]);
	oTL5.innerHTML = '<input type="text" class="txtshort"  name="productinfoAdd[accessItem]['
			+ mycount + '][configNum]" />';
	var oTL6 = oTR.insertCell([ 6 ]);
	oTL6.innerHTML = '<input type="text" class="txt"  name="productinfoAdd[accessItem]['
			+ mycount + '][explains]" />';
	document.getElementById("accessCount").value = document
			.getElementById("accessCount").value * 1 + 1;
	reloadItemProduct();
	reloadAccessCount();
}

/**
 * 渲染物料清单物料信息combogrid
 */
function reloadItemProduct() {
	var itemscount = $('#accessCount').val();
	for ( var i = 0; i < itemscount; i++) {// 绑定物料编码
		$("#aConfigCode" + i).yxcombogrid_product("remove");
		$("#aConfigCode" + i).yxcombogrid_product({
			hiddenId : 'aConfigId' + i,
			nameCol : 'productCode',
			gridOptions : {
				// isTitle : true,
				showcheckbox : false,
				event : {
					'row_dblclick' : function(i) {
						return function(e, row, data) {
							$('#aConfigName' + i).val(data.productName);
							$("#aConfigPattern" + i).val(data.pattern);
						}
					}(i)
				}
			}
		})
	}
	for ( var i = 0; i < itemscount; i++) {// 绑定物料名称
		$("#aConfigName" + i).yxcombogrid_product("remove");
		$("#aConfigName" + i).yxcombogrid_product({
			hiddenId : 'aConfigId' + i,
			nameCol : 'productName',
			gridOptions : {
				showcheckbox : false,
				event : {
					'row_dblclick' : function(i) {
						return function(e, row, data) {
							$('#aConfigCode' + i).val(data.productCode);
							$("#aConfigPattern" + i).val(data.pattern);
						}
					}(i)
				}
			}
		})
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
	})
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
	})
}

function delConfigItem(obj) {
	if (confirm('确定要删除该行？')) {
		var rowNo = obj.parentNode.parentNode.rowIndex - 2;
		$(obj).parent().parent().hide();
		$(obj).parent().append(
				'<input type="hidden" name="productinfoAdd[configItem][' + rowNo
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
				'<input type="hidden" name="productinfoAdd[accessItem][' + rowNo
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
function checkSubmit(){
	if($.trim($("#priCost").val())==''){
		alert('物料成本不能为空');
		return false;
	}
	if($("#priCost").val()*1==0){
		$("#priCost").val('');
		alert('物料成本不能为0');
		return false;
	}
	
}