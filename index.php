<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Listando aniversário dos colaboradores!</h1>
    <?php 

        require_once __DIR__ . '/vendor/autoload.php'; // Carrega o autoload do Composer

        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__); // Cria uma instância do Dotenv
        $dotenv->load(); // Carrega as variáveis de ambiente do arquivo .env

        $apiKey = $_ENV['GOOGLE_SHEETS_API_KEY']; // Obtém a chave de API do ambiente

        $url = "https://sheets.googleapis.com/v4/spreadsheets/1-fWXw-8tuKHiDdaSmNIUnNcRrcah2D8Bw_zaME0HxpM/values/Página1!A1:D20?majorDimension=ROWS&key=$apiKey";
        
        $file = file_get_contents($url);
        $colaboradores = json_decode($file);
        
        $colaboradoresValores = $colaboradores->values;

        $dataAtual = new DateTime();

        foreach ($colaboradoresValores as $row) {
            // Verifica se o segundo elemento do array existe e se o quarto elemento não existe
            if (isset($row[1]) && !isset($row[3])) {
                $nasciColaborador = $row[1];
                $aniverAdmissao = $row[2];
                
                // Converte a data de nascimento para um objeto DateTime
                $dataNascimento = DateTime::createFromFormat('d/m/Y', $nasciColaborador);
                $dataAdmissao = DateTime::createFromFormat('d/m/Y', $aniverAdmissao);
                
                $diferenca = $dataNascimento->diff($dataAtual);
                $idade = $diferenca->y;
                $diferencaAdmissao = $dataAdmissao->diff($dataAtual);
                $anosEmpresa = $diferencaAdmissao->y;
                
                // Define o ano da data de nascimento como o ano atual
                $dataNascimento->setDate($dataAtual->format('Y'), $dataNascimento->format('m'), $dataNascimento->format('d'));
                $dataAdmissao->setDate($dataAtual->format('Y'), $dataAdmissao->format('m'), $dataAdmissao->format('d'));
                
                // Calcula a diferença entre a data de nascimento e a data atual
                $diff = $dataAtual->diff($dataNascimento);
                $diffAdmissao = $dataAtual->diff($dataAdmissao);
                
                // Verifica se o aniversário já passou, é hoje ou ainda está por vir
                if ($diff->format('%R%a') < 0) {
                    echo 'O aniversário de ' . $row[0] . ' já passou a ' . abs($diff->format('%a')) . ' dias. E ele(a) completou ' . $idade . ' anos.<br>';
                } elseif ($diff->format('%R%a') == 0) {
                    echo 'Hoje é o aniversário de ' . $row[0] . '! Que está completando ' . $idade . ' anos.<br>';
                } else {
                    $idade = $idade + 1;
                    echo 'O aniversário de ' . $row[0] . ' será em ' . $diff->format('%a') . ' dias. E ele(a) completará ' . $idade . ' anos.<br>';
                }
                

                if ($diffAdmissao->format('%R%a') < 0) {
                    echo 'O aniversario de empresa de ' . $row[0] . ' já foi. Ele(a) completou ' . $anosEmpresa . ' anos de empresa.<br>';
                } elseif ($diffAdmissao->format('%R%a') == 0) {
                    echo 'O aniversario de empresa de ' . $row[0] . ' é hoje! Que está completando ' . $anosEmpresa .  ' anos de empresa.<br>';
                } else {
                    $anosEmpresa = $anosEmpresa + 1;
                    echo 'O aniversario de empresa de ' . $row[0] . ' será esse ano ainda! Ele(a) estará completando ' . $anosEmpresa . ' anos de empresa.<br>';
                }

                echo '------------------------------------------------------------------------------------------------------------------------------------------------------------------<br>';
            }
        }
    ?>

</body>
</html>
