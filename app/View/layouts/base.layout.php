<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.3/css/dataTables.dataTables.css" />

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/2.0.3/js/dataTables.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>

    <link rel="stylesheet" href="<?= BASE_PATH ?>/assets/css/styles.css">

    <link rel="shortcut icon" href="https://www.apuestatotal.com/_next/static/media/logomin.fa0bada0.png" type="image/x-icon">
    <title>Apuestas</title>
    <script>
        const BASE_PATH = '<?= BASE_PATH ?>';
    </script>
</head>

<body>

    <header class="navbar bg-body-tertiary shadow-sm">
        <div class="container-fluid">
            <div class="d-flex align-items-center">
                <a class="navbar-brand" href="<?= BASE_PATH ?>/">
                    <img class="img-fluid" width="30" src="https://www.apuestatotal.com/_next/static/media/logomin.fa0bada0.png" alt="">
                </a>
                <div class="menu_options">
                    <a href="<?= BASE_PATH ?>/home/index">Panel de consulta</a>
                    <a href="<?= BASE_PATH ?>/recarga/reporte">Reporte</a>
                </div>
            </div>

            <div class="user_avatar">A</div>
        </div>
    </header>

    <div class="container-fluid">

        <?php echo $content ?>

    </div>

    <?php include(__DIR__ . '/common/modal-alert.php'); ?>

    <script src="<?= BASE_PATH ?>/assets/js/script.js"></script>
</body>

</html>