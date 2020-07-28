// 自定义组件目录
var table = layui.table;

var tableTools = {
    render: function(url, cols) {
        table.render({
            elem: '#dataTable',
            url: url,
            page: true,
            cols: [cols]
        });
    }
};

