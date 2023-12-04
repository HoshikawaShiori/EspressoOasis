<!DOCTYPE html>
<html>
<head>
    <title>Contact Form Submission</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <h2>New Contact Form Submission</h2>
        <div class="row">
            <div class="col-md-6">
                <p><strong>Name:</strong> {{ $formData['name'] }}</p>
                <p><strong>Email:</strong> {{ $formData['email'] }}</p>
                <p><strong>Subject:</strong> {{ $formData['subject'] }}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <p><strong>Message:</strong></p>
                <p>{{ $formData['message'] }}</p>
            </div>
        </div>
    </div>
</body>
</html>
