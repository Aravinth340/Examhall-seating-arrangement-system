<?php
session_start();

// Defensive, fixed version of tt.php — protects against huge/invalid session payloads
// and prevents memory exhaustion by validating inputs and adding sensible limits.

$DateSe = $DateTe = $DateBe = array();

// don't mutate the global $_SESSION unexpectedly; use array_slice instead of array_splice
$classVector = is_array($_SESSION) ? array_slice($_SESSION, 4) : [];

/**
 * Safely extract the "slot" values and the remaining table.
 * - If the first element is an array, find the first scalar string inside it (depth-limited).
 * - Avoid imploding huge arrays; impose limits on slot count.
 * - Return an array: [slotArray, remainingTable]
 */
function extract_slot_and_table($timeTable)
{
    if (!is_array($timeTable) || count($timeTable) === 0) {
        return [[], $timeTable ?: []];
    }

    // Work on a copy so we don't mutate the original passed array (caller has its own copy)
    $tableCopy = $timeTable;
    $first = array_shift($tableCopy);

    // Find a usable slot string if $first is an array
    if (is_array($first)) {
        // Depth-limited walk to locate a scalar value
        $slotString = '';
        $queue = [$first];
        $depth = 0;
        while (!empty($queue) && $depth < 3 && $slotString === '') {
            $item = array_shift($queue);
            foreach ($item as $v) {
                if (is_string($v) && trim($v) !== '') {
                    $slotString = $v;
                    break 2;
                } elseif (is_scalar($v) && $slotString === '') {
                    $slotString = (string)$v;
                    break 2;
                } elseif (is_array($v)) {
                    $queue[] = $v;
                }
            }
            $depth++;
        }

        // As a last resort, if we still don't have a string, try joining up to a small number of elements
        if ($slotString === '') {
            $flat = array_slice($first, 0, 50); // limit how many we join to avoid massive string
            $slotString = trim(implode("  ", array_map('strval', $flat)));
        }
    } else {
        $slotString = is_scalar($first) ? trim((string)$first) : '';
    }

    if ($slotString === '') {
        return [[], $tableCopy];
    }

    // Split using the original delimiter but guard slot count to avoid huge loops later
    $rawSlots = explode("  ", $slotString);
    $rawSlots = array_map('trim', $rawSlots);
    $rawSlots = array_values(array_filter($rawSlots, function ($v) {
        return $v !== '';
    }));

    // limit to a reasonable number of slots (adjust if your domain needs more)
    $maxSlots = 20;
    if (count($rawSlots) > $maxSlots) {
        $rawSlots = array_slice($rawSlots, 0, $maxSlots);
    }

    return [$rawSlots, $tableCopy];
}

// Helper to safely consume up to $count items from $tableCopy and return them (without throwing)
function safe_shift_n(&$tableCopy, $count)
{
    $out = [];
    $count = max(0, (int)$count);
    for ($i = 0; $i < $count && !empty($tableCopy); $i++) {
        $out[] = array_shift($tableCopy);
    }
    return $out;
}

// SE table
$seTimeTable = isset($_SESSION['SE']) && is_array($_SESSION['SE']) ? $_SESSION['SE'] : [];
list($slot, $seTimeTable) = extract_slot_and_table($seTimeTable);
foreach ($slot as $s_raw) {
    // convert to int and bound it to avoid runaway allocations
    $s = intval($s_raw);
    if ($s <= 0) {
        continue;
    }
    $s = min($s, 10); // protect: don't allow more than 10 repeated shifts per date (adjust as needed)

    $dateD = isset($seTimeTable[0]) ? array_shift($seTimeTable) : null;
    if ($dateD === null) {
        break;
    }

    $taken = safe_shift_n($seTimeTable, $s);
    foreach ($taken as $val) {
        $DateSe[$dateD][] = $val;
    }
}

// TE table
$teTimeTable = isset($_SESSION['TE']) && is_array($_SESSION['TE']) ? $_SESSION['TE'] : [];
list($slot, $teTimeTable) = extract_slot_and_table($teTimeTable);
foreach ($slot as $s_raw) {
    $s = intval($s_raw);
    if ($s <= 0) {
        continue;
    }
    $s = min($s, 10);

    $dateD = isset($teTimeTable[0]) ? array_shift($teTimeTable) : null;
    if ($dateD === null) {
        break;
    }

    $taken = safe_shift_n($teTimeTable, $s);
    foreach ($taken as $val) {
        $DateTe[$dateD][] = $val;
    }
}

// BE table
$beTimeTable = isset($_SESSION['BE']) && is_array($_SESSION['BE']) ? $_SESSION['BE'] : [];
list($slot, $beTimeTable) = extract_slot_and_table($beTimeTable);
foreach ($slot as $s_raw) {
    $s = intval($s_raw);
    if ($s <= 0) {
        continue;
    }
    $s = min($s, 10);

    $dateD = isset($beTimeTable[0]) ? array_shift($beTimeTable) : null;
    if ($dateD === null) {
        break;
    }

    $taken = safe_shift_n($beTimeTable, $s);
    foreach ($taken as $val) {
        $DateBe[$dateD][] = $val;
    }
}

error_reporting(E_ERROR | E_PARSE | E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_COMPILE_WARNING);

$tt = array();

// defend against missing POST keys
$tt[0][0][0] = isset($_POST["SUB21"]) ? $_POST["SUB21"] : 0;
$tt[0][0][1] = isset($_POST["SUB22"]) ? $_POST["SUB22"] : 0;
$tt[0][1][0] = isset($_POST["SUB23"]) ? $_POST["SUB23"] : 0;
$tt[0][1][1] = isset($_POST["SUB24"]) ? $_POST["SUB24"] : 0;
$tt[0][2][0] = isset($_POST["SUB25"]) ? $_POST["SUB25"] : 0;
$tt[0][2][1] = isset($_POST["SUB26"]) ? $_POST["SUB26"] : 0;

$tt[1][0][0] = isset($_POST["SUB31"]) ? $_POST["SUB31"] : 0;
$tt[1][0][1] = isset($_POST["SUB32"]) ? $_POST["SUB32"] : 0;
$tt[1][1][0] = isset($_POST["SUB33"]) ? $_POST["SUB33"] : 0;
$tt[1][1][1] = isset($_POST["SUB34"]) ? $_POST["SUB34"] : 0;
$tt[1][2][0] = isset($_POST["SUB35"]) ? $_POST["SUB35"] : 0;
$tt[1][2][1] = 0;

$tt[2][0][0] = isset($_POST["SUB41"]) ? $_POST["SUB41"] : 0;
$tt[2][0][1] = isset($_POST["SUB42"]) ? $_POST["SUB42"] : 0;
$tt[2][1][0] = isset($_POST["SUB43"]) ? $_POST["SUB43"] : 0;
$tt[2][1][1] = isset($_POST["SUB44"]) ? $_POST["SUB44"] : 0;
$tt[2][2][0] = 0;
$tt[2][2][1] = 0;

class teacher
{
    public $name;
    public $sub;
}

$te = array();
// Initialize teacher objects before use
for ($i = 0; $i < 10; $i++) {
    $te[$i] = new teacher();
}

$yearinclass = array();

$yearinclass[0][0] = isset($_POST["class11"]) ? intval($_POST["class11"]) : 0;
$yearinclass[0][1] = isset($_POST["class12"]) ? intval($_POST["class12"]) : 0;
$yearinclass[1][0] = isset($_POST["class21"]) ? intval($_POST["class21"]) : 0;
$yearinclass[1][1] = isset($_POST["class22"]) ? intval($_POST["class22"]) : 0;
$yearinclass[2][0] = isset($_POST["class31"]) ? intval($_POST["class31"]) : 0;
$yearinclass[2][1] = isset($_POST["class32"]) ? intval($_POST["class32"]) : 0;

$total = 30;

$lectures = array();
$k = 0;
$j = 0;
for ($i = 0; $i < 10; $i++) {
    if ($i == 0) {
        $te[$i]->name = "Prof. Dr. Syed Akhter Hossain";
        $te[$i]->sub[0] = "43";
        $te[$i]->sub[1] = "31";
    }
    if ($i == 1) {
        $te[$i]->name = "Dr. Sheak Rashed Haider Noori";
        $te[$i]->sub[0] = "26";
        $te[$i]->sub[1] = "33";
    }
    if ($i == 2) {
        $te[$i]->name = "Dr. Md. Mustafizur Rahman";
        $te[$i]->sub[0] = "22";
        $te[$i]->sub[1] = "34";
    }
    if ($i == 3) {
        $te[$i]->name = "Dr. S. M. Aminul Haque";
        $te[$i]->sub[0] = "41";
        $te[$i]->sub[1] = "0";
    }
    if ($i == 4) {
        $te[$i]->name = "Professor Dr. Md. Ismail Jabiullah";
        $te[$i]->sub[0] = "25";
        $te[$i]->sub[1] = "0";
    }
    if ($i == 5) {
        $te[$i]->name = "Dr. S.R.Subramanya";
        $te[$i]->sub[0] = "35";
        $te[$i]->sub[1] = "0";
    }
    if ($i == 6) {
        $te[$i]->name = "Dr. Neil Perez Balba";
        $te[$i]->sub[0] = "42";
        $te[$i]->sub[1] = "32";
    }
    if ($i == 7) {
        $te[$i]->name = "Dr. Bibhuti Roy";
        $te[$i]->sub[0] = "24";
        $te[$i]->sub[1] = "44";
    }
    if ($i == 8) {
        $te[$i]->name = "Mr. Anisur Rahman";
        $te[$i]->sub[0] = "31";
        $te[$i]->sub[1] = "0";
    }
    if ($i == 9) {
        $te[$i]->name = "Mr. Gazi Zahirul Islam";
        $te[$i]->sub[0] = "21";
        $te[$i]->sub[1] = "0";
    }

    if (!isset($te[$i]->sub[0]) || !isset($te[$i]->sub[1])) {
        $te[$i]->sub[0] = isset($te[$i]->sub[0]) ? $te[$i]->sub[0] : "0";
        $te[$i]->sub[1] = isset($te[$i]->sub[1]) ? $te[$i]->sub[1] : "0";
    }

    if ($te[$i]->sub[0] == 0 || $te[$i]->sub[1] == 0) {
        $lectures[$i] = 5;
        $total = $total - 5;
        $k++;
    } else {
        if ($j < 4) {
            $lectures[$i] = 2;
            $total = $total - 2;
            $j++;
        } else {
            $lectures[$i] = 1;
            $total = $total - 1;
            $j++;
        }
    }
}

// Build subjectinclass only when indices exist; guard against invalid yearinclass values
$subjectinclass = array();
for ($i = 0; $i < 3; $i++) {
    for ($j = 0; $j < 3; $j++) {
        for ($k2 = 0; $k2 < 2; $k2++) {
            for ($m = 0; $m < 2; $m++) {
                $yearVal = isset($yearinclass[$j][$m]) ? intval($yearinclass[$j][$m]) : 0;
                $l = $yearVal - 2;
                // ensure $l is within bounds of $tt
                if (!isset($tt[$l]) || !is_array($tt[$l]) || !isset($tt[$l][$i]) || !is_array($tt[$l][$i]) || !isset($tt[$l][$i][$k2])) {
                    $subjectinclass[$i][$j][$k2][$m] = 0;
                } else {
                    $subjectinclass[$i][$j][$k2][$m] = $tt[$l][$i][$k2];
                }
            }
        }
    }
}

$teacherinclass = array();
$total = 30;
$l = 0;

for ($i = 0; $i < 3; $i++) {
    for ($j = 0; $j < 3; $j++) {
        for ($k2 = 0; $k2 < 2; $k2++) {
            for ($m = 0; $m < 2; $m++) {
                $flag = 0;
                $safetyCounter = 0; // avoid infinite loops
                do {
                    $safetyCounter++;
                    if ($safetyCounter > 1000) {
                        // give up and mark unassigned to avoid locking up
                        $teacherinclass[$i][$j][$k2][$m] = 100;
                        $flag = 1;
                        break;
                    }

                    if ($l >= 10) {
                        $l = 0;
                    } elseif ($subjectinclass[$i][$j][$k2][$m] == 0) {
                        $teacherinclass[$i][$j][$k2][$m] = 100;
                        $flag = 1;
                    } elseif (!isset($lectures[$l]) || $lectures[$l] == 0) {
                        $l++;
                    } elseif ($subjectinclass[$i][$j][$k2][$m] != $te[$l]->sub[0] && $subjectinclass[$i][$j][$k2][$m] != $te[$l]->sub[1] && $lectures[$l] != 0) {
                        $teacherinclass[$i][$j][$k2][$m] = $l;
                        $lectures[$l]--;
                        $l++;
                        $flag = 1;
                    } else {
                        $l++;
                    }
                } while ($flag == 0);
            }
        }
    }
}

echo "<table border =\"1\" style='border-collapse: collapse;width:80%;margin-left:10%;height:50%;text-align: center;'>";
echo "<tr> \n";
echo "<th> </td>\n";
echo "<th colspan='2'>DT 101</td> \n";
echo "<th colspan='2'>DT 102</td> \n";
echo "<th colspan='2'>DT 103</td> \n";
echo "</tr> \n";

for ($i = 0; $i < 3; $i++) {
    $lk = $i + 1;
    echo "<tr> ";
    echo "<th >Roster $lk</th> ";

    for ($j = 0; $j < 3; $j++) {
        for ($k2 = 0; $k2 < 2; $k2++) {
            echo "<td>";
            for ($m = 0; $m < 2; $m++) {
                $lindex = isset($teacherinclass[$i][$j][$k2][$m]) ? $teacherinclass[$i][$j][$k2][$m] : 100;
                if ($lindex !== 100 && isset($te[$lindex]) && isset($te[$lindex]->name)) {
                    $z = htmlspecialchars($te[$lindex]->name, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                    echo " $z,";
                } else {
                    echo " ";
                }
            }
            echo "</td>";
        }
    }
    echo "</tr>";
}
echo "</table>";
?>
<!DOCTYPE html>
<html>
<head>
    <title>Time Table</title>
</head>
<body>
<br>
<center><button onclick="window.print()">PRINT</button></center>
</body>
</html>