<table>
    <thead>
        <tr style="background-color:#066b5e;text-align:left;">
            <th style="text-align:left; font-size: 16px; font-weight: 700; vertical-align: middle; width:50px; height:48px; background-color:#066b5e;color:#ffffff;border:1px solid #000000; padding:4px;">SN</th>

            <th style="text-align:left; font-size: 16px; font-weight: 700; vertical-align: middle; width:100px; height:48px; background-color:#066b5e;color:#ffffff;border:1px solid #000000; padding:4px;">Mill</th>

            <th style="text-align:left; font-size: 16px; font-weight: 700; vertical-align: middle; width:100px; height:48px; background-color:#066b5e;color:#ffffff;border:1px solid #000000; padding:4px;">Godown</th>

            <th style="text-align:left; font-size: 16px; font-weight: 700; vertical-align: middle; width:100px; height:48px; background-color:#066b5e;color:#ffffff;border:1px solid #000000; padding:4px;">SO Date</th>

            <th style="text-align:left; font-size: 16px; font-weight: 700; vertical-align: middle; width:120px; height:48px; background-color:#066b5e;color:#ffffff;border:1px solid #000000; padding:4px;">SO No</th>

            <th style="text-align:left; font-size: 16px; font-weight: 700; vertical-align: middle; width:100px; height:48px; background-color:#066b5e;color:#ffffff;border:1px solid #000000; padding:4px;">Invoice No</th>

            <th style="text-align:left; font-size: 16px; font-weight: 700; vertical-align: middle; width:100px; height:48px; background-color:#066b5e;color:#ffffff;border:1px solid #000000; padding:4px;">Quality</th>

            <th style="text-align:left; font-size: 16px; font-weight: 700; vertical-align: middle; width:120px; height:48px; background-color:#066b5e;color:#ffffff;border:1px solid #000000; padding:4px;">Length (cm)</th>

            <th style="text-align:left; font-size: 16px; font-weight: 700; vertical-align: middle; width:120px; height:48px; background-color:#066b5e;color:#ffffff;border:1px solid #000000; padding:4px;">Length (inch)</th>

            <th style="text-align:left; font-size: 16px; font-weight: 700; vertical-align: middle; width:120px; height:48px; background-color:#066b5e;color:#ffffff;border:1px solid #000000; padding:4px;">Width (cm)</th>

            <th style="text-align:left; font-size: 16px; font-weight: 700; vertical-align: middle; width:120px; height:48px; background-color:#066b5e;color:#ffffff;border:1px solid #000000; padding:4px;">Width (inch)</th>

            <th style="text-align:left; font-size: 16px; font-weight: 700; vertical-align: middle; width:60px; height:48px; background-color:#066b5e;color:#ffffff;border:1px solid #000000; padding:4px;">GSM</th>

            <th style="text-align:left; font-size: 16px; font-weight: 700; vertical-align: middle; width:80px; height:48px; background-color:#066b5e;color:#ffffff;border:1px solid #000000; padding:4px;">Pkt</th>

            <th style="text-align:left; font-size: 16px; font-weight: 700; vertical-align: middle; width:100px; height:48px; background-color:#066b5e;color:#ffffff;border:1px solid #000000; padding:4px;">Pkt Wt</th>

            <th style="text-align:left; font-size: 16px; font-weight: 700; vertical-align: middle; width:120px; height:48px; background-color:#066b5e;color:#ffffff;border:1px solid #000000; padding:4px;">Total Weight</th>

            <th style="text-align:left; font-size: 16px; font-weight: 700; vertical-align: middle; width:80px; height:48px; background-color:#066b5e;color:#ffffff;border:1px solid #000000; padding:4px;">Rate</th>
            
        </tr>
    </thead>
    <tbody>
        @foreach($rows as $row)
        <tr style="text-align:left;">

            <td style="height:40px; text-align:left; font-size: 14px; vertical-align: middle; border:1px solid #000000;">
                {{ $row['sn'] }}
            </td>

            <td style="height:40px; text-align:left; font-size: 14px; vertical-align: middle; border:1px solid #000000;">{{ $row['mill'] }}</td>

            <td style="height:40px; text-align:left; font-size: 14px; vertical-align: middle; border:1px solid #000000;">{{ $row['godown'] }}</td>

            <td style="height:40px; text-align:left; font-size: 14px; vertical-align: middle; border:1px solid #000000;">{{ $row['so_date'] }}</td>

            <td style="height:40px; text-align:left; font-size: 14px; vertical-align: middle; border:1px solid #000000;">{{ $row['so_no'] }}</td>

            <td style="height:40px; text-align:left; font-size: 14px; vertical-align: middle; border:1px solid #000000;">{{ $row['invoice_no'] }}</td>

            <td style="height:40px; text-align:left; font-size: 14px; vertical-align: middle; border:1px solid #000000;">{{ $row['quality'] }}</td>

            <td style="height:40px; text-align:left; font-size: 14px; vertical-align: middle; border:1px solid #000000;">{{ $row['length_cm'] }}</td>

            <td style="height:40px; text-align:left; font-size: 14px; vertical-align: middle; border:1px solid #000000;">{{ $row['length_inch'] }}</td>

            <td style="height:40px; text-align:left; font-size: 14px; vertical-align: middle; border:1px solid #000000;">{{ $row['width_cm'] }}</td>

            <td style="height:40px; text-align:left; font-size: 14px; vertical-align: middle; border:1px solid #000000;">{{ $row['width_inch'] }}</td>

            <td style="height:40px; text-align:left; font-size: 14px; vertical-align: middle; border:1px solid #000000;">{{ $row['gsm'] }}</td>

            <td style="height:40px; text-align:left; font-size: 14px; vertical-align: middle; border:1px solid #000000;">{{ $row['pkt'] }}</td>

            <td style="height:40px; text-align:left; font-size: 14px; vertical-align: middle; border:1px solid #000000;">{{ $row['pkt_wt'] }}</td>

            <td style="height:40px; text-align:left; font-size: 14px; vertical-align: middle; border:1px solid #000000;">{{ $row['weight'] }}</td>

            <td style="height:40px; text-align:left; font-size: 14px; vertical-align: middle; border:1px solid #000000;">{{ $row['rate'] }}</td>

            
        </tr>
        @endforeach
    </tbody>
</table>