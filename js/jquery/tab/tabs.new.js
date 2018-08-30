$(function() {
	//tabs处理
	var tt = $('#tt');
	var thisHeight = document.documentElement.clientHeight - 5;
	tt.tabs({
		height : thisHeight,
		plain : true,
		onSelect : function(title) {
			var tb = tt.tabs('getTab',title);
			var hc = tb.panel('options').headerCls;
			if(hc && hc != null) {
				tb.panel('options').thisUrl = hc; //保存一个值用于tab刷新
				tb.panel('options').headerCls = ''; //清空之后才不会重新刷新
				tt.tabs('update', {
					tab: tb,
					options:{
						content:'<iframe fit="true" name="' + hc + '" scrolling="auto" frameborder="0"  src="' + hc + '" style="width:100%;height:100%;"></iframe>'
					}
				});
			}
		}
	});
});