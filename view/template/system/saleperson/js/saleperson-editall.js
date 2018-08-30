/**
 * info
 */
$(function() {
	$("#info").yxeditgrid({
		objName : 'saleperson[info]',
		url:'?model=system_saleperson_saleperson&action=listJson',
		type:'view',
		param:{
            "ids" : $("#ids").val()
		},
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
			process: function(v){
			  if(v == "0"){
			    return "启用";
			  }else{
			    return "禁用";
			  }
			}
		}]

	});
});


// 组织机构选择
$(function() {
	$("#personName").yxselect_user({
				hiddenId : 'personId',
				isGetDept:[true,"deptId","deptName"]
	});
	$("#areaName").yxselect_user({
		hiddenId : 'areaNameId'
	});
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
