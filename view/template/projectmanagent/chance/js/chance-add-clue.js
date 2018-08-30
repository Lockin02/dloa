function toApproval(){
	document.getElementById('form1').action = "index1.php?model=projectmanagent_chance_chance&action=add&act=app";

}

/**
 * license
 * @type
 */

function License(licenseId){

	var licenseVal = $("#" + licenseId ) .val();
	if( licenseVal == ""){
		//如果为空,则不传值
		showThickboxWin('?model=yxlicense_license_tempKey&action=toSelect'
		    + '&focusId=' + licenseId
			+ '&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=900');
	}else{
		//不为空则传值
		showThickboxWin('?model=yxlicense_license_tempKey&action=toSelect'
		    + '&focusId=' + licenseId
			+ '&licenseId=' + licenseVal
			+ '&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=900');
	}
}

//反写id
function setLicenseId(licenseId,thisVal){
	$('#' + licenseId ).val(thisVal);
}


$(function() {
  //组织机构人员选择
	$("#trackman").yxselect_user({
		hiddenId : 'trackmanId',

     mode : 'check'
	 });
	 });
//类型属性
function Type() {
               var chanceTypeIfno = $("#chanceType").val();
                 $('#orderNatureXS').empty();
               if ( chanceTypeIfno == 'SJLX-XSXS'){

	    				addDataToSelect(orderNatureCodeArrXS, 'orderNatureXS');
               }else if (chanceTypeIfno == 'SJLX-FWXM'){
                         addDataToSelect(orderNatureCodeArrFW, 'orderNatureXS');
               }else if (chanceTypeIfno == 'SJLX-ZL'){
                         addDataToSelect(orderNatureCodeArrZL, 'orderNatureXS');
               }else if (chanceTypeIfno == 'SJLX-YF'){
                        addDataToSelect(orderNatureCodeArrYF, 'orderNatureXS');
               }
      }

$(function() {


	orderNatureCodeArrXS = getData('XSHTSX');
	    addDataToSelect(orderNatureCodeArrXS, 'orderNatureXS');
	orderNatureCodeArrFW = getData('FWHTSX');
//	    addDataToSelect(orderNatureCodeArr, 'orderNatureFW');
	orderNatureCodeArrZL = getData('ZLHTSX');
//	    addDataToSelect(orderNatureCodeArr, 'orderNatureZL');
	orderNatureCodeArrYF = getData('YFHTSX');
//	    addDataToSelect(orderNatureCodeArr, 'orderNatureYF');
});

// 日期联动
$(function() {
	$('#deliveryDate').bind('focusout', function() {
		var thisDate = $(this).val();
        deliveryDate = $('#deliveryDate').val();
		$.each($(':input[id^="projArraDate"]'), function(i, n) {
			$(this).val(thisDate);
		})
	});

});

// 金额相加
function countAll(){
	var invnumber = $('#productNumber').val();
	var incomeMoney = $('#money').val();
	var thisAmount = 0;
	var allAmount = 0;
	for(var i = 1;i <= invnumber;i++){
		thisAmount = $('#money' + i).val() * 1;
		if(!isNaN(thisAmount)){
			allAmount += thisAmount;
		}
	}

	$('#orderMoney').val(allAmount);

}

////商机编号唯一性验证
//function check_code(code) {
//	if (code != '') {
//		$.get('index1.php', {
//			model : 'projectmanagent_chance_chance',
//			action : 'checkRepeat',
//            ajaxChanceCode : code
//		}, function(data) {
//			if (data != '') {
//				$('#_chanceCode').html('已存在的编号！');
//
//			} else {
//				$('#_chanceCode').html('编号可用！');
//
//			}
//		})
//	}
//	return false;
//}

$(document).ready(function() {
    $.formValidator.initConfig({
    	formid: "form1",
        //autotip: true,
        onerror: function(msg) {
            //alert(msg);
        },
        onsuccess: function (msg){
            var cusId = $("#customerId").val();
            if(cusId == ''){
                alert ("客户信息在客户列表中不存在，请从新选择客户");
                return false;
            }
                return true;
        }

    });
 /**
 * 客户名称
 */
	$("#customerName").formValidator({
		onshow : "请选择客户名称",
		onfocus : "名称至少2个字符，最多50个字符",
		oncorrect : "您输入的名称有效"
	}).inputValidator({
		min : 2,
		max : 50,
		empty : {
			leftempty : false,
			rightempty : false,
			emptyerror : "名称两边不能为空"
		},
		onerror : "您输入的名称不合法，请重新输入"
	});
/**
 *商机名称
 */
$("#chanceName").formValidator({
		onshow : "请输入商机名称",
		onfocus : "名称至少2个字符，最多50个字符",
		oncorrect : "您输入的名称有效"
	}).inputValidator({
		min : 2,
		max : 50,
		empty : {
			leftempty : false,
			rightempty : false,
			emptyerror : "名称两边不能为空"
		},
		onerror : "您输入的名称不合法，请重新输入"
	});
});


// 选择省份
$(function() {
	$("#customerProvince").yxcombogrid_province({
		hiddenId : 'provinceId',
		gridOptions : {
			showcheckbox : false
		}
	});
	//商机阶段
		chanceStageArr = getData('SJJD');
	    addDataToSelect(chanceStageArr, 'chanceStage');
//		addDataToSelect(invoiceTypeArr, 'invoiceListType1');
	//商机等级
		chanceStageArr = getData('SJDJ');
	    addDataToSelect(chanceStageArr, 'chanceLevel');
//		addDataToSelect(invoiceTypeArr, 'invoiceListType1');
	//商机类型
		chanceStageArr = getData('SJLX');
	    addDataToSelect(chanceStageArr, 'chanceType');
});

//客户联系人
function reloadLinkman(i) {
	$("#linkmanName"+i).yxcombogrid_linkman("remove");
	$("#linkmanName"+i).yxcombogrid_linkman("showCombo");
	$("#linkmanName" + i).yxcombogrid_linkman({
		gridOptions : {
			reload : true,
			showcheckbox : false,
			param:{'customerId':$("#customerId").val()},
			event : {
				'row_dblclick' : function(i){
						return function(e, row, data) {
						$("#customerId"+i).val(data.customerId);
						$("#linkmanId"+i).val(data.id);
						$("#mobileTel"+i).val(data.mobile);
						$("#email"+i).val(data.email);
					};
			  	}(i)
			}
		}
	});
}

$(function() {

	$("#provincecity").yxcombogrid_province({
		hiddenId : 'provinceId',
		gridOptions : {
			showcheckbox : false
		}
	});
});

$(function() {

	var html='<tr id="linkTab_1">'+
			'<td>1'+
			'</td>'+
			'<td>'+
				'<input class="text" type="hidden" name="chance[linkman][1][linkmanId]" id="linkmanId1"/>'+
				'<input class="text" type="hidden" name="chance[linkman][1][customerId]" id="customerId1"/>'+
				'<input class="txt" type="text" name="chance[linkman][1][linkmanName]" id="linkmanName1" title="双击可以添加联系人" onclick="reloadLinkman(\'1\');">'+
			'</td>'+
			'<td>'+
				'<input class="txt" type="text" name="chance[linkman][1][mobileTel]" id="mobileTel1" onchange="tel(1);"/>'+
			'</td>'+
			'<td>'+
				'<input class="txt" type="text" name="chance[linkman][1][email]" id="email1" onchange="Email(1);"/>'+
			'</td>'+
			'<td>'+
				'<select class="" type="text" name="chance[linkman][1][roleCode]" id="roleCode1">'+$("#role").val()+'</select>'+
			'</td>'+
			'<td>'+
				'<input class="" type="checkbox" name="chance[linkman][1][isKeyMan]" id="isKeyMan1"/>'+
			'</td>'+
			'<td>'+
				'<img src="images/closeDiv.gif" onclick="mydel(this,\'mylink\')" title="删除行"/>'+
			'</td>'+'</tr>';
	$("#customerName").yxcombogrid_customer({
		hiddenId : 'customerId',
		gridOptions : {
			showcheckbox : false,
			// param :{"contid":$('#contractId').val()},
			event : {
				'row_dblclick' : function(e, row, data) {
					var getGrid = function() {
						return $("#customerLinkman")
								.yxcombogrid_linkman("getGrid");
					}
					var getGridOptions = function() {
						return $("#customerLinkman")
								.yxcombogrid_linkman("getGridOptions");
					}
					if (getGrid().reload) {
						getGridOptions().param = {
							customerId : data.id
						};
						getGrid().reload();
					} else {
						getGridOptions().param = {
							customerId : data.id
						}
					}

					$("#customerType").val(data.TypeOne);
					$("#customerProvince").val(data.Prov);
					$("#customerId").val(data.id);
					// $("#customerLinkman").yxcombogrid('grid').param={}
					// $("#customerLinkman").yxcombogrid('grid').reload;
					$("#linkmanName1").yxcombogrid_linkman("remove");
					$("#linkmanName1").yxcombogrid_linkman("showCombo");
//					$("#mylink").html("");
//					$("#mylink").html(html);
					$("#linkmanName1").yxcombogrid_linkman({
							hiddenId : 'linkmanId1',
							gridOptions : {
								reload : true,
								showcheckbox : false,
								param:{'customerId':data.id},
								event : {
									'row_dblclick' : function(e, row, data) {
										$("#customerId1").val(data.customerId);
										$("#mobileTel1").val(data.mobile);
										$("#email1").val(data.email);
									}
								}
							}
					});
				}
			}
		}
	});
	// customerId = $("#customerId").val()
	// $("#customerId").val(customerId)
	$("#customerLinkman").yxcombogrid_linkman({
		hiddenId : 'customerLinkmanId',
		gridOptions : {
			reload : true,
			showcheckbox : false,
			// param : param,
			event : {
				'row_dblclick' : function(e, row, data) {
					// alert( $('#customerId').val() );
					// unset($('#customerId'));
					$("#customerName").val(data.customerName);
					$("#customerId").val(data.customerId);
					$("#customerTel").val(data.mobile);
					$("#customerEmail").val(data.email);
				}
			}
		}
	});


});

/***********************************************************************************************/
/*
 * 双击查看产品清单 物料 配置信息
 */
function conInfo(productId){
	    var proId = $("#"+productId).val();
	    if (proId == ''){
	        alert("【请选择物料】");
	    }else {
	    	 showThickboxWin('?model=stock_productinfo_configuration&action=viewConfig&productId='
                      + proId
                      +'&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=600');
	    }

	}
$(function() {
	temp = $('#productNumber').val();
	for(var i=1;i<=temp;i++){
	$("#productNumber" + i).yxcombogrid_product({
		hiddenId : 'productId'+i,
		gridOptions : {
			showcheckbox : false,
				event : {
					'row_dblclick' : function(i){
						return function(e, row, data) {
							$("#productName" + i).val(data.productName);
							$("#productModel" + i).val(data.pattern);
							$("#unitName" + i).val(data.unitName);
							$("#warrantyPeriod" + i).val(data.warranty);
							$("#amount" + i).val(1);

							var encrypt = data.encrypt;
							if(encrypt == 'on'){
								alert("此物料已设置加密必填属性，请填写加密配置");
								License('chanEqulicenseId' + i);
							}
							var allocation = data.allocation;
								$("#isCon" + i).val("isCon_" + i)
								       $.get('index1.php', {
											model : 'projectmanagent_chance_chance',
											action : 'ajaxConfig',
											id : data.id,
											trId : "isCon_" + i,
											Num : i,
											dataType : "html"
										}, function(pro) {
											if (pro) {
												$("#equTab_"+i).after(pro);
												var rowNums = $("tr[name^='equTab_']").length * 1 + $("tr[id^='equTab_']").length * 1;
												document.getElementById(countNumP).value = document.getElementById(countNumP).value * 1 + 1;
												recount("myequ");
											} else {
											}
										})
						}
					}(i)
				}
			}
		});
	}
});

$(function() {
	temp = $('#productNumber').val();
	for(var i=1;i<=temp;i++){
	$("#productName" + i).yxcombogrid_productName({
		hiddenId : 'productId'+i,
		gridOptions : {
			showcheckbox : false,
				event : {
					'row_dblclick' : function(i){
						return function(e, row, data) {
							$("#productNumber" + i).val(data.productCode);
							$("#productModel" + i).val(data.pattern);
							$("#unitName" + i).val(data.unitName);
                            $("#warrantyPeriod" + i).val(data.warranty);
                            $("#amount" + i).val(1);

							var encrypt = data.encrypt;
							if(encrypt == 'on'){
								alert("此物料已设置加密必填属性，请填写加密配置");
								License('chanEqulicenseId' + i);
							}
							var allocation = data.allocation;
								$("#isCon" + i).val("isCon_" + i)
								       $.get('index1.php', {
											model : 'projectmanagent_chance_chance',
											action : 'ajaxConfig',
											id : data.id,
											trId : "isCon_" + i,
											Num : i,
											dataType : "html"
										}, function(pro) {
											if (pro) {
												$("#equTab_"+i).after(pro);
												var rowNums = $("tr[name^='equTab_']").length * 1 + $("tr[id^='equTab_']").length * 1;
												document.getElementById(countNumP).value = document.getElementById(countNumP).value * 1 + 1;
												recount("myequ");
											} else {
											}
										})
						}
					}(i)
				}
			}
		});
	}
});
/** ********************产品信息************************ */
function dynamic_add(packinglist, countNumP) {
	deliveryDate = $('#deliveryDate').val();
	// 获取当前行数 ,用于行序号
	var rowNums = $("tr[name^='equTab_']").length * 1 + $("tr[id^='equTab_']").length * 1;
	// 获取当前隐藏值,用于做id增长
	mycount = $('#' + countNumP).val() * 50 + 1;
	// 缓存插入表格
	var packinglist = document.getElementById(packinglist);
	// 插入行
	oTR = packinglist.insertRow([rowNums]);
	oTR.id = "equTab_" + mycount;
	// 当前行号
	i = rowNums + 1;

	var oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = i;
	var oTL1 = oTR.insertCell([1]);
	oTL1.innerHTML = "<input type='text' id='productNumber" + mycount + "' class='txtmiddle' name='chance[chanceequ][" + mycount + "][productNumber]' >";
	// 单选产品
	$("#productNumber" + mycount).yxcombogrid_product({
		hiddenId : 'productId' + mycount,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(mycount){
						return function(e, row, data) {
						$("#productName"+mycount).val(data.productName);
						$("#productModel"+mycount).val(data.pattern);
						$("#unitName" + mycount).val(data.unitName);
						$("#warrantyPeriod" +mycount).val(data.warranty);
						$("#amount" + mycount + "_v").val(1);
						$("#amount" + mycount).val(1);

						var encrypt = data.encrypt;
							if(encrypt == 'on'){
								alert("此物料已设置加密必填属性，请填写加密配置");
								License('licenseId' + mycount);
							}
					    var allocation = data.allocation;
								 $("#isCon" + mycount).val("isCon_" + mycount);
								       $.get('index1.php', {
											model : 'projectmanagent_chance_chance',
											action : 'ajaxConfig',
											id : data.id,
											trId : "isCon_" + mycount,
											Num : mycount,
											dataType : "html"
										}, function(pro) {
											if (pro) {
												$("#equTab_"+mycount).after(pro);
												var rowNums = $("tr[name^='equTab_']").length * 1 + $("tr[id^='equTab_']").length * 1;
												document.getElementById(countNumP).value = document.getElementById(countNumP).value * 1 + 1;
												recount("myequ");
											} else {
											}
										})
					};
			  	}(mycount)
			}
		}
	});
	var oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML = "<input type='hidden' id='productId" + mycount + "'  name='chance[chanceequ][" + mycount + "][productId]'/>" +
			  "<input id='productName" + mycount + "' type='text' class='txt' name='chance[chanceequ][" + mycount + "][productName]' />"+
			  "&nbsp<img src='images/icon/icon105.gif' onclick='conInfo(\"productId"+ mycount +"\");' title='查看配置信息'/>";
	$("#productName" + mycount).yxcombogrid_productName({
		hiddenId : 'productId' + mycount,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(mycount){
						return function(e, row, data) {
						$("#productNumber"+mycount).val(data.productCode);
						$("#productModel"+mycount).val(data.pattern);
						$("#unitName" + mycount).val(data.unitName);
						$("#warrantyPeriod" + mycount).val(data.warranty);
						$("#amount" + mycount + "_v").val(1);
						$("#amount" + mycount).val(1);

						var encrypt = data.encrypt;
							if(encrypt == 'on'){
								alert("此物料已设置加密必填属性，请填写加密配置");
								License('licenseId' + mycount);
							}
					    var allocation = data.allocation;
								 $("#isCon" + mycount).val("isCon_" + mycount);
								       $.get('index1.php', {
											model : 'projectmanagent_chance_chance',
											action : 'ajaxConfig',
											id : data.id,
											trId : "isCon_" + mycount,
											Num : mycount,
											dataType : "html"
										}, function(pro) {
											if (pro) {
												$("#equTab_"+mycount).after(pro);
												var rowNums = $("tr[name^='equTab_']").length * 1 + $("tr[id^='equTab_']").length * 1;
												document.getElementById(countNumP).value = document.getElementById(countNumP).value * 1 + 1;
												recount("myequ");
											} else {
											}
										})
					};
			  	}(mycount)
			}
		}
	});
	var oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML = "<input id='productModel" + mycount + "' type='text' class='txtmiddle' name='chance[chanceequ][" + mycount + "][productModel]' readonly>";
    var oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML = "<input class='txtshort' type='text' name='chance[chanceequ][" + mycount + "][amount]' id='amount" + mycount + "'  />";
    var oTL5 = oTR.insertCell([5]);
    oTL5.innerHTML = "<input class='txtshort' type='text' name='chance[chanceequ]["+ mycount +"][unitName]' id='unitName"+ mycount +"' />"
	var oTL6 = oTR.insertCell([6]);
	oTL6.innerHTML = "<input class='txtshort formatMoney' type='text' name='chance[chanceequ][" + mycount + "][price]' id='price" + mycount + "' />";
	var oTL7 = oTR.insertCell([7]);
	oTL7.innerHTML = "<input class='txtshort formatMoney'  type='text' name='chance[chanceequ][" + mycount + "][money]' id='money" + mycount + "' />";
	var oTL8 = oTR.insertCell([8]);
	oTL8.innerHTML = "<input class='txtshort' type='text' name='chance[chanceequ]["+ mycount +"][warrantyPeriod]' id='warrantyPeriod"+ mycount +"' />";
	var oTL9 = oTR.insertCell([9]);
	oTL9.innerHTML = "<input  id='projArraDate" + mycount + "'type='text'  class='txtshort' name='chance[chanceequ][" + mycount + "][projArraDate]' value='"+ deliveryDate +"'  onfocus='WdatePicker()'>"
    var oTL10 = oTR.insertCell([10]);
	oTL10.innerHTML = "<input type='hidden' id='chanEqulicenseId" + mycount + "' name='chance[chanceequ][" + mycount + "][license]'/>" +
			          "<input type='button' name='' class='txt_btn_a' value='配置' onclick='License(\"chanEqulicenseId" + mycount + "\");'>"+
			          "<input type='hidden' name='chance[chanceequ][" + mycount + "][isCon]'id='isCon" + mycount + "'>"+
	                  "<input type='hidden' name='chance[chanceequ][" + mycount + "][isConfig]' id='isConfig" + mycount + "'>";
	var oTL11 = oTR.insertCell([11]);
	oTL11.innerHTML = "<img src='images/closeDiv.gif' onclick='mydel(this,\"" + packinglist.id + "\")' title='删除行'>";


	document.getElementById(countNumP).value = document.getElementById(countNumP).value * 1 + 1;
//千分位带计算
    createFormatOnClick('amount'+mycount,'amount' +mycount ,'price' +mycount ,'money'+ mycount);
    createFormatOnClick('price'+mycount,'amount' +mycount ,'price'+mycount,'money'+ mycount);
    createFormatOnClick('money'+mycount,'amount' +mycount ,'price'+mycount,'money'+ mycount);
}


/************************自定义清单***********************************/

function pre_add(mycustom, countNum) {
	mycount = document.getElementById(countNum).value * 1 + 1;
	var mycustom = document.getElementById(mycustom);
	i = mycustom.rows.length;
	oTR = mycustom.insertRow([i]);
	oTR.className = "TableData";
	oTR.align = "center";
	oTR.height = "30px";
	j = i + 1;
	oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = j;
	var oTL1 = oTR.insertCell([1]);
	oTL1.innerHTML = "<input class='txtshort' type='text' name='chance[customizelist][" + mycount
			+ "][productCode]' id='PequID" + mycount
			+ "' value='' size='10' maxlength='40'/>";
	var oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML = "<input class='txtshort' type='text' name='chance[customizelist][" + mycount
			+ "][productName]' id='PequName" + mycount
			+ "' value='' size='15' maxlength='20'/>";
	var oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML = "<input class='txtshort' type='text' name='chance[customizelist][" + mycount
			+ "][productModel]' id='PreModel" + mycount
			+ "' value='' size='10' maxlength='40'/>";
	var oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML = "<input class='txtshort' type='text' name='chance[customizelist][" + mycount
			+ "][number]' id='PreAmount" + mycount + "' />";
	var oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML = "<input class='txtshort' type='text' name='chance[customizelist][" + mycount
			+ "][price]' id='PrePrice" + mycount + "' />";
	var oTL6 = oTR.insertCell([6]);
	oTL6.innerHTML = "<input class='txtshort' type='text' name='chance[customizelist][" + mycount
			+ "][money]' id='CountMoney" + mycount + "' />";
	var oTL7 = oTR.insertCell([7]);
	oTL7.innerHTML = "<input class='txtshort' type='text' name='chance[customizelist][" + mycount
			+ "][projArraDT]' id='PreDeliveryDT" + mycount
			+ "' size='10' onfocus='WdatePicker()'>";
	var oTL8 = oTR.insertCell([8]);
	oTL8.innerHTML = "<input class='txt' type='text' name='chance[customizelist][" + mycount
			+ "][remark]' id='PRemark" + mycount
			+ "' value='' size='18' maxlength='100'/>";

	var oTL9 = oTR.insertCell([9]);
	oTL9.innerHTML = "<input type='hidden' id='chanCuslicenseId" + mycount + "' name='chance[customizelist][" + mycount + "][license]'/>" +
			"<input type='button' name='' class='txt_btn_a' value='配置' onclick='License(\"chanCuslicenseId" + mycount + "\");'>";
	var oTL10 = oTR.insertCell([10]);
	oTL10.innerHTML = "<img src='images/closeDiv.gif' onclick='mydel(this,\""
			+ mycustom.id + "\")' title='删除行'>";
	document.getElementById(countNum).value = document.getElementById(countNum).value
			* 1 + 1;

	//千分位带计算
    createFormatOnClick('PreAmount'+mycount,'PreAmount' +mycount ,'PrePrice' +mycount ,'CountMoney'+ mycount);
	createFormatOnClick('PrePrice'+mycount,'PreAmount'+mycount,'PrePrice'+mycount,'CountMoney'+mycount);
	createFormatOnClick('CountMoney'+mycount,'PreAmount'+mycount,'PrePrice'+mycount,'CountMoney'+mycount);
}
	// ************************客户联系人*************************
function link_add(mypay, countNum) {
	var customerId=$("#customerId").val();
	mycount = document.getElementById(countNum).value * 1 + 1;
	var mypay = document.getElementById(mypay);
	i = mypay.rows.length;

	j = i +1 ;
	oTR = mypay.insertRow([i]);
	oTR.id = "linkTab_" + i;
	oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = j;
	oTL1 = oTR.insertCell([1]);
	oTL1.innerHTML = "<input class='txt' type='hidden' name='chance[linkman][" + mycount
			+ "][linkmanId]' id='linkmanId" + mycount + "'/><input class='txt' type='hidden' name='chance[linkman][" + mycount
			+ "][customerId]' id='customerId" + mycount + "'/>"
			+ "<input class='txt' type='text' name='chance[linkman][" + mycount
			+ "][linkmanName]' id='linkmanName" + mycount + "' title='双击可以添加联系人' onclick=\"reloadLinkman('linkman"+mycount+"\');\"/>";

	/**
	 * 客户联系人
	 */
	$("#linkmanName" + mycount).yxcombogrid_linkman({
		gridOptions : {
			reload : true,
			showcheckbox : false,
			param:{'customerId':$("#customerId").val()},
			event : {
				'row_dblclick' : function(mycount){
						return function(e, row, data) {
//						alert( "linkman" + mycount );
						$("#customerId"+mycount).val(data.customerId);
						$("#linkmanId"+mycount).val(data.id);
						$("#mobileTel"+mycount).val(data.mobile);
						$("#email"+mycount).val(data.email);
					};
			  	}(mycount)
			}
		}
	});


	oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML = "<input class='txt' type='text' name='chance[linkman][" + mycount
			+ "][mobileTel]' id='mobileTel" + mycount + "'onchange='tel(" + mycount + ")'/>";
	oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML = "<input class='txt' type='text' name='chance[linkman][" + mycount
			+ "][email]' id='email" + mycount + "'onchange='Email(" + mycount + ")'/>";
	oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML = "<select class=''  name='chance[linkman][" + mycount
			+ "][roleCode]' id='roleCode" + mycount + "'>"+$("#role").val()+"</select>";
	oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML = "<input class='' type='checkbox' name='chance[linkman][" + mycount
			+ "][isKeyMan]' id='isKeyMan" + mycount + "'/>";
	oTL6 = oTR.insertCell([6]);
	oTL6.innerHTML = "<img src='images/closeDiv.gif' onclick='mydel_link(this,\""
			+ mypay.id + "\")' title='删除行'>";
	document.getElementById(countNum).value = document.getElementById(countNum).value
			* 1 + 1;
}


/** ********************删除动态表单************************ */
function mydel(obj, mytable) {
	if (confirm('确定要删除该行？')) {
		var rowSize = $("#" + mytable).children().length;
		var rowNo = obj.parentNode.parentNode.rowIndex * 1 - 2;
		var mytable = document.getElementById(mytable);
		var objA = obj.parentNode.parentNode;
		if($(objA).find("input[id^='isConfig']").val() == ''){
		   $("tr[parentRowId="+$(objA).find("input[id^='isCon']").val()+"]").remove();
		}
		mytable.deleteRow(rowNo);
		var myrows = rowSize - 1;
		for (i = 0; i < myrows; i++) {
			mytable.rows[i].childNodes[0].innerHTML = i + 1;
		}
	}
}
function mydel_link(obj, mytable) {
	if (confirm('确定要删除该行？')) {
		var rowNo = obj.parentNode.parentNode.rowIndex * 1 -1;
		var mytable = document.getElementById(mytable);
		mytable.deleteRow(rowNo-1);
		var myrows = mytable.rows.length;
		for (i = 0; i < myrows; i++) {
			mytable.rows[i].childNodes[0].innerHTML = i+1;
		}
	}
}
/**
 * 重新计算列序号
 * @param {}
 * name
 */
function recount(mytable) {
	var mytable = document.getElementById(mytable);

	var myrows = mytable.rows.length;
	for (i = 0; i < myrows; i++) {
		mytable.rows[i].childNodes[0].innerHTML = i + 1;
	}
}
/** *****************隐藏计划******************************* */
function dis(name) {
	var temp = document.getElementById(name);
	if (temp.style.display == '')
		temp.style.display = "none";
	else if (temp.style.display == "none")
		temp.style.display = '';
}
