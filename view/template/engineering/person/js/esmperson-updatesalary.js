$(document).ready(function() {
	//�󶨵���¼�
	$("#confirmBtn").click(function(){
		if(confirm('�������ݻ���Ҫ���ѽϳ�ʱ�䣬ȷ�����д˲�����')){
			$("#showMsg").text('���ݸ�����......');
			//��ʾ����ͼ
			var imgObj = $("#imgLoading");
			imgObj.show();

			//���ð�ť
			var btnObj = $(this);
			btnObj.attr('disabled',true);

			setTimeout(function(){
				//���ø��¹���
				$.ajax({
				    type: "POST",
				    url: "?model=engineering_person_esmperson&action=updateSalary",
				    data : {"thisYear" : $("#year").val(),"thisMonth" : $("#month").val()},
				    async: false,
				    success: function(data){
				    	if(data=='0'){
							$("#showMsg").text('�����ݸ���');
				    	}else{
				    		if(data == "1"){
								$("#showMsg").text('���³ɹ�');
				    		}else{
				    			$("#showMsg").text(data);
				    		}
				    	}
						imgObj.hide();
						btnObj.attr('disabled',false);
					}
				});
			},200);
		}
	});
});
