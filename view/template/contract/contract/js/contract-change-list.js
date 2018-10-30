/**
 * 发货完成时间判断
 */

$(function () {
	//声明一个全局变量，用来判断当前应该加载源单记录还是临时变更记录
	//合同id等于源单id，则加载源单记录，否则加载临时变更记录
	isTemp = $("#contractId").val() == $("#oldId").val() ? '0' : '1';
	
    var deliveryStatus = $("#DeliveryStatus").val();
    var outstockDate = $("#outstockDate").val();
    var Nowdate = formatDate(new Date());

    var days = DateDiff(outstockDate, Nowdate);
    // 2018-07-02 PMS 708 销售合同发货超过6个月，产品信息不能修改的限制取消。
    // if ((deliveryStatus == 'YFH' || deliveryStatus == 'TZFH') && days > '180') {
    //     productListHtml("view");
    //     $("#changeTips").html("<img src='images/icon/icon095.gif' />提醒：此合同已完成发货超过六个月，按规定在不改变合同金额的前提下，将无法变更产品内容");
    // } else {
        productListHtml("edit");
    // }


    $("#contractMoney_v").change(function () {
        deliveryCon();
    });
});

//发货完成期限控制方法
function deliveryCon(){
    var oldMoney = moneyFormat2($("#oldMoney").val());
    // if (this.value != oldMoney) {
        $("#productInfo").productInfoGrid("remove");
        productListHtml("edit");
    // } else {
    //     $("#productInfo").productInfoGrid("remove");
    //     productListHtml("view");
    //     $("#changeTips").html("<img src='images/icon/icon095.gif' />提醒：此合同已完成发货超过六个月，按规定在不改变合同金额的前提下，将无法变更产品内容");
    // }
}


// 计算方法
function countAll(rowNum) {
    var beforeStr = "productInfo_cmp_";
    if ($("#" + beforeStr + "number" + rowNum).val() == ""
        || $("#" + beforeStr + "price" + rowNum + "_v").val() == "") {
        return false;
    } else {
        // 获取当前数
        var thisNumber = $("#" + beforeStr + "number" + rowNum).val();
        // alert(thisNumber)

        // 获取当前单价
        var thisPrice = $("#" + beforeStr + "price" + rowNum + "_v").val();
        // alert(thisPrice)

        // 计算本行金额 - 不含税
        var thisMoney = accMul(thisNumber, thisPrice, 2);
        setMoney(beforeStr + "money" + rowNum, thisMoney);
    }
}

function linkmanList(customerId, flag) {

    var listObj = {
        objName: 'contract[linkman]',
        isAddOneRow: false,
        url: '?model=contract_contract_linkman&action=listJson',
        param: {
            'contractId': $("#contractId").val(),
            'isTemp': isTemp,
            'isDel': '0'
        },
        tableClass: 'form_in_table',
        colModel: [{
            display: 'id',
            name: 'id',
            type: 'hidden'
        }, {
        //     display: '客户联系人',
        //     name: 'linkmanName',
        //     tclass: 'txt',
        //     process: function ($input, rowData) {
        //         var rowNum = $input.data("rowNum");
        //         var g = $input.data("grid");
        //         $input.yxcombogrid_linkman({
        //             hiddenId: 'linkmanListInfo_cmp_linkmanId' + rowNum,
        //             isFocusoutCheck: false,
        //             gridOptions: {
        //                 showcheckbox: false,
        //                 param: {
        //                     'customerId': customerId
        //                 },
        //                 event: {
        //                     "row_dblclick": (function (rowNum) {
        //                         return function (e, row, rowData) {
        //                             var $telephone = g.getCmpByRowAndCol(
        //                                 rowNum, 'telephone');
        //                             $telephone.val(rowData.mobile);
        //                             var $QQ = g.getCmpByRowAndCol(rowNum, 'QQ');
        //                             $QQ.val(rowData.QQ);
        //                             var $email = g.getCmpByRowAndCol(rowNum,
        //                                 'Email');
        //                             $email.val(rowData.email);
        //                         }
        //                     })(rowNum)
        //                 }
        //             }
        //         });
        //     }
        // }, {
        //     display: '联系人ID',
        //     name: 'linkmanId',
        //     type: 'hidden'
        // }, {
            display: '客户联系人',
            name: 'linkmanName',
            tclass: 'txt'
        },{
            display: '电话',
            name: 'telephone',
            tclass: 'txt'
        }, {
            display: '职位',
            name: 'position',
            tclass: 'txt'
        },{
            display: '邮箱',
            name: 'Email',
            tclass: 'txt'
        }, {
            display: '备注',
            name: 'remark',
            tclass: 'txt'
        }, {
            display: 'originalId',
            name: 'originalId',
            type: 'hidden'
        }]
    };
    if (flag == 1) {
        listObj.url = '';
        listObj.param = '';
    }
    // 客户联系人
    $("#linkmanListInfo").yxeditgrid(listObj);

    setTimeout(
        function(){
            var length = $("#linkmanListInfo").yxeditgrid("getCmpByCol", "telephone").length;
            if(length <= 0){
                $("#linkmanListInfo").yxeditgrid('addRow',1);
            }
        }, 300
    )
}

// 根据借试用物料ID查询相应的执行数量
function chkBorrowEquExeNum(borrowEquId){
    var returnValue = $.ajax({
        type: 'POST',
        url: "?model=projectmanagent_borrow_borrow&action=getOriginalBorrowEquInfo",
        data: {
            id: borrowEquId
        },
        async: false,
        success: function (data) {
        }
    }).responseText;
    returnValue = eval("(" + returnValue + ")");
    return returnValue[0];
}

// 合同新增从表t
$(function () {

    linkmanList($("#customerId").val(), 0);
    // 产品清单


    //收开计划
    $("#financialplanInfo").yxeditgrid({
        objName: 'contract[financialplan]',
        url: '?model=contract_contract_financialplan&action=listJson',
        tableClass: 'form_in_table',
        param: {
            'contractId': $("#contractId").val()
        },
        colModel: [{
            display: '日期',
            name: 'planDate',
            type: 'date'
        }, {
            display: '开票金额',
            name: 'invoiceMoney',
            tclass: 'txtshort',
            type: 'money'
        }, {
            display: '收款金额',
            name: 'incomeMoney',
            tclass: 'txtshort',
            type: 'money'
        }, {
            display: '备注',
            name: 'remark',
            tclass: 'txtlong'
        }, {
            display: 'originalId',
            name: 'originalId',
            type: 'hidden'
        }]
    });

    //借试用转销售
    $("#borrowConEquInfo").yxeditgrid({
        objName: 'contract[material]',
        url: '?model=contract_contract_equ&action=listJson',
        async: false,
        param: {
            'contractId': $("#contractId").val(),
            'isTemp': isTemp,
            'isDel': '0',
            'isBorrowToorder': '1',
            'parentEquId': '0'
        },
        isAddOneRow: false,
        isAdd: false,
        tableClass: 'form_in_table',
        colModel: [{
            display: '归属产品冗余',
            name: 'conProduct',
            tclass: 'txt',
            type: 'hidden'
        }, {
            display: '从表Id',
            name: 'id',
            tclass: 'txt',
            type: 'hidden'
        }, {
            display: '归属产品',
            name: 'onlyProductId',
            type: 'select',
            tclass: 'txt',
            width: 100,
            options: [
                {
                    name: '..请选择产品..',
                    value: ''
                }
            ],
            event: {
                change: function () {
                    rowNum = $(this).data("rowNum");//行号
                }
            }
        }, {
            display: '物料Id',
            name: 'productId',
            tclass: 'txt',
            type: 'hidden'
        }, {
            display: '物料编号',
            name: 'productCode',
            tclass: 'readOnlyTxtNormal',
            readonly: 'readonly'
        }, {
            display: '物料名称',
            name: 'productName',
            tclass: 'readOnlyTxtNormal',
            readonly: 'readonly'
        }, {
            display: '型号/版本',
            name: 'productModel',
            tclass: 'readOnlyTxtNormal',
            readonly: 'readonly'
        }, {
            display: '数量',
            name: 'number',
            tclass: 'txtshort',
            event: {
                blur: function () {
                    var borrowEquId = $("#borrowConEquInfo_cmp_toBorrowequId"+$(this).data("rowNum")).val();
                    var borrowEqu = chkBorrowEquExeNum(borrowEquId);
                    var executedNum = borrowEqu.executedNum - borrowEqu.backNum;
                    if(isNaN($(this).val()) || parseInt($(this).val()) <= 0){
                        alert("请输入大于0的整数。");
                        $(this).val(executedNum);
                    }else if(parseInt($(this).val()) > parseInt(executedNum)){
                        alert("转销售数量请控制在可行性数量范围内。");
                        $(this).val(executedNum);
                    }
                }
            }
        }, {
            display: '单价',
            name: 'price',
            tclass: 'txtshort',
            type: 'hidden'
        }, {
            display: '金额',
            name: 'money',
            tclass: 'txtshort',
            type: 'hidden'
        }, {
            display: '保修期',
            name: 'warrantyPeriod',
            tclass: 'txtshort'
        }, {
            display: '借试用转销售标识',
            name: 'isBorrowToorder',
            tclass: 'txtshort',
            type: 'hidden'
        }, {
            display: '借试用表Id',
            name: 'toBorrowId',
            tclass: 'txtshort',
            type: 'hidden'
        }, {
            display: '借试用从ID',
            name: 'toBorrowequId',
            tclass: 'txtshort',
            type: 'hidden'
        }, {
            name: 'serialId',
            display: '序列号ID',
            type: 'hidden'
        }, {
            name: 'serialName',
            display: '序列号',
            tclass: 'readOnlyTxtNormal',
            readonly: 'readonly',
            process: function ($input, rowData, $tr, grid) {
                var inputId = $input.attr('id');
                var rownum = $input.data('rowNum');// 第几行
                var sid = grid.getCmpByRowAndCol(rownum, 'serialId').attr('id');
                var $img = $("<img src='images/add_snum.png' align='absmiddle'  title='选择序列号'>");
                $img.click(function (toBorrowId, productId, num, inputId, sid) {
                    return function () {
                        serialNum(toBorrowId, productId, num, inputId, sid);
                    }
                }(rowData.toBorrowId, rowData.productId, rowData.number, inputId, sid));
                $input.before($img)
            },
            event: {
                dblclick: function () {
                    var serial = $(this).val();
                    if (serial != "") {
                        showThickboxWin('?model=projectmanagent_borrow_borrow&action=serialShow&serial='
                        + serial
                        + "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=800");
                    }
                }
            }
        }, {
            display: 'originalId',
            name: 'originalId',
            type: 'hidden'
        }],
        event: {
            beforeRemoveRow : function(e, rowNum, rowData, g) {
                if( rowData.executedNum>0 ){//如果有已出库不能删
                    alert("该物料已部分出库，禁止直接删除，请走退货流程！");
                    g.isRemoveAction=false;
                    return false;
                }else if(rowData.issuedShipNum > 0){//如果没有已出库,但是有发货计划的也不能删
                    alert("该物料已下达发货计划，禁止直接删除！");
                    g.isRemoveAction=false;
                    return false;
                }
            },
            //借试用转销售物料
            reloadData: function () {
                createProArr();
            }
        }

    });

    var ids = $("#ids").val();
    if (ids != '') {
        var returnValue = $.ajax({
            type: 'POST',
            url: "?model=projectmanagent_borrow_borrow&action=getBorrowequInfo",
            data: {
                ids: ids
            },
            async: false,
            success: function (data) {
            }
        }).responseText;
        returnValue = eval("(" + returnValue + ")");
        if (returnValue) {
            var g = $("#borrowConEquInfo").data("yxeditgrid");
            var rn = $("#borrowConEquInfo").yxeditgrid('getAllAddRowNum');
            var j = rn + 1;
            //循环拆分数组
            for (var i = 0; i < returnValue.length; i++) {
                var canExeNum = returnValue[i].executedNum - returnValue[i].backNum;
                outJson = {
                    "productId": returnValue[i].productId,
                    "productCode": returnValue[i].productNo,
                    "productName": returnValue[i].productName,
                    "productModel": returnValue[i].productModel,
                    // "number": returnValue[i].executedNum - returnValue[i].backNum,
                    "number": canExeNum, //数量默认带出已执行数量
                    "price": returnValue[i].price,
                    "money": returnValue[i].price*canExeNum,
                    "warrantyPeriod": returnValue[i].warrantyPeriod,
                    "isBorrowToorder": 1,
                    "toBorrowId": returnValue[i].borrowId,
                    "toBorrowequId": returnValue[i].id
                };

                //插入数据
                $("#customerIdtest").val(returnValue[i].customerId);
                g.addRow(j, outJson);
                j++;
            }
        }
    }

    // 开票计划
    $("#invoiceListInfo").yxeditgrid({
        objName: 'contract[invoice]',
        url: '?model=contract_contract_invoice&action=listJson',
        param: {
            'contractId': $("#contractId").val(),
            'isTemp': isTemp,
            'isDel': '0'
        },
        colModel: [{
            display: 'id',
            name: 'id',
            type: 'hidden'
        }, {
            display: '开票金额',
            name: 'money',
            tclass: 'txt'
        }, {
            display: '软件金额',
            name: 'softMoney',
            tclass: 'txt'
        }, {
            display: '开票类型',
            name: 'iType',
            type: 'select',
            datacode: 'FPLX'
        }, {
            display: '开票日期',
            name: 'invDT',
            type: 'date'
        }, {
            display: '开票内容',
            name: 'remark',
            tclass: 'txt'
        }, {
            display: 'originalId',
            name: 'originalId',
            type: 'hidden'
        }]
    });
    // 收款计划
    $("#incomeListInfo").yxeditgrid({
        objName: 'contract[income]',
        url: '?model=contract_contract_receiptplan&action=listJson',
        param: {
            'contractId': $("#contractId").val(),
            'isTemp': isTemp,
            'isDel': '0'
        },
        colModel: [{
            display: 'id',
            name: 'id',
            type: 'hidden'
        }, {
            display: '收款金额',
            name: 'money',
            tclass: 'txt'
        }, {
            display: '收款日期',
            name: 'payDT',
            type: 'date'
        }, {
            display: '收款方式',
            name: 'pType',
            tclass: 'txt'
        }, {
            display: '收款条件',
            name: 'collectionTerms',
            tclass: 'txtlong'
        }, {
            display: 'originalId',
            name: 'originalId',
            type: 'hidden'
        }]
    });
    //物料
    $("#equInfo").yxeditgrid({
        objName: 'contract[equ]',
        url: '?model=contract_contract_equ&action=listJson',
        param: {
            'contractId': $("#contractId").val(),
            'isTemp': isTemp,
            'isDel': '0',
            'isBorrowToorder': '0'
        },
        tableClass: 'form_in_table',
//		isAddOneRow:false,
//		isAdd : false,
        colModel: [{
            display: 'id',
            name: 'id',
            type: 'hidden'
        }, {
            display: '物料编号',
            name: 'productCode',
            tclass: 'readOnlyTxtNormal',
            readonly: true,
            process: function ($input, rowData) {
                var rowNum = $input.data("rowNum");
                var g = $input.data("grid");
                var isEqu = g.getCmpByRowAndCol(rowNum, 'productCode').val();
                if (isEqu == '') {
                    $input.yxcombogrid_product({
                        hiddenId: 'itemTable_cmp_productId' + rowNum,
                        nameCol: 'productCode',
//					closeCheck : true,// 关闭状态,不可选择
                        closeAndStockCheck: true,
                        width: 600,
                        gridOptions: {
                            event: {
                                row_dblclick: (function (rowNum) {
                                    return function (e, row, rowData) {
                                        g.getCmpByRowAndCol(rowNum, 'productId').val(rowData.id);
                                        g.getCmpByRowAndCol(rowNum, 'productName').val(rowData.productName);
                                        g.getCmpByRowAndCol(rowNum, 'productModel').val(rowData.pattern);
                                        g.getCmpByRowAndCol(rowNum, 'number').val("1");
                                    }
                                })(rowNum)
                            }
                        }
                    });
                }
            }
        }, {
            display: '归属产品',
            name: 'onlyProductId',
            type: 'hidden',
        }, {
            display: '借试用转销售标识',
            name: 'isBorrowToorder',
            tclass: 'txtshort',
            type: 'hidden'
        },{
            display: '物料Id',
            name: 'productId',
            type: 'hidden'
        }, {
            display: '物料名称',
            name: 'productName',
            tclass: 'readOnlyTxtNormal',
            readonly: true,
            process: function ($input, rowData) {
                var rowNum = $input.data("rowNum");
                var g = $input.data("grid");
                var isEqu = g.getCmpByRowAndCol(rowNum, 'productCode').val();
                if (isEqu == '') {
                    $input.yxcombogrid_product({
                        hiddenId: 'itemTable_cmp_productId' + rowNum,
                        nameCol: 'productName',
//					closeCheck : true,// 关闭状态,不可选择
                        closeAndStockCheck: true,
                        width: 600,
                        gridOptions: {
                            event: {
                                row_dblclick: (function (rowNum) {
                                    return function (e, row, rowData) {
                                        g.getCmpByRowAndCol(rowNum, 'productId').val(rowData.id);
                                        g.getCmpByRowAndCol(rowNum, 'productCode').val(rowData.productCode);
                                        g.getCmpByRowAndCol(rowNum, 'productModel').val(rowData.pattern);
                                        g.getCmpByRowAndCol(rowNum, 'number').val("1");
                                    }
                                })(rowNum)
                            }
                        }
                    });
                }
            }
        }, {
            display: '型号/版本',
            name: 'productModel',
            tclass: 'readOnlyTxtNormal',
            readonly: true
        }, {
            display: '数量',
            name: 'number',
            tclass: 'txtshort',
            event: {
                blur: function () {
                    countAll($(this).data("rowNum"), "equinfo");
                }
            }
        }, {
            display: '单价',
            name: 'price',
            tclass: 'txtshort',
            type: 'money',
            event: {
                blur: function () {
                    countAll($(this).data("rowNum"), "equinfo");
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
            display: '归属产品Id',
            name: 'conProductId',
            type: 'hidden'
        },
            {
            name: 'licenseButton',
            display: '加密配置',
            type: 'statictext',
            event: {
                'click': function (e) {
                    var rowNum = $(this).data("rowNum");
                    // 获取licenseid
                    var licenseObj = $("#equInfo_cmp_license" + rowNum);
                    if (licenseObj.val() == '') {
                        // 弹窗
                        url = "?model=yxlicense_license_tempKey&action=toSelectWin"
                        + "&productInfoId="
                        + "equInfo_cmp_license"
                        + rowNum;
                        var returnValue = showModalDialog(url, '', "dialogWidth:1000px;dialogHeight:600px;");
                        if (returnValue) {
                            licenseObj.val(returnValue);
                        }
                    } else {
                        // 弹窗
                        url = "?model=yxlicense_license_tempKey&action=toSelectChange" + "&licenseId=" + licenseObj.val()
                        + "&productInfoId="
                        + "equInfo_cmp_license"
                        + rowNum;
                        var returnValue = showModalDialog(url, '', "dialogWidth:1000px;dialogHeight:600px;");

                        if (returnValue) {
                            licenseObj.val(returnValue);
                        }
                    }
                }
            },
            html: '<input type="button"  value="加密配置"  class="txt_btn_a"  />'
        }, {
            display: 'originalId',
            name: 'originalId',
            type: 'hidden'
        }]
    });
});

// 单独封装产品选择
(function($) {
	// 产品清单
	$.woo.yxeditgrid.subclass('woo.productInfoGrid', {
		objName: 'contract[product]',
		url: '?model=contract_contract_product&action=listJson',
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
			name: 'exeDeptId',
			type: 'select',
            emptyOption : true,
			datacode: 'GCSCX'
		}, {
			display: '执行区域Name',
			name: 'exeDeptName',
			type: 'hidden'
		}, {
			display: '产品类型',
			tclass: 'readOnlyTxtMiddle',
			name: 'proType',
			readonly: true
		}, {
			display: '产品类型id',
			name: 'proTypeId',
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
		},
        {
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
			event: {
				blur: function () {
					countAll($(this).data("rowNum"));
				}
			}
		}, {
			display: '单价',
			name: 'price',
			tclass: 'txtshort',
			type: 'money',
			event: {
				blur: function () {
					countAll($(this).data("rowNum"));
				}
			}
		}, {
			display: '金额',
			name: 'money',
			tclass: 'txtshort',
			type: 'money'
		}, {
			display: '原产品配置Id',
			name: 'orgDeploy',
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
				click: function (e) {
					var rowNum = $(this).data("rowNum");
					// 缓存产品信息
					var conProductId = $("#productInfo_cmp_conProductId"
					+ rowNum).val();
					var conProductName = $("#productInfo_cmp_conProductName"
					+ rowNum).val();
					var deploy = $("#productInfo_cmp_deploy" + rowNum).val();
					var orgDeploy = $("#productInfo_cmp_orgDeploy" + rowNum).val();

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
							if (deploy == orgDeploy) {
								var url = "?model=goods_goods_properties&action=toChooseChange"
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

				}
			},
			html: '<input type="button"  value="产品配置"  class="txt_btn_a"/>'
		},{
			display: '产品物料唯一Id',
			name: 'onlyProductId',
			type: 'hidden'
		}, {
            display: 'originalId',
            name: 'originalId',
            type: 'hidden'
        }],
		isAddOneRow: false,
		event: {
			clickAddRow: function (e, rowNum, g) {
				rowNum = g.allAddRowNum;
				//是否框架合同，销售类合同是框架合同允许合同金额为0
				var isFrame = $("#contractType").val() == 'HTLX-XSHT' && $("#isFrame").val() == '1' ? '1' : '0';
				var url = "?model=contract_contract_product&action=toProductIframe&isCon=1"
					+ "&componentId=productInfo"
					+ "&rowNum="
					+ rowNum
					+ "&isFrame="
					+ isFrame;

				window.open(url, '',
					'top=0,left=0,menubar=0,toolbar=0,status=1,scrollbars=1,resizable=1,width=1000px,height=600px');
			},
			reloadData: function (e, g ,data) {
				if ($("#proflag").val() == '0') {
					changeEqu();
				}
				initCacheInfo();
				createProArr();

				// 执行部门处理
//				initExeDept(data, g);
			},
            beforeRemoveRow: function (e, rowNum, rowData, g){ //Create By HuangHaoJin
                var contractId = $('#oldId').val();
                var isPlaning = false;//物料已下达发货计划

                if (typeof(rowData) != 'undefined') {
                    var productId = (rowData.originalId>0)?rowData.originalId : rowData.id;
                    //检查是否已下达发货计划
                    var resultNum = $.ajax({
                        type: 'POST',
                        url: "?model=contract_contract_equ&action=chkPlaningEqu",
                        data: {
                            contractid: contractId,
                            productId: productId
                        },
                        async: false,
                        success: function (data) {
                        }
                    }).responseText;
                    isPlaning = (resultNum>0) ? true : false;
                }

                if(isPlaning){
                    alert("产品无法删除，此产品的物料已下达发货计划。");
                    g.isRemoveAction = false;
                }
            },
			removeRow: function (e, rowNum, rowData) {
                var equObj = $("#equInfo");
				if (typeof(rowData) != 'undefined') {
					$("#goodsDetail_" + rowData.deploy).remove();
                    //删除产品同时删除底下关联的物料
                    for (var i = 0; i < equObj.yxeditgrid("getAllAddRowNum"); i++) {
                        if(equObj.yxeditgrid("getCmpByRowAndCol",i,"conProductId").val() == rowData.id){
                            //如果有对应的物料，删除物料
                            $("#equInfo").append('<input type="hidden" id="equInfo_cmp_isDelTag'+i+'" name="contract[equ]['+i+'][isDelTag]" value="1" class="">');
                        }
                    }
					createProArr();
				}
			}
		},
		addBtnClick: function() {
			return false;
		},
		setData: function(returnValue, rowNum) {
			var g = this;
			if (returnValue) {
//                var rowsNum = parseInt(g.tbody.find('.tr_even').length);
                // 新增一行
                g.addRow(g.allAddRowNum);

				//产品
				var proArr = returnValue[0];
				g.setRowColValue(rowNum, "proType", proArr.proType);
				g.setRowColValue(rowNum, "proTypeId", proArr.proTypeId);
				g.setRowColValue(rowNum, "proExeDeptId", proArr.proExeDeptId);
				g.setRowColValue(rowNum, "proExeDeptName", proArr.proExeDeptName);
				g.setRowColValue(rowNum, "newExeDeptCode", proArr.newExeDeptCode);
				g.setRowColValue(rowNum, "newExeDeptName", proArr.newExeDeptName);
				g.setRowColValue(rowNum, "newProLineCode", proArr.newExeDeptCode);
				g.setRowColValue(rowNum, "newProLineName", proArr.newExeDeptName);
//				initExeDeptByRow(g, rowNum);
				setProExeDeptByRow(rowNum);

				g.setRowColValue(rowNum, "conProductId", proArr.goodsId, true);
				g.setRowColValue(rowNum, "conProductName", proArr.goodsName, true);
				g.setRowColValue(rowNum, "goodsClass", proArr.goodsClass, true);
				g.setRowColValue(rowNum, "goodsClassName", proArr.goodsClassName, true);
				g.setRowColValue(rowNum, "number", proArr.number, true);
				g.setRowColValue(rowNum, "price", proArr.price, true);
				g.setRowColValue(rowNum, "money", proArr.money, true);
				g.setRowColValue(rowNum, "warrantyPeriod", proArr.warrantyPeriod, true);
				g.setRowColValue(rowNum, "deploy", proArr.cacheId, true);
				g.setRowColValue(rowNum, "license", proArr.licenseId, true);
				g.setRowColValue(rowNum, "onlyProductId", proArr.onlyProductId, true);
				proArr.deploy = proArr.cacheId;
				var $tr = g.getRowByRowNum(rowNum);
                // 如果原来有挂载配件物料的，先删除原来的配件栏
                if($tr.data("rowData")){
                    var oldDeploy = $tr.data("rowData").deploy;
                    if($("#goodsDetail_" + oldDeploy)){$("#goodsDetail_" + oldDeploy).remove();}
                }

//                $("#goodsDetail_" + oldDeploy).remove();

				$tr.data("rowData", proArr);
				//选择产品后动态渲染下面的配置单
				getCacheInfo(proArr.cacheId, rowNum);

				//物料
				var equArr = returnValue[1];
				if (typeof(equArr) != 'undefined') {
					var equLen = equArr.length;
//					var equObj = $("#materialInfo");
                    var equObj = $("#equInfo");
					for (var i = 0; i < equLen; i++) {
						//重新获取行数
						var tbRowNum = equObj.yxeditgrid("getAllAddRowNum");
						//新增行
						equObj.yxeditgrid("addRow", tbRowNum);
						equObj.yxeditgrid("setRowColValue", tbRowNum, "productCode", equArr[i].productCode);
						equObj.yxeditgrid("setRowColValue", tbRowNum, "productName", equArr[i].productName);
						equObj.yxeditgrid("setRowColValue", tbRowNum, "productId", equArr[i].productId);
						equObj.yxeditgrid("setRowColValue", tbRowNum, "conProductId", equArr[i].conProductId);
						equObj.yxeditgrid("setRowColValue", tbRowNum, "productModel", equArr[i].productModel);
						equObj.yxeditgrid("setRowColValue", tbRowNum, "number", equArr[i].number);
						equObj.yxeditgrid("setRowColValue", tbRowNum, "onlyProductId", equArr[i].onlyProductId);
                        equObj.yxeditgrid("setRowColValue", tbRowNum, "price", equArr[i].price);
                        equObj.yxeditgrid("setRowColValue", tbRowNum, "money", equArr[i].money);
                        equObj.yxeditgrid("setRowColValue", tbRowNum, "license", equArr[i].license);
					}
				}
				createProArr();
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

//产品清单
function productListHtml(typeState) {
	$("#productInfo").productInfoGrid({
		type: typeState,
		param: {
			contractId: $("#contractId").val(),
			dir: 'ASC',
			isTemp: isTemp,
			isDel: '0'
		}
	});
}

function changeEqu() {
    var rowNum = $("#productInfo").productInfoGrid('getCurShowRowNum');
    if (rowNum == '0') {
        //2012-11-09 谢工说禁掉合同变更物料
        document.getElementById("equ").style.display = "none";
    } else {
        document.getElementById("equ").style.display = "none";
    }
}
// 选择序列号
function serialNum(borrowId, productId, num, inputId, sid) {
    var amount = $("#bornumber" + num).val();
    showThickboxWin('?model=projectmanagent_borrow_borrow&action=serialNum&borrowId='
    + borrowId
    + '&productId='
    + productId
    + '&num='
    + num
    + '&amount='
    + num
    + '&inputId='
    + inputId
    + '&sid='
    + sid
    + "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=800");
}

//构建归属产品下拉数组
function createProArr(denyRmBtn) {
    var itemArr = $("#productInfo").productInfoGrid("getCmpByCol", "conProductName");
    if (itemArr.length > 0) {
        var returnArr = [];
        //循环
        itemArr.each(function () {
            var rowNum = $(this).data("rowNum");
            var rowArr = $("#productInfo").productInfoGrid("getRowByRowNum", rowNum);
            rowArr.each(function () {
                var beforeStr = "productInfo_cmp_";
				//借试用转销售的物料，只能挂在销售类产品下
				if($("#" + beforeStr + "proTypeId" + rowNum).val() == "11"){
					var equJson = {
						name: $("#" + beforeStr + "conProductName" + rowNum).val(),
						value: $("#" + beforeStr + "onlyProductId" + rowNum).val()
					};
					returnArr.push(equJson);
				}

                // 如果需要隐藏删除按钮的隐藏该列表的删除按钮
                if (typeof(denyRmBtn) != 'undefined' && denyRmBtn) {
                    $("#" + beforeStr + "removeBn" + rowNum).hide();
                }else{
                    //如果有缓存合同数据则用原合同的产品ID（originalId）,如果没用则用当前合同产品ID（id）
                    var conProductId = ($("#" + beforeStr + "originalId" + rowNum).val() > 0)? $("#" + beforeStr + "originalId" + rowNum).val() : $("#" + beforeStr + "id" + rowNum).val();
                    var contractId = $("#oldId").val();

                    // 查询是否存在挂载物料(如果存在的不能删除)
                    var chkEquResult = $.ajax({
                        type: 'POST',
                        url: "?model=contract_contract_equ&action=chkRelativeEqu",
                        data: {
                            contractid: contractId,
                            productid:conProductId
                        },
                        async: false,
                        success: function (data) {
                        }
                    }).responseText;
                    if( parseInt(chkEquResult)>0 && conProductId!=''){$("#" + beforeStr + "removeBn" + rowNum).hide();}//如果产品有关联yi物料，且不是变更页面新增的不许删除
                }
            })
        });
    }
    proLineArr = returnArr;
//	        proLineArr = eval("(" + returnArr + ")");
    //借用物料
    var bowItemArr = $("#borrowConEquInfo").yxeditgrid("getCmpByCol", "productId");
    if (bowItemArr.length > 0) {
        var returnArr = [];
        //循环
        bowItemArr.each(function () {
            var borowNum = $(this).data("rowNum");
            var borowArr = $("#borrowConEquInfo").yxeditgrid("getRowByRowNum", borowNum);
            borowArr.each(function () {
                var OldVal = $("#borrowConEquInfo_cmp_conProduct" + borowNum).val();
                $("#borrowConEquInfo_cmp_onlyProductId" + borowNum).empty();
                var obj = document.getElementById("borrowConEquInfo_cmp_onlyProductId" + borowNum);
                obj.add(new Option("...请选择...", " "));
//	            	 	$("#borrowConEquInfo_cmp_proId" + borowNum).append("<option value=''>...请选择...<option>");
                if (typeof(proLineArr) != 'undefined') {
                    if (proLineArr.length > 0) {
                        for (i = 0; i < proLineArr.length; i++) {
                            obj.add(new Option("" + proLineArr[i]['name'] + "", "" + proLineArr[i]['value'] + ""));
                            if (proLineArr[i]['name'] == OldVal) {
                                var oldTval = proLineArr[i]['value'];
                            }
//	            	 	       $("#borrowConEquInfo_cmp_proId" + borowNum).append("<option value='"+proLineArr[i]['value']+"'>"+proLineArr[i]['name']+"<option>");
                        }
                    }
                }
                for (var i = 0; i < obj.options.length; i++) {
                    if (oldTval == obj.options[i].value) {
                        obj.options[i].selected = 'selected';
                        break;
                    }
                }

            })
        });
    }
}
