<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Recovery</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;600;700&display=swap" rel="stylesheet">


    <style>
        body {
            font-family: "Nunito Sans", Helvetica, Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: white; /* #e9ecef; */
            border-radius: 2px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .logo {
            display: block;
            margin: 0 auto;
            width: 150px;
            height: auto;
        }

        h2 {
            color: #333;
            text-align: center;
        }

        p {
            margin: 20px 0;
            color: #666;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            /*background-color: #117a8b; */
            color: #117a8b;
            text-decoration: none;
            transition: background-color 0.3s ease;
            cursor:pointer;
            font-weight:bold;
            font-size:28px;
        }

        .btn:hover {
            color: #0056b3;
        }

        footer {
            margin-top: 40px;
            text-align: center;
            color: #999;
            border-top: 2px solid #117a8b;
            border-radius: 1px;
        }

    header {
        border-bottom: 2px solid #117a8b;
        border-radius: 1px;
        padding: 10px 20px;
        text-align: center !important;
    }

    .logo {
        width: 150px;
        height: auto;
        
    }
    .message {
    margin-top: 40px;
    color: #666;
    font-style: italic;
    font-size: 14px;
    font-weight:bold;
}

    </style>
</head>
<body>

    <div class="container">
    <header>
        <img src="{{ asset('logo.png') }}" alt="Logo" class="logo" style="width: 150px !important;height: auto;">
    </header>
    <section>
        <p> Dear {{$username}}, </p>
        <p> Welcome to {{ env ('app_company_name') }}! We are thrilled to have you join our community and are excited to embark on this journey together.</p>
        <p> Below are your login credentials to access our application: </p>
        <p> <strong> Username: {{$loginId}} <br/>
         Password: {{$randomPassword}} </strong> </p>
        <p> If you have any questions or need assistance, please don't hesitate to contact our support team at {{ env('app_contact_email') }}. We're here to help!</p>
        <p class="message">If this email wasn't expected, simply disregard it. Your account's security is our top priority.</p>
    </section>
    <footer>
    <p class="footer">Thank you for using our service.<br>For any inquiries, please contact us at: {{ env('app_contact_email') }}</p>
    </footer>
    </div>

</body>
</html>
