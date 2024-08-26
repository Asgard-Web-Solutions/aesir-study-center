<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $heading }} | {{ config('app.name') }} Email</title>
    <style>
        .body {
            background-color: #1a202c; /* Dark background */
            color: #e2e8f0; /* Light text color */
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            -webkit-text-size-adjust: none;
            -ms-text-size-adjust: none;
            width: 100% !important;
            height: 100% !important;
        }
        .wrapper {
            width: 100%;
            table-layout: fixed;
            background-color: #1a202c;
            padding: 20px;
        }
        .content {
            max-width: 600px;
            margin: 0 auto;
            background-color: #2d3748; /* Slightly lighter background */
            border-radius: 8px;
            padding: 20px;
        }
        .header {
            text-align: center; /* Centers the logo horizontally */
            padding-bottom: 20px; /* Adds some space below the logo */
        }

        .logo-box {
            background-color: #ffffff; /* White background for the box */
            padding: 2px; /* Space between the image and the box border */
            border-radius: 10px; /* Rounded corners */
            display: inline-block; /* Adjusts the box size to fit the content */
        }

        .logo-box img {
            width: 250px; /* Adjusts the image width */
            height: auto; /* Keeps the image's aspect ratio */
            border-radius: 5px; /* Rounds the image corners slightly */
        }
        .header img {
            max-width: 250px;
        }
        .header h1 {
            margin: 0;
            color: #e2e8f0;
            font-size: 24px;
        }
        .content {
            font-size: 16px;
            line-height: 1.5;
            padding: 20px 0;
            color: #cbd5e0;
        }
        .footer {
            text-align: center;
            padding-top: 20px;
            font-size: 14px;
            color: #a0aec0;
        }
        a {
            color: #4299e1; /* Light blue links */
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<div class="body">
    <table class="wrapper" cellpadding="0" cellspacing="0">
        <tr>
            <td>
                <div class="content">
                    <div class="header">
                        <div class="logo-box">
                            <a href="{{ route('home') }}"><img src="{{ asset('images/AALogo-DodgerBlue-Long-Small.png') }}" alt="Acolyte Academy Logo" /></a>
                        </div>
                    </div>
                    <div class="content">
                        <table width="100%" cellpadding="0" cellspacing="0">
                            <!-- Heading Row -->
                            <tr>
                                <td align="center" style="text-align: center; padding-bottom: 20px;">
                                    <h1 style="margin: 0;">{{ $heading }}</h1>
                                </td>
                            </tr>
                            <!-- Content Row -->
                            <tr>
                                <td style="padding-top: 20px;">
                                    <!-- Main Content Here -->
                                    @yield('content')
                                </td>
                            </tr>
                            <!-- Owl Image Row -->
                        </table>

                        <table>
                            <tr>
                                <td align="center">
                                    <img src="{{ asset('images/QueryTheOwl.png') }}" alt="Goodbye from {{ config('app.name') }}!"
                                        style="height: 75px; margin-top: 10px;">
                                </td>
                                <td>
                                    <p style="margin: 10px 0 0 0; color: #e2e8f0; font-size: 14px;">
                                        Have a great day!
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="footer">
                        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
                        <p>
                            You are receiving this email because you are a member of <a href="{{ url('/') }}">{{ config('app.name') }}</a> and the email provides important information regarding your account.
                        </p>
                        <p>For support, contact Hello@Acolyte.Academy</p>
                    </div>
                </div>
            </td>
        </tr>
    </table>
</div>
</html>
