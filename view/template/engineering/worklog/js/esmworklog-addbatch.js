var provinceArr; //缓存的省份数组
var cityArr; //缓存的城市数组
var cityCache = {};

$(document).ready(function() {
	//省份
	provinceArr = getProvince();

	//如果有默认
	var provinceId = $("#provinceId").val() + "";
	if(provinceId != ""){
		cityArr = getCity(provinceId);

		//缓存城市信息
		cityCache[provinceId] = cityArr;
	}

	//初始化日志
	initWorklog();

	/**
	 * 验证信息
	 */
	validate({
		"executionDate" : {
			required : true
		},
		"workStatus" : {
			required : true
		}
	});

    // 获取截止提示
    $.ajax({
        url: "?model=engineering_baseinfo_esmdeadline&action=getTips",
        success: function(data) {
            if (data != "") {
                $("#showTips").html(data);
            }
        }
    });
});

//初始化日志
function initWorklog(){
	//缓存内容表
	var importTableObj = $("#importTable");

	importTableObj.yxeditgrid({
		objName : 'esmworklog[detail]',
		event : {
			'reloadData' : function(e){

			}
		},
		title : '日志明细',
		colModel : [{
			display : '项目id',
			name : 'projectId',
			type : 'hidden'
		}, {
			display : '项目编号',
			name : 'projectCode',
			type : 'hidden'
		}, {
			display : '项目名称',
			name : 'projectName',
			width : 130,
			readonly : true,
			process : function($input) {
				var rowNum = $input.data("rowNum");
				$input.yxcombogrid_esmproject({
					nameCol : 'projectName',
					hiddenId : 'importTable_cmp_projectId' + rowNum,
					isDown : true,
					height : 250,
					gridOptions : {
						action : 'logProjectJson',
						param : {'selectstatus' : 'GCXMZT02'},
						isTitle : true,
						event : {
							row_dblclick : function(e, row, data) {
								importTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"projectCode").val(data.projectCode);

								//省份和城市
								importTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"provinceId").val(data.provinceId);
								importTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"province").val(data.province);
								importTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"city").val(data.city);
								importTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"projectEndDate").val(data.planEndDate);
								importTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"maxLogDay").val(data.maxLogDay);
								//变更城市的信息
								changeCity(rowNum,data.provinceId,data.cityId);

								//清空
								clearActivityBatch(rowNum);

								//任务渲染
								initActivityBatch(data.id,rowNum);
							}
						}
					},
					event : {
						'clear' : function(){
							clearActivityBatch(rowNum);
						}
					}
				});
			},
			validation : {
				required : true
			}
		}, {
			display : '项目预计结束日期',
			name : 'projectEndDate',
			type : 'hidden'
		}, {
			display : '任务id',
			name : 'activityId',
			type : 'hidden'
		}, {
			display : '任务名称',
			name : 'activityName',
			width : 130,
			readonly : true,
			validation : {
				required : true
			}
		}, {
			display : '任务预计结束日期',
			name : 'activityEndDate',
			type : 'hidden'
		}, {
			display : '省份',
			name : 'provinceId',
			type : 'select',
			width : 70,
			options : provinceArr,
			value : $("#provinceId").val(),
			event : {
				change : function(){
					var rowNum = $(this).data("rowNum");//行号

					//省份加载
					var province = $(this).find("option:selected").text();
					importTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"province").val(province);

					//变更城市的信息
					changeCity(rowNum,$(this).val());
				}
			}
		}, {
			display : '省份',
			name : 'province',
			type : 'hidden',
			value : $("#province").val()
		}, {
			display : '城市',
			name : 'cityId',
			type : 'select',
			width : 70,
			options : cityArr,
			value : $("#cityId").val(),
			event : {
				change : function(){
                    var rowNum = $(this).data("rowNum");//行号

					//省份加载
					var city = $(this).find("option:selected").text();
					importTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"city").val(city);
				}
			}
		}, {
			display : '城市',
			name : 'city',
			type : 'hidden',
			value : $("#city").val()
		}, {
			display : '国家',
			name : 'country',
			type : 'hidden',
			value : '中国'
		}, {
			display : '国家id',
			name : 'countryId',
			type : 'hidden',
			value : '1'
		}, {
			display : '完成量',
			name : 'workloadDay',
			width : 70,
			event : {
				blur : function(){
                    var rowNum = $(this).data("rowNum");//行号
					calTaskProcessBatch(rowNum,$(this).val());
				}
			}
		}, {
			display : '单位',
			name : 'workloadUnitView',
            tclass : 'readOnlyTxtShort',
            readonly : true,
			width : 80
		}, {
			display : '单位',
			name : 'workloadUnit',
			width : 80,
			type : 'hidden'
		}, {
			display : '项目最大填报期限',
			name : 'maxLogDay',
			type : 'hidden'
		}, {
			display : '进度',
			name : 'workProcess',
			tclass : 'readOnlyTxtShort',
			readonly : true,
			width : 80
		}, {
			display : '本次任务进度',
			name : 'thisActivityProcess',
			type : 'hidden'
		}, {
			display : '本次项目进度',
			name : 'thisProjectProcess',
			type : 'hidden'
		}, {
			display : '投入工作比例(%)',
			name : 'inWorkRate',
			validation : {
				required : true
			},
			width : 90,
			event : {
				blur : function(){
					if(isNaN($(this).val()) || ($(this).val()*1 > 100 || $(this).val()*1 < 0)){
						alert('请输入 0 到 100 以内的数字');
						$(this).val('');
					}
				}
			}
		}, {
			display : '完成情况描述',
			name : 'description',
			width : 160
		}, {
			display : '备注',
			name : 'problem',
			width : 120
		}]
	})
}