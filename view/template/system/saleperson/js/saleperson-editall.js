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
			process: function(v){
			  if(v == "0"){
			    return "����";
			  }else{
			    return "����";
			  }
			}
		}]

	});
});


// ��֯����ѡ��
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
    //��֤
	validate({
		"personName" : {
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
