      $(function(){
  		//�칫���״̬
		$('select[name="leave[softSate]"] option').each(function() {
			if( $(this).val() == $("#softSateSelect").val() ){
				$(this).attr("selected","selected'");
			}
		});

		//�ù���ֹ
		$('select[name="leave[employmentEnd]"] option').each(function() {
			if( $(this).val() == $("#employmentEndSelect").val() ){
				$(this).attr("selected","selected'");
			}
		});

			// ��֤��Ϣ
			validate({
				"comfirmQuitDate" : {
					required : true
				},
				"salaryEndDate" : {
					required : true
				}
			});
       });
