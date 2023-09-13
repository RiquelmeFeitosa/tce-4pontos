<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simulador de Investimentos</title>
</head>

<body>
    <header>
        <h2>Desenvolvimento Web</h2>
    </header>

    <body>
        <h1>Histórico</h1>

        <form>
            <fieldset>
                <legend>Simulação a se recuperar</legend>
                <label for="id">ID da simulação:</label>
                <input type="number" name="id" id="id">

                <button type="submit">Recuperar</button>
            </fieldset>

            <?php
            if (isset($_GET['id'])) {

                require_once 'classes/autoloader.class.php';

                R::setup(
                    'mysql:host=localhost;dbname=fintech',
                    'root',
                    ''
                );

                $i = R::load('investimento', $_GET['id']);

                    echo "<fieldset>";
                    echo "<legend>Dados</legend>";

                    echo "<p>ID da Simulação: {$i->id}</p>";
                    echo "<p>Cliente: {$i->nome}</p>";
                    echo "<p>Aporte Inicial (R$): {$i->inicial}</p>";
                    echo "<p>Aporte Mensal (R$): {$i->mensal}</p>";
                    echo "<p>Rendimento (%): {$i->rendimento}</p>";
                    echo "<p>Período (meses): {$i->periodo}</p>";


                    echo "</fieldset>";

                $aux = R::load('investimento', $i);

                ?>

                <p>ID da Simulação: <?php echo isset($aux) ? $aux->id : '' ?></p>
                <p>Cliente: <?php echo isset($aux) ? $aux->nome : '' ?></p>
                <p>Aporte Inicial (R$): <?php echo isset($aux) ? $aux->inicial : '' ?></p>
                <p>Aporte Mensal (R$): <?php echo isset($aux) ? $aux->mensal : '' ?></p>
                <p>Rendimento (%): <?php echo isset($aux) ? $aux->taxaRendimento : '' ?></p>

                <?php
                function calcularRendimento($inicial, $mensal, $taxaRendimento)
                {
                    $rendimento = ($inicial + $mensal) * ($taxaRendimento / 100);
                    $total = $inicial + $mensal + $rendimento;
                    return array($rendimento, $total);
                }

                $aporteInicial = $i->inicial;
                $periodo = $i->periodo;
                $rendimentoMensal = $i->taxaRendimento;
                $aporteMensal = $i->mensal;

                $valorInicial = $aporteInicial;

                echo '<table border="1">';
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
            }
            ?>
        </form>


        <p><a href="index.html">Página Inicial</a></p>
    </body>

    <footer>
        <p>&copy; Lorena Zuba - 2023</p>
    </footer>

</body>

</html>
