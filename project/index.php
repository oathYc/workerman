
<input type="text" value="" id="sub" placeholder="请输入数据" />
<textarea id="history"></textarea>
<button onclick="submitContent()">提交</button>

<script>
    // ws = new WebSocket('ws://127.0.0.1:8071');
    ws = new WebSocket('ws://59.110.156.117:8071');
    ws.onopen = function(){
        // var his = document.getElementById('history').value;
        // his += '用户进入'+"\n";
        // document.getElementById('history').value = his;
    };
    ws.onmessage = function(e){
        var data = JSON.parse(e.data);
        var str = data.userId+'：'+data.content;
        // console.log(data);
        var his = document.getElementById('history').value;
        his += str+"\n";
        document.getElementById('history').value = his;
    };
    // ws.onclose = function(){
    //     ws.reconnect();
    // };
    ws.error = function(){
      alert('连接出错，请刷新重连');
    };
    function submitContent(){
        var sub = document.getElementById('sub').value;
        var data = {'userId':'oathYc','content':sub};
        data = JSON.stringify(data);
        ws.send(data);
        // var his = document.getElementById('history').value;
        // his += '用户发言：'+sub+"\n";
        // document.getElementById('history').value = his;
    }
</script>