<?php
# To debug from terminal use the following:
# php -dxdebug.remote_enable=1 -dxdebug.remote_mode=req -dxdebug.remote_port=9000 -dxdebug.remote_host=127.0.0.1 -dxdebug.remote_connect_back=0 ./PrepareTheProse.php
function startProject()
{
    echo "Starting localhost:8080 PHP Server \n";
    exec("chmod +x startServer.sh");
    echo "Open PHPoetry:HiQueue by opening http://localhost:8080 in your browser";
    exec("./startServer.sh", $output, $result_code);
}

function checkExec()
{
    # Check if the function exists
    if (!function_exists("exec")) {
        return false;
    }

    # Check if the function works
    exec("echo enabled", $output);
    if ($output[0] == "enabled") {
        return true;
    } else {
        return false;
    }
}

function checkMySql() {
    exec("mysql --version",$result, $code);
    return $code;
}

$execStatus = checkExec();
if (!$execStatus) {
    echo "Unfortunately your PHP installation's exec() function is either \n
    missing, disabled, or not functioning correctly. \n
    Please enable it following the documentation for your version of PHP \n";
    echo "exiting now...";
    sleep(1);
    echo  "  .  ... .";
    sleep(1);
    echo "...............................";
    die();
}
# Continue with checking for MySQL CLI availability
else {
    $mySqlStatus = checkMySql();
    # 0 means an exit code of no errors, not false in this context
    if ($mySqlStatus === 1) {
        echo "There is a problem with your MySQL installation \n 
        it may need to be installed still \n
        check to see if it is installed by running \n
        $ mysql -v \n
        If you do not see a version output then run: \n
        $ sudo apt install mysql-server -y \n
        ";
    } elseif (1 !== $mySqlStatus && 0 === $mySqlStatus) {
        # Get credentials for mysql and set to $args
        $dbuser = readline("DB User: ");
        $dbpass = readline("DB Password: ");
        $dbname = "word_list";
        if (is_null($dbuser) || is_null($dbpass)) {
            echo "Going to need your database credentials if you would like to continue \n
                Restart the script when you have MySQL user and password.";
            die();
        }
        $cwd = __DIR__;
        # Does database exist already? If so, delete it, then continue
        exec("mysqlshow -u$dbuser -p$dbpass | grep $dbname", $output, $code);
        echo "Result Code: $code \n";
        echo "Output: \n".print_r($output)."\n";

        if ($code == 0) {
            echo "deleting current word_list database \n";
            exec("echo yes | mysqladmin -u$dbuser -p$dbpass drop $dbname", $output, $code);
            echo "mysqladmin output \n";
            print_r($output);
        }

        # Create database named word_list
        echo "Creating the word list database... \n";
        exec("mysqladmin -u$dbuser -p$dbpass create $dbname");

        function theCmd($dbuser, $dbpass, $dbname)
        {
            return "pv word_list.sql.gz | gunzip | mysql -u$dbuser -p$dbpass $dbname";
        }

        # Assign variable value for return of function theCmd
        $command = theCmd($dbuser, $dbpass, $dbname);

        # Installing PipeViewer (pv) if not installed
        echo "installing pv for progress indicator of db import";
        exec("apt install -y pv", $output, $code);
        if ($code == 1) {
            echo "There was a problem installing pv (PipeViewer)";
            die();
        }
        if (1 === $code) {
            $dbpass = "******";
            $command = theCmd($dbuser, $dbpass, $dbname);
            echo "There was a problem running the last command \n
                Command: $command \n
                Are zcat & gzip installed on your system? \n
                Are you sure you used the correct username and password for MySql? \n 
            ";
            die();
        } elseif (0 === $code && $code !== 1) {
            echo "Database $dbname was created successfully \n
            Continuing with script \n";
            # Import db dump into word_list db
            echo "Importing the wordlist database dump into the empty word_list database in MySql \n";
	    exec($command, $output, $result_code);
	                $string = '<?php $dbuser='.$dbuser.";".'$dbpass='.$dbpass.";";
            $string = '<?php $UntrackedDbUser="'.$dbuser.'";'.'$UntrackedDbPass='.'"'.$dbpass.'";';
            $file = $cwd."/env.php";
	    file_put_contents($file, $string);

            if ($result_code == 0) {
                $option = readline("Do you want to start the project now? y/n: ");
                switch ($option) {
                    case "n" :
                        echo "To start the project, from this directory run: \n
                    $ php -S localhost:8080 \n
                    Thank You!!";
                        break;
                    case "y" :
                        startProject();
                        break;
                    default :
                        echo "Script needs a 'y' or an 'n'";
                        die();
                }
            }
        }
    }
}
