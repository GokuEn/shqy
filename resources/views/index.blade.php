<!DOCTYPE HTML>
<html>
<head>
    <title>完整demo</title>
    <script type="text/javascript" src="js/jquery-3.4.1.js"></script>
</head>
<body>
    <form action="/login" method="post">
    @if (count($errors) > 0 )
    <div name='errors'>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <p>code</br><input type="text" name="code" value=""></p>
    <input type="submit" value="提交">
    {{csrf_field()}}
    </form>
</body>
</html>