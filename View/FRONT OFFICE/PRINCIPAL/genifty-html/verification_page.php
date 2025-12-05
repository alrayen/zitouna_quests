<?php
$email = isset($_GET['email']) ? $_GET['email'] : '';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Vérification du compte - Zitouna Quest</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .card { background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); text-align: center; }
        input[type="text"] { padding: 10px; font-size: 1.2rem; letter-spacing: 5px; text-align: center; width: 150px; margin: 10px 0; }
        button { background-color: #28a745; color: white; border: none; padding: 10px 20px; font-size: 1rem; cursor: pointer; border-radius: 5px; }
        button:hover { background-color: #218838; }
    </style>
</head>
<body>

<div class="card">
    <h2>Vérification Email</h2>
    <p>Un code à 6 chiffres a été envoyé à : <strong><?php echo htmlspecialchars($email); ?></strong></p>
    
    <form action="/Projet2/Controller/verify_handler.php" method="POST">
        <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
        
        <label for="code">Entrez le code :</label><br>
       <input type="text" name="code" id="code_input" placeholder="123456"><br><br>
        
        <button type="submit" name="verifier">Valider mon compte</button>
    </form>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var form = document.querySelector('form');
        var codeInput = document.getElementById('code_input');

        var errorMsg = document.createElement('p');
        errorMsg.style.color = "red";
        errorMsg.style.fontSize = "0.9rem";
        errorMsg.style.display = "none"; 
        errorMsg.style.marginTop = "5px";
        
        codeInput.parentNode.insertBefore(errorMsg, codeInput.nextSibling);

        form.addEventListener('submit', function(e) {
            var value = codeInput.value.trim();
            var isValid = true;
            var message = "";

            
            if (value === "") {
                message = "Veuillez entrer le code de vérification.";
                isValid = false;
            }
            else if (!/^\d+$/.test(value)) {
                message = "Le code ne doit contenir que des chiffres.";
                isValid = false;
            }
            else if (value.length !== 6) {
                message = "Le code doit contenir exactement 6 chiffres.";
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
                errorMsg.innerText = message;
                errorMsg.style.display = "block";
                codeInput.style.border = "1px solid red"; 
            } else {
                errorMsg.style.display = "none";
                codeInput.style.border = "1px solid #ccc";
            }
        });

        codeInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, ''); 
        });
    });
</script>

</body>
</html>