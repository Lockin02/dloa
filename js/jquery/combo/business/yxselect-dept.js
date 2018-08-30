/**
 * 下拉客户表格组件
 */
(function ($) {
    if (!$.window) {
        document.write("<link type='text/css' href='js/jquery/style/yxwindow.css' media='screen' rel='stylesheet'/>");
        document.write("<script language='javascript' src='js/jquery/jquery-ui.js'></script>");
        document.write("<script language='javascript' src='js/jquery/window/yxwindow.js'></script>");
    }

    $.woo.component.subclass('woo.yxselect_dept', {

        _create: function () {
            var t = this, p = t.options, el = t.el;
            t.$selectbutton = $("<span title='点击搜索数据' class='search-trigger'>&nbsp;</span>");
            t.$clearbutton = $("<span title='点击清空数据' class='clear-trigger'>&nbsp;</span>");

            function openDeptWin() {
                var url = "?model=deptuser_dept_dept&action=selectdept&mode="
                    + p.mode + "&showDialog=1";
                if (p.hiddenId && $("#" + p.hiddenId).val() != '') {
                    url += "&deptVal=" + $("#" + p.hiddenId).val();
                }
                if (p.deptFilter != "") {
                    url += "&deptFilter=" + p.deptFilter;
                }
                if (p.unDeptFilter != "") {
                    url += "&unDeptFilter=" + p.unDeptFilter;
                }

                if (p.unSltDeptFilter != "") {
                    url += "&unSltDeptFilter=" + p.unSltDeptFilter;
                }

                if (p.disableDeptLevel != "") {
                    url += "&disableDeptLevel=" + p.disableDeptLevel;
                }
                // 获取当前对象id
                url += "&targetId=" + el.attr('id');

                try {
                    var width = 650;
                    var height = 540;

                    if ($(window).height() <= 650) {
                        url += "&mini=1";
                        height = 300;
                    }

                    $.window({
                        title: '部门选择',
                        url: url,
                        width: width,
                        height: height,
                        bookmarkable: false,
                        maximizable: false,
                        z: 20000
                    });
                } catch (e) {
                    console.log(e);
                }
            }

            t.$selectbutton.click(function () {
                openDeptWin();
            });
            t.$clearbutton.click(function () {
                if (p.hiddenId) {
                    $("#" + p.hiddenId).val("");
                }
                $(el).val("").trigger('clearReturn');//清空后触发事件
            });
            $(el).after(t.$clearbutton).after(t.$selectbutton).bind('dblclick', function () {
                openDeptWin();
            });
            // 保存初始化宽度
            if (!t.initWidth) {
                var w = $(el).css("width");
                if (w && w != 'auto') {
                    t.initWidth = w.substr(0, w.length - 2);// 减去px
                }
            }
            // 更改宽度
            if (t.initWidth) {
                $(el).width(t.initWidth - t.$selectbutton.width() - t.$clearbutton.width());
            }
        },
        options: {
            deptFilter: '', // 部门限制，传入部门Id串，以逗号隔开
            unDeptFilter: '', // 部门限制，传入部门Id串，以逗号隔开
            unSltDeptFilter: '',// 不允许选的部门限制，传入部门Id串，以逗号隔开
            mode: 'single', // 选择模式 :single 单选 check 多选
            disableDeptLevel: '' // 部门级别限制，传入级别串，以逗号隔开
        },
        /**
         * 移除组件
         */
        remove: function () {
            var t = this, el = t.el;
            // 设置回初始化宽度
            if (t.initWidth) {
                $(el).width(t.initWidth + "px");
            }
            t.$clearbutton.remove();
            t.$selectbutton.remove();
            $(el).unbind();
        },
        /**
         * 设置数据 - 此接口给不兼容showModalDialog的浏览器使用
         */
        setData: function (returnValue) {
            var t = this, p = t.options, el = t.el;
            if (returnValue) {
                if (p.hiddenId) {
                    $("#" + p.hiddenId).val(returnValue.val);
                }
                // 选择后触发事件
                $(el).val(returnValue.text).attr('title', returnValue.text).trigger('selectReturn', [returnValue]);
            }
        }
    });
})(jQuery);