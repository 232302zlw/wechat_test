<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>标签列表</title>
</head>
<body>
    <br>
    <h2 align="center">标签列表</h2>
    <br>
    <table align="center" width="700px" border="1">
        <tr align="center">
            <td>序号</td>
            <td>名称</td>
            <td>数量</td>
            <td>操作</td>
        </tr>
        @foreach($info as $v)
        <tr align="center">
            <td>{{$v['id']}}</td>
            <td>{{$v['name']}}</td>
            <td>{{$v['count']}}</td>
            <td>
                <a href="{{url('wechat/user_list')}}?tagid={{$v['id']}}">用户打标签</a>
                <a href="{{url('wechat/send_message')}}?tagid={{$v['id']}}">发送消息</a>
            </td>
        </tr>
        @endforeach
    </table>
</body>
</html>
