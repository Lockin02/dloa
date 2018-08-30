//������������
function mulSelectSet(thisObj){
	thisObj.next().find("input").each(function(i,n){
		if($(this).attr('class') == 'combo-text validatebox-text'){
			$("#"+ thisObj.attr('id') + "Hidden").val(this.value);
		}
	});
}

//��ֵ��ѡֵ -- ��ʼ����ֵ
function mulSelectInit(thisObj){
	//��ʼ����Ӧ����
	var objVal = $("#"+ thisObj.attr('id') + "Hidden").val();
	if(objVal != "" ){
		thisObj.combobox("setValues",objVal.split(','));
	}
}

//��ʼ�����鲹�䷽ʽ��Ϣ
function initMainBusiness(){
		//��ȡ���鲹�䷽ʽ
		var mainBusinessArr = $('#mainBusinessHidden').val().split(",");
		var str;
		//���鲹�䷽ʽ��Ⱦ
		var mainBusinessObj = $('#mainBusiness');
		mainBusinessObj.combobox({
			url:'index1.php?model=system_datadict_datadict&action=ajaxGetForEasyUI&parentCode=GYSZYYW',
			multiple:true,
			valueField:'text',
            textField:'text',
			editable : false,
	        formatter: function(obj){
	        	//�ж� ���û�г�ʼ�������У���ѡ��
	        	if(mainBusinessArr.indexOf(obj.text) == -1){
	        		str = "<input type='checkbox' id='mainBusiness_"+ obj.text +"' value='"+ obj.text +"'/> " + obj.text;
	        	}else{
	        		str = "<input type='checkbox' id='mainBusiness_"+ obj.text +"' value='"+ obj.text +"' checked='checked'/> " + obj.text;
	        	}
				return str;
	        },
			onSelect : function(obj){
				//checkbox��ֵ
				$("#mainBusiness_" + obj.text).attr('checked',true);
				//���ö����µ�ѡ����
				mulSelectSet(mainBusinessObj);
			},
			onUnselect : function(obj){
				//checkbox��ֵ
				$("#mainBusiness_" + obj.text).attr('checked',false);
				//����������
				mulSelectSet(mainBusinessObj);
			}
		});

		//�ͻ����ͳ�ʼ����ֵ
		mulSelectInit(mainBusinessObj);
}

//��ʼ���ó�����������Ϣ
function initAdeptNetType(){
		var adeptNetTypeArr = $('#adeptNetTypeHidden').val().split(",");
		var str;
		var adeptNetTypeObj = $('#adeptNetType');
		adeptNetTypeObj.combobox({
			url:'index1.php?model=system_datadict_datadict&action=ajaxGetForEasyUI&parentCode=WBWLLX',
			multiple:true,
			valueField:'text',
            textField:'text',
			editable : false,
	        formatter: function(obj){
	        	//�ж� ���û�г�ʼ�������У���ѡ��
	        	if(adeptNetTypeArr.indexOf(obj.text) == -1){
	        		str = "<input type='checkbox' id='adeptNetType_"+ obj.text +"' value='"+ obj.text +"'/> " + obj.text;
	        	}else{
	        		str = "<input type='checkbox' id='adeptNetType_"+ obj.text +"' value='"+ obj.text +"' checked='checked'/> " + obj.text;
	        	}
				return str;
	        },
			onSelect : function(obj){
				//checkbox��ֵ
				$("#adeptNetType_" + obj.text).attr('checked',true);
				//���ö����µ�ѡ����
				mulSelectSet(adeptNetTypeObj);
			},
			onUnselect : function(obj){
				//checkbox��ֵ
				$("#adeptNetType_" + obj.text).attr('checked',false);
				//����������
				mulSelectSet(adeptNetTypeObj);
			}
		});

		//�ͻ����ͳ�ʼ����ֵ
		mulSelectInit(adeptNetTypeObj);
}

//��ʼ���ó������豸��Ϣ
function initAdeptDeviceType(){
		var adeptDeviceArr = $('#adeptDeviceHidden').val().split(",");
		var str;
		var adeptDeviceObj = $('#adeptDevice');
		adeptDeviceObj.combobox({
			url:'index1.php?model=system_datadict_datadict&action=ajaxGetForEasyUI&parentCode=WBCJSB',
			multiple:true,
			valueField:'text',
            textField:'text',
			editable : false,
	        formatter: function(obj){
	        	//�ж� ���û�г�ʼ�������У���ѡ��
	        	if(adeptDeviceArr.indexOf(obj.text) == -1){
	        		str = "<input type='checkbox' id='adeptDevice_"+ obj.text +"' value='"+ obj.text +"'/> " + obj.text;
	        	}else{
	        		str = "<input type='checkbox' id='adeptDevice_"+ obj.text +"' value='"+ obj.text +"' checked='checked'/> " + obj.text;
	        	}
				return str;
	        },
			onSelect : function(obj){
				//checkbox��ֵ
				$("#adeptDevice_" + obj.text).attr('checked',true);
				//���ö����µ�ѡ����
				mulSelectSet(adeptDeviceObj);
			},
			onUnselect : function(obj){
				//checkbox��ֵ
				$("#adeptDevice_" + obj.text).attr('checked',false);
				//����������
				mulSelectSet(adeptDeviceObj);
			}
		});

		//�ͻ����ͳ�ʼ����ֵ
		mulSelectInit(adeptDeviceObj);
}

//ҵ��ֲ���Ϣ
function initBusinessDistribute(){
		var businessDistributeArr = $('#businessDistributeHidden').val().split(",");
		var str;
		var businessDistributeObj = $('#businessDistribute');
		businessDistributeObj.combobox({
			url:'index1.php?model=system_procity_province&action=listJson&dir=ASC',
			multiple:true,
			valueField:'provinceName',
            textField:'provinceName',
			editable : false,
	        formatter: function(obj){
	        	//�ж� ���û�г�ʼ�������У���ѡ��
	        	if(businessDistributeArr.indexOf(obj.provinceName) == -1){
	        		str = "<input type='checkbox' id='businessDistribute_"+ obj.provinceName +"' value='"+ obj.provinceName +"'/> " + obj.provinceName;
	        	}else{
	        		str = "<input type='checkbox' id='businessDistribute_"+ obj.provinceName +"' value='"+ obj.provinceName +"' checked='checked'/> " + obj.provinceName;
	        	}
				return str;
	        },
			onSelect : function(obj){
				//checkbox��ֵ
				$("#businessDistribute_" + obj.provinceName).attr('checked',true);
				//���ö����µ�ѡ����
				mulSelectSet(businessDistributeObj);
			},
			onUnselect : function(obj){
				//checkbox��ֵ
				$("#businessDistribute_" + obj.provinceName).attr('checked',false);
				//����������
				mulSelectSet(businessDistributeObj);
			}
		});
		//�ͻ����ͳ�ʼ����ֵ
		mulSelectInit(businessDistributeObj);
}

  // ����������
	function check_all() {
		var totalNum = 0;
		var cmps = $("#hrListInfo").yxeditgrid("getCmpByCol", "totalNum");
		cmps.each(function() {
			totalNum = accAdd(totalNum, $(this).val(),0);
		});
		$("#employeesNum").val(totalNum);

	}
	// ����ռ��
	function checkProportion() {
		var cmps = $("#hrListInfo").yxeditgrid("getCmpByCol", "proportion");
		var employeesNum=$("#employeesNum").val();
		cmps.each(function() {
			if(employeesNum>0){
				var grid = $(this).data('grid');// ������
				var rowNum=$(this).data('rowNum');
				var totalNum=grid.getCmpByRowAndCol(rowNum, 'totalNum').val();
				var proportion = accDiv(totalNum,employeesNum,'4');
				$(this).val(accMul(proportion,100,2));
			}else{
				$(this).val('0.00');
			}
		});
	}

		// ���㹤������ռ��
	function checkWorkProportion() {
		var cmps = $("#workListInfo").yxeditgrid("getCmpByCol", "proportion");
		var employeesNum=$("#companySize").val();
		cmps.each(function() {
			if(employeesNum>0){
				var grid = $(this).data('grid');// ������
				var rowNum=$(this).data('rowNum');
				var personNum=grid.getCmpByRowAndCol(rowNum, 'personNum').val();
				var proportion = accDiv(personNum,employeesNum,'4');
				$(this).val(accMul(proportion,100,2));
			}else{
				$(this).val('0.00');
			}
		});
	}

	  // ���㹤������������
	function checkWorkAll() {
		var totalNum = 0;
		var cmps = $("#workListInfo").yxeditgrid("getCmpByCol", "personNum");
		cmps.each(function() {
			totalNum = accAdd(totalNum, $(this).val(),0);
		});
		$("#companySize").val(totalNum);

	}