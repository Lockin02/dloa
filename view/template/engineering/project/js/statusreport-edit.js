// ��֤����
var validateArr = {
    description : {
        required : true
    },
    nextPlan : {
        required : true
    }
};

$(function(){
    //��Ŀ��չ״����
    initWeekStatus();

    //��ĿԤ��
    initWeekWarning();

    /**
     * ��֤��Ϣ
     */
    validate(validateArr);
});