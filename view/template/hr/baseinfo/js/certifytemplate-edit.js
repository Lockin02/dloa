$(document).ready(function() {
	//状态选中
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
			display : '模版Id',
			name : 'modelId',
			type : 'hidden',
			value :  $("#id").val()
		}, {
			display : '行为模块id',
			name : 'moduleId',
			type : 'hidden'
		}, {
			display : '行为模块',
			name : 'moduleName',
			readonly : true,
			tclass : 'readOnlyTxtMiddle'
		}, {
			display : '行为要项id',
			name : 'detailId',
			type : 'hidden'
		}, {
			display : '行为要项',
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
			display : '权重(%)',
			name : 'weights',
			tclass : 'txtshort',
			validation : {
				required : true
			}
		}, {
			display : '任职标准',
			name : 'standard',
			tclass : 'txtlong',
			validation : {
				required : true
			}
		}, {
			display : '需要提供的评价材料',
			name : 'needMaterial',
			tclass : 'txt',
			validation : {
				required : true
			}
		}]
	})

	/**
	 * 验证信息
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

//表单验证
function checkform() {
	//判断权重是否为100
	var rowAmountVa = 0;
	var cmps = $("#certifytemplatedetail").yxeditgrid("getCmpByCol", "weights");
	cmps.each(function () {
		rowAmountVa = accAdd(rowAmountVa, $(this).val(), 2);
	});
	if(rowAmountVa != 100){
		alert('当前权重和为 ' + rowAmountVa + " ,请重新调整权重");
		return false;
	}

	var rtVal = true;
	//验证是否已经存在同一份
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
	    		alert('已存在启用的同通道/级别/级等的模板，请调整模板的通道/级别/级等');
				rtVal = false;
	    	}
		}
	});

	return rtVal;
}