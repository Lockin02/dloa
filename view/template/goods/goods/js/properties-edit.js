$(document).ready(function () {
    validate({
        propertiesName: {
            required: true
        },
        orderNum: {
            required: true,
            custom: ['onlyNumber']
        }
    });

    // 设置产品id
    $("#mainId").val(parent.parent.window.$("#goodsId").val());
    // 新增分类信息 选择类型
    $("#parentName").yxcombotree({
        hiddenId: 'parentId',
        treeOptions: {
            url: "?model=goods_goods_properties&action=getTreeData&goodsId="
            + $("#mainId").val()
        }
    });

    var detalArr = [{
        value: "",
        name: "无license"
    }, {
        value: "PIO",
        name: "Pioneer"
    }, {
        value: "NAV",
        name: "Navigator"
    }, {
        value: "Pioneer-Navigator",
        name: "Pioneer-Navigator"
    }, {
        value: "WT",
        name: "Walktour"
    }, {
        value: "Walktour Pack-Ipad",
        name: "Walktour Pack-Ipad"
    }, {
        value: "FL2",
        name: "Fleet"
    }];

    $.ajax({
        url : '?model=yxlicense_license_baseinfo&action=getLicense',
        type : "POST",
        data : {},
        async : false,
        success: function(data) {
            data = eval("(" + data + ")");
            for (var i = 0; i <= data.length; i++) {
                detalArr.push({
                    value: data[i].value,
                    name: data[i].name
                });
            }
        }
    });

    $("#itemTable").yxeditgrid({
        objName: 'properties[items]',
        url: '?model=goods_goods_propertiesitem&action=pageItemJson',
        param: {
            mainId: $("#id").val()
        },
        colModel: [{
            name: 'id',
            display: 'id',
            type: 'hidden'
        }, {
            name: 'itemContent',
            tclass: 'txt',
            display: '值内容',
            validation: {
                required: true
            }
        }, {
            name: 'isNeed',
            display: '是否必选',
            type: 'checkbox',
            tclass: 'txtmin',
            sortable: true
        }, {
            name: 'isDefault',
            display: '是否默认',
            type: 'checkbox',
            tclass: 'txtmin',
            sortable: true
        }, {
            name: 'defaultNum',
            display: '数量',
            tclass: 'txtmin',
            sortable: true
        }, {
            name: 'productId',
            display: '产品id',
            type: "hidden",
            sortable: true
        }, {
            name: 'productCode',
            display: '对应物料编号',
            sortable: true,
            process: function ($input, rowData) {
                var rowNum = $input.data("rowNum");
                var g = $input.data("grid");
                $input.yxcombogrid_product({
                    hiddenId: 'itemTable_cmp_productId'
                    + rowNum,
                    nameCol: 'productCode',
                    width: 600,
                    gridOptions: {
                        event: {
                            row_dblclick: (function (rowNum) {
                                return function (e, row, rowData) {
                                    g.getCmpByRowAndCol(rowNum, 'productName').val(rowData.productName);
                                    g.getCmpByRowAndCol(rowNum, 'pattern').val(rowData.pattern);
                                    g.getCmpByRowAndCol(rowNum, 'itemContent').val(rowData.productName)
                                    .attr('readonly','readonly').removeClass('txt').addClass('readOnlyTxtNormal');
                                }
                            })(rowNum)
                        }
                    },
                    event : {
                        'clear' : function() {
                            g.getCmpByRowAndCol(rowNum, 'productName').val('');
                            g.getCmpByRowAndCol(rowNum, 'pattern').val('');
                            g.getCmpByRowAndCol(rowNum, 'itemContent').val('')
                            .attr('readonly',false).removeClass('readOnlyTxtNormal').addClass('txt');
                        }
                    }
                });
            }
        }, {
            name: 'productName',
            display: '对应物料名称',
            tclass: 'txt',
            process: function ($input, rowData) {
                var rowNum = $input.data("rowNum");
                var g = $input.data("grid");
                $input.yxcombogrid_product({
                    hiddenId: 'itemTable_cmp_productId'
                    + rowNum,
                    nameCol: 'productName',
                    width: 600,
                    gridOptions: {
                        event: {
                            row_dblclick: (function (rowNum) {
                                return function (e, row, rowData) {
                                    g.getCmpByRowAndCol(rowNum, 'productCode').val(rowData.productCode);
                                    g.getCmpByRowAndCol(rowNum, 'pattern').val(rowData.pattern);
                                    g.getCmpByRowAndCol(rowNum, 'itemContent').val(rowData.productName)
                                    .attr('readonly','readonly').removeClass('txt').addClass('readOnlyTxtNormal');
                                }
                            })(rowNum)
                        }
                    },
                    event : {
                        'clear' : function() {
                            g.getCmpByRowAndCol(rowNum, 'productCode').val('');
                            g.getCmpByRowAndCol(rowNum, 'pattern').val('');
                            g.getCmpByRowAndCol(rowNum, 'itemContent').val('')
                            .attr('readonly',false).removeClass('readOnlyTxtNormal').addClass('txt');
                        }
                    }
                });
            },
            sortable: true
        }, {
            name: 'pattern',
            display: '对应物料型号',
            sortable: true,
            tclass: 'readOnlyTxtItem'
        }, {
            name: 'proNum',
            display: '对应物料数量',
            tclass: 'txtmin',
            sortable: true
        }, {
            name: 'status',
            display: '状态',
            type: 'select',
            tclass: 'txtshort',
            options: [{
                name: "在产",
                value: 'ZC'
            }, {
                name: "停产",
                value: 'TC'
            }],
            sortable: true
        }, {
            name: 'licenseTypeName',
            display: 'license类型',
            type: 'hidden'
        }, {
            name: 'licenseTypeCode',
            display: 'license类型',
            type: 'select',
            options: detalArr,
            event: {
                change: function (e) {
                    var rowNum = e.data.rowNum;
                    var g = e.data.gird;
                    var $cmp = g.getCmpByRowAndCol(rowNum,
                        'licenseTypeName');
                    var name = $(this).find("option:selected").text();
                    $cmp.val(name);
                    $.ajax({
                        type: "POST",
                        url: "?model=yxlicense_license_template&action=getTemplateByType",
                        data: {
                            'licenseType': $(this).val()
                        },
                        async: false,
                        success: function (data) {
                            var $cmp = g.getCmpByRowAndCol(rowNum,
                                'licenseTemplateId');
                            $cmp.children().remove();
                            $cmp.append("<option value=''>请选择</option>");
                            var dataRows = eval('(' + data + ')');
                            for (var i = 0, l = dataRows.length; i < l; i++) {
                                $cmp.append("<option title='"
                                + dataRows[i].remark
                                + "' value='" + dataRows[i].id
                                + "' innerTitle='"
                                + dataRows[i].extVal
                                + "' value='"
                                + dataRows[i].thisVal + "'>"
                                + dataRows[i].name
                                + "</option>");
                            }
                        }
                    });
                }
            }
        }, {
            name: 'licenseTemplateId',
            display: 'license模板',
            type: 'select',
            options: []
        }, {
            name: 'remark',
            display: '描述',
            type: "hidden"
        }, {
            name: 'rkey',
            display: '描述标识',
            type: "hidden"
        }, {
            name: 'staticRemark',
            display: '具体描述按钮',
            type: 'statictext',
            event: {
                'click': function (e) {
                    var rowNum = $(this).data("rowNum");
                    var g = $(this).data("grid");
                    window
                        .open(
                        "?model=goods_goods_properties&action=toEditRemark&rowNum="
                        + rowNum
                        + "&id="
                        + g.getCmpByRowAndCol(
                            rowNum, 'id').val()
                        + "&rkey="
                        + $("#itemTable_cmp_rkey"
                        + rowNum).val(),
                        '描述信息编辑',
                        'height=500, width=650, top=200, left=200, toolbar=no, menubar=no, scrollbars=no, resizable=no,location=n o, status=no');
                }
            },
            html: '<input type="button"  value="编辑"  class="txt_btn_a"  />'
        }, {
            name: 'assitem',
            display: '数据项关联',
            type: "hidden"
        }, {
            name: 'assitemIdStr',
            display: '数据项Id关联',
            type: "hidden"
        }, {
            name: 'assitemTipStr',
            display: '数据项Tip关联',
            type: "hidden"
        }, {
            name: 'staticAssitem',
            display: '数据项关联',
            type: 'statictext',
            event: {
                'click': function (e) {
                    var rowNum = $(this).data("rowNum");
                    var g = $(this).data("grid");
                    var rowData = $(this).data("rowData");
                    var url = "?model=goods_goods_properties&action=toSetAssItem&goodsId="
                        + $("#mainId").val() + "&orderNum=" + $("#orderNum").val()
                        + "&rowNum=" + rowNum + "&assitem=" + $("#itemTable_cmp_assitem" + rowNum).val()
                        + "&assItemIdStr=" + $("#itemTable_cmp_assitemIdStr" + rowNum).val()
                        + "&assitemTipStr=" + geturl($("#itemTable_cmp_assitemTipStr" + rowNum).val());
                    window.open(url, '数据项关联', 'height=500, width=650, top=200, left=200, toolbar=no, menubar=no, scrollbars=no, resizable=no,location=n o, status=no');
                }
            },
            html: '<input type="button"  value="设置"  class="txt_btn_a"  />'

        }],
        event: {
            addRow: function (e, rowNum, rowData, g, $tr) {
                if (rowData && rowData.licenseTypeCode) {
                    $.ajax({
                        type: "POST",
                        url: "?model=yxlicense_license_template&action=getTemplateByType",
                        data: {
                            'licenseType': rowData.licenseTypeCode
                        },
                        async: false,
                        success: function (data) {

                            var $cmp = g.getCmpByRowAndCol(rowNum,
                                'licenseTemplateId');
                            $cmp.children().remove();
                            $cmp.append("<option value=''>请选择</option>");
                            dataRows = eval('(' + data + ')');
                            for (var i = 0, l = dataRows.length; i < l; i++) {
                                var selected = "";
                                if (rowData.licenseTemplateId == dataRows[i].id) {
                                    selected = "selected";
                                }
                                $cmp.append("<option " + selected + " title='"
                                + dataRows[i].remark + "' value='"
                                + dataRows[i].id + "' >"
                                + dataRows[i].name + "</option>");
                            }
                        }
                    });
                }
            },
            reloadData : function(e){
            	var objGrid = $("#itemTable");
            	//获取值内容
            	var itemContentArr = objGrid.yxeditgrid("getCmpByCol", "itemContent");
				if (itemContentArr.length > 0) {
					itemContentArr.each(function(){
               			var rowNum = $(this).data("rowNum");
	            		//获取对应物料名称
						var productName = objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,"productName").val();
						//值内容与对应物料名称一致时，不允许改值内容
						if($(this).val() == productName){
							$(this).removeClass('txt').addClass('readOnlyTxtNormal').attr('readonly','readonly');
						}
					});
				}
			}
        }
    })
});

//符号转义
function geturl(str) {
    return str.replace(/\&/g, '%26');
}

//保存时验证表单
function checkForm() {
	var objGrid = $("#itemTable");
	var statusZC = false;//是否存在【在产】状态的配置项
	objGrid.yxeditgrid("getCmpByCol","status").each(function(){
		if($(this).val() == 'ZC'){
			statusZC = true;
			return false; //跳出each循环
		}
    });
	if($("#isLeast").attr("checked") && statusZC == false){
		alert("当前不存在【在产】的配置项，请勿勾选基本控制里的【至少选中一项】");
	    return false;
	}
}
