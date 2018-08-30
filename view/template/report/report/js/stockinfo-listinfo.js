$(function(){
	    //隐藏插件工具栏上的部分按钮与菜单项。首先用RemoveToolbarControl方法指定要隐藏的项
    //最后用UpdateToolbar方法更新显示。RemoveToolbarControl中的参数指定要隐藏的按钮与
    //菜单项，对应的常数值请查帮助中枚举 GRToolControlType 的定义。
    //隐藏打印机设置按钮
      ReportViewer.RemoveToolbarControl(2);
      ReportViewer.RemoveToolbarControl(3);
      ReportViewer.RemoveToolbarControl(4);
      ReportViewer.RemoveToolbarControl(5);
      ReportViewer.RemoveToolbarControl(40);
      ReportViewer.RemoveToolbarControl(41);
      ReportViewer.RemoveToolbarControl(50);
      ReportViewer.RemoveToolbarControl(51);
      ReportViewer.RemoveToolbarControl(60);
      ReportViewer.RemoveToolbarControl(61);
    //隐藏导出并发送邮件按钮
    ReportViewer.RemoveToolbarControl(6);

    //隐藏导出HTM菜单项
    ReportViewer.RemoveToolbarControl(52);

    //最后更新显示
    ReportViewer.UpdateToolbar();
});


//搜索方法
function searchBtn(){
	window.open("?model=report_report_stockinfo&action=listinfoSearch"
		        + "&budgetTypeName="  + $("#budgetTypeName").val()
				+ "&budgetTypeId="  + $("#budgetTypeId").val()
				+ "&brand="  + $("#brand").val()
				+ "&equName="  + $("#equName").val()
				+ "&equId="  + $("#equId").val()
				+ "&netWork="  + $("#netWork").val()
				+ "&software="  + $("#software").val()
				+ "&isStop="  + $("#isStop").val()
				+ "&dataType="  + $("#dataType").val()
	,'newwindow1','height=500,width=800');
}
//重置
function resetBtn(){
	this.location = "?model=report_report_stockinfo&action=reportView"
		+"&budgetTypeName="+budgetTypeName
		+"&budgetTypeId="+budgetTypeId
		+"&brand="+brand
		+"&equName="+equName
		+"&equId="+equId
		+"&netWork="+netWork
		+"&software="+software
		+"&isStop="+isStop
		+ "&dataType="  + $("#dataType").val()
}