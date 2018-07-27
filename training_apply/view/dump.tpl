<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>报名者信息导出</title>

<link href="/training_apply/static/style/dump.css" rel="stylesheet" type="text/css" />

</head>



<body>

<h1>报名者信息导出</h1>

<form action="{$form_action}" method="post">

    <div>
        <label>导出指定日期之后的数据</label>
        {html_select_date field_order='YMD' start_year='2013'}
    </div>

    <div>
        <label>密码</label>
        <input type='password' name='password' value="" />
    </div>

    <div>
        <button type="submit">立刻导出</button>
    </div>


</form>


</body>

</html>

