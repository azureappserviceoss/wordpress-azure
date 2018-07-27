/**
 * Created with JetBrains PhpStorm.
 * User: Max
 * Date: 04/07/13
 * Time: 11:35 AM
 * To change this template use File | Settings | File Templates.
 */
$(document).ready(function() {

    //表单验证 <http://jqueryvalidation.org/documentation/>
    $('#baoming-form').validate({
        rules: {
            name: {
                required: true
            },
            age: {
                required: true,
                number: true
            },
            phone: {
                required: true
            },
            email: {
                required: true,
                email: true
            },
            address: {
                required: true,
                maxlength: 100
            },
            level: {
                required: true
            },
            site: {
                required: true
            },
            legal_info: {
                required: true
            },
            emergency_name: {
                required: true
            },
            emergency_phone: {
                required: true
            }
        },
        messages: {
            name: {
                required: "请填写您的姓名"
            },
            age: {
                required: "请填写您的年龄",
                number: "年龄必须是数字"
            },
            phone: {
                required: "请填写您的联系电话"
            },
            email: {
                required: "请填写邮箱",
                email: 'Email地址不正确'
            },
            address: {
                required: '请填写地址',
                maxlength: '地址长度应小于50个字符'
            },
            level: {
                required: '请选择您的麻将基本水平'
            },
            site: {
                required: '请选择您的培训地点'
            },
            legal_info: {
                required: '您必须同意我们的《免责声明》方可报名'
            },
            emergency_name: {
                required: "请填写紧急联系人姓名"
            },
            emergency_phone: {
                required: "请填写紧急联系人电话"
            }
        },
        errorElement: 'em',
        errorPlacement: function(error, element) {
            element.parent().find('.display_error_here').append(error);
        }
    });

});