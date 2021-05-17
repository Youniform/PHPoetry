<?php
require_once("./DbConn.php");
class HiQueue
{
    function  __construct()
    {
        $DbConn = new DbConn();
        $this->pdo = $DbConn->givePdoHandle();
    }

    public function random($number) {
        return rand(0, $number-1);
    }
    public function getOneSyl() {
        // get number of rows in table
        $table = "eng-1-syl";
        $sql = "SELECT * FROM `$table`";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($response) {
            $numberRows = count($response);
            $random = $this->random($numberRows);
            $word = $response[$random]["word"];
            return $word;
        }
        else {
            echo "something is broken";
        }

        // select a random number from 0 to the highest numbered row
        // take that word and return it
    }
    public function getTwoSyl() {
        $table = "eng-2-syl";
        $sql = "SELECT * FROM `$table`";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $response = $stmt ->fetchAll(PDO::FETCH_ASSOC);
        if ($response) {
            $numberRows = count($response);
            $random = $this->random($numberRows);
            $word = $response[$random]["word"];
            $splode = explode("_", $word);
            $word = "$splode[0]$splode[1]";
            return $word;
        }
        else {
            echo "something is broken";
        }
    }
    public function getThreeSyl() {
        $table = "eng-3-syl";
        $sql = "SELECT * FROM `$table`";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $response = $stmt ->fetchAll(PDO::FETCH_ASSOC);
        if ($response) {
            $numberRows = count($response);
            $random = $this->random($numberRows);
            $word = $response[$random]["word"];
            $splode = explode("-", $word);
            $word = "$splode[0]$splode[1]$splode[2]";
            return $word;
        }
        else {
            echo "something is broken";
        }
    }
    public function checkFormat(array $format) {
        $rowCheck = (count($format) ? 3 : true);
        $row1Total = 0;
        $row2Total = 0;
        $row3Total = 0;
        foreach($format[0] as $value) {
            $row1Total+=$value;
        }
        foreach($format[1] as $value) {
            $row2Total+=$value;
        }
        foreach($format[2] as $value) {
            $row3Total+=$value;
        }
        $totals = array("row1"=>$row1Total,"row2"=>$row2Total,"row3"=>$row3Total);
        $correctCheck = false;
        switch ($totals) {
            case ($totals["row1"] !== 5) : return "Row 1 needs 5 syllables";
            break;
            case ($totals["row2"] !== 7 && $totals["row2"] != 7) : return "Row 2 needs 7 syllables";
            break;
            case ($totals["row3"] !== 5) : return "Row 3 needs 5 syllables";
            break;
            case($totals["row1"] === 5 && $totals["row2"] === 7 && $totals["row3"] === 5) : $correctCheck = true;
            break;
            default : return "There's a problem somewhere, default function is proc'ing in checkFormat HiQueue function";
        }
        return $correctCheck;

    }
}
$format = [
    [1,2,1,1],
    [1,3,3],
    [2,3]
];
$haiku = new HiQueue();
$checkFormat = $haiku->checkFormat($format);
$structure = [
        0=>array(),
        1=>array(),
        2=>array()
];
for ($i=0; $i < count($format); $i++) {
    foreach($format[$i] as $syl) {
        switch ($syl) {
            case 1 : array_push($structure[$i], $haiku->getOneSyl());
            break;
            case 2 : array_push($structure[$i], $haiku->getTwoSyl());
            break;
            case 3 : array_push($structure[$i], $haiku->getThreeSyl());
            break;
            default : return false;
        }
    }
}


?>
<html>
<body>
<div style="width:100%; position:relative;">
    <div style="width:400px; border:1px solid black; margin:0 auto; height:400px; position:relative; top:100px; text-align:center; ">
        <p><?php echo ucfirst($structure[0][0])." ".$structure[0][1]." ".$structure[0][2]." ".$structure[0][3]." ".$structure[0][4];?></p>
        <p><?php echo ucfirst($structure[1][0])." ".$structure[1][1]." ".$structure[1][2]." ".$structure[1][3]." ".$structure[1][4]." ".$structure[1][5]." ".$structure[1][6];?></p>
        <p><?php echo ucfirst($structure[2][0])." ".$structure[2][1]." ".$structure[2][2]." ".$structure[2][3]." ".$structure[2][4];?></p>

    </div>
    <?php echo "<pre>";?>
    <?php print_r($format);?>
    <?php echo "</pre>";?>
</div>
</div>
</body>
</html>
