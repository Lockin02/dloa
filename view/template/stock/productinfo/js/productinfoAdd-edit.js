$(function() {
	if($("#methodType").val()=='confirm'){
		$("#submitStatus").val('ȷ  ��');
	}
	$("#submitSave").click(function(){
		$("#form1").attr("action","?model=stock_productinfo_productinfoAdd&action=edit");
	});
	$("#submitStatus").click(function(){
		if($(this).val()=='ȷ  ��')
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
	// ���̿�������
	if ($("#esmCanUse1").val() == "1") {
		$("#esmCanUse").attr("checked", 'checked');
	}
	if ($("#isRelated").val() == 1) {
		$("#productCode").attr('readonly', true);
		$("#unitName").attr('readonly', true);
		$("#productName").attr('readonly', true);
		$("#pattern").attr('readonly', true);
		$('#msg').html("����ʾ:�������Ѿ���ҵ��������,�޷��޸���������,���ϱ���,��λ,�ͺ�/�汾��.��");
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
	// /** ��֤���ϱ��� * */
	// $("#productCode").formValidator({
	// onShow : "���������ϱ���",
	// onFocus : "���ϱ��벻��Ϊ��",
	// onCorrect : "���ϱ�����Ч"
	// }).inputValidator({
	// min : 1,
	// max : 50,
	// onError : "���ϱ��벻��Ϊ�գ�����������"
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
	// alert("������û�з������ݣ����ܷ�����æ��������");
	// },
	// onError : "�����ϱ������ظ��������",
	// onwait : "���ڶ����ϱ�����кϷ���У�飬���Ժ�..."
	// });
	// /** ��֤�������� * */
	// $("#productName").formValidator({
	// onShow : "��������������",
	// onFocus : "�������Ʋ���Ϊ��",
	// onCorrect : "����������Ч"
	// }).inputValidator({
	// min : 1,
	// max : 100,
	// onError : "�������Ʋ���Ϊ�գ�������"
	// });
	//
	// /** ��֤�������� * */
	// $("#proTypeId").formValidator({
	// onShow : "��ѡ����������",
	// onFocus : "�������Ͳ���Ϊ��",
	// onCorrect : "����������Ч"
	//
	// }).inputValidator({
	// min : 1,
	// max : 50,
	// onError : "�������Ͳ���Ϊ�գ�����������"
	// });
	//
	// /** �������� * */
	// $("#arrivalPeriod").formValidator({
	// onShow : "������...",
	// onCorrect : "��ʽ��ȷ"
	// }).regexValidator({
	// regExp : "num1",
	// dataType : "enum",
	// onError : "�������ڸ�ʽ����ȷ"
	// });
	//
	// /** ������ * */
	// $("#warranty").formValidator({
	// onShow : "������...",
	// onCorrect : "��ʽ��ȷ"
	// }).regexValidator({
	// regExp : "num1",
	// dataType : "enum",
	// onError : "�����ڸ�ʽ����ȷ"
	// });
	/**
	 * /** ��֤��Ϣ
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
						alertText : "* �ñ����Ѵ���",
						alertTextOk : "* �ñ������"
					});

    $("#ext2").ajaxCheck({
		url : "?model=stock_productinfo_productinfo&action=checkRepeat&id="+ $("#id").val(),
		alertText : "* ��K3�����Ѵ���",
		alertTextOk : "* ��K3�������"
	});});

/**
 * ��̬�����������
 */
function addConfig() {
	var mycount = parseInt($("#configCount").val());
	var itemtable = document.getElementById("itemConfigTable");
	var i = itemtable.rows.length;
	var oTR = itemtable.insertRow([ i ]);
	oTR.align = "center";
	var oTL0 = oTR.insertCell([ 0 ]);
	oTL0.innerHTML = '<img align="absmiddle" src="images/removeline.png"  onclick="delConfigItem(this);" title="ɾ����">';
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
 * ��̬����������
 */
function addAccess() {
	var mycount = parseInt($("#accessCount").val());
	var itemtable = document.getElementById("itemAccessTable");
	var i = itemtable.rows.length;
	var oTR = itemtable.insertRow([ i ]);
	oTR.align = "center";
	var oTL0 = oTR.insertCell([ 0 ]);
	oTL0.innerHTML = '<img align="absmiddle" src="images/removeline.png"  onclick="delAccessItem(this);" title="ɾ����">';
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
 * ��Ⱦ�����嵥������Ϣcombogrid
 */
function reloadItemProduct() {
	var itemscount = $('#accessCount').val();
	for ( var i = 0; i < itemscount; i++) {// �����ϱ���
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
	for ( var i = 0; i < itemscount; i++) {// ����������
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
 * ����������Ϣ���к�
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
 * ��������嵥���к�
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
	if (confirm('ȷ��Ҫɾ�����У�')) {
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
	if (confirm('ȷ��Ҫɾ�����У�')) {
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
function checkSubmit(){
	if($.trim($("#priCost").val())==''){
		alert('���ϳɱ�����Ϊ��');
		return false;
	}
	if($("#priCost").val()*1==0){
		$("#priCost").val('');
		alert('���ϳɱ�����Ϊ0');
		return false;
	}
	
}