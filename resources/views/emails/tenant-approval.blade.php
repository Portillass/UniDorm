<!DOCTYPE html>
<html>
<head>
    <title>Tenant Account Approved - Dormitory Management System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #4a5568;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            padding: 20px;
            background-color: #f7fafc;
            border: 1px solid #e2e8f0;
            border-radius: 0 0 5px 5px;
        }
        .credentials {
            background-color: #fff;
            padding: 15px;
            border: 1px solid #e2e8f0;
            border-radius: 5px;
            margin: 15px 0;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #718096;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Your Tenant Account Has Been Approved</h1>
    </div>
    
    <div class="content">
        <p>Hello {{ $user->name }},</p>
        
        <p>We are pleased to inform you that your tenant account has been approved for the Dormitory Management System. Below are your database credentials that you will need to access your tenant dashboard:</p>
        
        <div class="credentials">
            <h3>Database Credentials:</h3>
            <ul>
                <li><strong>Database Name:</strong> {{ $tenant->db_name }}</li>
                <li><strong>Database Host:</strong> {{ $tenant->db_host }}</li>
                <li><strong>Database Username:</strong> {{ $tenant->db_user }}</li>
                <li><strong>Database Password:</strong> {{ $tenant->db_password }}</li>
            </ul>
        </div>
        
        <p><strong>Important Security Notice:</strong></p>
        <ul>
            <li>Please keep these credentials secure and do not share them with anyone.</li>
            <li>Change your database password after your first login.</li>
            <li>If you suspect any unauthorized access, please contact the system administrator immediately.</li>
        </ul>
        
        <p>You can now log in to your tenant dashboard and start managing your dormitory system. If you have any questions or need assistance, please don't hesitate to contact us.</p>
        
        <p>Best regards,<br>
        <strong>The Dormitory System Team</strong></p>
    </div>
    
    <div class="footer">
        <p>This is an automated message. Please do not reply to this email.</p>
        <p>© {{ date('Y') }} Dormitory Management System. All rights reserved.</p>
    </div>
</body>
</html> 