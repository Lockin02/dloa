/**
 * 高级搜索组件
 */
(function ($) {
    $.woo.component.subclass('woo.yxadvsearch', {
        options: {
            // 事件
            // confirmAdvsearch 按下确认按钮事件
            afterConfirmAction: "close"// 搜索按钮后行为 close 关闭窗口 minimize 最小化 hide
            // 隐藏
        },
        /**
         * 初始化组件
         */
        _create: function () {
            var g = this, el = this.el, p = this.options;
            var param = {
                showModal: true,
                modalOpacity: 0.5,
                width: 1000,
                frameClass: '12',
                title: "高级搜索",
                content: "<div  style='padding:1px;float:left;height:250px;width:20%;overflow:auto;'><table class='form_main_table' id='caseList'></table></div>"
                    + "<div id='advsearchgrid'></div><div id='sortgrid'></div>"
                    + "<div style='text-align:center'>"
                    + "<input id='confirmAdvsearch' type='button' class='txt_btn_a' value='搜索'> "
                    + "<input id='saveAdvsearch' type='button' class='txt_btn_a' value='保存'></div>"
            };
            $.extend(param, p.windowOptions);

            var w = $.window(param);
            g.window = w;
            var selectedItemId;
            var selectedItemName;
            var $selectedItem;
            var $tr = $('<tr><td ><div><span class="systemView">自定义视图</span></div></td></tr>');
            var $td = $('<td ></td>').appendTo($tr);
            $("#caseList").append($tr);
            var $addBn = $('<span  class="addBn" title="添加自定义视图">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>');
            $td.append($addBn);
            $addBn.click(function () {
                selectedItemId = null;
                selectedItemName = null;
                $selectedItem = null;
                $("#advsearchgrid").yxeditgrid('removeAll', true).yxeditgrid('addRow', 0, {});
            });
            /**
             * 添加方案
             */
            var addCase = function (dataItem) {
                var $tr = $("<tr style='background-color:#ffffff'></tr>");
                var $td = $("<td class='form_view_left divChangeLine'></td>");
                var $case = $("<a id='caseHref" + dataItem.id
                    + "' href='#' style='float:left'>"
                    // + "<span style='float:left'
                    // class='caseHref'>&nbsp;&nbsp;&nbsp;&nbsp;</span>"
                    + dataItem.caseName + "</a>");
                $td.append($case);
                $tr.append($td);
                $case.click(function (dataItemId) {
                    return function () {
                        $case.parents("table").find("tr").css(
                            "background-color", "#ffffff");
                        $case.parents("tr").css("background-color", "#CAD0E4");
                        $.ajax({
                            url: '?model=system_adv_advcasedetail&action=listJson',
                            type: "POST",
                            dataType: 'json',
                            data: {
                                caseId: dataItemId
                            },
                            success: function (data) {
                                if (data) {
                                    selectedItemId = dataItemId;
                                    selectedItemName = dataItem.caseName;
                                    $selectedItem = $case;

                                    $("#advsearchgrid").yxeditgrid(
                                        'reloadData', data);
                                    var $searchFieldCmps = $("#advsearchgrid")
                                        .yxeditgrid("getCmpByCol",
                                            "searchField");
                                    $searchFieldCmps.each(function () {
                                        var rowNum = $(this)
                                            .data("rowNum");
                                        var $valueCmp = $("#advsearchgrid")
                                            .yxeditgrid(
                                                "getCmpByRowAndCol",
                                                rowNum, 'value');
                                        var v = $valueCmp.val();
                                        $(this).trigger('change',
                                            [true]);
                                        $valueCmp.val(v);
                                    });
                                } else {
                                    $("#advsearchgrid").yxeditgrid('removeAll',
                                        true);
                                }
                            }
                        });
                    }
                }(dataItem.id));
                $("#caseList").append($tr);
                var $td = $("<td class='form_view_right'></td>");
                var $del = $("<span style='float:right' class='delBn'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>");
                $tr.append($td);
                $td.append($del);
                $del.click(function (dataItemId) {
                    return function () {
                        if (confirm("确认删除此方案?")) {
                            $.ajax({
                                url: '?model=system_adv_advcase&action=ajaxdeletes',
                                type: "POST",
                                data: {
                                    id: dataItemId
                                },
                                success: function (msg) {
                                    if (msg == 1) {
                                        $case.parent().parent().remove();
                                        alert("删除成功.");
                                    }
                                }

                            });
                        }
                    }
                }(dataItem.id));
            }

            var data = $.ajax({
                url: '?model=system_adv_advcase&action=listByCurJson',
                type: 'POST',
                dataType: 'json',
                async: false,
                data: {
                    modelName: p.advSearchOptions.modelName
                }
            }).responseText;
            data = eval("(" + data + ")");
            for (var i = 0; i < data.length; i++) {
                var dataItem = data[i];
                addCase(dataItem);
            }

            var changeFnMap = {};
            var searchConfig = p.advSearchOptions.searchConfig;
            for (var i = 0; i < searchConfig.length; i++) {
                var searchItem = searchConfig[i];
                changeFnMap[searchItem.value] = searchItem.changeFn;
            }
            $("#advsearchgrid").yxeditgrid({
                objName: 'advSearch',
                title: '搜索条件',
                isFristRowDenyDel: true,
                event: {
                    // 移除行事件
                    removeRow: function (e, rowNum, rowData, index) {
                        var $valInput = $("#advsearchgrid").yxeditgrid(
                            "getCmpByRowAndCol", index, "value");
                        if ($.isFunction(p.advSearchOptions.selectFn)) {
                            p.advSearchOptions.selectFn($valInput);
                        }
                    },
                    // 添加行事件
                    addRow: function (e, rowNum, rowData) {
                        if (!rowData || !rowData.id) {
                            var $input = $("#advsearchgrid").yxeditgrid(
                                'getCmpByRowAndCol', rowNum, "searchField");
                            $input.trigger('change');
                        }
                    }
                },
                colModel: [
                    {
                        display: 'id',
                        name: 'id',
                        type: 'hidden'
                    },
                    {
                        display: '逻辑',
                        name: 'logic',
                        type: 'select',
                        process: function ($input, rowData, $tr, g) {
                            var rowNum = $input.data("rowNum");
                            if (rowNum == 0) {
                                $input.attr("disabled", true);
                            }

                        },
                        options: [
                            {
                                name: '并且',
                                value: 'and'
                            },
                            {
                                name: '或者',
                                value: 'or'
                            }
                        ]
                    },
                    {
                        display: '',
                        name: 'leftK',
                        type: 'select',
                        width: 42,
                        options: [
                            {
                                name: '(',
                                value: '('
                            },
                            {
                                name: '((',
                                value: '(('
                            },
                            {
                                name: '(((',
                                value: '((('
                            },
                            {
                                name: '((((',
                                value: '(((('
                            }
                        ]
                    },
                    {
                        display: '查询字段',
                        name: 'searchField',
                        type: 'select',
                        options: searchConfig,
                        event: {
                            /**
                             * isKeepVal:促发的时候是否保持原值
                             */
                            change: function (e, isKeepVal) {
                                var v = $(this).val();
                                var rowNum = $(this).data("rowNum");
                                var $valInput = $("#advsearchgrid")
                                    .yxeditgrid("getCmpByRowAndCol",
                                        rowNum, "value");
                                if (!isKeepVal) {
                                    $valInput.val("");
                                }
                                $valInput.unbind();
                                if ($
                                    .isFunction(p.advSearchOptions.selectFn)) {
                                    p.advSearchOptions.selectFn($valInput);
                                }
                                var $selectedOption = $(this)
                                    .find("option:selected");
                                var type = $selectedOption.data('type');
                                if (type == 'select') {
                                    var datacode = $selectedOption
                                        .data('datacode');
                                    var val = $selectedOption.data('val');
                                    var optionData = $selectedOption
                                        .data('options');
                                    if (optionData) {
                                        datacode = optionData;
                                    }
                                    $valInputC = g.processAdvDatadict(
                                        datacode, $valInput, val);
                                } else {
                                    $valInputC = $("<input type='text' class='txtmiddle'>");
                                    $valInputC.attr('name', $valInput
                                        .attr('name'));
                                    $valInputC.attr('id', $valInput
                                        .attr('id'));
                                }
                                if (isKeepVal) {
                                    $valInputC.val($valInput.val());
                                }
                                $valInput.parent().append($valInputC);
                                $valInput.remove();
                                if ($.isFunction(changeFnMap[v])) {
                                    changeFnMap[v]($(this), $valInputC,
                                        rowNum);
                                }
                            }
                        }
                    },
                    {
                        display: '比较关系',
                        name: 'compare',
                        type: 'select',
                        options: [
                            {
                                name: '包含',
                                value: 'like'
                            },
                            {
                                name: '等于',
                                value: '='
                            },
                            {
                                name: '不等于',
                                value: '!='
                            },
                            {
                                name: '大于',
                                value: '>'
                            },
                            {
                                name: '小于',
                                value: '<'
                            },
                            {
                                name: '不包含',
                                value: 'not like'
                            }
                        ]
                    },
                    {
                        display: '数值',
                        name: 'value'
                    },
                    {
                        display: '',
                        name: 'rightK',
                        type: 'select',
                        width: 42,
                        options: [
                            {
                                name: ')',
                                value: ')'
                            },
                            {
                                name: '))',
                                value: '))'
                            },
                            {
                                name: ')))',
                                value: ')))'
                            },
                            {
                                name: '))))',
                                value: '))))'
                            }
                        ]
                    }
                ]
            });
            var $searchFieldCmps = $("#advsearchgrid").yxeditgrid("getCmpByCol", "searchField");
            $searchFieldCmps.each(function () {
                $(this).trigger('change');
            });

            // 获取搜索条件数组
            g.advSearchArr = [];
            function getSearchCondition() {//TODO 高级搜索
                g.advSearchArr = [];
                var advsearchgridObj = $("#advsearchgrid");
                var $logicCmps = advsearchgridObj.yxeditgrid("getCmpByCol", 'logic');
                var allLeftLength = 0;
                var allRightLength = 0;
                $logicCmps.each(function () {
                    var logic = $(this).val();
                    var rowNum = $(this).data("rowNum");
                    var detailId = advsearchgridObj.yxeditgrid("getCmpByRowAndCol", rowNum, 'id').val();
                    var searchField = advsearchgridObj.yxeditgrid("getCmpByRowAndCol", rowNum, 'searchField').val();
                    var compare = advsearchgridObj.yxeditgrid("getCmpByRowAndCol", rowNum, 'compare').val();
                    var value = advsearchgridObj.yxeditgrid("getCmpByRowAndCol", rowNum, 'value').val();
                    var leftK = advsearchgridObj.yxeditgrid("getCmpByRowAndCol", rowNum, 'leftK').val();
                    var rightK = advsearchgridObj.yxeditgrid("getCmpByRowAndCol", rowNum, 'rightK').val();
                    allLeftLength += leftK.length;
                    allRightLength += rightK.length;
                    var searchItem = {
                        id: detailId,
                        logic: logic,
                        searchField: searchField,
                        compare: compare,
                        value: value,
                        leftK: leftK,
                        rightK: rightK
                    };
                    g.advSearchArr.push(searchItem);
                });
                if (allLeftLength != allRightLength) {
                    alert("条件括号不匹配,请检查。");
                    return false;
                }
                // 处理排序数据
                g.advSortArr = [];
                var sortGridObj = $("#sortgrid");
                var $sortCmps = sortGridObj.yxeditgrid("getCmpByCol", 'sortField');
                $sortCmps.each(function () {
                    var rowNum = $(this).data("rowNum");
                    if (rowNum !== null) {
                        var sortField = sortGridObj.yxeditgrid("getCmpByRowAndCol", rowNum, 'sortField').val();
                        var sort = sortGridObj.yxeditgrid("getCmpByRowAndCol", rowNum, 'sort').val();
                        var sortItem = {
                            sortField: sortField,
                            sort: sort
                        };
                        g.advSortArr.push(sortItem);
                    }
                });
                if (p.grid) {
                    p.grid.options.extParam.advArr = g.advSearchArr;
                    if (g.advSortArr.length > 0) {
                        p.grid.options.extParam.sortArr = g.advSortArr;
                    }
                }
                return true;
            }

            // 处理排序
            if (p.advSearchOptions.sortConfig) {
                g.processSortGrid();
            }
            // 确认搜索
            $("#confirmAdvsearch").click(function () {
                var r = getSearchCondition();
                if (r) {
                    if (p.grid) {
                        p.grid.reload();
                    }
                    // 触发事件
                    $(el).trigger('confirmAdvsearch', [g]);
                    if (p.afterConfirmAction == "close") {
                        w.close();
                    } else if (p.afterConfirmAction == "minimize") {
                        w.minimize();
                    } else {
                        //w.minimize();
                        w.hide();
                    }
                }
            });

            $("#saveAdvsearch").click(function () {
                var caseName = prompt("请输入搜索方案名称", selectedItemName
                    ? selectedItemName
                    : '');
                if (caseName) {
                    getSearchCondition();
                    // 处理被删除
                    var $idCmps = $("#advsearchgrid").yxeditgrid(
                        "getDelCmpByCol", 'id');
                    $idCmps.each(function () {
                        var detailId = $(this).val();
                        var searchItem = {
                            id: detailId,
                            isDelTag: 1
                        };
                        if (p.grid) {
                            g.advSearchArr.push(searchItem);
                        }
                    });
                    var param = {
                        advcase: {
                            caseName: caseName,
                            modelName: p.advSearchOptions.modelName,
                            detail: g.advSearchArr
                        }
                    };
                    if (selectedItemId) {
                        param.advcase.id = selectedItemId;
                    }
                    $.ajax({
                        url: '?model=system_adv_advcase&action=saveAjax',
                        type: 'POST',
                        data: param,
                        success: function (data) {
                            if (data) {
                                alert("保存成功!");
                                if (!selectedItemId) {
                                    var dataItem = {
                                        id: data,
                                        caseName: caseName
                                    };
                                    addCase(dataItem);
                                    if (p.grid
                                        && p.grid.options
                                        && p.grid.options.leftLayout
                                        && !selectedItemId) {
                                        p.grid
                                            .addCustomViewItem(dataItem);
                                    }
                                } else {
                                    $selectedItem.html(caseName);
                                }
                            }
                        }
                    });
                } else if (caseName && caseName.trim() == '') {
                    alert("请输入搜索方案名称")
                }
            });
        },
        /**
         * 处理排序表格
         */
        processSortGrid: function () {
            var g = this, el = this.el, p = this.options;
            var sortConfig = p.advSearchOptions.sortConfig;
            $("#sortgrid").yxeditgrid({
                objName: 'advSort',
                title: '排序条件',
                colModel: [
                    {
                        display: 'id',
                        name: 'id',
                        type: 'hidden'
                    },
                    {
                        display: '排序字段',
                        name: 'sortField',
                        type: 'select',
                        options: sortConfig
                    },
                    {
                        display: '升降序',
                        name: 'sort',
                        type: 'select',
                        options: [
                            {
                                name: '升序',
                                value: 'ASC'
                            },
                            {
                                name: '降序',
                                value: 'DESC'
                            }
                        ]
                    }
                ]
            });
        },
        /**
         * 处理高级搜索数据字典，并创建下拉选择 datacode 存在两种情况 如果是字符串，则为数据字典code,如果是数组，则为静态数据
         */
        processAdvDatadict: function (datacode, $valInput, val) {
            var g = this, el = this.el, p = this.options, cm = p.colModel;
            $input = $("<select class='txtmiddle'>");
            $input.attr('id', $valInput.attr('id'));
            $input.attr('name', $valInput.attr('name'));
            var datadictData = [];
            if ($.isArray(datacode)) {
                datadictData['key'] = datacode;
                datacode = "key";
            } else {
                datadictData = $.ajax({
                    type: 'POST',
                    url: "?model=system_datadict_datadict&action=getDataJsonByCodes",
                    data: {
                        codes: datacode
                    },
                    async: false
                }).responseText;
                datadictData = eval("(" + datadictData + ")");
            }
            var selected = "";
            for (var i = 0; i < datadictData[datacode].length; i++) {
                var datadict = datadictData[datacode][i];
                if (val == datadict.dataCode) {
                    selected = "selected";
                }
                var $option = $("<option " + selected + " value='"
                    + datadict.dataCode + "'>" + datadict.dataName
                    + "</option>");
                $input.append($option);
            }
            return $input;
        },
        /**
         * 选中某一个视图进行编辑
         */
        selectCustomItemToEdit: function (itemId) {
            $("#caseList").find("a[id='caseHref" + itemId + "']").click();
        },
        /**
         * 获取高级搜索条件
         */
        getAdvSearchArr: function () {
            return this.advSearchArr;
        },
        restore: function () {
            this.window.restore();
        },
        hide: function () {
            this.window.hide();
        },
        show: function () {
            this.window.show();
        }
    });

})(jQuery);