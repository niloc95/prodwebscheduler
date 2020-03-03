<!DOCTYPE html>
<html>
<head>
    <title>@WebScheduler - Installation</title>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" type="text/css" href="<?= asset_url('assets/css/custom.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= asset_url('assets/ext/bootstrap/css/bootstrap.min.css') ?>">

    <link rel="icon" type="image/x-icon" href="<?= asset_url('assets/img/favicon.ico') ?>">
    <link rel="stylesheet" type="text/css" href="<?= asset_url ('assets/ext/jquery-ui/jquery-ui.min.css')?>">


    <style>
        header {
            background: #EBF1F1;
            margin-bottom: 25px;
            align: center;
        }
        body {
            background: #EBF1F1;


        }

        .content {
            /*margin: 32px;*/
            align-content: center;
            max-width: 1200px;


        }

        .alert {
            margin: 25px 0 10px 0;
        }

        footer {
           padding: 10px 35px;
           margin-top: 20px;
           border-top: 0px solid #EEE;
        }

        #loading {
            position: fixed;
            top: 0px;
            left: 0px;
            width: 100%;
            height: 100%;
            z-index: 999999;
            /*background: rgba(255, 255, 255, 0.75);*/
            background: #EBF1F1;
        }

        #loading img {
            margin: auto;
            display: block;
        }
    </style>
</head>
<body>
    <div id="loading" class="hidden">
        <img src="<?= base_url('assets/img/loading.gif') ?>">
    </div>

    <header>
        <a href="http://semeonline.co.za" target="_blank">
            <img src="<?= base_url('assets/img/installation-banner.png') ?>"
                 alt="@WebScheduler Installation Banner">
        </a>
    </header>
    <br/>

    <div class="content container-fluid">
        <div class="welcome">
            <h3 class="installheading">Welcome to the WebScheduler</h3>
            <br>
            <p>
                <span class="installpara">This section will set the <strong>System Administrator Acount</strong> for <strong>@WebScheduler</strong>.
                You will be able to edit these settings from the Administrator section in the system.</span>
                <br>
                <span class="installpara">For technical support with @WebScheduler refer to the online <a href="https://semeonline.co.za">Documentation.</a></span>
             </p>
        </div>

        <div class="alert hidden"></div>

        <div class="row">
            <div class="admin-settings col-xs-12 col-sm-5">
                <h3 class="install_sub_headings">Administrator</h3>

                <div class="form-group">
                    <label for="first-name" class="control-label, install_field_input_heading">First Name</label>
                    <input type="text" id="first-name" class="form-control" />
                </div>

                <div class="form-group">
                <label for="last-name" class="control-label, install_field_input_heading">Last Name</label>
                <input type="text" id="last-name" class="form-control" />
                </div>

                <div class="form-group">
                <label for="email" class="control-label, install_field_input_heading">Email</label>
                <input type="text" id="email" class="form-control" />
                </div>



                <div class="form-group">
                    <label for="username" class="control-label, install_field_input_heading">Username</label>
                    <input type="text" id="username" class="form-control" />
                </div>

                <div class="form-group">
                    <label for="password" class="control-label, install_field_input_heading">Password</label>
                    <input type="password" id="password" class="form-control" />
                </div>

                <div class="form-group">
                    <label for="retype-password" class="control-label, install_field_input_heading">Retype Password</label>
                    <input type="password" id="retype-password" class="form-control"/>
                </div>
            </div>

            <div class="company-settings col-xs-12 col-sm-5">
                <h3 class="install_sub_headings">Company Details</h3>

                <div class="form-group">
                    <label for="company-name" class="control-label, install_field_input_heading">Company Name</label>
                    <input type="text" id="company-name" class="form-control" />
                </div>

                <div class="form-group">
                    <label for="company-email" class="control-label, install_field_input_heading">Company Email</label>
                    <input type="text" id="company-email" class="form-control" />
                </div>

                <div class="form-group">
                    <label for="Phone-number" class="control-label, install_field_input_heading">Company Phone Number</label>
                    <input type="text" id="phone-number" class="form-control" />
                </div>

                <div class="form-group">
                    <label for="company-link" class="control-label, install_field_input_heading">Company Link</label>
                    <input type="text" id="company-link" class="form-control" />
                </div>
            </div>
        </div>

        <br>

        <p class="installpara">
            You will be able to set your business logic in the backend settings page
            after the installation is complete.
            <br>
            Press the following button to complete the installation process.
        </p>

        <h3 class="install_sub_headings">License</h3>
        <p class="installpara">
            @WebScheduler is licensed under the SemeOnline Pty Ltd.
            By using this Application <br> you agree with the terms and conditions described in the
            following <a href="https://www.semeonline.co.za">Policy:</a>

        </p>

        <br>

        <button type="button" id="install" class="btn btn-success btn-large">
            <i class="icon-white icon-ok"></i>
            Install @WebScheduler</button>
    </div>

    <footer>
        Powered by <a href="https://semeonline.co.za">@WebScheduler</a>
    </footer>

    <script>
        var GlobalVariables = {
            'csrfToken': <?= json_encode($this->security->get_csrf_hash()) ?>,
            'baseUrl': <?= json_encode(config('base_url')) ?>
        };

        var WBLang = <?= json_encode($this->lang->language) ?>;
    </script>

    <script src="<?= asset_url('assets/ext/jquery/jquery.min.js') ?>"></script>
    <script src="<?= asset_url('assets/ext/jquery-ui/jquery-ui.min.js') ?>"></script>
    <script src="<?= asset_url('assets/ext/bootstrap/js/bootstrap.min.js') ?>"></script>
    <script src="<?= asset_url('assets/js/general_functions.js') ?>"></script>
    <script src="<?= asset_url('assets/ext/datejs/date.js') ?>"></script>
    <script src="<?= asset_url('assets/js/installation.js') ?>"></script>
</body>
</html>
