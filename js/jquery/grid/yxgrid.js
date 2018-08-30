// / <reference path="../intellisense/jquery-1.2.6-vsdoc-cn.js" />
// / <reference path="../lib/blackbird.js" />
/**
 * 高级表格组件 继承基本表格:加入工具栏，右键菜单
 */
(function ($) {
    $.woo.yxsgrid.subclass('woo.yxgrid', {
        options: {
            // ------ 扩展属性开始-------
            /**
             * 扩展按钮
             */
            buttonsEx: [],
            /**
             * 菜单扩展属性
             */
            menusEx: [],
            /**
             * 数据过滤下拉选择扩展
             */
            comboEx: [],
            /**
             * 是否显示右键菜单，如果为flase，则右键菜单失效
             *
             * @type Boolean
             */
            isRightMenu: true,
            /**
             * 右键菜单默认宽度
             */
            menuWidth: 120,
            /**
             * 是否显示工具栏
             *
             * @type Boolean
             */
            isToolBar: true,
            /**
             * 是否显示打印相关按钮/菜单
             *
             * @type Boolean
             */
            isPrintAction: false,
            /**
             * 是否显示添加按钮/菜单
             *
             * @type Boolean
             */
            isAddAction: true,
            /**
             * 是否显示查看按钮/菜单
             *
             * @type Boolean
             */
            isViewAction: true,
            /**
             * 是否显示修改按钮/菜单
             *
             * @type Boolean
             */
            isEditAction: true,
            /**
             * 是否显示删除按钮/菜单
             *
             * @type Boolean
             */
            isDelAction: true,
            /**
             * 是否显示高级搜索按钮（待扩展）
             *
             * @type Boolean
             */
            isAdvanceSearch: true,
            /**
             * 是否显示导出excel按钮
             *
             * @type Boolean
             */
            isToExcel: false,
            /**
             * 是否显示数据操作按钮
             */
            isOpButton: true,
            /**
             * 是否显示左边查询框
             */
            leftLayout: false,
            /**
             * 左边查询框是否默认关闭 - 只有当leftLayout启用时才会生效
             */
            leftLayoutInitClosed: true,
            /**
             * 表单默认宽度
             */
            formWidth: 800,
            /**
             * 表单默认宽度
             */
            formHeight: 400,
            /**
             * 新增表单属性配置
             */
            toAddConfig: {
                text: '新增',
                /**
                 * 默认点击新增按钮触发事件
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
                 * 新增表单调用的后台方法
                 */
                action: 'toAdd',
                /**
                 * 追加的url
                 */
                plusUrl: '',
                /**
                 * 新增表单默认宽度
                 */
                formWidth: 0,
                /**
                 * 新增表单默认高度
                 */
                formHeight: 0
            },
            /**
             * 查看表单属性配置
             */
            toViewConfig: {
                text: '查看',
                /**
                 * 默认点击查看按钮触发事件
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
                        alert('请选择一行记录！');
                    }
                },
                /**
                 * 加载表单默认调用的后台方法
                 */
                action: 'init',
                /**
                 * 追加的url
                 */
                plusUrl: '',
                /**
                 * 查看表单默认宽度
                 */
                formWidth: 0,
                /**
                 * 查看表单默认高度
                 */
                formHeight: 0
            },
            /**
             * 编辑表单属性配置
             */
            toEditConfig: {
                text: '编辑',
                /**
                 * 默认点击编辑按钮触发事件
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
                        alert('请选择一行记录！');
                    }
                },
                /**
                 * 加载表单默认调用的后台方法
                 */
                action: 'init',
                /**
                 * 追加的url
                 */
                plusUrl: '',
                /**
                 * 编辑表单默认宽度
                 */
                formWidth: 0,
                /**
                 * 编辑表单默认高度
                 */
                formHeight: 0
            },
            /**
             * 删除属性配置
             */
            toDelConfig: {
                text: '删除',
                /**
                 * 默认点击删除按钮触发事件
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
                        if (window.confirm("确认要删除?")) {
                            $.ajax({
                                type: "POST",
                                url: "?model=" + p.model + "&action="
                                    + p.toDelConfig.action
                                    + p.toDelConfig.plusUrl,
                                data: {
                                    id: g.getCheckedRowIds()
                                        .toString(),
                                    skey: key
                                    // 转换成以,隔开方式
                                },
                                success: function (msg) {
                                    if (msg == 1) {
                                        if (window.show_page != "undefined") {
                                            show_page();
                                        } else {
                                            g.reload();
                                        }
                                        alert('删除成功！');
                                    } else if (msg == 2) {
                                        alert('非法访问');
                                    } else if (msg != '' && msg != 0) {
                                        alert(msg);
                                    } else {
                                        if (p.toDelConfig.errorMsg != '') {
                                            alert(p.toDelConfig.errorMsg);
                                        } else {
                                            alert('删除失败，该对象可能已经被引用!');
                                        }
                                    }
                                }
                            });
                        }
                    } else {
                        alert('请选择一行记录！');
                    }
                },
                /**
                 * 删除默认调用的后台方法
                 */
                action: 'ajaxdeletes',
                /**
                 * 追加的url
                 */
                plusUrl: '',
                errorMsg: ''
            },
            /**
             * 打印表单属性配置
             */
            toPrintConfig: {
                text: '打印',
                /**
                 * 默认点击打印按钮触发事件
                 */
                toPrintFn: function (p) {
                    javascript: prn_print();
                }
            },
            /**
             * 打印预览表单属性配置
             */
            toPreviewConfig: {
                text: '打印预览',
                /**
                 * 默认点击打印预览按钮触发事件
                 */
                toPreviewFn: function (p) {
                    javascript: prn_preview();
                }
            },
            /**
             * 打印预览表单属性配置
             */
            toDesignConfig: {
                text: '页面设置',
                /**
                 * 默认点击页面设置按钮触发事件
                 */
                toDesignFn: function (p) {
                    javascript: prn_design();
                }
            },
            /**
             * 根据审批状态显示菜单/按钮条件函数
             */
            showFn: function (row) {
                if (!row.ExaStatus) {
                    return true;
                } else if (row.ExaStatus == '打回' || row.ExaStatus == '未提交') {
                    return true;
                }
                return false;
            },
            /**
             * 高级搜索设置
             */
            advSearchOptions: {

            }

            // ------ 扩展属性结束-------
        },
        /**
         * 初始化组件
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
            // title : "视图",
            // content : $("#view").html()
            // });
            // w.minimize();
        },
        /**
         * 动态添加按钮
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
                    text: "高级搜索",
                    index: 999,
                    icon: 'search',
                    action: function () {
                        g.createAdvSearchWin();
                    }
                });
            }
            // 扩展按钮
            if (p.buttonsEx) {
                buttons = buttons.concat(p.buttonsEx);
            }
            return buttons;
        },
        /**
         * 创建高级搜索窗口
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
         * 创建工具栏
         */
        _createToolBar: function () {
            var g = this, el = this.el, p = this.options;
            // set toolbar 工具栏开始
            // 兼容以前toolbar与title分开的模式，如果此属性为flase，不应该显示工具栏上的按钮
            if (p.isToolBar == false) {
                p.isAddAction = false;
                p.isViewAction = false;
                p.isEditAction = false;
                p.isDelAction = false;
                p.isAdvanceSearch = false;
            }
            if (p.isTitle || p.isToolBar) {
                g.tDiv.className = 'tDiv';
                g._createTitle();// 工具栏与标题栏合并 update by chengl

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
         * 创建工具栏按钮
         */
        _createToolbarButtons: function () {
            var g = this, el = this.el, p = this.options;
            var buttons = g._getButtons();
            if (buttons) {
                // 通过按钮index进行排序
                buttons.sort(function (a, b) {
                    var i = a.index ? a.index : 999;
                    var j = b.index ? b.index : 999;
                    return i - j;
                });
                // 创建操作菜单
                buttons.push({
                    separator: true
                });
                if (p.isRightMenu && p.isOpButton) {
                    buttons.push({
                        name: 'op',
                        text: "数据操作",
                        title: '请选择一条数据进行操作',
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
                                    // 重新构造action
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
         * 创建一个工具栏分割线
         */
        _createToolbarLine: function () {
            return this.options.isOpButton == true ? $("<div class='btnseparator'></div>") : false;
        },
        /**
         * 创建表格数据过滤下拉
         */
        _createToolbarCombos: function () {
            var g = this, el = this.el, p = this.options, combos = p.comboEx;
            // 新视图代码
            if (p.leftLayout == true) {
                g.$leftLayout = $('<div id="view" class="ui-layout-west"></div>');
                $("body").append(g.$leftLayout).yxlayout({
                    initClosed: p.leftLayoutInitClosed
                });
            }
            if (combos.length > 0) {
                if (p.leftLayout == true) {
                    g.createSystemViewTable();// 创建系统视图表格
                }
                // 创建一条分割线
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
                                p.extParam = {};//注释掉这段,快速搜索时,搜索条件和高级搜索一起进行查询
                            }
                            p.newp = 1;
                            g.reload();
                        }
                    }(combo.key));
                    select.append($("<option value=''>").html("所有"));

                    // 如果传入数据字典编码，则用数据字典数据
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
                                text: '待提交',
                                value: '待提交'
                            },
                            {
                                text: '部门审批',
                                value: '部门审批'
                            },
                            {
                                text: '完成',
                                value: '完成'
                            },
                            {
                                text: '打回',
                                value: '打回'
                            }
                        ];
                    }
                    //快速搜索
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
                        $(g.toolBarDiv).append("&nbsp;" + combo.text + "：").append(select);
                    }
                }
            }

            if (p.leftLayout == true) {
                g.createCustomViewTable();// 创建自定义视图表格
            }
        },
        /**
         * 创建系统视图表格
         */
        createSystemViewTable: function () {
            var g = this, el = this.el, p = this.options;
            g.$sytemViewTable = $('<table class="form_main_table"></table>');
            g.$sytemViewTable.append('<tr><td colspan="2"><div><span class="systemView">系统视图</span></div></td></tr>');
            g.$leftLayout.append(g.$sytemViewTable);
        },
        /**
         * 添加自定义视图表格
         */
        createCustomViewTable: function () {
            var g = this, el = this.el, p = this.options;
            if (p.advSearchOptions && p.advSearchOptions.modelName) {
                g.$customViewTable = $('<table class="form_main_table" id="caseListWrap"></table>');
                g.$customViewTable
                    .append('<tr><td ><div><span class="systemView">自定义视图</span></div></td><td><span id="addCustomViewBt" class="addBn" title="添加自定义视图">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td></tr>');
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
         * 添加自定义视图
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
                    // 获取视图的搜索条件
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
            var $del = $('<span class="delBn" title="删除自定义视图">&nbsp;&nbsp;&nbsp;&nbsp;</span>');
            var $edit = $('<span class="editBn" title="编辑自定义视图">&nbsp;&nbsp;&nbsp;&nbsp;</span>');
            var $td2 = $('<td></td>').append($edit).append($del);
            $edit.click(function (itemId) {
                return function () {
                    g.createAdvSearchWin();
                    $("body").yxadvsearch("selectCustomItemToEdit", itemId);
                }
            }(dataItem.id));
            $del.click(function (itemId) {
                return function () {
                    if (confirm("确认删除此视图?")) {
                        $.ajax({
                            url: '?model=system_adv_advcase&action=ajaxdeletes',
                            type: "POST",
                            data: {
                                id: itemId
                            },
                            success: function (msg) {
                                if (msg == 1) {
                                    $td2.parent().remove();
                                    alert("删除成功.");
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
         * 添加系统视图
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
         * 创建表格右键菜单
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
                    text: '查看',
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
                    text: '编辑',
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
                    text: '删除',
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
                    // 重新构造action
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
                        return function (menuCmp) {// 拿到菜单组件
                            if (menuCmp.el) {
                                return showMenuFn(menuCmp.el.data('data'));
                            }
                        }
                    })(showMenuFn);
                }
            }
            if (p.isRightMenu) {
                rows.live('mousedown', function () {
                    // 如果改行菜单已创建，则无需再次创建
                    var isCreate = $(this).data('isCreate');
                    if (!isCreate) {
                        $(this).yxmenu({
                            type: 'rclick',
                            isBubble: true,// 是否允许菜单冒泡
                            width: p.menuWidth,
                            items: menus
                        });
                    }
                    $(this).data('isCreate', true);
                });
                var gridId = $(el).attr("id");
                $("#" + gridId + "-op").click(function (e) {
                    // 获取选中行
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
                        alert("请至少选中一条数据.");
                    }
                });
            }
        },
        /**
         * 获取高级搜索条件
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