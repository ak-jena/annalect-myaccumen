<html>
    <head>
        <meta content="text/html; charset=UTF-8" http-equiv="content-type">
    </head>
    <body>
        <table cellspacing="0" cellpadding="10" style="color:#666;font:13px Arial;line-height:1.4em;width:100%;">
            <tbody>
                <tr>
                    <td style="color:#959595;font-size:22px;border-bottom: 1px solid #959595;">
                         <?php echo $data['header']; ?>
                    </td>
                </tr>
                <tr>
                    <td style="color:#FF0000;font-size:20px;padding-top:5px;">
                        <?php if(isset($data['description'])){echo $data['description'];} ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php echo $content ?>
                    </td>
                </tr>
                <tr>
                    <td style="padding:15px 20px;text-align:right;padding-top:5px;border-top:solid 1px #dfdfdf">
                        <a href="http://www.accuenmedia.com/"><img src="{{asset('/img/accuen.png')}}" /></a>
                    </td>
                </tr>
            </tbody>
        </table>
    </body>
</html>