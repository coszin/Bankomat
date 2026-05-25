<?php
        class LoginValidation {
        function validate(array $post) : array {
            $errors = [];

            if(!preg_match('/^[0-9]{16}$/', $post['kortnummer'])) {
                $errors[] = "Kortnumret måste vara exakt 16 siffror.";
            }

            if(!preg_match('/^[0-9]{4}$/', $post['pinkod'])) {
                $errors[] = "PIN-kod måste vara exakt 4 siffror.";
            }

            return $errors;
        }
    } 
?>  