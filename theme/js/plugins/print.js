function print_content()
{
    let content = $('html'),
        popup = window.open('', '_blank', 'width=720,height=500');
    content.find('#top-header').remove();
    content.find('#footer').remove();
    let page = content.html();
    popup.document.open();
    popup.document.write('<html><body class="print" onload="window.print()">' + page + '</html>');
    popup.document.close();
    location.reload(true);
}