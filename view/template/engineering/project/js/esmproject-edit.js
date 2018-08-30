$(document).ready(function () {
    /**
     * 验证信息
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

    //初始化省份城市信息
    initCity();
});