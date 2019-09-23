<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>创建标签</title>
</head>
<body>
    <form action="{{url('wechat/save_tag')}}" method="post">
        @csrf
        <table align="center">
            <tr>
                <td>
                    <input type="text" name="name">
                </td>
            </tr>
            <tr>
                <td>
                    <button>提交</button>
                </td>
            </tr>
        </table>
    </form>
</body>
</html>
