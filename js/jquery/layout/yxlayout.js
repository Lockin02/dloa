(function($) {
	$.woo.component.subclass('woo.yxlayout', {
        options : {
            style : true,// 应用默认样式
            tip : "显示/隐藏菜单栏",
            closeTip : "打开",// pane关闭时，当鼠标移动到边框上按钮上，显示的提示语
            openTip : "隐藏",// pane打开时，当鼠标移动到边框上按钮上，显示的提示语
            initClosed : false // 初始化layout时是否默认关闭
        },
        // 初始化组件
        _create : function() {
            p = this.options;
            $(this.el).layout({
                applyDefaultStyles : p.style,// 应用默认样式
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
