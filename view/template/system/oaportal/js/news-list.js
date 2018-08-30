var news_detail = function(rowId) {
		 URL="general/news/show/show_news.php?NEWS_ID="+rowId;
 		 myleft=(screen.availWidth-500)/2;
 		 parent.window.open(URL,"read_work_plan","height=500,width=600,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");
}
$(function() {
	$("#newsGrid").yxgrid({
		model : 'system_oaportal_oaportlet',
		action : "getNewsList",
		// 表单
		colModel : [{
			display : '新闻标题',
			name : 'SUBJECT',
			width : 150,
			process : function(v, row) {
				if (row.isnew) {
					v += '<IMG  style="position:absolute;zIndex:100;" src="images/new.gif"  border=0 >';
				}
				return "<a href='javascript:news_detail(\""+row.NEWS_ID+"\")'>" + v + "</a>";
			}
		}, {
			display : '发布时间',
			name : 'NEWS_TIME',
			width : 150
		}],
		showcheckbox : false,
		usepager : false,
		isTitle : false,
		height : 'auto',
		isToolBar : false
	});
});