$(function() {
	//tabs����
	var tt = $('#tt');
	var thisHeight = document.documentElement.clientHeight - 5;
	tt.tabs({
		height : thisHeight,
		plain : true,
		onSelect : function(title) {
			var tb = tt.tabs('getTab',title);
			var hc = tb.panel('options').headerCls;
			if(hc && hc != null) {
				tb.panel('options').thisUrl = hc; //����һ��ֵ����tabˢ��
				tb.panel('options').headerCls = ''; //���֮��Ų�������ˢ��
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