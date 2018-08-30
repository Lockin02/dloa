/**
 * 用户选择组件
 */
(function ($) {
    if (!$.window) {
        document.write("<link type='text/css' href='js/jquery/style/yxwindow.css' media='screen' rel='stylesheet'/>");
        document.write("<script language='javascript' src='js/jquery/jquery-ui.js'></script>");
        document.write("<script language='javascript' src='js/jquery/window/yxwindow.js'></script>");
    }

    $.woo.component.subclass('woo.yxselect_user', {
        _create: function () {
            var t = this, p = t.options, el = t.el;
            if (p.isShowButton) {
                // 清空按钮绑定
                t.$clearbutton = $("<span title='点击清空数据' class='clear-trigger'>&nbsp;</span>");
                t.$clearbutton.click(function () {
                    if (p.hiddenId) {
                        $("#" + p.hiddenId).val("");
                    }
                    if (p.isGetDept[0]) {
                        $("#" + p.isGetDept[1]).val("");
                        $("#" + p.isGetDept[2]).val("");
                    }
                    //人员选择添加 职位信息 update on 2012-6-11 by kuangzw
                    if (p.isGetJob[0]) {
                        $("#" + p.isGetJob[1]).val("");
                        $("#" + p.isGetJob[2]).val("");
                    }
                    $(el).val("");
                    $(el).trigger('clearReturn');// 清空后触发事件
                });
                $(el).after(t.$clearbutton);

                // 搜索按钮绑定
                t.$selectbutton = $("<span title='点击搜索数据' class='search-trigger'>&nbsp;</span>");
                t.$selectbutton.click(function () {
                    openUserWin();
                });
                $(el).after(t.$selectbutton);

                // 保存初始化宽度
                if (!t.initWidth) {
                    var w = $(el).css("width");
                    if (w && w != 'auto') {
                        t.initWidth = w.substr(0, w.length - 2);// 减去px
                    }
                }

                // 更改宽度
                if (t.initWidth) {
                    $(el).width(t.initWidth - t.$selectbutton.width()
                    - t.$clearbutton.width());
                }
            }
            var openUserWin = function () {
                var url = p.hostUrl + "?model=deptuser_user_user&action=selectuser&mode="
                    + p.mode + "&showDialog=1&isOnlyCurDept="
                    + p.isOnlyCurDept + "&deptIds=" + p.deptIds
                    + "&isDeptAddedUser=" + p.isDeptAddedUser
                    + "&isDeptSetUserRange=" + p.isDeptSetUserRange;
                var hiddenIdObj = $("#" + p.hiddenId);
                var hiddenIdVal = hiddenIdObj.val();
                if (p.hiddenId && hiddenIdVal != '') {
                    url += "&userVal=" + hiddenIdVal;
                }
                if (p.hiddenId && hiddenIdVal != ''
                    && p.isGetDept[0]) {
                    url += "&userVal=" + hiddenIdVal + "&deptId="
                    + $("#" + p.isGetDept[1]).val() + "&deptName="
                    + $("#" + p.isGetDept[2]).val();
                }
                if (p.isShowLeft) {
                    url += "&isShowLeft=" + p.isShowLeft;
                }
                if (p.formCode) {
                    url += "&formCode=" + p.formCode;
                }
                //人员选择添加 人员编号 update on 2012-6-6 by kuangzw
                if (p.userNo) {
                    url += "&userNo=" + p.userNo;
                }
                if (p.isGetJob[0]) {
                    url += "&isNeedJob=1";
                }

                //初始化时验证变量是否存在
                url += "&targetId=" + el.attr('id');

                try {
                    var width = 650;
                    var height = 540;

                    if ($(window).height() <= 650) {
                        url += "&mini=1";
                        height = 300;
                    }

                    $.window({
                        title: '人员选择',
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
            };

            $(el).bind('dblclick', function () {
                openUserWin();
            });
        },
        options: {
            hostUrl: '', // 附加地址
            mode: 'single', // 选择模式 :single 单选 check 多选
            isShowButton: true,
            isOnlyCurDept: false, // 是否只能选择当前登录人所在部门
            deptIds: "",// 只能选择设置的部门id，逗号隔开 如1,33,34
            isGetDept: [false, "", ""],
            // 选择用户时是否带出用户所在的部门,数组第二个参数为页面的部门隐藏ID，第三个参数为页面的部门名称
            formCode: '',// 表单编码，传入则使用此编码作为保存选中的人作为常用人员
            userNo: "",//人员选择添加 人员编号 update on 2012-6-6 by kuangzw
            isGetJob: [false, "", ""], //人员选择添加 人员职位信息 update on 2012-6-11 by kuangzw
            isShowLeft: false, //是否显示离职人员
            isDeptAddedUser: false, // 是否往部门追加人员
            isDeptSetUserRange: false // 部门是否设置人员选择范围
        },
        /**
         * 移除组件
         */
        remove: function () {
            var t = this, p = t.options, el = t.el;
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
                    if (p.isGetDept[0]) {
                        $("#" + p.isGetDept[1]).val(returnValue.deptId);
                        $("#" + p.isGetDept[2]).val(returnValue.deptName);
                    }
                    //人员选择添加 职位信息 update on 2012-6-11 by kuangzw
                    if (p.isGetJob[0]) {
                        $("#" + p.isGetJob[1]).val(returnValue.jobId);
                        $("#" + p.isGetJob[2]).val(returnValue.jobName);
                    }
                    $("#" + p.hiddenId).val(returnValue.val);
                }
                $(el).val(returnValue.text);
                $(el).attr('title', returnValue.text);
                $(el).trigger('select', [returnValue]);// 选择后触发事件

                //人员选择添加 人员编号 update on 2012-6-6 by kuangzw
                if (p.userNo != "") {
                    $("#" + p.userNo).val(returnValue.userNo);
                }
            }
        }
    });
})(jQuery);