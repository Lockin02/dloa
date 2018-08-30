// 省市
$(document).ready(function() {
	var countryUrl = "?model=system_procity_country&action=pageJson";// 获取国家的URL
	var provinceUrl = "?model=system_procity_province&action=pageJson"; // 获取省的URL
	var cityUrl = "?model=system_procity_city&action=pageJson"; // 获取市的URL
    $('#country').append($("<option value=''>").html("请选择国家"));
	$('#province').append($("<option value=''>").html("请选择省份"));
	$('#city').append($("<option value=''>").html("请选择城市"));
	/* 获取国家的方法 */
	function getCountrys(data) {
		var o = eval("(" + data + ")");
		var countryArr = o.collection;
		for (var i = 0, l = countryArr.length; i < l; i++) {
			var country = countryArr[i];
			var option = $("<option>").val(country.id)
					.html(country.countryName);
			$('#country').append(option);
		}
	}
	/* 获取省的方法 */
	function getProvinces(data) {
		var o = eval("(" + data + ")");
		var provinceArr = o.collection;
		for (var i = 0, l = provinceArr.length; i < l; i++) {
			var province = provinceArr[i];
			var option = $("<option>").val(province.id)
					.html(province.provinceName);
			$('#province').append(option)
		}
	}

	/* 获取市的方法 */
	function getCitys(data) {
		// $('#city').html("");
		var o = eval("(" + data + ")");
		var cityArr = o.collection;
		for (var i = 0, l = cityArr.length; i < l; i++) {
			var city = cityArr[i];
			var option = $("<option>").val(city.id).html(city.cityName);
			$('#city').append(option);
		}
	}

                 //获取国家
                  $.ajax({
					    type : 'POST',
					    url : countryUrl,
					    data:{
							pageSize : 999
					    },
					    async: false,
					    success : function(data){
					    	$('#contry').children().remove("option[value!='']");
							$('#province').children().remove("option[value!='']");
							$('#city').children().remove("option[value!='']");
							getCountrys(data);
							if ($('#country').attr('val')) {
								$('#country').val($('#country').attr('val'));
							}else{
								$('#country').val(1);
								//$('#countryName').val('中国');
							}
							$('#country').trigger('change');
						}
					});
					//获取省份
					$.ajax({
					    type : 'POST',
					    url : provinceUrl,
					    data:{
					        countryId : 1,
							pageSize : 999
					    },
					    async: false,
					    success : function(data){
							$('#province').children().remove("option[value!='']");
							$('#city').children().remove("option[value!='']");
							getProvinces(data);
							$('#provinceName').val("");
							$('#cityName').val("");
							if ($('#province').attr('val')) {
								$('#province').val($('#province').attr('val'));
								$('#province').trigger('change');
							}
						}
					});
    // 选择国家改变省
	$('#country').change(function() {
			$('#countryName').val($(this).find("option:selected").text());
			var countryId = $(this).val();
			if(countryId==""){
				$('#provinceName').val("");
				$('#cityName').val("");
			}else{
			     $.ajax({
					    type : 'POST',
					    url : provinceUrl,
					    data:{
					       countryId : countryId,
						   pageSize : 999
					    },
					    async: false,
					    success : function(data){
					    	    $('#province').children() .remove("option[value!='']");
								$('#city').children().remove("option[value!='']");
								getProvinces(data);
								$('#provinceName').val("");
								$('#cityName').val("");
								if ($('#province').attr('val')) {
									$('#province').val($('#province').attr('val'));
									$('#province').trigger('change');
								}
					    }
					});
			}
			if ($(this).val() == 1) {
					validate({
						"province" : {
							required : true
						},
						"city" : {
							required : true
						}
					})
				} else {
					$('#province').removeClass("validate[required]");
					$('#city').removeClass("validate[required]");
				}

		});
	// 省的选择改变，获取市
	$('#province').change(function() {
				$('#provinceName').val($(this).find("option:selected").text());
				var provinceId = $(this).val();
				if(provinceId==""){    //判断是否选择了省份，如果没有选中，刚省份名称为空   add by suxc 2011-08-22
					$('#provinceName').val("");
					$('#city').children().remove("option[value!='']");
					$('#cityName').val("");
				}else{
					$.ajax({
					    type : 'POST',
					    url : cityUrl,
					    data:{
					       provinceId : provinceId,
						   pageSize : 999
					    },
					    async: false,
					    success : function(data){
								$('#city').children().remove("option[value!='']");
								getCitys(data);
								$('#cityName').val("");
								if ($('#city').attr('val')) {
									$('#city').val($('#city').attr('val'));
									$('#city').trigger('change');
								}
					    }
					});
				}
			});

	$('#city').change(function() {
//		alert($(this).find("option:selected").text())
		$('#cityName').val("");
		if($(this).val()==""){   //判断是否选择了城市  add by suxc 2011-08-22
			$('#cityName').val("");
		}else{
			$('#cityName').val($(this).find("option:selected").text());
		}
			});


	//编辑页处理省市
	var countryId = $("#contryView").val();
	var proId = $("#provinceView").val();
	var cityId = $("#cityView").val();
	$("#country").val(countryId);// 所属国家Id
	$("#country").trigger("change");
	$("#province").val(proId);// 所属省份Id
	$("#province").trigger("change");
	$("#city").val(cityId);// 城市ID
	$("#city").trigger("change");

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
});

// 组织机构选择
$(function() {
	$("#personName").yxselect_user({
				hiddenId : 'personId',
				isGetDept:[true,"deptId","deptName"]
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
		},
		"cuuntry" : {
			required : true
		},
		"province" : {
			required : true
		},
		"city" : {
			required : true
		},
		"customerType" : {
			required : true
		},
		"exeDeptName" : {
			required : true
		}
	});

	//是否启用
	$('select[name="saleperson[isUse]"] option').each(function() {
		if( $(this).val() == $("#isUseName").val() ){
			$(this).attr("selected","selected'");
		}
	});

    //执行部门
    $("#customerTypeName").yxcombogrid_datadict({
        height : 250,
        valueCol : 'dataCode',
        hiddenId : 'customerType',
        gridOptions : {
            isTitle : true,
            param : {"parentCode":"KHLX"},
            showcheckbox : true,
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
