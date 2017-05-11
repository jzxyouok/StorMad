/**
 * 
 */
var yonghui = {
    contextPath: 'http://testbp.stormad.cn', //域名
    pageSize: 10, //每页数据数量
    groups: 5 //显示页码数量
}

String.prototype.trim = function() {
    return this.replace(/(^\s*)|(\s*$)/g, '');
}

//关闭click.bs.dropdown.data-api事件，一级菜单恢复href属性
$(document).ready(function() {
    $(document).off('click.bs.dropdown.data-api');
});
