<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

function convertNumberToWords($number)
{
    $words = array(
        0 => 'Zero',
        1 => 'One',
        2 => 'Two',
        3 => 'Three',
        4 => 'Four',
        5 => 'Five',
        6 => 'Six',
        7 => 'Seven',
        8 => 'Eight',
        9 => 'Nine',
        10 => 'Ten',
        11 => 'Eleven',
        12 => 'Twelve',
        13 => 'Thirteen',
        14 => 'Fourteen',
        15 => 'Fifteen',
        16 => 'Sixteen',
        17 => 'Seventeen',
        18 => 'Eighteen',
        19 => 'Nineteen',
        20 => 'Twenty',
        30 => 'Thirty',
        40 => 'Forty',
        50 => 'Fifty',
        60 => 'Sixty',
        70 => 'Seventy',
        80 => 'Eighty',
        90 => 'Ninety'
    );

    $units = ['', 'Thousand', 'Million', 'Billion', 'Trillion'];

    if ($number == 0) return 'Zero';

    $numStr = strval($number);
    $numGroups = array_reverse(str_split(str_pad($numStr, ceil(strlen($numStr) / 3) * 3, '0', STR_PAD_LEFT), 3));
    $textParts = [];

    foreach ($numGroups as $index => $group) {
        $num = intval($group);
        if ($num == 0) continue;

        $hundred = floor($num / 100);
        $remainder = $num % 100;
        $groupText = '';

        if ($hundred) {
            $groupText .= $words[$hundred] . ' Hundred';
        }
        if ($remainder) {
            if ($remainder < 20) {
                $groupText .= ($groupText ? ' ' : '') . $words[$remainder];
            } else {
                $groupText .= ($groupText ? ' ' : '') . $words[floor($remainder / 10) * 10];
                if ($remainder % 10) {
                    $groupText .= ' ' . $words[$remainder % 10];
                }
            }
        }
        if ($units[$index]) {
            $groupText .= ' ' . $units[$index];
        }
        array_unshift($textParts, $groupText);
    }

    return implode(' ', $textParts);
}

function convertNumberToKhmerWords($number)
{
    $khmerWords = array(
        0 => 'សូន្យ',
        1 => 'មួយ',
        2 => 'ពីរ',
        3 => 'បី',
        4 => 'បួន',
        5 => 'ប្រាំ',
        6 => 'ប្រាំមួយ',
        7 => 'ប្រាំពីរ',
        8 => 'ប្រាំបី',
        9 => 'ប្រាំបួន',
        10 => 'ដប់',
        11 => 'ដប់មួយ',
        12 => 'ដប់ពីរ',
        13 => 'ដប់បី',
        14 => 'ដប់បួន',
        15 => 'ដប់ប្រាំ',
        16 => 'ដប់ប្រាំមួយ',
        17 => 'ដប់ប្រាំពីរ',
        18 => 'ដប់ប្រាំបី',
        19 => 'ដប់ប្រាំបួន',
        20 => 'ម្ភៃ',
        30 => 'សាមសិប',
        40 => 'សែសិប',
        50 => 'ហាសិប',
        60 => 'ហុកសិប',
        70 => 'ចិតសិប',
        80 => 'ប៉ែតសិប',
        90 => 'កៅសិប'
    );

    $units = ['', 'ពាន់', 'លាន', 'ប៊ីលាន', 'ទ្រីលាន'];

    if ($number == 0) return 'សូន្យ';

    $numStr = strval($number);
    $numGroups = array_reverse(str_split(str_pad($numStr, ceil(strlen($numStr) / 3) * 3, '0', STR_PAD_LEFT), 3));
    $textParts = [];

    foreach ($numGroups as $index => $group) {
        $num = intval($group);
        if ($num == 0) continue;

        $hundred = floor($num / 100);
        $remainder = $num % 100;
        $groupText = '';

        if ($hundred) {
            $groupText .= $khmerWords[$hundred] . ' រយ';
        }
        if ($remainder) {
            if ($remainder < 20) {
                $groupText .= ($groupText ? ' ' : '') . $khmerWords[$remainder];
            } else {
                $groupText .= ($groupText ? ' ' : '') . $khmerWords[floor($remainder / 10) * 10];
                if ($remainder % 10) {
                    $groupText .= ' ' . $khmerWords[$remainder % 10];
                }
            }
        }
        if ($units[$index]) {
            $groupText .= ' ' . $units[$index];
        }
        array_unshift($textParts, $groupText);
    }

    return implode(' ', $textParts);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $inputNumber = $_POST['number'] ?? '';
    $error = '';
    $result = null;

    if (!ctype_digit($inputNumber)) {
        $error = "Please enter a valid number.";
    } else {
        $inputNumber = intval($inputNumber);
        $englishWords = convertNumberToWords($inputNumber) . ' Riel';
        $khmerWords = convertNumberToKhmerWords($inputNumber) . ' រៀល';
        $dollarAmount = $inputNumber / 4000;
        $dollars = ($dollarAmount == floor($dollarAmount)) ? number_format($dollarAmount, 0) . ' $' : number_format($dollarAmount, 2) . ' $';

        // Structuring the result
        $result = "
            <div class='result-row'><strong>Riel:</strong> $inputNumber → <strong>USD:</strong> $dollars</div>
            <div class='result-row'><strong>English:</strong> $englishWords</div>
            <div class='result-row'><strong>លេខខ្មែរ:</strong> $khmerWords</div>
        ";

        // Saving result to file more securely
        $file = @fopen("results.txt", "a");
        if ($file) {
            fwrite($file, date('Y-m-d H:i:s') . " - " . strip_tags($result) . "\n");
            fclose($file);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Number to Words Converter</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap');

        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #6dd5fa, #2980b9);
            background-size: 200% 200%;
            animation: gradientFlow 10s ease infinite;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        @keyframes gradientFlow {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        .container {
            background: #ffffff;
            padding: 40px 30px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 480px;
            text-align: center;
        }

        h2 {
            font-size: 1.8rem;
            color: #2c3e50;
            margin-bottom: 25px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        input[type="number"] {
            padding: 12px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 6px;
            transition: border-color 0.3s ease;
        }

        input[type="number"]:focus {
            border-color: #3498db;
            outline: none;
        }

        button {
            padding: 12px;
            font-size: 1rem;
            font-weight: 500;
            color: #fff;
            background: #3498db;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        button:hover {
            background: #2980b9;
            transform: translateY(-2px);
        }

        button:active {
            transform: translateY(0);
        }

        .result-container {
            margin-top: 25px;
            text-align: left;
        }

        .result-row {
            background: #ecf0f1;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 10px;
            font-size: 1rem;
            color: #34495e;
            transition: background 0.3s ease, color 0.3s ease;
        }

        .result-row:hover {
            background: #3498db;
            color: #fff;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2>សូមបញ្ចូលលេខដែលអ្នកចង់បម្លែង</h2>
        <form method="post">
            <input type="number" name="number" placeholder="បញ្ចូលលេខ" required>
            <button type="submit">បញ្ចូន</button>
        </form>

        <!-- HTML Display -->
        <div class="result-container">
            <?php if (isset($result)) echo $result; ?>
        </div>
    </div>

</body>

</html>