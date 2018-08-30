// 组织机构选择
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

	//验证
	validate({
		"personName" : {
			required : true
		},
		"exeDeptName" : {
			required : true
		}
	});

    //执行区域
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
            // 快速搜索
            searchitems : [{
                display : '名称',
                name : 'dataName'
            }]
        }
    });
});

/**
 * 插入明细方法
 */
function addInfo(){
    var g = $("#info").data("yxeditgrid");
    var i = g.getCurShowRowNum();
   var countryName = $("#countryName").val();
   var provinceName = $("#provinceName").val();
   var cityName = $("#cityName").val();
   var customerTypeName = $("#customerTypeName").val();
 if(countryName == '' || provinceName == '' || cityName == '' || customerTypeName == ''){
    alert("信息不全请补全信息");
 }else{

 	//处理插入数组
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
		//插入数据
        g.addRow(i, outJson);
 }
}


/**
 * 新增info
 */
$(function() {
	$("#info").yxeditgrid({
		objName : 'saleperson[info]',
		isAddOneRow : false,
		isAdd : false,
		tableClass : 'form_in_table',
		colModel : [{
			display : '国家',
			name : 'country',
			type : 'statictext',
			isSubmit : true
		},{
			display : '国家id',
			name : 'countryId',
			type : 'hidden'
		}, {
			display : '省份',
			name : 'province',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '省份id',
			name : 'provinceId',
			type : 'hidden'
		}, {
			display : '城市',
			name : 'city',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '城市id',
			name : 'cityId',
			type : 'hidden'
		}, {
			display : '客户类型',
			name : 'customerTypeName',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '客户类型Code',
			name : 'customerType',
			type : 'hidden'
		}, {
			display : '是否启用',
			name : 'isUse',
			type : 'select',
			options : [{
				name : "启用",
				value : "0"
			},{
				name : "禁用",
				value : "1"
			}]
		}]

	});
});


function sub(){
   var rowNum = $("#info").yxeditgrid('getCurShowRowNum');
    if(rowNum == '0'){
      alert("请添加明细信息");
      return false;
    }
	return true;
}

/*
 * 省  市  客户类型
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
	//客户类型
	$("#customerTypeName").yxcombotree({
		hiddenId : 'customerType',//隐藏控件id
		nameCol:'name',
		width : 300,
		height : 300,
		treeOptions : {
			checkable : true,//多选
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
//加载省
function onloadProvinceTree(){
	$("#provinceName").yxcombotree({
		hiddenId : 'provinceId',//隐藏控件id
		nameCol:'name',
		width : 150,
		height : 300,
		treeOptions : {
			checkable : false,//多选
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
			url : "index1.php?model=system_procity_province&action=getChildren"//获取数据url
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
		hiddenId : 'cityId',//隐藏控件id
		nameCol:'name',
		width : 150,
		height : 300,
		treeOptions : {
			checkable : true,//多选
//			expandAll : true,
			event : {
//				"render":function(){
//						$("#cityName").yxcombotree('expandAll');
//					}
			},
//			url : "index1.php?model=system_procity_city&action=getChildren"//获取数据url
			data : [data]
		}
	});

}

/*
 * 判断是否为行业总监
 */
  function isdirector(obj){
     var objVal = obj.value;
     if(objVal == '1'){
        $("#provinceName").val("全国");
        $("#provinceId").val("-1");
        $("#cityName").val("全国");
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