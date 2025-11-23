<?php
ob_start();

/*
 Updated seatAllot.php — portrait layout with properly completed (fully bordered)
 Subject box and centered text (both vertically and horizontally).

 What changed:
 - The Subject cell border is explicitly drawn with Rect() so its border always
   matches the Register Numbers cell height.
 - The Subject text is vertically centered by computing the Y offset and then
   printing the subject with MultiCell (horizontal centering via 'C').
 - Register Numbers still use MultiCell with border=1 to allow multiline content.
 - Column widths remain tuned for portrait A4 (adjust if needed).
*/

for ($i = 0; $i < (isset($_SESSION['Details']['noClass']) ? $_SESSION['Details']['noClass'] : 0); $i++) {
    unset($_SESSION['class_' . $i]);
}

require_once('fpdf.php');

class pdf extends FPDF
{
    function Header()
    {
        $this->SetMargins(2, 2, 2);
        $this->Ln();
        $this->SetFont('Times', 'B', 24);
        $this->Cell(0, 1, "589,Chendhuran Polytecnic College", 0, 1, 'C');
        $this->SetFont('Times', 'B', 14);
        $this->Cell(0, 1, "Department Of Computer Science & Engineering", 0, 1, 'C');
        $this->Cell(0, 1, "Seating Arrangement", 0, 1, 'C');
        $this->SetFont('Times', 'B', 12);
        $date = date("d/m/Y");
        $this->Cell(0, 1, "Date: $date", 0, 1, 'C');
        $this->Ln();
    }

    function FancyTable($header, $data, $columnWidths)
    {
        $this->SetFont('Times', 'B', 12);
        // Print header row
        for ($i = 0; $i < count($header); $i++) {
            $this->Cell($columnWidths[$i], 1, $header[$i], 1, 0, 'C');
        }
        $this->Ln();

        $this->SetFont('Times', '', 12);
        // Print data rows
        foreach ($data as $row) {
            $x = $this->GetX();
            $y = $this->GetY();

            // Register numbers (column index 2) may be multiline
            $register_numbers = isset($row[2]) ? $row[2] : '';

            // use a consistent line height for MultiCell
            $lineHeight = 0.6; // cm

            // number of lines required for register numbers
            $regLines = max(1, $this->NbLines($columnWidths[2], $register_numbers));
            $register_height = $regLines * $lineHeight;

            // Subject text and its lines/height
            $subject_text = isset($row[3]) ? $row[3] : '';
            $subLines = max(1, $this->NbLines($columnWidths[3], $subject_text));
            $subject_text_height = $subLines * $lineHeight;

            // ensure the overall cell height is at least subject height too
            $cell_height = max($register_height, $subject_text_height);

            // Date cell
            $this->Cell($columnWidths[0], $cell_height, isset($row[0]) ? $row[0] : '', 1, 0, 'C');
            // Hall Number cell
            $this->Cell($columnWidths[1], $cell_height, isset($row[1]) ? $row[1] : '', 1, 0, 'C');

            // Register Numbers with MultiCell (bordered)
            // Save current position to compute subject cell position later
            $regX = $this->GetX();
            $regY = $this->GetY();
            $this->MultiCell($columnWidths[2], $lineHeight, $register_numbers, 1, 'L');

            // Subject cell X and Y
            $subjectX = $x + $columnWidths[0] + $columnWidths[1] + $columnWidths[2];
            $subjectY = $y;

            // Draw subject border rectangle explicitly so border fills full cell_height
            $this->Rect($subjectX, $subjectY, $columnWidths[3], $cell_height);

            // Compute vertical offset to center subject text
            $textY = $subjectY + (($cell_height - $subject_text_height) / 2);

            // Print subject text centered horizontally using MultiCell without border (border already drawn)
            $this->SetXY($subjectX, $textY);
            // MultiCell will wrap if necessary; using 0 border to avoid double border
            $this->MultiCell($columnWidths[3], $lineHeight, $subject_text, 0, 'C');

            // Move cursor to beginning of next row
            $this->SetXY($x, $y + $cell_height);
        }
    }

    // Helper to estimate number of lines for a multicell
    function NbLines($w, $txt)
    {
        $cw = &$this->CurrentFont['cw'];
        if ($w == 0)
            $w = $this->w - $this->rMargin - $this->x;
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if ($nb > 0 && $s[$nb - 1] == "\n")
            $nb--;
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;
        while ($i < $nb) {
            $c = $s[$i];
            if ($c == "\n") {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            }
            if ($c == ' ')
                $sep = $i;
            // guard against unknown character widths
            $charWidth = isset($cw[$c]) ? $cw[$c] : (isset($cw['0']) ? $cw['0'] : 500);
            $l += $charWidth;
            if ($l > $wmax) {
                if ($sep == -1) {
                    if ($i == $j)
                        $i++;
                } else
                    $i = $sep + 1;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            } else
                $i++;
        }
        return $nl;
    }

    function Footer()
    {
        $this->SetY(-3);
        $this->Cell(10, 0, "____________________");
        $this->SetX(-(6));
        $this->Cell(0, 0, "____________________");
        $this->SetY(-2);
        $this->Cell(4.2, 1, "Exam Controller", 0, 0, 'C');
        $this->SetX(-5.8);
        $this->Cell(4, 1, "Head Of the Department", 0, 0, 'C');
    }
}

function seatAllot($se, $te, $be)
{
    if (session_status() === PHP_SESSION_NONE) {
        @session_start();
    }

    $date = date("d/m/Y");
    $halls = [
        'SE' => $se,
        'TE' => $te,
        'BE' => $be
    ];

    $seSubject = isset($_SESSION['seSubject']) ? trim($_SESSION['seSubject']) : (isset($_SESSION['Details']['seSubject']) ? trim($_SESSION['Details']['seSubject']) : '');
    $teSubject = isset($_SESSION['teSubject']) ? trim($_SESSION['teSubject']) : (isset($_SESSION['Details']['teSubject']) ? trim($_SESSION['Details']['teSubject']) : '');
    $beSubject = isset($_SESSION['beSubject']) ? trim($_SESSION['beSubject']) : (isset($_SESSION['Details']['beSubject']) ? trim($_SESSION['Details']['beSubject']) : '');

    $subjects = [
        'SE' => $seSubject,
        'TE' => $teSubject,
        'BE' => $beSubject
    ];

    $header = ['Date', 'Hall Number', 'Register Numbers', 'Subject'];

    // Build data rows
    $data = [];
    foreach ($halls as $hallName => $arr) {
        if (!is_array($arr)) continue;
        $register_numbers = implode(", ", array_keys($arr));
        $subject_for_hall = isset($subjects[$hallName]) ? $subjects[$hallName] : '';
        $data[] = [$date, $hallName, $register_numbers, $subject_for_hall];
    }

    // Column widths for portrait A4 with 2cm side margins (usable width ~17 cm)
    $columnWidths = [3, 3, 8, 3];

    // Create portrait page
    $page = new pdf('P', 'cm', 'A4');
    $page->AddPage('P', 'A4', 0);

    $page->FancyTable($header, $data, $columnWidths);

    if (ob_get_length()) {
        ob_end_clean();
    }
    $page->Output('D', 'SeatingArrangement.pdf');
}