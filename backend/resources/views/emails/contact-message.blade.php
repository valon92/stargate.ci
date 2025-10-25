<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Contact Message - Stargate.ci</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .message-details {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .field {
            margin-bottom: 15px;
        }
        .field-label {
            font-weight: bold;
            color: #495057;
            margin-bottom: 5px;
        }
        .field-value {
            color: #212529;
            padding: 8px 12px;
            background-color: #ffffff;
            border-radius: 4px;
            border: 1px solid #dee2e6;
        }
        .message-content {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #667eea;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            text-align: center;
            color: #6c757d;
            font-size: 14px;
        }
        .timestamp {
            color: #6c757d;
            font-size: 12px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🚀 New Contact Message</h1>
            <p>You have received a new message through Stargate.ci contact form</p>
        </div>

        <div class="message-details">
            <div class="field">
                <div class="field-label">👤 From:</div>
                <div class="field-value">{{ $contactMessage->name }}</div>
            </div>
            
            <div class="field">
                <div class="field-label">📧 Email:</div>
                <div class="field-value">{{ $contactMessage->email }}</div>
            </div>
            
            <div class="field">
                <div class="field-label">📝 Subject:</div>
                <div class="field-value">{{ $contactMessage->subject }}</div>
            </div>
        </div>

        <div class="message-content">
            <div class="field-label">💬 Message:</div>
            <div class="field-value" style="white-space: pre-wrap;">{{ $contactMessage->message }}</div>
        </div>

        <div class="footer">
            <p>This message was sent through the Stargate.ci contact form.</p>
            <p>Reply directly to this email to respond to the sender.</p>
            <div class="timestamp">
                Received: {{ $contactMessage->created_at->format('F j, Y \a\t g:i A') }}
            </div>
        </div>
    </div>
</body>
</html>
