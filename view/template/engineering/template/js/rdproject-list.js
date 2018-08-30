
//添加模板
function addTemplate(){
	//var projectType = $("#projectType").val();
	var url = "?model=engineering_template_rdprjtemplate&action=toAddTemplate"
			//+"&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=740"
			+"TB_iframe=true&modal=false&height=200&width=740"
	showThickboxWin(url);
}


//添加里程碑点
function addMilestone(parentId){
	//alert(temp);
	var url = "?model=engineering_baseinfo_rdmilestoneinfo&action=toaddmilestone&parentId="
			+parentId
    	    + "&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=740"
	//alert(url);
	showThickboxWin(url);
}

//设置模板-类型关联
function setTemplateType(){
	var url = "?model=engineering_template_rdprjtemplate&action=toSetTemplate"
			+ "&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=740"
	showThickboxWin(url);
}

//设置类型，跳转到类型设置页面，对项目类型进行定义及修改
function setProjectType(){
	this.location = "?model=engineering_projecttype_projecttype&action=showProjectType"
}

//发布模板
function releaseTemplate(){
	this.location = "?model=engineering_template_rdprjtemplate&action=releaseTemplate";
}

/*
 * 页面自动刷新
 */
function show_page(page){
//	var pageId =
	this.location="?model=engineering_template_rdprjtemplate&action=toviewtemplate";
}


/*
 * 选择项目类型触发事件
 */
 function selectType(v){
 	var param = {
 		'projectType' : v
 	};
 	myTree._searchGrid(param);
 }
