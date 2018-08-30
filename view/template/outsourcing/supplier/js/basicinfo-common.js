//隐藏区域设置
function mulSelectSet(thisObj){
	thisObj.next().find("input").each(function(i,n){
		if($(this).attr('class') == 'combo-text validatebox-text'){
			$("#"+ thisObj.attr('id') + "Hidden").val(this.value);
		}
	});
}

//设值多选值 -- 初始化赋值
function mulSelectInit(thisObj){
	//初始化对应内容
	var objVal = $("#"+ thisObj.attr('id') + "Hidden").val();
	if(objVal != "" ){
		thisObj.combobox("setValues",objVal.split(','));
	}
}

//初始化建议补充方式信息
function initMainBusiness(){
		//获取建议补充方式
		var mainBusinessArr = $('#mainBusinessHidden').val().split(",");
		var str;
		//建议补充方式渲染
		var mainBusinessObj = $('#mainBusiness');
		mainBusinessObj.combobox({
			url:'index1.php?model=system_datadict_datadict&action=ajaxGetForEasyUI&parentCode=GYSZYYW',
			multiple:true,
			valueField:'text',
            textField:'text',
			editable : false,
	        formatter: function(obj){
	        	//判断 如果没有初始化数组中，则选中
	        	if(mainBusinessArr.indexOf(obj.text) == -1){
	        		str = "<input type='checkbox' id='mainBusiness_"+ obj.text +"' value='"+ obj.text +"'/> " + obj.text;
	        	}else{
	        		str = "<input type='checkbox' id='mainBusiness_"+ obj.text +"' value='"+ obj.text +"' checked='checked'/> " + obj.text;
	        	}
				return str;
	        },
			onSelect : function(obj){
				//checkbox设值
				$("#mainBusiness_" + obj.text).attr('checked',true);
				//设置对象下的选中项
				mulSelectSet(mainBusinessObj);
			},
			onUnselect : function(obj){
				//checkbox设值
				$("#mainBusiness_" + obj.text).attr('checked',false);
				//设置隐藏域
				mulSelectSet(mainBusinessObj);
			}
		});

		//客户类型初始化赋值
		mulSelectInit(mainBusinessObj);
}

//初始化擅长网络类型信息
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
	        	//判断 如果没有初始化数组中，则选中
	        	if(adeptNetTypeArr.indexOf(obj.text) == -1){
	        		str = "<input type='checkbox' id='adeptNetType_"+ obj.text +"' value='"+ obj.text +"'/> " + obj.text;
	        	}else{
	        		str = "<input type='checkbox' id='adeptNetType_"+ obj.text +"' value='"+ obj.text +"' checked='checked'/> " + obj.text;
	        	}
				return str;
	        },
			onSelect : function(obj){
				//checkbox设值
				$("#adeptNetType_" + obj.text).attr('checked',true);
				//设置对象下的选中项
				mulSelectSet(adeptNetTypeObj);
			},
			onUnselect : function(obj){
				//checkbox设值
				$("#adeptNetType_" + obj.text).attr('checked',false);
				//设置隐藏域
				mulSelectSet(adeptNetTypeObj);
			}
		});

		//客户类型初始化赋值
		mulSelectInit(adeptNetTypeObj);
}

//初始化擅长厂家设备信息
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
	        	//判断 如果没有初始化数组中，则选中
	        	if(adeptDeviceArr.indexOf(obj.text) == -1){
	        		str = "<input type='checkbox' id='adeptDevice_"+ obj.text +"' value='"+ obj.text +"'/> " + obj.text;
	        	}else{
	        		str = "<input type='checkbox' id='adeptDevice_"+ obj.text +"' value='"+ obj.text +"' checked='checked'/> " + obj.text;
	        	}
				return str;
	        },
			onSelect : function(obj){
				//checkbox设值
				$("#adeptDevice_" + obj.text).attr('checked',true);
				//设置对象下的选中项
				mulSelectSet(adeptDeviceObj);
			},
			onUnselect : function(obj){
				//checkbox设值
				$("#adeptDevice_" + obj.text).attr('checked',false);
				//设置隐藏域
				mulSelectSet(adeptDeviceObj);
			}
		});

		//客户类型初始化赋值
		mulSelectInit(adeptDeviceObj);
}

//业务分布信息
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
	        	//判断 如果没有初始化数组中，则选中
	        	if(businessDistributeArr.indexOf(obj.provinceName) == -1){
	        		str = "<input type='checkbox' id='businessDistribute_"+ obj.provinceName +"' value='"+ obj.provinceName +"'/> " + obj.provinceName;
	        	}else{
	        		str = "<input type='checkbox' id='businessDistribute_"+ obj.provinceName +"' value='"+ obj.provinceName +"' checked='checked'/> " + obj.provinceName;
	        	}
				return str;
	        },
			onSelect : function(obj){
				//checkbox设值
				$("#businessDistribute_" + obj.provinceName).attr('checked',true);
				//设置对象下的选中项
				mulSelectSet(businessDistributeObj);
			},
			onUnselect : function(obj){
				//checkbox设值
				$("#businessDistribute_" + obj.provinceName).attr('checked',false);
				//设置隐藏域
				mulSelectSet(businessDistributeObj);
			}
		});
		//客户类型初始化赋值
		mulSelectInit(businessDistributeObj);
}

  // 计算总人数
	function check_all() {
		var totalNum = 0;
		var cmps = $("#hrListInfo").yxeditgrid("getCmpByCol", "totalNum");
		cmps.each(function() {
			totalNum = accAdd(totalNum, $(this).val(),0);
		});
		$("#employeesNum").val(totalNum);

	}
	// 计算占比
	function checkProportion() {
		var cmps = $("#hrListInfo").yxeditgrid("getCmpByCol", "proportion");
		var employeesNum=$("#employeesNum").val();
		cmps.each(function() {
			if(employeesNum>0){
				var grid = $(this).data('grid');// 表格组件
				var rowNum=$(this).data('rowNum');
				var totalNum=grid.getCmpByRowAndCol(rowNum, 'totalNum').val();
				var proportion = accDiv(totalNum,employeesNum,'4');
				$(this).val(accMul(proportion,100,2));
			}else{
				$(this).val('0.00');
			}
		});
	}

		// 计算工作经验占比
	function checkWorkProportion() {
		var cmps = $("#workListInfo").yxeditgrid("getCmpByCol", "proportion");
		var employeesNum=$("#companySize").val();
		cmps.each(function() {
			if(employeesNum>0){
				var grid = $(this).data('grid');// 表格组件
				var rowNum=$(this).data('rowNum');
				var personNum=grid.getCmpByRowAndCol(rowNum, 'personNum').val();
				var proportion = accDiv(personNum,employeesNum,'4');
				$(this).val(accMul(proportion,100,2));
			}else{
				$(this).val('0.00');
			}
		});
	}

	  // 计算工作经验总人数
	function checkWorkAll() {
		var totalNum = 0;
		var cmps = $("#workListInfo").yxeditgrid("getCmpByCol", "personNum");
		cmps.each(function() {
			totalNum = accAdd(totalNum, $(this).val(),0);
		});
		$("#companySize").val(totalNum);

	}