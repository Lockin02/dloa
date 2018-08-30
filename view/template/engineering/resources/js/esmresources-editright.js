$(document).ready(function(){

	//��ʼ��ʡ�ݳ�����Ϣ
	initCity();

	//��ѡ���´�
	$("#officeName").yxcombogrid_office({
		hiddenId : 'officeId',
		gridOptions : {
			showcheckbox : false
		}
	});

	//��ѡ��Ŀ����
	$("#managerName").yxselect_user({
		hiddenId : 'managerId',
		mode : 'check',
		isGetDept : [true, "depId", "depName"],
		formCode : 'esmManagerEdit'
	});

	//��ظ����˳�ʼ��
	//��������
	$("#areaManager").yxselect_user({
		hiddenId : 'areaManagerId',
		formCode : 'esmAreaManager'
	});
	//��������
	$("#techManager").yxselect_user({
		hiddenId : 'techManagerId',
		formCode : 'esmTechManager'
	});
	//���۸�����
	$("#salesman").yxselect_user({
		hiddenId : 'salesmanId',
		formCode : 'esmSalesman'
	});
	//�з�������
	$("#rdUser").yxselect_user({
		hiddenId : 'rdUserId',
		formCode : 'esmRdUser'
	});

	//����������Ⱦ
	$("#toolType").yxcombogrid_datadict({
		height : 250,
		valueCol : 'dataName',
		hiddenId : 'toolTypeHidden',
		gridOptions : {
			isTitle : true,
			param : {"parentCode":"GCGJLX"},
			showcheckbox : true,
			event : {
				'row_dblclick' : function(e, row, data){

				}
			},
			// ��������
			searchitems : [{
				display : '����',
				name : 'dataName'
			}]
		}
	});

	/**
	 * ��֤��Ϣ
	 */
	validate({
		"projectName" : {
			required : true,
			length : [0,100]
		}
	});

	//�����Ŀû����Ŀ���ԣ�����������Ŀ����
	var attributeHiddenObj = $("#attributeHidden");
	if(attributeHiddenObj.length == 1){
		if(attributeHiddenObj.val() == ""){
			var selectStr = '<select class="select" name="esmproject[attribute]" id="attribute"></select>';

			$("#attributeShow").html(selectStr);

			//��ȡ�����ֵ���Ϣ
			var responseText = $.ajax({
				url : 'index1.php?model=system_datadict_datadict&action=ajaxGetForEasyUI',
				type : "POST",
				data : { "parentCode" : "GCXMSS", "expand1" :"1"},
				async : false
			}).responseText;

			var dataArr = eval("(" + responseText + ")");
			var optionsStr = "<option value=''></option>";
			for (var i = 0, l = dataArr.length; i < l; i++) {
				optionsStr += "<option title='" + dataArr[i].text
						+ "' value='" + dataArr[i].id + "'>" + dataArr[i].text
						+ "</option>";
			}
			$("#attribute").append(optionsStr);
		}
	}
});