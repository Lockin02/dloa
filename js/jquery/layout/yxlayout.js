(function($) {
	$.woo.component.subclass('woo.yxlayout', {
        options : {
            style : true,// Ӧ��Ĭ����ʽ
            tip : "��ʾ/���ز˵���",
            closeTip : "��",// pane�ر�ʱ��������ƶ����߿��ϰ�ť�ϣ���ʾ����ʾ��
            openTip : "����",// pane��ʱ��������ƶ����߿��ϰ�ť�ϣ���ʾ����ʾ��
            initClosed : false // ��ʼ��layoutʱ�Ƿ�Ĭ�Ϲر�
        },
        // ��ʼ�����
        _create : function() {
            p = this.options;
            $(this.el).layout({
                applyDefaultStyles : p.style,// Ӧ��Ĭ����ʽ
                sliderTip : p.tip,
                togglerTip_closed : p.closeTip,
                togglerTip_open : p.openTip,
                resizable : false,
                initClosed  : p.initClosed
            });
            var layoutObj = $(".ui-layout-west");
            if (layoutObj) {
                layoutObj.css({
                    "overflow-x" : "hidden"
                });
            }
        }
    });
})(jQuery);
