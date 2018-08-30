$(document).ready(function() {
    /**
     * 验证信息
     */
    validate({
        "personLevel" : {
            required : true
        }
    });
    //设置选项
    setSelect('customPrice');
});