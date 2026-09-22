<?php
// A página de Fornecedores foi substituída por Parceiros (rede de distribuidores/revendedores).
// Redirect permanente para preservar links antigos e SEO.
header('Location: parceiros.php', true, 301);
exit;
