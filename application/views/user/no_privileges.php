<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#35A768">

    <title><?= lang('no_privileges') . ' - ' . $company_name ?></title>
    <img src="<?= base_url('assets/img/installation-banner.png') ?>" />
    <script src="<?= asset_url('assets/ext/jquery/jquery.min.js') ?>"></script>
    <script src="<?= asset_url('assets/ext/bootstrap/js/bootstrap.min.js') ?>"></script>


    <link rel="stylesheet" type="text/css" href="<?= asset_url('assets/ext/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= asset_url('assets/css/custom.css') ?>">
    <link rel="icon" type="image/x-icon" href="<?= asset_url('assets/img/favicon.ico') ?>">

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
            margin-top: -85px;
            }

        #no-priv-frame {
            width: 630px;
            margin: auto;
            background: transparent;
            border: 5px solid #fff;
            padding: 70px;
        }

        .btn {
            margin-right: 10px;
        }

        @media(max-width: 640px) {
            #no-priv-frame {
                width: 100%;
                padding: 20px;
            }

            .btn {
                width: 100%;
                margin-bottom: 20px;
            }
        }
    </style>
</head>
<body>
    <div id="no-priv-frame" class="frame-container">
        <h3 class="installheading"><?= lang('no_privileges') ?></h3>
        <p class="installpara">
            <?= lang('no_privileges_message') ?>
        </p>

        <br>

        <a href="<?= site_url('backend') ?>" class="btn btn-success btn-large">
            <i class="icon-calendar icon-white"></i>
            <?= lang('backend_calendar') ?>
        </a>
    </div>
</body>
</html>
