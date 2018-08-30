$(document).ready(function() {
	$("#ZK").yxeditgrid({
		isAdd : false,
		isAddOneRow : false,
		url : '?model=engineering_resources_taskdetail&action=listJson',
		param : {
			'taskId' : $("#id").val(),
			'taskType' : 'ZK'
		},
		objName : 'task[ZK]',
		colModel : [{
			display : '设备id',
			name : 'proResourceId',
			type : 'hidden'
		},{
			display : '设备id',
			name : 'resourceId',
			type : 'hidden'
		}, {
			display : '设备编码',
			name : 'resourceCode',
			type : 'hidden'
		}, {
			display : '分类id',
			name : 'resourceTypeId',
			type : 'hidden'
		}, {
			display : '设备分类',
			name : 'resourceTypeName',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '设备名称',
			name : 'resourceName',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '负责人ID',
			name : 'areaPrincipalId',
			tclass : 'txtshort',
			type : 'hidden'
		}, {
			display : '单位',
			name : 'unit',
			tclass : 'txtshort',
			type : 'hidden'
		}, {
			display : '预计借用日期',
			name : 'planBeginDate',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '预计归还日期',
			name : 'planEndDate',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '使用天数',
			name : 'useDays',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '设备折价',
			name : 'price',
			type : 'statictext',
			isSubmit : true,
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			display : '预计成本',
			name : 'amount',
			type : 'statictext',
			isSubmit : true,
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			display : '任务id',
			name : 'activityId',
			type : 'hidden'
		}, {
			display : '任务名称',
			name : 'activityName',
			type : 'hidden'
		}, {
			display : '项目id',
			name : 'projectId',
			type : 'hidden'
		}, {
			display : '项目名称',
			name : 'projectName',
			type : 'hidden'
		}, {
			display : '项目编号',
			name : 'projectCode',
			type : 'hidden'
		}, {
			display : '是否接收',
			name : 'isRe',
			type : 'hidden'
		}, {
			display : '数量',
			name : 'number',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '库存地',
			name : 'area',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '负责人',
			name : 'areaPrincipal',
			type : 'statictext',
			isSubmit : true
		}, {
			display : 'rowNum',
			name : 'rowNum',
			type : 'hidden'
		}, {
			display : '是否接收',
			name : 'isRe',
			type : 'statictext',
			isSubmit : true,
			process : function(v){
				if(v=='0'){
				   return "×";
				}else if(v=='1'){
				   return "√";
				}else{
				   return "";
				}
			}
		}],
		event : {
			'removeRow' : function(e, rowNum, rowData){
				if(rowData.rowNum == '' || typeof(rowData.rowNum) == "undefined" ){
					resetRow(rowData)
				}else{
				    var beforeStr = "DCJ_cmp";
					//获取现有数量
					var nowNumber= $("#"+ beforeStr + "_number" + rowData.rowNum ).val();
					//计算应反写数量
					var handleNumber = accAdd(nowNumber,rowData.number);
					//反写数量
					$("#"+ beforeStr + "_number" + rowData.rowNum ).val(handleNumber);
					handleRemoveRow(rowData.rowNum,handleNumber)
				}
			},
			'reloadData' : function(e){
				var thisGrid = $("#ZK");
				var isRnbn = thisGrid.yxeditgrid("getCmpByCol", "isRe");
			  isRnbn.each(function(i,n) {
                   if(this.value == '1'){
                   	 var tr = $(this).parent().parent();
                   	 var btn = tr.find("td span[class='removeBn']")
                   	 $(btn).hide();
                   }
			  });
			}
		}
	})

	//待申购/生产设备
	$("#DSG").yxeditgrid({
		objName : 'task[DSG]',
		isAdd : false,
		isAddOneRow : false,
		url : '?model=engineering_resources_taskdetail&action=listJson',
		param : {
			'taskId' : $("#id").val(),
			'taskType' : 'DSG'
		},
		colModel : [{
			display : '设备id',
			name : 'proResourceId',
			type : 'hidden'
		},{
			display : '设备id',
			name : 'resourceId',
			type : 'hidden'
		}, {
			display : '设备编码',
			name : 'resourceCode',
			type : 'hidden'
		}, {
			display : '分类id',
			name : 'resourceTypeId',
			type : 'hidden'
		}, {
			display : '设备分类',
			name : 'resourceTypeName',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '设备名称',
			name : 'resourceName',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '负责人ID',
			name : 'areaPrincipalId',
			tclass : 'txtshort',
			type : 'hidden'
		}, {
			display : '单位',
			name : 'unit',
			tclass : 'txtshort',
			type : 'hidden'
		}, {
			display : '预计借用日期',
			name : 'planBeginDate',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '预计归还日期',
			name : 'planEndDate',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '使用天数',
			name : 'useDays',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '设备折价',
			name : 'price',
			type : 'statictext',
			isSubmit : true,
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			display : '预计成本',
			name : 'amount',
			type : 'statictext',
			isSubmit : true,
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			display : '任务id',
			name : 'activityId',
			type : 'hidden'
		}, {
			display : '任务名称',
			name : 'activityName',
			type : 'hidden'
		}, {
			display : '项目id',
			name : 'projectId',
			type : 'hidden'
		}, {
			display : '项目名称',
			name : 'projectName',
			type : 'hidden'
		}, {
			display : '项目编号',
			name : 'projectCode',
			type : 'hidden'
		}, {
			display : '是否接收',
			name : 'isRe',
			type : 'hidden'
		}, {
			display : '数量',
			name : 'number',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '库存地',
			name : 'area',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '负责人',
			name : 'areaPrincipal',
			type : 'statictext',
			isSubmit : true
		}, {
			display : 'rowNum',
			name : 'rowNum',
			type : 'hidden'
		}, {
			display : '是否接收',
			name : 'isRe',
			type : 'statictext',
			process : function(v){
				if(v=='0'){
				   return "×";
				}else if(v=='1'){
				   return "√";
				}else{
				   return "";
				}
			},
			isSubmit : true
		}],
		event : {
			'removeRow' : function(e, rowNum, rowData){
				if(rowData.rowNum == '' || typeof(rowData.rowNum) == "undefined" ){
					resetRow(rowData)
				}else{
				    var beforeStr = "DCJ_cmp";
					//获取现有数量
					var nowNumber= $("#"+ beforeStr + "_number" + rowData.rowNum ).val();
					//计算应反写数量
					var handleNumber = accAdd(nowNumber,rowData.number);
					//反写数量
					$("#"+ beforeStr + "_number" + rowData.rowNum ).val(handleNumber);
					handleRemoveRow(rowData.rowNum,handleNumber)
				}
			},
			'reloadData' : function(e){
				var thisGrid = $("#DSG");
				var isRnbn = thisGrid.yxeditgrid("getCmpByCol", "isRe");
			  isRnbn.each(function(i,n) {
                   if(this.value == '1'){
                   	 var tr = $(this).parent().parent();
                   	 var btn = tr.find("td span[class='removeBn']")
                   	 $(btn).hide();
                   }
			  });
			}
		}
	})
//无法调配
	$("#WFDP").yxeditgrid({
		objName : 'task[WFDP]',
		isAdd : false,
		isAddOneRow : false,
		url : '?model=engineering_resources_taskdetail&action=listJson',
		param : {
			'taskId' : $("#id").val(),
			'taskType' : 'WFDP'
		},
		colModel : [{
			display : '设备id',
			name : 'proResourceId',
			type : 'hidden'
		},{
			display : '设备id',
			name : 'resourceId',
			type : 'hidden'
		}, {
			display : '设备编码',
			name : 'resourceCode',
			type : 'hidden'
		}, {
			display : '分类id',
			name : 'resourceTypeId',
			type : 'hidden'
		}, {
			display : '设备分类',
			name : 'resourceTypeName',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '设备名称',
			name : 'resourceName',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '负责人ID',
			name : 'areaPrincipalId',
			tclass : 'txtshort',
			type : 'hidden'
		}, {
			display : '单位',
			name : 'unit',
			tclass : 'txtshort',
			type : 'hidden'
		}, {
			display : '预计借用日期',
			name : 'planBeginDate',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '预计归还日期',
			name : 'planEndDate',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '使用天数',
			name : 'useDays',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '设备折价',
			name : 'price',
			type : 'statictext',
			isSubmit : true,
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			display : '预计成本',
			name : 'amount',
			type : 'statictext',
			isSubmit : true,
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			display : '任务id',
			name : 'activityId',
			type : 'hidden'
		}, {
			display : '任务名称',
			name : 'activityName',
			type : 'hidden'
		}, {
			display : '项目id',
			name : 'projectId',
			type : 'hidden'
		}, {
			display : '项目名称',
			name : 'projectName',
			type : 'hidden'
		}, {
			display : '项目编号',
			name : 'projectCode',
			type : 'hidden'
		}, {
			display : '是否接收',
			name : 'isRe',
			type : 'hidden'
		}, {
			display : '数量',
			name : 'number',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '库存地',
			name : 'area',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '负责人',
			name : 'areaPrincipal',
			type : 'statictext',
			isSubmit : true
		}, {
			display : 'rowNum',
			name : 'rowNum',
			type : 'hidden'
		}, {
			display : '是否接收',
			name : 'isRe',
			type : 'statictext',
			process : function(v){
				if(v=='0'){
				   return "×";
				}else if(v=='1'){
				   return "√";
				}else{
				   return "";
				}
			},
			isSubmit : true
		}],
		event : {
			'removeRow' : function(e, rowNum, rowData){
				if(rowData.rowNum == '' || typeof(rowData.rowNum) == "undefined" ){
					resetRow(rowData)
				}else{
				    var beforeStr = "DCJ_cmp";
					//获取现有数量
					var nowNumber= $("#"+ beforeStr + "_number" + rowData.rowNum ).val();
					//计算应反写数量
					var handleNumber = accAdd(nowNumber,rowData.number);
					//反写数量
					$("#"+ beforeStr + "_number" + rowData.rowNum ).val(handleNumber);
					handleRemoveRow(rowData.rowNum,handleNumber)
				}
			},
			'reloadData' : function(e){
				var thisGrid = $("#WFDP");
				var isRnbn = thisGrid.yxeditgrid("getCmpByCol", "isRe");
			  isRnbn.each(function(i,n) {
                   if(this.value == '1'){
                   	 var tr = $(this).parent().parent();
                   	 var btn = tr.find("td span[class='removeBn']")
                   	 $(btn).hide();
                   }
			  });
			}
		}
	})
//待拆解 设备
	$("#DCJ").yxeditgrid({
		objName : 'task[DCJ]',
		isAddAndDel : false,
		isAddOneRow : false,
		colModel : [{
			display : '清单id',
			name : 'id',
			type : 'hidden'
		},{
			display : '设备id',
			name : 'resourceId',
			type : 'hidden'
		}, {
			display : '设备编码',
			name : 'resourceCode',
			type : 'hidden'
		}, {
			display : '分类id',
			name : 'resourceTypeId',
			type : 'hidden'
		}, {
			display : '设备分类',
			name : 'resourceTypeName',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '设备名称',
			name : 'resourceName',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '单位',
			name : 'unit',
			tclass : 'txtshort',
			validation : {
				required : true
			},
			type : 'hidden'
		}, {
			display : '预计借用日期',
			name : 'planBeginDate',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '预计归还日期',
			name : 'planEndDate',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '使用天数',
			name : 'useDays',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '设备折价',
			name : 'price',
			type : 'hidden',
			readonly : true,
			tclass : 'readOnlyTxtShort'
		}, {
			display : '预计成本',
			name : 'amount',
			tclass : 'txtshort',
			type : 'hidden',
			readonly : true,
			tclass : 'readOnlyTxtShort'
		}, {
			display : '任务id',
			name : 'activityId',
			type : 'hidden'
		}, {
			display : '任务名称',
			name : 'activityName',
			type : 'hidden'
		}, {
			display : '项目id',
			name : 'projectId',
			type : 'hidden'
		}, {
			display : '项目名称',
			name : 'projectName',
			type : 'hidden'
		}, {
			display : '项目编号',
			name : 'projectCode',
			type : 'hidden'
		}, {
			display : '数量',
			name : 'number',
			tclass : 'readOnlyTxtShort',
			type : 'int',
			readonly : true
		}, {
			display : '分配数量',
			name : 'allotNumber',
			tclass : 'txtshort',
			type : 'int'
		}, {
			display : '分配类型',
			name : 'allotType',
			type : 'select',
			options : [{
				name : "..请选择..",
				value : ""
			},{
				name : "在库设备",
				value : "在库设备"
			}, {
				name : "待申购/生产设备",
				value : "待申购/生产设备"
			}, {
				name : "无法调配设备",
				value : "无法调配设备"
			}]
		}, {
			display : '库存地',
			name : 'area',
			tclass : 'txtshort',
			readonly : true,
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_projectArea({
					hiddenId : 'DCJ_cmp_areaId' + rowNum,
					nameCol : 'area',
					width : 600,
					isFocusoutCheck : false,
					gridOptions : {
						param : { 'list_id' : $("#DCJ_cmp_resourceId"+rowNum).val()},
						showcheckbox : false,
						event : {
							row_dblclick : (function(rowNum) {
								return function(e, row, rowData) {
									g.getCmpByRowAndCol(rowNum,'area').val(rowData.Name);
									g.getCmpByRowAndCol(rowNum,'areaId').val(rowData.id);
//									$("#activityMembers_cmp_price" + rowNum).val(rowData.price);
								}
							})(rowNum)
						}
					}
				});
			}
		}, {
			display : '库存地ID',
			name : 'areaId',
			type : 'hidden'
		}, {
			display : '负责人',
			name : 'areaPrincipal',
			tclass : 'txtshort',
			width : 80,
			readonly : true,
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxselect_user({
					hiddenId : 'DCJ_cmp_areaPrincipalId' + rowNum,
					formCode : 'areaPrincipal'
				});
			}
		}, {
			display : '负责人ID',
			name : 'areaPrincipalId',
			type : 'hidden'
		}, {
			name : 'deployButton',
			display : '确认分配',
			type : 'statictext',
			html : '<input type="button"  value="确认分配"  class="txt_btn_a"  />',
			event : {
				'click' : function(e) {
				  var row = e.data.rowData;
				  var rowNum = $(this).data("rowNum");
				  //处理并插入数据
				  handleRow(row,rowNum);

				}
			}
		}]
	})
});
function resetRow(row){
   var addRgrid = $("#DCJ").data("yxeditgrid");
   var rum = addRgrid.getCurRowNum() + 1;
   addRgrid.addRow(rum,row);
}
function handleRow(row,rowNum){
	//从表前置字符串
	var beforeStr = "DCJ_cmp";
	//获取当前数量
	var allotNumber= $("#"+ beforeStr + "_allotNumber" + rowNum ).val();
	var area= $("#"+ beforeStr + "_area" + rowNum ).val();
	var areaId= $("#"+ beforeStr + "_areaId" + rowNum ).val();
	var areaPrincipal= $("#"+ beforeStr + "_areaPrincipal" + rowNum ).val();
	var areaPrincipalId= $("#"+ beforeStr + "_areaPrincipalId" + rowNum ).val();
	//分配类型
	var allotType = $("#"+ beforeStr + "_allotType" + rowNum ).val();

	inputJson = {
		    "rowNum" : rowNum,
		    "proResourceId" : row.id,
			"resourceId" : row.resourceId,
			"resourceCode" : row.resourceCode,
			"resourceTypeId" : row.resourceTypeId,
			"resourceTypeName" : row.resourceTypeName,
			"resourceName" : row.resourceName,
			"unit" : row.unit,
			"planBeginDate" : row.planBeginDate,
			"planEndDate" : row.planEndDate,
			"useDays" : row.useDays,
			"price" : row.price,
			"amount" : row.amount,
			"projectId" : row.projectId,
			"projectName" : row.projectName,
			"projectCode" : row.projectCode,

			"number" : allotNumber,//数量
			"area" : area,//库存地
			"areaId" : areaId,
			"areaPrincipal" : areaPrincipal,//负责人
			"areaPrincipalId" : areaPrincipalId//负责人ID
		};
    switch(allotType){
      case "" : addR = ""; alert("请选择分配类型");break;
      case "在库设备" :
         addR = $("#ZK").data("yxeditgrid"); break;
      case "待申购/生产设备" :
         addR = $("#DSG").data("yxeditgrid"); break;
      case "无法调配设备" :
         addR = $("#WFDP").data("yxeditgrid"); break;
    }
     //插入数据
    if(addR != ""){
    	var number = $("#"+ beforeStr + "_number" + rowNum ).val();
    	var num = number - allotNumber;//分配数量
    	if(area == "" && allotType == "在库设备"){
    	  alert("请选择库存地" )
    	}else if(areaPrincipal == "" && (allotType == "在库设备" || allotType == "待申购/生产设备")){
    	  alert("请选择负责人")
    	}else if(allotNumber == ""){
    	  alert("请填写分配数量")
    	}else
    	if(num < 0 ){
    	   alert("分配数量不得大于设备需求数量");
    	}else{
    	   var addrowNum = addR.getCurRowNum() + 1;
	       addR.addRow(addrowNum,inputJson);
//	     $("#"+ beforeStr + "_allotNumber" + rowNum ).val("");
//	     $("#"+ beforeStr + "_area" + rowNum ).val("");
//	     $("#"+ beforeStr + "_areaPrincipal" + rowNum ).val("");
//	     $("#"+ beforeStr + "_areaPrincipalId" + rowNum ).val("");

	       $("#"+ beforeStr + "_number" + rowNum ).val(num);
	       handleRemoveRow(rowNum,num)
    	}

    }
}
//处理是否需求清楚分配单数据
function handleRemoveRow(rowNum,num){
	var g = $("#DCJ").data("yxeditgrid");
   if(num <= '0'){
      g.removeRow(rowNum);
   }else{
      g.showRow(rowNum);
   }
}
//$(function(){
//     //组织机构选择
//	$("#taskManager").yxselect_user({
//						hiddenId : 'taskManagerId'
//					});
//
//});

//提交验证
function sub(){
      var rowNum = $("#DCJ").yxeditgrid('getCurShowRowNum');
      if(rowNum != '0'){
        alert("还有物料未拆解！");
        return false;
      }
        return true;
}
