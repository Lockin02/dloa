/**
 * �û�ѡ�����
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
                // ��հ�ť��
                t.$clearbutton = $("<span title='����������' class='clear-trigger'>&nbsp;</span>");
                t.$clearbutton.click(function () {
                    if (p.hiddenId) {
                        $("#" + p.hiddenId).val("");
                    }
                    if (p.isGetDept[0]) {
                        $("#" + p.isGetDept[1]).val("");
                        $("#" + p.isGetDept[2]).val("");
                    }
                    //��Աѡ����� ְλ��Ϣ update on 2012-6-11 by kuangzw
                    if (p.isGetJob[0]) {
                        $("#" + p.isGetJob[1]).val("");
                        $("#" + p.isGetJob[2]).val("");
                    }
                    $(el).val("");
                    $(el).trigger('clearReturn');// ��պ󴥷��¼�
                });
                $(el).after(t.$clearbutton);

                // ������ť��
                t.$selectbutton = $("<span title='�����������' class='search-trigger'>&nbsp;</span>");
                t.$selectbutton.click(function () {
                    openUserWin();
                });
                $(el).after(t.$selectbutton);

                // �����ʼ�����
                if (!t.initWidth) {
                    var w = $(el).css("width");
                    if (w && w != 'auto') {
                        t.initWidth = w.substr(0, w.length - 2);// ��ȥpx
                    }
                }

                // ���Ŀ��
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
                //��Աѡ����� ��Ա��� update on 2012-6-6 by kuangzw
                if (p.userNo) {
                    url += "&userNo=" + p.userNo;
                }
                if (p.isGetJob[0]) {
                    url += "&isNeedJob=1";
                }

                //��ʼ��ʱ��֤�����Ƿ����
                url += "&targetId=" + el.attr('id');

                try {
                    var width = 650;
                    var height = 540;

                    if ($(window).height() <= 650) {
                        url += "&mini=1";
                        height = 300;
                    }

                    $.window({
                        title: '��Աѡ��',
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
            hostUrl: '', // ���ӵ�ַ
            mode: 'single', // ѡ��ģʽ :single ��ѡ check ��ѡ
            isShowButton: true,
            isOnlyCurDept: false, // �Ƿ�ֻ��ѡ��ǰ��¼�����ڲ���
            deptIds: "",// ֻ��ѡ�����õĲ���id�����Ÿ��� ��1,33,34
            isGetDept: [false, "", ""],
            // ѡ���û�ʱ�Ƿ�����û����ڵĲ���,����ڶ�������Ϊҳ��Ĳ�������ID������������Ϊҳ��Ĳ�������
            formCode: '',// �����룬������ʹ�ô˱�����Ϊ����ѡ�е�����Ϊ������Ա
            userNo: "",//��Աѡ����� ��Ա��� update on 2012-6-6 by kuangzw
            isGetJob: [false, "", ""], //��Աѡ����� ��Աְλ��Ϣ update on 2012-6-11 by kuangzw
            isShowLeft: false, //�Ƿ���ʾ��ְ��Ա
            isDeptAddedUser: false, // �Ƿ�������׷����Ա
            isDeptSetUserRange: false // �����Ƿ�������Աѡ��Χ
        },
        /**
         * �Ƴ����
         */
        remove: function () {
            var t = this, p = t.options, el = t.el;
            // ���ûس�ʼ�����
            if (t.initWidth) {
                $(el).width(t.initWidth + "px");
            }
            t.$clearbutton.remove();
            t.$selectbutton.remove();
            $(el).unbind();
        },
        /**
         * �������� - �˽ӿڸ�������showModalDialog�������ʹ��
         */
        setData: function (returnValue) {
            var t = this, p = t.options, el = t.el;
            if (returnValue) {
                if (p.hiddenId) {
                    if (p.isGetDept[0]) {
                        $("#" + p.isGetDept[1]).val(returnValue.deptId);
                        $("#" + p.isGetDept[2]).val(returnValue.deptName);
                    }
                    //��Աѡ����� ְλ��Ϣ update on 2012-6-11 by kuangzw
                    if (p.isGetJob[0]) {
                        $("#" + p.isGetJob[1]).val(returnValue.jobId);
                        $("#" + p.isGetJob[2]).val(returnValue.jobName);
                    }
                    $("#" + p.hiddenId).val(returnValue.val);
                }
                $(el).val(returnValue.text);
                $(el).attr('title', returnValue.text);
                $(el).trigger('select', [returnValue]);// ѡ��󴥷��¼�

                //��Աѡ����� ��Ա��� update on 2012-6-6 by kuangzw
                if (p.userNo != "") {
                    $("#" + p.userNo).val(returnValue.userNo);
                }
            }
        }
    });
})(jQuery);