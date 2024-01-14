function print_content($remove)
{
    let content = $('html'),
        popup = window.open('', '_blank', 'width=720,height=500');
    $($remove).each(function(index, value) {
        content.find(value).remove();
    });
    let page = content.html();
    popup.document.open();
    popup.document.write('<html><body class="print" onload="window.print()">' + page + '</html>');
    popup.document.close();
    location.reload(true);
}