<html>
<head>
	<meta content="text/html; charset=UTF-8" http-equiv="content-type">
</head>
<body>
<table cellspacing="0" cellpadding="10" style="color:#666;font:13px Arial;line-height:1.4em;width:100%;">
	<tbody>
            <tr>
                <td style="color:#00a65a;font-size:24px;border-bottom: 1px solid #605ca8;">
                    Hi there,
                </td>
            </tr>
            <tr>
                <td style="color:#FF0000;font-size:20px;padding-top:5px;">
                </td>
            </tr>
            <tr>
                <td>
                    <p> {{ $campaign->grids->first()->user->name }} has completed the Targeting Grid for {{ $campaign->brief->client->name }}, {{ $campaign->brief->campaign_name }}.</p>
                    <p>Access the grid on the link below to view the targeting grid.</p>
                    <p>
                        <a href="{{ route('workflow', ['campaign_id' => $campaign->id]) }}">
                            {{ route('workflow', ['campaign_id' => $campaign->id]) }}
                        </a>
                    </p>

                    <p>Regards,<br>
                    Programmatic Team
                    </p>                
                </td>
            </tr>
            <br>
            <tr>
                <td style="padding:15px 20px;text-align:right;padding-top:20px;border-top:solid 1px #605ca8">

                </td>
            </tr>
    </tbody>
</table>
</body>
</html>