// / <reference path="../intellisense/jquery-1.2.6-vsdoc-cn.js" />
// / <reference path="../lib/blackbird.js" />
/**
 * �߼������� �̳л������:���빤�������Ҽ��˵�
 */
(function ($) {
    $.woo.yxsgrid.subclass('woo.yxgrid', {
        options: {
            // ------ ��չ���Կ�ʼ-------
            /**
             * ��չ��ť
             */
            buttonsEx: [],
            /**
             * �˵���չ����
             */
            menusEx: [],
            /**
             * ���ݹ�������ѡ����չ
             */
            comboEx: [],
            /**
             * �Ƿ���ʾ�Ҽ��˵������Ϊflase�����Ҽ��˵�ʧЧ
             *
             * @type Boolean
             */
            isRightMenu: true,
            /**
             * �Ҽ��˵�Ĭ�Ͽ��
             */
            menuWidth: 120,
            /**
             * �Ƿ���ʾ������
             *
             * @type Boolean
             */
            isToolBar: true,
            /**
             * �Ƿ���ʾ��ӡ��ذ�ť/�˵�
             *
             * @type Boolean
             */
            isPrintAction: false,
            /**
             * �Ƿ���ʾ��Ӱ�ť/�˵�
             *
             * @type Boolean
             */
            isAddAction: true,
            /**
             * �Ƿ���ʾ�鿴��ť/�˵�
             *
             * @type Boolean
             */
            isViewAction: true,
            /**
             * �Ƿ���ʾ�޸İ�ť/�˵�
             *
             * @type Boolean
             */
            isEditAction: true,
            /**
             * �Ƿ���ʾɾ����ť/�˵�
             *
             * @type Boolean
             */
            isDelAction: true,
            /**
             * �Ƿ���ʾ�߼�������ť������չ��
             *
             * @type Boolean
             */
            isAdvanceSearch: true,
            /**
             * �Ƿ���ʾ����excel��ť
             *
             * @type Boolean
             */
            isToExcel: false,
            /**
             * �Ƿ���ʾ���ݲ�����ť
             */
            isOpButton: true,
            /**
             * �Ƿ���ʾ��߲�ѯ��
             */
            leftLayout: false,
            /**
             * ��߲�ѯ���Ƿ�Ĭ�Ϲر� - ֻ�е�leftLayout����ʱ�Ż���Ч
             */
            leftLayoutInitClosed: true,
            /**
             * ��Ĭ�Ͽ��
             */
            formWidth: 800,
            /**
             * ��Ĭ�Ͽ��
             */
            formHeight: 400,
            /**
             * ��������������
             */
            toAddConfig: {
                text: '����',
                /**
                 * Ĭ�ϵ��������ť�����¼�
                 */
                toAddFn: function (p) {
                    var c = p.toAddConfig;
                    var w = c.formWidth ? c.formWidth : p.formWidth;
                    var h = c.formHeight ? c.formHeight : p.formHeight;
                    showThickboxWin("?model="
                        + p.model
                        + "&action="
                        + c.action
                        + c.plusUrl
                        + "&placeValuesBefore&TB_iframe=true&modal=false&height="
                        + h + "&width=" + w);
                },
                /**
                 * ���������õĺ�̨����
                 */
                action: 'toAdd',
                /**
                 * ׷�ӵ�url
                 */
                plusUrl: '',
                /**
                 * ������Ĭ�Ͽ��
                 */
                formWidth: 0,
                /**
                 * ������Ĭ�ϸ߶�
                 */
                formHeight: 0
            },
            /**
             * �鿴����������
             */
            toViewConfig: {
                text: '�鿴',
                /**
                 * Ĭ�ϵ���鿴��ť�����¼�
                 */
                toViewFn: function (p, g) {
                    var c = p.toViewConfig;
                    var w = c.formWidth ? c.formWidth : p.formWidth;
                    var h = c.formHeight ? c.formHeight : p.formHeight;
                    var rowObj = g.getSelectedRow();
                    if (rowObj) {
                        var rowData = rowObj.data('data');
                        var keyUrl = "";
                        if (rowData['skey_']) {
                            keyUrl = "&skey=" + rowData['skey_'];
                        }
                        showThickboxWin("?model="
                            + p.model
                            + "&action="
                            + p.toViewConfig.action
                            + c.plusUrl
                            + "&id="
                            + rowData[p.keyField]
                            + keyUrl
                            + "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height="
                            + h + "&width=" + w);
                    } else {
                        alert('��ѡ��һ�м�¼��');
                    }
                },
                /**
                 * ���ر�Ĭ�ϵ��õĺ�̨����
                 */
                action: 'init',
                /**
                 * ׷�ӵ�url
                 */
                plusUrl: '',
                /**
                 * �鿴��Ĭ�Ͽ��
                 */
                formWidth: 0,
                /**
                 * �鿴��Ĭ�ϸ߶�
                 */
                formHeight: 0
            },
            /**
             * �༭����������
             */
            toEditConfig: {
                text: '�༭',
                /**
                 * Ĭ�ϵ���༭��ť�����¼�
                 */
                toEditFn: function (p, g) {
                    var c = p.toEditConfig;
                    var w = c.formWidth ? c.formWidth : p.formWidth;
                    var h = c.formHeight ? c.formHeight : p.formHeight;
                    var rowObj = g.getSelectedRow();
                    if (rowObj) {
                        var rowData = rowObj.data('data');
                        var keyUrl = "";
                        if (rowData['skey_']) {
                            keyUrl = "&skey=" + rowData['skey_'];
                        }
                        showThickboxWin("?model="
                            + p.model
                            + "&action="
                            + c.action
                            + c.plusUrl
                            + "&id="
                            + rowData[p.keyField]
                            + keyUrl
                            + "&placeValuesBefore&TB_iframe=true&modal=false&height="
                            + h + "&width=" + w);
                    } else {
                        alert('��ѡ��һ�м�¼��');
                    }
                },
                /**
                 * ���ر�Ĭ�ϵ��õĺ�̨����
                 */
                action: 'init',
                /**
                 * ׷�ӵ�url
                 */
                plusUrl: '',
                /**
                 * �༭��Ĭ�Ͽ��
                 */
                formWidth: 0,
                /**
                 * �༭��Ĭ�ϸ߶�
                 */
                formHeight: 0
            },
            /**
             * ɾ����������
             */
            toDelConfig: {
                text: 'ɾ��',
                /**
                 * Ĭ�ϵ��ɾ����ť�����¼�
                 */
                toDelFn: function (p, g) {
                    var rowIds = g.getCheckedRowIds();
                    var rowObj = g.getFirstSelectedRow();
                    var key = "";
                    if (rowObj) {
                        var rowData = rowObj.data('data');
                        if (rowData['skey_']) {
                            key = rowData['skey_'];
                        }
                    }
                    if (rowIds[0]) {
                        if (window.confirm("ȷ��Ҫɾ��?")) {
                            $.ajax({
                                type: "POST",
                                url: "?model=" + p.model + "&action="
                                    + p.toDelConfig.action
                                    + p.toDelConfig.plusUrl,
                                data: {
                                    id: g.getCheckedRowIds()
                                        .toString(),
                                    skey: key
                                    // ת������,������ʽ
                                },
                                success: function (msg) {
                                    if (msg == 1) {
                                        if (window.show_page != "undefined") {
                                            show_page();
                                        } else {
                                            g.reload();
                                        }
                                        alert('ɾ���ɹ���');
                                    } else if (msg == 2) {
                                        alert('�Ƿ�����');
                                    } else if (msg != '' && msg != 0) {
                                        alert(msg);
                                    } else {
                                        if (p.toDelConfig.errorMsg != '') {
                                            alert(p.toDelConfig.errorMsg);
                                        } else {
                                            alert('ɾ��ʧ�ܣ��ö�������Ѿ�������!');
                                        }
                                    }
                                }
                            });
                        }
                    } else {
                        alert('��ѡ��һ�м�¼��');
                    }
                },
                /**
                 * ɾ��Ĭ�ϵ��õĺ�̨����
                 */
                action: 'ajaxdeletes',
                /**
                 * ׷�ӵ�url
                 */
                plusUrl: '',
                errorMsg: ''
            },
            /**
             * ��ӡ����������
             */
            toPrintConfig: {
                text: '��ӡ',
                /**
                 * Ĭ�ϵ����ӡ��ť�����¼�
                 */
                toPrintFn: function (p) {
                    javascript: prn_print();
                }
            },
            /**
             * ��ӡԤ������������
             */
            toPreviewConfig: {
                text: '��ӡԤ��',
                /**
                 * Ĭ�ϵ����ӡԤ����ť�����¼�
                 */
                toPreviewFn: function (p) {
                    javascript: prn_preview();
                }
            },
            /**
             * ��ӡԤ������������
             */
            toDesignConfig: {
                text: 'ҳ������',
                /**
                 * Ĭ�ϵ��ҳ�����ð�ť�����¼�
                 */
                toDesignFn: function (p) {
                    javascript: prn_design();
                }
            },
            /**
             * ��������״̬��ʾ�˵�/��ť��������
             */
            showFn: function (row) {
                if (!row.ExaStatus) {
                    return true;
                } else if (row.ExaStatus == '���' || row.ExaStatus == 'δ�ύ') {
                    return true;
                }
                return false;
            },
            /**
             * �߼���������
             */
            advSearchOptions: {

            }

            // ------ ��չ���Խ���-------
        },
        /**
         * ��ʼ�����
         */
        _create: function () {

            // var w=$.window({
            // showModal : false,
            // minimizable:true,
            // maximizable:false,
            // closable:false,
            // draggable:false,
            // resizable:false,
            // modalOpacity : 0.5,
            // width : 200,
            // height:'100%',
            // x:0,
            // title : "��ͼ",
            // content : $("#view").html()
            // });
            // w.minimize();
        },
        /**
         * ��̬��Ӱ�ť
         */
        _getButtons: function () {
            var g = this, el = this.el, p = this.options, buttons = [];
            if (p.isAddAction) {
                buttons.push({
                    name: 'Add',
                    text: p.toAddConfig.text,
                    index: 10,
                    icon: 'add',
                    action: function () {
                        p.toAddConfig.toAddFn(p, g);
                    }
                });
            }
            if (p.isPrintAction) {
                buttons.push({
                    name: 'Print',
                    text: p.toPrintConfig.text,
                    index: 50,
                    icon: 'edit',
                    action: function () {
                        p.toPrintConfig.toPrintFn(p, g);
                    }
                }, {
                        name: 'Preview',
                        text: p.toPreviewConfig.text,
                        index: 60,
                        icon: 'edit',
                        action: function () {
                            p.toPreviewConfig.toPreviewFn(p, g);
                        }
                    }, {
                        name: 'Design',
                        text: p.toDesignConfig.text,
                        index: 70,
                        icon: 'edit',
                        action: function () {
                            p.toDesignConfig.toDesignFn(p, g);
                        }
                    });
            }
            if (p.isDelAction) {
                buttons.push({
                    name: 'Delete',
                    text: p.toDelConfig.text,
                    index: 40,
                    icon: 'delete',
                    action: function () {
                        p.toDelConfig.toDelFn(p, g);
                    }
                });
            }

            if (p.advSearchOptions.searchConfig && !p.leftLayout) {
                buttons.push({
                    name: 'advsearch',
                    text: "�߼�����",
                    index: 999,
                    icon: 'search',
                    action: function () {
                        g.createAdvSearchWin();
                    }
                });
            }
            // ��չ��ť
            if (p.buttonsEx) {
                buttons = buttons.concat(p.buttonsEx);
            }
            return buttons;
        },
        /**
         * �����߼���������
         */
        createAdvSearchWin: function () {
            var g = this, el = this.el, p = this.options;
            $("body").yxadvsearch({
                advSearchOptions: p.advSearchOptions,
                windowOptions: p.windowOptions,
                grid: g
            });
        },
        /**
         * ����������
         */
        _createToolBar: function () {
            var g = this, el = this.el, p = this.options;
            // set toolbar ��������ʼ
            // ������ǰtoolbar��title�ֿ���ģʽ�����������Ϊflase����Ӧ����ʾ�������ϵİ�ť
            if (p.isToolBar == false) {
                p.isAddAction = false;
                p.isViewAction = false;
                p.isEditAction = false;
                p.isDelAction = false;
                p.isAdvanceSearch = false;
            }
            if (p.isTitle || p.isToolBar) {
                g.tDiv.className = 'tDiv';
                g._createTitle();// ��������������ϲ� update by chengl

                g.toolBarDiv = document.createElement('div');
                g.toolBarDiv.className = 'tDiv2';
                g._createToolbarButtons();
                g._createToolbarCombos();

                $(g.tDiv).append(g.toolBarDiv);
                $(g.tDiv).append("<div style='clear:both'></div>");
                $(g.gDiv).prepend(g.tDiv);
                return true;
            }
        },
        /**
         * ������������ť
         */
        _createToolbarButtons: function () {
            var g = this, el = this.el, p = this.options;
            var buttons = g._getButtons();
            if (buttons) {
                // ͨ����ťindex��������
                buttons.sort(function (a, b) {
                    var i = a.index ? a.index : 999;
                    var j = b.index ? b.index : 999;
                    return i - j;
                });
                // ���������˵�
                buttons.push({
                    separator: true
                });
                if (p.isRightMenu && p.isOpButton) {
                    buttons.push({
                        name: 'op',
                        text: "���ݲ���",
                        title: '��ѡ��һ�����ݽ��в���',
                        index: 999,
                        icon: 'edit'
                    });
                }
                for (var i = 0, l = buttons.length; i < l; i++) {
                    var btn = buttons[i];
                    if (!btn.separator) {
                        if (btn.action) {
                            var action = btn.action;
                            btn.action = function (action) {
                                return function () {
                                    var row = g.getSelectedRow();
                                    var rowData, rows, rowIds;
                                    if (row) {
                                        rowData = row.data('data');
                                        rows = p.showcheckbox ? g.getCheckedRows() : g.getSelectedRow();
                                        rowIds = g.getAllCheckedRowIds();
                                    }
                                    action(rowData, rows, rowIds, g);
                                }
                            }(action);
                        }
                        var btnDiv = g.createButton(btn);
                        if (btn.items) {
                            for (var k = 0; k < btn.items.length; k++) {
                                var m = btn.items[k];
                                if (m.action) {
                                    var action = m.action;
                                    // ���¹���action
                                    m.action = (function (action) {
                                        return function () {
                                            var row = g.getSelectedRow();
                                            var rowData, rows, rowIds;
                                            if (row) {
                                                rowData = row.data('data');
                                                rows = g.getCheckedRows();
                                                if (rows.length == 0) {
                                                    rows = row;
                                                }
                                                rowIds = g
                                                    .getAllCheckedRowIds();
                                            }
                                            action(rowData, rows, rowIds, g);
                                        }
                                    })(action);
                                }
                                ;
                            }
                            btnDiv.yxmenu({
                                type: 'click',
                                width: p.menuWidth,
                                items: btn.items,
                                showMenuType: 'down'
                            });
                        }
                        $(g.toolBarDiv).append(btnDiv);
                        if ($.browser.msie && $.browser.version < 7.0) {
                            $(btnDiv).hover(function () {
                                $(this).addClass('fbOver');
                            }, function () {
                                $(this).removeClass('fbOver');
                            });
                        }

                    } else {
                        $(g.toolBarDiv).append(g._createToolbarLine());
                    }
                }

            }

        },
        /**
         * ����һ���������ָ���
         */
        _createToolbarLine: function () {
            return this.options.isOpButton == true ? $("<div class='btnseparator'></div>") : false;
        },
        /**
         * ����������ݹ�������
         */
        _createToolbarCombos: function () {
            var g = this, el = this.el, p = this.options, combos = p.comboEx;
            // ����ͼ����
            if (p.leftLayout == true) {
                g.$leftLayout = $('<div id="view" class="ui-layout-west"></div>');
                $("body").append(g.$leftLayout).yxlayout({
                    initClosed: p.leftLayoutInitClosed
                });
            }
            if (combos.length > 0) {
                if (p.leftLayout == true) {
                    g.createSystemViewTable();// ����ϵͳ��ͼ���
                }
                // ����һ���ָ���
                $(g.toolBarDiv).append(g._createToolbarLine());
                for (var i = 0, l = combos.length; i < l; i++) {
                    var combo = combos[i];
                    var select = $("<select class='selectauto' id='" + combo.key + "'>");
                    if (p.leftLayout == true) {
                        select.width("100%");
                    }
                    select.bind("change", function (key) {
                        return function () {
                            var v = $(this).val();
                            if (!$.woo.isEmpty(v)) {
                                p.param[key] = v;
                            } else {
                                delete p.param[key];
                            }
                            if (combo.clearExtParam) {
                                p.extParam = {};//ע�͵����,��������ʱ,���������͸߼�����һ����в�ѯ
                            }
                            p.newp = 1;
                            g.reload();
                        }
                    }(combo.key));
                    select.append($("<option value=''>").html("����"));

                    // ������������ֵ���룬���������ֵ�����
                    if (!$.woo.isEmpty(combo.datacode)) {
                        combo.data = [];
                        var datadictData = $.ajax({
                            type: 'POST',
                            url: "?model=system_datadict_datadict&action=getDataJsonByCodes",
                            data: {
                                codes: combo.datacode
                            },
                            async: false
                        }).responseText;
                        datadictData = eval("(" + datadictData + ")");
                        datadictData = datadictData[combo.datacode];
                        if (datadictData) {
                            for (var k = 0, kl = datadictData.length; k < kl; k++) {
                                var o = {
                                    value: datadictData[k].dataCode,
                                    text: datadictData[k].dataName
                                };
                                combo.data.push(o);
                            }
                        }
                    }
                    if (combo.type == 'workFlow') {
                        combo.data = [
                            {
                                text: '���ύ',
                                value: '���ύ'
                            },
                            {
                                text: '��������',
                                value: '��������'
                            },
                            {
                                text: '���',
                                value: '���'
                            },
                            {
                                text: '���',
                                value: '���'
                            }
                        ];
                    }
                    //��������
                    var options = "";

                    var key = combo.key;
                    if (p.customCode && p.customSearchParam.rightSearch) {
                        for (var j = 0, jl = combo.data.length; j < jl; j++) {
                            if (p.customSearchParam.rightSearch[key] == combo.data[j].value) {
                                options += "<option value='" + combo.data[j].value + "' selected>" + combo.data[j].text + "</option>";
                                p.param[key] = combo.data[j].value;
                            } else {
                                options += "<option value='" + combo.data[j].value + "'>" + combo.data[j].text + "</option>";
                            }
                        }
                    } else {
                        for (var j = 0, jl = combo.data.length; j < jl; j++) {
                            options += "<option value='" + combo.data[j].value + "'>" + combo.data[j].text + "</option>";
                        }
                    }

                    select.append(options);
                    if (combo.value) {
                        select.val(combo.value);
                        p.param[combo.key] = combo.value;
                    }
                    if (p.leftLayout == true) {
                        g.addSystemViewItem(combo.text, select);
                    } else {
                        $(g.toolBarDiv).append("&nbsp;" + combo.text + "��").append(select);
                    }
                }
            }

            if (p.leftLayout == true) {
                g.createCustomViewTable();// �����Զ�����ͼ���
            }
        },
        /**
         * ����ϵͳ��ͼ���
         */
        createSystemViewTable: function () {
            var g = this, el = this.el, p = this.options;
            g.$sytemViewTable = $('<table class="form_main_table"></table>');
            g.$sytemViewTable.append('<tr><td colspan="2"><div><span class="systemView">ϵͳ��ͼ</span></div></td></tr>');
            g.$leftLayout.append(g.$sytemViewTable);
        },
        /**
         * ����Զ�����ͼ���
         */
        createCustomViewTable: function () {
            var g = this, el = this.el, p = this.options;
            if (p.advSearchOptions && p.advSearchOptions.modelName) {
                g.$customViewTable = $('<table class="form_main_table" id="caseListWrap"></table>');
                g.$customViewTable
                    .append('<tr><td ><div><span class="systemView">�Զ�����ͼ</span></div></td><td><span id="addCustomViewBt" class="addBn" title="����Զ�����ͼ">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td></tr>');
                $.ajax({
                    url: '?model=system_adv_advcase&action=listByCurJson',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        modelName: p.advSearchOptions.modelName
                    },
                    success: function (data) {
                        for (var i = 0; i < data.length; i++) {
                            var dataItem = data[i];
                            g.addCustomViewItem(dataItem)
                        }
                    }
                });

                g.$leftLayout.append(g.$customViewTable);
                $("#addCustomViewBt").click(function () {
                    g.createAdvSearchWin();
                });
            }
        },
        /**
         * ����Զ�����ͼ
         */
        addCustomViewItem: function (dataItem) {
            var g = this, el = this.el, p = this.options;
            var $href = $("<a href='javascript:void(0)' title='"
                + dataItem.caseName + "'>" + dataItem.caseName + "</a>");
            $href.click(function (itemId) {
                return function () {
                    $(this).parents("table").find("tr").css("background-color",
                        "#ffffff");
                    $(this).parents("tr").css("background-color", "#CAD0E4");
                    // ��ȡ��ͼ����������
                    $.ajax({
                        url: '?model=system_adv_advcasedetail&action=listJson',
                        type: "POST",
                        dataType: 'json',
                        data: {
                            caseId: itemId
                        },
                        success: function (data) {
                            if (data.length > 0) {
                                g.advSearchArr = [];
                                for (var i = 0; i < data.length; i++) {
                                    var d = data[i];
                                    var searchItem = {
                                        logic: d.logic,
                                        searchField: d.searchField,
                                        compare: d.compare,
                                        value: d.value,
                                        leftK: d.leftK,
                                        rightK: d.rightK
                                    };
                                    g.advSearchArr.push(searchItem);
                                }
                                p.extParam.advArr = g.advSearchArr;
                                g.reload();
                            }
                        }
                    })
                }
            }(dataItem.id));
            var $td1 = $("<td  class='form_view_right divChangeLine' ></td>")
                .append($href);
            var $tr = $("<tr></tr>").append($td1);
            var $del = $('<span class="delBn" title="ɾ���Զ�����ͼ">&nbsp;&nbsp;&nbsp;&nbsp;</span>');
            var $edit = $('<span class="editBn" title="�༭�Զ�����ͼ">&nbsp;&nbsp;&nbsp;&nbsp;</span>');
            var $td2 = $('<td></td>').append($edit).append($del);
            $edit.click(function (itemId) {
                return function () {
                    g.createAdvSearchWin();
                    $("body").yxadvsearch("selectCustomItemToEdit", itemId);
                }
            }(dataItem.id));
            $del.click(function (itemId) {
                return function () {
                    if (confirm("ȷ��ɾ������ͼ?")) {
                        $.ajax({
                            url: '?model=system_adv_advcase&action=ajaxdeletes',
                            type: "POST",
                            data: {
                                id: itemId
                            },
                            success: function (msg) {
                                if (msg == 1) {
                                    $td2.parent().remove();
                                    alert("ɾ���ɹ�.");
                                }
                            }

                        });
                    }
                }
            }(dataItem.id));
            $tr.append($td2);
            g.$customViewTable.append($tr);
        },
        /**
         * ���ϵͳ��ͼ
         */
        addSystemViewItem: function (text, select) {
            var g = this, el = this.el, p = this.options;
            var $tr = $("<tr><td class='form_text_left'>" + text + "</td></tr>");
            var $td = $("<td class='form_view_right'></td>");
            $td.append(select);
            $tr.append($td);
            g.$sytemViewTable.append($tr);
        },
        /**
         * ��������Ҽ��˵�
         */
        createRightMenu: function (rows) {
            var g = this, el = this.el, p = this.options, t = this;
            var menus = [];
            g.menus = menus;
            if (p.isViewAction) {
                var viewShowFn = p.toViewConfig.showMenuFn;
                var trueFn = function () {
                    return true
                };
                menus.push({
                    text: '�鿴',
                    icon: 'view',
                    showMenuFn: viewShowFn ? viewShowFn : trueFn,
                    action: function () {
                        p.toViewConfig.toViewFn(p, g)
                    }
                });
            }
            if (p.isEditAction) {
                var editShowFn = p.toEditConfig.showMenuFn;
                menus.push({
                    text: '�༭',
                    icon: 'edit',
                    showMenuFn: editShowFn ? editShowFn : trueFn,
                    action: function () {
                        p.toEditConfig.toEditFn(p, g);
                    }
                });
            }
            if (p.isDelAction) {
                var delShowFn = p.toDelConfig.showMenuFn;
                menus.push({
                    text: 'ɾ��',
                    icon: 'delete',
                    showMenuFn: delShowFn ? delShowFn : trueFn,
                    action: function () {
                        p.toDelConfig.toDelFn(p, g);
                    }
                });
            }
            if (p.menusEx) {
                menus = menus.concat(p.menusEx);
            }

            for (var i = 0; i < menus.length; i++) {
                var menu = menus[i];
                if (menu.action) {
                    var action = menu.action;
                    // ���¹���action
                    menu.action = (function (action) {
                        return function () {
                            var row = g.getSelectedRow();
                            var rowData, rows, rowIds;
                            if (row) {
                                rowData = row.data('data');
                                rows = g.getCheckedRows();
                                if (rows.length == 0) {
                                    rows = row;
                                }
                                rowIds = g.getAllCheckedRowIds();
                            }
                            action(rowData, rows, rowIds, g);
                        }
                    })(action);
                }
                if (menu.showMenuFn) {
                    var showMenuFn = menu.showMenuFn;
                    menu.showMenuFn = (function (showMenuFn) {
                        return function (menuCmp) {// �õ��˵����
                            if (menuCmp.el) {
                                return showMenuFn(menuCmp.el.data('data'));
                            }
                        }
                    })(showMenuFn);
                }
            }
            if (p.isRightMenu) {
                rows.live('mousedown', function () {
                    // ������в˵��Ѵ������������ٴδ���
                    var isCreate = $(this).data('isCreate');
                    if (!isCreate) {
                        $(this).yxmenu({
                            type: 'rclick',
                            isBubble: true,// �Ƿ�����˵�ð��
                            width: p.menuWidth,
                            items: menus
                        });
                    }
                    $(this).data('isCreate', true);
                });
                var gridId = $(el).attr("id");
                $("#" + gridId + "-op").click(function (e) {
                    // ��ȡѡ����
                    var selectedRow = g.getSelectedRow();
                    if (selectedRow) {
                        var f = $(this).offset();
                        var w = $(this).width() / 2;
                        var h = $(this).height();
                        var pos = {
                            left: f.left + w,
                            top: f.top + h + 3
                        };
                        selectedRow.trigger('contextmenu', [pos]);
                        return false;
                    } else {
                        alert("������ѡ��һ������.");
                    }
                });
            }
        },
        /**
         * ��ȡ�߼���������
         */
        getAdvSearchArr: function () {
            var body = $("body");
            var render = body.yxadvsearch('getIsRender');
            if (render === true) {
                return body.yxadvsearch('getAdvSearchArr');
            }
            return false;
        }
    });
})(jQuery);