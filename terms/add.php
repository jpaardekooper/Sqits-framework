<?php
require_once '../system/session.php';

include_once('../system/config.php');

include_once('../templates/content.php');


if (isset($_SESSION['id'])) {

    checkRole('admin');

    if (@$_GET['action'] == "save") {

        try {

//http://php.net/manual/en/password.constants.php

            $query = "INSERT INTO terms (acceptance, service_level_agreement, contact, signature, created_date) 
                                    VALUES (:acceptance, :service_level_agreement,  :contact, :signature, NOW() )";
            $results = $conn->prepare($query);
            $results->execute(array(
                'acceptance' => $_POST['acceptance'],
                'service_level_agreement' => $_POST['service_level_agreement'],
                'contact' => $_POST['contact'],
                'signature' => $_POST['signature'],

            ));

            echo "<div class='loading-screen'>
                    <img class='loading' src='" . getAssetsDirectory() . "image/loading.gif'/>
            </div>";


            echo "Het is opgeslagen.";

            header("Refresh: 1; URL=index.php");


        } catch (PDOException $e) {
            $sMsg = '<p>
                Regelnummer: ' . $e->getLine() . '<br />
                Bestand: ' . $e->getFile() . '<br />
                Foutmelding: ' . $e->getMessage() . '
            </p>';

            trigger_error($sMsg);
        }
    } else {

        getHeader("Sqits form-add", "Form add");

        echo '<div class="content-wrapper">';
        echo '<div class="container-fluid">';

        getTopPanel("Voorwaarde", " toevoegen");

        ?>

        <div class="container">
            <div class="card card-register mx-auto mt-1">
                <form name="add" action="?action=save" method="post">
                    <div class="card-header">Voorwaarden Forumulier</div>
                    <div class="card-body">
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="exampleTextArea1">Acceptatie</label>
                                        <textarea class="form-control" rows="5" id="exampleTextArea1" name="acceptance"
                                                  placeholder="de update bevat de volgende onderdelen..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="exampleTextArea2">Voorwaarden</label>
                                        <textarea class="form-control" rows="5" id="exampleTextArea2"
                                                  name="service_level_agreement"
                                                  placeholder="de update bevat de volgende onderdelen..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="exampleTextArea3">contact</label>
                                        <textarea class="form-control" rows="5" id="exampleTextArea3" name="contact"
                                                  placeholder="de update bevat de volgende onderdelen..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="exampleTextArea4">ondertekening</label>
                                        <textarea class="form-control" rows="5" id="exampleTextArea4" name="signature"
                                                  placeholder="de update bevat de volgende onderdelen..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input class="btn btn-primary btn-block" type="submit" name="submit" value="Opslaan">
                </form>
            </div>
        </div>

        <?php


        echo "</div>";
        echo "</div>";

        getFooter();

    }
} else {
    echo "please login first on login page";
    header("Location:../login.php");
    exit;
}


?>