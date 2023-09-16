<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Ejitrace</title>
  <link rel="stylesheet" href="css/index.css" />
  <link rel="stylesheet" href="https://unpkg.com/ionicons@4.5.10-0/dist/css/ionicons.min.css" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:500,700&amp;display=swap" />
</head>

<body>
  <form method="POST" action="connexion.php">
    <div class="segment">
      <h1>Ejitrace Tracking</h1>
    </div>
    <label>
      <input type="text" placeholder="Adresse Email" name="user" />
    </label>
    <label>
      <input type="password" placeholder="Mot de passe" name="pass" />
    </label>
    <button class="red" type="submit">
      <i class="icon ion-md-lock"></i> Se Connecter
    </button>
    <div class="segment">
      <button class="unit" type="button">
        <i class="icon ion-md-help-circle"></i>
      </button>
      <button class="unit" type="button">
        <i class="icon ion-md-eye-off"></i>
      </button>
    </div>
  </form>
</body>

</html>