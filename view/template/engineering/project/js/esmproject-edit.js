$(document).ready(function () {
    /**
     * ��֤��Ϣ
     */
    validate({
        projectName: {
            required: true
        },
        outsourcing: {
            required: true
        },
        country: {
            required: true
        },
        province: {
            required: true
        },
        city: {
            required: true
        },
        workDescription: {
            required: true
        }
    });

    //��ʼ��ʡ�ݳ�����Ϣ
    initCity();
});