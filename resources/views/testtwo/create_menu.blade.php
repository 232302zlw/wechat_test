<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>创建菜单</title>
</head>
<body>
    <h1>&nbsp;</h1>
    <form action="{{url('sign/save_menu')}}" method="post">
        @csrf
        <table align="center">
            <tr>
                <td>一级菜单：</td>
                <td>
                    <input type="text" name="name1">
                </td>
            </tr>
            <tr><td><br></td></tr>
            <tr>
                <td>二级菜单：</td>
                <td>
                    <input type="text" name="name2">
                </td>
            </tr>
            <tr><td><br></td></tr>
            <tr>
                <td>菜单类型：</td>
                <td>
                    <select name="type">
                        <option value="1">click</option>
                        <option value="2">view</option>
                    </select>
                </td>
            </tr>
            <tr><td><br></td></tr>
            <tr>
                <td>唯一键名：</td>
                <td>
                    <input type="text" name="event_value">
                </td>
            </tr>
            <tr><td><br></td></tr>
            <tr>
                <td colspan="2" align="center">
                    <button>提交</button>
                </td>
            </tr>
        </table>
    </form>
</body>
</html>
