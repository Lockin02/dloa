var exedeptArr = new Array();

// 直接提交审批
function toApp() {
    document.getElementById('form1').action = "index1.php?model=projectmanagent_trialproject_trialproject&action=edit&act=app";
}

// 单独封装产品选择
(function($) {
	// 产品清单
	$.woo.yxeditgrid.subclass('woo.productInfoGrid', {
		objName: 'trialproject[product]',
		tableClass: 'form_in_table',
		colModel: [{
				display: 'id',
				name: 'id',
				type: 'hidden'
			}, {
				display: '产品线',
				name: 'newProLineName',
				tclass: 'readOnlyTxtNormal',
				width: 80,
				readonly: true
			}, {
				display: '产品线编号',
				name: 'newProLineCode',
				type: 'hidden'
			}, {
				display: '执行区域',
				name: 'exeDeptCode',
				type: 'select'
			}, {
				display: '执行区域Name',
				name: 'exeDeptName',
				type: 'hidden'
			}, {
				display: 'proExeDeptId',
				name: 'proExeDeptId',
				type: 'hidden'
			}, {
				display: 'proExeDeptName',
				name: 'proExeDeptName',
				type: 'hidden'
			}, {
				display: 'newExeDeptCode',
				name: 'newExeDeptCode',
				type: 'hidden'
			}, {
				display: 'newExeDeptName',
				name: 'newExeDeptName',
				type: 'hidden'
			}, {
				display: '产品名称',
				name: 'conProductName',
				tclass: 'readOnlyTxtNormal',
				readonly: true
			}, {
				display: '产品Id',
				name: 'conProductId',
				type: 'hidden'
			}, {
				display: '产品描述',
				name: 'conProductDes',
				tclass: 'txt'
			}, {
				display: '数量',
				name: 'number',
				tclass: 'txtshort',
				type: 'money',
				event: {
					blur: function() {
						countAll($(this).data("rowNum"));
					}
				}
			}, {
				display: '单价',
				name: 'price',
				tclass: 'txtshort',
				type: 'moneySix',
				event: {
					blur: function() {
						countAll($(this).data("rowNum"));
					}
				}
			}, {
				display: '金额',
				name: 'money',
				tclass: 'txtshort',
				type: 'money'
			}, {
				display: '加密配置Id',
				name: 'license',
				type: 'hidden'
			}, {
				name: 'licenseButton',
				display: '加密配置',
				event: {
					'click': function(e) {
						var rowNum = $(this).data("rowNum");
						// 获取licenseid
						var licenseObj = $("#productInfo_cmp_license" + rowNum);

						// 弹窗
						url = "?model=yxlicense_license_tempKey&action=toSelectWin" + "&licenseId=" + licenseObj.val()
						+ "&productInfoId="
						+ "productInfo_cmp_license"
						+ rowNum;
						var returnValue = showModalDialog(url, '', "dialogWidth:1000px;dialogHeight:600px;");

						if (returnValue) {
							licenseObj.val(returnValue);
						}
					}
				},
				html: '<input type="button"  value="加密配置"  class="txt_btn_a"  />',
				type: 'hidden'
			}, {
				display: '产品配置Id',
				name: 'deploy',
				type: 'hidden'
			}, {
				name: 'deployButton',
				display: '产品配置',
				type: 'statictext',
				event: {
					'click': function(e) {
						var rowNum = $(this).data("rowNum");
						// 缓存产品信息
						var conProductId = $("#productInfo_cmp_conProductId" + rowNum).val();
						var conProductName = $("#productInfo_cmp_conProductName" + rowNum).val();
						var deploy = $("#productInfo_cmp_deploy" + rowNum).val();

						if (conProductId == "") {
							alert('请先选择相关产品!');
							return false;
						} else {
							if (deploy == "") {

								var url = "?model=goods_goods_properties&action=toChoose"
										+ "&productInfoId="
										+ "productInfo_cmp_deploy"
										+ rowNum
										+ "&goodsId="
										+ conProductId
										+ "&goodsName="
										+ conProductName
										+ "&rowNum="
										+ rowNum
										+ "&componentId=productInfo"
									;

								window.open(url, '',
									'top=0,left=0,menubar=0,toolbar=0,status=1,scrollbars=1,resizable=1,width=1000px,height=600px');
							} else {

								var url = "?model=goods_goods_properties&action=toChooseAgain"
										+ "&productInfoId="
										+ "productInfo_cmp_deploy"
										+ rowNum
										+ "&goodsId="
										+ conProductId
										+ "&goodsName="
										+ conProductName
										+ "&cacheId="
										+ deploy
										+ "&rowNum="
										+ rowNum
										+ "&componentId=productInfo"
									;

								window.open(url, '',
									'top=0,left=0,menubar=0,toolbar=0,status=1,scrollbars=1,resizable=1,width=1000px,height=600px');
							}
						}

					}
				},
				html: '<input type="button"  value="产品配置"  class="txt_btn_a"  />'
			}
		],
		isAddOneRow: false,
		event: {
			clickAddRow: function(e, rowNum, g) {
				rowNum = g.allAddRowNum;
				var url = "?model=contract_contract_product&action=toProductIframe"
					+ "&componentId=productInfo&notEquSlt=1"
					+ "&rowNum="
					+ rowNum;

				window.open(url, '',
					'top=0,left=0,menubar=0,toolbar=0,status=1,scrollbars=1,resizable=1,width=1000px,height=600px');
			},
			reloadData: function (e, g, data) {
				initCacheInfo();
				// 执行部门处理
				initExeDept(data, g);
			},
			removeRow: function(e, rowNum, rowData) {
				if (typeof(rowData) != 'undefined') {
					$("#goodsDetail_" + rowData.deploy).remove();
				}
			}
		},
		addBtnClick: function() {
			return false;
		},
		setData: function(returnValue, rowNum) {
			var g = this;
			if (returnValue) {
				returnValue = returnValue[0];
				// 新增一行
				g.addRow(g.allAddRowNum);
				//产品
				g.setRowColValue(rowNum, "proExeDeptId", returnValue.proExeDeptId);
				g.setRowColValue(rowNum, "proExeDeptName", returnValue.proExeDeptName);
				g.setRowColValue(rowNum, "newExeDeptCode", returnValue.exeDeptCode);
				g.setRowColValue(rowNum, "newExeDeptName", returnValue.exeDeptName);
				g.setRowColValue(rowNum, "newProLineCode", returnValue.exeDeptCode);
				g.setRowColValue(rowNum, "newProLineName", returnValue.exeDeptName);
				initExeDeptByRow(g, rowNum);
				setProExeDeptByRow(rowNum);
				
				g.setRowColValue(rowNum, "conProductId", returnValue.goodsId, true);
				g.setRowColValue(rowNum, "conProductName", returnValue.goodsName, true);
				g.setRowColValue(rowNum, "number", returnValue.number, true);
				g.setRowColValue(rowNum, "price", returnValue.price, true);
				g.setRowColValue(rowNum, "money", returnValue.money, true);
				g.setRowColValue(rowNum, "warrantyPeriod", returnValue.warrantyPeriod, true);
				g.setRowColValue(rowNum, "deploy", returnValue.cacheId, true);
				g.setRowColValue(rowNum, "license", returnValue.licenseId, true);
				var $tr = g.getRowByRowNum(rowNum);
				returnValue.deploy = returnValue.cacheId;
				$tr.data("rowData", returnValue);
				//选择产品后动态渲染下面的配置单
				getCacheInfo(returnValue.cacheId, rowNum);
			}
		},
		reloadCache: function(cacheId, rowNum) {
			if (cacheId) {
				$("#goodsDetail_" + cacheId).remove();
				//选择产品后动态渲染下面的配置单
				getCacheInfo(cacheId, rowNum);
			}
		}
	});
})(jQuery);

// 加载下拉列表
$(function () {
    // 客户
    $("#customerName").yxcombogrid_customer({
        hiddenId: 'customerId',
        gridOptions: {
            showcheckbox: false,
            event: {
                'row_dblclick': function (e, row, data) {
                    $("#customerType").val(data.TypeOne);
                    $("#province").val(data.ProvId);// 所属省份Id
                    $("#province").trigger("change");
                    $("#provinceName").val(data.Prov);// 所属省份
                    $("#city").val(data.CityId);// 城市ID
                    $("#cityName").val(data.City);// 城市名称
                    $("#customerId").val(data.id);
                    $("#areaPrincipal").val(data.AreaLeader);// 区域负责人
                    $("#areaPrincipalId").val(data.AreaLeaderId);// 区域负责人Id
                    $("#areaName").val(data.AreaName);// 合同所属区域
                    $("#areaCode").val(data.AreaId);// 合同所属区域
                    $("#address").val(data.Address);// 客户地址
                }
            }
        }
    });

    // 产品清单
    var isView = $("#isView").val();
    if (isView == 1) {
        var yxType = "view";
    } else {
        var yxType = "edit";
    }
    $("#productInfo").productInfoGrid({
        objName: 'trialproject[product]',
        url: '?model=projectmanagent_trialproject_trialprojectEqu&action=listJson',
        param: {
            'trialprojectId': $("#trialprojectId").val()
        },
		type: yxType
    });

});

//省市
$(function () {
    var proId = $("#provinceId").val();
    var cityId = $("#cityId").val();
    $("#province").val(proId);//所属省份Id
    $("#province").trigger("change");
    $("#city").val(cityId);//城市ID
    $("#city").trigger("change");

});

//判断试用时间间隔
function timeInterval() {
    //开始时间
    var beginDate = $("#beginDate").val();
    //结束时间
    var closeDate = $("#closeDate").val();
    if (beginDate != '' && closeDate != '') {
        if (closeDate >= beginDate) {
            var days = daysBetween(beginDate, closeDate);
            if (days > 31) {
                alert("试用项目时间不得超过一个月（31天）！");
                $("#closeDate").val("");
            } else {
                $("#projectDays").blur(function () {
                    //开始时间
                    var beginDates = $("#beginDate").val();
                    //结束时间
                    var closeDates = $("#closeDate").val();
                    var newdays = daysBetween(beginDates, closeDates);
                    if ($(this).val() != "") {
                        if (!(/^(\+|-)?\d+$/.test($(this).val()))) {
                            alert("请输入正整数");
                            $(this).val("");
                        } else {
                            if ($(this).val() < 0 || $(this).val() > newdays) {
                                alert("试用项目时间不得超过一个月（" + newdays + "天）！");
                                $(this).val("");
                            }
                        }
                    }
                });
            }
        } else {
            alert("结束日期不能小于开始日期！");
            $("#closeDate").val("");
        }
    }
}

//判断工期
function timeIntervals() {
    var projectDays = $("#projectDays");
    if (projectDays.val() != "") {
        if (!(/^(\+|-)?\d+$/.test(projectDays.val()))) {
            alert("请输入正整数");
            projectDays.val("");
        } else {
            if (projectDays.val() < 0 || projectDays.val() > 31) {
                alert("试用项目时间不得超过一个月（31天）！");
                projectDays.val("");
            }
        }
    }
}

//提交验证
function toSub() {
    $("form").bind("submit", function () {
        //开始时间
        var beginDate = $("#beginDate").val();
        //结束时间
        var closeDate = $("#closeDate").val();
        //工期
        var projectDays = $("#projectDays").val();
        if (projectDays == "" && closeDate == "" && beginDate == "") {
            alert("必须填写项目周期或者工期!");
            return false;
        }
        var productInfoObj = $("#productInfo");
        var rowNum = $("#productInfo").productInfoGrid('getCurShowRowNum');
        var reFlag = 0;
        if (rowNum == '0') {
            alert("产品清单不能为空!");
            return false;
        } else {
        	// 产品线处理
        	var newProLineArr = [];
            var proLineAllSelected = true;
            productInfoObj.productInfoGrid("getCmpByCol", "newProLineCode").each(function(){
                if ($(this).val() == "") {
                    alert("请选择产品的产品线！");
                    proLineAllSelected = false;
                    return false;
                } else {
//                    var rowNum = $(this).data('rowNum');
//                    productInfoObj.productInfoGrid("getCmpByRowAndCol", rowNum, 'newProLineName').
//                        val($(this).find("option:selected").text());

                    if ($.inArray($(this).val(), newProLineArr) == -1) {
                    	newProLineArr.push($(this).val());
						if (newProLineArr.length > 1) {
							reFlag = 1;
						}
                    }
                }
            });
            if (proLineAllSelected == false) {
                return false;
            }
			if (reFlag == 1) {
				alert("试用项目不允许存在多产品线！");
				return false;
			}
            // 执行部门处理
            var exeDeptArr = [];
            var exeDeptAllSelected = true;
            productInfoObj.productInfoGrid("getCmpByCol", "exeDeptCode").each(function(){
                if ($(this).val() == "") {
                    alert("请选择产品的执行区域！");
                    exeDeptAllSelected = false;
                    return false;
                } else {
                    var rowNum = $(this).data('rowNum');
                    productInfoObj.productInfoGrid("getCmpByRowAndCol", rowNum, 'exeDeptName').
                        val($(this).find("option:selected").text());

                    if ($.inArray($(this).val(), exeDeptArr) == -1) {
                        exeDeptArr.push($(this).val());
//                        if(exeDeptArr.length > 1){
//                            reFlag = 1;
//                        }
                    }
                }
            });
            if (exeDeptAllSelected == false) {
                return false;
            }
        }
    });
}

$(function () {
    // 提交验证
    $("#form1").validationEngine({
        inlineValidation: false,
        success: function () {
            toSub();
            $("#form1").submit();//加上验证后再提交表单，解决需要连续点击两次按钮才能提交表单的bug

        },
        failure: false
    });
    /**
     * 验证信息
     */
    validate({
        "projectName": {
            required: true
        },
        "customerId": {
            required: true
        },
        "projectDescribe": {
            required: true
        },
        "executive": {
            required: true
        },
        "budgetMoney": {
            required: true
        }
    });
	// 设置执行区域
	setAreaInfo();
	// 切换板块时设置执行区域
    $("#module").change(function () {
        setAreaInfo();
    });
})


//回调插入产品信息 － 单条
function getCacheInfo(cacheId, rowNum) {
    $.ajax({
        type: "POST",
        url: "?model=goods_goods_goodscache&action=getCacheConfig",
        data: {"id": cacheId },
        async: false,
        success: function (data) {
            if (data != "") {
                $("#productInfo table tr[rowNum=" + rowNum + "]").after(data);
            }

        }
    });
//	$.ajax({
//	    type: "POST",
//	    url: "?model=goods_goods_goodscache&action=getCacheInRow",
//	    data: {"id" : cacheId },
//	    async: false,
//	    success: function(data){
//	    	if(data != ""){
//				$("#productInfo_cmp_showDeploy"+ rowNum + "").html(data);
//	    	}
//		}
//	});
}


//回调插入产品信息 - 单边/带变更
function getCacheInfoChange(cacheId, beforeCacheId, rowNum) {
    $.ajax({
        type: "POST",
        url: "?model=goods_goods_goodscache&action=getCacheChange",
        data: {"id": cacheId, "beforeId": beforeCacheId },
        async: false,
        success: function (data) {
            if (data != "") {
                $("#productInfo table tr[rowNum=" + rowNum + "]").after(data);
            }
        }
    });
}

//加载页面时渲染产品配置信息
function initCacheInfo() {
    //缓存表格对象
    var thisGrid = $("#productInfo");

    var colObj = thisGrid.productInfoGrid("getCmpByCol", "deploy");
    colObj.each(function (i, n) {
        //判断是否有变更前值
        var beforeDeployObj = $("#productInfo_cmp_beforeDeploy" + i);
        if (beforeDeployObj.length == 1) {
            if (beforeDeployObj.val()) {
                getCacheInfoChange(this.value, beforeDeployObj.val(), i);
            } else {
                getCacheInfo(this.value, i);
            }
        } else {
            getCacheInfo(this.value, i);
        }
    });
}


// 计算方法
function countAll(rowNum) {
    var beforeStr = "productInfo_cmp_";
    if ($("#" + beforeStr + "number" + rowNum).val() == ""
        || $("#" + beforeStr + "price" + rowNum + "_v").val() == "") {
        return false;
    } else {
        // 获取当前数
        thisNumber = $("#" + beforeStr + "number" + rowNum).val();
        // alert(thisNumber)

        // 获取当前单价
        thisPrice = $("#" + beforeStr + "price" + rowNum + "_v").val();
        // alert(thisPrice)

        // 计算本行金额 - 不含税
        thisMoney = accMul(thisNumber, thisPrice, 2);
        setMoney(beforeStr + "money" + rowNum, thisMoney);
    }
}

//设置产品执行区域及产品线
function initExeDept(data, g) {
	if (data) {
		for (var i = 0; i < data.length; i++) {
			initExeDeptByRow(g, i);
		}
	}
}

// 设置产品执行区域及产品线- 行
function initExeDeptByRow(g, i) {
	// 执行区域
	var productInfoObj = $("#productInfo");
	var productLineName = productInfoObj.productInfoGrid("getCmpByRowAndCol", i, 'exeDeptName').val();
	exeDeptCodeArr = getData('GCSCX');
	$('#productInfo_cmp_exeDeptCode' + i).append("<option value=''>..请选择..</option>");
    addDataToSelect(exeDeptCodeArr, 'productInfo_cmp_exeDeptCode' + i);
	productInfoObj.productInfoGrid("getCmpByRowAndCol", i, 'exeDeptCode')
		.find("option:[text='"+ productLineName + "']").attr("selected",true);
	// 产品线
//	var exeDeptCode = g.getCmpByRowAndCol(i, 'newExeDeptCode').val();
//	if (exeDeptCode != "") {
//		var exeDeptName = g.getCmpByRowAndCol(i, 'newExeDeptName').val();
//		var newProLineName = g.getCmpByRowAndCol(i, 'newProLineName').val();
//		var exeDeptCodeArr = exeDeptCode.split(',');
//		var exeDeptNameArr = exeDeptName.split(',');
//		var optionStr = "";
//
//		for (var j = 0; j < exeDeptCodeArr.length; j++) {
//			if (newProLineName == exeDeptNameArr[j] || exeDeptCodeArr.length == 1) {
//				optionStr += "<option value='" + exeDeptCodeArr[j] + "' selected='selected'>" +
//				exeDeptNameArr[j] + "</option>";
//			} else {
//				optionStr += "<option value='" + exeDeptCodeArr[j] + "'>" +
//				exeDeptNameArr[j] + "</option>";
//			}
//		}
//		g.getCmpByRowAndCol(i, 'newProLineCode').append(optionStr);
//	}
}

// 设置数据
function setData(data, componentId, rowNum) {
	if(componentId!="") {
		$("#" + componentId).productInfoGrid('setData', data, rowNum);
	}
}

// 刷新产品配置
function reloadCache(cacheId, componentId, rowNum) {
	if(componentId!="") {
		$("#" + componentId).productInfoGrid('reloadCache', cacheId, rowNum);
	}
}

/**
 * 根据商机原单归属区域获取对应的执行区域
 * 最后一次更新：2016-12-29 PMS 2313
 */
function getDeptCode(areaCode){
	var returnData = $.ajax({
		type: 'POST',
		url: "?model=system_region_region&action=ajaxChkExeDept",
		data: {
			areaCode: areaCode,
		},
		async: false,
		success: function (data) {
		}
	}).responseText;
	returnData = eval("(" + returnData + ")");
	return returnData;
}

// 设置执行区域
function setAreaInfo() {
	// 修改规则,与合同的统一,都是用销售区域带出相应的执行区域 PMS2313 2016-12-29
	var areaCode = $("#areaCode").val();//商机归属区域ID
	var exeDeptInfo = getDeptCode(areaCode);
	if(exeDeptInfo){
		$('#exeDeptCode').val(exeDeptInfo[0].exeDeptCode);
		$('#exeDeptName').val(exeDeptInfo[0].exeDeptName);
	}

	/*var customerType = $("#customerType").val();
	var province = $("#provinceId").val();
	var module = $("#module").val();
	if (customerType != '' && province != '' && module != '') {
	    var returnValue = $.ajax({
	        type: 'POST',
	        url: "?model=system_region_region&action=ajaxConRegion",
	        data: {
	            customerType: customerType,
	            province: province,
	            module: module
	        },
	        async: false,
	        success: function (data) {
	        }
	    }).responseText;
	    returnValue = eval("(" + returnValue + ")");
	    if (returnValue) {
	        $("#exeDeptCode").val(returnValue[0].exeDeptCode);// 执行区域编号
	        $("#exeDeptName").val(returnValue[0].exeDeptName);// 执行区域
	    }else{
            $("#exeDeptCode").val("");// 执行区域编号
            $("#exeDeptName").val("");// 执行区域
	    }
	} else {
	    return false;
	}*/
}

// 设置某个产品执行区域
function setProExeDeptByRow(i) {
	var exeDeptCode = $("#exeDeptCode").val();
	var exeDeptName = $("#exeDeptName").val();
	if (exeDeptCode !== undefined && exeDeptCode !== "") {
		var productInfoObj = $("#productInfo");
		productInfoObj.productInfoGrid("getCmpByRowAndCol", i, 'exeDeptCode')
			.find("option:[value='"+ exeDeptCode + "']").attr("selected",true);
		productInfoObj.productInfoGrid("getCmpByRowAndCol", i, 'exeDeptName').val(exeDeptName);
	} else {
		return false;
	}
}