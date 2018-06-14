<?php
require_once('../system/session.php');
require_once('../system/config.php');

require_once('../templates/content.php');

if (isset($_SESSION['id'])) {
    checkRole('user');
    // echo $_SESSION['id']['active'];


    getHeader("Sqits", "user Dashboard");
    echo "<div class=\"content-wrapper\">";
    echo "<div class=\"container-fluid\">";

    getBreadCrumbs();
    getTopPanel();
    ?>

    <div class="content">
        <?php
        //random query in order to get current patch
        try {
            $query = $conn->prepare("SELECT COUNT(up.company_id) as total_update, f.version
                                                        FROM `update` as up
                                                        INNER JOIN company as c ON c.company_id = up.company_id
                                                        INNER JOIN user as u ON u.company_id = c.company_id                                                        
                                                        INNER JOIN form as f ON up.form_id = f.form_id                                                        
                                                        WHERE u.user_id = :id AND up.status = 'pending' 
                                                        GROUP BY  f.version");
            $query->execute(array(
                'id' => $_SESSION['id']['user_id']
            ));
        } catch (PDOException $e) {
            $sMsg = '<p> 
                    Line number: ' . $e->getLine() . '<br /> 
                    File: ' . $e->getFile() . '<br /> 
                    Error message: ' . $e->getMessage() .
                '</p>';
            trigger_error($sMsg);
        }

        //to hide the error of no update_id if the user is new
        ini_set('error_reporting', E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);
        if ($update_id > 0){

        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $update_id = $row['total_update'];
            $version = $row['version'];

        }

        ?>
        <div class="card card-login mx-auto mt-5">
            <div class="card-header text-center">Current version:
                <br/>
                <h5><?= $version ?></h5>
            </div>
            <div class="card-body">

                <div class="text-center">
                    <div class="form-group">
                        <?php

                        if ($update_id > 0) {
                            echo " <span class='patch-version'>U bent up to date</span>";
                        } else {
                            echo " <a href='" . getPathToRoot() . "update/index.php'/><span class='patch-version'>Er staat nog " . $update_id . " update open</span></a>";
                        }

                        ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
        } else{
            ?>
            <div class="card card-login mx-auto mt-5">
                <div class="card-header text-center">Current version:
                    <br/>
                    <h5>Geen versie gevonden</h5>
                </div>
                <div class="card-body">

                    <div class="text-center">
                        <div class="form-group">
                            <span class='patch-version'>Er zijn geen updates gevonden</span>
                        </div>
                    </div>
                </div>
            </div>
            <?php

        }

?>
    </div>

    </div>
    </div>


    <?php
    getFooter();
} else {
    echo "please login first on login page";
    header("Refresh: 1; URL=\"../login.php\"");
    exit;
}
