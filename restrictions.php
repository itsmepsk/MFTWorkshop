<?php 
    include 'header.php';
    require 'permission_check.php';
    // session_start();
    function checkRole($role_value) {
        // print_r($role_value);
        $page = basename($_SERVER['SCRIPT_NAME'], ".php");
        // echo $page;
        $is_admin = isset($_SESSION['is_admin'])?$_SESSION['is_admin']:null;
        // print_r($is_admin);
        if(!$is_admin) {
            $permission = hasPermission($page);
            // echo "Inside if hhh";
            // echo $permission;
            if($permission > $role_value) {
                return $permission;
            }
            elseif ($permission < $role_value) {
                // If the user doesn't have permission, show an error message and exit
                header("HTTP/1.0 403 Forbidden");
                echo '
                    <div class="error-container">
                        <div class="error-message">
                            <h1>403 Forbidden</h1>
                            <p>You do not have permission to access this page.</p>
                        </div>
                    </div>';
                exit;
            }
        }
    }
 
?>