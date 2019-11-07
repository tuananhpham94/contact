<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        td, th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

    </style>
</head>
<body>
<h1>CONTACT DETAILS UPDATE Advice Form</h1>
<h2>To: {{$company->legal_name}}</h2>
<p><b>Reference</b>: {{ $history->user->unique_id }}</p>
<p><b>Contact details change request:</b> Our customer has authorised us to contact you to update their contact details. If this customer no longer has an active account, no update is required. Please respond in all cases using the CA response number and customer name to the email below</p>
<br>
<p>Effective Date: {{\Carbon\Carbon::parse($history->created_at)->format("d-m-y")}}</p>
<p>Name of customer: {{ $history->user->name }}</p>

<h2>Existing contact (to be changed)</h2>
<table>
    <tr>
        <th>Phone number</th>
        <th>Email</th>
        <th>Address</th>
    </tr>
    <tr>
        <td>{{$oldInfo->tel }}</td>
        <td>{{$oldInfo->email }}</td>
        <td>{{$oldInfo->address }}</td>
    </tr>
</table>
<br>
<h2>New contact details</h2>
<table>
    <tr>
        <th>Phone number</th>
        <th>Email</th>
        <th>Address</th>
    </tr>
    <tr>
        <td>{{$history->tel }}</td>
        <td>{{$history->email }}</td>
        <td>{{$history->address }}</td>
    </tr>
</table>
<br>
<p><b>If provided by customer reference or policy number quoted in your company</b></p>
<p><b>Policy number:</b></p>
<p><b>Client number</b></p>


</body>
</html>