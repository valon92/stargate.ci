<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Event: {{ $event->title }}</title>
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
        .detail-icon {
            font-size: 20px;
            margin-right: 10px;
            width: 30px;
        }
        .detail-label {
            font-weight: 600;
            color: #4b5563;
            min-width: 80px;
        }
        .detail-value {
            color: #1f2937;
        }
        .cta-button {
            display: inline-block;
            padding: 14px 28px;
            background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            text-align: center;
            margin: 10px 5px;
        }
        .cta-button:hover {
            opacity: 0.9;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #e9ecef;
            text-align: center;
            color: #6b7280;
            font-size: 14px;
        }
        .social-links a {
            color: #2563eb;
            text-decoration: none;
            margin: 0 10px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">🚀 Stargate.CI</div>
            <h1 style="color: #2563eb; margin: 0;">New Event Alert!</h1>
        </div>

        <div style="margin-bottom: 30px;">
            <h2 class="event-title">{{ $event->title }}</h2>
            
            <div class="event-details">
                <div class="detail-row">
                    <span class="detail-icon">📅</span>
                    <span class="detail-label">Date:</span>
                    <span class="detail-value">{{ $eventDate }}@if($eventTime) at {{ $eventTime }}@endif</span>
                </div>
                <div class="detail-row">
                    <span class="detail-icon">📍</span>
                    <span class="detail-label">Location:</span>
                    <span class="detail-value">{{ $event->location }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-icon">🏢</span>
                    <span class="detail-label">Organizer:</span>
                    <span class="detail-value">{{ $event->organizer }}</span>
                </div>
                @if($event->category)
                <div class="detail-row">
                    <span class="detail-icon">🏷️</span>
                    <span class="detail-label">Category:</span>
                    <span class="detail-value">{{ ucfirst($event->category) }}</span>
                </div>
                @endif
            </div>

            <div style="margin: 25px 0;">
                <h3 style="color: #1f2937; margin-bottom: 15px;">📝 Event Description</h3>
                <p style="color: #4b5563; line-height: 1.7;">{{ $event->description }}</p>
            </div>

            <div style="text-align: center; margin: 30px 0;">
                @if($event->type === 'video' && $event->video_url)
                    <a href="{{ $event->video_url }}" class="cta-button" target="_blank">
                        🎥 Watch Video
                    </a>
                @elseif($event->registration_url)
                    <a href="{{ $event->registration_url }}" class="cta-button" target="_blank">
                        📝 Register Now
                    </a>
                @endif
                
                <a href="{{ $eventUrl }}" class="cta-button">
                    🌟 View All Events
                </a>
            </div>
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
            <p>You're receiving this email because you subscribed to Stargate.CI newsletter.</p>
            <p>If you no longer wish to receive event notifications, you can <a href="{{ $eventUrl }}?unsubscribe={{ $subscriberEmail }}" style="color: #2563eb;">unsubscribe here</a></p>
            <p style="margin-top: 15px;">
                <strong>Stargate.CI</strong> - Your Gateway to AI Innovation<br>
                © 2025 Stargate.CI. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>

