// ��֯����ѡ��
$(function() {
    $("#salesAreaName").yxcombogrid_area({
        hiddenId: 'salesAreaId',
        gridOptions: {
            showcheckbox: false,
//			param : { 'businessBelong' : $("#businessBelong").val()},
            event: {
                'row_dblclick': function (e, row, data) {
                    $("#salesManNames").val(data.areaPrincipal);
                    $("#salesAreaId").val(data.id);
                    $("#salesManIds").val(data.areaPrincipalId);
                }
            }
        }
    });
	$("#personName").yxselect_user({
		hiddenId : 'personId',
		isGetDept : [true, "deptId", "deptName"]
	});
//	$("#areaName").yxselect_user({
//		hiddenId : 'areaNameId'
//	});
	$("#businessBelongName").yxcombogrid_branch({
		hiddenId : 'businessBelong',
		height : 250,
		isFocusoutCheck : false,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e,row,data) {
				}
			}
		}
	});

	//��֤
	validate({
		"personName" : {
			required : true
		},
		"exeDeptName" : {
			required : true
		}
	});

    //ִ������
    $("#exeDeptName").yxcombogrid_datadict({
        height : 250,
        valueCol : 'dataCode',
        hiddenId : 'exeDeptCode',
        gridOptions : {
            isTitle : true,
            param : {"parentCode":"GCSCX"},
            showcheckbox : false,
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
});

/**
 * ������ϸ����
 */
function addInfo(){
    var g = $("#info").data("yxeditgrid");
    var i = g.getCurShowRowNum();
   var countryName = $("#countryName").val();
   var provinceName = $("#provinceName").val();
   var cityName = $("#cityName").val();
   var customerTypeName = $("#customerTypeName").val();
 if(countryName == '' || provinceName == '' || cityName == '' || customerTypeName == ''){
    alert("��Ϣ��ȫ�벹ȫ��Ϣ");
 }else{

 	//�����������
		outJson = {
			"country" : countryName,
			"countryId" : $("#countryId").val(),
			"province" : provinceName,
			"provinceId" : $("#provinceId").val(),
			"city" : cityName,
			"cityId" : $("#cityId").val(),
			"customerTypeName" : customerTypeName,
			"customerType" : $("#customerType").val(),
			"isUse" : $("#isUse").val()
		};
		//��������
        g.addRow(i, outJson);
 }
}


/**
 * ����info
 */
$(function() {
	$("#info").yxeditgrid({
		objName : 'saleperson[info]',
		isAddOneRow : false,
		isAdd : false,
		tableClass : 'form_in_table',
		colModel : [{
			display : '����',
			name : 'country',
			type : 'statictext',
			isSubmit : true
		},{
			display : '����id',
			name : 'countryId',
			type : 'hidden'
		}, {
			display : 'ʡ��',
			name : 'province',
			type : 'statictext',
			isSubmit : true
		}, {
			display : 'ʡ��id',
			name : 'provinceId',
			type : 'hidden'
		}, {
			display : '����',
			name : 'city',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '����id',
			name : 'cityId',
			type : 'hidden'
		}, {
			display : '�ͻ�����',
			name : 'customerTypeName',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '�ͻ�����Code',
			name : 'customerType',
			type : 'hidden'
		}, {
			display : '�Ƿ�����',
			name : 'isUse',
			type : 'select',
			options : [{
				name : "����",
				value : "0"
			},{
				name : "����",
				value : "1"
			}]
		}]

	});
});


function sub(){
   var rowNum = $("#info").yxeditgrid('getCurShowRowNum');
    if(rowNum == '0'){
      alert("�������ϸ��Ϣ");
      return false;
    }
	return true;
}

/*
 * ʡ  ��  �ͻ�����
 */
$(document).ready(function(){

    var cusData = $.ajax({
		type : 'POST',
		url : '?model=system_datadict_datadict&action=getCustomerType',
		data : {
			'id' : $("#provinceId").val()
		},
		async : false,
		success : function(data) {

		}
	}).responseText;
   cusData = eval("(" + cusData + ")");
	//�ͻ�����
	$("#customerTypeName").yxcombotree({
		hiddenId : 'customerType',//���ؿؼ�id
		nameCol:'name',
		width : 300,
		height : 300,
		treeOptions : {
			checkable : true,//��ѡ
			event : {
//				"render":function(){
//					$("#customerTypeName").yxcombotree('expandAll');
//				},
				"node_click" : function(event, treeId, treeNode) {
					$("#customerTypeName").val(treeNode.dataName);
					$("#customerType").val(treeNode.dataCode);
				}
			},
			data : [cusData]
		}
	});
    onloadProvinceTree();
});
//����ʡ
function onloadProvinceTree(){
	$("#provinceName").yxcombotree({
		hiddenId : 'provinceId',//���ؿؼ�id
		nameCol:'name',
		width : 150,
		height : 300,
		treeOptions : {
			checkable : false,//��ѡ
			event : {
				"node_click" : function(event, treeId, treeNode) {
					$("#provinceName").val(treeNode.name);
					$("#provinceId").val(treeNode.id);
					$("#cityName").val("");
					$("#cityId").val("");
					$("#cityName").yxcombotree("remove");
					onloadCityTree();
				}
			},
			url : "index1.php?model=system_procity_province&action=getChildren"//��ȡ����url
		}
	});
}
function onloadCityTree(){

    var data = $.ajax({
		type : 'POST',
		url : '?model=system_procity_city&action=getChildren',
		data : {
			'id' : $("#provinceId").val()
		},
		async : false,
		success : function(data) {

		}
	}).responseText;
   data = eval("(" + data + ")");
	$("#cityName").yxcombotree({
		hiddenId : 'cityId',//���ؿؼ�id
		nameCol:'name',
		width : 150,
		height : 300,
		treeOptions : {
			checkable : true,//��ѡ
//			expandAll : true,
			event : {
//				"render":function(){
//						$("#cityName").yxcombotree('expandAll');
//					}
			},
//			url : "index1.php?model=system_procity_city&action=getChildren"//��ȡ����url
			data : [data]
		}
	});

}

/*
 * �ж��Ƿ�Ϊ��ҵ�ܼ�
 */
  function isdirector(obj){
     var objVal = obj.value;
     if(objVal == '1'){
        $("#provinceName").val("ȫ��");
        $("#provinceId").val("-1");
        $("#cityName").val("ȫ��");
        $("#cityId").val("-1");

        $("#provinceName").yxcombotree("remove");
        $("#cityName").yxcombotree("remove");
     }else{
        $("#provinceName").val("");
        $("#provinceId").val("");
        $("#cityName").val("");
        $("#cityId").val("");

        onloadProvinceTree();
     }
  }