var userLevelArr = [];//技术等级数组

/**
 * 添加数组到下拉
 * @param {} code
 * @param {} selectId
 */
function addDataToSelectCus(data, selectId) {
	for (var i = 0, l = data.length; i < l; i++) {
		$("#" + selectId).append("<option value='" + data[i].levelName + "'>"
				+ data[i].levelName + "</option>");
	}
}

$(document).ready(function() {
    $.formValidator.initConfig({
    	formid: "form1",
        //autotip: true,
        onerror: function(msg) {
            //alert(msg);
        },
        onsuccess: function() {
        }
    });

     /**验证员工名称 **/
     $("#userName").formValidator({
         onshow:"请输入员工名称",
         onfocus:"员工名称至少2个字符，最多50个字符",
         oncorrect:"OK!"
     }).inputValidator({
         min:2,
         max:50,
         empty:{
            leftempty:false,
            rightempty:false,
            emptyerror:"员工名称两边不能有空符号"
         },
         onerror:"您输入的名称不合法，请重新输入"
     });

     /**所属办事处验证**/
     $("#officeName").formValidator({
     	 onshow:"请输入办事处名称",
         onfocus:"办事处至少2个字符，最多50个字符",
         oncorrect:"OK!"
     }).inputValidator({
         min:2,
         max:50,
         empty:{
            leftempty:false,
            rightempty:false,
            emptyerror:"办事处名称两边不能有空符号"
         },
         onerror:"您输入的办事处不合法，请重新输入"
     });

     // 产品线数据
	userLevelArr = getDataCus('','index1.php?model=engineering_assessment_assPeopleLevel&action=pageJson','levelName');
	if (!$("#contNumber").val()) {
		addDataToSelectCus(userLevelArr, 'userLevel','id','levelName');
	}

	// 等级下拉选中
	$("#userLevel").val(userLevel);
});

