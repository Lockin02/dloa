//��ʼ����񣬴����
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

//����ռ���grid �� ���ڱ༭������
function reloadComboClear(thisVal,rowNum){
	//ѡ��ͻ�
	if($("#contractUnitId").val() != ""){
		$thisCustomerId = $("#contractUnitId").val();
	}else{
		$thisCustomerId = $("#incomeUnitId").val();
	}

	//ѡ��ͻ�
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

//��ʼ���º�ͬ
function initContract(rowNum,thisVal){
    var objGrid = $("#allotTable");
    var thisObj = objGrid.yxeditgrid('getCmpByRowAndCol',rowNum,'objCode',true);
    thisObj.yxcombogrid_allcontract({
		hiddenId : objGrid.yxeditgrid('getCmpByRowAndCol',rowNum,'objId',true).attr('id'),
		height : 300,
		width : 700,
		nameCol : 'contractCode',
		gridOptions : {
			param : {"customerId":$thisCustomerId , 'ExaStatusArr' : '���,���������'},
			showcheckbox :false,
			event : {
				'row_dblclick' : function(e,row,data) {
                    objGrid.yxeditgrid('setRowColValue',rowNum,'rObjCode',data.objCode);
					objGrid.yxeditgrid('setRowColValue',rowNum,'areaName',data.areaName);
					//�ж��Ƿ��ظ����ظ������
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

//��ʼ���������
function initAccessOrder(rowNum,thisVal){
    var objGrid = $("#allotTable");
    var thisObj = objGrid.yxeditgrid('getCmpByRowAndCol',rowNum,'objCode',true);
    thisObj.yxcombogrid_accessorder({
        hiddenId : objGrid.yxeditgrid('getCmpByRowAndCol',rowNum,'objId',true).attr('id'),
        height : 300,
        width : 700,
        searchName : 'docCode',
        gridOptions : {
            param : {"customerId":$thisCustomerId  , 'ExaStatus' : '���'},
            showcheckbox :false,
            event : {
                'row_dblclick' : function(e,row,data) {
                    //�ж��Ƿ��ظ����ظ������
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

//��ʼ��ά�޵�
function initRepairReduce(rowNum,thisVal){
    var objGrid = $("#allotTable");
    var thisObj = objGrid.yxeditgrid('getCmpByRowAndCol',rowNum,'objCode',true);
    thisObj.yxcombogrid_reduce({
        hiddenId : objGrid.yxeditgrid('getCmpByRowAndCol',rowNum,'objId',true).attr('id'),
        height : 300,
        width : 700,
        searchName : 'docCode',
        gridOptions : {
            param : {"customerId":$thisCustomerId  , 'ExaStatus' : '���'},
            showcheckbox :false,
            event : {
                'row_dblclick' : function(e,row,data) {
                    //�ж��Ƿ��ظ����ظ������
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

//�ж���ͬ���͵ĵ����Ƿ��ظ�
function checkObjRepeat(thisType){
	var objIdArray = [];//�����ͬid
	var isRepeat = false;//�Ƿ��ظ�

	//��ȡ����ӱ�����
    var tblObj = $("#allotTable"); // ����ӱ����
	var objArr = tblObj.yxeditgrid('getCmpByCol','objType');
	if(objArr.length > 0){
		objArr.each(function(){
			if(this.value == thisType){
				var rowNum = $(this).data('rowNum');
				//id��֤
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
		alert('ͬһ��Դ�����ܷ�������');
	}
	return isRepeat;
}

// ��ȡԴ����������
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

//��ȡԴ�����������ֵ�
function getDatadict(code){
	var responseText = $.ajax({
		url : 'index1.php?model=system_datadict_datadict&action=listJson',
		type : "POST",
		data : {"parentCode" : code ,'expand1' : '1'},
		async : false
	}).responseText;
	return eval("(" + responseText + ")");
}