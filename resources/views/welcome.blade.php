<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HVR Healthcare</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/5.0.0-beta3/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body, html {
            height: 100%;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #ff9a9e 0%, #fad0c4 99%, #fad0c4 100%);
            color: white;
            text-align: center;
            overflow: hidden;
        }
        .content {
            z-index: 1;
        }
        .content h1 {
            font-size: 4rem;
            background: linear-gradient(135deg, #ff9a9e, #fad0c4);
            -webkit-background-clip: text;
            animation: fadeIn 3s ease-in-out infinite alternate;
            text-shadow: 0 4px 8px rgba(0, 0, 0, 0.3), 0 6px 20px rgba(0, 0, 0, 0.19);
        }
        .content h1 .welcome {
            color: white;
        }
        .content h1 .back {
            color: navy;
        }
        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(-20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="content container">
        <div>
            <h1> 
                <span class="welcome">Welcome </span>
                 <span class="back"> To HVR </span>
            </h1>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-beta3/js/bootstrap.min.js"></script>
</body>
</html>
