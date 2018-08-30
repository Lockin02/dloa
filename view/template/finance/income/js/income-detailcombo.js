//初始化表格，带清空
function reloadCombo(thisVal,rowNum){
    var objGrid = $("#allotTable");
	var thisObj = objGrid.yxeditgrid('getCmpByRowAndCol',rowNum,'objCode',true);
	thisObj.yxcombogrid_allcontract('remove');
	thisObj.yxcombogrid_accessorder('remove');
	thisObj.yxcombogrid_reduce('remove');

	thisObj.val('');
    objGrid.yxeditgrid('setRowColValue',rowNum,'objId','');
    objGrid.yxeditgrid('setRowColValue',rowNum,'rObjCode','');
	objGrid.yxeditgrid('setRowColValue',rowNum,'salesArea','');

    reloadComboClear(thisVal,rowNum)
}

//无清空加载grid ， 用于编辑款额分配
function reloadComboClear(thisVal,rowNum){
	//选择客户
	if($("#contractUnitId").val() != ""){
		$thisCustomerId = $("#contractUnitId").val();
	}else{
		$thisCustomerId = $("#incomeUnitId").val();
	}

	//选择客户
	if($("#contractUnitName").val() != ""){
		$thisCustomerName = $("#contractUnitName").val();
	}else{
		$thisCustomerName = $("#incomeUnitName").val();
	}

	switch(thisVal){
		case 'KPRK-10' : initAccessOrder(rowNum,thisVal);break;
		case 'KPRK-11' : initRepairReduce(rowNum,thisVal);break;
		case 'KPRK-12' : initContract(rowNum,thisVal);break;
		default : break;
	}
}

//初始化新合同
function initContract(rowNum,thisVal){
    var objGrid = $("#allotTable");
    var thisObj = objGrid.yxeditgrid('getCmpByRowAndCol',rowNum,'objCode',true);
    thisObj.yxcombogrid_allcontract({
		hiddenId : objGrid.yxeditgrid('getCmpByRowAndCol',rowNum,'objId',true).attr('id'),
		height : 300,
		width : 700,
		nameCol : 'contractCode',
		gridOptions : {
			param : {"customerId":$thisCustomerId , 'ExaStatusArr' : '完成,变更审批中'},
			showcheckbox :false,
			event : {
				'row_dblclick' : function(e,row,data) {
                    objGrid.yxeditgrid('setRowColValue',rowNum,'rObjCode',data.objCode);
					objGrid.yxeditgrid('setRowColValue',rowNum,'areaName',data.areaName);
					//判断是否重复，重复则清空
					var isRepeat = checkObjRepeat(thisVal);
                    if(isRepeat){
                        objGrid.yxeditgrid('setRowColValue',rowNum,'rObjCode','');
                        objGrid.yxeditgrid('setRowColValue',rowNum,'objId','');
                        objGrid.yxeditgrid('setRowColValue',rowNum,'objCode','');
						objGrid.yxeditgrid('setRowColValue',rowNum,'areaName','');
                    }
				}
			}
		},
		event : {
			'clear' : function() {
                objGrid.yxeditgrid('setRowColValue',rowNum,'rObjCode','');
                objGrid.yxeditgrid('setRowColValue',rowNum,'objId','');
                objGrid.yxeditgrid('setRowColValue',rowNum,'objCode','');
				objGrid.yxeditgrid('setRowColValue',rowNum,'areaName','');
			}
		}
	});
}

//初始化配件订单
function initAccessOrder(rowNum,thisVal){
    var objGrid = $("#allotTable");
    var thisObj = objGrid.yxeditgrid('getCmpByRowAndCol',rowNum,'objCode',true);
    thisObj.yxcombogrid_accessorder({
        hiddenId : objGrid.yxeditgrid('getCmpByRowAndCol',rowNum,'objId',true).attr('id'),
        height : 300,
        width : 700,
        searchName : 'docCode',
        gridOptions : {
            param : {"customerId":$thisCustomerId  , 'ExaStatus' : '完成'},
            showcheckbox :false,
            event : {
                'row_dblclick' : function(e,row,data) {
                    //判断是否重复，重复则清空
                    var isRepeat = checkObjRepeat(thisVal);
                    if(isRepeat){
                        objGrid.yxeditgrid('setRowColValue',rowNum,'rObjCode','');
                        objGrid.yxeditgrid('setRowColValue',rowNum,'objId','');
                        objGrid.yxeditgrid('setRowColValue',rowNum,'objCode','');
                    }
                }
            }
        },
        event : {
            'clear' : function() {
                objGrid.yxeditgrid('setRowColValue',rowNum,'rObjCode','');
                objGrid.yxeditgrid('setRowColValue',rowNum,'objId','');
                objGrid.yxeditgrid('setRowColValue',rowNum,'objCode','');
            }
        }
    });
}

//初始化维修单
function initRepairReduce(rowNum,thisVal){
    var objGrid = $("#allotTable");
    var thisObj = objGrid.yxeditgrid('getCmpByRowAndCol',rowNum,'objCode',true);
    thisObj.yxcombogrid_reduce({
        hiddenId : objGrid.yxeditgrid('getCmpByRowAndCol',rowNum,'objId',true).attr('id'),
        height : 300,
        width : 700,
        searchName : 'docCode',
        gridOptions : {
            param : {"customerId":$thisCustomerId  , 'ExaStatus' : '完成'},
            showcheckbox :false,
            event : {
                'row_dblclick' : function(e,row,data) {
                    //判断是否重复，重复则清空
                    var isRepeat = checkObjRepeat(thisVal);
                    if(isRepeat){
                        objGrid.yxeditgrid('setRowColValue',rowNum,'rObjCode','');
                        objGrid.yxeditgrid('setRowColValue',rowNum,'objId','');
                        objGrid.yxeditgrid('setRowColValue',rowNum,'objCode','');
                    }
                }
            }
        },
        event : {
            'clear' : function() {
                objGrid.yxeditgrid('setRowColValue',rowNum,'rObjCode','');
                objGrid.yxeditgrid('setRowColValue',rowNum,'objId','');
                objGrid.yxeditgrid('setRowColValue',rowNum,'objCode','');
            }
        }
    });
}

//判断相同类型的单据是否重复
function checkObjRepeat(thisType){
	var objIdArray = [];//缓存合同id
	var isRepeat = false;//是否重复

	//获取分配从表数据
    var tblObj = $("#allotTable"); // 缓存从表对象
	var objArr = tblObj.yxeditgrid('getCmpByCol','objType');
	if(objArr.length > 0){
		objArr.each(function(){
			if(this.value == thisType){
				var rowNum = $(this).data('rowNum');
				//id验证
				var objId = tblObj.yxeditgrid('getCmpByRowAndCol',rowNum,'objId').val();
				if(objId != '0' && objId != ''){
					if($.inArray(objId,objIdArray) != -1){
						isRepeat = true;
						return false;
					}
					objIdArray.push(objId);
				}
			}
		});
	}
	if(isRepeat == true){
		alert('同一条源单不能分配两次');
	}
	return isRepeat;
}

// 获取源单数据类型
function getObjType(code){
    var data = getDatadict(code);
    var rt = [];
    for(k in data){
        if(data[k].dataName){
            rt.push({'name':data[k].dataName,'value':data[k].dataCode});
        }
    }
    return rt;
}

//获取源单类型数据字典
function getDatadict(code){
	var responseText = $.ajax({
		url : 'index1.php?model=system_datadict_datadict&action=listJson',
		type : "POST",
		data : {"parentCode" : code ,'expand1' : '1'},
		async : false
	}).responseText;
	return eval("(" + responseText + ")");
}