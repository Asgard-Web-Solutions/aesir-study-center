@extends('layouts.mail', ['heading' => 'Acolyte Credits Received!'])

@section('content')
    <p>The following Acolyte Package has been added to your account and the credits are available for immediate use.</p>
    <table style="margin: 0 auto; width: 80%; border-collapse: collapse;">
        <tr>
            <td style="width: 30%;">Package:</td>
            <td><strong>{{ $history->product->name }}</strong></td>
        </tr>
        <tr>
        <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td style="width: 30%;">Author Credits:</td>
            <td>+ {{ $history->architect_change }}</td>
        </tr>
        <tr>
            <td style="width: 30%;">Study Credits:</td>
            <td>+ {{ $history->study_change }}</td>
        </tr>
        <tr>
        <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td style="width: 30%;">Message:</td>
            <td>
                {{ $history->title }}<br />
                {{ $history->reason }}
            </td>
        </tr>
        <tr>
        <td colspan="2">&nbsp;</td>
        </tr>
    </table>
    
    <p><a href="{{ route('profile.credits', $history->user) }}">Click Here</a> to view your credit balance.</p>

@endsection
