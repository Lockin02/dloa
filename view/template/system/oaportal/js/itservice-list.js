var news_detail = function(rowId) {
		 URL="news/show/show_news.php?NEWS_ID="+rowId;
 		 myleft=(screen.availWidth-500)/2;
 		 parent.window.open(URL,"read_work_plan","height=500,width=600,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");
}
//数据库没有表，暂时停工
$(function() {
	$("#itserviceGrid").yxgrid({
		model : 'system_oaportal_oaportlet',
		action : "getItserviceList",
		// 表单
		colModel : [{
			display : '',
			name : '',
			width : 250,
			process : function(v, row) {
				if (row.isnew) {
					v += '<IMG  style="position:absolute;zIndex:100;" src="images/new.gif"  border=0 >';
				}

				return "<a href='javascript:news_detail(\""+row.NEWS_ID+"\")'>" + v + "</a>";
			}
		}, {
			display : '',
			name : '',
			width : 150
		}],
		showcheckbox : false,
		usepager : false,
		isTitle : false,
		height : 'auto',
		isToolBar : false
	});
});