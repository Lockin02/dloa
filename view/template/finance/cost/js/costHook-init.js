/**
 * Created by show on 14-5-6.
 * 发票勾稽组件
 */
(function ($) {

    //默认属性
    var defaults = {
        objName: "", // 业务名称
        type: "hook", // 显示类型 "" 空, hookEdit 勾稽编辑, hook 勾稽, hookSelect 勾稽选择
        url: "", // 路径
        param: {}, // 参数
        showSelectWin: false, // 是否开放选择窗口，一般是在其他发票选择界面用
        event: {}, // 自定义事件
        async: true, // 异步
        parentGrid: "", // 父对象
        isShowCountRow: false, // 是否显示合计栏
        countKey: "", // 合计栏计算的字段
        colModel: [{ // 默认列
            name: 'id',
            display: 'id',
            type: 'hidden'
        }, {
            name: 'oldId',
            display: 'oldId',
            type: 'hidden'
        }, {
            name: 'hookId',
            display: 'hookId',
            type: 'hidden'
        }, {
            name: 'mainId',
            display: 'mainId',
            type: 'hidden'
        }, {
            name: 'hookDetailId',
            display: 'hookDetailId',
            type: 'hidden'
        }, {
            name: 'objCode',
            display: '源单编号',
            type: 'hidden'
        }, {
            name: 'objType',
            display: '源单类型',
            type: 'hidden'
        }, {
            name: 'company',
            display: '公司主体',
            type: 'hidden'
        }, {
            name: 'companyName',
            display: '公司主体',
            type: 'hidden'
        }, {
            name: 'module',
            display: '所属板块编号',
            sortable: true,
            width: 60,
            type: 'hidden'
        }, {
            name: 'moduleName',
            display: '所属板块',
            sortable: true,
            width: 60,
            type: 'statictext',
            isSubmit: true
        }, {
            name: 'belongCompany',
            display: '归属公司',
            width: 70,
            process: function (html, rowData, $tr, g) {
                if (rowData && g.options.type == 'view') {
                    return rowData.belongCompanyName;
                } else if (rowData && g.options.type == 'hook') {
                    html.hide().after(rowData.belongCompanyName);
                }
            }
        }, {
            name: 'belongCompanyName',
            display: '归属公司',
            type: 'hidden'
        }, {
            name: 'inPeriod',
            display: '费用入账期间',
            width: 70,
            process: function (html, rowData, $tr, g) {
                if (rowData && g.options.type == 'hook') {
                    html.hide().after(rowData.inPeriod);
                } else {
                    return html;
                }
            }
        }, {
            name: 'belongPeriod',
            display: '费用归属期间',
            width: 70,
            process: function (html, rowData, $tr, g) {
                if (rowData && g.options.type == 'hook') {
                    html.hide().after(rowData.belongPeriod);
                } else {
                    return html;
                }
            }
        }, {
            name: 'detailType',
            display: '费用类型',
            width: 80,
            process: function (html, rowData, $tr, g) {
                if (rowData && g.options.type == 'hook') {
                    html.hide().after(g.getDetailTypeCN(rowData.detailType));
                } else {
                    return g.getDetailTypeCN(html);
                }
            }
        }, {
            name: 'shareObjType',
            display: '分摊对象类型',
            width: 80,
            type: 'select',
            datacode: 'FTDXLX',
            options: [],
            process: function (html, rowData, $tr, g) {
                if (rowData && g.options.type == 'hook') {
                    html.hide().after(g.getDatadictValue('shareObjType', rowData.shareObjType));
                } else if (rowData) {
                    return g.getDatadictValue('shareObjType', rowData.shareObjType);
                }
            }
        }, {
            name: 'defaultShareObjType',
            display: 'defaultShareObjType',
            type: 'hidden'
        }, {
            name: 'costShareObj',
            display: '分摊对象',
            width: 120,
            align: 'left',
            type: 'statictext',
            process: function (html, rowData, $tr, g, $input, rowNum) {
                if ((g.options.type == 'view' || g.options.type == 'hook') && rowData) {
                    return g.initShareObjView(rowData.shareObjType, rowNum, rowData);
                }
            }
        }, {
            name: 'costShareObjExtends',
            display: '关联信息',
            width: 130,
            align: 'left',
            type: 'statictext',
            process: function (html, rowData, $tr, g, $input, rowNum) {
                if (rowData) {
                    if (g.options.type == 'view') {
                        return g.initShareObjExtendsView(rowData.shareObjType, rowNum, rowData);
                    } else if (g.options.type == 'hook') {
                        return g.initShareObjExtendsHook(rowData.shareObjType, rowNum, rowData);
                    }
                }
            }
        }, {
            name: 'parentTypeId',
            display: 'parentTypeId',
            type: 'hidden'
        }, {
            name: 'parentTypeName',
            display: '费用明细上级',
            type: 'hidden'
        }, {
            name: 'costTypeId',
            display: 'costTypeId',
            type: 'hidden'
        }, {
            name: 'costTypeName',
            display: '费用明细',
            width: 80,
            process: function (html, rowData, $tr, g) {
                if (rowData && g.options.type == 'hook') {
                    html.hide().after(rowData.costTypeName);
                } else if (rowData) {
                    return rowData.costTypeName;
                }
            }
        }, {
            name: 'actCostMoney',
            display: '可勾稽金额',
            type: 'hidden'
        }, {
            name: 'costMoney',
            display: '本次勾稽金额',
            type: 'moneyAndNegative',
            event: {
                blur: function (e) {
                    var g = e.data.gird;
                    if (g.options.type != 'view') {
                        g.countShareMoney();
                    }
                }
            },
            process: function (v) {
                if (v < 0) {
                    return '<span class="red">' + moneyFormat2(v) + '</span>';
                } else {
                    return moneyFormat2(v);
                }
            },
            width: 80
        }]
    };

    $.fn.costShareGrid = function (options, other1, other2, other3, other4) {
        if (typeof(options) != 'object') {
            return $(this).costShareGirdInit(options, other1, other2, other3, other4);
        } else {
            //合并属性
            var options = $.extend(defaults, options);
            //支持选择器以及链式操作
            return this.each(function () {

                $(this).costShareGirdInit({
                    objName: options.objName,
                    url: options.url,
                    param: options.param,
                    type: options.type,
                    isAdd: false,
                    event: options.event,
                    async: options.async,
                    parentGrid: options.parentGrid,
                    countKey: options.countKey,
                    isShowCountRow: options.isShowCountRow,
                    isAddOneRow: false,
                    colModel: options.colModel
                });

                // 如果有传入这个参数，则附加分摊明细选择框
                if (options.showSelectWin && options.showSelectWin == true) {
                    $(this).costShareGirdInit('initSelectWin');
                }

                if (options.isShowCountRow == true) {
                    initCountRow($(this), options);
                }
            });
        }
    };

    // 初始化合计
    var initCountRow = function (g, options) {
        if (options.countKey != "") {
            g.find('tbody').after('<tr class="tr_count">' +
            '<td colspan="9"></td>' +
            '<td>剩余可分摊：' +
            '<input type="text" id="costShareCanShare" class="readOnlyTxtShortCount" readonly="readonly"/>' +
            '</td>' +
            '<td>合计</td>' +
            '<td>' +
            '<input type="text" id="costShareShared" class="readOnlyTxtShortCount" readonly="readonly"/>' +
            '</td>' +
            '</tr>');
        }
    };

    var shareObjTypeOptions = []; // 全局变量，保存分摊对象类型
    $.ajax({
        type: "POST",
        url: "?model=system_datadict_datadict&action=ajaxGetForEasyUI",
        data: {parentCode: 'FTDXLX', isUse: 0},
        async: false,
        success: function (data) {
            data = eval("(" + data + ")");
            var j;
            for (var i = 0; i < data.length; i++) {
                j = data[i].expand1;
                if (!shareObjTypeOptions[j]) {
                    shareObjTypeOptions[j] = [];
                }
                shareObjTypeOptions[j].push({text: data[i].text, value: data[i].id});
            }
        }
    });

    //初始化费用表格
    $.woo.yxeditgrid.subclass('woo.costShareGirdInit', {
        title: '费用分摊明细',
        realDel: false,
        width: '100%',
        tableClass: 'form_in_table',
        defaultClass: 'txt-auto',
        event: {
            removeRow: function (e, rowNum, rowData, index, g) {
                g.countShareMoney();
            },
            reloadData: function (e, g, data) {
                if (data) {
                    // 自动初始化
                    g.autoInitDetailType(data);

                    // 初始化合计
                    if (g.options.type != 'hookSelect') {
                        g.countShareMoney();
                    }
                } else if ((g.options.type == 'hook' || g.options.type == 'hookSelect') && !data) {
                    g.el.find('tbody').append("<tr><td colspan='99'> -- 没有可核销的分摊明细，需要补充分摊记录并且通知财务审核 -- </td></tr>");
                }

                // 如果是选择框
                if (g.options.type == 'hookSelect') {
                    g.initSelectButton();
                }
                
                // 加载全选按钮
                $("全选<input type='checkbox' id='checkAll' onclick='checkAll(this);'/>").appendTo("#costShareListGrid .main_tr_header th:first");
            }
        },
        /**
         * init select btn
         */
        initSelectButton: function () {
            var g = this;
            var btn = $("<input type='button' class='txt_btn_a' value='确认选择'/>");
            btn.bind('click', function () {
                if ($("#" + g.el.attr("id") + " input[id^='costShareCheckBox']:checked").length == 0) {
                    alert('至少选择一项分摊记录');
                    return false;
                }
                var k = g.options.parentGrid;

                var idMap = {}; // 保存当前列表所有的id
                $("[id^='" + k.el.attr('id') + "_cmp_id']").each(function () {
                    idMap[$(this).val()] = {
                        id: $(this).attr("id"),
                        rowNum: $(this).data('rowNum'),
                        isDel: !k.isRowDel($(this).data('rowNum'))
                    };
                });

                // 缓存对象选择框
                var costShareListGridObj = $("#costShareListGrid");

                // 设置确认选择的内容
                $("#" + g.el.attr("id") + " input[id^='costShareCheckBox']").each(function () {
                    if (!idMap[$(this).val()] && $(this).attr('checked') == true) {
                        // 这边做添加行处理
                        var nowRowNum = k.getAllAddRowNum();
                        // 临时变量处理
                        var tempData = $(this).data('rowData');
                        // 本次勾稽金额赋值
                        tempData.costMoney = costShareListGridObj.costShareGrid('getCmpByRowAndCol',
                            $(this).attr("rowNum"), 'costMoney', true).val();

                        k.addRow(nowRowNum, tempData);
                    } else if (idMap[$(this).val()] && $(this).attr('checked') == false) {
                        // 这边扣除行
                        k.removeRow(idMap[$(this).val()].rowNum);
                    } else if (idMap[$(this).val()] && idMap[$(this).val()].isDel == false && $(this).attr('checked') == true) {
                        // 对于假删除，但是后面要加回来的
                        k.resetRow(idMap[$(this).val()].rowNum);
                    }

                    // 如果有选中，则为父对象金额赋值
                    if (idMap[$(this).val()] && $(this).attr('checked') == true) {
                        // 获取行号
                        var rowNum = $(this).attr("rowNum");

                        // 本次勾稽金额
                        var costMoney = costShareListGridObj.costShareGrid('getCmpByRowAndCol', rowNum, 'costMoney', true).val();

                        // 金额赋值
                        k.setRowColValue(idMap[$(this).val()].rowNum, 'costMoney', costMoney, true);
                    }
                });
                k.countShareMoney();

                self.parent.tb_remove();
            });
            this.getTitleObj().append("&nbsp;").append(btn);
        },
        /**
         * init select window TODO
         */
        initSelectWin: function () {
            var g = this;
            var btn = $("<input type='button' class='txt_btn_a thickbox' value='选择分摊明细'" +
            " alt='#TB_inline?height=600&width=1050&inlineId=costShareListDiv' id='costShareListButton'/>");
            btn.bind('click', function () {
                // 动态生成费用类型选择
                var selectDiv = $("#costShareListDiv");
                if (selectDiv.length == 0) {
                    // 后面加载一个div用于保存费用类型选择项
                    g.el.after("<div id='costShareListDiv' style='display:none;'><div id='costShareListGrid'></div></div>");

                    //显示费用分摊明细
                    $("#costShareListGrid").costShareGrid({
                        url: "?model=finance_cost_costshare&action=hookSelectJson",
                        objName: "selectList",
                        param: g.options.param,
                        type: "hookSelect",
                        async: false,
                        showSelectWin: false,
                        isShowCountRow: false,
                        parentGrid: g,
                        colModel: [{ // 默认列
                            name: 'id',
                            display: 'id',
                            type: 'hidden'
                        }, {
                            name: 'oldId',
                            display: 'oldId',
                            type: 'hidden'
                        }, {
                            name: 'hookId',
                            display: 'hookId',
                            type: 'hidden'
                        }, {
                            name: 'mainId',
                            display: 'mainId',
                            type: 'hidden'
                        }, {
                            name: 'hookDetailId',
                            display: 'hookDetailId',
                            type: 'hidden'
                        }, {
                            name: 'objCode',
                            display: '源单编号',
                            type: 'hidden'
                        }, {
                            name: 'objType',
                            display: '源单类型',
                            type: 'hidden'
                        }, {
                            name: 'company',
                            display: '公司主体',
                            type: 'hidden'
                        }, {
                            name: 'companyName',
                            display: '公司主体',
                            type: 'hidden'
                        }, {
                            name: 'belongCompany',
                            display: '归属公司',
                            type: 'hidden'
                        }, {
                            name: 'belongCompanyName',
                            display: '归属公司',
                            width: 70,
                            type: 'hidden'
                        }, {
                            name: 'inPeriod',
                            display: '费用入账期间',
                            width: 70
                        }, {
                            name: 'belongPeriod',
                            display: '费用归属期间',
                            width: 70
                        }, {
                            name: 'module',
                            display: '所属板块编号',
                            width: 60,
                            type: 'hidden'
                        }, {
                            name: 'moduleName',
                            display: '所属板块',
                            width: 60,
                            type: 'statictext'
                        }, {
                            name: 'detailType',
                            display: '费用类型',
                            width: 80,
                            process: function (html, rowData, $tr, g) {
                                return g.getDetailTypeCN(html);
                            }
                        }, {
                            name: 'shareObjType',
                            display: '分摊对象类型',
                            width: 80,
                            type: 'select',
                            datacode: 'FTDXLX',
                            options: [],
                            process: function (html, rowData, $tr, g) {
                                return g.getDatadictValue('shareObjType', rowData.shareObjType);
                            }
                        }, {
                            name: 'defaultShareObjType',
                            display: 'defaultShareObjType',
                            type: 'hidden'
                        }, {
                            name: 'costShareObj',
                            display: '分摊对象',
                            width: 120,
                            align: 'left',
                            type: 'statictext'
                        }, {
                            name: 'costShareObjExtends',
                            display: '关联信息',
                            width: 130,
                            align: 'left',
                            type: 'statictext'
                        }, {
                            name: 'parentTypeId',
                            display: 'parentTypeId',
                            type: 'hidden'
                        }, {
                            name: 'parentTypeName',
                            display: '费用明细上级',
                            type: 'hidden'
                        }, {
                            name: 'costTypeId',
                            display: 'costTypeId',
                            type: 'hidden'
                        }, {
                            name: 'costTypeName',
                            display: '费用明细',
                            width: 80
                        }, {
                            name: 'actCostMoney',
                            display: '原始分摊金额',
                            width: 70,
                            type: 'statictext',
                            process: function (v) {
                                return moneyFormat2(v);
                            }
                        }, {
                            name: 'hookMoney',
                            display: '已勾稽金额',
                            width: 70,
                            type: 'statictext',
                            process: function (v) {
                                return moneyFormat2(v);
                            }
                        }, {
                            name: 'unHookMoney',
                            display: '未勾稽金额',
                            width: 70,
                            type: 'statictext',
                            process: function (v) {
                                return moneyFormat2(v);
                            }
                        }, {
                            name: 'costMoney',
                            display: '本次勾稽金额',
                            type: 'moneyAndNegative',
                            align: 'right',
                            process: function (v) {
                                if (v < 0) {
                                    return '<span class="red">' + moneyFormat2(v) + '</span>';
                                } else {
                                    return moneyFormat2(v);
                                }
                            },
                            width: 70
                        }]
                    });
                }
                // 为checkbox赋值
                g.getCmpByCol("id").each(function () {
                    var checkBoxObj = $("#costShareCheckBox" + $(this).val());

                    // 选中
                    checkBoxObj.attr("checked", true);

                    // 行号获取
                    var rowNum = checkBoxObj.attr('rowNum');

                    // 金额获取
                    var costMoney = g.getCmpByRowAndCol($(this).data('rowNum'), 'costMoney', true).val();

                    // 金额赋值
                    $("#costShareListGrid").costShareGrid('setRowColValue', rowNum, 'costMoney', costMoney, true);
                });
            });
            this.getTitleObj().append("&nbsp;").append(btn);
            tb_init('#costShareListButton'); // 使其具有thickbox功能
        },
        /**
         * get detail type cn
         * @param v
         * @returns {*}
         */
        getDetailTypeCN: function (v) {
            switch (v) {
                case '1' :
                    return '部门费用';
                case '2' :
                    return '合同项目费用';
                case '3' :
                    return '研发费用';
                case '4' :
                    return '售前费用';
                case '5' :
                    return '售后费用';
                default :
                    return v;
            }
        },
        /**
         * get datadict value
         * @param type
         * @param val
         */
        getDatadictValue: function (type, val) {
            var dataArr = this.datadict[type].datadictData;
            for (var i = 0; i < dataArr.length; i++) {
                if (dataArr[i].dataCode == val) {
                    return dataArr[i].dataName;
                }
            }
        },
        /**
         * 可勾选的记录
         * @param rowNum
         * @param shareObjType
         * @param rowData
         */
        initRowSelect: function (rowNum, shareObjType, rowData) {
            var checkBox = $('<input type="checkbox" name="costShareListCheckBox" id="costShareCheckBox' + rowData.id
            + '" value="' + rowData.id + '" rowNum="' + rowNum + '"/>');
            checkBox.data('rowData', rowData);
            this.getCmpByRowAndCol(rowNum, "removeBn").hide().after(checkBox);
            this.getCmpByRowAndCol(rowNum, "belongCompany").hide().after(rowData.belongCompanyName);
            this.getCmpByRowAndCol(rowNum, "belongPeriod").hide().after(rowData.belongPeriod);
            this.getCmpByRowAndCol(rowNum, "inPeriod").hide().after(rowData.inPeriod);
            this.getCmpByRowAndCol(rowNum, "detailType").hide().after(this.getDetailTypeCN(rowData.detailType));
            this.getCmpByRowAndCol(rowNum, "shareObjType").hide()
                .after(this.getDatadictValue('shareObjType', rowData.shareObjType));
            this.getCmpByRowAndCol(rowNum, "costTypeName").hide().after(rowData.costTypeName);
            this.getCmpByRowAndCol(rowNum, "costShareObj")
                .append(this.initShareObjView(rowData.shareObjType, rowNum, rowData));
            this.getCmpByRowAndCol(rowNum, "costShareObjExtends")
                .append(this.initShareObjExtendsHook(rowData.shareObjType, rowNum, rowData));
        },
        /**
         * 已勾稽记录处理
         * @param rowNum
         * @param shareObjType
         * @param rowData
         */
        initRowHooked: function (rowNum, shareObjType, rowData) {
            this.getCmpByRowAndCol(rowNum, "removeBn").hide();
            this.getCmpByRowAndCol(rowNum, "belongCompany").hide().after(rowData.belongCompanyName);
            this.getCmpByRowAndCol(rowNum, "belongPeriod").hide().after(rowData.belongPeriod);
            this.getCmpByRowAndCol(rowNum, "inPeriod").hide().after(rowData.inPeriod);
            this.getCmpByRowAndCol(rowNum, "detailType").hide().after(this.getDetailTypeCN(rowData.detailType));
            this.getCmpByRowAndCol(rowNum, "shareObjType").hide()
                .after(this.getDatadictValue('shareObjType', rowData.shareObjType));
            this.getCmpByRowAndCol(rowNum, "costTypeName").hide().after(rowData.costTypeName);
            this.getCmpByRowAndCol(rowNum, "costMoney").hide().after(moneyFormat2(rowData.costMoney));
            this.getCmpByRowAndCol(rowNum, "costShareObj")
                .append(this.initShareObjView(rowData.shareObjType, rowNum, rowData));
            this.getCmpByRowAndCol(rowNum, "costShareObjExtends")
                .append(this.initShareObjExtendsHook(rowData.shareObjType, rowNum, rowData));
        },
        /**
         * init share object info
         * @param shareObjType
         * @param rowNum
         * @param rowData
         */
        initShareObjView: function (shareObjType, rowNum, rowData) {
            switch (shareObjType) {
	            case 'FTDXLX-01' : // 部门费用
	                return rowData.belongDeptName;
	            case 'FTDXLX-03' : // 合同销售项目费用
	            case 'FTDXLX-02' : // 合同工程项目费用
	            case 'FTDXLX-04' : // 合同研发项目费用
	            case 'FTDXLX-05' : // 售前费用 - 试用项目
	                return rowData.projectCode + '<div style="margin: 2px 0"></div>'
	                	+ rowData.feeMan;
	            case 'FTDXLX-10' : // 研发费用
	                // return rowData.projectCode;
                    return rowData.projectName;// 研发项目的分摊对象显示项目名称 PMS 2579
	            case 'FTDXLX-06' :  // 售前费用 - 商机
	                return rowData.chanceCode + '<div style="margin: 2px 0"></div>'
	                	+ rowData.feeMan;
	            case 'FTDXLX-07' : // 售前费用 - 客户
	                return rowData.customerName + '<div style="margin: 2px 0"></div>'
	                    + rowData.belongDeptName + '<div style="margin: 2px 0"></div>'
	                    + rowData.feeMan;
	            case 'FTDXLX-08' : // 售前费用 - 省份/客户类型/归属部门
	                return rowData.province + '<div style="margin: 2px 0"></div>'
	                    + rowData.customerType + '<div style="margin: 2px 0"></div>'
	                    + rowData.belongDeptName + '<div style="margin: 2px 0"></div>'
	                    + rowData.feeMan;
	            case 'FTDXLX-09' :// 售后费用 - 合同
	                return rowData.contractCode + '<div style="margin: 2px 0"></div>'
	                    + rowData.belongDeptName + '<div style="margin: 2px 0"></div>'
	                    + rowData.feeMan;
	            case 'FTDXLX-11' :// 合同项目费用 - 合同
	                return rowData.contractCode;
	            default :
	        }
        },
        /**
         * init share object extends info
         * @param shareObjType
         * @param rowNum
         * @param rowData
         */
        initShareObjExtendsView: function (shareObjType, rowNum, rowData) {
            switch (shareObjType) {
				case 'FTDXLX-01' : // 部门费用
					if (rowData.projectName != "") {
						return '工作组：' + rowData.projectName;
					}
					break;
	            case 'FTDXLX-05' : // 售前费用 - 试用项目
	                // 关联信息扩展
	                return '商机：' + rowData.chanceCode + '<br/>'
	                    + '客户：' + rowData.customerName + '<br/>'
	                    + '省份：' + rowData.province + '<br/>'
	                    + '客户类型：' + rowData.customerType + '<br/>'
	                    + '归属部门：' + rowData.belongDeptName + '<br/>'
	                	+ '销售区域：' + rowData.salesArea;
	                break;
	            case 'FTDXLX-06' :  // 售前费用 - 商机
	                return '客户：' + rowData.customerName + '<br/>'
	                    + '省份：' + rowData.province + '<br/>'
	                    + '客户类型：' + rowData.customerType + '<br/>'
	                    + '归属部门：' + rowData.belongDeptName + '<br/>'
	                	+ '销售区域：' + rowData.salesArea;
	                break;
	            case 'FTDXLX-07' : // 售前费用 - 客户
	                return '省份：' + rowData.province + '<br/>'
	                    + '客户类型：' + rowData.customerType + '<br/>'
	                	+ '销售区域：' + rowData.salesArea;
	                break;
	            case 'FTDXLX-08' : // 售前费用 - 省份/客户类型/归属部门
	                return '销售区域：' + rowData.salesArea;
	                break;
	            case 'FTDXLX-09' : // 售后费用 - 合同
	                return '销售区域：' + rowData.salesArea;
	                break;
	            case 'FTDXLX-11' : // 合同项目费用 - 合同
	                return '客户：' + rowData.customerName + '<br/>'
	                    + '省份：' + rowData.province + '<br/>'
	                    + '客户类型：' + rowData.customerType + '<br/>'
	                    + '归属部门：' + rowData.belongDeptName + '<br/>'
	                	+ '销售区域：' + rowData.salesArea;
	                break;
	            default :
	        }
        },
        /**
         * init share object extends info  for hook
         * @param shareObjType
         * @param rowNum
         * @param rowData
         */
        initShareObjExtendsHook: function (shareObjType, rowNum, rowData) {
            var str = "";
            switch (shareObjType) {
				case 'FTDXLX-01' : // 部门费用
					if (rowData.projectName != "") {
						return '工作组：' + rowData.projectName;
					}
					break;
                case 'FTDXLX-05' : // 售前费用 - 试用项目
                    // 关联信息扩展
                    str += '商机：' + rowData.chanceCode + '<br/>'
	                    + '客户：' + rowData.customerName + '<br/>'
	                    + '省份：' + rowData.province + '<br/>'
	                    + '客户类型：' + rowData.customerType + '<br/>'
	                    + '归属部门：' + rowData.belongDeptName + '<br/>'
	                    + '销售区域：' + rowData.salesArea;
                    break;
                case 'FTDXLX-06' :  // 售前费用 - 商机
                    str += '客户：' + rowData.customerName + '<br/>'
	                    + '省份：' + rowData.province + '<br/>'
	                    + '客户类型：' + rowData.customerType + '<br/>'
	                    + '归属部门：' + rowData.belongDeptName + '<br/>'
	                    + '销售区域：' + rowData.salesArea;
                    break;
                case 'FTDXLX-07' : // 售前费用 - 客户
                    str += '省份：' + rowData.province + '<br/>'
	                    + '客户类型：' + rowData.customerType + '<br/>'
	                    + '销售区域：' + rowData.salesArea;
                    break;
                case 'FTDXLX-08' : // 售前费用 - 省份/客户类型/归属部门
                	str += '销售区域：' + rowData.salesArea;
                    break;
                case 'FTDXLX-09' : // 售后费用 - 合同
                	str += '销售区域：' + rowData.salesArea;
                    break;
                case 'FTDXLX-11' : // 合同项目费用 - 合同
                	str += '客户：' + rowData.customerName + '<br/>'
                        + '省份：' + rowData.province + '<br/>'
                        + '客户类型：' + rowData.customerType + '<br/>'
                        + '归属部门：' + rowData.belongDeptName + '<br/>'
                    	+ '销售区域：' + rowData.salesArea;
                    break;
                default :
            }
            // append share info
            var g = this;
            var tbId = g.el.attr('id');
            var objName = g.options.objName;
            str += '<input type="hidden" id="' + tbId + '_cmp_belongDeptName' + rowNum + '" name="' + objName + '[' + rowNum + '][belongDeptName]" value="' + rowData.belongDeptName + '"/>'
            + '<input type="hidden" id="' + tbId + '_cmp_belongDeptId' + rowNum + '" name="' + objName + '[' + rowNum + '][belongDeptId]" value="' + rowData.belongDeptId + '"/>'
            + '<input type="hidden" id="' + tbId + '_cmp_projectId' + rowNum + '" name="' + objName + '[' + rowNum + '][projectId]" value="' + rowData.projectId + '"/>'
            + '<input type="hidden" id="' + tbId + '_cmp_projectName' + rowNum + '" name="' + objName + '[' + rowNum + '][projectName]" value="' + rowData.projectName + '"/>'
            + '<input type="hidden" id="' + tbId + '_cmp_projectCode' + rowNum + '" name="' + objName + '[' + rowNum + '][projectCode]" value="' + rowData.projectCode + '"/>'
            + '<input type="hidden" id="' + tbId + '_cmp_projectType' + rowNum + '" name="' + objName + '[' + rowNum + '][projectType]" value="' + rowData.projectType + '"/>'
            + '<input type="hidden" id="' + tbId + '_cmp_chanceId' + rowNum + '" name="' + objName + '[' + rowNum + '][chanceId]" value="' + rowData.chanceId + '"/>'
            + '<input type="hidden" id="' + tbId + '_cmp_chanceCode' + rowNum + '" name="' + objName + '[' + rowNum + '][chanceCode]" value="' + rowData.chanceCode + '"/>'
            + '<input type="hidden" id="' + tbId + '_cmp_customerId' + rowNum + '" name="' + objName + '[' + rowNum + '][customerId]" value="' + rowData.customerId + '"/>'
            + '<input type="hidden" id="' + tbId + '_cmp_customerName' + rowNum + '" name="' + objName + '[' + rowNum + '][customerName]" value="' + rowData.customerName + '"/>'
            + '<input type="hidden" id="' + tbId + '_cmp_customerType' + rowNum + '" name="' + objName + '[' + rowNum + '][customerType]" value="' + rowData.customerType + '"/>'
            + '<input type="hidden" id="' + tbId + '_cmp_province' + rowNum + '" name="' + objName + '[' + rowNum + '][province]" value="' + rowData.province + '"/>'
            + '<input type="hidden" id="' + tbId + '_cmp_contractId' + rowNum + '" name="' + objName + '[' + rowNum + '][contractId]" value="' + rowData.contractId + '"/>'
            + '<input type="hidden" id="' + tbId + '_cmp_contractCode' + rowNum + '" name="' + objName + '[' + rowNum + '][contractCode]" value="' + rowData.contractCode + '"/>'
            + '<input type="hidden" id="' + tbId + '_cmp_belongName' + rowNum + '" name="' + objName + '[' + rowNum + '][belongName]" value="' + rowData.belongName + '"/>'
            + '<input type="hidden" id="' + tbId + '_cmp_belongId' + rowNum + '" name="' + objName + '[' + rowNum + '][belongId]" value="' + rowData.belongId + '"/>'
            + '<input type="hidden" id="' + tbId + '_cmp_feeManId' + rowNum + '" name="' + objName + '[' + rowNum + '][feeManId]" value="' + rowData.feeManId + '"/>'
            + '<input type="hidden" id="' + tbId + '_cmp_feeMan' + rowNum + '" name="' + objName + '[' + rowNum + '][feeMan]" value="' + rowData.feeMan + '"/>'
            + '<input type="hidden" id="' + tbId + '_cmp_salesAreaId' + rowNum + '" name="' + objName + '[' + rowNum + '][salesAreaId]" value="' + rowData.salesAreaId + '"/>'
            + '<input type="hidden" id="' + tbId + '_cmp_salesArea' + rowNum + '" name="' + objName + '[' + rowNum + '][salesArea]" value="' + rowData.salesArea + '"/>';

            return str;
        },
        /**
         * 自动初始化 - 不含清楚
         * @param data
         */
        autoInitDetailType: function (data) {
            for (var i = 0; i < data.length; i++) {
                // 对于已经勾稽的记录，不允许再执行其他操作
                if (this.options.type == 'hookSelect') {
                    this.initRowSelect(i, this.getCmpByRowAndCol(i, "shareObjType").val(), data[i]);
                }
            }
        },
        /**
         * form validate
         * @return {boolean}
         */
        checkForm: function (formMoney, needEqual) {
            formMoney = Number(formMoney); // 强制类型转换
            if (needEqual == undefined) needEqual = true;
            var g = this, el = this.el, p = this.options;
            var shareObjTypeArray = $("[id^='" + el.attr('id') + "_cmp_shareObjType']"); // 因为勾稽部分使用的是隐藏内容，所以这里要特殊处理
            var rs = true; // 验证返回值
            var shareMoney = 0; // 分摊金额
            if (shareObjTypeArray.length > 0) {
                shareObjTypeArray.each(function () {
                    var rowNum = $(this).data('rowNum');
                    if (g.isRowDel(rowNum) == false) {
                        var showNum = Number(rowNum) + 1;

                        var costMoney = g.getCmpByRowAndCol(rowNum, "costMoney", true).val();
                        var actCostMoney = g.getCmpByRowAndCol(rowNum, "actCostMoney", true).val(); // 这项是实际可勾稽金额
                        if (costMoney == "" || parseFloat(costMoney) == 0) {
                            alert('费用分摊明细表第【' + showNum + '】行金额不能为空或0');
                            rs = false;
                            return false;
                        }

                        if (parseFloat(actCostMoney) < parseFloat(costMoney)) {
                            alert('费用分摊明细表第【' + showNum + '】行的本次勾稽金额已经大于可勾稽金额');
                            rs = false;
                            return false;
                        }

                        shareMoney = accAdd(shareMoney, costMoney, 2);
                    }
                });

                // 如果传入了金额，则需要进行金额验证
                if (formMoney && rs == true && formMoney != shareMoney) {
                    // 强制要求金额一致
                    if (needEqual == true) {
                        alert('分摊金额【' + shareMoney + '】与单据金额【' + formMoney + '】不一致！');
                        rs = false;
                    } else if (formMoney < shareMoney) {
                        alert('分摊金额【' + shareMoney + '】不能大于单据金额【' + formMoney + '】');
                        rs = false;
                    } else {
                        rs = confirm('分摊金额【' + shareMoney + '】小于单据金额【' + formMoney + '】，确认提交吗？');
                    }
                }
            } else {
                // 强制要求金额一致
                if (needEqual == true) {
                    alert('需要填写分摊金额');
                    rs = false;
                } else {
                    rs = confirm('没有费用分摊明细，确定要提交单据吗？');
                }
            }
            return rs;
        },
        /**
         * 重置一行删除的数据
         * @param rowNum
         */
        resetRow: function (rowNum) {
            var g = this, el = this.el, p = this.options;
            var $tr = $(el).find("tr[rowNum='" + rowNum + "']");
            if ($tr.length > 0) {
                var index = $tr.data("index");
                var rowData = $tr.data("rowData");
                $("#" + el.attr('id') + "_cmp_" + p.delTagName + index).remove();
                g.curShowRowNum++;
                $tr.show();
                // 更改序号
                $tr.nextAll("tr").find("td[type='rowNum']").each(function (v) {
                    var index = $(this).html();
                    index = parseInt(index);
                    if (index > rowNum) {
                        index++;
                        $(this).html(index);
                    }
                });
            }
            g.processRowNum();
        },
        /**
         * 计算分摊金额
         */
        countShareMoney: function () {
            var g = this;
            if (g.options.countKey != "") {
                var formMoney = $("#" + g.options.countKey).val();
                var sharedMoney = 0;
                var costMoneyArr = g.getCmpByCol('costMoney');
                if (costMoneyArr.length > 0) {
                    costMoneyArr.each(function () {
                        if ($(this).val() != "") {
                            sharedMoney = accAdd(sharedMoney, $(this).val(), 2);
                        }
                    });
                    $("#costShareShared").val(moneyFormat2(sharedMoney));
                }

                if (formMoney != '' && parseFloat(formMoney) != 0) {
                    var canShareMoney = accSub(formMoney, sharedMoney, 2);
                    $("#costShareCanShare").val(moneyFormat2(canShareMoney));
                }
            }
        },
        /**
         * 变更金额合计字段
         * @param newKey
         */
        changeCountKey: function (newKey) {
            var g = this, p = this.options;
            if (newKey != p.countKey) {
                p.countKey = newKey;
                g.countShareMoney();
            }
        },
        /**
         * 查看页面的统计金额查看
         * @param data
         */
        costShareMoneyView: function (data) {
            var g = this;
            var sharedMoney = 0;
            for (var i = 0; i < data.length; i++) {
                sharedMoney = accAdd(sharedMoney, data[i].costMoney, 2);
            }
            g.el.find('tbody').after('<tr class="tr_count">' +
            '<td colspan="9"></td>' +
            '<td>合计</td>' +
            '<td>' +
            moneyFormat2(sharedMoney) +
            '</td>' +
            '</tr>');
        }
    });
})(jQuery);

//全选
function checkAll(obj){
	var checked = $(obj).attr("checked");
	$(obj).parents("#costShareListGrid .form_in_table:first").find("tbody input[type='checkbox']").each(function(){
		$(this).attr("checked",checked);
	});
}