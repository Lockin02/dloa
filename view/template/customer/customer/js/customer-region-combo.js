$(function() {
			/* 选择区域负责人 */
			$("#AreaName").yxcombogrid_area({
						nameCol : 'AreaName',
						hiddenId : 'AreaId',
						gridOptions : {
							showcheckbox : false,
							event : {
                                'row_dblclick' : function(e, row, data) {
                                    $("#customer_areaLeader_id").val(data.areaPrincipalId);
									$("#AreaName").val(data.areaName);
									$("#customer_areaLeader").val(data.areaPrincipal);
									$("#AreaId").val(data.id);
								}
			}
						}
					});

		});
		//验证手机
function mob(){
	    var tel = $("#Mobile").val();
	    var t = /(\d{11})|^((\d{7,8})|(\d{4}|\d{3})-(\d{7,8})|(\d{4}|\d{3})-(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1})|(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1}))$/ ;
	    if(t.test(tel) == false){
	        alert ("请正确填写电话信息！");
	        $("#Mobile").val("");
	       $("#Mobile").focus();
	    }

	}
//验证固定电话
	function tell(){
	   var tel= $('#Tell').val().replace(/(^[0-9]{3,4}\-[0-9]{7,8}$)|(^[0-9]{7,8}$)|(^\([0-9]{3,4}\)[0-9]{3,8}$)|(^0{0,1}13[0-9]{9}$)/)*1;
	   if(!tel){
	        alert ("请正确填写电话信息！");
	        $("#Tell").val("");
	       $("#Tell").focus();
	    }

	}

//验证邮箱
	function email(){
	     var Email = $("#Email").val();
	     var E =  /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
         if(E.test(Email) == false){
             alert("请填写正确的邮箱信息");
             $("#Email").val("");
             $("#Email").focus();
         }
	}
	//验证邮编
	function Post(){

	   var post= $('#PostalCode').val();
	   var  P=/^[0-9]{6}$/;
	   if(P.test(post)==false){
	        alert ("请正确填写邮编信息！");
	        $("#PostalCode").val("");
	       $("#PostalCode").focus();
	    }

	}

	//验证传真
	function fax(){
	   var faxx= $('#Fax').val().replace(/(^[0-9]{3,4}\-[0-9]{7,8}$)|(^[0-9]{7,8}$)|(^\([0-9]{3,4}\)[0-9]{3,8}$)|(^0{0,1}13[0-9]{9}$)/)*1;
	   if(!faxx){
	        alert ("请正确填写传真信息！");
	        $("#Fax").val("");
	       $("#Fax").focus();
	    }

	}
