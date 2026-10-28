<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ajax - Danh sách lớp</title>
    <link href="../style/style.css" type="text/css" rel="stylesheet"></link>
    <style>
        body { font-size: 11pt; font-family: Arial, sans-serif; }
        div { padding: 3px 5px; font: normal 13pt Arial; }
        table { background-color: #eaeaea; border-collapse: collapse; margin-top: 10px; width: 100%; max-width: 600px; }
        td { font: normal 11pt Arial; padding: 6px 10px; border: 1px solid #ccc; }
        .hd { background-color: navy; color: white; font-weight: bold; }
    </style>
    <script>
        function sendajax() {
            var lop = document.getElementById("lop").value;
            var objds = document.getElementById("ds");

            if (!lop) {
                objds.innerHTML = "Vui lòng chọn một lớp";
                return;
            }

            var xml = new XMLHttpRequest();
            xml.onreadystatechange = function() {
                if (xml.readyState == 4 && xml.status == 200) {
                    objds.innerHTML = xml.responseText;
                }
            };
            var url = "ds.php?lop=" + encodeURIComponent(lop);
            xml.open("GET", url, true);
            xml.send();
        }
    </script>
</head>
<body>
    <h3>In danh sách theo từng lớp</h3>
    <?php
    include("../inc/connect.inc");

    function initClass($conn) {
        $sql = "SELECT DISTINCT lop FROM sinhvien";
        $rs = mysqli_query($conn, $sql);

        $str = "Chọn lớp: <select id='lop' onChange='sendajax();'>";
        $str .= "<option value=''>-- Chọn lớp --</option>";
        while ($row = mysqli_fetch_array($rs)) {
            $str .= "<option value='{$row['lop']}'>{$row['lop']}</option>";
        }
        $str .= "</select>";

        echo $str;
    }

    initClass($con);
    ?>
    <hr>
    <div id="ds">Danh sách</div>
</body>
</html>
