@php
    $navy = '#1a2b4a';
    $gold = '#c9a042';
    $offwhite = '#f4f6f9';
    $slate = '#8a9bb0';
    $logoUrl = config('app.url') . '/assets/images/logo.png';
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Thank you for your enquiry') }}</title>
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
                    {{-- Greeting --}}
                    <tr>
                        <td style="padding: 28px 32px 16px 32px;">
                            <h1 style="margin: 0 0 8px 0; font-size: 22px; font-weight: bold; color: {{ $navy }};">{{ __('Thank you for reaching out') }}</h1>
                            <p style="margin: 0; font-size: 14px; color: {{ $slate }};">
                                {{ __('Hi :name,', ['name' => $inquiry->name]) }}<br>
                                {{ __('Thank you for your enquiry to McWills Consulting. Here is our response:') }}
                            </p>
                        </td>
                    </tr>
                    {{-- Reply body --}}
                    <tr>
                        <td style="padding: 0 32px 24px 32px;">
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: {{ $offwhite }}; border-left: 4px solid {{ $gold }}; border-radius: 4px;">
                                <tr>
                                    <td style="padding: 20px 24px;">
                                        <p style="margin: 0; font-size: 15px; color: {{ $navy }}; line-height: 1.6;">
                                            {!! nl2br(e($replyBody)) !!}
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    {{-- Footer --}}
                    <tr>
                        <td style="padding: 0 32px 28px 32px;">
                            <p style="margin: 0 0 8px 0; font-size: 14px; color: {{ $navy }};">
                                {{ __('If you have any follow-up questions, just reply to this email and we’ll be happy to help.') }}
                            </p>
                            <p style="margin: 0; font-size: 12px; color: {{ $slate }};">
                                {{ __('Warm regards,') }}<br>
                                {{ __('McWills Consulting') }}
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>

