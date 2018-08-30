// 验证内容
var validateArr = {
    description : {
        required : true
    },
    nextPlan : {
        required : true
    }
};

$(function(){
    //项目进展状况表
    initWeekStatus();

    //项目预警
    initWeekWarning();

    /**
     * 验证信息
     */
    validate(validateArr);
});