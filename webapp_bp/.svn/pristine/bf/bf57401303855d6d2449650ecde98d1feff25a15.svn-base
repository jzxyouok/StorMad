/**
 * placeholder兼容IE
 */
(function placeholderSupport() {
    if (!('placeholder' in document.createElement('input'))) {
        /**
         * 对input框和textarea的特殊处理
         */
        $('input[placeholder]:not([type="password"]),textarea[placeholder]').each(function() {
            var self = this,
                text = $(self).attr('placeholder');

            if ($(self).val() === '') {
                $(self).val(text).addClass('placeholder');
            }
            $(self).focus(function() {
                if ($(self).val() === text) {
                    $(self).val('').removeClass('placeholder');
                }
            }).blur(function() {
                if ($(self).val() === '') {
                    $(self).val(text).addClass('placeholder');
                }
            }).closest('form').submit(function() {
                if ($(self).val() === text) {
                    $(self).val('');
                }
            });
        });
        /**
         * 对password框的特殊处理
         * 1.创建一个text框
         * 2获取焦点和失去焦点的时候切换
         */
        $('input[type="password"][placeholder]').each(function(index, val) {
            var self = this,
                text = $(self).attr('placeholder');
            $(self).after('<input id="pwdPlaceholder' + index + '" type="text" value=' + text + ' autocomplete="off" class="placeholder" />');
            var pwdPlaceholder = $('#pwdPlaceholder' + index);
            pwdPlaceholder.show();
            $(self).hide();

            pwdPlaceholder.focus(function() {
                $(this).hide();
                $(self).show();
                $(self).focus();
            });

            $(self).blur(function() {
                if ($(self).val() == '') {
                    pwdPlaceholder.show();
                    $(self).hide();
                }
            });
        });
    }
})();
