<?

class DBConnectionEPIC {
		#*****************************************************************************
        #
        # dbconnection.class.php
        #
        # Author:       Denis Roy
        # Date:         2004-08-05
        #
        # Description: Functions and modules related to the MySQL database connection
        #
        # HISTORY:  THIS IS A READ-ONLY DB CONNECTION CLASS!
        #
		#*****************************************************************************

        var $MysqlUrl           = "localhost";
        var $MysqlUser          = "epic";
        var $MysqlPassword      = "";
        var $MysqlDatabase      = "eclipseplugincentral";

        function connect()
        {
                static $dbh;

                $dbh = mysql_connect($this->MysqlUrl, $this->MysqlUser, $this->MysqlPassword);

                if (!$dbh) {
                        echo( "<P>Unable to Connect to the EPIC Database.</P>" );
                }
                $DbSelected = mysql_select_db($this->MysqlDatabase, $dbh);
                return $dbh;
        }

        function disconnect() {
                mysql_close();
        }
}
?>
