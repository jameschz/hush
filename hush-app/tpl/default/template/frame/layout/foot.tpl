<div style="clear:both"></div>
<script type="text/javascript">
function openWindow(url){
    var title = arguments[1] || 'Window';
    var index = layer.open({
        type: 2,
        title: title,
        content: url,
        maxmin: false,
        end:function(){
            window.location.reload();
        }
    });
    layer.full(index);
}
function openBox(url){
    layer.open({
        type: 1,
        title: false,
        content: url,
        end:function(){
            window.location.reload();
        }
    });
}
function close(){
    layer.closeAll();
}
</script>
</body>
</html>