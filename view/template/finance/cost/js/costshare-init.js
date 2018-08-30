var getFeeMansSelectOpts = function (){
    var opts = "";
    var feemanDefaultId = ($("#feemanDefaultId").val() != undefined)? $("#feemanDefaultId").val() : '';
    if($("#feemansForXtsSales").val() != undefined){
        var feemansForXtsSales = $("#feemansForXtsSales").val();
        var feemansForXtsSalesArr = feemansForXtsSales.split(",");
        if(feemansForXtsSalesArr .length > 0){
            $.each(feemansForXtsSalesArr,function(i,item){
                if(item != ''){
                    var itemArr = item.split(":");
                    if(feemanDefaultId != ''){
                        opts += (itemArr[0] == feemanDefaultId)? "<option value = '"+itemArr[0]+"' data-name = '"+itemArr[1]+"' selected>"+itemArr[1]+"</option>" : "<option value = '"+itemArr[0]+"' data-name = '"+itemArr[1]+"'>"+itemArr[1]+"</option>";
                    }else{
                        opts += (opts == "")? "<option value = '"+itemArr[0]+"' data-name = '"+itemArr[1]+"' selected>"+itemArr[1]+"</option>" : "<option value = '"+itemArr[0]+"' data-name = '"+itemArr[1]+"'>"+itemArr[1]+"</option>";
                    }
                }
            })
        }
    }
    return opts;
}

// 设置费用承担人信息
var setFeeManInfo = function (rowNum){
    var sltObj = '#feeMansSelect_'+rowNum;
    var idObj = '#shareGrid_cmp_feeManId'+rowNum;
    var nameObj = '#shareGrid_cmp_feeMan'+rowNum;

    var seletedVal = $(sltObj).find("option:selected");
    $(idObj).val(seletedVal.val());
    $(nameObj).val(seletedVal.attr('data-name'));
}

var timestamp;
/**
 * Created by show on 14-5-6.
 * 费用分摊组件
 */
(function ($) {
    //默认属性
    var defaults = {
        title: '费用分摊信息',
        objName: '', // 业务名称
        type: 'edit', // 显示类型 '' 空, edit 编辑, view 查看, audit 审核, change 变更
        url: '', // 路径
        param: {}, // 参数
        isAdd: true, // 是否显示新增按钮
        event: {}, // 自定义事件
        async: true, // 异步
        isShowExcelBtn: false, // 是否显示excel导入按钮
        isShowCountRow: false, // 是否显示合计栏
        countKey: '', // 合计栏计算的字段
        thisPeriod: '' // 当前财务周期
    };

    $.fn.costShareGrid = function (options, other1, other2) {
        if (typeof(options) != 'object') {
            return $(this).costShareGirdInit(options, other1, other2);
        } else {
            //合并属性
            var options = $.extend(defaults, options);
            //支持选择器以及链式操作
            return this.each(function () {
                $(this).costShareGirdInit({
                    title: options.title,
                    objName: options.objName,
                    url: options.url,
                    param: options.param,
                    type: options.type,
                    isAdd: options.isAdd,
                    event: options.event,
                    async: options.async,
                    countKey: options.countKey,
                    isShowCountRow: options.isShowCountRow,
                    thisPeriod: options.thisPeriod,
                    colModel: getColModel(options)
                });

                if (options.isShowExcelBtn == true) {
                    initExcelButton($(this));
                }

                if (options.isShowCountRow == true) {
                    initCountRow($(this), options);
                }

                // 如果是审核页面，则初始话全选按钮的事件
                if (options.type == "audit") {
                    initCheckAll($(this), options);
                }
            });
        }
    };

    var toShowImportPage = function(url){
        // 如有禁止选择的费用明细,带到导入页面去
        var extParamStr = '';
        var mainTypeSlted = $("#payForBusinessMain").find("option:selected");
        if(mainTypeSlted != undefined && mainTypeSlted.val() != ''){
            var mainTypeCode = mainTypeSlted.val();
            extParamStr = '&payForBusiness='+mainTypeCode;
        }

        url += extParamStr + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900";
        showThickboxWin(url);
    };

    // 初始化excel导入按钮
    var initExcelButton = function (g) {
        g.find('td.form_header').append('&nbsp;<input type="button" class="txt_btn_a" value="EXCEL导入" ' +
        'id="costShareExcelButton"/>' +
		'&nbsp;<span style="color:red">如果分摊数据过多，请使用合同的编辑页面的快捷导入功能进行导入。</span>');
        $("#costShareExcelButton").click(function(){
            var url = '?model=finance_cost_costshare&action=importExcel';
            toShowImportPage(url);
        });
    };

    // 初始化合计
    var initCountRow = function (g, options) {
        if (options.countKey != "") {
            var normalRow = options.type == "audit" ? 10 : 9;
            g.find('tbody').after('<tr class="tr_count">' +
            '<td colspan="' + normalRow + '" id="showOther"></td>' +
            '<td>可分摊：' +
            '<input type="text" id="costShareCanShare" class="readOnlyTxtShortCount" readonly="readonly"/>' +
            '</td>' +
            '<td>合计</td>' +
            '<td>' +
            '<input type="text" id="costShareShared" class="readOnlyTxtShortCount" readonly="readonly"/>' +
            '</td>' +
            '</tr>');
        }
    };

    // 初始化全选事件
    var initCheckAll = function (g, options) {
        $("#cmp_checkAll").click(function() {
            if ($(this).attr("checked")) {
                $("input[id^='shareGrid_cmp_check']").attr("checked", true);
            } else {
                $("input[id^='shareGrid_cmp_check']").attr("checked", false);
            }
        });
    };

    // 获取列头
    var getColModel = function(options) {
        return [{
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
            name: 'check',
            display: "<input type='checkbox' id='cmp_checkAll' checked title='取消选中后，对应的行不会被审核。'>",
            type: options.type == 'audit' ? 'statictext' : 'hidden',
            width: 20,
            process: function (html, rowData, $tr, g, $input, rowNum) {
                return "<input type='checkbox'  value='1' id='shareGrid_cmp_check" + rowNum +
                    "' name='costshare[detail][" + rowNum + "][check]' checked title='取消选中后，对应的行不会被审核。'>";
            },
            value: 1
        }, {
            name: 'module',
            display: '所属板块',
            width: 65,
            type: 'select',
            datacode: 'HTBK',
            emptyOption: true,
            event: {
                'change': function () {
                    var g = $(this).data("grid");
                    var rowNum = $(this).data('rowNum');
                    g.getCmpByRowAndCol(rowNum, 'moduleName').val($(this).find("option:selected").text());
                    var shareObjType = g.getCmpByRowAndCol(rowNum, 'shareObjType').val();
                    if(shareObjType == 'FTDXLX-07' || shareObjType == 'FTDXLX-08'){
                        // 销售负责人
                        g.setAreaInfo(rowNum, g.getCmpByRowAndCol(rowNum, "customerType").val(),
                            g.getCmpByRowAndCol(rowNum, "province").val(),
                            g.getCmpByRowAndCol(rowNum, "belongCompanyName").val(),
                            $(this).find("option:selected").text());
                    }
                }
            }
        }, {
            name: 'moduleName',
            display: '所属板块名称',
            type: 'hidden'
        }, {
            name: 'belongCompany',
            display: '归属公司',
            width: 80,
            type: "select",
            options: companyOptions,
            event: {
                change: function () {
                    var g = $(this).data("grid");
                    var rowNum = $(this).data('rowNum');
                    g.getCmpByRowAndCol(rowNum, 'belongCompanyName').val($(this).find("option:selected").text());
                    var shareObjType = g.getCmpByRowAndCol(rowNum, 'shareObjType').val();
                    if(shareObjType == 'FTDXLX-07' || shareObjType == 'FTDXLX-08'){
                        // 销售负责人
                        g.setAreaInfo(rowNum, g.getCmpByRowAndCol(rowNum, "customerType").val(),
                            g.getCmpByRowAndCol(rowNum, "province").val(),
                            $(this).find("option:selected").text(),
                            g.getCmpByRowAndCol(rowNum, "moduleName").val());
                    }
                }
            },
            process: function (html, rowData) {
                if (rowData) {
                    return rowData.belongCompanyName;
                }
            }
        }, {
            name: 'belongCompanyName',
            display: '归属公司',
            type: 'hidden'
        }, {
            name: 'inPeriod',
            display: '费用入账期间',
            width: 80,
            type: 'select',
            otherValue: true,
            options: periodNoOptions,
            event: {
                change: function () {
                    var g = $(this).data("grid");
                    var p = g.options;
                    if (p.thisPeriod != '') {
                        g.countThisPeriodWaitAudit();
                    }
                }
            }
        }, {
            name: 'belongPeriod',
            display: '费用归属期间',
            width: 80
        }, {
            name: 'detailType',
            display: '费用类型',
            width: 100,
            type: 'select',
            options: [{
                name: '部门费用',
                value: '1'
            }, {
                name: '合同项目费用',
                value: '2'
            }, {
                name: '研发费用',
                value: '3'
            }, {
                name: '售前费用',
                value: '4'
            }, {
                name: '售后费用',
                value: '5'
            }],
            event: {
                change: function () {
                    //费用类型变更时相应的对相关字段做处理
                    var g = $(this).data("grid");
                    g.initDetailType($(this).val(), $(this).data('rowNum'));
                }
            },
            process: function (html, rowData, $tr, g) {
                return g.getDetailTypeCN(html);
            }
        }, {
            name: 'shareObjType',
            display: '分摊对象类型',
            width: 100,
            type: 'select',
            datacode: 'FTDXLX',
            options: [],
            event: {
                change: function () {
                    //费用类型变更时相应的对相关字段做处理
                    var g = $(this).data("grid");
                    var rowNum = $(this).data('rowNum');
                    g.initShareObj(rowNum, g.getCmpByRowAndCol(rowNum, "shareObjType").val());
                }
            },
            process: function (html, rowData, $tr, g) {
                if (rowData) {
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
            width: 150,
            align: 'left',
            type: 'statictext',
            process: function (html, rowData, $tr, g, $input, rowNum) {
                if (rowData && g.options.type == 'view') {
                    return g.initShareObjView(rowData.shareObjType, rowNum, rowData);
                }
            }
        }, {
            name: 'costShareObjExtends',
            display: '关联信息',
            width: 150,
            align: 'left',
            type: 'statictext',
            process: function (html, rowData, $tr, g, $input, rowNum) {
                if (rowData && g.options.type == 'view') {
                    return g.initShareObjExtendsView(rowData.shareObjType, rowNum, rowData);
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
            width: 100,
            readonly: true,
            event: {
                click: function (e) {
                    var g = e.data.gird;
                    var rowNum = e.data.rowNum;
                    if (g.options.type != 'view') {
                        g.showFeeType(rowNum);
                    }
                }
            }
        }, {
            name: 'costMoney',
            display: '分摊金额',
            type: 'moneyAndNegative',
            align: 'right',
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
        }];
    };

    //获取公司选项
    var companyOptions;
    $.ajax({
        type: "POST",
        url: "?model=deptuser_branch_branch&action=listForSelect",
        async: false,
        success: function (data) {
            companyOptions = eval("(" + data + ")");
        }
    });

    //账期获取
    var periodNoOptions;
    var periodNoCurArr; // 当前财务期数组，以'.'分隔
    $.ajax({
        type: "POST",
        url: "?model=finance_period_period&action=getNextOneYearPeriod",
        data : {type : 'cost'},
        async: false,
        success: function (data) {
            periodNoOptions = eval("(" + data + ")");
            periodNoCurArr = periodNoOptions[0].value.split('.');// 账期第一个选项为当前财务期
        }
    });

    var provinceArray; // 省份缓存
    var customerTypeArray; // 客户类型
    var saleDeptArray; // 售前部门
    var contractDeptArray; // 售后部门

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
		otherPageMoney: 0,
        thisPeriodWaitAudit: 0,
        thisPeriod: '',
        colModel: [],
		/**
		 * 处理表格初始化数据
		 */
		processData: function() {
			var g = this, el = this.el, p = this.options;
			// 处理静态数据
			if (p.data.length > 0) {
				g.reloadData(p.data);
			} else if (p.url) {// 后台异步处理
				$.ajax({
					type: 'POST',
					url: p.url,
					data: p.param,
					async: p.async !== false,
					dataType: 'json',
					success: function(data) {
						data = data ? data : [];

						if (p.type == 'audit') {
							data = data ? data : [];

							var suitData = [];
							for (var i = 0; i < data.length; i++) {

								if (data[i].auditStatus == "2") {
									suitData.push(data[i]);

                                    if (p.thisPeriod != '' && data[i].inPeriod == p.thisPeriod) {
                                        g.thisPeriodWaitAudit = accAdd(g.thisPeriodWaitAudit, data[i].costMoney, 2);
                                    }
								} else {
									g.otherPageMoney = accAdd(g.otherPageMoney, data[i].costMoney, 2);
								}
							}

							g.reloadData(suitData);
							$(el).trigger('reloadData', [g, suitData]);
						} else {
							g.reloadData(data);
							$(el).trigger('reloadData', [g, data]);
						}
					}
				});
			}
		},
		// 重写add row
		addRow: function(rowNum, rowData) {
			var g = this, el = this.el, p = this.options;
			var elId = el.attr('id');
			rowNum = rowNum ? rowNum : 0;
			g.curRowNum++;
			g.curShowRowNum++;
			g.allAddRowNum++;
			var $tr = $("<tr class='tr_even'>");
			$tr.trigger('beforeAddRow', [rowNum, rowData, g]);
			var $removeBn = $('<span class="removeBn" id="' + elId + "_cmp_removeBn" + rowNum + '">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>');
			// 第一行是否显示删除按钮并且不是是第一行 并且不是查看?型 ?K且isAddAndDel??true
			if (p.type != 'view' && p.isAddAndDel == true) {
				if (p.isFristRowDenyDel == true && rowNum == 0) {
					$removeBn.hide();
				}
				var $opTd = $("<td valign='top'>");
				$opTd.append($removeBn);
				var $h = $("<input type='hidden' name='" + p.objName + "["
				+ rowNum + "][rowNum_]' value='" + rowNum + "'>");
				$opTd.append($h);
				$tr.append($opTd);
			}

			// 序号
			var hideStyle = p.hideRowNum == false ? '' : 'style="display:none;"';
			var $indexTd = $("<td valign='top' type='rowNum' " + hideStyle + ">");
			$indexTd.append(g.curRowNum);
			$tr.append($indexTd);
			$tr.data("index", rowNum);
			$tr.attr("rowNum", rowNum);
			$tr.data("rowData", rowData);

			$removeBn.click(function() {
				var n = $(this).parent().parent().attr("rowNum");
				g.isRemoveAction = true;
				$tr.trigger('beforeRemoveRow', [rowNum, rowData, g]);
				if (g.isRemoveAction !== false) {// 支持在事件里面设置此参数控制是否删除
					g.removeRow(n);
				}
			});

			// TODO 想办法先在行内渲染完成
			g.tbody.append($tr);

			// 新增前事件
			$tr.trigger('beforeAddRow', [rowNum, rowData, g]);

			// 缓存
			var colLength = p.colModel.length;
			for (var i = 0; i < colLength; i++) {
				var config = p.colModel[i];
				var emptyOption = config.emptyOption ? config.emptyOption : false;//选择首行空项
				var type = config.type ? config.type : p.defaultType;// 控件类型
				var tclass = config.tclass ? config.tclass : p.defaultClass;// 控件样式
				var name = config.name;
				var val = rowData ? rowData[name] : config.value;
				if (config.staticVal) {
					val = config.staticVal;
				}
				var cmpId = elId + "_cmp_" + name + rowNum;// 控件id
				var cmpName = p.objName + "[" + rowNum + "][" + name + "]";// 控件名称
				var $input;

				// 逻辑调整：如果是查看/变更，则呈现为静态模式
				if (config.type != 'hidden') {
					if (p.type == 'view') {
						type = "statictext";
						config.tclass = "";
					} else if (p.type == 'view' && rowData) {
						type = "statictext";
						config.tclass = "";
					}
				}

				switch (type) {
					case 'select' :// 下拉选择
						$input = $("<select>");
						var optionStr = "";
						var option;
						var selected;
						// 空选项
						if (emptyOption == true) {
							optionStr += "<option value=''></option>";
						}
						// 处理数据字典
						if (config.datacode) {
							var data = g.datadict[name].datadictData;
							if (data) {
								for (var d = 0; d < data.length; d++) {
									option = data[d];
									selected = val == option.dataCode ? selected = "selected" : "";
									optionStr += "<option " + selected
									+ " value='" + option.dataCode
									+ "' title='" + option.dataName
									+ "'>" + option.dataName
									+ "</option>";
								}
							}
						} else {// 处理静态数据
							if (config.options) {
                                var otherValue = true;
								for (var j = 0; j < config.options.length; j++) {
									option = config.options[j];
                                    if (val == option.value) {
                                        selected = "selected";
                                        otherValue = false;
                                    } else {
                                        selected = "";
                                    }
									optionStr += "<option " + selected
									+ " title='" + option.name
									+ "' value='" + option.value + "'>"
									+ option.name + "</option>";
								}
                                if (val && otherValue && config.otherValue) {
                                	var isAddOption = true;
                                	if(name == 'inPeriod'){// 费用入账期间处理，当入账期间大于当前可选财务期时，才追加选项，否则默认为当前财务期
                                		var valArr = val.split('.');
                                        var yearNum = Number(valArr[0]);// 单据周期年
                                        var monthNum = Number(valArr[1]);// 单据周期月
                                        var thisYearNum = Number(periodNoCurArr[0]);// 当前周期年
                                        var thisMonthNum = Number(periodNoCurArr[1]);// 当前周期月
                                        // if(valArr[0] < periodNoCurArr[0] || (valArr[0] == periodNoCurArr[0] && valArr[1] < periodNoCurArr[1])){
                                        if(yearNum < thisYearNum || (yearNum == thisYearNum && monthNum < thisMonthNum)){
                                            isAddOption = false;
                                        }
                                	}
                                	if(isAddOption){
                                        optionStr += "<option " + selected
                                        + " title='" + val
                                        + "' value='" + val + "'>"
                                        + val + "</option>";
                                	}
                                }
							}
						}
						$input.append(optionStr);
						break;
					case 'statictext' :// 静态文本
						if (!config.tclass) {
							tclass = "";// 静态文本不能用默认的样式
						}
						$input = $("<div   class='divChangeLine'>");
						var html = config.html;
						var oldHtml = html;
						if (!html) {
							html = val;
							oldHtml = val;
						}
						if (config.process) {
							html = config
								.process(html, rowData, $tr, g, $input, rowNum);
						}
						$input.append(html);
						// 如果需要提交值，动态创建一个隐藏域
						if (config.isSubmit) {
							var $h = $("<input type='hidden'>");
							$h.attr('name', cmpName);
							$h.val(oldHtml);
							$input.append($h);
						}
						break;
					case 'hidden' :// 隐藏域
						$input = $("<input type='hidden'>");
						break;
					default :
						$input = $("<input type='text'>");
						break;
				}
				if (val) {
					$input.val(val);
				}
				// attr
				$input.attr('id', cmpId);
				$input.addClass(tclass);
				$input.attr('name', cmpName);
				$input.attr("readonly", config.readonly);
				// 加入初始值存储,提供变更时判断是否已经修改
				$input.data("oldVal", $input.val());
				if (config.validation) {
					$input.validation(config.validation);
				}
				// 给控件加入属性
				$input.data("rowNum", rowNum);// 第几行
				$input.data("colNum", i);// 第几列
				$input.data("grid", g);

				var $td = $("<td valign='top'>");
				if (type == 'hidden') {
					$td.hide();
				}
				$td.append($input).css("text-align",
					config.align ? config.align : "center");
				$tr.append($td);
				if (type != 'statictext' && config.process) {
					config.process($input, rowData, $tr, g);
				}

				// 金额处理
				if (type == 'moneyAndNegative') { // 负数金额
					$input.attr("etype", "moneyAndNegative");
					createFormatOnClick($input.attr("id"), null, null, null, 2, true);
				}
				// 事件处理
				if (config.event) {
					for (var e in config.event) {
						$input.bind(e, {
							rowData: rowData,
							rowNum: rowNum,
							colNum: i,
							gird: g
						}, config.event[e]);
					}
				}
				if (config.width) {
					if ((config.width + "").indexOf("%") > 0) {
						$input.width('95%');
					} else {
						$input.width(config.width);
					}
				}
			}

			// 触发行后时间
			$tr.trigger('addRow', [rowNum, rowData, g, $tr]);
		},
        event: {
            'clickAddRow': function (e, rowNum, g) {
                if (rowNum == 0) {
                    g.getCmpByRowAndCol(rowNum, 'belongCompany').trigger('change');
                    g.initDetailType(g.getCmpByRowAndCol(rowNum, 'detailType').val(), rowNum);
                } else {
                    g.initNext(rowNum);
                }

                // 至少保留一条数据,不得删除
                var pageAct = $("#pageAct").val();
                if(pageAct == 'edit' || pageAct == 'add'){
                    $("[id^='shareGrid_cmp_removeBn']").each(function(i,item){
                        if(g.curRowNum > 1){
                            $(item).show();
                        }else{
                            $(item).hide();
                        }
                    });
                }
            },
            'removeRow': function (e, rowNum, rowData, index, g) {
                if (g.options.countKey != "") {
                    g.countShareMoney();
                }

                if(g.curRowNum <= 1){
                    var pageAct = $("#pageAct").val();
                    if(pageAct == 'edit' || pageAct == 'add'){
                        $("[id^='shareGrid_cmp_removeBn']").each(function(i,item){
                            $(item).hide();
                        });
                    }
                }
            },
            'reloadData': function (e, g, data) {
                if (data) {
                    //自动初始化
                    g.autoInitDetailType(data);

                    if (g.options.url != "" && g.options.countKey != "") {
                        g.countShareMoney();
                    }
//					console.log((new Date()).valueOf() - timestamp);
                }
            }
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
         * 初始化分摊对象模型
         * @param detailType
         * @param rowNum
         * @param rowData
         */
        initDetailType: function (detailType, rowNum, rowData) {
            // 当审核状态非0（保存）时，显示为只读
            if ((this.options.type == 'change' || this.options.type == 'edit') && rowData
                && rowData.auditStatus != "0" && rowData.auditStatus != "3") {
                this.initRowChange(rowNum, rowData.shareObjType, rowData);
            } else if (this.options.type == 'audit' && rowData && rowData.auditStatus == "1") {
                this.initRowChange(rowNum, this.getCmpByRowAndCol(rowNum, "shareObjType").val(), rowData);
            } else {
                // 初始化分摊对象类型的选项
                var optionStr = '';
                detailType = (detailType == undefined)? 1 : detailType;
                var tmpShareObjType = shareObjTypeOptions[detailType];
                for (var i = 0; i < tmpShareObjType.length; i++) {
                    if ((rowData && rowData.shareObjType == tmpShareObjType[i].value)
                    		|| tmpShareObjType[i].value == this.getCmpByRowAndCol(rowNum, "defaultShareObjType").val()) {
                        optionStr += "<option value='" + tmpShareObjType[i].value + "' selected='selected'>" +
                        tmpShareObjType[i].text + "</option>";
                    } else {
                        optionStr += "<option value='" + tmpShareObjType[i].value + "'>" +
                        tmpShareObjType[i].text + "</option>";
                    }
                }
                this.getCmpByRowAndCol(rowNum, "shareObjType").empty().append(optionStr);
                // 初始化分摊对象以及关联信息
                if ((this.options.type == 'edit' || this.options.type == 'change' || this.options.type == 'audit')) {
                	if (rowData) {
                        this.initShareObj(rowNum, rowData.shareObjType, rowData);
                	} else {
                        this.initShareObj(rowNum, this.getCmpByRowAndCol(rowNum, "shareObjType").val(), rowData);
                	}
                }
            }
        },
        /**
         * 已勾稽记录处理
         * @param rowNum
         * @param shareObjType
         * @param rowData
         */
        initRowChange: function (rowNum, shareObjType, rowData) {
            this.getCmpByRowAndCol(rowNum, "removeBn").hide();
            this.getCmpByRowAndCol(rowNum, "belongCompany").hide().after(rowData.belongCompanyName);
            this.getCmpByRowAndCol(rowNum, "belongPeriod").hide().after(rowData.belongPeriod);
            this.getCmpByRowAndCol(rowNum, "inPeriod").empty()
                .append("<option value='" + rowData.inPeriod + "'>" + rowData.inPeriod + "</option>")
                .hide().after(rowData.inPeriod);
            this.getCmpByRowAndCol(rowNum, "detailType").hide().after(this.getDetailTypeCN(rowData.detailType));
            this.getCmpByRowAndCol(rowNum, "shareObjType").hide()
                .after(this.getDatadictValue('shareObjType', rowData.shareObjType));
            this.getCmpByRowAndCol(rowNum, "costTypeName").hide().after(rowData.costTypeName);
            this.getCmpByRowAndCol(rowNum, "costMoney").hide().after(moneyFormat2(rowData.costMoney));
            this.getCmpByRowAndCol(rowNum, "costShareObj")
                .append(this.initShareObjView(rowData.shareObjType, rowNum, rowData));
            this.getCmpByRowAndCol(rowNum, "costShareObjExtends")
                .append(this.initShareObjExtendsChange(rowData.shareObjType, rowNum, rowData));
        },
        /**
         * 渲染分摊对象
         * @param rowNum
         * @param shareObjType
         * @param rowData
         */
        initShareObj: function (rowNum, shareObjType, rowData) {
            if (!rowData) {
                rowData = {
                    deptId: '',
                    deptName: '',
                    projectId: '',
                    projectCode: '',
                    projectName: '',
                    chanceId: '',
                    chanceCode: '',
                    province: '',
                    customerType: '',
                    contractId: '',
                    contractCode: '',
                    contractName: '',
                    belongId: '',
                    belongName: '',
                    belongDeptId: '',
                    belongDeptName: '',
                    projectType: '',
                    customerName: '',
                    customerId: '',
                    salesAreaId: '',
                    salesArea: '',
                    feeMan: '',
                    feeManId: ''
                };
            }
            var appendStr;
            var g = this;
            var tbId = g.el.attr('id');
            var objName = g.options.objName;
            var textWidth = g.getCmpByRowAndCol(rowNum, "costShareObj").width();
            // 做一些清除工作
            g.getCmpByRowAndCol(rowNum, "belongDeptName").yxselect_dept('remove');
            g.getCmpByRowAndCol(rowNum, "projectCode").yxcombogrid_esmproject('remove').yxcombogrid_projectall('remove').yxcombogrid_rdprojectfordl('remove');
            g.getCmpByRowAndCol(rowNum, "projectName").yxcombogrid_esmproject('remove').yxcombogrid_projectall('remove').yxcombogrid_rdprojectfordl('remove');
            g.getCmpByRowAndCol(rowNum, "customerName").yxcombogrid_customer('remove');
            g.getCmpByRowAndCol(rowNum, "costShareObj").empty();
            g.getCmpByRowAndCol(rowNum, "costShareObjExtends").empty();
            switch (shareObjType) {
                case 'FTDXLX-01' : // 部门费用
                    appendStr = '<input type="hidden" id="' + tbId + '_cmp_projectId' + rowNum + '" name="' + objName + '[' + rowNum + '][projectId]" value="' + rowData.projectId + '"/>'
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
                    + '<input type="hidden" id="' + tbId + '_cmp_contractName' + rowNum + '" name="' + objName + '[' + rowNum + '][contractName]" value="' + rowData.contractName + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_belongName' + rowNum + '" name="' + objName + '[' + rowNum + '][belongName]" value="' + rowData.belongName + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_belongId' + rowNum + '" name="' + objName + '[' + rowNum + '][belongId]" value="' + rowData.belongId + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_belongDeptId' + rowNum + '" name="' + objName + '[' + rowNum + '][belongDeptId]" value="' + rowData.belongDeptId + '"/>'
                    + '<input type="text" class="txtmiddle" id="' + tbId + '_cmp_belongDeptName' + rowNum + '" name="' + objName + '[' + rowNum + '][belongDeptName]" value="' + rowData.belongDeptName + '"/>'
                    ;
                    textWidth = textWidth - 40;
                    g.getCmpByRowAndCol(rowNum, "costShareObj").append(appendStr);
                    var unSltDeptFilterStr = ($('#unSltDeptFilter').val() != undefined)? $('#unSltDeptFilter').val() : "";
                    g.getCmpByRowAndCol(rowNum, "belongDeptName").yxselect_dept({
                        hiddenId: g.getCmpByRowAndCol(rowNum, "belongDeptId").attr("id"),
            			disableDeptLevel: '1', // 禁用选择一级部门
                        unDeptFilter: ($('#unDeptFilter').val() != undefined)? $('#unDeptFilter').val() : '',
                        unSltDeptFilter : unSltDeptFilterStr
                    }).width(textWidth).attr("readonly", true);

					// 关联信息扩展
					var extendStr = '工作组：<input type="text" class="txtmiddle" style="width: 100px;" id="' + tbId + '_cmp_projectName' + rowNum + '" name="' + objName + '[' + rowNum + '][projectName]" value="' + rowData.projectName + '" readonly="readonly"/>'
					g.getCmpByRowAndCol(rowNum, "costShareObjExtends").append(extendStr);

					// 工作组
					g.getCmpByRowAndCol(rowNum, "projectName").yxcombogrid_esmproject({
						hiddenId: g.getCmpByRowAndCol(rowNum, "projectId").attr('id'),
						nameCol: 'projectName',
						height: 250,
						isFocusoutCheck: false,
						gridOptions: {
							param: {attribute: 'GCXMSS-04', statusArr: 'GCXMZT02,GCXMZT04,GCXMZT00'},
							comboEx: false,
							event: {
								row_dblclick: function (e, row, data) {
									g.getCmpByRowAndCol(rowNum, "projectCode").val(data.projectCode);
									g.getCmpByRowAndCol(rowNum, "projectType").val(projectType);
								}
							}
						},
						event: {
							clear: function () {
								g.getCmpByRowAndCol(rowNum, "projectCode").val('');
								g.getCmpByRowAndCol(rowNum, "projectType").val('');
							}
						}
					}).width(80);
                    break;
                case 'FTDXLX-03' : // 合同销售项目费用
                    appendStr = '<input type="text" class="txtmiddle" id="' + tbId + '_cmp_projectCode' + rowNum + '" name="' + objName + '[' + rowNum + '][projectCode]" value="' + rowData.projectCode + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_projectId' + rowNum + '" name="' + objName + '[' + rowNum + '][projectId]" value="' + rowData.projectId + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_projectName' + rowNum + '" name="' + objName + '[' + rowNum + '][projectName]" value="' + rowData.projectName + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_projectType' + rowNum + '" name="' + objName + '[' + rowNum + '][projectType]" value="' + rowData.projectType + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_chanceId' + rowNum + '" name="' + objName + '[' + rowNum + '][chanceId]" value="' + rowData.chanceId + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_chanceCode' + rowNum + '" name="' + objName + '[' + rowNum + '][chanceCode]" value="' + rowData.chanceCode + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_customerId' + rowNum + '" name="' + objName + '[' + rowNum + '][customerId]" value="' + rowData.customerId + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_customerName' + rowNum + '" name="' + objName + '[' + rowNum + '][customerName]" value="' + rowData.customerName + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_customerType' + rowNum + '" name="' + objName + '[' + rowNum + '][customerType]" value="' + rowData.customerType + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_province' + rowNum + '" name="' + objName + '[' + rowNum + '][province]" value="' + rowData.province + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_contractId' + rowNum + '" name="' + objName + '[' + rowNum + '][contractId]" value="' + rowData.contractId + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_contractCode' + rowNum + '" name="' + objName + '[' + rowNum + '][contractCode]" value="' + rowData.contractCode + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_contractName' + rowNum + '" name="' + objName + '[' + rowNum + '][contractName]" value="' + rowData.contractName + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_belongName' + rowNum + '" name="' + objName + '[' + rowNum + '][belongName]" value="' + rowData.belongName + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_belongId' + rowNum + '" name="' + objName + '[' + rowNum + '][belongId]" value="' + rowData.belongId + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_belongDeptId' + rowNum + '" name="' + objName + '[' + rowNum + '][belongDeptId]" value="' + rowData.belongDeptId + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_belongDeptName' + rowNum + '" name="' + objName + '[' + rowNum + '][belongDeptName]" value="' + rowData.belongDeptName + '"/>'
                    ;
                    textWidth = textWidth - 23;
                    g.getCmpByRowAndCol(rowNum, "costShareObj").append(appendStr);
                    break;
                case 'FTDXLX-02' : // 合同工程项目费用
                case 'FTDXLX-04' : // 合同研发项目费用
                case 'FTDXLX-10' : // 研发费用
                    appendStr = '<input type="hidden" id="' + tbId + '_cmp_projectId' + rowNum + '" name="' + objName + '[' + rowNum + '][projectId]" value="' + rowData.projectId + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_projectType' + rowNum + '" name="' + objName + '[' + rowNum + '][projectType]" value="' + rowData.projectType + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_chanceId' + rowNum + '" name="' + objName + '[' + rowNum + '][chanceId]" value="' + rowData.chanceId + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_chanceCode' + rowNum + '" name="' + objName + '[' + rowNum + '][chanceCode]" value="' + rowData.chanceCode + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_customerId' + rowNum + '" name="' + objName + '[' + rowNum + '][customerId]" value="' + rowData.customerId + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_customerName' + rowNum + '" name="' + objName + '[' + rowNum + '][customerName]" value="' + rowData.customerName + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_customerType' + rowNum + '" name="' + objName + '[' + rowNum + '][customerType]" value="' + rowData.customerType + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_province' + rowNum + '" name="' + objName + '[' + rowNum + '][province]" value="' + rowData.province + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_contractId' + rowNum + '" name="' + objName + '[' + rowNum + '][contractId]" value="' + rowData.contractId + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_contractCode' + rowNum + '" name="' + objName + '[' + rowNum + '][contractCode]" value="' + rowData.contractCode + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_contractName' + rowNum + '" name="' + objName + '[' + rowNum + '][contractName]" value="' + rowData.contractName + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_belongName' + rowNum + '" name="' + objName + '[' + rowNum + '][belongName]" value="' + rowData.belongName + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_belongId' + rowNum + '" name="' + objName + '[' + rowNum + '][belongId]" value="' + rowData.belongId + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_belongDeptId' + rowNum + '" name="' + objName + '[' + rowNum + '][belongDeptId]" value="' + rowData.belongDeptId + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_belongDeptName' + rowNum + '" name="' + objName + '[' + rowNum + '][belongDeptName]" value="' + rowData.belongDeptName + '"/>'
                    ;
                    textWidth = textWidth - 23;
                    var projectType = shareObjType == 'FTDXLX-02' ? 'esm' : 'rd'; // 判断项目类型
                    if (shareObjType == 'FTDXLX-02') {
                    	appendStr += '<input type="text" class="txtmiddle" id="' + tbId + '_cmp_projectCode' + rowNum + '" name="' + objName + '[' + rowNum + '][projectCode]" value="' + rowData.projectCode + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_projectName' + rowNum + '" name="' + objName + '[' + rowNum + '][projectName]" value="' + rowData.projectName + '"/>';
                        g.getCmpByRowAndCol(rowNum, "costShareObj").append(appendStr);
                    	g.getCmpByRowAndCol(rowNum, "projectCode").yxcombogrid_projectall({
                            hiddenId: g.getCmpByRowAndCol(rowNum, "projectId").attr('id'),
                            valueCol: 'projectId',
                            nameCol: 'projectCode',
                            height: 250,
                            isFocusoutCheck: false,
                            gridOptions: {
                                param: {contractType: 'GCXMYD-01'},
                                comboEx: false,
                                event: {
                                    'row_dblclick': function (e, row, data) {
                                        g.getCmpByRowAndCol(rowNum, "projectName").val(data.projectName);
                                        g.getCmpByRowAndCol(rowNum, "projectType").val(projectType);
                                        //重置费用归属部门
                                        g.getCmpByRowAndCol(rowNum, "belongDeptId").val(data.deptId);
                                        g.getCmpByRowAndCol(rowNum, "belongDeptName").val(data.deptName);
                                    }
                                }
                            },
                            event: {
                                'clear': function () {
                                    g.getCmpByRowAndCol(rowNum, "projectName").val('');
                                    g.getCmpByRowAndCol(rowNum, "projectType").val('');
                                    //重置费用归属部门
                                    g.getCmpByRowAndCol(rowNum, "belongDeptId").val('');
                                    g.getCmpByRowAndCol(rowNum, "belongDeptName").val('');
                                }
                            }
                        }).width(textWidth);
                    } else {
                    	appendStr += '<input type="hidden" class="txtmiddle" id="' + tbId + '_cmp_projectCode' + rowNum + '" name="' + objName + '[' + rowNum + '][projectCode]" value="' + rowData.projectCode + '"/>'
                        + '<input type="text" id="' + tbId + '_cmp_projectName' + rowNum + '" name="' + objName + '[' + rowNum + '][projectName]" value="' + rowData.projectName + '"/>';
                        g.getCmpByRowAndCol(rowNum, "costShareObj").append(appendStr);
                        var paramRdProject = {'is_delete': 0};
                        if (shareObjType == 'FTDXLX-04') {
                            paramRdProject['project_type'] = 4;
                        } else {
                            paramRdProject['project_typeNo'] = 4;
                        }
                        paramRdProject['attribute']='GCXMSS-05';
                        paramRdProject['statusArr']='GCXMZT02,GCXMZT04,GCXMZT00';

                        if(shareObjType == 'FTDXLX-10'){
                            paramRdProject['statusArr']='GCXMZT02,GCXMZT04';
                            paramRdProject['setStatusComboEx'] = 'true';// 设置过滤选项为完工和在建
                        }

                        g.getCmpByRowAndCol(rowNum, "projectName").yxcombogrid_esmproject({
                            hiddenId: g.getCmpByRowAndCol(rowNum, "projectId").attr('id'),
                            nameCol: 'projectName',
                            height: 250,
                            isFocusoutCheck: false,
                            gridOptions: {
                                comboEx: false,
                                param: paramRdProject,
                                event: {
                                    'row_dblclick': function (e, row, data) {
                                        g.getCmpByRowAndCol(rowNum, "projectCode").val(data.projectCode);
                                        g.getCmpByRowAndCol(rowNum, "projectType").val(projectType);
                                        //重置费用归属部门
                                        g.getCmpByRowAndCol(rowNum, "belongDeptId").val(data.deptId);
                                        g.getCmpByRowAndCol(rowNum, "belongDeptName").val(data.deptName);
                                    }
                                }
                            },
                            event: {
                                'clear': function () {
                                    g.getCmpByRowAndCol(rowNum, "projectCode").val('');
                                    g.getCmpByRowAndCol(rowNum, "projectType").val('');
                                    //重置费用归属部门
                                    g.getCmpByRowAndCol(rowNum, "belongDeptId").val('');
                                    g.getCmpByRowAndCol(rowNum, "belongDeptName").val('');
                                }
                            }
                        }).width(textWidth);
                    }
                    break;
                case 'FTDXLX-05' : // 售前费用 - 试用项目
                    appendStr = '<input type="text" class="txtmiddle" id="' + tbId + '_cmp_projectCode' + rowNum + '" name="' + objName + '[' + rowNum + '][projectCode]" value="' + rowData.projectCode + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_projectId' + rowNum + '" name="' + objName + '[' + rowNum + '][projectId]" value="' + rowData.projectId + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_projectName' + rowNum + '" name="' + objName + '[' + rowNum + '][projectName]" value="' + rowData.projectName + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_projectType' + rowNum + '" name="' + objName + '[' + rowNum + '][projectType]" value="' + rowData.projectType + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_chanceId' + rowNum + '" name="' + objName + '[' + rowNum + '][chanceId]" value="' + rowData.chanceId + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_customerId' + rowNum + '" name="' + objName + '[' + rowNum + '][customerId]" value="' + rowData.customerId + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_contractId' + rowNum + '" name="' + objName + '[' + rowNum + '][contractId]" value="' + rowData.contractId + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_contractCode' + rowNum + '" name="' + objName + '[' + rowNum + '][contractCode]" value="' + rowData.contractCode + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_contractName' + rowNum + '" name="' + objName + '[' + rowNum + '][contractName]" value="' + rowData.contractName + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_belongName' + rowNum + '" name="' + objName + '[' + rowNum + '][belongName]" value="' + rowData.belongName + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_belongId' + rowNum + '" name="' + objName + '[' + rowNum + '][belongId]" value="' + rowData.belongId + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_belongDeptId' + rowNum + '" name="' + objName + '[' + rowNum + '][belongDeptId]" value="' + rowData.belongDeptId + '"/><div style="margin: 2px 0"></div>'
                    + '<input type="text" class="txtmiddle" id="' + tbId + '_cmp_feeMan' + rowNum + '" name="' + objName + '[' + rowNum + '][feeMan]" value="' + rowData.feeMan + '" readonly="readonly" style="display:none;"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_feeManId' + rowNum + '" name="' + objName + '[' + rowNum + '][feeManId]" value="' + rowData.feeManId + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_salesAreaId' + rowNum + '" name="' + objName + '[' + rowNum + '][salesAreaId]" value="' + rowData.salesAreaId + '"/>'
                    ;
                    textWidth = textWidth - 23;
                    g.getCmpByRowAndCol(rowNum, "costShareObj").append(appendStr);
                    var params = {'contractType': 'GCXMYD-04', 'statusArr': 'GCXMZT02,GCXMZT04,GCXMZT00'};
                    if(shareObjType == 'FTDXLX-05'){
                        params['statusArr']='GCXMZT02,GCXMZT04';
                        params['setStatusComboEx'] = 'true';// 设置过滤选项为完工和在建
                    }
                    g.getCmpByRowAndCol(rowNum, "projectCode").yxcombogrid_esmproject({
                        hiddenId: g.getCmpByRowAndCol(rowNum, "projectId").attr('id'),
                        nameCol: 'projectCode',
                        height: 250,
                        isFocusoutCheck: false,
                        gridOptions: {
                            param: params,
                            event: {
                                'row_dblclick': function (e, row, data) {
                                    g.getCmpByRowAndCol(rowNum, "projectName").val(data.projectName);
                                    g.getCmpByRowAndCol(rowNum, "projectType").val('esm');

                                    // 获取试用项目Id
                                    var trialProjectInfo = g.getTrialProject(data.contractId);
                                    if (trialProjectInfo != false) {
                                        g.getCmpByRowAndCol(rowNum, "chanceId").val(trialProjectInfo.chanceId);
                                        g.getCmpByRowAndCol(rowNum, "chanceCode").val(trialProjectInfo.chanceCode);
                                        g.getCmpByRowAndCol(rowNum, "customerName").val(trialProjectInfo.customerName);
                                        g.getCmpByRowAndCol(rowNum, "customerId").val(trialProjectInfo.customerId);
                                        g.getCmpByRowAndCol(rowNum, "province").val(trialProjectInfo.province);
                                        g.getCmpByRowAndCol(rowNum, "customerType").val(trialProjectInfo.customerTypeName);
                                        g.getCmpByRowAndCol(rowNum, "salesArea").val(trialProjectInfo.areaName);
                                        g.getCmpByRowAndCol(rowNum, "salesAreaId").val(trialProjectInfo.areaCode);
                                        //重置费用归属部门
                                        g.getCmpByRowAndCol(rowNum, "belongDeptId").val(trialProjectInfo.deptId);
                                        g.getCmpByRowAndCol(rowNum, "belongDeptName").val(trialProjectInfo.deptName);
                                        // 渲染费用承担人
                                        g.dealFeeMan(rowNum, trialProjectInfo.deptId, textWidth - 17);
                                    } else {
                                        //重置费用归属部门
                                        g.getCmpByRowAndCol(rowNum, "chanceId").val('');
                                        g.getCmpByRowAndCol(rowNum, "chanceCode").val('');
                                        g.getCmpByRowAndCol(rowNum, "customerName").val('');
                                        g.getCmpByRowAndCol(rowNum, "customerId").val('');
                                        g.getCmpByRowAndCol(rowNum, "province").val('');
                                        g.getCmpByRowAndCol(rowNum, "customerType").val('');
                                        g.getCmpByRowAndCol(rowNum, "salesArea").val('');
                                        g.getCmpByRowAndCol(rowNum, "salesAreaId").val('');
                                        g.getCmpByRowAndCol(rowNum, "belongDeptId").val('');
                                        g.getCmpByRowAndCol(rowNum, "belongDeptName").val('');
                                    }
                                }
                            }
                        },
                        event: {
                            'clear': function () {
                                g.getCmpByRowAndCol(rowNum, "projectName").val('');
                                g.getCmpByRowAndCol(rowNum, "projectType").val('');
                                //重置费用归属部门
                                g.getCmpByRowAndCol(rowNum, "belongDeptId").val('');
                                g.getCmpByRowAndCol(rowNum, "belongDeptName").val('');
                                g.getCmpByRowAndCol(rowNum, "chanceId").val('');
                                g.getCmpByRowAndCol(rowNum, "chanceCode").val('');
                                g.getCmpByRowAndCol(rowNum, "customerName").val('');
                                g.getCmpByRowAndCol(rowNum, "customerId").val('');
                                g.getCmpByRowAndCol(rowNum, "province").val('');
                                g.getCmpByRowAndCol(rowNum, "customerType").val('');
                                g.getCmpByRowAndCol(rowNum, "salesArea").val('');
                                g.getCmpByRowAndCol(rowNum, "salesAreaId").val('');
                        		// 渲染费用承担人
                                g.dealFeeMan(rowNum, '', textWidth - 17);
                            }
                        }
                    }).width(textWidth);
                    // 关联信息扩展
                    var extendStr = '商机：<input type="text" class="rimless_textB" style="width: 110px;" id="' + tbId + '_cmp_chanceCode' + rowNum + '" name="' + objName + '[' + rowNum + '][chanceCode]" value="' + rowData.chanceCode + '" readonly="readonly"/><br/>'
                        + '客户：<input type="text" class="rimless_textB" style="width: 110px;" id="' + tbId + '_cmp_customerName' + rowNum + '" name="' + objName + '[' + rowNum + '][customerName]" value="' + rowData.customerName + '" readonly="readonly"/><br/>'
                        + '省份：<input type="text" class="rimless_textB" style="width: 110px;" id="' + tbId + '_cmp_province' + rowNum + '" name="' + objName + '[' + rowNum + '][province]" value="' + rowData.province + '" readonly="readonly"/><br/>'
                        + '客户类型：<input type="text" class="rimless_textB" style="width: 90px;" id="' + tbId + '_cmp_customerType' + rowNum + '" name="' + objName + '[' + rowNum + '][customerType]" value="' + rowData.customerType + '" readonly="readonly"/><br/>'
                        + '归属部门：<input type="text" class="rimless_textB" style="width: 90px;" id="' + tbId + '_cmp_belongDeptName' + rowNum + '" name="' + objName + '[' + rowNum + '][belongDeptName]" value="' + rowData.belongDeptName + '" readonly="readonly"/><br/>'
                        + '销售区域：<input type="text" class="rimless_textB" style="width: 90px;" id="' + tbId + '_cmp_salesArea' + rowNum + '" name="' + objName + '[' + rowNum + '][salesArea]" value="' + rowData.salesArea + '" readonly="readonly"/>';
                    g.getCmpByRowAndCol(rowNum, "costShareObjExtends").append(extendStr);
            		// 渲染费用承担人
                    if(rowData.belongDeptId != ''){
                        g.dealFeeMan(rowNum, rowData.belongDeptId, textWidth - 17, 'init');
                    }
                    break;
                case 'FTDXLX-06' :  // 售前费用 - 商机
                    appendStr = '<input type="text" class="txtmiddle" id="' + tbId + '_cmp_chanceCode' + rowNum + '" name="' + objName + '[' + rowNum + '][chanceCode]" value="' + rowData.chanceCode + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_chanceId' + rowNum + '" name="' + objName + '[' + rowNum + '][chanceId]" value="' + rowData.chanceId + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_projectId' + rowNum + '" name="' + objName + '[' + rowNum + '][projectId]" value="' + rowData.projectId + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_projectCode' + rowNum + '" name="' + objName + '[' + rowNum + '][projectCode]" value="' + rowData.projectCode + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_projectName' + rowNum + '" name="' + objName + '[' + rowNum + '][projectName]" value="' + rowData.projectName + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_projectType' + rowNum + '" name="' + objName + '[' + rowNum + '][projectType]" value="' + rowData.projectType + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_chanceId' + rowNum + '" name="' + objName + '[' + rowNum + '][chanceId]" value="' + rowData.chanceId + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_customerId' + rowNum + '" name="' + objName + '[' + rowNum + '][customerId]" value="' + rowData.customerId + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_contractId' + rowNum + '" name="' + objName + '[' + rowNum + '][contractId]" value="' + rowData.contractId + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_contractCode' + rowNum + '" name="' + objName + '[' + rowNum + '][contractCode]" value="' + rowData.contractCode + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_contractName' + rowNum + '" name="' + objName + '[' + rowNum + '][contractName]" value="' + rowData.contractName + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_belongName' + rowNum + '" name="' + objName + '[' + rowNum + '][belongName]" value="' + rowData.belongName + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_belongId' + rowNum + '" name="' + objName + '[' + rowNum + '][belongId]" value="' + rowData.belongId + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_belongDeptId' + rowNum + '" name="' + objName + '[' + rowNum + '][belongDeptId]" value="' + rowData.belongDeptId + '"/><div style="margin: 2px 0"></div>'
                    + '<input type="text" class="txtmiddle" id="' + tbId + '_cmp_feeMan' + rowNum + '" name="' + objName + '[' + rowNum + '][feeMan]" value="' + rowData.feeMan + '" readonly="readonly" style="display:none;"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_feeManId' + rowNum + '" name="' + objName + '[' + rowNum + '][feeManId]" value="' + rowData.feeManId + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_salesAreaId' + rowNum + '" name="' + objName + '[' + rowNum + '][salesAreaId]" value="' + rowData.salesAreaId + '"/>'
                    ;
                    g.getCmpByRowAndCol(rowNum, "costShareObj").append(appendStr);
                    //编号搜索渲染
                    var codeObj = g.getCmpByRowAndCol(rowNum, "chanceCode");
                    if (codeObj.attr('wchangeTag2')) {
                        return false;
                    }
                    var $button = $("<span class='search-trigger' title='商机编号'>&nbsp;</span>");
                    $button.click(function () {
                        if (codeObj.val() == "") {
                            alert('请输入一个商机编号');
                            return false;
                        }
                    });
                    textWidth = textWidth - 40;
                    //添加清空按钮
                    var $button2 = $("<span class='clear-trigger' title='点击清空数据'>&nbsp;</span>");
                    $button2.click(function () {
                        codeObj.val('');
                		// 渲染费用承担人
                        g.dealFeeMan(rowNum, '', textWidth);
                    });
                    codeObj.bind('blur', function () {
                        g.getChanceInfo(rowNum);
                		// 渲染费用承担人
                        g.dealFeeMan(rowNum, g.getCmpByRowAndCol(rowNum, "belongDeptId").val(), textWidth);
            			//如果出现费用承担人，则默认为销售负责人
            			if(g.getCmpByRowAndCol(rowNum, "feeMan").is(':visible')){
            				g.getCmpByRowAndCol(rowNum, "feeMan").val(g.getCmpByRowAndCol(rowNum, "belongName").val());
            				g.getCmpByRowAndCol(rowNum, "feeManId").val(g.getCmpByRowAndCol(rowNum, "belongId").val());
            			}
                    }).after($button2).after($button).attr("wchangeTag2", true).width(textWidth);

                    // 关联信息扩展
                    var extendStr = '客户：<input type="text" class="rimless_textB" style="width: 110px;" id="' + tbId + '_cmp_customerName' + rowNum + '" name="' + objName + '[' + rowNum + '][customerName]" value="' + rowData.customerName + '" readonly="readonly"/><br/>'
                        + '省份：<input type="text" class="rimless_textB" style="width: 110px;" id="' + tbId + '_cmp_province' + rowNum + '" name="' + objName + '[' + rowNum + '][province]" value="' + rowData.province + '" readonly="readonly"/><br/>'
                        + '客户类型：<input type="text" class="rimless_textB" style="width: 90px;" id="' + tbId + '_cmp_customerType' + rowNum + '" name="' + objName + '[' + rowNum + '][customerType]" value="' + rowData.customerType + '" readonly="readonly"/><br/>'
                        + '归属部门：<input type="text" class="rimless_textB" style="width: 90px;" id="' + tbId + '_cmp_belongDeptName' + rowNum + '" name="' + objName + '[' + rowNum + '][belongDeptName]" value="' + rowData.belongDeptName + '" readonly="readonly"/><br/>'
                        + '销售区域：<input type="text" class="rimless_textB" style="width: 90px;" id="' + tbId + '_cmp_salesArea' + rowNum + '" name="' + objName + '[' + rowNum + '][salesArea]" value="' + rowData.salesArea + '" readonly="readonly"/>';
                    g.getCmpByRowAndCol(rowNum, "costShareObjExtends").append(extendStr);
            		// 渲染费用承担人
                    if(rowData.belongDeptId != ''){
                        g.dealFeeMan(rowNum, rowData.belongDeptId, textWidth, 'init');
                    }
                    break;
                case 'FTDXLX-07' : // 售前费用 - 客户
                    appendStr = '<input type="text" class="txtmiddle" id="' + tbId + '_cmp_customerName' + rowNum + '" name="' + objName + '[' + rowNum + '][customerName]" value="' + rowData.customerName + '" readonly="readonly"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_chanceCode' + rowNum + '" name="' + objName + '[' + rowNum + '][chanceCode]" value="' + rowData.chanceCode + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_chanceId' + rowNum + '" name="' + objName + '[' + rowNum + '][chanceId]" value="' + rowData.chanceId + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_projectId' + rowNum + '" name="' + objName + '[' + rowNum + '][projectId]" value="' + rowData.projectId + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_projectCode' + rowNum + '" name="' + objName + '[' + rowNum + '][projectCode]" value="' + rowData.projectCode + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_projectName' + rowNum + '" name="' + objName + '[' + rowNum + '][projectName]" value="' + rowData.projectName + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_projectType' + rowNum + '" name="' + objName + '[' + rowNum + '][projectType]" value="' + rowData.projectType + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_chanceId' + rowNum + '" name="' + objName + '[' + rowNum + '][chanceId]" value="' + rowData.chanceId + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_customerId' + rowNum + '" name="' + objName + '[' + rowNum + '][customerId]" value="' + rowData.customerId + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_contractId' + rowNum + '" name="' + objName + '[' + rowNum + '][contractId]" value="' + rowData.contractId + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_contractCode' + rowNum + '" name="' + objName + '[' + rowNum + '][contractCode]" value="' + rowData.contractCode + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_contractName' + rowNum + '" name="' + objName + '[' + rowNum + '][contractName]" value="' + rowData.contractName + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_belongName' + rowNum + '" name="' + objName + '[' + rowNum + '][belongName]" value="' + rowData.belongName + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_belongId' + rowNum + '" name="' + objName + '[' + rowNum + '][belongId]" value="' + rowData.belongId + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_belongDeptName' + rowNum + '" name="' + objName + '[' + rowNum + '][belongDeptName]" value="' + rowData.belongDeptName + '"/><div style="margin: 2px 0"></div>'
                    + '<select class="txtmiddle" id="' + tbId + '_cmp_belongDeptId' + rowNum + '" name="' + objName + '[' + rowNum + '][belongDeptId]"></select><div style="margin: 2px 0"></div>'
                    + '<input type="text" class="txtmiddle" id="' + tbId + '_cmp_feeMan' + rowNum + '" name="' + objName + '[' + rowNum + '][feeMan]" value="' + rowData.feeMan + '" readonly="readonly" style="display:none;"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_feeManId' + rowNum + '" name="' + objName + '[' + rowNum + '][feeManId]" value="' + rowData.feeManId + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_salesArea' + rowNum + '" name="' + objName + '[' + rowNum + '][salesArea]" value="' + rowData.salesArea + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_salesAreaId' + rowNum + '" name="' + objName + '[' + rowNum + '][salesAreaId]" value="' + rowData.salesAreaId + '"/>'
                    ;
                    g.getCmpByRowAndCol(rowNum, "costShareObj").append(appendStr);

                    // 渲染客户
                    g.getCmpByRowAndCol(rowNum, "customerName").yxcombogrid_customer({
                        hiddenId: g.getCmpByRowAndCol(rowNum, "customerId").attr('id'),
                        isFocusoutCheck: false,
                        isDown: false,
                        gridOptions: {
                            showcheckbox: false,
                            event: {
                                'row_dblclick': function (e, row, data) {
                                    g.getCmpByRowAndCol(rowNum, "province").val(data.Prov);
                                    var customerType = g.getCustomerNameByCode(data.TypeOne);
                                    g.getCmpByRowAndCol(rowNum, "customerType").val(customerType);
                            		// 销售负责人
                                    g.setAreaInfo(rowNum, customerType, data.Prov,
                                    		g.getCmpByRowAndCol(rowNum, "belongCompanyName").val(),
                                    		g.getCmpByRowAndCol(rowNum, "moduleName").val());
                                }
                            }
                        },
                        event: {
                            'clear': function () {
                                g.getCmpByRowAndCol(rowNum, "province").val('');
                                g.getCmpByRowAndCol(rowNum, "customerType").val('');
                                g.getCmpByRowAndCol(rowNum, "salesArea").val('');
                                g.getCmpByRowAndCol(rowNum, "salesAreaId").val('');
                            }
                        }
                    }).width(textWidth - 23);

                    // 归属部门选择
                    g.getCmpByRowAndCol(rowNum, "belongDeptId").width(textWidth - 4).append(g.getSaleDeptOptions(rowData.belongDeptId))
                        .bind('change', function () {
                            g.changeSelectDept(rowNum);
                    		// 渲染费用承担人
                            g.dealFeeMan(rowNum, $(this).val(), textWidth - 40);
                        });

                    // 关联信息扩展
                    var extendStr = '省份：<input type="text" class="rimless_textB" style="width: 110px;" id="' + tbId + '_cmp_province' + rowNum + '" name="' + objName + '[' + rowNum + '][province]" value="' + rowData.province + '" readonly="readonly"/><br/>'
                        + '客户类型：<input type="text" class="rimless_textB" style="width: 90px;" id="' + tbId + '_cmp_customerType' + rowNum + '" name="' + objName + '[' + rowNum + '][customerType]" value="' + rowData.customerType + '" readonly="readonly"/><br/>'
                        + '销售区域：<select id="' + tbId + '_cmp_salesAreaOpt' + rowNum + '" data-initNum="0" style="width: 90px;margin-top:3px;display:none"></select><input type="text" class="rimless_textB" style="width: 90px;" id="' + tbId + '_cmp_salesAreaRead' + rowNum + '" value="' + rowData.salesArea + '" readonly="readonly"/>';
                    g.getCmpByRowAndCol(rowNum, "costShareObjExtends").append(extendStr);
                    this.setAreaInfo(rowNum,rowData.customerType,rowData.province,rowData.belongCompanyName,rowData.moduleName);

            		// 渲染费用承担人
                    if(rowData.belongDeptId != ''){
                        g.dealFeeMan(rowNum, rowData.belongDeptId, textWidth - 40, 'init');
                    }
                    break;
                case 'FTDXLX-08' : // 售前费用 - 省份/客户类型/归属部门
                    appendStr = '<select class="txtmiddle" id="' + tbId + '_cmp_province' + rowNum + '" name="' + objName + '[' + rowNum + '][province]" value="' + rowData.province + '"></select><div style="margin: 2px 0"></div>'
                    + '<select class="txtmiddle" id="' + tbId + '_cmp_customerType' + rowNum + '" name="' + objName + '[' + rowNum + '][customerType]" value="' + rowData.customerType + '"></select><div style="margin: 2px 0"></div>'
                    + '<input type="hidden" id="' + tbId + '_cmp_contractId' + rowNum + '" name="' + objName + '[' + rowNum + '][contractId]" value="' + rowData.contractId + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_contractCode' + rowNum + '" name="' + objName + '[' + rowNum + '][contractCode]" value="' + rowData.contractCode + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_contractName' + rowNum + '" name="' + objName + '[' + rowNum + '][contractName]" value="' + rowData.contractName + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_projectId' + rowNum + '" name="' + objName + '[' + rowNum + '][projectId]" value="' + rowData.projectId + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_projectCode' + rowNum + '" name="' + objName + '[' + rowNum + '][projectCode]" value="' + rowData.projectCode + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_projectName' + rowNum + '" name="' + objName + '[' + rowNum + '][projectName]" value="' + rowData.projectName + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_projectType' + rowNum + '" name="' + objName + '[' + rowNum + '][projectType]" value="' + rowData.projectType + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_chanceId' + rowNum + '" name="' + objName + '[' + rowNum + '][chanceId]" value="' + rowData.chanceId + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_chanceCode' + rowNum + '" name="' + objName + '[' + rowNum + '][chanceCode]" value="' + rowData.chanceCode + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_customerId' + rowNum + '" name="' + objName + '[' + rowNum + '][customerId]" value="' + rowData.customerId + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_customerName' + rowNum + '" name="' + objName + '[' + rowNum + '][customerName]" value="' + rowData.customerName + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_belongName' + rowNum + '" name="' + objName + '[' + rowNum + '][belongName]" value="' + rowData.belongName + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_belongId' + rowNum + '" name="' + objName + '[' + rowNum + '][belongId]" value="' + rowData.belongId + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_belongDeptName' + rowNum + '" name="' + objName + '[' + rowNum + '][belongDeptName]" value="' + rowData.belongDeptName + '"/><div style="margin: 2px 0"></div>'
                    + '<select class="txtmiddle" id="' + tbId + '_cmp_belongDeptId' + rowNum + '" name="' + objName + '[' + rowNum + '][belongDeptId]"></select><div style="margin: 2px 0"></div>'
                    + '<input type="text" class="txtmiddle" id="' + tbId + '_cmp_feeMan' + rowNum + '" name="' + objName + '[' + rowNum + '][feeMan]" value="' + rowData.feeMan + '" readonly="readonly" style="display:none;"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_feeManId' + rowNum + '" name="' + objName + '[' + rowNum + '][feeManId]" value="' + rowData.feeManId + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_salesArea' + rowNum + '" name="' + objName + '[' + rowNum + '][salesArea]" value="' + rowData.salesArea + '"/>'
                        + '<input type="hidden" id="' + tbId + '_cmp_salesAreaId' + rowNum + '" name="' + objName + '[' + rowNum + '][salesAreaId]" value="' + rowData.salesAreaId + '"/>'
                    ;
                    g.getCmpByRowAndCol(rowNum, "costShareObj").append(appendStr);

                    // 省份选择
                    g.getCmpByRowAndCol(rowNum, "province").width(textWidth - 4).append(g.getProvinceOptions(rowData.province))
	                    .bind('change', function () {
                    		// 销售负责人
                            g.setAreaInfo(rowNum, g.getCmpByRowAndCol(rowNum, "customerType").val(),
                            		$(this).find("option:selected").text(),
                            		g.getCmpByRowAndCol(rowNum, "belongCompanyName").val(),
                            		g.getCmpByRowAndCol(rowNum, "moduleName").val());
	                    });
                    // 客户类型选择
                    g.getCmpByRowAndCol(rowNum, "customerType").width(textWidth - 4).append(g.getCustomerTypeOptions(rowData.customerType))
	                    .bind('change', function () {
                    		// 销售负责人
                            g.setAreaInfo(rowNum, $(this).find("option:selected").text(),
                            		g.getCmpByRowAndCol(rowNum, "province").val(),
                            		g.getCmpByRowAndCol(rowNum, "belongCompanyName").val(),
                            		g.getCmpByRowAndCol(rowNum, "moduleName").val());
	                    });
                    // 归属部门选择
                    g.getCmpByRowAndCol(rowNum, "belongDeptId").width(textWidth - 4).append(g.getSaleDeptOptions(rowData.belongDeptId))
                        .bind('change', function () {
                            g.changeSelectDept(rowNum);
                    		// 渲染费用承担人
                            g.dealFeeMan(rowNum, $(this).val(), textWidth - 40);
                        });
                    // 关联信息扩展
                    var extendStr = '销售区域：<select id="' + tbId + '_cmp_salesAreaOpt' + rowNum + '" data-initNum="0" style="width: 90px;margin-top:3px;display:none"></select><input type="text" class="rimless_textB" style="width: 90px;" id="' + tbId + '_cmp_salesAreaRead' + rowNum + '" value="' + rowData.salesArea + '" readonly="readonly"/>';
                    g.getCmpByRowAndCol(rowNum, "costShareObjExtends").append(extendStr);
                    this.setAreaInfo(rowNum,rowData.customerType,rowData.province,rowData.belongCompanyName,rowData.moduleName);

            		// 渲染费用承担人
                    if(rowData.belongDeptId != ''){
                        g.dealFeeMan(rowNum, rowData.belongDeptId, textWidth - 40, 'init');
                    }
                    break;
                case 'FTDXLX-09' : // 销售合同 / 归属部门
                    appendStr = '<input type="text" class="txtmiddle" id="' + tbId + '_cmp_contractCode' + rowNum + '" name="' + objName + '[' + rowNum + '][contractCode]" value="' + rowData.contractCode + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_contractId' + rowNum + '" name="' + objName + '[' + rowNum + '][contractId]" value="' + rowData.contractId + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_contractName' + rowNum + '" name="' + objName + '[' + rowNum + '][contractName]" value="' + rowData.contractName + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_projectId' + rowNum + '" name="' + objName + '[' + rowNum + '][projectId]" value="' + rowData.projectId + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_projectCode' + rowNum + '" name="' + objName + '[' + rowNum + '][projectCode]" value="' + rowData.projectCode + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_projectName' + rowNum + '" name="' + objName + '[' + rowNum + '][projectName]" value="' + rowData.projectName + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_projectType' + rowNum + '" name="' + objName + '[' + rowNum + '][projectType]" value="' + rowData.projectType + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_chanceId' + rowNum + '" name="' + objName + '[' + rowNum + '][chanceId]" value="' + rowData.chanceId + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_chanceCode' + rowNum + '" name="' + objName + '[' + rowNum + '][chanceCode]" value="' + rowData.chanceCode + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_customerId' + rowNum + '" name="' + objName + '[' + rowNum + '][customerId]" value="' + rowData.customerId + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_customerName' + rowNum + '" name="' + objName + '[' + rowNum + '][customerName]" value="' + rowData.customerName + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_customerType' + rowNum + '" name="' + objName + '[' + rowNum + '][customerType]" value="' + rowData.customerType + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_province' + rowNum + '" name="' + objName + '[' + rowNum + '][province]" value="' + rowData.province + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_belongName' + rowNum + '" name="' + objName + '[' + rowNum + '][belongName]" value="' + rowData.belongName + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_belongId' + rowNum + '" name="' + objName + '[' + rowNum + '][belongId]" value="' + rowData.belongId + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_belongDeptName' + rowNum + '" name="' + objName + '[' + rowNum + '][belongDeptName]" value="' + rowData.belongDeptName + '"/><div style="margin: 2px 0"></div>'
                    + '<select class="txtmiddle" id="' + tbId + '_cmp_belongDeptId' + rowNum + '" name="' + objName + '[' + rowNum + '][belongDeptId]"></select><div style="margin: 2px 0"></div>'
                    + '<input type="text" class="txtmiddle" id="' + tbId + '_cmp_feeMan' + rowNum + '" name="' + objName + '[' + rowNum + '][feeMan]" value="' + rowData.feeMan + '" readonly="readonly" style="display:none;"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_feeManId' + rowNum + '" name="' + objName + '[' + rowNum + '][feeManId]" value="' + rowData.feeManId + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_salesAreaId' + rowNum + '" name="' + objName + '[' + rowNum + '][salesAreaId]" value="' + rowData.salesAreaId + '"/>'
                    ;
                    g.getCmpByRowAndCol(rowNum, "costShareObj").append(appendStr);
                    //编号搜索渲染
                    var codeObj = g.getCmpByRowAndCol(rowNum, "contractCode");
                    if (codeObj.attr('wchangeTag2')) {
                        return false;
                    }
                    var $button = $("<span class='search-trigger' title='合同编号'>&nbsp;</span>");
                    $button.click(function () {
                        if (codeObj.val() == "") {
                            alert('请输入一个合同编号');
                            return false;
                        }
                    });
                    //添加清空按钮
                    var $button2 = $("<span class='clear-trigger' title='点击清空数据'>&nbsp;</span>");
                    $button2.click(function () {
                        codeObj.val('');
                    });
                    codeObj.after($button2).after($button).attr("wchangeTag2", true).width(textWidth - 40).
                        bind('blur', function () {
                            g.getContractInfo(rowNum);
                    		// 渲染费用承担人
                            g.dealFeeMan(rowNum, g.getCmpByRowAndCol(rowNum, "belongDeptId").val(), textWidth - 40);
                        });
                    // 归属部门选择
                    g.getCmpByRowAndCol(rowNum, "belongDeptId").width(textWidth - 4).
                        append(g.getContractDeptOptions(rowData.belongDeptId)).
                        bind('change', function () {
                            g.changeSelectDept(rowNum);
                    		// 渲染费用承担人
                            g.dealFeeMan(rowNum, $(this).val(), textWidth - 40);
                        });
                    // 关联信息扩展
                    var extendStr = '销售区域：<input type="text" class="rimless_textB" style="width: 90px;" id="' + tbId + '_cmp_salesArea' + rowNum + '" name="' + objName + '[' + rowNum + '][salesArea]" value="' + rowData.salesArea + '" readonly="readonly"/>';
                    g.getCmpByRowAndCol(rowNum, "costShareObjExtends").append(extendStr);
            		// 渲染费用承担人
                    if(rowData.belongDeptId != ''){
                        g.dealFeeMan(rowNum, rowData.belongDeptId, textWidth - 40, 'init');
                    }
                    break;
                case 'FTDXLX-11' : // 合同
                    appendStr = '<input type="text" class="txtmiddle" id="' + tbId + '_cmp_contractCode' + rowNum + '" name="' + objName + '[' + rowNum + '][contractCode]" value="' + rowData.contractCode + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_contractId' + rowNum + '" name="' + objName + '[' + rowNum + '][contractId]" value="' + rowData.contractId + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_contractName' + rowNum + '" name="' + objName + '[' + rowNum + '][contractName]" value="' + rowData.contractName + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_projectId' + rowNum + '" name="' + objName + '[' + rowNum + '][projectId]" value="' + rowData.projectId + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_projectCode' + rowNum + '" name="' + objName + '[' + rowNum + '][projectCode]" value="' + rowData.projectCode + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_projectName' + rowNum + '" name="' + objName + '[' + rowNum + '][projectName]" value="' + rowData.projectName + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_projectType' + rowNum + '" name="' + objName + '[' + rowNum + '][projectType]" value="' + rowData.projectType + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_chanceId' + rowNum + '" name="' + objName + '[' + rowNum + '][chanceId]" value="' + rowData.chanceId + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_chanceCode' + rowNum + '" name="' + objName + '[' + rowNum + '][chanceCode]" value="' + rowData.chanceCode + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_customerId' + rowNum + '" name="' + objName + '[' + rowNum + '][customerId]" value="' + rowData.customerId + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_belongName' + rowNum + '" name="' + objName + '[' + rowNum + '][belongName]" value="' + rowData.belongName + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_belongId' + rowNum + '" name="' + objName + '[' + rowNum + '][belongId]" value="' + rowData.belongId + '"/>'
                    + '<input type="hidden" id="' + tbId + '_cmp_belongDeptId' + rowNum + '" name="' + objName + '[' + rowNum + '][belongDeptId]" value="' + rowData.belongDeptId + '"/><div style="margin: 2px 0"></div>'
                    + '<input type="hidden" id="' + tbId + '_cmp_salesAreaId' + rowNum + '" name="' + objName + '[' + rowNum + '][salesAreaId]" value="' + rowData.salesAreaId + '"/>'
                    ;
                    g.getCmpByRowAndCol(rowNum, "costShareObj").append(appendStr);
                    //编号搜索渲染
                    var codeObj = g.getCmpByRowAndCol(rowNum, "contractCode");
                    if (codeObj.attr('wchangeTag2')) {
                        return false;
                    }
                    var $button = $("<span class='search-trigger' title='合同编号'>&nbsp;</span>");
                    $button.click(function () {
                        if (codeObj.val() == "") {
                            alert('请输入一个合同编号');
                            return false;
                        }
                    });
                    //添加清空按钮
                    var $button2 = $("<span class='clear-trigger' title='点击清空数据'>&nbsp;</span>");
                    $button2.click(function () {
                        codeObj.val('');
                    });
                    codeObj.after($button2).after($button).attr("wchangeTag2", true).width(textWidth - 40).
                        bind('blur', function () {
                            g.getContractInfo(rowNum,'完成');
                        });

                    // 关联信息扩展
                    var extendStr = '客户：<input type="text" class="rimless_textB" style="width: 110px;" id="' + tbId + '_cmp_customerName' + rowNum + '" name="' + objName + '[' + rowNum + '][customerName]" value="' + rowData.customerName + '" readonly="readonly"/><br/>'
                        + '省份：<input type="text" class="rimless_textB" style="width: 110px;" id="' + tbId + '_cmp_province' + rowNum + '" name="' + objName + '[' + rowNum + '][province]" value="' + rowData.province + '" readonly="readonly"/><br/>'
                        + '客户类型：<input type="text" class="rimless_textB" style="width: 90px;" id="' + tbId + '_cmp_customerType' + rowNum + '" name="' + objName + '[' + rowNum + '][customerType]" value="' + rowData.customerType + '" readonly="readonly"/><br/>'
                        + '归属部门：<input type="text" class="rimless_textB" style="width: 90px;" id="' + tbId + '_cmp_belongDeptName' + rowNum + '" name="' + objName + '[' + rowNum + '][belongDeptName]" value="' + rowData.belongDeptName + '" readonly="readonly"/><br/>'
                        + '销售区域：<input type="text" class="rimless_textB" style="width: 90px;" id="' + tbId + '_cmp_salesArea' + rowNum + '" name="' + objName + '[' + rowNum + '][salesArea]" value="' + rowData.salesArea + '" readonly="readonly"/>';
                    g.getCmpByRowAndCol(rowNum, "costShareObjExtends").append(extendStr);
                    break;
                default :
            }
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
                    return rowData.projectName;
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
         * init share object extends info for change
         * @param shareObjType
         * @param rowNum
         * @param rowData
         */
        initShareObjExtendsChange: function (shareObjType, rowNum, rowData) {
            var str = "";
            var g = this;
            var tbId = g.el.attr('id');
            var objName = g.options.objName;
            switch (shareObjType) {
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
                	+ '销售区域：<span id="' + tbId + '_cmp_salesAreaShow' + rowNum + '">' + rowData.salesArea +'</span>';
                    break;
                case 'FTDXLX-08' : // 售前费用 - 省份/客户类型/归属部门
                	str += '销售区域：<span id="' + tbId + '_cmp_salesAreaShow' + rowNum + '">' + rowData.salesArea +'</span>';
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
            + '<input type="hidden" id="' + tbId + '_cmp_contractName' + rowNum + '" name="' + objName + '[' + rowNum + '][contractName]" value="' + rowData.contractName + '"/>'
            + '<input type="hidden" id="' + tbId + '_cmp_belongName' + rowNum + '" name="' + objName + '[' + rowNum + '][belongName]" value="' + rowData.belongName + '"/>'
            + '<input type="hidden" id="' + tbId + '_cmp_belongId' + rowNum + '" name="' + objName + '[' + rowNum + '][belongId]" value="' + rowData.belongId + '"/>'
            + '<input type="hidden" id="' + tbId + '_cmp_feeManId' + rowNum + '" name="' + objName + '[' + rowNum + '][feeManId]" value="' + rowData.feeManId + '"/>'
            + '<input type="hidden" id="' + tbId + '_cmp_feeMan' + rowNum + '" name="' + objName + '[' + rowNum + '][feeMan]" value="' + rowData.feeMan + '"/>'
            + '<input type="hidden" id="' + tbId + '_cmp_salesAreaId' + rowNum + '" name="' + objName + '[' + rowNum + '][salesAreaId]" value="' + rowData.salesAreaId + '"/>'
            + '<input type="hidden" id="' + tbId + '_cmp_salesArea' + rowNum + '" name="' + objName + '[' + rowNum + '][salesArea]" value="' + rowData.salesArea + '"/>'
            ;
            return str;
        },
        /**
         * 自动初始化 - 不含清除
         * @param data
         */
        autoInitDetailType: function (data) {
            for (var i = 0; i < data.length; i++) {
                this.initDetailType(data[i].detailType, i, data[i]);
            }
        },
        /**
         * 获取合同
         * @param rowNum
         * @param ExaStatus
         */
        getContractInfo: function (rowNum, ExaStatus) {
            var contractCodeObj = this.getCmpByRowAndCol(rowNum, "contractCode");
            var contractCode = contractCodeObj.val();
            var g = this;
            if (strTrim(contractCode) != '') {
                $.ajax({
                    type: "POST",
                    url: "?model=contract_contract_contract&action=ajaxGetContract",
                    data: {contractCode: contractCode,ExaStatus: ExaStatus},
                    async: false,
                    success: function (data) {
                        if (data != "") {
                            var obj = eval("(" + data + ")");
                            //判断合同状态
                            // if(obj.state == '3' || obj.state == '7'){
                            //     alert('合同已关闭,不允许分摊');

                            //现在售后分摊的合同状态可以选择的是执行中2，完成3，关闭4。 ID2182
                            if(obj.state == '2' || obj.state == '3' || obj.state == '4'){
                                g.getCmpByRowAndCol(rowNum, "contractId").val(obj.id);
                                g.getCmpByRowAndCol(rowNum, "contractName").val(obj.contractName);
                                g.getCmpByRowAndCol(rowNum, "customerId").val(obj.customerId);
                                g.getCmpByRowAndCol(rowNum, "customerName").val(obj.customerName);
                                g.getCmpByRowAndCol(rowNum, "customerType").val(obj.customerTypeName);
                                g.getCmpByRowAndCol(rowNum, "province").val(obj.contractProvince);
                                g.getCmpByRowAndCol(rowNum, "belongName").val(obj.prinvipalName);
                                g.getCmpByRowAndCol(rowNum, "belongId").val(obj.prinvipalId);
                                g.getCmpByRowAndCol(rowNum, "belongDeptId").val(obj.prinvipalDeptId);
                                g.getCmpByRowAndCol(rowNum, "belongDeptName").val(obj.prinvipalDept);
                                g.getCmpByRowAndCol(rowNum, "salesArea").val(obj.areaName);
                                g.getCmpByRowAndCol(rowNum, "salesAreaId").val(obj.areaCode);
                            }else{
                                alert('该合同不是有效合同，不允许分摊。');
                                contractCodeObj.val('');
                                g.getCmpByRowAndCol(rowNum, "contractId").val('');
                                g.getCmpByRowAndCol(rowNum, "contractName").val('');
                                g.getCmpByRowAndCol(rowNum, "customerId").val('');
                                g.getCmpByRowAndCol(rowNum, "customerName").val('');
                                g.getCmpByRowAndCol(rowNum, "customerType").val('');
                                g.getCmpByRowAndCol(rowNum, "province").val('');
                                g.getCmpByRowAndCol(rowNum, "belongName").val('');
                                g.getCmpByRowAndCol(rowNum, "belongId").val('');
                                g.getCmpByRowAndCol(rowNum, "belongDeptId").val('');
                                g.getCmpByRowAndCol(rowNum, "belongDeptName").val('');
                                g.getCmpByRowAndCol(rowNum, "salesArea").val('');
                                g.getCmpByRowAndCol(rowNum, "salesAreaId").val('');
                            }
                        } else {
                            alert('系统中不存在此合同号，请重新输入');
                            contractCodeObj.val('');
                            g.getCmpByRowAndCol(rowNum, "contractId").val('');
                            g.getCmpByRowAndCol(rowNum, "contractName").val('');
                            g.getCmpByRowAndCol(rowNum, "customerId").val('');
                            g.getCmpByRowAndCol(rowNum, "customerName").val('');
                            g.getCmpByRowAndCol(rowNum, "customerType").val('');
                            g.getCmpByRowAndCol(rowNum, "province").val('');
                            g.getCmpByRowAndCol(rowNum, "belongName").val('');
                            g.getCmpByRowAndCol(rowNum, "belongId").val('');
                            g.getCmpByRowAndCol(rowNum, "belongDeptId").val('');
                            g.getCmpByRowAndCol(rowNum, "belongDeptName").val('');
                            g.getCmpByRowAndCol(rowNum, "salesArea").val('');
                            g.getCmpByRowAndCol(rowNum, "salesAreaId").val('');
                        }
                    }
                });
            }
        },
        /**
         * get chance info by row number
         * @param rowNum
         */
        getChanceInfo: function (rowNum) {
            var chanceCodeObj = this.getCmpByRowAndCol(rowNum, "chanceCode");
            var chanceCode = chanceCodeObj.val();
            var g = this;
            if (strTrim(chanceCode) != '') {
                $.ajax({
                    type: "POST",
                    url: "?model=projectmanagent_chance_chance&action=ajaxChanceByCode",
                    data: {"chanceCode": chanceCode},
                    async: false,
                    success: function (data) {
                        if (data != "") {
                            var obj = eval("(" + data + ")");
                            g.getCmpByRowAndCol(rowNum, "chanceId").val(obj.id);
                            g.getCmpByRowAndCol(rowNum, "customerId").val(obj.customerId);
                            g.getCmpByRowAndCol(rowNum, "customerName").val(obj.customerName);
                            g.getCmpByRowAndCol(rowNum, "customerType").val(obj.customerTypeName);
                            g.getCmpByRowAndCol(rowNum, "province").val(obj.Province);
                            g.getCmpByRowAndCol(rowNum, "belongName").val(obj.prinvipalName);
                            g.getCmpByRowAndCol(rowNum, "belongId").val(obj.prinvipalId);
                            g.getCmpByRowAndCol(rowNum, "belongDeptId").val(obj.prinvipalDeptId);
                            g.getCmpByRowAndCol(rowNum, "belongDeptName").val(obj.prinvipalDept);
                            g.getCmpByRowAndCol(rowNum, "salesArea").val(obj.areaName);
                            g.getCmpByRowAndCol(rowNum, "salesAreaId").val(obj.areaCode);
                        } else {
                            alert('系统中不存在此商机，请重新输入');
                            chanceCodeObj.val('');
                            g.getCmpByRowAndCol(rowNum, "chanceId").val('');
                            g.getCmpByRowAndCol(rowNum, "customerId").val('');
                            g.getCmpByRowAndCol(rowNum, "customerName").val('');
                            g.getCmpByRowAndCol(rowNum, "customerType").val('');
                            g.getCmpByRowAndCol(rowNum, "province").val('');
                            g.getCmpByRowAndCol(rowNum, "belongName").val('');
                            g.getCmpByRowAndCol(rowNum, "belongId").val('');
                            g.getCmpByRowAndCol(rowNum, "belongDeptId").val('');
                            g.getCmpByRowAndCol(rowNum, "belongDeptName").val('');
                            g.getCmpByRowAndCol(rowNum, "salesArea").val('');
                            g.getCmpByRowAndCol(rowNum, "salesAreaId").val('');
                        }
                    }
                });
            }
        },
        /**
         * get trialProject info by row number
         * @param id
         */
        getTrialProject: function (id) {
            var obj = $.ajax({
                type: "POST",
                url: "?model=projectmanagent_trialproject_trialproject&action=ajaxGetInfo",
                data: {"id": id},
                async: false
            }).responseText;
            if (obj != "") {
                return eval("(" + obj + ")");
            } else {
                return false;
            }
        },
        /**
         * get customer type
         */
        getCustomerType: function () {
            if (!customerTypeArray) {
                var obj = $.ajax({
                    type: "GET",
                    url: 'index1.php?model=system_datadict_datadict&action=ajaxGetForEasyUI&parentCode=KHLX',
                    async: false
                }).responseText;
                if (obj != "") {
                    customerTypeArray = eval("(" + obj + ")");
                } else {
                    return [];
                }
            }
            return customerTypeArray;
        },
        /**
         * get customer type options
         * @param value
         */
        getCustomerTypeOptions: function (value) {
            var thisCustomerTypeArray = this.getCustomerType();
            var optionStr = '<option value=""></option>';
            for (var i = 0; i < thisCustomerTypeArray.length; i++) {
                if (value == thisCustomerTypeArray[i].text) {
                    optionStr += '<option value="' + thisCustomerTypeArray[i].text + '" selected="selected">' +
                    thisCustomerTypeArray[i].text + '</option>';
                } else {
                    optionStr += '<option value="' + thisCustomerTypeArray[i].text + '">' +
                    thisCustomerTypeArray[i].text + '</option>';
                }
            }
            return optionStr;
        },
        /**
         * get customer type name by code
         * @param value
         */
        getCustomerNameByCode: function (value) {
            var thisCustomerTypeArray = this.getCustomerType();
            for (var i = 0; i < thisCustomerTypeArray.length; i++) {
                if (value == thisCustomerTypeArray[i].value) {
                    return thisCustomerTypeArray[i].text;
                }
            }
            return value;
        },
        /**
         * get province
         */
        getProvince: function () {
            if (!provinceArray) {
                var obj = $.ajax({
                    type: "POST",
                    url: "?model=system_procity_province&action=getProvinceForEditGrid",
                    async: false
                }).responseText;
                if (obj != "") {
                    provinceArray = eval("(" + obj + ")");
                } else {
                    return [];
                }
            }
            return provinceArray;
        },
        /**
         * get province options
         * @param value
         * @returns {string}
         */
        getProvinceOptions: function (value) {
            var thisProvinceArray = this.getProvince();
            var optionStr = '<option value=""></option>';
            for (var i = 0; i < thisProvinceArray.length; i++) {
                if (value == thisProvinceArray[i].name) {
                    optionStr += '<option value="' + thisProvinceArray[i].name + '" selected="selected">' + thisProvinceArray[i].name + '</option>';
                } else {
                    optionStr += '<option value="' + thisProvinceArray[i].name + '">' + thisProvinceArray[i].name + '</option>';
                }
            }
            return optionStr;
        },
        /**
         * get sale department
         */
        getSaleDept: function () {
            if (!saleDeptArray) {
                var obj = $.ajax({
                    type: "POST",
                    url: 'index1.php?model=finance_expense_expense&action=getSaleDept&detailType=4',
                    async: false
                }).responseText;
                if (obj != "") {
                    saleDeptArray = eval("(" + obj + ")");
                } else {
                    return [];
                }
            }
            return saleDeptArray;
        },
        /**
         * get sale department options
         * @param value
         * @returns {string}
         */
        getSaleDeptOptions: function (value) {
            var thisSaleDept = this.getSaleDept();
            var optionStr = '<option value=""></option>';
            for (var i = 0; i < thisSaleDept.length; i++) {
                if (value == thisSaleDept[i].value) {
                    optionStr += '<option value="' + thisSaleDept[i].value + '" selected="selected">' + thisSaleDept[i].text + '</option>';
                } else {
                    optionStr += '<option value="' + thisSaleDept[i].value + '">' + thisSaleDept[i].text + '</option>';
                }
            }
            return optionStr;
        },
        /**
         * get contract department
         */
        getContractDept: function () {
            if (!contractDeptArray) {
                var obj = $.ajax({
                    type: "POST",
                    url: 'index1.php?model=finance_expense_expense&action=getSaleDept&detailType=5',
                    async: false
                }).responseText;
                if (obj != "") {
                    contractDeptArray = eval("(" + obj + ")");
                } else {
                    return [];
                }
            }
            return contractDeptArray;
        },
        /**
         * get contract department options
         * @param value
         * @returns {string}
         */
        getContractDeptOptions: function (value) {
            var thisContractDept = this.getContractDept();
            var optionStr = '<option value=""></option>';
            for (var i = 0; i < thisContractDept.length; i++) {
                if (value == thisContractDept[i].value) {
                    optionStr += '<option value="' + thisContractDept[i].value + '" selected="selected">' +
                    thisContractDept[i].text + '</option>';
                } else {
                    optionStr += '<option value="' + thisContractDept[i].value + '">' +
                    thisContractDept[i].text + '</option>';
                }
            }
            return optionStr;
        },
        /**
         * when user change department selector, will change department name too
         * @param rowNum
         */
        changeSelectDept: function (rowNum) {
            this.getCmpByRowAndCol(rowNum, "belongDeptName")
                .val(this.getCmpByRowAndCol(rowNum, "belongDeptId").find("option:selected").text());
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

                        if (g.getCmpByRowAndCol(rowNum, "module").val() == "") {
                            alert('费用分摊明细表第【' + showNum + '】行没有选择所属板块');
                            rs = false;
                            return false;
                        }else if(g.getCmpByRowAndCol(rowNum, "costTypeName").val() == ""){
                            alert('费用分摊明细表第【' + showNum + '】行费用明细不得为空');
                            rs = false;
                            return false;
                        }

                        switch ($(this).val()) {
                            case 'FTDXLX-01' :
                                if (g.getCmpByRowAndCol(rowNum, "belongDeptName").val() == '') {
                                    alert('费用分摊明细表第【' + showNum + '】行没有费用归属部门');
                                    rs = false;
                                    return false;
                                }
                                break;
                            case 'FTDXLX-02' : // 合同工程项目费用
                                if (g.getCmpByRowAndCol(rowNum, "projectCode").val() == '') {
                                    alert('费用分摊明细表第【' + showNum + '】行没有选择费用归属项目');
                                    rs = false;
                                    return false;
                                }
                                break;
                            case 'FTDXLX-03' : // 合同销售项目费用
                                if (g.getCmpByRowAndCol(rowNum, "projectCode").val() == '') {
                                    alert('费用分摊明细表第【' + showNum + '】行没有选择费用归属项目');
                                    rs = false;
                                    return false;
                                }
                                break;
                            case 'FTDXLX-04' : // 合同研发项目费用
                                if (g.getCmpByRowAndCol(rowNum, "projectCode").val() == '') {
                                    alert('费用分摊明细表第【' + showNum + '】行没有选择费用归属项目');
                                    rs = false;
                                    return false;
                                }
                                break;
                            case 'FTDXLX-05' : // 售前费用 - 试用项目
                                if (g.getCmpByRowAndCol(rowNum, "projectCode").val() == '') {
                                    alert('费用分摊明细表第【' + showNum + '】行没有选择费用归属项目');
                                    rs = false;
                                    return false;
                                }
								if (g.getCmpByRowAndCol(rowNum, "feeManId").val() == '') {
									alert('费用分摊明细表第【' + showNum + '】行没有选择费用承担人');
									rs = false;
									return false;
								}
                                if (g.getCmpByRowAndCol(rowNum, "salesAreaId").val() == '' || g.getCmpByRowAndCol(rowNum, "salesAreaId").val() == '0') {
                                    alert('费用分摊明细表第【' + showNum + '】行没有销售区域');
                                    rs = false;
                                    return false;
                                }
                                break;
                            case 'FTDXLX-10' : // 研发费用
                                if (g.getCmpByRowAndCol(rowNum, "projectCode").val() == '') {
                                    alert('费用分摊明细表第【' + showNum + '】行没有选择费用归属项目');
                                    rs = false;
                                    return false;
                                }
                                break;
                            case 'FTDXLX-06' :  // 售前费用 - 商机
                                if (g.getCmpByRowAndCol(rowNum, "chanceCode").val() == '') {
                                    alert('费用分摊明细表第【' + showNum + '】行没有填写费用归属商机');
                                    rs = false;
                                    return false;
                                }
								if (g.getCmpByRowAndCol(rowNum, "feeManId").val() == '') {
									alert('费用分摊明细表第【' + showNum + '】行没有选择费用承担人');
									rs = false;
									return false;
								}
                                if (g.getCmpByRowAndCol(rowNum, "salesAreaId").val() == '' || g.getCmpByRowAndCol(rowNum, "salesAreaId").val() == '0') {
                                    alert('费用分摊明细表第【' + showNum + '】行没有销售区域');
                                    rs = false;
                                    return false;
                                }
                                break;
                            case 'FTDXLX-07' :  // 售前费用 - 客户/归属部门
                                if (g.getCmpByRowAndCol(rowNum, "customerName").val() == '') {
                                    alert('费用分摊明细表第【' + showNum + '】行没有费用客户信息');
                                    rs = false;
                                    return false;
                                }
                                if (g.getCmpByRowAndCol(rowNum, "belongDeptName").val() == '') {
                                    alert('费用分摊明细表第【' + showNum + '】行没有费用归属部门');
                                    rs = false;
                                    return false;
                                }
                                if (g.getCmpByRowAndCol(rowNum, "salesAreaId").val() == '' || g.getCmpByRowAndCol(rowNum, "salesAreaId").val() == '0') {
                                    alert('费用分摊明细表第【' + showNum + '】行没有销售区域');
                                    rs = false;
                                    return false;
                                }
								if (g.getCmpByRowAndCol(rowNum, "feeManId").val() == '') {
									alert('费用分摊明细表第【' + showNum + '】行没有选择费用承担人');
									rs = false;
									return false;
								}
                                break;
                            case 'FTDXLX-08' :  // 售前费用 - 客户省份/客户类型/归属部门
                                if (g.getCmpByRowAndCol(rowNum, "province").val() == '') {
                                    alert('费用分摊明细表第【' + showNum + '】行没有费用的省份信息');
                                    rs = false;
                                    return false;
                                }
                                if (g.getCmpByRowAndCol(rowNum, "customerType").val() == '') {
                                    alert('费用分摊明细表第【' + showNum + '】行没有费用的客户类型');
                                    rs = false;
                                    return false;
                                }
                                if (g.getCmpByRowAndCol(rowNum, "salesAreaId").val() == '' || g.getCmpByRowAndCol(rowNum, "salesAreaId").val() == '0') {
                                    alert('费用分摊明细表第【' + showNum + '】行没有销售区域');
                                    rs = false;
                                    return false;
                                }
                                if (g.getCmpByRowAndCol(rowNum, "belongDeptName").val() == '') {
                                    alert('费用分摊明细表第【' + showNum + '】行没有费用归属部门');
                                    rs = false;
                                    return false;
                                }
								if (g.getCmpByRowAndCol(rowNum, "feeManId").val() == '') {
									alert('费用分摊明细表第【' + showNum + '】行没有选择费用承担人');
									rs = false;
									return false;
								}
                                break;
                            case 'FTDXLX-09' : // 合同费用
                                if (g.getCmpByRowAndCol(rowNum, "contractCode").val() == '') {
                                    alert('费用分摊明细表第【' + showNum + '】行没有填写费用归属合同');
                                    rs = false;
                                    return false;
                                }
								if (g.getCmpByRowAndCol(rowNum, "belongDeptName").val() == '') {
									alert('费用分摊明细表第【' + showNum + '】行没有费用归属部门');
									rs = false;
									return false;
								}
								if (g.getCmpByRowAndCol(rowNum, "feeManId").val() == '') {
									alert('费用分摊明细表第【' + showNum + '】行没有选择费用承担人');
									rs = false;
									return false;
								}
                                break;
                            case 'FTDXLX-11' : // 合同费用
                                if (g.getCmpByRowAndCol(rowNum, "contractCode").val() == '') {
                                    alert('费用分摊明细表第【' + showNum + '】行没有填写费用归属合同');
                                    rs = false;
                                    return false;
                                }
                                break;
                            default :
                        }

                        if (g.getCmpByRowAndCol(rowNum, "costType").val() == "") {
                            alert('费用分摊明细表第【' + showNum + '】行没有选择费用明细');
                            rs = false;
                            return false;
                        }

                        var costMoney = g.getCmpByRowAndCol(rowNum, "costMoney").val();
                        if (costMoney == "" || costMoney == 0) {
                            alert('费用分摊明细表第【' + showNum + '】行金额不能为空或0');
                            rs = false;
                            return false;
                        }

                        shareMoney = accAdd(shareMoney, costMoney, 2);
                    }
                });

				// 加上其他页面的分摊金额
				shareMoney = accAdd(shareMoney, g.otherPageMoney, 2);

                // 如果传入了金额，则需要进行金额验证
                if (rs == true && formMoney != shareMoney) {
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
         * open select fee type window
         * @param rowNum
         */
        showFeeType: function (rowNum) {
            var g = this;
            // 动态生成费用类型选择
            var selectDiv = $("#costShareSelectDiv");
            if (selectDiv.length == 0) {
                // 后面加载一个div用于保存费用类型选择项
                var divObj = $("<div id='costShareSelectArea' style='display:none;'></div>");
                g.el.after(divObj);
                divObj.append("<div id='costShareSelectDiv'></div>");

                $.ajax({
                    type: "POST",
                    url: "?model=finance_expense_costtype&action=getCostType",
                    data: {isAction: 0},
                    async: false,
                    success: function (data) {
                        if (data != "") {
                            // 加载缓存
                            $("#costShareSelectDiv").append(data);
                        } else {
                            alert('没有找到自定义的费用类型');
                        }
                    }
                });

                var btnObj = "<input type='button' id='showCostTypeButton' value='选择费用类型' class='thickbox'" +
                    " alt='#TB_inline?height=600&width=1000&inlineId=costShareSelectArea' style='display:none;'/>";
                g.el.after(btnObj);
                tb_init('#showCostTypeButton');// 使其具有thickbox功能
                $("#showCostTypeButton").bind('click', function () {
                    return true;
                }).trigger('click');
                setTimeout(function () {
                    $('#costShareSelectDiv').masonry({
                        itemSelector: '.box'
                    });
                }, 200);
            } else {
                $("#showCostTypeButton").trigger('click');
                selectDiv.masonry({
                    itemSelector: '.box'
                });
            }

            var selected = '';
            g.getCmpByCol("costTypeId").each(function () {
                if ($(this).val() != "" && $(this).val() != "0") {
                    if ($(this).data('rowNum') == rowNum) {
                        selected = $(this).val();
                        $("#chk" + $(this).val()).attr('checked', true).next().attr('class', 'blue');
                        $("#costTypeSelectedHidden").val($(this).val());
                    } else if (selected != $(this).val()) {
                        $("#chk" + $(this).val()).attr('checked', false).next().attr('class', '');
                    }
                }
            });

            // 加入监听事件
            $("#costShareSelectDiv input").unbind("click").bind("click", function () {
                g.setCostType(rowNum, $(this));
            });

            // 如果页面有禁止选择的ID值,则做禁止选择处理
            var unSelectableIds = $("#unSelectableIds").val();
            if(unSelectableIds && unSelectableIds != undefined && unSelectableIds != ''){
                var unSelectableIdsArr = unSelectableIds.split(",");
                $.each(unSelectableIdsArr,function(i,item){
                    if(item != ''){
                        $("#chk"+item).attr('checked', false).next().attr('class', '');
                        $("#chk"+item).attr("disabled","disabled");
                        $("#chk"+item).css("display","none");
                        if(g.getCmpByRowAndCol(rowNum, "costTypeId").val() == item){
                            g.setRowColValue(rowNum, 'costTypeId', '');
                            g.setRowColValue(rowNum, 'costTypeName', '');
                            g.setRowColValue(rowNum, 'parentTypeId', '');
                            g.setRowColValue(rowNum, 'parentTypeName', '');
                            $("#costTypeSelectedHidden").val('');
                        }
                    }
                });
            }else{
                $('#costShareSelectDiv input[type="checkbox"]').each(function(i,item){
                    if($(item).attr("disabled")){
                        $(item).removeAttr("disabled");
                        $(item).css("display","inline-block")
                    }
                });
            }

            // 避免一开始有值选了无,清空了行内的数据后,再点其他类型,进到费用明细选项页中,之前的那项还是选中的
            if(g.getCmpByRowAndCol(rowNum, "costTypeId").val() == ''){
                $('#costShareSelectDiv input[type="checkbox"]').each(function(i,item){
                    $(item).attr('checked', false).next().attr('class', '');
                });
            }
        },
        /**
         * select cost type
         * @param rowNum
         * @param jq
         */
        setCostType: function (rowNum, jq) {
            var costTypeSelectedHiddenObj = $("#costTypeSelectedHidden");
            $("#chk" + costTypeSelectedHiddenObj.val()).attr('checked', false).next().attr('class', ''); // 取消选中类型
            if (jq.attr("checked") == true) {
                // if($("#fundType") && $("#fundType").val() == 'KXXZB' && jq.attr('name') == '投标服务费'){
                //     $("#isNeedRelativeContract").val("1");
                //     $(".relativeContract").show();
                // }else{
                //     $("#relativeContract").val("");
                //     $("#relativeContractId").val("");
                //     $("#isNeedRelativeContract").val("");
                //     $(".relativeContract").hide();
                // }

                this.setRowColValue(rowNum, 'costTypeId', jq.val());
                this.setRowColValue(rowNum, 'costTypeName', jq.attr('name'));
                this.setRowColValue(rowNum, 'parentTypeId', jq.attr('parentId'));
                this.setRowColValue(rowNum, 'parentTypeName', jq.attr('parentName'));
                jq.next().attr('class', 'blue');
                costTypeSelectedHiddenObj.val(jq.val());
            } else {
                this.setRowColValue(rowNum, 'costTypeId', '');
                this.setRowColValue(rowNum, 'costTypeName', '');
                this.setRowColValue(rowNum, 'parentTypeId', '');
                this.setRowColValue(rowNum, 'parentTypeName', '');
                jq.next().attr('class', '');
                // $("#relativeContract").val("");
                // $("#relativeContractId").val("");
                // $("#isNeedRelativeContract").val("");
                // $(".relativeContract").hide();
                costTypeSelectedHiddenObj.val('');
            }
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
         * 导入数据
         * @param data
         */
        importData: function (data) {
            var g = this;

            data = eval("(" + data + ")");
            var j = g.getAllAddRowNum();
            var i;
            for (i in data) {
                // 如果导入的数据存在错误，则直接跳过
                if (data[i].result && data[i].result != "") continue;
                // 插入数据
                g.addRow(j, data[i]);
                // 初始化分摊对象以及关联信息
                var optionStr = '';
                var tmpShareObjType = shareObjTypeOptions[data[i].detailType];
                for (var k = 0; k < tmpShareObjType.length; k++) {
                    if (tmpShareObjType[k].value == data[i].shareObjType) {
                        optionStr += "<option value='" + tmpShareObjType[k].value + "' selected='selected'>"
                        + tmpShareObjType[k].text + "</option>";
                    } else {
                        optionStr += "<option value='" + tmpShareObjType[k].value + "'>" + tmpShareObjType[k].text
                        + "</option>";
                    }
                }
                this.getCmpByRowAndCol(j, "shareObjType").empty().append(optionStr);
                this.initShareObj(j, this.getCmpByRowAndCol(j, "shareObjType").val(), data[i]);
                this.setAreaInfo(j,data[i]['customerType'],data[i]['province'],data[i]['belongCompanyName'],data[i]['moduleName']);
                j++;
            }

            g.countShareMoney();
        },
        /**
         * 计算分摊金额
         */
        countShareMoney: function () {
            var g = this, el = this.el, p = this.options;
            var formMoney = $("#" + g.options.countKey).val();
            if (formMoney != '') {
                var sharedMoney = 0;
                var array = $("[id^='" + el.attr('id') + "_cmp_costMoney']");
                array.each(function () {
                    var rowNum = $(this).data('rowNum');
                    if ($(this).val() != "" && !g.isRowDel(rowNum)) {
                        sharedMoney = accAdd(sharedMoney, $(this).val(), 2);
                    }
                });
				// 把其他分页的也加上
				sharedMoney = accAdd(sharedMoney, g.otherPageMoney, 2);

                var canShareMoney = accSub(formMoney, sharedMoney, 2);
                $("#costShareCanShare").val(moneyFormat2(canShareMoney));
                $("#costShareShared").val(moneyFormat2(sharedMoney));

                if (p.thisPeriod != "") {
                    g.countThisPeriodWaitAudit();
                }
            }
        },
        /**
         * 统计当期未审核金额
         */
        countThisPeriodWaitAudit: function () {
            var g = this, el = this.el, p = this.options;
            var array = $("[id^='" + el.attr('id') + "_cmp_inPeriod']");
            // 因为勾稽部分使用的是隐藏内容，所以这里要特殊处理
            var thisPeriodWaitAudit = 0; // 分摊金额
            if (array.length > 0) {
                array.each(function () {
                    if ($(this).val() == p.thisPeriod) {
                        var rowNum = $(this).data('rowNum');
                        if (!g.isRowDel(rowNum)) {
                            thisPeriodWaitAudit = accAdd(thisPeriodWaitAudit,
                                g.getCmpByRowAndCol(rowNum, "costMoney").val(), 2);
                        }
                    }
                });
            }
            p.thisPeriodWaitAudit = thisPeriodWaitAudit;

            $("#showOther").html("当期未审核金额：" + moneyFormat2(thisPeriodWaitAudit));
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
            '<td style="text-align: right">' +
            moneyFormat2(sharedMoney) +
            '</td>' +
            '</tr>');
        },
        /**
         * 获取行内的值
         * @param rowNum
         */
        getRowData: function (rowNum) {
            var g = this;
            return {
                projectId: g.getCmpByRowAndCol(rowNum, "projectId").val(),
                projectCode: g.getCmpByRowAndCol(rowNum, "projectCode").val(),
                projectName: g.getCmpByRowAndCol(rowNum, "projectName").val(),
                chanceId: g.getCmpByRowAndCol(rowNum, "chanceId").val(),
                chanceCode: g.getCmpByRowAndCol(rowNum, "chanceCode").val(),
                province: g.getCmpByRowAndCol(rowNum, "province").val(),
                customerType: g.getCmpByRowAndCol(rowNum, "customerType").val(),
                contractId: g.getCmpByRowAndCol(rowNum, "contractId").val(),
                contractCode: g.getCmpByRowAndCol(rowNum, "contractCode").val(),
                contractName: g.getCmpByRowAndCol(rowNum, "contractName").val(),
                belongId: g.getCmpByRowAndCol(rowNum, "belongId").val(),
                belongName: g.getCmpByRowAndCol(rowNum, "belongName").val(),
                belongDeptId: g.getCmpByRowAndCol(rowNum, "belongDeptId").val(),
                belongDeptName: g.getCmpByRowAndCol(rowNum, "belongDeptName").val(),
                projectType: g.getCmpByRowAndCol(rowNum, "projectType").val(),
                customerName: g.getCmpByRowAndCol(rowNum, "customerName").val(),
                customerId: g.getCmpByRowAndCol(rowNum, "customerId").val(),
                parentTypeId: g.getCmpByRowAndCol(rowNum, "parentTypeId").val(),
                parentTypeName: g.getCmpByRowAndCol(rowNum, "parentTypeName").val(),
                costTypeId: g.getCmpByRowAndCol(rowNum, "costTypeId").val(),
                costTypeName: g.getCmpByRowAndCol(rowNum, "costTypeName").val(),
                costMoney: g.getCmpByRowAndCol(rowNum, "costMoney", true).val(),
                inPeriod: g.getCmpByRowAndCol(rowNum, "inPeriod").val(),
                belongPeriod: g.getCmpByRowAndCol(rowNum, "belongPeriod").val(),
                belongCompany: g.getCmpByRowAndCol(rowNum, "belongCompany").val(),
                belongCompanyName: g.getCmpByRowAndCol(rowNum, "belongCompanyName").val(),
                detailType: g.getCmpByRowAndCol(rowNum, "detailType").val(),
                shareObjType: g.getCmpByRowAndCol(rowNum, "shareObjType").val(),
                auditStatus: 0,
                feeManId: g.getCmpByRowAndCol(rowNum, "feeManId").val(),
                feeMan: g.getCmpByRowAndCol(rowNum, "feeMan").val(),
                salesAreaId: g.getCmpByRowAndCol(rowNum, "salesAreaId").val(),
                salesArea: g.getCmpByRowAndCol(rowNum, "salesArea").val()
            };
        },
        /**
         * 初始化下一行数据
         * @param rowNum
         */
        initNext: function (rowNum) {
            var g = this;
            var prevRowNum = rowNum - 1;
            while (prevRowNum >= 0) {
                if (g.isRowDel(prevRowNum) == true) {
                    prevRowNum--;
                } else {
                    break;
                }
            }
            var prevRowData = g.getRowData(prevRowNum);
            // 有些数据要自行赋值
            g.setRowColValue(rowNum, "costTypeId", prevRowData.costTypeId);
            g.setRowColValue(rowNum, "costTypeName", prevRowData.costTypeName);
            g.setRowColValue(rowNum, "parentTypeId", prevRowData.parentTypeId);
            g.setRowColValue(rowNum, "parentTypeName", prevRowData.parentTypeName);
            g.setRowColValue(rowNum, "costMoney", prevRowData.costMoney, true);
            g.setRowColValue(rowNum, "inPeriod", prevRowData.inPeriod);
            g.setRowColValue(rowNum, "belongPeriod", prevRowData.belongPeriod);
            g.setRowColValue(rowNum, "belongCompany", prevRowData.belongCompany);
            g.setRowColValue(rowNum, "belongCompanyName", prevRowData.belongCompanyName);
            g.setRowColValue(rowNum, "detailType", prevRowData.detailType);
            g.setRowColValue(rowNum, "shareObjType", prevRowData.shareObjType);

            // 加载分摊对象信息
            g.initDetailType(prevRowData.detailType, rowNum, prevRowData);
        },
        /**
         * 判断行是否被假删除
         */
        isRowDel : function(rowNum) {
            var g = this, el = this.el, p = this.options;
            // 如果查询不到行，则表示已经删除
            if ($(el).find("tr[rowNum='" + rowNum + "']").length == 0) {
                return true;
            } else {
                var delTagNameObj = $("#" + el.attr('id') + "_cmp_" + p.delTagName + rowNum);
                if (delTagNameObj.length == 0) {
                    return false;
                } else {
                    return delTagNameObj.val() == 1;
                }
            }
        },
        /**
         * 费用承担人
         * @param rowNum
         * @param deptId
         * @param textWidth
         * @param type
         */
        dealFeeMan: function (rowNum, deptId, textWidth, type) {
        	var g = this;
        	var feeManObj = g.getCmpByRowAndCol(rowNum, "feeMan");
        	var feeManIdObj = g.getCmpByRowAndCol(rowNum, "feeManId");
            $("#feeMansSelect_"+rowNum).remove();
        	//获取销售部
        	var saleDeptIdArr = $("#saleDeptId").val().split(",");
        	//费用归属部门为销售部，才显示费用承担人
            if(deptId == 329){// 系统商销售
                feeManObj.hide();
                feeManObj.val('');
                feeManIdObj.val('');
                feeManObj.yxselect_user("remove");
                var optsStr = getFeeMansSelectOpts();
                if(optsStr != ""){
                    feeManObj.before("<select id='feeMansSelect_"+rowNum+"' onchange='setFeeManInfo(\""+rowNum+"\")' style='width:100px'>"+optsStr+"</select>");
                    setFeeManInfo(rowNum);
                }
            }else if(saleDeptIdArr.indexOf(deptId) != -1){
        		//显示费用承担人
        		feeManObj.show();
        		//渲染费用承担人
        		feeManObj.yxselect_user("remove").yxselect_user({
        			hiddenId: feeManIdObj.attr('id'),
        			deptIds: deptId,
        			isDeptAddedUser: true,//部门需要额外添加人员
        			isDeptSetUserRange: true,//部门设置人员选择范围
        			event: {
        				clearReturn: function() {
        				}
        			}
        		}).width(textWidth);
        		//费用报销人所在部门与费用归属部门相同，则自动获取费用报销人员为费用承担人
        		if(type != 'init'){//初始化的时候不覆盖原来的值
            		if($("#deptId").val() == deptId){
            			feeManObj.val($("#principalName").val());
            			feeManIdObj.val($("#principalId").val());
            		}else{
            			feeManObj.val("");
            			feeManIdObj.val("");
            		}
        		}
        	}else{
        		//隐藏费用承担人
        		feeManObj.yxselect_user("remove").hide();
        		//费用承担人默认为报销单申请人
    			feeManObj.val($("#principalName").val());
    			feeManIdObj.val($("#principalId").val());
        	}
        },
        /**
         * 销售负责人
         * @param rowNum
         * @param customerType
         * @param province
         * @param businessBelong
         * @param module
         */
        setAreaInfo: function (rowNum, customerType, province, businessBelong, module) {
        	var g = this;
            var shareObjType = g.getCmpByRowAndCol(rowNum, "shareObjType").val();
            var needRelodSalesArea = false;// 是否需要异步更新销售区域信息标示 关联PMS2383
            // 编辑页面编辑次数标示
            var iniNum = g.getCmpByRowAndCol(rowNum, "salesAreaOpt").attr("data-initNum");
            iniNum = parseInt(iniNum);
            
            if(shareObjType == "FTDXLX-07" || shareObjType == "FTDXLX-08"){
                needRelodSalesArea = true;
            }
        	if (customerType != '' && province != '' && businessBelong != '' && module != '') {
    	        var returnValue = $.ajax({
    	            type: 'POST',
    	            url: "?model=system_region_region&action=ajaxConRegionByName",
    	            data: {
    	                customerType: customerType,
    	                province: province,
    	                businessBelong: businessBelong,
    	                module: module,
                        needAll: (needRelodSalesArea)? 1 : 0
    	            },
    	            async: false,
    	            success: function (data) {
    	            }
    	        }).responseText;
    	        returnValue = eval("(" + returnValue + ")");
    	        if (returnValue) {
    	            if(needRelodSalesArea){// 如果分摊类型属于【售前费用 - 省份/客户类型/归属部门】或【售前费用 - 客户】
                        if(g.getCmpByRowAndCol(rowNum, "salesArea").val() != '' && iniNum <= 0){
                            g.getCmpByRowAndCol(rowNum, "salesAreaOpt").html("");
                            g.getCmpByRowAndCol(rowNum, "salesAreaRead").val("");
                        }else{
                            g.getCmpByRowAndCol(rowNum, "salesAreaOpt").html("");
                            g.getCmpByRowAndCol(rowNum, "salesAreaRead").val("");
                            g.getCmpByRowAndCol(rowNum, "salesArea").val("");
                            g.getCmpByRowAndCol(rowNum, "salesAreaId").val("");
                        }
                        if(returnValue.length > 1){// 有多个销售区域,显示下拉选项,隐藏文本框
                            var optionsStr = '<option value="">..请选择..</option>';
                            $.each(returnValue,function(){
                                if(g.getCmpByRowAndCol(rowNum, "salesArea").val() != '' && iniNum <= 0){
                                    if(g.getCmpByRowAndCol(rowNum, "salesAreaId").val() == $(this)[0]['id']){
                                        iniNum += 1;
                                        g.getCmpByRowAndCol(rowNum, "salesAreaOpt").attr("data-initNum",iniNum);
                                        g.getCmpByRowAndCol(rowNum, "salesAreaRead").val($(this)[0]['areaName']);
                                        optionsStr += '<option value="'+$(this)[0]['id']+'" selected>'+$(this)[0]['areaName']+'</option>';
                                    }else{
                                        optionsStr += '<option value="'+$(this)[0]['id']+'">'+$(this)[0]['areaName']+'</option>';
                                    }
                                }else{
                                    optionsStr += '<option value="'+$(this)[0]['id']+'">'+$(this)[0]['areaName']+'</option>';
                                }
                            });
                            g.getCmpByRowAndCol(rowNum, "salesAreaOpt").html(optionsStr);
                            g.getCmpByRowAndCol(rowNum, "salesAreaOpt").show();
                            g.getCmpByRowAndCol(rowNum, "salesAreaRead").hide();

                            // 用户选择区域后,更新相应位置的销售区域信息
                            var selectOrId = g.getCmpByRowAndCol(rowNum, "salesAreaOpt").attr("id");
                            $("#"+selectOrId).change(function(){
                                var salesAreaId = $('#'+selectOrId+' option:selected').val();
                                var salesArea = $('#'+selectOrId+' option:selected').text();
                                g.getCmpByRowAndCol(rowNum, "salesAreaRead").val(salesArea);
                                g.getCmpByRowAndCol(rowNum, "salesArea").val(salesArea);
                                g.getCmpByRowAndCol(rowNum, "salesAreaId").val(salesAreaId);
                            });
                        }else{
                            g.getCmpByRowAndCol(rowNum, "salesArea").val(returnValue[0].areaName);
                            g.getCmpByRowAndCol(rowNum, "salesAreaId").val(returnValue[0].id);
                            g.getCmpByRowAndCol(rowNum, "salesAreaRead").val(returnValue[0].areaName);
                            g.getCmpByRowAndCol(rowNum, "salesAreaRead").show();
                            g.getCmpByRowAndCol(rowNum, "salesAreaOpt").html("");
                            g.getCmpByRowAndCol(rowNum, "salesAreaOpt").hide();
                        }
                    }else{
                        g.getCmpByRowAndCol(rowNum, "salesArea").val(returnValue[0].areaName);
                        g.getCmpByRowAndCol(rowNum, "salesAreaId").val(returnValue[0].id);
                    }
    	        } else {
                    if(needRelodSalesArea){// 如果分摊类型属于【售前费用 - 省份/客户类型/归属部门】或【售前费用 - 客户】
                        g.getCmpByRowAndCol(rowNum, "salesAreaOpt").html("");
                        g.getCmpByRowAndCol(rowNum, "salesAreaRead").val("");
                        g.getCmpByRowAndCol(rowNum, "salesAreaOpt").hide();
                        g.getCmpByRowAndCol(rowNum, "salesAreaRead").show();
                    }
    	        	g.getCmpByRowAndCol(rowNum, "salesArea").val("");
    	        	g.getCmpByRowAndCol(rowNum, "salesAreaId").val("");
    	        }
    	    } else {
    	        return false;
    	    }
        }
    });
})(jQuery);