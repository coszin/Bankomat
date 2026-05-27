<?php
    if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
        class RegisterUserValidation {
        function validate(array $post) : array {
            $errors = [];

            if(!filter_var($post['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = "E-postadressen är inte giltig.";
            }

            if(strlen($post['email']) > 128) {
                $errors[] = "E-post kan inte vara längre än 128 tecken.";
            }
            
            if(strlen($post['firstname']) > 64) {
                $errors[] = "Förnamn kan inte vara längre än 64 tecken.";
            }

            if(strlen($post['lastname']) > 64) {
                $errors[] = "Efternamn kan inte vara längre än 64 tecken.";
            }

            if(!preg_match('/^[0-9]{10}$/', $post['phone'])) {
                $errors[] = "Telefonnumret måste vara exakt 10 siffror.";
            }

            if(strtotime($post['dateofbirth']) <= strtotime("-18 years")) {
                $errors[] = "Du måste vara minst 18 år gammal.";
            }

            return $errors;
        }
    } 
?>  