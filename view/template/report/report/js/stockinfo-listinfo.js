$(function(){
	    //���ز���������ϵĲ��ְ�ť��˵��������RemoveToolbarControl����ָ��Ҫ���ص���
    //�����UpdateToolbar����������ʾ��RemoveToolbarControl�еĲ���ָ��Ҫ���صİ�ť��
    //�˵����Ӧ�ĳ���ֵ��������ö�� GRToolControlType �Ķ��塣
    //���ش�ӡ�����ð�ť
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
    //���ص����������ʼ���ť
    ReportViewer.RemoveToolbarControl(6);

    //���ص���HTM�˵���
    ReportViewer.RemoveToolbarControl(52);

    //��������ʾ
    ReportViewer.UpdateToolbar();
});


//��������
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
//����
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