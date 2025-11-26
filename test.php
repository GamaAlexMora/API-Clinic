<?php
// contraseña que quieres verificar
$password = '123456';

// Generar un hash nuevo (BCRYPT)
$hash_generado = password_hash($password, PASSWORD_BCRYPT);

// Imprimir información útil
echo "PHP version: " . PHP_VERSION . "<br>";
echo "password_hash disponible: " . (function_exists('password_hash') ? 'sí' : 'no') . "<br>";
echo "password_verify disponible: " . (function_exists('password_verify') ? 'sí' : 'no') . "<br><br>";

echo "Contraseña original: " . htmlspecialchars($password) . "<br>";
echo "Hash generado: <code>" . htmlspecialchars($hash_generado) . "</code><br><br>";

// Verificar inmediatamente con password_verify
if (password_verify($password, $hash_generado)) {
    echo "<strong>Resultado:</strong> COINCIDE ✅";
} else {
    echo "<strong>Resultado:</strong> NO COINCIDE ❌";
}

// Mostrar ejemplo: verificar con un hash fijo (si quieres probar uno que pegues)
$hash_fijo = '$2y$10$h0rn26Y24ur5i6HJeZblH.Ma/xj20iMVvk6tj4T0dQW9VW4XduU/i';
echo "<br><br>Probando hash fijo:<br>";
echo "<code>" . htmlspecialchars($hash_fijo) . "</code><br>";
echo "password_verify con hash fijo: ";
echo (password_verify($password, $hash_fijo) ? 'COINCIDE ✅' : 'NO COINCIDE ❌');
?>
