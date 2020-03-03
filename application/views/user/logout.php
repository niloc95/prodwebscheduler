<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#35A768">

    <title><?= lang('log_out') . ' - ' . $company_name ?></title>
    <img src="<?= base_url('assets/img/installation-banner.png') ?>" />

    <link rel="icon" type="image/x-icon" href="<?= asset_url('assets/img/favicon.ico') ?>">

    <script src="<?= asset_url('assets/ext/jquery/jquery.min.js') ?>"></script>
    <script src="<?= asset_url('assets/ext/bootstrap/js/bootstrap.min.js') ?>"></script>

    <script>
        var WBLang = <?= json_encode($this->lang->language) ?>;
    </script>

    <link rel="stylesheet" type="text/css" href="<?= asset_url('assets/ext/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= asset_url('assets/css/general.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= asset_url('assets/css/custom.css') ?>">
    <style>
        body {
            width: 100vw;
            height: 100vh;
            display: table-cell;
            vertical-align: middle;
            height: 1000px;
            background: #EBF1F1;

            background-size: cover;
            position: relative;
        }
        img {
            margin-top: -400px;

            }

        #logout-frame {
            width: 630px;
            margin: auto;
            background: transparent;
            border: 5px solid #fff;
            padding:70px;
        }



        @media(max-width: 640px) {
            #logout-frame {
                width: 100%;
                padding: 20px;
            }


        }
    </style>

</head>
<body>
    <div id="logout-frame" class="frame-container">
        <h3 class="installheading"><?= lang('log_out') ?></h3>
        <p class="installpara"><?= lang('logout_success') ?>
        </p>

        <br>

        <a href="<?= site_url() ?>" class="btn btn-success btn-large">
            <span class="glyphicon glyphicon-calendar"></span>
            <?= lang('book_appointment_title') ?>
        </a>

        <a href="<?= site_url('backend') ?>" class="btn btn-default btn-large">
            <span class="glyphicon glyphicon-wrench"></span>
            <?= lang('backend_section') ?>
        </a>
    </div>
</body>
</html>
