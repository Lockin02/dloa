/**
 * �����ͻ�������
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
            t.$selectbutton = $("<span title='�����������' class='search-trigger'>&nbsp;</span>");
            t.$clearbutton = $("<span title='����������' class='clear-trigger'>&nbsp;</span>");

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
                // ��ȡ��ǰ����id
                url += "&targetId=" + el.attr('id');

                try {
                    var width = 650;
                    var height = 540;

                    if ($(window).height() <= 650) {
                        url += "&mini=1";
                        height = 300;
                    }

                    $.window({
                        title: '����ѡ��',
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
                $(el).val("").trigger('clearReturn');//��պ󴥷��¼�
            });
            $(el).after(t.$clearbutton).after(t.$selectbutton).bind('dblclick', function () {
                openDeptWin();
            });
            // �����ʼ�����
            if (!t.initWidth) {
                var w = $(el).css("width");
                if (w && w != 'auto') {
                    t.initWidth = w.substr(0, w.length - 2);// ��ȥpx
                }
            }
            // ���Ŀ��
            if (t.initWidth) {
                $(el).width(t.initWidth - t.$selectbutton.width() - t.$clearbutton.width());
            }
        },
        options: {
            deptFilter: '', // �������ƣ����벿��Id�����Զ��Ÿ���
            unDeptFilter: '', // �������ƣ����벿��Id�����Զ��Ÿ���
            unSltDeptFilter: '',// ������ѡ�Ĳ������ƣ����벿��Id�����Զ��Ÿ���
            mode: 'single', // ѡ��ģʽ :single ��ѡ check ��ѡ
            disableDeptLevel: '' // ���ż������ƣ����뼶�𴮣��Զ��Ÿ���
        },
        /**
         * �Ƴ����
         */
        remove: function () {
            var t = this, el = t.el;
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
                    $("#" + p.hiddenId).val(returnValue.val);
                }
                // ѡ��󴥷��¼�
                $(el).val(returnValue.text).attr('title', returnValue.text).trigger('selectReturn', [returnValue]);
            }
        }
    });
})(jQuery);