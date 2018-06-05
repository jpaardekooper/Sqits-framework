<?php
require_once('../system/session.php');
require_once('../system/config.php');

require_once('../templates/content.php');

if ($_SESSION["id"]) {

    switch (getUserRole()) {
        case "user":

            getHeader("Sqits", "uw gegevens");


            echo '<div class="content-wrapper">';
            echo '<div class="container-fluid">';
            getBreadCrumbs();
            getTopPanel("uw gegevens");
            echo "</div>";
            echo "</div>";


            getFooter();
            break;

        case "admin":

            try {
                $query = $conn->prepare("SELECT u.*, p.*, c.*
                                          FROM `user` as u
                                          INNER JOIN company as c  ON u.company_id = c.company_id  
                                          INNER JOIN phone as p ON u.phone_id = p.phone_id                                                                                                                            
                                          ");
                $query->execute();

            } catch (PDOException $e) {
                $sMsg = '<p> 
                    Regulnummer: ' . $e->getLine() . '<br /> 
                    Bestand: ' . $e->getFile() . '<br /> 
                    Foutmedling: ' . $e->getMessage() .
                    '</p>';
                trigger_error($sMsg);
            }


            echo '<div class="content-wrapper">';
            echo '<div class="container-fluid">';
            getHeader("Sqits", "overzicht klanten");
            getBreadCrumbs();
            getTopPanel("overzicht gebruikers");


            echo " <div class=\"card mb-3\">
                <div class=\"card-header\">
                    <i class=\"fa fa-table\"></i> Data Table Example</div>
                <div class=\"card-body\">
                    <div class=\"table-responsive\">
                        <table class=\"table table-bordered\" id=\"table_id\" width=\"100%\" cellspacing=\"0\">                                                   
                        <thead>
                            <tr>
                             <th>bedrijfsnaam</th>
                             <th>Email: </th>
                             <th>voornaam</th>
                             <th>achternaam</th>           
                             <th>phone</th>
                             <th>active</th>
                             <th>last_visit</th>
                             <th>created_date</th>
                             <th>acties</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>bedrijfsnaam</th>
                                <th>Email: </th>
                                <th>voornaam</th>
                                <th>achternaam</th>           
                                <th>phone</th>
                                <th>active</th>
                                <th>last_visit</th>
                                <th>created_date</th>
                                <th>acties</th>
                            </tr>
                        </tfoot>
                     <tbody>";


            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $user_id = $row['user_id'];
                $first_name = $row['first_name'];
                $last_name = $row['last_name'];
                $email = $row['email'];
                $last_visit = $row['last_visit'];
                $active = $row['active'];
                $created_date = $row['created_date'];
                $role = $row['role'];
                $company_name = $row['company_name'];
                $phone = $row['phone_number'];

                echo "<tr>
                <td>" . htmlentities($company_name) . "</td>                           
                <td>" . htmlentities($email) . "</td> 
                <td>" . htmlentities($first_name) . "</td>
                <td>" . htmlentities($last_name) . "</td>
                   <td>" . htmlentities($phone) . "</td>
                <td>" . htmlentities($active) . "</td>
                  <td>" . htmlentities($last_visit) . "</td>            
                <td>" . htmlentities($created_date) . "</td>          
              
             
             
                    <td><a href=\"delete.php?action=delete&id=$user_id\">X</a>
                        <a href=\"update.php?action=delete&id=$user_id\">edit</a></td>
                            </tr>";
            }
            echo "       </tbody>
            </table>
          </div>
        </div>       
      </div>
    </div>";


            getFooter();
            break;
        default:
            trigger_error("Invalid role specified: " . $role, E_USER_WARNING);
    }//stops the switch
} else {
    echo "please login first on login page";
    header("Refresh: 1; URL=\"../login.php\"");
    exit;
}
