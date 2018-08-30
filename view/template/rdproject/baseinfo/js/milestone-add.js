//$(document).ready(function() {
//
//
//	//Tab表头
//	topTabShow(arrayTop,"rdMilestone");
//
//	//添加鼠标经过行颜色改变
//	rowsColorChange();
///*
//	$("#addButton").bind("click",function(){
//		var altStr = $(this).attr("alt");
//		altStr += "&projectType2=" + $("#projectType").val()+"&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=700";
//		showThickboxWin( altStr );
//	});
//*/
//});

/*
 * 自动刷新页面
 */
 	function show_page(page){
		this.location="?model=rdproject_baseinfo_rdmilestoneinfo&action=milestonelist"
	}

/*
 * 选择项目类型触发事件
 */
// function selectType(v){
// 	var param = {
// 		'projectType' : v
// 	};
// 	myTree._searchGrid(param);
// }

 /*
  *	添加里程碑窗口
  */
  function addMilestone(){
//  	var projectType = $("#projectType").val();
//  	if(projectType != "typeoption"){
	  	var url = '?model=rdproject_baseinfo_rdmilestoneinfo&action=toaddmilestone'
	  			+ '&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=700'
	//	alert(url);
	  	showThickboxWin(url);
//  	}else{
//  		alert("请先选择“项目类型”后才能添加里程碑");
//  	}
  }

// 	function addMilestone(){
// 		var url = '?model=rdproject_baseinfo_rdmilestoneinfo&action=toaddmilestone'
// 				+ '&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=700'
// 		showThickboxWin(url);
// 	}


  //导出到Excel
  function export2Excel(){
	this.location = "?model=rdproject_baseinfo_rdmilestoneinfo&action=export2Excel";
  }


//	$('#projectType').val('{projectType}');//选中值类型
	//根据不同中的选择下拉内容，跳转到不同的列表页面
  	function selectType(v){
		if(v != 'typeoption'){
			this.location="?model=rdproject_baseinfo_rdmilestoneinfo&action=milestonefilterlist&projectType="+v;
		}else{
			this.location="?model=rdproject_baseinfo_rdmilestoneinfo&action=milestonelist";
		}
	}


	//“保存并新建”按钮的功能，保存后并刷新当前页面，使其可以继续添加新的数据。
	function saveAndNew(){
		this.show_page();
	}

  //删除里程碑
	function deleteMilestone(objectName) {
		var checkIDS = checkOne();
		var ids = checkIDS.substring(0, checkIDS.length - 1);
		if (checkIDS.length == 0) {
			alert("提示: 请选择一条信息.");
			return false;
		}
		var msg = "确认要删除!";
		if (window.confirm(msg)) {
			$.ajax({
				type : "POST",
				url : "?model=rdproject_baseinfo_rdmilestoneinfo&action=deleteMilestone1&id=" + ids,
				success : function(msg) {
					if( msg == 1 ){
						alert('删除成功！');
						show_page();
					}else{
						alert( '删除失败' );
					}
				}
			});
		}
	}

/*
	function showprojectTypeCode(){
		var projectType=$('#projectType').val();
		showThickboxWin('?model=rdproject_baseinfo_rdmilestoneinfo&action=toaddmilestone&projectType='+projectType+'&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=700')
	}
*/

	//选择不同的项目类型，有不同的前置里程碑
$(document).ready(function() {
		$("#projectType").bind("change",function(){
			var projectType=$("#projectType").val();
			if(projectType!=""){
				$.post(
					"?model=rdproject_baseinfo_rdmilestoneinfo&action=getFrontMilestone",
					{projectType:projectType},
					function(data){
						$("#frontNumb").html("");
						$("#frontNumb").append(data);
					}
				);
			}
		});
	});
