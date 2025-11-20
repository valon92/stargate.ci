<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Reminder - {{ $event->title }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .container {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e9ecef;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 10px;
        }
        .event-title {
            font-size: 28px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 15px;
        }
        .event-details {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .detail-row {
            display: flex;
            margin-bottom: 10px;
            align-items: center;
        }
        .detail-label {
            font-weight: bold;
            color: #6b7280;
            min-width: 80px;
            margin-right: 15px;
        }
        .detail-value {
            color: #1f2937;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
            transition: transform 0.2s;
        }
        .cta-button:hover {
            transform: translateY(-2px);
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
            color: #6b7280;
            font-size: 14px;
        }
        .social-links {
            margin: 20px 0;
        }
        .social-links a {
            color: #2563eb;
            text-decoration: none;
            margin: 0 10px;
        }
        .reminder-badge {
            background: #fef3c7;
            color: #92400e;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
            display: inline-block;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">🚀 Stargate.CI</div>
            <div class="reminder-badge">📅 Event Reminder</div>
            <h1 class="event-title">{{ $event->title }}</h1>
        </div>

        <div class="event-details">
            <div class="detail-row">
                <span class="detail-label">📅 Date:</span>
                <span class="detail-value">{{ $eventDateTime }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">📍 Location:</span>
                <span class="detail-value">{{ $event->location }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">🏢 Organizer:</span>
                <span class="detail-value">{{ $event->organizer }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">⏰ Time:</span>
                <span class="detail-value">{{ $timeUntilEvent }}</span>
            </div>
        </div>

        <div style="margin: 25px 0;">
            <h3 style="color: #1f2937; margin-bottom: 15px;">📝 Event Description</h3>
            <p style="color: #4b5563; line-height: 1.7;">{{ $event->description }}</p>
        </div>

        @if($event->type === 'video')
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ $event->video_url }}" class="cta-button" target="_blank">
                    🎥 Watch Live Stream
                </a>
            </div>
        @elseif($event->registration_url)
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ $event->registration_url }}" class="cta-button" target="_blank">
                    📝 Register Now
                </a>
            </div>
        @endif

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $eventUrl }}" class="cta-button">
                🌟 View All Events
            </a>
        </div>

        <div class="social-links">
            <p style="text-align: center; color: #6b7280; margin-bottom: 15px;">
                Follow us for more updates:
            </p>
            <div style="text-align: center;">
                <a href="https://twitter.com/stargateci" target="_blank">🐦 Twitter</a>
                <a href="https://linkedin.com/company/stargateci" target="_blank">💼 LinkedIn</a>
                <a href="https://youtube.com/stargateci" target="_blank">📺 YouTube</a>
            </div>
        </div>

        <div class="footer">
            <p>You're receiving this email because you registered for this event on Stargate.CI</p>
            <p>If you no longer wish to receive event reminders, you can <a href="{{ $eventUrl }}?unsubscribe={{ $registration->email }}" style="color: #2563eb;">unsubscribe here</a></p>
            <p style="margin-top: 15px;">
                <strong>Stargate.CI</strong> - Your Gateway to AI Innovation<br>
                © {{ date('Y') }} Stargate.CI. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>

