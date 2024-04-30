<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php 
        $url = "https://sheets.googleapis.com/v4/spreadsheets/1-fWXw-8tuKHiDdaSmNIUnNcRrcah2D8Bw_zaME0HxpM/values/Página1!A1:D20?majorDimension=ROWS&key=AIzaSyCjGmLrBX9K18l6AUUzluoIB67SJWAXnps";
        
        $file = file_get_contents($url);
        $colaboradores = json_decode($file);
        
        $colaboradoresValores = $colaboradores->values;

        $dataAtual = new DateTime();

        foreach ($colaboradoresValores as $row) {
            // Verifica se o segundo elemento do array existe e se o quarto elemento não existe
            if (isset($row[1]) && !isset($row[3])) {
                $aniverColaborador = $row[1];
                
                // Converte a data de nascimento para um objeto DateTime
                $dataNascimento = DateTime::createFromFormat('d/m/Y', $aniverColaborador);
                
                // Define o ano da data de nascimento como o ano atual
                $dataNascimento->setDate($dataAtual->format('Y'), $dataNascimento->format('m'), $dataNascimento->format('d'));
                
                // Calcula a diferença entre a data de nascimento e a data atual
                $diff = $dataAtual->diff($dataNascimento);
                
                // Verifica se o aniversário já passou, é hoje ou ainda está por vir
                if ($diff->format('%R%a') < 0) {
                    echo 'O aniversário de ' . $row[0] . ' já passou a ' . abs($diff->format('%a')) . ' dias.<br>';
                } elseif ($diff->format('%R%a') == 0) {
                    echo 'Hoje é o aniversário de ' . $row[0] . '!<br>';
                } else {
                    echo 'O aniversário de ' . $row[0] . ' será em ' . $diff->format('%a') . ' dias.<br>';
                }
            }
        }
    ?>
</body>
</html>
