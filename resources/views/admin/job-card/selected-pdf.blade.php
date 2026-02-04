<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Job Card</title>

    <style>
        @page {
            margin: 8px;
        }

        body{
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #000;
        }

        .title{
            text-align: center;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        table{
            width: 100%;
            border-collapse: collapse;
        }

        th, td{
            border: 1px solid #000;
            padding: 6px 5px;
            vertical-align: middle;
        }

        th{
            font-size: 11px!important;
        }

        .text-center{
            text-align: center;
        }

        .section{
            background: #eaeaea;
            font-weight: bold;
            text-align: center;
        }

        .muted{
            color: #444;
        }
    </style>


</head>

<body>

    <div class="title">{{ get_app_setting('app_name') }}</div>

  <table class="table table-bordered" width="100%" cellspacing="0" cellpadding="0">
    <tbody>

        {{-- Header only once --}}
        <tr>
            <th>JOB NUMBER</th>
            <th>PAPER BOARD</th>
             <th>TOTAL SHEETS</th>
            <th>SHEET SIZE</th>
            <th>IMPRESSION</th>
            <th>COLOUR</th>
            <th>BACK PRINTING</th>
            <th>COATING</th>
            <th>OTHER COATING</th>
            <th>EMBOSSING</th>
            <th>LEAFING</th>
            <th>BRAILLE</th>
        </tr>

        @foreach($job_cards as $job_card)
            <tr>
                @php
                    $paperBoards = $job_card->jobCardProducts->map(function($p){
                        return $p->product?->full_name;
                    })->filter()->implode(', ');
                @endphp
                <td>{{ $job_card->set_number ?? '--' }}</td>
                <td>{{ $paperBoards ?: '--' }}</td>
                <td>{{ $job_card->total_sheet ?? '--' }}</td>
                <td>{{ $job_card->sheet_size ?? '--' }}</td>
                <td>{{ $job_card->required_sheet ?? '--' }}</td>
                
                
                <td>{{ $job_card->items->pluck('colour')->filter()->unique()->implode(', ') }}</td>
                <td>{{ $job_card->items->contains('back_print', 'Yes') ? 'Yes' : 'No' }}</td>
                <td>{{ $job_card->items->pluck('coatingType.name')->filter()->unique()->implode(', ') ?: '--' }}</td>
               <td> {{ $job_card->items->pluck('otherCoatingType.name')->filter()->unique()->implode(', ') ?: '--' }}</td>
                <td>{{ $job_card->embossing ?? '--' }}</td>
                <td>{{ $job_card->leafing ?? '--' }}</td>
                <td>{{ $job_card->items->contains('braille', 'Yes') ? 'Yes' : 'No' }}</td>
            </tr>
        @endforeach

    </tbody>
</table>

</body>
</html>