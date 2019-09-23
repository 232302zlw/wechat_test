<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>推送消息</title>
</head>
<body>
    <form action="{{url('wechat/send_message_do')}}" method="post">
        @csrf
        <table align="center" width="500px">
            <tr>
                <td>消息</td>
                <td>
                    <input type="hidden" name="tag_id" value="{{$tagid}}">
                    <textarea name="content" id="" cols="30" rows="10"></textarea>
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <button>提交</button>
                </td>
            </tr>
        </table>
    </form>
</body>
</html>
