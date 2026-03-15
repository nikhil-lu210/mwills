@php
    $navy = '#1a2b4a';
    $gold = '#c9a042';
    $offwhite = '#f4f6f9';
    $slate = '#8a9bb0';
    $logoUrl = config('app.url') . '/assets/images/logo.png';
    $dashboardUrl = config('app.url') . '/dashboard/messages';
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('New consultation enquiry') }}</title>
    <!--[if mso]>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <![endif]-->
</head>
<body style="margin:0; padding:0; background-color: {{ $offwhite }}; font-family: Georgia, 'Times New Roman', serif;">
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: {{ $offwhite }};">
        <tr>
            <td style="padding: 32px 24px;">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 8px rgba(26, 43, 74, 0.08);">
                    {{-- Header with logo and brand --}}
                    <tr>
                        <td style="padding: 28px 32px; background-color: {{ $navy }}; border-radius: 8px 8px 0 0;">
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tr>
                                    <td>
                                        @if(file_exists(public_path('assets/images/logo.png')))
                                            <img src="{{ $logoUrl }}" alt="McWills" width="140" height="40" style="display: block; max-height: 40px; width: auto;" />
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-top: 8px;">
                                        <span style="font-size: 18px; font-weight: bold; color: #ffffff; letter-spacing: 0.02em;">MCWILLS <span style="color: {{ $gold }};">|</span> CONSULTING</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    {{-- Subject line --}}
                    <tr>
                        <td style="padding: 28px 32px 16px 32px;">
                            <h1 style="margin: 0; font-size: 22px; font-weight: bold; color: {{ $navy }};">{{ __('New consultation enquiry') }}</h1>
                            <p style="margin: 8px 0 0 0; font-size: 14px; color: {{ $slate }};">{{ __('Someone submitted a contact form on your website.') }}</p>
                        </td>
                    </tr>
                    {{-- Content card --}}
                    <tr>
                        <td style="padding: 0 32px 24px 32px;">
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: {{ $offwhite }}; border-left: 4px solid {{ $gold }}; border-radius: 4px;">
                                <tr>
                                    <td style="padding: 20px 24px;">
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                            <tr>
                                                <td style="padding-bottom: 12px;">
                                                    <span style="font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: {{ $gold }};">{{ __('Name') }}</span><br>
                                                    <span style="font-size: 15px; color: {{ $navy }};">{{ $inquiry->name }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-bottom: 12px;">
                                                    <span style="font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: {{ $gold }};">{{ __('Company') }}</span><br>
                                                    <span style="font-size: 15px; color: {{ $navy }};">{{ $inquiry->company }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-bottom: 12px;">
                                                    <span style="font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: {{ $gold }};">{{ __('Email') }}</span><br>
                                                    <a href="mailto:{{ $inquiry->email }}" style="font-size: 15px; color: {{ $gold }}; text-decoration: none;">{{ $inquiry->email }}</a>
                                                </td>
                                            </tr>
                                            @if($inquiry->area)
                                            <tr>
                                                <td style="padding-bottom: 12px;">
                                                    <span style="font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: {{ $gold }};">{{ __('Area of interest') }}</span><br>
                                                    <span style="font-size: 15px; color: {{ $navy }};">{{ $inquiry->area }}</span>
                                                </td>
                                            </tr>
                                            @endif
                                            @if($inquiry->message)
                                            <tr>
                                                <td style="padding-top: 4px;">
                                                    <span style="font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: {{ $gold }};">{{ __('Message') }}</span><br>
                                                    <span style="font-size: 15px; color: {{ $navy }}; line-height: 1.5;">{{ $inquiry->message }}</span>
                                                </td>
                                            </tr>
                                            @endif
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    {{-- CTA button --}}
                    <tr>
                        <td style="padding: 0 32px 32px 32px;">
                            <a href="{{ $dashboardUrl }}" style="display: inline-block; padding: 12px 24px; background-color: {{ $gold }}; color: {{ $navy }}; font-size: 14px; font-weight: 600; text-decoration: none; border-radius: 4px;">{{ __('View in dashboard') }}</a>
                        </td>
                    </tr>
                    {{-- Footer --}}
                    <tr>
                        <td style="padding: 20px 32px; background-color: {{ $offwhite }}; border-radius: 0 0 8px 8px; border-top: 1px solid #e5e7eb;">
                            <p style="margin: 0; font-size: 12px; color: {{ $slate }};">{{ __('This email was sent from your McWills Consulting website.') }}</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
