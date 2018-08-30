/**
 * ������֤����
 * required��ֵ������Ϊ��
 * length[0,100] ������������
 * confirm[fieldID] ��ƥ�������ı�Ԫ�ص�ֵ��fieldID����������Ԫ�ص�id�������Ҫ�����ٴ�ȷ������
 * telephone �����ݸ�ʽҪ����ϵ绰��ʽ
 * email �� ���ݸ�ʽҪ�����email ��ʽ
 * onlyNumber ��ֻ������������
 * noSpecialCaracters ����������������ַ�
 * onlyLetter �� ֻ������ĸ
 * date ������������ڸ�ʽYYYY-MM-DD
 * money :����� 999999999999999.999999����С��� 0����ʽ XXXX������λ������14λ��С��λ������6λ��
 * percentage �� 0��100����ʽ XX ���� ��ʽXX.xx
 * percentageNum : ֻ������������С��
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
                    "alertText": "* ����Ϊ������.",
                    "alertTextCheckboxMultiple": "* ��ѡ��һ����ѡ��.",
                    "alertTextCheckboxe": "* ��ѡ��һ����ѡ��."
                },
                "blank": {
                    "regex": "",
                    "alertText": "* �����������߲��ܺ��пո�."
                },
                "length": {
                    "regex": "none",
                    "alertText": "* ���ȱ����� ",
                    "alertText2": " �� ",
                    "alertText3": " ֮��."
                },
                "maxCheckbox": {
                    "regex": "none",
                    "alertText": "* ���ѡ�� ",// �ٷ��ĵ�����������
                    "alertText2": " ��."
                },
                "minCheckbox": {
                    "regex": "none",
                    "alertText": "* ����ѡ�� ",
                    "alertText2": " ��."
                },
                "confirm": {
                    "regex": "none",
                    "alertText": "* �������벻һ��,����������."
                },
                // ��telephone���£�����validate2fields������Ҫд���磺custom[telephone]����ʽ
                // custom��funcCall�Ĳ�����ڣ�custom��ʹ��������ʽ������֤���ɣ���funcCall��Ҫдjs������������֤��
                "datelogic": {
                    "nname": "datelogic",
                    "alertText": "* ��ǰʱ��ѡ�񲻺���"
                },
                "telephone": {
                    "regex": "/^(0[0-9]{2,3}\-)?([2-9][0-9]{6,7})+(\-[0-9]{1,4})?$/",
                    "alertText": "* ��������Ч�ĵ绰����,��:010-29292929."
                },
                "mobilephone": {
                    "regex": "/(^0?[1][34578][0-9]{9}$)/",
                    "alertText": "* ��������Ч���ֻ�����."
                },
                "email": {
                    "regex": "/^[a-zA-Z0-9_\.\-]+\@([a-zA-Z0-9\-]+\.)+[a-zA-Z0-9]{2,4}$/",
                    "alertText": "* ��������Ч���ʼ���ַ."
                },
                "date": {
                    "regex": "/^(([0-9]{3}[1-9]|[0-9]{2}[1-9][0-9]{1}|[0-9]{1}[1-9][0-9]{2}|[1-9][0-9]{3})-(((0[13578]|1[02])-(0[1-9]|[12][0-9]|3[01]))|((0[469]|11)-(0[1-9]|[12][0-9]|30))|(02-(0[1-9]|[1][0-9]|2[0-8]))))|((([0-9]{2})(0[48]|[2468][048]|[13579][26])|((0[48]|[2468][048]|[3579][26])00))-02-29)$/",
                    "alertText": "* ��������Ч������,��:2008-08-08."
                },
                "ip": {
                    "regex": "/^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/",
                    "alertText": "* ��������Ч��IP."
                },
                "chinese": {
                    "regex": "/^[\u4e00-\u9fa5]+$/",
                    "alertText": "* ����������."
                },
                "dicChinese": {
                    "regex": "/^[\u4e00-\u9fa5]+$/",
                    "alertText": "* ��ѡ���ϼ�����."
                },
                "url": {
                    "regex": "/^[a-zA-z]:\\/\\/[^s]$/",// ��Щ��֤���ǿ
                    "alertText": "* ��������Ч����ַ."
                },
                "zipcode": {
                    "regex": "/^[1-9]\d{5}$/",
                    "alertText": "* ��������Ч����������."
                },
                "qq": {
                    "regex": "/^[1-9]\d{4,9}$/",
                    "alertText": "* ��������Ч��QQ����."
                },
                "onlyNumber": {
                    "regex": "/^[0-9]+$/",
                    "alertText": "* ���������֣�������,��Ҫ����С�����������ţ�."
                },
                "onlyLetter": {
                    "regex": "/^[a-zA-Z]+$/",
                    "alertText": "* ������Ӣ����ĸ."
                },
                "noSpecialCaracters": {
                    "regex": "/^[0-9a-zA-Z]+$/",
                    "alertText": "* ������Ӣ����ĸ������."
                },
                "ajaxName": {
                    "file": "validateUser.php",
                    "alertText": "* �����ظ�",
                    "alertTextOk": "* ���ݿ���",
                    "alertTextLoad": "* У����.."
                },
                // ��չ����֤�д����
                "money": {
                    "regex": "/^((([1-9]{1}\\d{0,14}))|([0]{1}))([.][0-9]{1,6})?$/",
                    "alertText": "* ��������Ч�Ľ��."
                },
                "percentage": {
//					"regex" : "/^([0-9]|[1-9][0-9]|100)((\\.(\\d){2}))?$/",
                    "regex": "/^(([0-9]|[1-9][0-9])(\\.(\\d){1,2})?|100|100.0|100.00)?$/",
                    "alertText": "* ��������Ч������.��Χ0~100.00,�磺1��1.00"
                },
                "percentageNum": {
                    "regex": "/^(?:[1-9][0-9]*|0)(?:\.[0-9]+)?$/",
                    "alertText": "* ���������ֻ�С��"
                },
                // У���ظ��ԣ������
                "ajaxCheck": {
                    "nname": "ajaxRepeat",
                    "alertText": "* �����ظ�����."
                },
                // У���ظ��ԣ������
                "numberA": {
                    "regex": "/^(?:[1-9][0-9]?|100)$/",
                    "alertText": "* ����100���ڵ�������."
                }
            }
        }
    }
})(jQuery);

/**
 * ajaxУ��
 *
 * @param {}
 *            param
 */
$.fn.ajaxCheck = function (param) {
    var id = $(this).attr("id");
    var p = {
        "alertText": "* �����ظ�",
        "alertTextOk": "* ���ݿ���",
        "alertTextLoad": "* У����..",
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