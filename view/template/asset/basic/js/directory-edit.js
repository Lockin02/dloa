$(function(){

	/**
	* ���Ψһ����֤
	*/

	var url = "?model=asset_basic_directory&action=checkRepeat";
	if ($("#id").val()) {
				url += "&id=" + $("#id").val();
			}
	$("#code").ajaxCheck({
						url : url,
						alertText : "* �ñ���Ѵ���",
						alertTextOk : "* �ñ�ſ���"
					});

	$("#name").ajaxCheck({
						url : url,
						alertText : "* �������Ѵ���",
						alertTextOk : "* �����ֿ���"
					});
	/**
	 * ��֤��Ϣ
	 */
	validate( {"limitYears" : {
			custom : ['onlyNumber']
		},"salvage" : {
			custom : ['percentageNum']
		}
	});
	//�޸�ʱ����ѡ�ĵ�ѡ��ť������
	var isDepr=$("#isDeprHidden").val();
	$("input[type=radio]").each(function(){
		if(isDepr==$(this).val()){
			$(this).attr("checked","checked");
		}
	});

});
