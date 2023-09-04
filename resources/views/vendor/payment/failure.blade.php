<!DOCTYPE html>
<htm l

<head>
     <title>Paymen
    t
 Failure</title>
</head>

<body>
    <h1>Payment Failed</h1>
    @if(isset($error))
    <p>Error: {{ $error }}</p>
    @endif
    <!-- Your failure page content here -->
</body>

</html>