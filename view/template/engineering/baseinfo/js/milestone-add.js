$(document).ready(function() {


	//Tab表头
	topTabShow(arrayTop,"rdMilestone");

	//添加鼠标经过行颜色改变
	rowsColorChange();
/*
	$("#addButton").bind("click",function(){
		var altStr = $(this).attr("alt");
		altStr += "&projectType2=" + $("#projectType").val()+"&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=700";
		showThickboxWin( altStr );
	});
*/
});

/*
 * 自动刷新页面
 */
 	function show_page(page){
		this.location="?model=engineering_baseinfo_rdmilestoneinfo&action=milestonelist"
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

 /*
  *	添加里程碑窗口
  */
  function addMilestone(){
  	var projectType = $("#projectType").val();
  	if(projectType != "typeoption"){
	  	var url = '?model=engineering_baseinfo_rdmilestoneinfo&action=toaddmilestone&projectType='
	  			+ projectType
	  			+ '&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=700'
	//	alert(url);
	  	showThickboxWin(url);
  	}else{
  		alert("请先选择“项目类型”后才能添加里程碑");
  	}
  }

// 	function addMilestone(){
// 		var url = '?model=engineering_baseinfo_rdmilestoneinfo&action=toaddmilestone'
// 				+ '&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=700'
// 		showThickboxWin(url);
// 	}


  //导出到Excel
  function export2Excel(){
	this.location = "?model=engineering_baseinfo_rdmilestoneinfo&action=export2Excel";
  }


//	$('#projectType').val('{projectType}');//选中值类型
	//根据不同中的选择下拉内容，跳转到不同的列表页面
  	function selectType(v){
		if(v != 'typeoption'){
			this.location="?model=engineering_baseinfo_rdmilestoneinfo&action=milestonefilterlist&projectType="+v;
		}else{
			this.location="?model=engineering_baseinfo_rdmilestoneinfo&action=milestonelist";
		}
	}


	//“保存并新建”按钮的功能，保存后并刷新当前页面，使其可以继续添加新的数据。
	function saveAndNew(){
		this.show_page();
	}



/*
	function showprojectTypeCode(){
		var projectType=$('#projectType').val();
		showThickboxWin('?model=engineering_baseinfo_rdmilestoneinfo&action=toaddmilestone&projectType='+projectType+'&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=700')
	}
*/
