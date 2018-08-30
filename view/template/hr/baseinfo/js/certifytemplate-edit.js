$(document).ready(function() {
	//״̬ѡ��
	setSelect('status');

	$("#certifytemplatedetail").yxeditgrid({
		url : '?model=hr_baseinfo_certifytemplatedetail&action=listJson',
		param : {"modelId" : $("#id").val()},
		objName : 'certifytemplate[certifytemplatedetail]',
		tableClass : 'form_in_table',
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
			display : 'ģ��Id',
			name : 'modelId',
			type : 'hidden',
			value :  $("#id").val()
		}, {
			display : '��Ϊģ��id',
			name : 'moduleId',
			type : 'hidden'
		}, {
			display : '��Ϊģ��',
			name : 'moduleName',
			readonly : true,
			tclass : 'readOnlyTxtMiddle'
		}, {
			display : '��ΪҪ��id',
			name : 'detailId',
			type : 'hidden'
		}, {
			display : '��ΪҪ��',
			name : 'detailName',
			tclass : 'txtmiddle',
			validation : {
				required : true
			},
			readonly : true,
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_behamoduledetail({
					hiddenId : 'certifytemplatedetail_cmp_detailId' + rowNum,
					height : 250,
					gridOptions : {
						showcheckbox : false,
						isTitle : true,
						event : {
							row_dblclick : (function(rowNum) {
								return function(e, row, rowData) {
									g.getCmpByRowAndCol(rowNum,'moduleId').val(rowData.moduleId);
									g.getCmpByRowAndCol(rowNum,'moduleName').val(rowData.moduleName);
								}
							})(rowNum)
						}
					}
				});
			}
		}, {
			display : 'Ȩ��(%)',
			name : 'weights',
			tclass : 'txtshort',
			validation : {
				required : true
			}
		}, {
			display : '��ְ��׼',
			name : 'standard',
			tclass : 'txtlong',
			validation : {
				required : true
			}
		}, {
			display : '��Ҫ�ṩ�����۲���',
			name : 'needMaterial',
			tclass : 'txt',
			validation : {
				required : true
			}
		}]
	})

	/**
	 * ��֤��Ϣ
	 */
	validate({
		"modelName" : {
			required : true
		},
		"careerDirection" : {
			required : true
		},
		"baseLevel" : {
			required : true
		},
		"baseGrade" : {
			required : true
		}
	});
})

//����֤
function checkform() {
	//�ж�Ȩ���Ƿ�Ϊ100
	var rowAmountVa = 0;
	var cmps = $("#certifytemplatedetail").yxeditgrid("getCmpByCol", "weights");
	cmps.each(function () {
		rowAmountVa = accAdd(rowAmountVa, $(this).val(), 2);
	});
	if(rowAmountVa != 100){
		alert('��ǰȨ�غ�Ϊ ' + rowAmountVa + " ,�����µ���Ȩ��");
		return false;
	}

	var rtVal = true;
	//��֤�Ƿ��Ѿ�����ͬһ��
	$.ajax({
	    type: "POST",
	    url: "?model=hr_baseinfo_certifytemplate&action=isAnotherTemplate",
	    data: {
	    	"careerDirection" : $("#careerDirection").val(),
	    	"baseLevel" : $("#baseLevel").val(),
	    	"baseGrade" : $("#baseGrade").val(),
	    	"id" : $("#id").val()
	    },
	    async: false,
	    success: function(data){
	    	if(data != 0){
	    		alert('�Ѵ������õ�ͬͨ��/����/���ȵ�ģ�壬�����ģ���ͨ��/����/����');
				rtVal = false;
	    	}
		}
	});

	return rtVal;
}