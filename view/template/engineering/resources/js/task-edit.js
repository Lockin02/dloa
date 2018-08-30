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
			display : '�豸id',
			name : 'proResourceId',
			type : 'hidden'
		},{
			display : '�豸id',
			name : 'resourceId',
			type : 'hidden'
		}, {
			display : '�豸����',
			name : 'resourceCode',
			type : 'hidden'
		}, {
			display : '����id',
			name : 'resourceTypeId',
			type : 'hidden'
		}, {
			display : '�豸����',
			name : 'resourceTypeName',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '�豸����',
			name : 'resourceName',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '������ID',
			name : 'areaPrincipalId',
			tclass : 'txtshort',
			type : 'hidden'
		}, {
			display : '��λ',
			name : 'unit',
			tclass : 'txtshort',
			type : 'hidden'
		}, {
			display : 'Ԥ�ƽ�������',
			name : 'planBeginDate',
			type : 'statictext',
			isSubmit : true
		}, {
			display : 'Ԥ�ƹ黹����',
			name : 'planEndDate',
			type : 'statictext',
			isSubmit : true
		}, {
			display : 'ʹ������',
			name : 'useDays',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '�豸�ۼ�',
			name : 'price',
			type : 'statictext',
			isSubmit : true,
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			display : 'Ԥ�Ƴɱ�',
			name : 'amount',
			type : 'statictext',
			isSubmit : true,
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			display : '����id',
			name : 'activityId',
			type : 'hidden'
		}, {
			display : '��������',
			name : 'activityName',
			type : 'hidden'
		}, {
			display : '��Ŀid',
			name : 'projectId',
			type : 'hidden'
		}, {
			display : '��Ŀ����',
			name : 'projectName',
			type : 'hidden'
		}, {
			display : '��Ŀ���',
			name : 'projectCode',
			type : 'hidden'
		}, {
			display : '�Ƿ����',
			name : 'isRe',
			type : 'hidden'
		}, {
			display : '����',
			name : 'number',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '����',
			name : 'area',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '������',
			name : 'areaPrincipal',
			type : 'statictext',
			isSubmit : true
		}, {
			display : 'rowNum',
			name : 'rowNum',
			type : 'hidden'
		}, {
			display : '�Ƿ����',
			name : 'isRe',
			type : 'statictext',
			isSubmit : true,
			process : function(v){
				if(v=='0'){
				   return "��";
				}else if(v=='1'){
				   return "��";
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
					//��ȡ��������
					var nowNumber= $("#"+ beforeStr + "_number" + rowData.rowNum ).val();
					//����Ӧ��д����
					var handleNumber = accAdd(nowNumber,rowData.number);
					//��д����
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

	//���깺/�����豸
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
			display : '�豸id',
			name : 'proResourceId',
			type : 'hidden'
		},{
			display : '�豸id',
			name : 'resourceId',
			type : 'hidden'
		}, {
			display : '�豸����',
			name : 'resourceCode',
			type : 'hidden'
		}, {
			display : '����id',
			name : 'resourceTypeId',
			type : 'hidden'
		}, {
			display : '�豸����',
			name : 'resourceTypeName',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '�豸����',
			name : 'resourceName',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '������ID',
			name : 'areaPrincipalId',
			tclass : 'txtshort',
			type : 'hidden'
		}, {
			display : '��λ',
			name : 'unit',
			tclass : 'txtshort',
			type : 'hidden'
		}, {
			display : 'Ԥ�ƽ�������',
			name : 'planBeginDate',
			type : 'statictext',
			isSubmit : true
		}, {
			display : 'Ԥ�ƹ黹����',
			name : 'planEndDate',
			type : 'statictext',
			isSubmit : true
		}, {
			display : 'ʹ������',
			name : 'useDays',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '�豸�ۼ�',
			name : 'price',
			type : 'statictext',
			isSubmit : true,
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			display : 'Ԥ�Ƴɱ�',
			name : 'amount',
			type : 'statictext',
			isSubmit : true,
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			display : '����id',
			name : 'activityId',
			type : 'hidden'
		}, {
			display : '��������',
			name : 'activityName',
			type : 'hidden'
		}, {
			display : '��Ŀid',
			name : 'projectId',
			type : 'hidden'
		}, {
			display : '��Ŀ����',
			name : 'projectName',
			type : 'hidden'
		}, {
			display : '��Ŀ���',
			name : 'projectCode',
			type : 'hidden'
		}, {
			display : '�Ƿ����',
			name : 'isRe',
			type : 'hidden'
		}, {
			display : '����',
			name : 'number',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '����',
			name : 'area',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '������',
			name : 'areaPrincipal',
			type : 'statictext',
			isSubmit : true
		}, {
			display : 'rowNum',
			name : 'rowNum',
			type : 'hidden'
		}, {
			display : '�Ƿ����',
			name : 'isRe',
			type : 'statictext',
			process : function(v){
				if(v=='0'){
				   return "��";
				}else if(v=='1'){
				   return "��";
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
					//��ȡ��������
					var nowNumber= $("#"+ beforeStr + "_number" + rowData.rowNum ).val();
					//����Ӧ��д����
					var handleNumber = accAdd(nowNumber,rowData.number);
					//��д����
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
//�޷�����
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
			display : '�豸id',
			name : 'proResourceId',
			type : 'hidden'
		},{
			display : '�豸id',
			name : 'resourceId',
			type : 'hidden'
		}, {
			display : '�豸����',
			name : 'resourceCode',
			type : 'hidden'
		}, {
			display : '����id',
			name : 'resourceTypeId',
			type : 'hidden'
		}, {
			display : '�豸����',
			name : 'resourceTypeName',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '�豸����',
			name : 'resourceName',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '������ID',
			name : 'areaPrincipalId',
			tclass : 'txtshort',
			type : 'hidden'
		}, {
			display : '��λ',
			name : 'unit',
			tclass : 'txtshort',
			type : 'hidden'
		}, {
			display : 'Ԥ�ƽ�������',
			name : 'planBeginDate',
			type : 'statictext',
			isSubmit : true
		}, {
			display : 'Ԥ�ƹ黹����',
			name : 'planEndDate',
			type : 'statictext',
			isSubmit : true
		}, {
			display : 'ʹ������',
			name : 'useDays',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '�豸�ۼ�',
			name : 'price',
			type : 'statictext',
			isSubmit : true,
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			display : 'Ԥ�Ƴɱ�',
			name : 'amount',
			type : 'statictext',
			isSubmit : true,
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			display : '����id',
			name : 'activityId',
			type : 'hidden'
		}, {
			display : '��������',
			name : 'activityName',
			type : 'hidden'
		}, {
			display : '��Ŀid',
			name : 'projectId',
			type : 'hidden'
		}, {
			display : '��Ŀ����',
			name : 'projectName',
			type : 'hidden'
		}, {
			display : '��Ŀ���',
			name : 'projectCode',
			type : 'hidden'
		}, {
			display : '�Ƿ����',
			name : 'isRe',
			type : 'hidden'
		}, {
			display : '����',
			name : 'number',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '����',
			name : 'area',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '������',
			name : 'areaPrincipal',
			type : 'statictext',
			isSubmit : true
		}, {
			display : 'rowNum',
			name : 'rowNum',
			type : 'hidden'
		}, {
			display : '�Ƿ����',
			name : 'isRe',
			type : 'statictext',
			process : function(v){
				if(v=='0'){
				   return "��";
				}else if(v=='1'){
				   return "��";
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
					//��ȡ��������
					var nowNumber= $("#"+ beforeStr + "_number" + rowData.rowNum ).val();
					//����Ӧ��д����
					var handleNumber = accAdd(nowNumber,rowData.number);
					//��д����
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
//����� �豸
	$("#DCJ").yxeditgrid({
		objName : 'task[DCJ]',
		isAddAndDel : false,
		isAddOneRow : false,
		colModel : [{
			display : '�嵥id',
			name : 'id',
			type : 'hidden'
		},{
			display : '�豸id',
			name : 'resourceId',
			type : 'hidden'
		}, {
			display : '�豸����',
			name : 'resourceCode',
			type : 'hidden'
		}, {
			display : '����id',
			name : 'resourceTypeId',
			type : 'hidden'
		}, {
			display : '�豸����',
			name : 'resourceTypeName',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '�豸����',
			name : 'resourceName',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '��λ',
			name : 'unit',
			tclass : 'txtshort',
			validation : {
				required : true
			},
			type : 'hidden'
		}, {
			display : 'Ԥ�ƽ�������',
			name : 'planBeginDate',
			type : 'statictext',
			isSubmit : true
		}, {
			display : 'Ԥ�ƹ黹����',
			name : 'planEndDate',
			type : 'statictext',
			isSubmit : true
		}, {
			display : 'ʹ������',
			name : 'useDays',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '�豸�ۼ�',
			name : 'price',
			type : 'hidden',
			readonly : true,
			tclass : 'readOnlyTxtShort'
		}, {
			display : 'Ԥ�Ƴɱ�',
			name : 'amount',
			tclass : 'txtshort',
			type : 'hidden',
			readonly : true,
			tclass : 'readOnlyTxtShort'
		}, {
			display : '����id',
			name : 'activityId',
			type : 'hidden'
		}, {
			display : '��������',
			name : 'activityName',
			type : 'hidden'
		}, {
			display : '��Ŀid',
			name : 'projectId',
			type : 'hidden'
		}, {
			display : '��Ŀ����',
			name : 'projectName',
			type : 'hidden'
		}, {
			display : '��Ŀ���',
			name : 'projectCode',
			type : 'hidden'
		}, {
			display : '����',
			name : 'number',
			tclass : 'readOnlyTxtShort',
			type : 'int',
			readonly : true
		}, {
			display : '��������',
			name : 'allotNumber',
			tclass : 'txtshort',
			type : 'int'
		}, {
			display : '��������',
			name : 'allotType',
			type : 'select',
			options : [{
				name : "..��ѡ��..",
				value : ""
			},{
				name : "�ڿ��豸",
				value : "�ڿ��豸"
			}, {
				name : "���깺/�����豸",
				value : "���깺/�����豸"
			}, {
				name : "�޷������豸",
				value : "�޷������豸"
			}]
		}, {
			display : '����',
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
			display : '����ID',
			name : 'areaId',
			type : 'hidden'
		}, {
			display : '������',
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
			display : '������ID',
			name : 'areaPrincipalId',
			type : 'hidden'
		}, {
			name : 'deployButton',
			display : 'ȷ�Ϸ���',
			type : 'statictext',
			html : '<input type="button"  value="ȷ�Ϸ���"  class="txt_btn_a"  />',
			event : {
				'click' : function(e) {
				  var row = e.data.rowData;
				  var rowNum = $(this).data("rowNum");
				  //������������
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
	//�ӱ�ǰ���ַ���
	var beforeStr = "DCJ_cmp";
	//��ȡ��ǰ����
	var allotNumber= $("#"+ beforeStr + "_allotNumber" + rowNum ).val();
	var area= $("#"+ beforeStr + "_area" + rowNum ).val();
	var areaId= $("#"+ beforeStr + "_areaId" + rowNum ).val();
	var areaPrincipal= $("#"+ beforeStr + "_areaPrincipal" + rowNum ).val();
	var areaPrincipalId= $("#"+ beforeStr + "_areaPrincipalId" + rowNum ).val();
	//��������
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

			"number" : allotNumber,//����
			"area" : area,//����
			"areaId" : areaId,
			"areaPrincipal" : areaPrincipal,//������
			"areaPrincipalId" : areaPrincipalId//������ID
		};
    switch(allotType){
      case "" : addR = ""; alert("��ѡ���������");break;
      case "�ڿ��豸" :
         addR = $("#ZK").data("yxeditgrid"); break;
      case "���깺/�����豸" :
         addR = $("#DSG").data("yxeditgrid"); break;
      case "�޷������豸" :
         addR = $("#WFDP").data("yxeditgrid"); break;
    }
     //��������
    if(addR != ""){
    	var number = $("#"+ beforeStr + "_number" + rowNum ).val();
    	var num = number - allotNumber;//��������
    	if(area == "" && allotType == "�ڿ��豸"){
    	  alert("��ѡ�����" )
    	}else if(areaPrincipal == "" && (allotType == "�ڿ��豸" || allotType == "���깺/�����豸")){
    	  alert("��ѡ������")
    	}else if(allotNumber == ""){
    	  alert("����д��������")
    	}else
    	if(num < 0 ){
    	   alert("�����������ô����豸��������");
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
//�����Ƿ�����������䵥����
function handleRemoveRow(rowNum,num){
	var g = $("#DCJ").data("yxeditgrid");
   if(num <= '0'){
      g.removeRow(rowNum);
   }else{
      g.showRow(rowNum);
   }
}
//$(function(){
//     //��֯����ѡ��
//	$("#taskManager").yxselect_user({
//						hiddenId : 'taskManagerId'
//					});
//
//});

//�ύ��֤
function sub(){
      var rowNum = $("#DCJ").yxeditgrid('getCurShowRowNum');
      if(rowNum != '0'){
        alert("��������δ��⣡");
        return false;
      }
        return true;
}
