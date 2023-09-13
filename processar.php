<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <script src="https://kit.fontawesome.com/549d3529d7.js" crossorigin="anonymous"></script>
    <title>Resultados da Simulação</title>
</head>

<body>
    <header>
        <h2>Desenvolvimento Web</h2>
    </header>

    <main>
        <h1>Resultado da Simulação</h1>

        <?php
        require_once 'classes/autoloader.class.php';

        R::setup(
            'mysql:host=localhost;dbname=fintech',
            'root',
            ''
        );
        
        

        $i = R::dispense('investimento');
        $i->nome = $_POST['nome'];
        $i->inicial = $_POST['inicial'];
        $i->mensal = $_POST['mensal'];
        $i->rendimento = $_POST['rendimento'];
        $i->periodo = $_POST['periodo'];

        R::store($i);
        $aux = R::load('investimento', $i);

        ?>
        <fieldset>
            <legend>Dados</legend>
            <p>ID da Simulação: <?php echo isset($aux) ? $aux->id : '' ?></p>
            <p>Cliente: <?php echo isset($aux) ? $aux->nome : '' ?></p>
            <p>Aporte Inicial BRL: <?php echo isset($aux) ? $aux->inicial : '' ?></p>
            <p>Aporte Mensal BRL: <?php echo isset($aux) ? $aux->mensal : '' ?></p>
            <p>Rendimento (%): <?php echo isset($aux) ? $aux->rendimento : '' ?></p>
        </fieldset>
        <br>
        
        <?php
        function calcularRendimento($inicial, $mensal, $taxaRendimento)
        {
            $rendimento = ($inicial + $mensal) * ($taxaRendimento / 100);
            $total = $inicial + $mensal + $rendimento;
            return array($rendimento, $total);
        }

        $aporteInicial = $i->inicial;
        $periodo = $i->periodo;
        $rendimentoMensal = $i->rendimento;
        $aporteMensal = $i->mensal;

        $valorInicial = $aporteInicial;
        
        echo '<table border="1" class="tabela">';
        echo '<tr><th>Mês</th><th>Valor Inicial (R$)</th><th>Aporte (R$)</th><th>Rendimento (R$)</th><th>Total (R$)</th></tr>';

        for ($mes = 1; $mes <= $periodo; $mes++) {
            if ($mes == 1) {
                list($rendimento, $total) = calcularRendimento($valorInicial, 0, $rendimentoMensal);
                echo '<tr>';
                echo '<td>' . $mes . '</td>';
                echo '<td>' . number_format($valorInicial, 2, ',', '.') . '</td>';
                echo '<td>' . number_format(0, 2, ',', '.') . '</td>';
                echo '<td>' . number_format($rendimento, 2, ',', '.') . '</td>';
                echo '<td>' . number_format($total, 2, ',', '.') . '</td>';
                echo '</tr>';
                $valorInicial = $total;
            } else {
                list($rendimento, $total) = calcularRendimento($valorInicial, $aporteMensal, $rendimentoMensal);
                echo '<tr>';
                echo '<td>' . $mes . '</td>';
                echo '<td>' . number_format($valorInicial, 2, ',', '.') . '</td>';
                echo '<td>' . number_format($aporteMensal, 2, ',', '.') . '</td>';
                echo '<td>' . number_format($rendimento, 2, ',', '.') . '</td>';
                echo '<td>' . number_format($total, 2, ',', '.') . '</td>';
                echo '</tr>';
                $valorInicial = $total;
            }
        }
        echo '</table>';
        ?>
        <p><i class="fa-solid fa-house"></i> <a href="index.html">Página Inicial</a></p>
    </main>

    <footer>
        <p>Riquelme & Lorena - &copy; 2023</p>
    </footer>
</body>

</html>
