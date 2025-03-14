<!DOCTYPE html>
<html>
<head>
    <title>Vérification Email</title>
</head>
<body>
    <h1>Bonjour {{ $details['name'] }}</h1> <!-- Accède au nom de l'utilisateur -->
    <p>Cliquez sur le lien ci-dessous pour vérifier votre adresse email :</p>
    <a href="{{ route('auth.verify.email', ['token' => $details['token'], 'hasEmail' => $details['hasEmail']]) }}">
        Vérifier mon email
    </a>    
</body>
</html>
