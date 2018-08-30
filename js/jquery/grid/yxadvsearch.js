/**
 * �߼��������
 */
(function ($) {
    $.woo.component.subclass('woo.yxadvsearch', {
        options: {
            // �¼�
            // confirmAdvsearch ����ȷ�ϰ�ť�¼�
            afterConfirmAction: "close"// ������ť����Ϊ close �رմ��� minimize ��С�� hide
            // ����
        },
        /**
         * ��ʼ�����
         */
        _create: function () {
            var g = this, el = this.el, p = this.options;
            var param = {
                showModal: true,
                modalOpacity: 0.5,
                width: 1000,
                frameClass: '12',
                title: "�߼�����",
                content: "<div  style='padding:1px;float:left;height:250px;width:20%;overflow:auto;'><table class='form_main_table' id='caseList'></table></div>"
                    + "<div id='advsearchgrid'></div><div id='sortgrid'></div>"
                    + "<div style='text-align:center'>"
                    + "<input id='confirmAdvsearch' type='button' class='txt_btn_a' value='����'> "
                    + "<input id='saveAdvsearch' type='button' class='txt_btn_a' value='����'></div>"
            };
            $.extend(param, p.windowOptions);

            var w = $.window(param);
            g.window = w;
            var selectedItemId;
            var selectedItemName;
            var $selectedItem;
            var $tr = $('<tr><td ><div><span class="systemView">�Զ�����ͼ</span></div></td></tr>');
            var $td = $('<td ></td>').appendTo($tr);
            $("#caseList").append($tr);
            var $addBn = $('<span  class="addBn" title="����Զ�����ͼ">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>');
            $td.append($addBn);
            $addBn.click(function () {
                selectedItemId = null;
                selectedItemName = null;
                $selectedItem = null;
                $("#advsearchgrid").yxeditgrid('removeAll', true).yxeditgrid('addRow', 0, {});
            });
            /**
             * ��ӷ���
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
                        if (confirm("ȷ��ɾ���˷���?")) {
                            $.ajax({
                                url: '?model=system_adv_advcase&action=ajaxdeletes',
                                type: "POST",
                                data: {
                                    id: dataItemId
                                },
                                success: function (msg) {
                                    if (msg == 1) {
                                        $case.parent().parent().remove();
                                        alert("ɾ���ɹ�.");
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
                title: '��������',
                isFristRowDenyDel: true,
                event: {
                    // �Ƴ����¼�
                    removeRow: function (e, rowNum, rowData, index) {
                        var $valInput = $("#advsearchgrid").yxeditgrid(
                            "getCmpByRowAndCol", index, "value");
                        if ($.isFunction(p.advSearchOptions.selectFn)) {
                            p.advSearchOptions.selectFn($valInput);
                        }
                    },
                    // ������¼�
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
                        display: '�߼�',
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
                                name: '����',
                                value: 'and'
                            },
                            {
                                name: '����',
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
                        display: '��ѯ�ֶ�',
                        name: 'searchField',
                        type: 'select',
                        options: searchConfig,
                        event: {
                            /**
                             * isKeepVal:�ٷ���ʱ���Ƿ񱣳�ԭֵ
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
                        display: '�ȽϹ�ϵ',
                        name: 'compare',
                        type: 'select',
                        options: [
                            {
                                name: '����',
                                value: 'like'
                            },
                            {
                                name: '����',
                                value: '='
                            },
                            {
                                name: '������',
                                value: '!='
                            },
                            {
                                name: '����',
                                value: '>'
                            },
                            {
                                name: 'С��',
                                value: '<'
                            },
                            {
                                name: '������',
                                value: 'not like'
                            }
                        ]
                    },
                    {
                        display: '��ֵ',
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

            // ��ȡ������������
            g.advSearchArr = [];
            function getSearchCondition() {//TODO �߼�����
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
                    alert("�������Ų�ƥ��,���顣");
                    return false;
                }
                // ������������
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

            // ��������
            if (p.advSearchOptions.sortConfig) {
                g.processSortGrid();
            }
            // ȷ������
            $("#confirmAdvsearch").click(function () {
                var r = getSearchCondition();
                if (r) {
                    if (p.grid) {
                        p.grid.reload();
                    }
                    // �����¼�
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
                var caseName = prompt("������������������", selectedItemName
                    ? selectedItemName
                    : '');
                if (caseName) {
                    getSearchCondition();
                    // ����ɾ��
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
                                alert("����ɹ�!");
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
                    alert("������������������")
                }
            });
        },
        /**
         * ����������
         */
        processSortGrid: function () {
            var g = this, el = this.el, p = this.options;
            var sortConfig = p.advSearchOptions.sortConfig;
            $("#sortgrid").yxeditgrid({
                objName: 'advSort',
                title: '��������',
                colModel: [
                    {
                        display: 'id',
                        name: 'id',
                        type: 'hidden'
                    },
                    {
                        display: '�����ֶ�',
                        name: 'sortField',
                        type: 'select',
                        options: sortConfig
                    },
                    {
                        display: '������',
                        name: 'sort',
                        type: 'select',
                        options: [
                            {
                                name: '����',
                                value: 'ASC'
                            },
                            {
                                name: '����',
                                value: 'DESC'
                            }
                        ]
                    }
                ]
            });
        },
        /**
         * ����߼����������ֵ䣬����������ѡ�� datacode ����������� ������ַ�������Ϊ�����ֵ�code,��������飬��Ϊ��̬����
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
         * ѡ��ĳһ����ͼ���б༭
         */
        selectCustomItemToEdit: function (itemId) {
            $("#caseList").find("a[id='caseHref" + itemId + "']").click();
        },
        /**
         * ��ȡ�߼���������
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