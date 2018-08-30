/**
 * 确认项目状态报告
 */
function confirmReport(thisId,submitUserId){
	if(confirm('确认项目状态报告吗？')){
		$.ajax({
		    type: "POST",
		    url: "?model=engineering_project_statusreport&action=confirmReport",
		    data: { 'id' : thisId , 'createId':submitUserId},
		    async: false,
		    success: function(data){
		   		if(data == 1){
		   			alert('确认成功');
		   			parent.show_page();
		   			closeFun();
				}else{
		   			alert('确认失败');
		   			closeFun();
				}
			}
		});
	}
}


//页面查看方法
function viewStatusreportDetail(id) {
	var skey = "";
        $.ajax({
		    type: "POST",
		    url: "?model=finance_invpurchase_invpurchase&action=md5RowAjax",
		    data: { "id" : id },
		    async: false,
		    success: function(data){
		   	   skey = data;
			}
		});
	showModalWin("?model=engineering_project_esmproject&action=viewTab&id="
		+ id + "&skey=" + skey
		+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=900");

}