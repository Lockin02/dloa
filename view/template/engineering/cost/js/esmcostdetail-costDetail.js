$(function() {	
//��ʼ����Ĭ����ʾδ������statusΪ1
	init(1);
});
//ˢ��
var show_page = function(page) {
	init(1);
	self.opener.show_page(1);
};
//��ʼ������
function init(status){
	var projectId = $("#projectId").val();
	//ʵ������ϸ���ñ���
    var costDetailObj = $("#costDetail");
    $.ajax({
    	type : "POST",
    	url : "?model=engineering_cost_esmcostdetail&action=listHtml",
    	data: {"projectId" : projectId,"status" : status},
    	success : function(data) {
			if (data) {
				costDetailObj.empty().append(data);
				$("#status").val(status);
				//��ʼ�����
				formateMoney();
			}
		}
    });
}
//checkboxȫѡ
function selectAllCheck(){
	$("input.subCheck").each(function(){
		this.checked=$("#mainCheck").attr("checked");
	}); 
 }
//���ɱ�����
function toEsmExpenseAdd(){
	var dateArr = [];//ѡ��ı���������
	$("input.subCheck").each(function(){
		 if(this.checked){
			 dateArr.push($(this).parents(".tr_even").find(".executionDate").html());
		 }
	 }); 
	//����������ҳ��
	if(dateArr.length > 0){
		showModalWin("?model=finance_expense_expense&action=toEsmExpenseAdd&days="
			+ dateArr.toString()
			+ "&projectId="
			+ $("#projectId").val()
		);
	}else{
		alert('����ѡ���¼');
	}
}
//�ı䱨��״̬
function changeStatus(){
	init($("#status").val());
}