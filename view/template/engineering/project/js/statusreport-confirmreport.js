/**
 * ȷ����Ŀ״̬����
 */
function confirmReport(thisId,submitUserId){
	if(confirm('ȷ����Ŀ״̬������')){
		$.ajax({
		    type: "POST",
		    url: "?model=engineering_project_statusreport&action=confirmReport",
		    data: { 'id' : thisId , 'createId':submitUserId},
		    async: false,
		    success: function(data){
		   		if(data == 1){
		   			alert('ȷ�ϳɹ�');
		   			parent.show_page();
		   			closeFun();
				}else{
		   			alert('ȷ��ʧ��');
		   			closeFun();
				}
			}
		});
	}
}


//ҳ��鿴����
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