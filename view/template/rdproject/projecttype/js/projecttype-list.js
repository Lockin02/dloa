
//跳转到增加项目类型页面
function toAddProjectType(){
	var url = "?model=rdproject_projecttype_projecttype&action=toAddProjectType"
			+ "&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=730"
	showThickboxWin(url);
}

//自动刷新页面
function show_page(page){
		this.location="?model=rdproject_projecttype_projecttype&action=showProjectType"
}


$(document).ready(function(){
	//奇偶行颜色变换效果
	rowsColorChange();


});