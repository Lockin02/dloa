/**
 * 常用验证规则：
 * required：值不可以为空
 * length[0,100] ：文字允许长度
 * confirm[fieldID] ：匹配其他的表单元素的值，fieldID就是其他表单元素的id，这个主要用于再次确认密码
 * telephone ：数据格式要求符合电话格式
 * email ： 数据格式要求符合email 格式
 * onlyNumber ：只允许输入数字
 * noSpecialCaracters ：不允许出现特殊字符
 * onlyLetter ： 只能是字母
 * date ：必须符合日期格式YYYY-MM-DD
 * money :最大金额 999999999999999.999999，最小金额 0，格式 XXXX（整数位不超过14位，小数位不超过6位）
 * percentage ： 0到100，格式 XX 或者 格式XX.xx
 * percentageNum : 只可输入整数或小数
 */

(function ($) {
    $.fn.validationEngineLanguage = function () {
    };

    $.validationEngineLanguage = {
        newLang: function () {
            $.validationEngineLanguage.allRules = {
                "required": { // Add your regex rules here, you can take
                    // telephone as an example
                    "regex": "none",
                    "alertText": "* 该项为必填项.",
                    "alertTextCheckboxMultiple": "* 请选择一个单选框.",
                    "alertTextCheckboxe": "* 请选择一个复选框."
                },
                "blank": {
                    "regex": "",
                    "alertText": "* 所填内容两边不能含有空格."
                },
                "length": {
                    "regex": "none",
                    "alertText": "* 长度必须在 ",
                    "alertText2": " 至 ",
                    "alertText3": " 之间."
                },
                "maxCheckbox": {
                    "regex": "none",
                    "alertText": "* 最多选择 ",// 官方文档这里有问题
                    "alertText2": " 项."
                },
                "minCheckbox": {
                    "regex": "none",
                    "alertText": "* 至少选择 ",
                    "alertText2": " 项."
                },
                "confirm": {
                    "regex": "none",
                    "alertText": "* 两次输入不一致,请重新输入."
                },
                // 从telephone往下，除了validate2fields，都需要写成如：custom[telephone]的样式
                // custom与funcCall的差别在于，custom是使用正则表达式进行验证即可，而funcCall需要写js函数来进行验证。
                "datelogic": {
                    "nname": "datelogic",
                    "alertText": "* 当前时间选择不合理"
                },
                "telephone": {
                    "regex": "/^(0[0-9]{2,3}\-)?([2-9][0-9]{6,7})+(\-[0-9]{1,4})?$/",
                    "alertText": "* 请输入有效的电话号码,如:010-29292929."
                },
                "mobilephone": {
                    "regex": "/(^0?[1][34578][0-9]{9}$)/",
                    "alertText": "* 请输入有效的手机号码."
                },
                "email": {
                    "regex": "/^[a-zA-Z0-9_\.\-]+\@([a-zA-Z0-9\-]+\.)+[a-zA-Z0-9]{2,4}$/",
                    "alertText": "* 请输入有效的邮件地址."
                },
                "date": {
                    "regex": "/^(([0-9]{3}[1-9]|[0-9]{2}[1-9][0-9]{1}|[0-9]{1}[1-9][0-9]{2}|[1-9][0-9]{3})-(((0[13578]|1[02])-(0[1-9]|[12][0-9]|3[01]))|((0[469]|11)-(0[1-9]|[12][0-9]|30))|(02-(0[1-9]|[1][0-9]|2[0-8]))))|((([0-9]{2})(0[48]|[2468][048]|[13579][26])|((0[48]|[2468][048]|[3579][26])00))-02-29)$/",
                    "alertText": "* 请输入有效的日期,如:2008-08-08."
                },
                "ip": {
                    "regex": "/^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/",
                    "alertText": "* 请输入有效的IP."
                },
                "chinese": {
                    "regex": "/^[\u4e00-\u9fa5]+$/",
                    "alertText": "* 请输入中文."
                },
                "dicChinese": {
                    "regex": "/^[\u4e00-\u9fa5]+$/",
                    "alertText": "* 请选择上级名称."
                },
                "url": {
                    "regex": "/^[a-zA-z]:\\/\\/[^s]$/",// 这些验证需加强
                    "alertText": "* 请输入有效的网址."
                },
                "zipcode": {
                    "regex": "/^[1-9]\d{5}$/",
                    "alertText": "* 请输入有效的邮政编码."
                },
                "qq": {
                    "regex": "/^[1-9]\d{4,9}$/",
                    "alertText": "* 请输入有效的QQ号码."
                },
                "onlyNumber": {
                    "regex": "/^[0-9]+$/",
                    "alertText": "* 请输入数字（正整数,不要出现小数或其他符号）."
                },
                "onlyLetter": {
                    "regex": "/^[a-zA-Z]+$/",
                    "alertText": "* 请输入英文字母."
                },
                "noSpecialCaracters": {
                    "regex": "/^[0-9a-zA-Z]+$/",
                    "alertText": "* 请输入英文字母和数字."
                },
                "ajaxName": {
                    "file": "validateUser.php",
                    "alertText": "* 数据重复",
                    "alertTextOk": "* 数据可用",
                    "alertTextLoad": "* 校验中.."
                },
                // 扩展的验证有待提高
                "money": {
                    "regex": "/^((([1-9]{1}\\d{0,14}))|([0]{1}))([.][0-9]{1,6})?$/",
                    "alertText": "* 请输入有效的金额."
                },
                "percentage": {
//					"regex" : "/^([0-9]|[1-9][0-9]|100)((\\.(\\d){2}))?$/",
                    "regex": "/^(([0-9]|[1-9][0-9])(\\.(\\d){1,2})?|100|100.0|100.00)?$/",
                    "alertText": "* 请输入有效的数字.范围0~100.00,如：1，1.00"
                },
                "percentageNum": {
                    "regex": "/^(?:[1-9][0-9]*|0)(?:\.[0-9]+)?$/",
                    "alertText": "* 请输入数字或小数"
                },
                // 校验重复性，如编码
                "ajaxCheck": {
                    "nname": "ajaxRepeat",
                    "alertText": "* 输入重复数据."
                },
                // 校验重复性，如编码
                "numberA": {
                    "regex": "/^(?:[1-9][0-9]?|100)$/",
                    "alertText": "* 输入100以内的正整数."
                }
            }
        }
    }
})(jQuery);

/**
 * ajax校验
 *
 * @param {}
 *            param
 */
$.fn.ajaxCheck = function (param) {
    var id = $(this).attr("id");
    var p = {
        "alertText": "* 数据重复",
        "alertTextOk": "* 数据可用",
        "alertTextLoad": "* 校验中..",
        file: param.url
    };
    p = $.extend(true, p, param);
    $.validationEngineLanguage.allRules[id + 'AjaxName'] = p;
    validateSingle($(this), {
        ajax: id + 'AjaxName'
    });
    $("form").validationEngine();
};

$(document).ready(function () {
    $.validationEngineLanguage.newLang()
});