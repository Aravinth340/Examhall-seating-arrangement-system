<?php
ob_start();
session_start();

if(!isset($_SESSION['Details'])) {
    header('Location: homepage.php');
    exit;
}
require('seatAllot.php');

// Provide defaults if not set
$seDefaulter = isset($_SESSION['seDefaulter']) ? trim($_SESSION['seDefaulter']) : "0";
$teDefaulter = isset($_SESSION['teDefaulter']) ? trim($_SESSION['teDefaulter']) : "0";
$beDefaulter = isset($_SESSION['beDefaulter']) ? trim($_SESSION['beDefaulter']) : "0";

$se = array();
$seName = array();
$te = array();
$teName = array();
$be = array();
$beName = array();

$conn = mysqli_connect('localhost','root','') or die();
$db = mysqli_select_db($conn,'seatingarrangement');

// Helper function to build SQL based on defaulters
function getStudentSQL($table, $defaulters) {
    if ($defaulters === "0" || $defaulters === "" || preg_replace('/[,\s]/', '', $defaulters) === "") {
        // No defaulters, select all
        return "SELECT `Roll_No`, CONCAT(`first_name`, ' ', `last_name`) FROM `$table`";
    } else {
        return "SELECT `Roll_No`, CONCAT(`first_name`, ' ', `last_name`) FROM `$table` WHERE `Roll_No` NOT IN ($defaulters)";
    }
}

$sqlSE = getStudentSQL('secondyear', $seDefaulter);
$sqlTE = getStudentSQL('thirdyear', $teDefaulter);
$sqlBE = getStudentSQL('fourthyear', $beDefaulter);

$sqlResult1 = mysqli_query($conn, $sqlSE);
$sqlResult2 = mysqli_query($conn, $sqlBE);
$sqlResult3 = mysqli_query($conn, $sqlTE);

if($sqlResult1!=false) {
    while ($row = mysqli_fetch_array($sqlResult1)) {
        $seName[$row[0]]=$row[1];
    }
} else {
    echo "Error in Fetching data for SE";
}

if($sqlResult3!=false) {
    while ($row = mysqli_fetch_array($sqlResult3)) {
        $teName[$row[0]]=$row[1];
    }
} else {
    echo "Error in Fetching data for TE";
}

if($sqlResult2!=false) {
    while ($row = mysqli_fetch_array($sqlResult2)) {
        $beName[$row[0]]=$row[1];
    }
} else {
    echo "Error in Fetching data for BE";
}
mysqli_close($conn);

$seSlots = array_chunk($seName,34);
$teSlots = array_chunk($teName,34);
$beSlots = array_chunk($beName,34);

// --- AUTO-PICK SUBJECT NAMES FOR EACH HALL ---
// We look into the Time Table session arrays (SE, TE, BE) and pick the first subject found.
// The TimeTable forms store rows where array_values($row)[5] corresponds to Subject (Term, Date, From, To, Room, Subject).
// If the subject value is a code or short name, resolve it to the full subject Name from the subject table.

function pick_first_subject_from_timetable($ttArray) {
    if (!is_array($ttArray)) return '';
    foreach ($ttArray as $k => $row) {
        if (!is_array($row)) continue;
        $vals = array_values($row);
        if (isset($vals[5]) && trim($vals[5]) !== '') {
            return trim($vals[5]);
        }
    }
    return '';
}

// Resolve a subject code/shortname to the full subject Name using the subject table.
// Falls back to the original value when no mapping exists.
function resolve_subject_name($subjectVal) {
    $subjectVal = trim((string)$subjectVal);
    if ($subjectVal === '') return '';

    // Try to look up in DB (separate connection to avoid interfering with earlier queries)
    $conn = @mysqli_connect('localhost','root','');
    if (!$conn) {
        return $subjectVal; // can't connect, return original
    }
    mysqli_select_db($conn, 'seatingarrangement');

    // Try matching SubjectCode or ShortNames exactly first
    $sql = "SELECT `Name`, `ShortNames`, `SubjectCode` FROM `subject` WHERE `SubjectCode` = ? OR `ShortNames` = ? LIMIT 1";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "ss", $subjectVal, $subjectVal);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $name, $shortnames, $subcode);
        if (mysqli_stmt_fetch($stmt)) {
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            return trim($name !== '' ? $name : $shortnames);
        }
        mysqli_stmt_close($stmt);
    }

    // If not found, try searching by ShortNames containing the value (for partial matches)
    $likeVal = '%' . $subjectVal . '%';
    $sql = "SELECT `Name`, `ShortNames` FROM `subject` WHERE `ShortNames` LIKE ? OR `Name` LIKE ? LIMIT 1";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "ss", $likeVal, $likeVal);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $name2, $short2);
        if (mysqli_stmt_fetch($stmt)) {
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            return trim($name2 !== '' ? $name2 : $short2);
        }
        mysqli_stmt_close($stmt);
    }

    mysqli_close($conn);
    // No mapping found: return original
    return $subjectVal;
}

// pick and resolve for each level
$rawSeSubject = pick_first_subject_from_timetable(isset($_SESSION['SE']) ? $_SESSION['SE'] : []);
$rawTeSubject = pick_first_subject_from_timetable(isset($_SESSION['TE']) ? $_SESSION['TE'] : []);
$rawBeSubject = pick_first_subject_from_timetable(isset($_SESSION['BE']) ? $_SESSION['BE'] : []);

$_SESSION['seSubject'] = resolve_subject_name($rawSeSubject);
$_SESSION['teSubject'] = resolve_subject_name($rawTeSubject);
$_SESSION['beSubject'] = resolve_subject_name($rawBeSubject);

// Call your PDF generator
seatAllot($seName,$teName,$beName);
?>