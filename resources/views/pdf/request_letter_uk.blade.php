<?php
$meddate = new DateTime($case->updated_at);

$meddate = $meddate->format('d-m-Y');

?>

<!DOCTYPE html>
<html>

<head>
    <title> {{ config('app.name', 'Medtiator') }} | Request Letter</title>
    <style type="text/css">
        @page {
            header: page-header;
            footer: page-footer;
        }

        .pt-5 {
            padding-top: 5rem;
        }

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .table_ {

            margin: 0 auto;
            width: 100%;
            border: solid;
            border-width: 1px;
            border-collapse: collapse;
            margin-bottom: 20px;

        }

        .table_ th {
            border: solid;
            border-width: 1px;
        }

        .table_ td {
            border-top: solid;
            border-width: 1px;
            padding: 10px;
            border: solid;
            border-width: 1px;
        }

        .table_ th {
            padding: 10px;
        }
    </style>
</head>

<body>


    <br><br>
    <p>
        @if ($ini->organization != null)
            {{ $ini->organization }}
        @else
            {{ $ini->name }}
        @endif
        <br>
        @if ($ini->address1 != null)
            {{ $ini->address1 }}
            <br> {{ $ini->address2 }} <br> {{ $ini->city }}, {{ $ini->pincode }}
            <br> {{ $ini->state }}, {{ $ini->country }}
        @endif
        <br>
        @if ($ini->userEmail != null)
            {{ $ini->userEmail }}
            <br>
            @endif @if ($ini->userPhone != null)
                {{ $ini->userPhone }}
            @endif
    </p>
    <p>Date: {{ $meddate }} </p>

    <p>To,
        <br>Presolv360 Administrator,

        <br><a href="mailto:admin@presolv360.com">admin@presolv360.com</a>
    </p>

    <p>Dear Sir,</p>
    <h4>Sub: Request letter</h4>


    <p style="text-indent: 4em;">Desirous of arriving at an amicable resolution of our dispute with
        <b>{{ $res->name }}</b>, and with a view to provide each party full opportunity to participate in the
        resolution of the dispute, fairly and conveniently, we request Presolv360 to provide administrative assistance
        in respect of the dispute between the parties.</p>
    <p style="text-indent: 4em;">We acknowledge that Presolv360 is a neutral institution whose role is limited to
        providing administrative support in respect of the said proceedings, has no interest in the outcome of
        the dispute and there is no conflict of interest. We understand that the said proceedings will be carried out by
        an independent, qualified and competent mediator from the panel of mediators on behalf of all the parties.
    </p>
    <br>
    <table cellspacing="0" cellpadding="10" width="100%">
        <tr>
            <td>
                <p>Sincerely,</p>
                @if ($ini->signature_photo != null)
                    <p><img width="10%"
                        src="{{ Config::get('constants.user_path') }}/{{ $ini->userId }}/signature/{{ $ini->signature_photo }}" /> </p>
                @else
                    <p>Sd/-</p>
                @endif
                <p>{{ $ini->name }}</p>
                <p>Authorized Representative</p>
            </td>
        </tr>
    </table>

</body>

</html>
